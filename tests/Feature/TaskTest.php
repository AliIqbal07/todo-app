<?php

use App\Models\Task;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('tasks.index'))->assertRedirect(route('login'));
});

test('users can create a task', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tasks.store'), [
            'title' => 'Write tests',
            'description' => 'Cover the main flows',
            'due_date' => now()->addDay()->toDateString(),
        ])
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', [
        'title' => 'Write tests',
        'user_id' => $user->id,
    ]);
});

test('a title is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tasks.store'), ['title' => ''])
        ->assertSessionHasErrors('title');

    $this->assertDatabaseCount('tasks', 0);
});

test('a new task cannot have a due date in the past', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tasks.store'), [
            'title' => 'Too late',
            'due_date' => now()->subDay()->toDateString(),
        ])
        ->assertSessionHasErrors('due_date');
});

test('users only see their own tasks', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Task::factory()->for($user)->create(['title' => 'My task']);
    Task::factory()->for($other)->create(['title' => 'Their task']);

    $this->actingAs($user)
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertSee('My task')
        ->assertDontSee('Their task');
});

test('users can update their own task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for($user)->create();

    $this->actingAs($user)
        ->put(route('tasks.update', $task), ['title' => 'Updated title'])
        ->assertRedirect(route('tasks.index'));

    expect($task->refresh()->title)->toBe('Updated title');
});

test('users can delete their own task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for($user)->create();

    $this->actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('users cannot edit or delete another users task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for(User::factory())->create();

    $this->actingAs($user)->get(route('tasks.edit', $task))->assertForbidden();
    $this->actingAs($user)->put(route('tasks.update', $task), ['title' => 'Hacked'])->assertForbidden();
    $this->actingAs($user)->delete(route('tasks.destroy', $task))->assertForbidden();

    $this->assertDatabaseHas('tasks', ['id' => $task->id]);
});

test('users can mark a task as complete and reopen it', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for($user)->create(['completed' => false]);

    $this->actingAs($user)->patch(route('tasks.toggle', $task));
    expect($task->refresh()->completed)->toBeTrue();

    $this->actingAs($user)->patch(route('tasks.toggle', $task));
    expect($task->refresh()->completed)->toBeFalse();
});

test('the filters show active and completed tasks', function () {
    $user = User::factory()->create();
    Task::factory()->for($user)->create(['title' => 'Open task', 'completed' => false]);
    Task::factory()->for($user)->create(['title' => 'Done task', 'completed' => true]);

    $this->actingAs($user)
        ->get(route('tasks.index', ['filter' => 'active']))
        ->assertSee('Open task')
        ->assertDontSee('Done task');

    $this->actingAs($user)
        ->get(route('tasks.index', ['filter' => 'completed']))
        ->assertSee('Done task')
        ->assertDontSee('Open task');
});

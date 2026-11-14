<x-layouts::app :title="__('Tasks')">
    <div class="mx-auto flex w-full max-w-3xl flex-col gap-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">My Tasks</h1>
            <a href="{{ route('tasks.create') }}" class="rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-neutral-900">New task</a>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex gap-2 text-sm">
            @foreach (['all' => 'All', 'active' => 'Active', 'completed' => 'Completed'] as $key => $label)
                <a href="{{ route('tasks.index', ['filter' => $key]) }}"
                    class="rounded-full border px-3 py-1 {{ $filter === $key ? 'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900' : 'border-neutral-300 dark:border-neutral-600' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @forelse ($tasks as $task)
            <div class="flex items-start justify-between gap-4 rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                <div>
                    <h2 class="font-medium {{ $task->completed ? 'line-through opacity-60' : '' }}">{{ $task->title }}</h2>

                    @if ($task->description)
                        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">{{ $task->description }}</p>
                    @endif

                    @if ($task->due_date)
                        <p class="mt-1 text-xs {{ ! $task->completed && $task->due_date->isBefore(today()) ? 'text-red-600' : 'text-neutral-500' }}">
                            Due {{ $task->due_date->format('d M Y') }}
                        </p>
                    @endif
                </div>

                <div class="flex shrink-0 items-center gap-3 text-sm">
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="underline">{{ $task->completed ? 'Reopen' : 'Complete' }}</button>
                    </form>

                    <a href="{{ route('tasks.edit', $task) }}" class="underline">Edit</a>

                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 underline">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-neutral-500">No tasks here yet.</p>
        @endforelse
    </div>
</x-layouts::app>
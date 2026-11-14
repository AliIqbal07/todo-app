<div class="flex flex-col gap-1">
    <label for="title" class="text-sm font-medium">Title</label>
    <input id="title" name="title" type="text" value="{{ old('title', $task?->title) }}" required
        class="rounded-lg border border-neutral-300 bg-transparent px-3 py-2 dark:border-neutral-600">
    @error('title')
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex flex-col gap-1">
    <label for="description" class="text-sm font-medium">Description</label>
    <textarea id="description" name="description" rows="4"
        class="rounded-lg border border-neutral-300 bg-transparent px-3 py-2 dark:border-neutral-600">{{ old('description', $task?->description) }}</textarea>
    @error('description')
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex flex-col gap-1">
    <label for="due_date" class="text-sm font-medium">Due date</label>
    <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $task?->due_date?->format('Y-m-d')) }}"
        class="rounded-lg border border-neutral-300 bg-transparent px-3 py-2 dark:border-neutral-600">
    @error('due_date')
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
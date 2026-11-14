<x-layouts::app :title="__('New task')">
    <div class="mx-auto flex w-full max-w-xl flex-col gap-6">
        <h1 class="text-2xl font-semibold">New task</h1>

        <form method="POST" action="{{ route('tasks.store') }}" class="flex flex-col gap-4">
            @csrf
            @include('tasks._fields', ['task' => null])

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-neutral-900">Save</button>
                <a href="{{ route('tasks.index') }}" class="text-sm underline">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts::app>
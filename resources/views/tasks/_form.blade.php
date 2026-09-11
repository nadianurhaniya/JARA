@props(['task' => null])

<div class="space-y-4">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Judul Tugas</label>
        <input
            type="text"
            name="title"
            id="title"
            value="{{ old('title', $task?->title) }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-400 focus:outline-none"
            required
        >
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea
            name="description"
            id="description"
            rows="3"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-400 focus:outline-none"
        >{{ old('description', $task?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Prioritas</label>
            <select
                name="priority"
                id="priority"
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-400 focus:outline-none"
            >
                @foreach (\App\Enums\TaskPriority::cases() as $priority)
                    <option
                        value="{{ $priority->value }}"
                        @selected(old('priority', $task?->priority?->value) === $priority->value)
                    >
                        {{ $priority->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700">Tenggat Waktu</label>
            <input
                type="date"
                name="due_date"
                id="due_date"
                value="{{ old('due_date', $task?->due_date?->format('Y-m-d')) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-400 focus:outline-none"
            >
        </div>
    </div>
</div>

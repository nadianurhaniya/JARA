@props(['taskList' => null])

<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Daftar/Proyek</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $taskList?->name) }}"
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
        >{{ old('description', $taskList?->description) }}</textarea>
    </div>
</div>

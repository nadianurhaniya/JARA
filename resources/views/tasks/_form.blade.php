@props(['task' => null])

<div class="space-y-4">
    <x-form.input label="Judul Tugas" name="title" :value="$task?->title" required autofocus />

    <div class="space-y-1.5">
        <label for="description" class="block text-xs font-semibold text-[#475569] uppercase tracking-wide">Deskripsi</label>
        <textarea
            name="description"
            id="description"
            rows="3"
            class="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] bg-white text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] hover:border-[#CBD5E1]"
        >{{ old('description', $task?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="space-y-1.5">
            <label for="priority" class="block text-xs font-semibold text-[#475569] uppercase tracking-wide">Prioritas</label>
            <select
                name="priority"
                id="priority"
                class="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] bg-white text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] hover:border-[#CBD5E1]"
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

        <x-form.input label="Tenggat Waktu" name="due_date" type="date" :value="$task?->due_date?->format('Y-m-d')" />
    </div>
</div>

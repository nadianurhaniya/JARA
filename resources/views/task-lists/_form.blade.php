@props(['taskList' => null])

<div class="space-y-4">
    <x-form.input label="Nama Daftar/Proyek" name="name" :value="$taskList?->name" required autofocus />

    <div class="space-y-1.5">
        <label for="description" class="block text-xs font-semibold text-[#475569] uppercase tracking-wide">Deskripsi</label>
        <textarea
            name="description"
            id="description"
            rows="3"
            @error('description') aria-invalid="true" aria-describedby="description-error" @enderror
            class="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] bg-white text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] hover:border-[#CBD5E1]"
        >{{ old('description', $taskList?->description) }}</textarea>
        @error('description')
            <p id="description-error" class="text-xs text-red-500 flex items-center gap-1">
                <span aria-hidden="true">⚠</span>{{ $message }}
            </p>
        @enderror
    </div>
</div>

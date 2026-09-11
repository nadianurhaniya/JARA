@props([])

<div class="relative" x-data="{ open: false }">
    <button
        @click="open = !open"
        class="inline-flex items-center gap-2 px-4 py-2 bg-[#0BC5C1] text-white text-sm font-medium rounded-xl hover:bg-[#0891B2] transition-colors"
    >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
            <polyline points="7 10 12 15 17 10" />
            <line x1="12" y1="15" x2="12" y2="3" />
        </svg>
        Export Report
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-[#E2E8F0] z-50 overflow-hidden"
    >
        <button
            @click="open = false; showToast('PDF export coming soon!')"
            class="w-full px-4 py-3 text-left text-sm text-[#1E293B] hover:bg-[#F8FAFC] flex items-center gap-3 transition-colors"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-[#EF4444]">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
            </svg>
            Export as PDF
        </button>
        <button
            @click="open = false; showToast('Excel export coming soon!')"
            class="w-full px-4 py-3 text-left text-sm text-[#1E293B] hover:bg-[#F8FAFC] flex items-center gap-3 transition-colors"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-[#10B981]">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                <line x1="9" y1="3" x2="9" y2="21" />
                <line x1="3" y1="9" x2="21" y2="9" />
            </svg>
            Export as Excel
        </button>
    </div>
</div>

<script>
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-[#1E293B] text-white px-4 py-3 rounded-xl shadow-lg text-sm z-50 transition-all duration-300';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

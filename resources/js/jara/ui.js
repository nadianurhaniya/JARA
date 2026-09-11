import {
    getState, getUser, currentUser, projectRole, isOwner, visibleProjects,
} from './store.js';

export function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

export function avatarInitials(name) {
    return name.split(' ').map((part) => part[0]).join('').slice(0, 2).toUpperCase();
}

export function roleBadge(role) {
    if (role === 'owner') {
        return '<span class="text-xs px-2 py-0.5 rounded-full font-medium bg-[#FEF3C7] text-[#D97706]">Owner</span>';
    }
    return '<span class="text-xs px-2 py-0.5 rounded-full font-medium bg-[#E8F9F9] text-[#0BC5C1]">Member</span>';
}

export function statusBadge(status) {
    const map = {
        not_started: { label: 'Not Started', bg: '#F1F5F9', fg: '#94A3B8' },
        in_progress: { label: 'In Progress', bg: '#E8F9F9', fg: '#0BC5C1' },
        completed: { label: 'Selesai', bg: '#ECFDF5', fg: '#065F46' },
    };
    const cfg = map[status] ?? map.not_started;
    return `<span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:${cfg.bg};color:${cfg.fg}">${cfg.label}</span>`;
}

export function priorityBadge(priority) {
    const map = {
        high: { label: 'High', bg: '#FEF2F2', fg: '#EF4444' },
        medium: { label: 'Medium', bg: '#FFFBEB', fg: '#F59E0B' },
        low: { label: 'Low', bg: '#ECFDF5', fg: '#10B981' },
    };
    const cfg = map[priority] ?? map.medium;
    return `<span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:${cfg.bg};color:${cfg.fg}">${cfg.label}</span>`;
}

export function avatar(user, size = 'w-10 h-10 text-xs') {
    return `<div class="${size} rounded-full bg-[#0BC5C1] flex items-center justify-center text-white font-bold shrink-0">${escapeHtml(user.avatar || avatarInitials(user.name))}</div>`;
}

export function inviteBadge(status) {
    const map = {
        pending: { label: 'Pending', bg: 'bg-[#FEF9C3] text-[#92400E]' },
        accepted: { label: 'Accepted', bg: 'bg-[#ECFDF5] text-[#065F46]' },
        rejected: { label: 'Rejected', bg: 'bg-red-50 text-red-600' },
    };
    const cfg = map[status] ?? map.pending;
    return `<span class="text-xs px-2.5 py-1 rounded-full font-medium ${cfg.bg}">${cfg.label}</span>`;
}

// --------------------------------------------------------------------------
// Auth page — login sebagai user demo
// --------------------------------------------------------------------------

export function renderAuthPage() {
    const { users } = getState();
    const demoUsers = ['u2', 'u3', 'u5', 'u6', 'u7'];

    return `
        <div class="min-h-screen bg-[#F0FAFA] flex">
            <div class="hidden lg:flex flex-col w-[420px] bg-[#0BC5C1] p-10 relative overflow-hidden shrink-0">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-10 left-10 w-40 h-40 rounded-full bg-white"></div>
                    <div class="absolute bottom-20 right-5 w-64 h-64 rounded-full bg-white"></div>
                    <div class="absolute top-1/2 left-1/4 w-20 h-20 rounded-full bg-white"></div>
                </div>
                <div class="relative z-10 flex-1 flex flex-col justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-lg">J</div>
                        <span class="font-display font-extrabold text-2xl text-white">JARA</span>
                    </div>
                    <div>
                        <h2 class="font-display font-extrabold text-3xl text-white leading-tight mb-4">Kelola tugas.<br />Berkolaborasi dengan tim.</h2>
                        <p class="text-white/80 text-sm leading-relaxed">JARA membantu kamu mengorganisasi proyek, melacak deadline, dan berkolaborasi dengan tim — semua dalam satu tempat.</p>
                        <div class="mt-8 space-y-3">
                            ${['Buat & kelola proyek', 'Tangani undangan kolaborasi', 'Berbagi tugas dengan member', 'Pantau notifikasi'].map((f) => `
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" class="w-3 h-3"><path d="M20 6L9 17l-5-5"/></svg>
                                    </div>
                                    <span class="text-white/90 text-sm">${f}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <p class="text-white/50 text-xs">© 2026 JARA. All rights reserved.</p>
                </div>
            </div>

            <div class="flex-1 flex items-center justify-center p-6 overflow-y-auto">
                <div class="w-full max-w-md">
                    <div class="lg:hidden flex items-center gap-2 justify-center mb-8">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold" style="background:#0BC5C1">J</div>
                        <span class="font-display font-extrabold text-2xl text-[#1E293B]">JARA</span>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-[#E2E8F0]">
                        <div class="mb-6">
                            <h2 class="font-display font-extrabold text-2xl text-[#1E293B]">Collaboration Demo</h2>
                            <p class="text-sm text-[#64748B] mt-1">Pilih user untuk mengeksplorasi modul Kolaborasi & Kepemilikan</p>
                        </div>

                        <p class="text-xs font-semibold text-[#475569] uppercase tracking-wide mb-2">Akun demo</p>
                        <div class="space-y-2 mb-6">
                            ${demoUsers.map((id) => {
                                const user = getUser(id);
                                const role = id === 'u2' ? 'Owner' : 'Member';
                                return `
                                    <button data-action="login" data-user="${id}"
                                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-[#E2E8F0] hover:border-[#0BC5C1] hover:bg-[#E8F9F9] text-left transition-all group">
                                        ${avatar(user, 'w-9 h-9 text-xs')}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-[#1E293B] truncate">${escapeHtml(user.name)}</p>
                                            <p class="text-xs text-[#94A3B8] truncate">${escapeHtml(user.email)}</p>
                                        </div>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium ${role === 'Owner' ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-[#E8F9F9] text-[#0BC5C1]'}">${role}</span>
                                    </button>
                                `;
                            }).join('')}
                        </div>

                        <div class="p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <p class="text-xs text-[#64748B] leading-relaxed">
                                Gunakan <strong class="text-[#1E293B]">Budi Hartono</strong> (Owner) untuk menguji undang/hapus member, assignment, dan notifikasi.
                                Gunakan <strong class="text-[#1E293B]">Farah Nadia</strong> untuk menerima/menolak undangan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// --------------------------------------------------------------------------
// App shell — sidebar, header, notifications, role bar
// --------------------------------------------------------------------------

export function renderSidebar() {
    const user = currentUser();
    const { notifications } = getState();
    const unread = notifications.filter((n) => n.userId === user.id && !n.read).length;

    return `
        <aside class="w-16 md:w-56 h-full flex flex-col bg-white border-r border-[#E2E8F0] shrink-0">
            <div class="h-16 flex items-center px-4 border-b border-[#E2E8F0]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm" style="background:linear-gradient(135deg,#0BC5C1,#0891B2)">J</div>
                    <span class="hidden md:block font-display font-extrabold text-lg text-[#1E293B] tracking-tight">JARA</span>
                </div>
            </div>

            <nav class="flex-1 py-4 px-2">
                <div class="space-y-1">
                    <div class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium bg-[#E8F9F9] text-[#0BC5C1]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 shrink-0"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        <span class="hidden md:block">Team</span>
                        <span class="hidden md:block ml-auto w-1.5 h-1.5 rounded-full bg-[#0BC5C1]"></span>
                    </div>
                </div>
            </nav>

            <div class="p-3 border-t border-[#E2E8F0]">
                <div class="w-full flex items-center gap-3 px-2 py-2 rounded-xl">
                    <div class="relative">
                        ${avatar(user, 'w-8 h-8 text-xs')}
                        ${unread > 0 ? `<span class="absolute -top-1 -right-1 min-w-4 h-4 bg-red-500 rounded-full text-white text-[10px] flex items-center justify-center font-bold px-0.5">${unread}</span>` : ''}
                    </div>
                    <div class="hidden md:block text-left min-w-0">
                        <p class="text-xs font-semibold text-[#1E293B] truncate">${escapeHtml(user.name)}</p>
                        <p class="text-xs text-[#94A3B8] truncate">${projectRoleName()}</p>
                    </div>
                </div>
            </div>
        </aside>
    `;
}

function projectRoleName() {
    const user = currentUser();
    if (!user) return '';
    if (user.role === 'admin') return 'Administrator';
    const ownsProject = getState().projects.some((p) => p.ownerId === user.id);
    return ownsProject ? 'Owner' : 'Member';
}

export function renderHeader(activeProject) {
    const user = currentUser();
    const { notifications } = getState();
    const userNotifs = notifications.filter((n) => n.userId === user.id);
    const unread = userNotifs.filter((n) => !n.read).length;

    const notifIcon = {
        invite: '👥',
        assignment: '📋',
        removed: '🚫',
    };

    return `
        <header class="h-16 px-6 flex items-center justify-between border-b border-[#E2E8F0] bg-white shrink-0 relative z-20">
            <div>
                <h1 class="font-display font-bold text-xl text-[#1E293B]">Team Collaboration</h1>
                <p class="text-xs text-[#94A3B8] mt-0.5">
                    ${activeProject ? `Members, invitations, and assignments — <strong class="text-[#0BC5C1] font-medium">${escapeHtml(activeProject.name)}</strong>` : 'Members, invitations, and assignments'}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative" data-notif-panel>
                    <button data-action="toggle-notif"
                        class="relative w-9 h-9 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center text-[#64748B] hover:bg-[#E8F9F9] hover:text-[#0BC5C1] hover:border-[#0BC5C1]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                        ${unread > 0 ? `<span class="absolute -top-1 -right-1 min-w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center font-bold px-0.5">${unread}</span>` : ''}
                    </button>

                    ${stateUI.notifOpen ? `
                        <div class="absolute right-0 top-11 w-80 bg-white rounded-2xl shadow-xl border border-[#E2E8F0] z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-[#E2E8F0] flex items-center justify-between">
                                <h3 class="font-display font-bold text-sm text-[#1E293B]">Notifications</h3>
                                ${unread > 0 ? '<span class="text-xs text-[#0BC5C1]">' + unread + ' unread</span>' : ''}
                            </div>
                            <div class="max-h-72 overflow-y-auto">
                                ${userNotifs.length === 0
                                    ? '<p class="px-4 py-6 text-sm text-[#94A3B8] text-center">Belum ada notifikasi</p>'
                                    : userNotifs.map((n) => `
                                        <button data-action="mark-notif" data-notif="${n.id}"
                                            class="w-full px-4 py-3 flex gap-3 text-left hover:bg-[#F8FAFC] border-b border-[#F1F5F9] last:border-0 ${n.read ? '' : 'bg-[#F0FAFA]'}">
                                            <span class="text-base shrink-0 mt-0.5">${notifIcon[n.type] ?? '💬'}</span>
                                            <div class="min-w-0">
                                                <p class="text-xs text-[#1E293B] leading-relaxed">${escapeHtml(n.message)}</p>
                                                <p class="text-xs text-[#94A3B8] mt-1">${escapeHtml(n.createdAt)}</p>
                                            </div>
                                            ${!n.read ? '<span class="w-2 h-2 rounded-full bg-[#0BC5C1] shrink-0 mt-1.5"></span>' : ''}
                                        </button>
                                    `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>

                ${avatar(user, 'w-8 h-8 text-xs')}
            </div>
        </header>
    `;
}

export const stateUI = {
    notifOpen: false,
};
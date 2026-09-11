import { subscribe, getState, ensureSession, currentUser } from './store.js';

import * as actions from './store.js';
import { renderCollaborationLayout, renderModals } from './collaboration.js';
import { stateUI } from './ui.js';

function getRoot() {
    return document.getElementById('jara-app');
}

function render() {
    const root = getRoot();
    if (!root) return;
    ensureSession();
    const state = getState();

    if (!state.currentUserId) {
        root.innerHTML = `
            <div class="flex items-center justify-center p-6">
                <div class="text-center">
                    <p class="font-display font-bold text-[#1E293B]">Sesi tidak ditemukan</p>
                    <p class="text-sm text-[#94A3B8] mt-1">Silakan masuk kembali untuk membuka kolaborasi.</p>
                    <a href="/login" class="mt-4 inline-block px-4 py-2 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">Ke Halaman Masuk</a>
                </div>
            </div>
        `;
        return;
    }
    // Rendered INSIDE layouts.app (#jara-app sits beside the original sidebar,
    // inside the main content column). No custom full-page sidebar/header here.
    root.innerHTML = `
        <div class="flex flex-col gap-4">
            ${renderToolbar()}
            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl overflow-hidden">
                <div class="flex min-h-[560px] max-h-[calc(100vh-260px)] min-w-0">
                    ${renderCollaborationLayout()}
                </div>
            </div>
        </div>
        ${renderModals()}
        ${state.toast ? renderToast(state.toast) : ''}
    `;
}

function renderToolbar() {
    const user = currentUser();
    const { notifications } = getState();
    if (!user) return '';
    const userNotifs = notifications.filter((n) => n.userId === user.id);
    const unread = userNotifs.filter((n) => !n.read).length;

    const notifIcon = {
        invite: '👥',
        assignment: '📋',
        removed: '🚫',
    };

    return `
        <div class="flex items-center justify-end gap-3 flex-wrap">
            <div class="relative" data-notif-panel>
                <button data-action="toggle-notif"
                    class="relative w-9 h-9 rounded-xl bg-white border border-[#E2E8F0] flex items-center justify-center text-[#64748B] hover:bg-[#E8F9F9] hover:text-[#0BC5C1] hover:border-[#0BC5C1]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                    ${unread > 0 ? `<span class="absolute -top-1 -right-1 min-w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center font-bold px-0.5">${unread}</span>` : ''}
                </button>

                ${stateUI.notifOpen ? `
                    <div class="absolute right-0 top-11 w-80 max-w-[80vw] bg-white rounded-2xl shadow-xl border border-[#E2E8F0] z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-[#E2E8F0] flex items-center justify-between">
                            <h3 class="font-display font-bold text-sm text-[#1E293B]">Notifikasi</h3>
                            ${unread > 0 ? '<span class="text-xs text-[#0BC5C1]">' + unread + ' belum dibaca</span>' : ''}
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
        </div>
    `;
}

function renderToast(toast) {
    const styles = {
        error: { bg: '#FEF2F2', border: '#FECACA', text: '#B91C1C', icon: '⚠' },
        success: { bg: '#ECFDF5', border: '#A7F3D0', text: '#065F46', icon: '✓' },
    };
    const cfg = styles[toast.kind] ?? styles.error;
    return `
        <div class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-xl border flex items-center gap-2 text-sm shadow-lg" style="background:${cfg.bg};border-color:${cfg.border};color:${cfg.text}">
            <span>${cfg.icon}</span>
            ${escapeHtml(toast.message)}
        </div>
    `;
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

// Keep the address bar in sync when switching tabs, without full page reloads.
function syncTabToUrl(tab) {
    try {
        const url = new URL(window.location.href);
        if (url.pathname.startsWith('/collaborations')) {
            url.pathname = tab === 'members' ? '/collaborations' : `/collaborations/${tab}`;
            window.history.replaceState(null, '', url);
        } else if (url.pathname.startsWith('/jara')) {
            url.pathname = tab === 'members' ? '/jara' : `/jara/${tab}`;
            window.history.replaceState(null, '', url);
        }
    } catch {
        // ignore URL sync errors (e.g. non-http contexts)
    }
}

function tabFromPath() {
    const path = window.location.pathname;
    if (path.endsWith('/invitations')) return 'invitations';
    if (path.endsWith('/tasks')) return 'tasks';
    if (path.endsWith('/members')) return 'members';
    return null;
}

function initCollaboration() {
    const root = getRoot();
    if (!root) return;
    subscribe(render);
    render();

    // Back/forward buttons restore the tab without reloading the whole page.
    window.addEventListener('popstate', () => {
        const tab = tabFromPath();
        if (tab) actions.setActiveTab(tab);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCollaboration, { once: true });
} else {
    initCollaboration();
}

// Klik pada backdrop (di luar konten modal) selalu menutup modal.
// Klik di dalam konten hanya menutup bila menekan kontrol eksplisit
// (tombol/link), bukan saat menekan teks atau area kosong konten.
function shouldCloseModal(event, target) {
    if (!event.target.closest('[data-stop]')) return true;
    return target.matches('button, a, input, select, textarea, [role="button"]');
}

document.body.addEventListener('click', (event) => {
    if (!getRoot()) return;
    const target = event.target.closest('[data-action]');
    if (!target) return;

    const action = target.dataset.action;

    switch (action) {
        case 'toggle-notif':
            stateUI.notifOpen = !stateUI.notifOpen;
            render();
            break;
        case 'mark-notif':
            actions.markNotifRead(target.dataset.notif);
            break;
        case 'select-project':
            actions.setActiveProject(target.dataset.project);
            break;
        case 'select-tab':
            actions.setActiveTab(target.dataset.tab);
            syncTabToUrl(target.dataset.tab);
            break;
        case 'open-invite':
            actions.openInviteModal();
            break;
        case 'close-invite':
            if (!shouldCloseModal(event, target)) return;
            actions.closeInviteModal();
            break;
        case 'pick-invite':
            actions.setInviteDraft(target.dataset.user);
            break;
        case 'send-invite':
            actions.inviteUser();
            break;
        case 'open-remove':
            actions.openRemoveConfirm(target.dataset.user);
            break;
        case 'close-remove':
            if (!shouldCloseModal(event, target)) return;
            actions.closeRemoveConfirm();
            break;
        case 'confirm-remove':
            actions.removeMember();
            break;
        case 'open-delete-project':
            actions.openDeleteProjectConfirm();
            break;
        case 'close-delete-project':
            if (!shouldCloseModal(event, target)) return;
            actions.closeDeleteProjectConfirm();
            break;
        case 'confirm-delete-project':
            actions.deleteProject();
            break;
        case 'respond-invite':
            actions.respondInvitation(target.dataset.invite, target.dataset.accept === '1');
            break;
        case 'cancel-invite':
            actions.cancelInvitation(target.dataset.invite);
            break;
        case 'open-new-task':
            actions.openNewTaskModal();
            break;
        case 'close-new-task':
            if (!shouldCloseModal(event, target)) return;
            actions.closeNewTaskModal();
            break;
        case 'open-new-project':
            actions.openNewProjectModal();
            break;
        case 'close-new-project':
            if (!shouldCloseModal(event, target)) return;
            actions.closeNewProjectModal();
            break;
        case 'pick-project-color':
            snapshotNewProjectDraft();
            actions.setNewProjectColor(target.dataset.color);
            restoreNewProjectFocus();
            break;
        default:
            break;
    }
});

document.body.addEventListener('input', (event) => {
    if (!getRoot()) return;
    const form = event.target.closest('form[data-form="new-project"]');
    if (!form || !(event.target instanceof HTMLInputElement || event.target instanceof HTMLTextAreaElement)) return;
    const field = event.target.getAttribute('name');
    if (field !== 'name' && field !== 'description' && field !== 'deadline') return;
    actions.setNewProjectDraft({ [field]: event.target.value });
});

function snapshotNewProjectDraft() {
    const form = getRoot()?.querySelector('form[data-form="new-project"]');
    if (!form) return;
    actions.setNewProjectDraft({
        name: form.elements.namedItem('name')?.value ?? '',
        description: form.elements.namedItem('description')?.value ?? '',
        deadline: form.elements.namedItem('deadline')?.value ?? '',
    });
}

function restoreNewProjectFocus() {
    const nameInput = getRoot()?.querySelector('form[data-form="new-project"] input[name="name"]');
    if (nameInput instanceof HTMLElement) nameInput.focus({ preventScroll: true });
}

document.body.addEventListener('change', (event) => {
    if (!getRoot()) return;
    const select = event.target.closest('[data-action]');
    if (!select) return;

    if (select.dataset.action === 'assign-task') {
        actions.setAssignee(select.dataset.task, select.value);
    } else if (select.dataset.action === 'change-status') {
        actions.changeTaskStatus(select.dataset.task, select.value);
    }
});

document.body.addEventListener('submit', (event) => {
    if (!getRoot()) return;
    if (event.target.dataset.form === 'new-project') {
        event.preventDefault();
        const formData = new FormData(event.target);
        actions.createProject({
            name: formData.get('name'),
            description: formData.get('description'),
            deadline: formData.get('deadline'),
        });
        return;
    }
    if (event.target.dataset.form !== 'new-task') return;
    event.preventDefault();
    const formData = new FormData(event.target);
    actions.createTask({
        title: formData.get('title'),
        priority: formData.get('priority'),
        deadline: formData.get('deadline'),
    });
});

import { subscribe, getState } from './store.js';

import * as actions from './store.js';
import { renderCollaborationLayout, renderModals } from './collaboration.js';
import { renderSidebar, renderHeader, stateUI } from './ui.js';

const root = document.getElementById('jara-app');

function render() {
    const state = getState();

    if (!state.currentUserId) {
        root.innerHTML = `
            <div class="min-h-screen flex items-center justify-center bg-[#F0FAFA] p-6">
                <div class="text-center">
                    <p class="font-display font-bold text-[#1E293B]">Sesi tidak ditemukan</p>
                    <p class="text-sm text-[#94A3B8] mt-1">Silakan login kembali untuk membuka kolaborasi.</p>
                    <a href="/login" class="mt-4 inline-block px-4 py-2 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">Ke Halaman Login</a>
                </div>
            </div>
        `;
        return;
    }

    const activeProject = state.projects.find((p) => p.id === state.activeProjectId);

    root.innerHTML = `
        <div class="h-screen w-full flex overflow-hidden bg-[#F0FAFA]">
            ${renderSidebar()}
            <div class="flex-1 flex flex-col overflow-hidden">
                ${renderHeader(activeProject)}
                <div class="min-h-0 flex-1 flex flex-col">
                    ${renderCollaborationLayout()}
                </div>
            </div>
            ${renderModals()}
            ${state.toast ? renderToast(state.toast) : ''}
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

subscribe(render);
render();

// Klik pada backdrop (di luar konten modal) selalu menutup modal.
// Klik di dalam konten hanya menutup bila menekan kontrol eksplisit
// (tombol/link), bukan saat menekan teks atau area kosong konten.
function shouldCloseModal(event, target) {
    if (!event.target.closest('[data-stop]')) return true;
    return target.matches('button, a, input, select, textarea, [role="button"]');
}

document.body.addEventListener('click', (event) => {
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
            actions.setNewProjectColor(target.dataset.color);
            break;
        default:
            break;
    }
});

document.body.addEventListener('change', (event) => {
    const select = event.target.closest('[data-action]');
    if (!select) return;

    if (select.dataset.action === 'assign-task') {
        actions.setAssignee(select.dataset.task, select.value);
    } else if (select.dataset.action === 'change-status') {
        actions.changeTaskStatus(select.dataset.task, select.value);
    }
});

document.body.addEventListener('submit', (event) => {
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
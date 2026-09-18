import { USERS, PROJECTS, TASKS, NOTIFICATIONS } from './mock.js';

const TABS = ['members', 'invitations', 'tasks'];

function initialTab() {
    const tab = typeof window !== 'undefined' ? window.JARA_INITIAL_TAB : null;
    return TABS.includes(tab) ? tab : 'members';
}

const state = {
    currentUserId: null,
    users: structuredClone(USERS),
    projects: structuredClone(PROJECTS),
    tasks: structuredClone(TASKS),
    notifications: structuredClone(NOTIFICATIONS),
    activeProjectId: PROJECTS[0].id,
    activeTab: initialTab(),
    inviteModal: false,
    inviteError: null,
    inviteSuccess: null,
    assigneeDraft: null,
    newTaskModal: false,
    newTaskError: null,
    removeTarget: null,
    deleteProjectConfirm: false,
    newProjectModal: false,
    newProjectError: null,
    newProjectColor: '#0BC5C1',
    newProjectDraft: { name: '', description: '', deadline: '' },
    toast: null,
};

const listeners = new Set();

function sessionUser() {
    return typeof window !== 'undefined' ? (window.JARA_SESSION_USER ?? null) : null;
}

function initials(name) {
    return String(name ?? '?')
        .trim()
        .split(/\s+/)
        .map((part) => part[0])
        .join('')
        .slice(0, 2)
        .toUpperCase();
}

// Samakan akun Laravel yang login dengan mock user berdasarkan email.
// Jika tidak cocok (mis. user baru hasil registrasi), buatkan identitas
// session agar langsung masuk aplikasi dengan daftar proyek kosong.
function resolveSessionUser() {
    const session = sessionUser();
    if (!session?.email) return null;

    const email = String(session.email).toLowerCase();
    const matched = state.users.find((u) => u.email.toLowerCase() === email);
    if (matched) return matched.id;

    state.users.push({
        id: 'u-session',
        name: session.name || session.email,
        email: session.email,
        role: 'user',
        status: 'active',
        avatar: initials(session.name || session.email),
        joinedAt: new Date().toISOString().slice(0, 10),
        lastActive: new Date().toISOString().slice(0, 10),
    });
    return 'u-session';
}

function initSession() {
    const resolved = resolveSessionUser();
    if (resolved) {
        state.currentUserId = resolved;
    }
    state.activeProjectId = visibleProjects()[0]?.id ?? null;
}

initSession();

export function ensureSession() {
    if (!state.currentUserId) {
        initSession();
    }
}

function emit() {
    listeners.forEach((fn) => fn(state));
}

export function subscribe(fn) {
    listeners.add(fn);
    return () => listeners.delete(fn);
}

export function getState() {
    return state;
}

export function getUser(id) {
    return state.users.find((u) => u.id === id) || null;
}

export function currentUser() {
    return getUser(state.currentUserId);
}

export function projectRole(projectId, userId) {
    const project = state.projects.find((p) => p.id === projectId);
    if (!project) return null;
    if (project.ownerId === userId) return 'owner';
    return project.members.find((m) => m.userId === userId)?.role ?? null;
}

export function isOwner(projectId) {
    return projectRole(projectId, state.currentUserId) === 'owner';
}

export function isMember(projectId) {
    return projectRole(projectId, state.currentUserId) === 'member';
}

export function visibleProjects() {
    return state.projects.filter(
        (p) => p.ownerId === state.currentUserId || p.members.some((m) => m.userId === state.currentUserId),
    );
}

function notify(userId, type, message) {
    state.notifications.unshift({
        id: `n${Date.now()}${Math.random().toString(36).slice(2, 6)}`,
        userId,
        type,
        message,
        read: false,
        createdAt: new Date().toLocaleString('id-ID', { hour12: false }),
    });
}

function toast(message, kind = 'error') {
    state.toast = { message, kind };
    emit();
    setTimeout(() => {
        state.toast = null;
        emit();
    }, 4000);
}

// --- FR-19: Invite member (owner only) ---------------------------------

export function openInviteModal() {
    state.inviteModal = true;
    state.inviteError = null;
    state.inviteSuccess = null;
    state.assigneeDraft = getUserByIdForInviteCandidate();
    emit();
}

export function closeInviteModal() {
    state.inviteModal = false;
    state.inviteError = null;
    state.inviteSuccess = null;
    emit();
}

function getUserByIdForInviteCandidate() {
    const project = state.projects.find((p) => p.id === state.activeProjectId);
    if (!project) return '';
    return '';
}

export function setInviteDraft(userId) {
    state.assigneeDraft = userId;
    state.inviteError = null;
    emit();
}

export function inviteUser() {
    const project = state.projects.find((p) => p.id === state.activeProjectId);
    if (!project) return;

    if (project.ownerId !== state.currentUserId) {
        toast('Hanya Pemilik yang dapat mengundang anggota.');
        return;
    }

    if (state.inviteSuccess) return;

    const userId = state.assigneeDraft;
    const target = getUser(userId);

    if (!target) {
        state.inviteError = 'Pilih pengguna yang akan diundang dari daftar.';
        emit();
        return;
    }

    // Pemilik tidak dapat mengundang dirinya sendiri
    if (userId === state.currentUserId) {
        state.inviteError = 'Pemilik tidak dapat mengundang dirinya sendiri.';
        emit();
        return;
    }

    // pengguna yang sudah menjadi anggota tidak dapat diundang lagi
    const alreadyMember = project.members.some((m) => m.userId === userId);
    if (alreadyMember) {
        state.inviteError = `"${target.name}" sudah menjadi anggota proyek ini.`;
        emit();
        return;
    }

    // pengguna dengan undangan tertunda tidak bisa menerima undangan duplikat
    const pendingInvite = project.invitations.some(
        (inv) => inv.userId === userId && inv.status === 'pending',
    );
    if (pendingInvite) {
        state.inviteError = `"${target.name}" sudah memiliki undangan tertunda.`;
        emit();
        return;
    }

    const invitation = {
        id: `inv${Date.now()}`,
        projectId: project.id,
        userId,
        email: target.email,
        status: 'pending',
        sentAt: new Date().toISOString().slice(0, 10),
    };
    project.invitations.push(invitation);
    state.inviteSuccess = `Undangan dikirim ke ${target.name}`;
    notify(userId, 'invite', `Kamu diundang untuk bergabung ke "${project.name}"`);
    emit();
}

export function cancelInvitation(invitationId) {
    const project = state.projects.find((p) => p.id === state.activeProjectId);
    if (!project) return;
    if (project.ownerId !== state.currentUserId) {
        toast('Hanya Pemilik yang dapat membatalkan undangan.');
        return;
    }
    project.invitations = project.invitations.filter((inv) => inv.id !== invitationId);
    emit();
}

// --- FR-21: Accept / Reject invitation ---------------------------------

export function respondInvitation(invitationId, accept) {
    for (const project of state.projects) {
        const invitation = project.invitations.find((inv) => inv.id === invitationId);
        if (!invitation) continue;

        // hanya pengguna yang menerima undangan yang dapat merespons
        if (invitation.userId !== state.currentUserId) {
            toast('Kamu tidak dapat merespons undangan milik orang lain.');
            return;
        }

        // undangan yang sudah diterima/ditolak tidak dapat diproses kembali
        if (invitation.status !== 'pending') {
            const statusLabel = invitation.status === 'accepted' ? 'diterima' : 'ditolak';
            toast(`Undangan ini sudah ${statusLabel}.`);
            return;
        }

        if (accept) {
            invitation.status = 'accepted';
            const alreadyMember = project.members.some((m) => m.userId === invitation.userId);
            if (!alreadyMember) {
                project.members.push({
                    userId: invitation.userId,
                    role: 'member',
                    joinedAt: new Date().toISOString().slice(0, 10),
                });
            }
            notify(project.ownerId, 'invite', `${getUser(invitation.userId).name} menerima undangan ke "${project.name}"`);
        } else {
            invitation.status = 'rejected';
            notify(project.ownerId, 'invite', `${getUser(invitation.userId).name} menolak undangan ke "${project.name}"`);
        }
        emit();
        return;
    }
    toast('Undangan tidak ditemukan.');
}

// --- FR-20: Remove member (hanya pemilik) ---------------------------------

export function openRemoveConfirm(userId) {
    state.removeTarget = userId;
    emit();
}

export function closeRemoveConfirm() {
    state.removeTarget = null;
    emit();
}

export function removeMember() {
    const project = state.projects.find((p) => p.id === state.activeProjectId);
    if (!project) return;

    if (project.ownerId !== state.currentUserId) {
        toast('Hanya Pemilik yang dapat menghapus anggota.');
        return;
    }

    const userId = state.removeTarget;
    const member = project.members.find((m) => m.userId === userId);

    // Pemilik tidak dapat menghapus dirinya sendiri
    if (!member || member.role === 'owner') {
        toast('Pemilik tidak dapat menghapus dirinya sendiri.');
        return;
    }

    const removedUser = getUser(userId);
    const removedName = removedUser ? removedUser.name : 'Anggota';
    project.members = project.members.filter((m) => m.userId !== userId);

    // penugasan tugas milik anggota menjadi belum ditugaskan
    state.tasks = state.tasks.map((task) =>
        task.projectId === project.id && task.assigneeId === userId
            ? { ...task, assigneeId: null }
            : task,
    );

    notify(userId, 'removed', `Kamu dihapus dari proyek "${project.name}"`);
    state.removeTarget = null;
    emit();
    toast(`"${removedName}" dihapus dari proyek.`, 'success');
}

// --- FR-22: Menugaskan tugas (hanya pemilik) -----------------------------------

export function setAssignee(taskId, assigneeId) {
    const task = state.tasks.find((t) => t.id === taskId);
    if (!task) return;
    const project = state.projects.find((p) => p.id === task.projectId);
    if (!project) return;

    if (project.ownerId !== state.currentUserId) {
        toast('Hanya Pemilik yang dapat menugaskan tugas.');
        return;
    }

    if (assigneeId) {
        // penerima tugas harus anggota aktif proyek
        const member = project.members.find((m) => m.userId === assigneeId);
        if (!member) {
            toast('Pengguna tersebut bukan anggota aktif proyek ini.');
            return;
        }
    }

    const previous = task.assigneeId;
    task.assigneeId = assigneeId || null;

    if (assigneeId && assigneeId !== previous) {
        notify(assigneeId, 'assignment', `Task "${task.title}" ditugaskan kepadamu`);
    }
    emit();
}

// --- FR-23: Hanya assignee yang boleh mengubah status tugas -----------------
// Owner read-only untuk task anggota (monitoring + assignment saja, FR-22).
// Member hanya boleh mengubah status task yang ditugaskan kepadanya.

export function changeTaskStatus(taskId, status) {
    const task = state.tasks.find((t) => t.id === taskId);
    if (!task) return;
    const project = state.projects.find((p) => p.id === task.projectId);
    if (!project) return;

    // Berlaku untuk semua role (owner maupun member): wajib assignee sendiri.
    if (task.assigneeId !== state.currentUserId) {
        toast('Kamu hanya dapat mengubah status tugas yang ditugaskan kepadamu.');
        return;
    }

    task.status = status;
    emit();
}

// --- FR-24: Hapus proyek (hanya pemilik) ----------------------------------

export function openDeleteProjectConfirm() {
    state.deleteProjectConfirm = true;
    emit();
}

export function closeDeleteProjectConfirm() {
    state.deleteProjectConfirm = false;
    emit();
}

export function deleteProject() {
    const project = state.projects.find((p) => p.id === state.activeProjectId);
    if (!project) return;

    if (project.ownerId !== state.currentUserId) {
        toast('Hanya Pemilik yang dapat menghapus proyek.');
        return;
    }

    const deletedName = project.name;
    state.projects = state.projects.filter((p) => p.id !== project.id);
    state.tasks = state.tasks.filter((t) => t.projectId !== project.id);

    state.activeProjectId = visibleProjects()[0]?.id ?? null;
    state.activeTab = 'members';
    state.deleteProjectConfirm = false;
    state.inviteModal = false;
    state.newTaskModal = false;
    state.newProjectModal = false;
    state.removeTarget = null;
    emit();
    toast(`Proyek "${deletedName}" dihapus.`, 'success');
}

// --- Bantuan tugas & proyek --------------------------------------------

export function openNewProjectModal() {
    state.newProjectModal = true;
    state.newProjectError = null;
    state.newProjectColor = '#0BC5C1';
    state.newProjectDraft = { name: '', description: '', deadline: '' };
    emit();
}

export function closeNewProjectModal() {
    state.newProjectModal = false;
    state.newProjectError = null;
    state.newProjectDraft = { name: '', description: '', deadline: '' };
    emit();
}

export function setNewProjectDraft(patch) {
    // Silent update (no emit): called on every keystroke so typing never
    // triggers a re-render that would steal focus or wipe the form.
    Object.assign(state.newProjectDraft, patch);
}

export function setNewProjectColor(color) {
    state.newProjectColor = color;
    emit();
}

export function createProject({ name, description, deadline }) {
    if (!state.currentUserId) {
        toast('Pilih pengguna terlebih dahulu.');
        return;
    }

    const cleanName = String(name ?? '').trim();
    const cleanDescription = String(description ?? '').trim();
    const cleanDeadline = String(deadline ?? '');

    // Keep what the user typed so the validation re-render doesn't wipe it.
    state.newProjectDraft = { name: String(name ?? ''), description: String(description ?? ''), deadline: cleanDeadline };

    if (!cleanName) {
        state.newProjectError = 'Nama proyek wajib diisi.';
        emit();
        return;
    }

    const project = {
        id: `p${Date.now()}`,
        name: cleanName,
        description: cleanDescription,
        color: state.newProjectColor,
        ownerId: state.currentUserId,
        members: [{ userId: state.currentUserId, role: 'owner', joinedAt: new Date().toISOString().slice(0, 10) }],
        invitations: [],
        createdAt: new Date().toISOString().slice(0, 10),
        deadline: cleanDeadline,
    };
    state.projects.push(project);
    state.activeProjectId = project.id;
    state.activeTab = 'members';
    state.newProjectModal = false;
    state.newProjectError = null;
    state.newProjectDraft = { name: '', description: '', deadline: '' };
    emit();
    toast(`Proyek "${project.name}" dibuat.`, 'success');
}

export function openNewTaskModal() {
    state.newTaskModal = true;
    state.newTaskError = null;
    emit();
}

export function closeNewTaskModal() {
    state.newTaskModal = false;
    state.newTaskError = null;
    emit();
}

export function createTask({ title, priority, deadline }) {
    const project = state.projects.find((p) => p.id === state.activeProjectId);
    if (!project) return;

    if (project.ownerId !== state.currentUserId) {
        toast('Hanya Pemilik yang dapat membuat tugas.');
        return;
    }

    const cleanTitle = String(title ?? '').trim();

    if (!cleanTitle) {
        state.newTaskError = 'Judul tugas wajib diisi.';
        emit();
        return;
    }

    state.tasks.unshift({
        id: `t${Date.now()}`,
        projectId: project.id,
        title: cleanTitle,
        description: '',
        status: 'not_started',
        priority: priority || 'medium',
        deadline: String(deadline ?? ''),
        assigneeId: null,
        subtasks: [],
        createdAt: new Date().toISOString().slice(0, 10),
        tags: [],
    });
    state.newTaskModal = false;
    state.newTaskError = null;
    emit();
}

export function markNotifRead(notificationId) {
    const notif = state.notifications.find((n) => n.id === notificationId);
    if (notif) notif.read = true;
    emit();
}

export function setActiveProject(projectId) {
    state.activeProjectId = projectId;
    state.activeTab = 'members';
    state.inviteModal = false;
    state.newTaskModal = false;
    state.newProjectModal = false;
    state.removeTarget = null;
    state.deleteProjectConfirm = false;
    emit();
}

export function setActiveTab(tab) {
    if (!TABS.includes(tab)) return;
    state.activeTab = tab;
    emit();
}
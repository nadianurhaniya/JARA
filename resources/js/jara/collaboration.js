import {
    getState, getUser, currentUser, projectRole, isOwner, visibleProjects,
} from './store.js';
import { escapeHtml, avatar, roleBadge, statusBadge, priorityBadge, inviteBadge } from './ui.js';

// --------------------------------------------------------------------------
// Project sidebar + my invitations
// --------------------------------------------------------------------------

export function renderCollaborationLayout() {
    const state = getState();
    const projects = visibleProjects();
    const myInvites = myPendingInvitations();
    const activeProject = projects.find((p) => p.id === state.activeProjectId);

    return `
        <div class="flex-1 flex overflow-hidden">
            ${renderProjectSidebar(projects, myInvites)}
            <div class="flex-1 flex flex-col overflow-hidden">
                ${activeProject ? renderProjectMain(activeProject) : renderNoProject()}
            </div>
        </div>
    `;
}

function myPendingInvitations() {
    const state = getState();
    const user = currentUser();
    if (!user) return [];
    const result = [];
    for (const project of state.projects) {
        for (const inv of project.invitations) {
            if (inv.userId === user.id && inv.status === 'pending') {
                result.push({ ...inv, projectName: project.name, projectColor: project.color, invitedBy: getUser(project.ownerId) });
            }
        }
    }
    return result;
}

function renderProjectSidebar(projects, myInvites) {
    const state = getState();

    return `
        <div class="w-56 shrink-0 border-r border-[#E2E8F0] bg-white p-4 overflow-y-auto jara-scroll">
            <h3 class="font-display font-bold text-xs text-[#94A3B8] uppercase tracking-wide mb-3">Projects</h3>
            ${projects.length === 0
                ? '<p class="text-xs text-[#94A3B8] px-1">Belum ada proyek untuk kamu.</p>'
                : projects.map((p) => `
                    <button data-action="select-project" data-project="${p.id}"
                        class="w-full flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm mb-1 text-left ${state.activeProjectId === p.id ? 'bg-[#E8F9F9] text-[#0BC5C1] font-medium' : 'text-[#64748B] hover:bg-[#F8FAFC]'}">
                        <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background:${p.color}"></div>
                        <div class="min-w-0">
                            <p class="truncate">${escapeHtml(p.name)}</p>
                            <p class="text-xs text-[#94A3B8] font-normal">${p.members.length} members</p>
                        </div>
                    </button>
                `).join('')}

            ${myInvites.length > 0 ? `
                <div class="mt-4">
                    <h3 class="font-display font-bold text-xs text-[#94A3B8] uppercase tracking-wide mb-2">My Invitations</h3>
                    ${myInvites.map((inv) => `
                        <div class="p-3 rounded-xl bg-[#FEF9C3] border border-yellow-200 mb-2">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full shrink-0" style="background:${inv.projectColor}"></div>
                                <p class="text-xs font-semibold text-[#92400E] truncate">${escapeHtml(inv.projectName)}</p>
                            </div>
                            <p class="text-xs text-[#78350F] mt-0.5">from ${inv.invitedBy ? escapeHtml(inv.invitedBy.name) : 'Owner'}</p>
                            <div class="flex gap-1.5 mt-2">
                                <button data-action="respond-invite" data-invite="${inv.id}" data-accept="1"
                                    class="flex-1 text-xs py-1 rounded-lg bg-[#10B981] text-white font-medium hover:bg-emerald-600">Accept</button>
                                <button data-action="respond-invite" data-invite="${inv.id}" data-accept="0"
                                    class="flex-1 text-xs py-1 rounded-lg bg-white border border-[#E2E8F0] text-[#64748B] hover:bg-[#F8FAFC]">Reject</button>
                            </div>
                        </div>
                    `).join('')}
                </div>
            ` : ''}
        </div>
    `;
}

function renderNoProject() {
    return `
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center">
                <div class="w-14 h-14 rounded-2xl bg-[#E8F9F9] flex items-center justify-center mx-auto mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0BC5C1" stroke-width="1.5" class="w-7 h-7"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <p class="font-display font-bold text-[#1E293B]">Kamu belum memiliki akses proyek</p>
                <p class="text-sm text-[#94A3B8] mt-1">Terima undangan untuk bergabung dengan proyek.</p>
            </div>
        </div>
    `;
}

// --------------------------------------------------------------------------
// Project main area (header + tabs)
// --------------------------------------------------------------------------

function renderProjectMain(project) {
    const state = getState();
    const user = currentUser();
    const role = roleBadge(projectRole(project.id, user.id));
    const pendingCount = project.invitations.filter((i) => i.status === 'pending').length;

    return `
        <div class="px-6 py-4 border-b border-[#E2E8F0] bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background:${project.color}"></div>
                        <h2 class="font-display font-bold text-base text-[#1E293B]">${escapeHtml(project.name)}</h2>
                        ${role}
                    </div>
                    <p class="text-xs text-[#94A3B8] mt-0.5">${escapeHtml(project.description)}</p>
                </div>
                ${isOwner(project.id) ? `
                    <div class="flex items-center gap-2">
                        <button data-action="open-invite"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0BC5C1] text-white text-xs font-semibold hover:bg-[#0AAEAA]">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-3.5 h-3.5"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8M19 8v6M22 11h-6"/></svg>
                            Invite Member
                        </button>
                        <button data-action="open-delete-project" title="Hapus proyek"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[#FECACA] text-red-500 text-xs font-semibold hover:bg-red-50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                            <span class="hidden sm:inline">Delete</span>
                        </button>
                    </div>
                ` : ''}
            </div>

            <div class="flex gap-1 mt-4 bg-[#F1F5F9] rounded-xl p-1 w-fit">
                ${[
                    ['members', 'Members'],
                    ['invitations', 'Invitations'],
                    ['tasks', 'Tasks'],
                ].map(([tab, label]) => `
                    <button data-action="select-tab" data-tab="${tab}"
                        class="px-4 py-1.5 rounded-lg text-xs font-medium transition-all ${state.activeTab === tab ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8] hover:text-[#64748B]'}">
                        ${label}
                        ${tab === 'invitations' && pendingCount > 0 ? `<span class="ml-1.5 min-w-4 h-4 inline-flex items-center justify-center bg-[#0BC5C1] text-white rounded-full text-xs font-bold px-1">${pendingCount}</span>` : ''}
                    </button>
                `).join('')}
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 jara-scroll">
            ${state.activeTab === 'members' ? renderMembersTab(project) : ''}
            ${state.activeTab === 'invitations' ? renderInvitationsTab(project) : ''}
            ${state.activeTab === 'tasks' ? renderTasksTab(project) : ''}
        </div>
    `;
}

// --------------------------------------------------------------------------
// Members tab (FR-25 + FR-20)
// --------------------------------------------------------------------------

function renderMembersTab(project) {
    const state = getState();
    const user = currentUser();
    const owner = isOwner(project.id);
    const projectTasks = state.tasks.filter((t) => t.projectId === project.id);

    if (project.members.length === 0) {
        return `
            <div class="text-center py-12">
                <div class="w-14 h-14 rounded-2xl bg-[#E8F9F9] flex items-center justify-center mx-auto mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0BC5C1" stroke-width="1.5" class="w-7 h-7"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <p class="font-display font-bold text-[#1E293B]">Belum ada member</p>
                <p class="text-sm text-[#94A3B8] mt-1">Undang orang untuk berkolaborasi di proyek ini.</p>
            </div>
        `;
    }

    return `
        <div class="space-y-3">
            ${project.members.map((m) => {
                const memberUser = getUser(m.userId);
                if (!memberUser) return '';
                const isSelf = m.userId === user.id;
                const memberTasks = projectTasks.filter((t) => t.assigneeId === m.userId);
                const doneCount = memberTasks.filter((t) => t.status === 'completed').length;
                const pct = memberTasks.length ? Math.round((doneCount / memberTasks.length) * 100) : 0;

                return `
                    <div class="bg-white rounded-xl border border-[#E2E8F0] p-4 flex items-center gap-4">
                        ${avatar(memberUser)}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-sm text-[#1E293B] truncate">${escapeHtml(memberUser.name)} ${isSelf ? '<span class="text-xs text-[#94A3B8]">(you)</span>' : ''}</p>
                                ${roleBadge(m.role)}
                            </div>
                            <p class="text-xs text-[#94A3B8] mt-0.5">${escapeHtml(memberUser.email)}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs text-[#64748B]">${doneCount}/${memberTasks.length} tasks done</span>
                                ${memberTasks.length > 0 ? `
                                    <div class="h-1.5 w-24 bg-[#F1F5F9] rounded-full overflow-hidden">
                                        <div class="h-full bg-[#0BC5C1] rounded-full" style="width:${pct}%"></div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="w-2 h-2 rounded-full ${memberUser.status === 'active' ? 'bg-[#10B981]' : 'bg-[#94A3B8]'}"></span>
                            <span class="text-xs text-[#94A3B8] hidden sm:inline">${memberUser.status}</span>
                            ${owner && m.role !== 'owner' ? `
                                <button data-action="open-remove" data-user="${m.userId}" title="Remove member"
                                    class="ml-2 p-1.5 rounded-lg hover:bg-red-50 text-[#94A3B8] hover:text-red-500">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/><line x1="17" y1="11" x2="23" y2="11"/></svg>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('')}
        </div>
    `;
}

// --------------------------------------------------------------------------
// Invitations tab
// --------------------------------------------------------------------------

function renderInvitationsTab(project) {
    const state = getState();
    const owner = isOwner(project.id);
    const invitations = project.invitations;

    if (invitations.length === 0) {
        return `
            <div class="text-center py-12">
                <div class="w-14 h-14 rounded-2xl bg-[#E8F9F9] flex items-center justify-center mx-auto mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0BC5C1" stroke-width="1.5" class="w-7 h-7"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <p class="font-display font-bold text-[#1E293B]">No invitations</p>
                <p class="text-sm text-[#94A3B8] mt-1">${owner ? 'Undang orang untuk berkolaborasi di proyek ini.' : 'Belum ada undangan pada proyek ini.'}</p>
            </div>
        `;
    }

    return `
        <div class="space-y-3">
            ${invitations.map((inv) => {
                const invitedUser = getUser(inv.userId);
                const name = invitedUser ? invitedUser.name : inv.email;
                const email = invitedUser ? invitedUser.email : inv.email;
                return `
                    <div class="bg-white rounded-xl border border-[#E2E8F0] p-4 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full ${invitedUser ? 'bg-[#0BC5C1]' : 'bg-[#F1F5F9]'} flex items-center justify-center ${invitedUser ? 'text-white' : 'text-[#94A3B8]'} text-xs font-bold shrink-0">
                            ${invitedUser ? escapeHtml(invitedUser.avatar) : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0112 0v1"/></svg>'}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-[#1E293B] truncate">${escapeHtml(name)}</p>
                            <p class="text-xs text-[#94A3B8] mt-0.5">${escapeHtml(email)} · Sent ${escapeHtml(inv.sentAt)}</p>
                        </div>
                        ${inviteBadge(inv.status)}
                        ${owner && inv.status === 'pending' ? `
                            <button data-action="cancel-invite" data-invite="${inv.id}"
                                class="p-1.5 rounded-lg hover:bg-red-50 text-[#94A3B8] hover:text-red-500" title="Cancel invitation">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        ` : ''}
                    </div>
                `;
            }).join('')}
        </div>
    `;
}

// --------------------------------------------------------------------------
// Tasks tab (FR-22 + FR-23)
// --------------------------------------------------------------------------

function renderTasksTab(project) {
    const state = getState();
    const user = currentUser();
    const owner = isOwner(project.id);
    const projectTasks = state.tasks.filter((t) => t.projectId === project.id);
    const activeMembers = project.members
        .map((m) => ({ member: m, user: getUser(m.userId) }))
        .filter(({ member, user }) => member.role !== 'owner' && user && user.status === 'active');

    return `
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs text-[#94A3B8]">${projectTasks.length} task · ${projectTasks.filter((t) => t.status === 'completed').length} selesai</p>
            ${owner ? `
                <button data-action="open-new-task"
                    class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0BC5C1] text-white text-xs font-semibold hover:bg-[#0AAEAA]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-3.5 h-3.5"><path d="M12 5v14M5 12h14"/></svg>
                    New Task
                </button>
            ` : ''}
        </div>

        ${projectTasks.length === 0 ? `
            <p class="text-sm text-[#94A3B8] text-center py-8">Belum ada task di proyek ini.</p>
        ` : `
            <div class="space-y-2">
                ${projectTasks.map((task) => {
                    const assignee = getUser(task.assigneeId);
                    const canChangeStatus = owner || task.assigneeId === user.id;

                    return `
                        <div class="bg-white rounded-xl border border-[#E2E8F0] p-4 flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full shrink-0" style="background:${statusColor(task.status)}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-[#1E293B] truncate">${escapeHtml(task.title)}</p>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    ${statusBadge(task.status)}
                                    ${priorityBadge(task.priority)}
                                    <span class="text-xs text-[#94A3B8]">Due ${escapeHtml(task.deadline || '—')}</span>
                                </div>
                            </div>

                            ${owner ? `
                                <label class="flex items-center gap-1.5 shrink-0">
                                    <span class="text-xs text-[#94A3B8] hidden sm:inline">Assign</span>
                                    <select data-action="assign-task" data-task="${task.id}"
                                        class="px-2 py-1.5 rounded-lg border border-[#E2E8F0] bg-white text-xs text-[#64748B] outline-none focus:border-[#0BC5C1]">
                                        <option value="">Unassigned</option>
                                        ${activeMembers.map(({ user: m }) => `
                                            <option value="${m.id}" ${task.assigneeId === m.id ? 'selected' : ''}>${escapeHtml(m.name)}</option>
                                        `).join('')}
                                    </select>
                                </label>
                            ` : ''}

                            ${task.assigneeId && assignee ? `
                                <div class="flex items-center gap-2 shrink-0">
                                    ${avatar(assignee, 'w-7 h-7 text-xs')}
                                    <span class="text-xs text-[#64748B] hidden sm:inline">${escapeHtml(assignee.name.split(' ')[0])}</span>
                                </div>
                            ` : `<span class="text-xs text-[#94A3B8] px-2 py-1 bg-[#F8FAFC] rounded-lg shrink-0">Unassigned</span>`}

                            ${canChangeStatus ? `
                                <select data-action="change-status" data-task="${task.id}"
                                    class="px-2 py-1.5 rounded-lg border border-[#E2E8F0] bg-white text-xs text-[#64748B] outline-none focus:border-[#0BC5C1] shrink-0">
                                    <option value="not_started" ${task.status === 'not_started' ? 'selected' : ''}>Not Started</option>
                                    <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                    <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Selesai</option>
                                </select>
                            ` : ''}
                        </div>
                    `;
                }).join('')}
            </div>
        `}
    `;
}

function statusColor(status) {
    const map = { not_started: '#94A3B8', in_progress: '#0BC5C1', completed: '#10B981' };
    return map[status] ?? '#94A3B8';
}

// --------------------------------------------------------------------------
// Modals
// --------------------------------------------------------------------------

export function renderModals() {
    const state = getState();
    const project = state.projects.find((p) => p.id === state.activeProjectId);
    if (!project) return '';

    return `
        ${state.inviteModal ? renderInviteModal(project) : ''}
        ${state.newTaskModal ? renderNewTaskModal() : ''}
        ${state.removeTarget ? renderRemoveModal(project) : ''}
        ${state.deleteProjectConfirm ? renderDeleteProjectModal(project) : ''}
    `;
}

function invitableUsers(project) {
    const state = getState();
    return state.users.filter((u) => {
        const alreadyMember = project.members.some((m) => m.userId === u.id);
        const pending = project.invitations.some((i) => i.userId === u.id && i.status === 'pending');
        return u.id !== project.ownerId && !alreadyMember && !pending;
    });
}

function renderInviteModal(project) {
    const state = getState();
    const candidates = invitableUsers(project);

    return `
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" data-action="close-invite">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6" data-stop>
                <h2 class="font-display font-bold text-lg text-[#1E293B] mb-1">Invite Member</h2>
                <p class="text-sm text-[#94A3B8] mb-5">Undang user untuk berkolaborasi di <strong class="text-[#1E293B]">${escapeHtml(project.name)}</strong></p>

                ${state.inviteSuccess ? `
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2 mb-4">
                        <span class="text-emerald-500">✓</span>
                        <p class="text-sm text-emerald-700">${escapeHtml(state.inviteSuccess)}</p>
                    </div>
                ` : ''}

                ${candidates.length === 0 ? `
                    <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-center">
                        <p class="text-sm text-[#64748B]">Tidak ada user yang dapat diundang.</p>
                        <p class="text-xs text-[#94A3B8] mt-1">Semua user aktif sudah menjadi member atau memiliki undangan pending.</p>
                    </div>
                ` : `
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-[#475569] uppercase tracking-wide mb-2">Select user</p>
                        <div class="space-y-1.5 max-h-52 overflow-y-auto jara-scroll">
                            ${candidates.map((u) => `
                                <button data-action="pick-invite" data-user="${u.id}"
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-left hover:bg-[#F8FAFC] ${state.assigneeDraft === u.id ? 'bg-[#E8F9F9] border border-[#0BC5C1]' : 'border border-transparent'}">
                                    ${avatar(u, 'w-7 h-7 text-xs')}
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-[#1E293B]">${escapeHtml(u.name)}</p>
                                        <p class="text-xs text-[#94A3B8]">${escapeHtml(u.email)}</p>
                                    </div>
                                </button>
                            `).join('')}
                        </div>
                    </div>
                `}

                ${state.inviteError ? `
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600 flex items-center gap-2">
                        <span>⚠</span>${escapeHtml(state.inviteError)}
                    </div>
                ` : ''}

                <div class="flex gap-3">
                    <button data-action="close-invite" class="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
                    <button data-action="send-invite" ${state.inviteSuccess ? 'disabled' : ''}
                        class="flex-1 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA] disabled:opacity-50 disabled:cursor-not-allowed">Send Invite</button>
                </div>
            </div>
        </div>
    `;
}

function renderNewTaskModal() {
    const state = getState();
    return `
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" data-action="close-new-task">
            <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl p-6" data-stop>
                <h2 class="font-display font-bold text-lg text-[#1E293B] mb-4">New Task</h2>
                <form data-form="new-task" class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Task Title *</label>
                        <input name="title" type="text" placeholder="Task title..." autocomplete="off"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1]" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Priority</label>
                            <select name="priority" class="w-full px-3 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] bg-white outline-none focus:border-[#0BC5C1]">
                                <option value="high">High</option>
                                <option value="medium" selected>Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Deadline</label>
                            <input name="deadline" type="date" class="w-full px-3 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] bg-white outline-none focus:border-[#0BC5C1]" />
                        </div>
                    </div>
                    ${state.newTaskError ? `<p class="text-xs text-red-500 flex items-center gap-1"><span>⚠</span>${escapeHtml(state.newTaskError)}</p>` : ''}
                    <div class="flex gap-3 pt-1">
                        <button type="button" data-action="close-new-task" class="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    `;
}

function renderRemoveModal(project) {
    const state = getState();
    const target = getUser(state.removeTarget);
    if (!target) return '';

    return `
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" data-action="close-remove">
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl" data-stop>
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" class="w-6 h-6"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/><line x1="17" y1="11" x2="23" y2="11"/></svg>
                </div>
                <h3 class="font-display font-bold text-lg text-[#1E293B] text-center mb-1">Hapus Member</h3>
                <p class="text-sm text-[#64748B] text-center mb-6">
                    Hapus <strong class="text-[#1E293B]">${escapeHtml(target.name)}</strong> dari proyek <strong class="text-[#1E293B]">${escapeHtml(project.name)}</strong>? Tugas yang ditugaskan kepadanya akan menjadi Unassigned.
                </p>
                <div class="flex gap-3">
                    <button data-action="close-remove" class="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
                    <button data-action="confirm-remove" class="flex-1 py-2.5 rounded-xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600">Remove</button>
                </div>
            </div>
        </div>
    `;
}

function renderDeleteProjectModal(project) {
    const taskCount = getState().tasks.filter((t) => t.projectId === project.id).length;

    return `
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" data-action="close-delete-project">
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl" data-stop>
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" class="w-6 h-6"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                </div>
                <h3 class="font-display font-bold text-lg text-[#1E293B] text-center mb-1">Hapus Proyek</h3>
                <p class="text-sm text-[#64748B] text-center mb-6">
                    Hapus proyek <strong class="text-[#1E293B]">${escapeHtml(project.name)}</strong> beserta ${taskCount} task di dalamnya? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button data-action="close-delete-project" class="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
                    <button data-action="confirm-delete-project" class="flex-1 py-2.5 rounded-xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600">Delete</button>
                </div>
            </div>
        </div>
    `;
}
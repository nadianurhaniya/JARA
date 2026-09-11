import { useState } from 'react';
import { PROJECTS, USERS, TASKS, CURRENT_USER, Project, Invitation } from '../../data/mockData';

export default function CollaborationPage() {
  const [projects, setProjects] = useState(PROJECTS);
  const [activeProjectId, setActiveProjectId] = useState(PROJECTS[0]?.id ?? '');
  const [showInviteModal, setShowInviteModal] = useState(false);
  const [inviteEmail, setInviteEmail] = useState('');
  const [inviteSuccess, setInviteSuccess] = useState('');
  const [activeTab, setActiveTab] = useState<'members' | 'invitations' | 'tasks'>('members');

  // Simulate current user is u2 (project owner of p1 and p2)
  const currentUser = CURRENT_USER;

  const activeProject = projects.find(p => p.id === activeProjectId);
  const isOwner = activeProject?.ownerId === currentUser.id;

  const sendInvite = () => {
    if (!inviteEmail.trim() || !activeProject) return;
    const newInv: Invitation = {
      id: `inv${Date.now()}`, projectId: activeProject.id,
      email: inviteEmail, status: 'pending', sentAt: new Date().toISOString().split('T')[0],
    };
    setProjects(projects.map(p => p.id === activeProjectId
      ? { ...p, invitations: [...p.invitations, newInv] }
      : p
    ));
    setInviteSuccess(`Invitation sent to ${inviteEmail}`);
    setInviteEmail('');
    setTimeout(() => { setInviteSuccess(''); setShowInviteModal(false); }, 1500);
  };

  const removeMember = (userId: string) => {
    setProjects(projects.map(p => p.id === activeProjectId
      ? { ...p, members: p.members.filter(m => m.userId !== userId) }
      : p
    ));
  };

  const cancelInvitation = (invId: string) => {
    setProjects(projects.map(p => p.id === activeProjectId
      ? { ...p, invitations: p.invitations.filter(i => i.id !== invId) }
      : p
    ));
  };

  // Simulate pending invitations for current user
  const myInvitations = [
    { id: 'pinv1', projectId: 'p3', projectName: 'Q4 Marketing Campaign', fromUser: 'Citra Dewi', sentAt: '2026-09-08' },
  ];
  const [myInvites, setMyInvites] = useState(myInvitations);

  const respondToInvite = (id: string, _accept: boolean) => {
    setMyInvites(myInvites.filter(i => i.id !== id));
  };

  const projectTasks = TASKS.filter(t => t.projectId === activeProjectId);

  const roleColor = (role: string) => role === 'owner' ? { bg: '#FEF3C7', text: '#D97706' } : { bg: '#E8F9F9', text: '#0BC5C1' };

  return (
    <div className="flex-1 flex overflow-hidden">
      {/* Project sidebar */}
      <div className="w-56 shrink-0 border-r border-[#E2E8F0] bg-white p-4 overflow-y-auto">
        <h3 className="font-display font-700 text-xs text-[#94A3B8] uppercase tracking-wide mb-3">Projects</h3>
        {projects.map(p => (
          <button
            key={p.id}
            onClick={() => setActiveProjectId(p.id)}
            className={`w-full flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm mb-1 text-left ${activeProjectId === p.id ? 'bg-[#E8F9F9] text-[#0BC5C1] font-medium' : 'text-[#64748B] hover:bg-[#F8FAFC]'}`}>
            <div className="w-2.5 h-2.5 rounded-full shrink-0" style={{ background: p.color }} />
            <div className="min-w-0">
              <p className="truncate">{p.name}</p>
              <p className="text-xs text-[#94A3B8] font-normal">{p.members.length} members</p>
            </div>
          </button>
        ))}

        {/* My pending invitations */}
        {myInvites.length > 0 && (
          <div className="mt-4">
            <h3 className="font-display font-700 text-xs text-[#94A3B8] uppercase tracking-wide mb-2">My Invitations</h3>
            {myInvites.map(inv => (
              <div key={inv.id} className="p-3 rounded-xl bg-[#FEF9C3] border border-yellow-200 mb-2">
                <p className="text-xs font-semibold text-[#92400E]">{inv.projectName}</p>
                <p className="text-xs text-[#78350F] mt-0.5">from {inv.fromUser}</p>
                <div className="flex gap-1.5 mt-2">
                  <button onClick={() => respondToInvite(inv.id, true)} className="flex-1 text-xs py-1 rounded-lg bg-[#10B981] text-white font-medium">Accept</button>
                  <button onClick={() => respondToInvite(inv.id, false)} className="flex-1 text-xs py-1 rounded-lg bg-white border border-[#E2E8F0] text-[#64748B]">Decline</button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Main content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {activeProject ? (
          <>
            {/* Header */}
            <div className="px-6 py-4 border-b border-[#E2E8F0] bg-white">
              <div className="flex items-center justify-between">
                <div>
                  <div className="flex items-center gap-2">
                    <div className="w-3 h-3 rounded-full" style={{ background: activeProject.color }} />
                    <h2 className="font-display font-700 text-base text-[#1E293B]">{activeProject.name}</h2>
                    {isOwner && (
                      <span className="text-xs px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#D97706] font-medium">Owner</span>
                    )}
                  </div>
                  <p className="text-xs text-[#94A3B8] mt-0.5">{activeProject.description}</p>
                </div>
                {isOwner && (
                  <button onClick={() => setShowInviteModal(true)}
                    className="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0BC5C1] text-white text-xs font-semibold hover:bg-[#0AAEAA]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" className="w-3.5 h-3.5"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8M19 8v6M22 11h-6" /></svg>
                    Invite Member
                  </button>
                )}
              </div>

              {/* Tabs */}
              <div className="flex gap-1 mt-4 bg-[#F1F5F9] rounded-xl p-1 w-fit">
                {(['members', 'invitations', 'tasks'] as const).map(tab => (
                  <button key={tab} onClick={() => setActiveTab(tab)}
                    className={`px-4 py-1.5 rounded-lg text-xs font-medium transition-all ${activeTab === tab ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8]'}`}>
                    {tab.charAt(0).toUpperCase() + tab.slice(1)}
                    {tab === 'invitations' && activeProject.invitations.filter(i => i.status === 'pending').length > 0 && (
                      <span className="ml-1.5 min-w-4 h-4 inline-flex items-center justify-center bg-[#0BC5C1] text-white rounded-full text-xs font-bold px-1">
                        {activeProject.invitations.filter(i => i.status === 'pending').length}
                      </span>
                    )}
                  </button>
                ))}
              </div>
            </div>

            <div className="flex-1 overflow-y-auto p-6">
              {/* Members tab */}
              {activeTab === 'members' && (
                <div className="space-y-3">
                  {activeProject.members.map(m => {
                    const user = USERS.find(u => u.id === m.userId);
                    if (!user) return null;
                    const rc = roleColor(m.role);
                    const memberTasks = projectTasks.filter(t => t.assigneeId === m.userId);
                    const memberDone = memberTasks.filter(t => t.status === 'completed').length;
                    return (
                      <div key={m.userId} className="bg-white rounded-xl border border-[#E2E8F0] p-4 flex items-center gap-4">
                        <div className="w-10 h-10 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white font-bold shrink-0">
                          {user.avatar}
                        </div>
                        <div className="flex-1 min-w-0">
                          <div className="flex items-center gap-2">
                            <p className="font-semibold text-sm text-[#1E293B]">{user.name}</p>
                            <span className="text-xs px-2 py-0.5 rounded-full font-medium" style={{ background: rc.bg, color: rc.text }}>
                              {m.role.charAt(0).toUpperCase() + m.role.slice(1)}
                            </span>
                          </div>
                          <p className="text-xs text-[#94A3B8] mt-0.5">{user.email}</p>
                          <div className="flex items-center gap-3 mt-2">
                            <span className="text-xs text-[#64748B]">{memberDone}/{memberTasks.length} tasks done</span>
                            {memberTasks.length > 0 && (
                              <div className="h-1.5 w-24 bg-[#F1F5F9] rounded-full overflow-hidden">
                                <div className="h-full bg-[#0BC5C1] rounded-full" style={{ width: `${memberTasks.length ? (memberDone / memberTasks.length) * 100 : 0}%` }} />
                              </div>
                            )}
                          </div>
                        </div>
                        <div className="flex items-center gap-2">
                          <span className={`w-2 h-2 rounded-full ${user.status === 'active' ? 'bg-[#10B981]' : 'bg-[#94A3B8]'}`} />
                          <span className="text-xs text-[#94A3B8]">{user.status}</span>
                          {isOwner && m.role !== 'owner' && (
                            <button onClick={() => removeMember(m.userId)}
                              className="ml-2 p-1.5 rounded-lg hover:bg-red-50 text-[#94A3B8] hover:text-red-500">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" /><line x1="17" y1="11" x2="23" y2="11" /></svg>
                            </button>
                          )}
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}

              {/* Invitations tab */}
              {activeTab === 'invitations' && (
                <div className="space-y-3">
                  {activeProject.invitations.length === 0 ? (
                    <div className="text-center py-12">
                      <div className="w-14 h-14 rounded-2xl bg-[#E8F9F9] flex items-center justify-center mx-auto mb-3">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#0BC5C1" strokeWidth="1.5" className="w-7 h-7"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                      </div>
                      <p className="font-display font-700 text-[#1E293B]">No invitations</p>
                      <p className="text-sm text-[#94A3B8] mt-1">Invite people to collaborate on this project.</p>
                    </div>
                  ) : (
                    activeProject.invitations.map(inv => (
                      <div key={inv.id} className="bg-white rounded-xl border border-[#E2E8F0] p-4 flex items-center gap-4">
                        <div className="w-10 h-10 rounded-full bg-[#F1F5F9] flex items-center justify-center">
                          <svg viewBox="0 0 24 24" fill="none" stroke="#94A3B8" strokeWidth="2" className="w-5 h-5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div className="flex-1">
                          <p className="font-semibold text-sm text-[#1E293B]">{inv.email}</p>
                          <p className="text-xs text-[#94A3B8] mt-0.5">Sent {inv.sentAt}</p>
                        </div>
                        <span className={`text-xs px-2.5 py-1 rounded-full font-medium ${
                          inv.status === 'pending' ? 'bg-[#FEF9C3] text-[#92400E]' :
                          inv.status === 'accepted' ? 'bg-[#ECFDF5] text-[#065F46]' : 'bg-red-50 text-red-600'
                        }`}>{inv.status.charAt(0).toUpperCase() + inv.status.slice(1)}</span>
                        {isOwner && inv.status === 'pending' && (
                          <button onClick={() => cancelInvitation(inv.id)}
                            className="p-1.5 rounded-lg hover:bg-red-50 text-[#94A3B8] hover:text-red-500">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><path d="M18 6L6 18M6 6l12 12" /></svg>
                          </button>
                        )}
                      </div>
                    ))
                  )}
                </div>
              )}

              {/* Tasks tab */}
              {activeTab === 'tasks' && (
                <div className="space-y-2">
                  {projectTasks.length === 0 ? (
                    <p className="text-sm text-[#94A3B8] text-center py-8">No tasks in this project yet.</p>
                  ) : (
                    projectTasks.map(task => {
                      const assignee = USERS.find(u => u.id === task.assigneeId);
                      const statusC: Record<string, string> = { not_started: '#94A3B8', in_progress: '#0BC5C1', completed: '#10B981' };
                      const statusBgC: Record<string, string> = { not_started: '#F1F5F9', in_progress: '#E8F9F9', completed: '#ECFDF5' };
                      const statusLabelC: Record<string, string> = { not_started: 'Not Started', in_progress: 'In Progress', completed: 'Completed' };
                      return (
                        <div key={task.id} className="bg-white rounded-xl border border-[#E2E8F0] p-4 flex items-center gap-3">
                          <div className={`w-2 h-2 rounded-full shrink-0`} style={{ background: statusC[task.status] }} />
                          <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-[#1E293B] truncate">{task.title}</p>
                            <div className="flex items-center gap-2 mt-1">
                              <span className="text-xs px-2 py-0.5 rounded-full font-medium" style={{ background: statusBgC[task.status], color: statusC[task.status] }}>
                                {statusLabelC[task.status]}
                              </span>
                              <span className="text-xs text-[#94A3B8]">Due {task.deadline}</span>
                            </div>
                          </div>
                          {assignee ? (
                            <div className="flex items-center gap-2">
                              <div className="w-7 h-7 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold">
                                {assignee.avatar}
                              </div>
                              <span className="text-xs text-[#64748B]">{assignee.name.split(' ')[0]}</span>
                            </div>
                          ) : (
                            <span className="text-xs text-[#94A3B8] px-2 py-1 bg-[#F8FAFC] rounded-lg">Unassigned</span>
                          )}
                        </div>
                      );
                    })
                  )}
                </div>
              )}
            </div>
          </>
        ) : (
          <div className="flex-1 flex items-center justify-center">
            <p className="text-[#94A3B8]">Select a project to view collaboration details.</p>
          </div>
        )}
      </div>

      {/* Invite Modal */}
      {showInviteModal && (
        <div className="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onClick={() => setShowInviteModal(false)}>
          <div className="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6" onClick={e => e.stopPropagation()}>
            <h2 className="font-display font-700 text-lg text-[#1E293B] mb-1">Invite Member</h2>
            <p className="text-sm text-[#94A3B8] mb-5">Invite someone to collaborate on <strong className="text-[#1E293B]">{activeProject?.name}</strong></p>

            {inviteSuccess ? (
              <div className="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2 mb-4">
                <span className="text-emerald-500">✓</span>
                <p className="text-sm text-emerald-700">{inviteSuccess}</p>
              </div>
            ) : (
              <>
                <div className="mb-4">
                  <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Email Address</label>
                  <input
                    type="email" value={inviteEmail} onChange={e => setInviteEmail(e.target.value)}
                    placeholder="colleague@company.com"
                    className="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1]"
                  />
                </div>
                <div className="mb-4">
                  <p className="text-xs font-semibold text-[#475569] uppercase tracking-wide mb-2">Or select existing user</p>
                  <div className="space-y-1.5 max-h-36 overflow-y-auto">
                    {USERS.filter(u => u.status === 'active' && u.id !== currentUser.id && !activeProject?.members.find(m => m.userId === u.id)).map(u => (
                      <button key={u.id} onClick={() => setInviteEmail(u.email)}
                        className={`w-full flex items-center gap-2 px-3 py-2 rounded-xl text-left hover:bg-[#F8FAFC] ${inviteEmail === u.email ? 'bg-[#E8F9F9] border border-[#0BC5C1]' : 'border border-transparent'}`}>
                        <div className="w-7 h-7 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold shrink-0">{u.avatar}</div>
                        <div>
                          <p className="text-xs font-medium text-[#1E293B]">{u.name}</p>
                          <p className="text-xs text-[#94A3B8]">{u.email}</p>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              </>
            )}
            <div className="flex gap-3">
              <button onClick={() => { setShowInviteModal(false); setInviteEmail(''); }} className="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
              <button onClick={sendInvite} disabled={!inviteEmail.trim()} className="flex-1 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA] disabled:opacity-50">Send Invite</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

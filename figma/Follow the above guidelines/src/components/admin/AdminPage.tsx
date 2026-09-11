import { useState } from 'react';
import { USERS, ACTIVITY_LOGS, User, Role } from '../../data/mockData';

type FilterRole = 'all' | Role;
type FilterStatus = 'all' | 'active' | 'inactive';

interface AddUserModalProps {
  onAdd: (user: Partial<User>) => void;
  onClose: () => void;
}

function AddUserModal({ onAdd, onClose }: AddUserModalProps) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState<Role>('user');
  const [errors, setErrors] = useState<Record<string, string>>({});

  const handleAdd = () => {
    const errs: Record<string, string> = {};
    if (!name.trim()) errs.name = 'Name is required';
    if (!email.trim()) errs.email = 'Email is required';
    else if (!email.includes('@')) errs.email = 'Invalid email';
    if (Object.keys(errs).length) { setErrors(errs); return; }
    onAdd({ name, email, role, status: 'active', avatar: name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) });
  };

  return (
    <div className="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onClick={onClose}>
      <div className="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6" onClick={e => e.stopPropagation()}>
        <h2 className="font-display font-700 text-lg text-[#1E293B] mb-5">Add New User</h2>
        <div className="space-y-4">
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Full Name *</label>
            <input value={name} onChange={e => setName(e.target.value)} placeholder="Full name"
              className={`w-full px-4 py-2.5 rounded-xl border text-sm outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] ${errors.name ? 'border-red-400 bg-red-50' : 'border-[#E2E8F0]'}`} />
            {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
          </div>
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Email *</label>
            <input type="email" value={email} onChange={e => setEmail(e.target.value)} placeholder="user@example.com"
              className={`w-full px-4 py-2.5 rounded-xl border text-sm outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] ${errors.email ? 'border-red-400 bg-red-50' : 'border-[#E2E8F0]'}`} />
            {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
          </div>
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Role</label>
            <div className="flex gap-2">
              {(['user', 'admin'] as Role[]).map(r => (
                <button key={r} onClick={() => setRole(r)}
                  className={`flex-1 py-2 rounded-xl text-sm font-medium border transition-all ${role === r ? 'bg-[#0BC5C1] text-white border-[#0BC5C1]' : 'border-[#E2E8F0] text-[#64748B] hover:bg-[#F8FAFC]'}`}>
                  {r.charAt(0).toUpperCase() + r.slice(1)}
                </button>
              ))}
            </div>
          </div>
        </div>
        <div className="flex gap-3 mt-6">
          <button onClick={onClose} className="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
          <button onClick={handleAdd} className="flex-1 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">Add User</button>
        </div>
      </div>
    </div>
  );
}

interface UserDetailModalProps {
  user: User;
  onClose: () => void;
  onToggleStatus: () => void;
}

function UserDetailModal({ user, onClose, onToggleStatus }: UserDetailModalProps) {
  return (
    <div className="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onClick={onClose}>
      <div className="bg-white rounded-2xl w-full max-w-sm shadow-2xl" onClick={e => e.stopPropagation()}>
        <div className="p-6 text-center border-b border-[#E2E8F0]">
          <div className="w-16 h-16 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">
            {user.avatar}
          </div>
          <h3 className="font-display font-700 text-lg text-[#1E293B]">{user.name}</h3>
          <p className="text-sm text-[#94A3B8]">{user.email}</p>
          <div className="flex items-center justify-center gap-2 mt-2">
            <span className={`text-xs px-2.5 py-1 rounded-full font-medium ${user.role === 'admin' ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-[#E8F9F9] text-[#0BC5C1]'}`}>
              {user.role.charAt(0).toUpperCase() + user.role.slice(1)}
            </span>
            <span className={`text-xs px-2.5 py-1 rounded-full font-medium flex items-center gap-1 ${user.status === 'active' ? 'bg-[#ECFDF5] text-[#065F46]' : 'bg-[#F1F5F9] text-[#64748B]'}`}>
              <span className={`w-1.5 h-1.5 rounded-full ${user.status === 'active' ? 'bg-[#10B981]' : 'bg-[#94A3B8]'}`} />
              {user.status.charAt(0).toUpperCase() + user.status.slice(1)}
            </span>
          </div>
        </div>
        <div className="p-6 space-y-3">
          {[
            { label: 'Member since', value: user.joinedAt },
            { label: 'Last active', value: user.lastActive },
          ].map(({ label, value }) => (
            <div key={label} className="flex justify-between">
              <span className="text-sm text-[#94A3B8]">{label}</span>
              <span className="text-sm font-medium text-[#1E293B]">{value}</span>
            </div>
          ))}
        </div>
        <div className="px-6 pb-6 flex gap-3">
          <button onClick={onClose} className="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Close</button>
          {user.role !== 'admin' && (
            <button onClick={() => { onToggleStatus(); onClose(); }}
              className={`flex-1 py-2.5 rounded-xl text-sm font-semibold ${user.status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-[#ECFDF5] text-[#065F46] hover:bg-emerald-100'}`}>
              {user.status === 'active' ? 'Deactivate' : 'Reactivate'}
            </button>
          )}
        </div>
      </div>
    </div>
  );
}

export default function AdminPage() {
  const [users, setUsers] = useState<User[]>(USERS);
  const [search, setSearch] = useState('');
  const [filterRole, setFilterRole] = useState<FilterRole>('all');
  const [filterStatus, setFilterStatus] = useState<FilterStatus>('all');
  const [showAddModal, setShowAddModal] = useState(false);
  const [selectedUser, setSelectedUser] = useState<User | null>(null);
  const [activeTab, setActiveTab] = useState<'users' | 'activity'>('users');

  const filteredUsers = users
    .filter(u => filterRole === 'all' || u.role === filterRole)
    .filter(u => filterStatus === 'all' || u.status === filterStatus)
    .filter(u => !search || u.name.toLowerCase().includes(search.toLowerCase()) || u.email.toLowerCase().includes(search.toLowerCase()));

  const addUser = (data: Partial<User>) => {
    const newUser: User = {
      id: `u${Date.now()}`, name: '', email: '', role: 'user', status: 'active', avatar: 'NU',
      joinedAt: new Date().toISOString().split('T')[0], lastActive: new Date().toISOString().split('T')[0],
      ...data,
    };
    setUsers([...users, newUser]);
    setShowAddModal(false);
  };

  const toggleStatus = (userId: string) => {
    setUsers(users.map(u => u.id === userId ? { ...u, status: u.status === 'active' ? 'inactive' : 'active' } : u));
  };

  const stats = {
    total: users.length,
    active: users.filter(u => u.status === 'active').length,
    inactive: users.filter(u => u.status === 'inactive').length,
    admins: users.filter(u => u.role === 'admin').length,
  };

  return (
    <div className="flex-1 overflow-y-auto p-6">
      {/* Stats row */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {[
          { label: 'Total Users', value: stats.total, color: '#0BC5C1', bgColor: '#E8F9F9' },
          { label: 'Active Users', value: stats.active, color: '#10B981', bgColor: '#ECFDF5' },
          { label: 'Inactive Users', value: stats.inactive, color: '#94A3B8', bgColor: '#F1F5F9' },
          { label: 'Admins', value: stats.admins, color: '#F59E0B', bgColor: '#FEF3C7' },
        ].map(s => (
          <div key={s.label} className="bg-white rounded-2xl p-5 border border-[#E2E8F0]">
            <div className="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style={{ background: s.bgColor }}>
              <svg viewBox="0 0 24 24" fill="none" stroke={s.color} strokeWidth="2" className="w-4.5 h-4.5">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
              </svg>
            </div>
            <p className="font-display font-800 text-2xl text-[#1E293B]">{s.value}</p>
            <p className="text-sm text-[#64748B]">{s.label}</p>
          </div>
        ))}
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-[#F1F5F9] rounded-xl p-1 w-fit mb-5">
        {(['users', 'activity'] as const).map(tab => (
          <button key={tab} onClick={() => setActiveTab(tab)}
            className={`px-5 py-1.5 rounded-lg text-xs font-medium transition-all ${activeTab === tab ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8]'}`}>
            {tab === 'users' ? 'User Management' : 'Activity Log'}
          </button>
        ))}
      </div>

      {activeTab === 'users' && (
        <>
          {/* Toolbar */}
          <div className="bg-white rounded-2xl border border-[#E2E8F0] p-4 mb-4">
            <div className="flex items-center gap-3 flex-wrap">
              <div className="flex items-center gap-2 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl px-3 py-2 flex-1 min-w-48">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-4 h-4 text-[#94A3B8]">
                  <circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" />
                </svg>
                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Search users..."
                  className="bg-transparent text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none flex-1" />
              </div>

              <select value={filterRole} onChange={e => setFilterRole(e.target.value as FilterRole)}
                className="px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="all">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>

              <select value={filterStatus} onChange={e => setFilterStatus(e.target.value as FilterStatus)}
                className="px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>

              <button onClick={() => setShowAddModal(true)}
                className="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" className="w-4 h-4"><path d="M12 5v14M5 12h14" /></svg>
                Add User
              </button>
            </div>
          </div>

          {/* User list */}
          <div className="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden">
            <table className="w-full">
              <thead>
                <tr className="border-b border-[#E2E8F0] bg-[#F8FAFC]">
                  <th className="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-5 py-3">User</th>
                  <th className="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3 hidden md:table-cell">Email</th>
                  <th className="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3">Role</th>
                  <th className="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3">Status</th>
                  <th className="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3 hidden lg:table-cell">Last Active</th>
                  <th className="px-3 py-3" />
                </tr>
              </thead>
              <tbody>
                {filteredUsers.map((user, i) => (
                  <tr key={user.id} className={`border-b border-[#F1F5F9] last:border-0 hover:bg-[#F8FAFC] ${i % 2 === 1 ? 'bg-[#FAFCFC]' : ''}`}>
                    <td className="px-5 py-3.5">
                      <div className="flex items-center gap-3">
                        <div className={`w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 ${user.status === 'inactive' ? 'opacity-50' : ''}`} style={{ background: '#0BC5C1' }}>
                          {user.avatar}
                        </div>
                        <span className="text-sm font-medium text-[#1E293B]">{user.name}</span>
                      </div>
                    </td>
                    <td className="px-3 py-3.5 hidden md:table-cell">
                      <span className="text-sm text-[#64748B]">{user.email}</span>
                    </td>
                    <td className="px-3 py-3.5">
                      <span className={`text-xs px-2.5 py-1 rounded-full font-medium ${user.role === 'admin' ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-[#E8F9F9] text-[#0BC5C1]'}`}>
                        {user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                      </span>
                    </td>
                    <td className="px-3 py-3.5">
                      <span className={`text-xs px-2.5 py-1 rounded-full font-medium flex items-center gap-1 w-fit ${user.status === 'active' ? 'bg-[#ECFDF5] text-[#065F46]' : 'bg-[#F1F5F9] text-[#64748B]'}`}>
                        <span className={`w-1.5 h-1.5 rounded-full ${user.status === 'active' ? 'bg-[#10B981]' : 'bg-[#94A3B8]'}`} />
                        {user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                      </span>
                    </td>
                    <td className="px-3 py-3.5 hidden lg:table-cell">
                      <span className="text-sm text-[#94A3B8]">{user.lastActive}</span>
                    </td>
                    <td className="px-3 py-3.5">
                      <div className="flex items-center gap-1">
                        <button onClick={() => setSelectedUser(user)}
                          className="p-1.5 rounded-lg hover:bg-[#F1F5F9] text-[#94A3B8] hover:text-[#64748B]">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /></svg>
                        </button>
                        {user.role !== 'admin' && (
                          <button onClick={() => toggleStatus(user.id)}
                            className={`p-1.5 rounded-lg ${user.status === 'active' ? 'hover:bg-red-50 text-[#94A3B8] hover:text-red-500' : 'hover:bg-emerald-50 text-[#94A3B8] hover:text-emerald-500'}`}>
                            {user.status === 'active' ? (
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><circle cx="12" cy="12" r="10" /><line x1="15" y1="9" x2="9" y2="15" /><line x1="9" y1="9" x2="15" y2="15" /></svg>
                            ) : (
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" /><path d="M22 4L12 14.01l-3-3" /></svg>
                            )}
                          </button>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {filteredUsers.length === 0 && (
              <div className="py-12 text-center">
                <p className="text-sm text-[#94A3B8]">No users match your filters.</p>
              </div>
            )}
          </div>
        </>
      )}

      {activeTab === 'activity' && (
        <div className="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden">
          <div className="px-5 py-3 border-b border-[#E2E8F0] bg-[#F8FAFC]">
            <h3 className="font-display font-700 text-sm text-[#1E293B]">Admin Activity Log</h3>
          </div>
          <div className="divide-y divide-[#F1F5F9]">
            {ACTIVITY_LOGS.map(log => {
              const admin = USERS.find(u => u.id === log.adminId);
              return (
                <div key={log.id} className="px-5 py-4 flex items-start gap-4">
                  <div className="w-8 h-8 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {admin?.avatar ?? 'AD'}
                  </div>
                  <div className="flex-1">
                    <p className="text-sm text-[#1E293B]">
                      <span className="font-semibold">{admin?.name ?? 'Admin'}</span>{' '}
                      <span className="text-[#64748B]">{log.action}:</span>{' '}
                      <span className="text-[#0BC5C1]">{log.target}</span>
                    </p>
                    <p className="text-xs text-[#94A3B8] mt-0.5">{log.timestamp}</p>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      )}

      {showAddModal && <AddUserModal onAdd={addUser} onClose={() => setShowAddModal(false)} />}
      {selectedUser && (
        <UserDetailModal
          user={selectedUser}
          onClose={() => setSelectedUser(null)}
          onToggleStatus={() => { toggleStatus(selectedUser.id); setSelectedUser(null); }}
        />
      )}
    </div>
  );
}

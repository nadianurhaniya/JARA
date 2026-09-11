import { User } from '../../data/mockData';

type Page = 'dashboard' | 'tasks' | 'collaboration' | 'admin' | 'profile';

interface SidebarProps {
  currentPage: Page;
  onNavigate: (page: Page) => void;
  currentUser: User;
  notificationCount: number;
}

const navItems = [
  {
    id: 'dashboard' as Page,
    label: 'Dashboard',
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5">
        <rect x="3" y="3" width="7" height="7" rx="1" />
        <rect x="14" y="3" width="7" height="7" rx="1" />
        <rect x="3" y="14" width="7" height="7" rx="1" />
        <rect x="14" y="14" width="7" height="7" rx="1" />
      </svg>
    ),
  },
  {
    id: 'tasks' as Page,
    label: 'Tasks',
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5">
        <path d="M9 11l3 3L22 4" />
        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
      </svg>
    ),
  },
  {
    id: 'collaboration' as Page,
    label: 'Team',
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
        <circle cx="9" cy="7" r="4" />
        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
      </svg>
    ),
  },
];

export default function Sidebar({ currentPage, onNavigate, currentUser, notificationCount }: SidebarProps) {
  return (
    <aside className="w-16 md:w-56 h-full flex flex-col bg-white border-r border-[#E2E8F0] shrink-0">
      {/* Logo */}
      <div className="h-16 flex items-center px-4 border-b border-[#E2E8F0]">
        <div className="flex items-center gap-2.5">
          <div className="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm" style={{ background: 'linear-gradient(135deg, #0BC5C1, #0891B2)' }}>
            J
          </div>
          <span className="hidden md:block font-display font-800 text-lg text-[#1E293B] tracking-tight">JARA</span>
        </div>
      </div>

      {/* Navigation */}
      <nav className="flex-1 py-4 px-2">
        <div className="space-y-1">
          {navItems.map((item) => {
            const active = currentPage === item.id;
            return (
              <button
                key={item.id}
                onClick={() => onNavigate(item.id)}
                className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 group ${
                  active
                    ? 'bg-[#E8F9F9] text-[#0BC5C1]'
                    : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B]'
                }`}
              >
                <span className={active ? 'text-[#0BC5C1]' : 'text-[#94A3B8] group-hover:text-[#64748B]'}>
                  {item.icon}
                </span>
                <span className="hidden md:block">{item.label}</span>
                {active && <span className="hidden md:block ml-auto w-1.5 h-1.5 rounded-full bg-[#0BC5C1]" />}
              </button>
            );
          })}

          {currentUser.role === 'admin' && (
            <button
              onClick={() => onNavigate('admin')}
              className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 group ${
                currentPage === 'admin'
                  ? 'bg-[#E8F9F9] text-[#0BC5C1]'
                  : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B]'
              }`}
            >
              <span className={currentPage === 'admin' ? 'text-[#0BC5C1]' : 'text-[#94A3B8] group-hover:text-[#64748B]'}>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5">
                  <path d="M12 2L2 7l10 5 10-5-10-5z" />
                  <path d="M2 17l10 5 10-5" />
                  <path d="M2 12l10 5 10-5" />
                </svg>
              </span>
              <span className="hidden md:block">Admin</span>
              {currentPage === 'admin' && <span className="hidden md:block ml-auto w-1.5 h-1.5 rounded-full bg-[#0BC5C1]" />}
            </button>
          )}
        </div>
      </nav>

      {/* User profile */}
      <div className="p-3 border-t border-[#E2E8F0]">
        <button
          onClick={() => onNavigate('profile')}
          className="w-full flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-[#F8FAFC] group"
        >
          <div className="w-8 h-8 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold shrink-0">
            {currentUser.avatar}
          </div>
          <div className="hidden md:block text-left min-w-0">
            <p className="text-xs font-semibold text-[#1E293B] truncate">{currentUser.name}</p>
            <p className="text-xs text-[#94A3B8] truncate">{currentUser.role === 'admin' ? 'Administrator' : 'Member'}</p>
          </div>
          {notificationCount > 0 && (
            <span className="hidden md:flex ml-auto min-w-5 h-5 rounded-full bg-red-500 text-white text-xs items-center justify-center font-bold">
              {notificationCount}
            </span>
          )}
        </button>
      </div>
    </aside>
  );
}

import { useState } from 'react';
import AuthPage from './components/auth/AuthPage';
import Sidebar from './components/layout/Sidebar';
import Header from './components/layout/Header';
import Dashboard from './components/dashboard/Dashboard';
import TasksPage from './components/tasks/TasksPage';
import CollaborationPage from './components/collaboration/CollaborationPage';
import AdminPage from './components/admin/AdminPage';
import { CURRENT_USER, CURRENT_ADMIN, NOTIFICATIONS, Notification } from './data/mockData';

type Page = 'dashboard' | 'tasks' | 'collaboration' | 'admin' | 'profile';

const pageMeta: Record<Page, { title: string; subtitle?: string }> = {
  dashboard: { title: 'Dashboard', subtitle: 'Your task overview and team activity' },
  tasks: { title: 'Tasks & Projects', subtitle: 'Manage your projects and tasks' },
  collaboration: { title: 'Team Collaboration', subtitle: 'Members, invitations, and assignments' },
  admin: { title: 'Admin Panel', subtitle: 'Manage users and system activity' },
  profile: { title: 'Profile', subtitle: 'Your account settings' },
};

export default function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [isAdmin, setIsAdmin] = useState(false);
  const [currentPage, setCurrentPage] = useState<Page>('dashboard');
  const [notifications, setNotifications] = useState<Notification[]>(NOTIFICATIONS);

  const currentUser = isAdmin ? CURRENT_ADMIN : CURRENT_USER;

  const handleLogin = (asAdmin = false) => {
    setIsAdmin(asAdmin);
    setIsLoggedIn(true);
    setCurrentPage('dashboard');
  };

  const markRead = (id: string) => {
    setNotifications(notifications.map(n => n.id === id ? { ...n, read: true } : n));
  };

  const unreadCount = notifications.filter(n => !n.read).length;

  const handleNavigate = (page: Page) => {
    if (page === 'profile') return; // no-op for now
    setCurrentPage(page);
  };

  if (!isLoggedIn) {
    return <AuthPage onLogin={handleLogin} />;
  }

  const meta = pageMeta[currentPage];

  return (
    <div className="h-full flex bg-[#F0FAFA]">
      <Sidebar
        currentPage={currentPage}
        onNavigate={handleNavigate}
        currentUser={currentUser}
        notificationCount={unreadCount}
      />

      <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
        <Header
          title={meta.title}
          subtitle={meta.subtitle}
          currentUser={currentUser}
          notifications={notifications}
          onMarkRead={markRead}
        />

        <main className="flex-1 flex overflow-hidden">
          {currentPage === 'dashboard' && <Dashboard />}
          {currentPage === 'tasks' && <TasksPage />}
          {currentPage === 'collaboration' && <CollaborationPage />}
          {currentPage === 'admin' && isAdmin && <AdminPage />}
          {currentPage === 'admin' && !isAdmin && (
            <div className="flex-1 flex items-center justify-center">
              <div className="text-center">
                <div className="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                  <svg viewBox="0 0 24 24" fill="none" stroke="#EF4444" strokeWidth="1.5" className="w-8 h-8">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" /><path d="M7 11V7a5 5 0 0110 0v4" />
                  </svg>
                </div>
                <p className="font-display font-700 text-[#1E293B] text-lg mb-1">Access Denied</p>
                <p className="text-sm text-[#94A3B8]">You don't have permission to view the Admin panel.</p>
              </div>
            </div>
          )}
        </main>

        {/* Role indicator bar */}
        <div className="px-4 py-2 border-t border-[#E2E8F0] bg-white flex items-center justify-between">
          <div className="flex items-center gap-2">
            <span className={`w-2 h-2 rounded-full ${currentUser.status === 'active' ? 'bg-[#10B981]' : 'bg-[#94A3B8]'}`} />
            <span className="text-xs text-[#94A3B8]">Logged in as <strong className="text-[#64748B]">{currentUser.name}</strong> · {currentUser.role === 'admin' ? 'Administrator' : 'Member'}</span>
          </div>
          <button
            onClick={() => { setIsLoggedIn(false); setIsAdmin(false); setCurrentPage('dashboard'); }}
            className="text-xs text-[#94A3B8] hover:text-red-500 flex items-center gap-1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5">
              <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
            </svg>
            Logout
          </button>
        </div>
      </div>
    </div>
  );
}

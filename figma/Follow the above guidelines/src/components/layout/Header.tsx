import { Notification, User } from '../../data/mockData';
import { useState } from 'react';

interface HeaderProps {
  title: string;
  subtitle?: string;
  currentUser: User;
  notifications: Notification[];
  onMarkRead: (id: string) => void;
}

export default function Header({ title, subtitle, currentUser, notifications, onMarkRead }: HeaderProps) {
  const [showNotifs, setShowNotifs] = useState(false);
  const unread = notifications.filter(n => !n.read).length;

  const notifTypeIcon: Record<string, string> = {
    invite: '👥',
    assignment: '📋',
    deadline: '⏰',
    mention: '💬',
  };

  return (
    <header className="h-16 px-6 flex items-center justify-between border-b border-[#E2E8F0] bg-white shrink-0 relative z-20">
      <div>
        <h1 className="font-display font-700 text-xl text-[#1E293B]">{title}</h1>
        {subtitle && <p className="text-xs text-[#94A3B8] mt-0.5">{subtitle}</p>}
      </div>

      <div className="flex items-center gap-3">
        {/* Search */}
        <div className="hidden md:flex items-center gap-2 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl px-3 py-2 w-52">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-4 h-4 text-[#94A3B8]">
            <circle cx="11" cy="11" r="8" />
            <path d="M21 21l-4.35-4.35" />
          </svg>
          <input
            type="text"
            placeholder="Search..."
            className="bg-transparent text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none w-full"
          />
        </div>

        {/* Notifications */}
        <div className="relative">
          <button
            onClick={() => setShowNotifs(!showNotifs)}
            className="relative w-9 h-9 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center text-[#64748B] hover:bg-[#E8F9F9] hover:text-[#0BC5C1] hover:border-[#0BC5C1]"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-4 h-4">
              <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
            </svg>
            {unread > 0 && (
              <span className="absolute -top-1 -right-1 min-w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center font-bold px-0.5">
                {unread}
              </span>
            )}
          </button>

          {showNotifs && (
            <div className="absolute right-0 top-11 w-80 bg-white rounded-2xl shadow-xl border border-[#E2E8F0] z-50 overflow-hidden">
              <div className="px-4 py-3 border-b border-[#E2E8F0] flex items-center justify-between">
                <h3 className="font-display font-700 text-sm text-[#1E293B]">Notifications</h3>
                {unread > 0 && <span className="text-xs text-[#0BC5C1]">{unread} unread</span>}
              </div>
              <div className="max-h-72 overflow-y-auto">
                {notifications.length === 0 ? (
                  <p className="px-4 py-6 text-sm text-[#94A3B8] text-center">No notifications</p>
                ) : (
                  notifications.map(n => (
                    <button
                      key={n.id}
                      onClick={() => { onMarkRead(n.id); setShowNotifs(false); }}
                      className={`w-full px-4 py-3 flex gap-3 text-left hover:bg-[#F8FAFC] border-b border-[#F1F5F9] last:border-0 ${!n.read ? 'bg-[#F0FAFA]' : ''}`}
                    >
                      <span className="text-base shrink-0 mt-0.5">{notifTypeIcon[n.type]}</span>
                      <div className="min-w-0">
                        <p className="text-xs text-[#1E293B] leading-relaxed">{n.message}</p>
                        <p className="text-xs text-[#94A3B8] mt-1">{n.createdAt}</p>
                      </div>
                      {!n.read && <span className="w-2 h-2 rounded-full bg-[#0BC5C1] shrink-0 mt-1.5" />}
                    </button>
                  ))
                )}
              </div>
            </div>
          )}
        </div>

        {/* User avatar */}
        <div className="w-8 h-8 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold">
          {currentUser.avatar}
        </div>
      </div>
    </header>
  );
}

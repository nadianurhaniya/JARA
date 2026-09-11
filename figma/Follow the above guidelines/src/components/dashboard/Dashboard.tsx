import { useState } from 'react';
import {
  AreaChart, Area, BarChart, Bar, PieChart, Pie, Cell,
  XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer
} from 'recharts';
import {
  TASKS, PROJECTS, USERS, WEEKLY_PROGRESS, MONTHLY_PROGRESS,
  PRIORITY_DISTRIBUTION, MEMBER_PROGRESS, CURRENT_USER
} from '../../data/mockData';

const statusColors: Record<string, string> = {
  completed: '#10B981',
  in_progress: '#0BC5C1',
  not_started: '#94A3B8',
};

export default function Dashboard() {
  const [timeRange, setTimeRange] = useState<'weekly' | 'monthly'>('weekly');

  const total = TASKS.length;
  const completed = TASKS.filter(t => t.status === 'completed').length;
  const inProgress = TASKS.filter(t => t.status === 'in_progress').length;
  const notStarted = TASKS.filter(t => t.status === 'not_started').length;
  const overdue = TASKS.filter(t => {
    const d = new Date(t.deadline);
    return d < new Date() && t.status !== 'completed';
  }).length;
  const pct = Math.round((completed / total) * 100);

  const chartData: Record<string, number | string>[] = timeRange === 'weekly'
    ? WEEKLY_PROGRESS.map(d => ({ label: d.week, completed: d.completed, created: d.created }))
    : MONTHLY_PROGRESS.map(d => ({ label: d.month, completed: d.completed, created: d.created }));
  const xKey = 'label';

  const upcomingTasks = TASKS
    .filter(t => t.status !== 'completed')
    .sort((a, b) => new Date(a.deadline).getTime() - new Date(b.deadline).getTime())
    .slice(0, 5);

  const recentActivity = [
    { user: 'Eko Prasetyo', action: 'completed', task: 'Setup Storybook', time: '2h ago', color: '#10B981' },
    { user: 'Citra Dewi', action: 'updated', task: 'SEO audit and optimization', time: '4h ago', color: '#0BC5C1' },
    { user: 'Budi Hartono', action: 'created', task: 'Performance testing', time: '6h ago', color: '#8B5CF6' },
    { user: 'Farah Nadia', action: 'commented on', task: 'Social media content calendar', time: '1d ago', color: '#F59E0B' },
    { user: 'Eko Prasetyo', action: 'started', task: 'Task list screen', time: '1d ago', color: '#0BC5C1' },
  ];

  const daysUntil = (date: string) => {
    const diff = new Date(date).getTime() - Date.now();
    const days = Math.ceil(diff / 86400000);
    if (days < 0) return <span className="text-red-500 text-xs font-medium">Overdue {Math.abs(days)}d</span>;
    if (days === 0) return <span className="text-orange-500 text-xs font-medium">Due today</span>;
    if (days <= 3) return <span className="text-orange-400 text-xs font-medium">Due in {days}d</span>;
    return <span className="text-[#94A3B8] text-xs">{days}d left</span>;
  };

  const priorityColor: Record<string, string> = { high: '#EF4444', medium: '#F59E0B', low: '#10B981' };

  const StatCard = ({ label, value, sub, color, icon }: { label: string; value: number | string; sub?: string; color: string; icon: React.ReactNode }) => (
    <div className="bg-white rounded-2xl p-5 border border-[#E2E8F0] hover:shadow-md">
      <div className="flex items-start justify-between mb-3">
        <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ background: color + '18' }}>
          <span style={{ color }}>{icon}</span>
        </div>
      </div>
      <p className="text-2xl font-display font-800 text-[#1E293B]">{value}</p>
      <p className="text-sm text-[#64748B] mt-0.5">{label}</p>
      {sub && <p className="text-xs text-[#94A3B8] mt-1">{sub}</p>}
    </div>
  );

  return (
    <div className="flex-1 overflow-y-auto p-6 space-y-6">
      {/* Greeting */}
      <div>
        <h2 className="font-display font-700 text-2xl text-[#1E293B]">
          Good morning, {CURRENT_USER.name.split(' ')[0]} 👋
        </h2>
        <p className="text-sm text-[#64748B] mt-1">Here's what's happening with your projects today.</p>
      </div>

      {/* Stat cards */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Total Tasks" value={total} color="#0BC5C1" icon={
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5"><rect x="9" y="9" width="13" height="13" rx="2" /><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" /></svg>
        } />
        <StatCard label="Completed" value={completed} sub={`${pct}% done`} color="#10B981" icon={
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" /><path d="M22 4L12 14.01l-3-3" /></svg>
        } />
        <StatCard label="In Progress" value={inProgress} color="#8B5CF6" icon={
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
        } />
        <StatCard label="Overdue" value={overdue} color="#EF4444" icon={
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /><line x1="12" y1="9" x2="12" y2="13" /><line x1="12" y1="17" x2="12.01" y2="17" /></svg>
        } />
      </div>

      {/* Progress ring + project summary */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {/* Overall progress */}
        <div className="bg-white rounded-2xl p-6 border border-[#E2E8F0] flex flex-col items-center justify-center">
          <p className="font-display font-700 text-sm text-[#64748B] uppercase tracking-wide mb-4">Overall Progress</p>
          <div className="relative w-36 h-36">
            <svg viewBox="0 0 120 120" className="w-full h-full -rotate-90">
              <circle cx="60" cy="60" r="52" fill="none" stroke="#E2E8F0" strokeWidth="10" />
              <circle
                cx="60" cy="60" r="52" fill="none"
                stroke="#0BC5C1" strokeWidth="10"
                strokeLinecap="round"
                strokeDasharray={`${2 * Math.PI * 52}`}
                strokeDashoffset={`${2 * Math.PI * 52 * (1 - pct / 100)}`}
                className="transition-all duration-700"
              />
            </svg>
            <div className="absolute inset-0 flex flex-col items-center justify-center">
              <span className="font-display font-800 text-3xl text-[#1E293B]">{pct}%</span>
              <span className="text-xs text-[#94A3B8]">complete</span>
            </div>
          </div>
          <div className="grid grid-cols-3 gap-3 mt-5 w-full">
            {[
              { label: 'Done', count: completed, color: '#10B981' },
              { label: 'Active', count: inProgress, color: '#0BC5C1' },
              { label: 'Pending', count: notStarted, color: '#94A3B8' },
            ].map(s => (
              <div key={s.label} className="text-center">
                <div className="w-2 h-2 rounded-full mx-auto mb-1" style={{ background: s.color }} />
                <p className="font-display font-700 text-sm text-[#1E293B]">{s.count}</p>
                <p className="text-xs text-[#94A3B8]">{s.label}</p>
              </div>
            ))}
          </div>
        </div>

        {/* Project summary */}
        <div className="lg:col-span-2 bg-white rounded-2xl p-6 border border-[#E2E8F0]">
          <h3 className="font-display font-700 text-sm text-[#1E293B] mb-4">Project Overview</h3>
          <div className="space-y-4">
            {PROJECTS.map(p => {
              const pTasks = TASKS.filter(t => t.projectId === p.id);
              const pDone = pTasks.filter(t => t.status === 'completed').length;
              const pPct = pTasks.length ? Math.round((pDone / pTasks.length) * 100) : 0;
              const daysLeft = Math.ceil((new Date(p.deadline).getTime() - Date.now()) / 86400000);
              return (
                <div key={p.id}>
                  <div className="flex items-center justify-between mb-1.5">
                    <div className="flex items-center gap-2">
                      <div className="w-3 h-3 rounded-full" style={{ background: p.color }} />
                      <span className="text-sm font-medium text-[#1E293B]">{p.name}</span>
                    </div>
                    <div className="flex items-center gap-3">
                      <span className="text-xs text-[#94A3B8]">{pDone}/{pTasks.length} tasks</span>
                      <span className={`text-xs font-medium ${daysLeft < 30 ? 'text-orange-500' : 'text-[#64748B]'}`}>{daysLeft}d left</span>
                      <span className="font-display font-700 text-sm text-[#1E293B]">{pPct}%</span>
                    </div>
                  </div>
                  <div className="h-2 bg-[#F1F5F9] rounded-full overflow-hidden">
                    <div className="h-full rounded-full transition-all duration-700" style={{ width: `${pPct}%`, background: p.color }} />
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* Charts row */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {/* Progress chart */}
        <div className="lg:col-span-2 bg-white rounded-2xl p-6 border border-[#E2E8F0]">
          <div className="flex items-center justify-between mb-5">
            <h3 className="font-display font-700 text-sm text-[#1E293B]">Task Completion Trend</h3>
            <div className="flex bg-[#F1F5F9] rounded-lg p-0.5">
              {(['weekly', 'monthly'] as const).map(t => (
                <button key={t} onClick={() => setTimeRange(t)}
                  className={`px-3 py-1 rounded-md text-xs font-medium transition-all ${timeRange === t ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8]'}`}>
                  {t.charAt(0).toUpperCase() + t.slice(1)}
                </button>
              ))}
            </div>
          </div>
          <ResponsiveContainer width="100%" height={200}>
            <AreaChart data={chartData}>
              <defs>
                <linearGradient id="gradCompleted" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#0BC5C1" stopOpacity={0.2} />
                  <stop offset="95%" stopColor="#0BC5C1" stopOpacity={0} />
                </linearGradient>
                <linearGradient id="gradCreated" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#8B5CF6" stopOpacity={0.2} />
                  <stop offset="95%" stopColor="#8B5CF6" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#F1F5F9" />
              <XAxis dataKey={xKey} tick={{ fontSize: 11, fill: '#94A3B8' }} axisLine={false} tickLine={false} />
              <YAxis tick={{ fontSize: 11, fill: '#94A3B8' }} axisLine={false} tickLine={false} />
              <Tooltip contentStyle={{ borderRadius: '12px', border: '1px solid #E2E8F0', fontSize: 12 }} />
              <Legend iconType="circle" iconSize={8} wrapperStyle={{ fontSize: 12 }} />
              <Area type="monotone" dataKey="completed" stroke="#0BC5C1" strokeWidth={2} fill="url(#gradCompleted)" name="Completed" />
              <Area type="monotone" dataKey="created" stroke="#8B5CF6" strokeWidth={2} fill="url(#gradCreated)" name="Created" />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        {/* Priority distribution */}
        <div className="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
          <h3 className="font-display font-700 text-sm text-[#1E293B] mb-5">Priority Distribution</h3>
          <ResponsiveContainer width="100%" height={150}>
            <PieChart>
              <Pie data={PRIORITY_DISTRIBUTION} cx="50%" cy="50%" innerRadius={45} outerRadius={70} paddingAngle={3} dataKey="value">
                {PRIORITY_DISTRIBUTION.map((entry, i) => <Cell key={i} fill={entry.color} />)}
              </Pie>
              <Tooltip contentStyle={{ borderRadius: '12px', border: '1px solid #E2E8F0', fontSize: 12 }} />
            </PieChart>
          </ResponsiveContainer>
          <div className="space-y-2 mt-2">
            {PRIORITY_DISTRIBUTION.map(p => (
              <div key={p.name} className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <div className="w-2.5 h-2.5 rounded-full" style={{ background: p.color }} />
                  <span className="text-xs text-[#64748B]">{p.name}</span>
                </div>
                <span className="text-xs font-semibold text-[#1E293B]">{p.value} tasks</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Member progress + Activity + Upcoming */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {/* Member progress */}
        <div className="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
          <h3 className="font-display font-700 text-sm text-[#1E293B] mb-5">Team Progress</h3>
          <ResponsiveContainer width="100%" height={160}>
            <BarChart data={MEMBER_PROGRESS} layout="vertical" barSize={8} barGap={2}>
              <XAxis type="number" tick={{ fontSize: 10, fill: '#94A3B8' }} axisLine={false} tickLine={false} />
              <YAxis type="category" dataKey="name" tick={{ fontSize: 11, fill: '#64748B' }} axisLine={false} tickLine={false} width={58} />
              <Tooltip contentStyle={{ borderRadius: '12px', border: '1px solid #E2E8F0', fontSize: 12 }} />
              <Bar dataKey="completed" fill="#0BC5C1" name="Completed" radius={[0, 4, 4, 0]} />
              <Bar dataKey="inProgress" fill="#E2E8F0" name="In Progress" radius={[0, 4, 4, 0]} />
            </BarChart>
          </ResponsiveContainer>
          <div className="flex gap-4 mt-2">
            <div className="flex items-center gap-1.5"><div className="w-2 h-2 rounded-full bg-[#0BC5C1]" /><span className="text-xs text-[#94A3B8]">Completed</span></div>
            <div className="flex items-center gap-1.5"><div className="w-2 h-2 rounded-full bg-[#E2E8F0]" /><span className="text-xs text-[#94A3B8]">In Progress</span></div>
          </div>
        </div>

        {/* Recent activity */}
        <div className="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
          <h3 className="font-display font-700 text-sm text-[#1E293B] mb-4">Recent Activity</h3>
          <div className="space-y-3">
            {recentActivity.map((a, i) => (
              <div key={i} className="flex gap-3">
                <div className="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style={{ background: a.color }}>
                  {a.user.charAt(0)}
                </div>
                <div className="min-w-0">
                  <p className="text-xs text-[#1E293B] leading-relaxed">
                    <span className="font-semibold">{a.user.split(' ')[0]}</span> {a.action} <span className="text-[#0BC5C1] font-medium">"{a.task}"</span>
                  </p>
                  <p className="text-xs text-[#94A3B8] mt-0.5">{a.time}</p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Upcoming deadlines */}
        <div className="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
          <h3 className="font-display font-700 text-sm text-[#1E293B] mb-4">Upcoming Deadlines</h3>
          <div className="space-y-3">
            {upcomingTasks.map(t => (
              <div key={t.id} className="flex items-center gap-3 p-2 rounded-xl hover:bg-[#F8FAFC]">
                <div className="w-2 h-2 rounded-full shrink-0" style={{ background: priorityColor[t.priority] }} />
                <div className="flex-1 min-w-0">
                  <p className="text-xs font-medium text-[#1E293B] truncate">{t.title}</p>
                  <p className="text-xs text-[#94A3B8] truncate">{PROJECTS.find(p => p.id === t.projectId)?.name}</p>
                </div>
                <div className="shrink-0">{daysUntil(t.deadline)}</div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

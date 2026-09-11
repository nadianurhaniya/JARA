import { useState } from 'react';
import { TASKS, PROJECTS, USERS, Task, Project, TaskStatus, Priority } from '../../data/mockData';

type FilterStatus = 'all' | TaskStatus;
type FilterPriority = 'all' | Priority;
type SortBy = 'deadline' | 'priority' | 'created';

const priorityOrder: Record<Priority, number> = { high: 0, medium: 1, low: 2 };
const priorityColor: Record<Priority, string> = { high: '#EF4444', medium: '#F59E0B', low: '#10B981' };
const priorityBg: Record<Priority, string> = { high: '#FEF2F2', medium: '#FFFBEB', low: '#ECFDF5' };
const statusLabel: Record<TaskStatus, string> = { not_started: 'Not Started', in_progress: 'In Progress', completed: 'Completed' };
const statusColor: Record<TaskStatus, string> = { not_started: '#94A3B8', in_progress: '#0BC5C1', completed: '#10B981' };
const statusBg: Record<TaskStatus, string> = { not_started: '#F1F5F9', in_progress: '#E8F9F9', completed: '#ECFDF5' };

interface TaskModalProps {
  task?: Task | null;
  projectId?: string;
  onSave: (task: Partial<Task>) => void;
  onClose: () => void;
}

function TaskModal({ task, projectId, onSave, onClose }: TaskModalProps) {
  const [title, setTitle] = useState(task?.title ?? '');
  const [description, setDescription] = useState(task?.description ?? '');
  const [status, setStatus] = useState<TaskStatus>(task?.status ?? 'not_started');
  const [priority, setPriority] = useState<Priority>(task?.priority ?? 'medium');
  const [deadline, setDeadline] = useState(task?.deadline ?? '');
  const [assigneeId, setAssigneeId] = useState(task?.assigneeId ?? '');
  const [subtasks, setSubtasks] = useState(task?.subtasks ?? []);
  const [newSubtask, setNewSubtask] = useState('');

  const addSubtask = () => {
    if (!newSubtask.trim()) return;
    setSubtasks([...subtasks, { id: `st${Date.now()}`, title: newSubtask.trim(), completed: false }]);
    setNewSubtask('');
  };

  const handleSave = () => {
    if (!title.trim()) return;
    onSave({ title, description, status, priority, deadline, assigneeId: assigneeId || null, subtasks, projectId: task?.projectId ?? projectId });
  };

  return (
    <div className="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onClick={onClose}>
      <div className="bg-white rounded-2xl w-full max-w-lg shadow-2xl max-h-[90vh] overflow-y-auto" onClick={e => e.stopPropagation()}>
        <div className="p-6 border-b border-[#E2E8F0] flex items-center justify-between">
          <h2 className="font-display font-700 text-lg text-[#1E293B]">{task ? 'Edit Task' : 'New Task'}</h2>
          <button onClick={onClose} className="text-[#94A3B8] hover:text-[#64748B]">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5"><path d="M18 6L6 18M6 6l12 12" /></svg>
          </button>
        </div>
        <div className="p-6 space-y-4">
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Task Title *</label>
            <input value={title} onChange={e => setTitle(e.target.value)} placeholder="Enter task title..."
              className="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1]" />
          </div>
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Description</label>
            <textarea value={description} onChange={e => setDescription(e.target.value)} placeholder="Task details..."
              rows={3} className="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] resize-none" />
          </div>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Status</label>
              <select value={status} onChange={e => setStatus(e.target.value as TaskStatus)}
                className="w-full px-3 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] bg-white">
                {(['not_started', 'in_progress', 'completed'] as TaskStatus[]).map(s => (
                  <option key={s} value={s}>{statusLabel[s]}</option>
                ))}
              </select>
            </div>
            <div>
              <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Priority</label>
              <select value={priority} onChange={e => setPriority(e.target.value as Priority)}
                className="w-full px-3 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] bg-white">
                {(['high', 'medium', 'low'] as Priority[]).map(p => (
                  <option key={p} value={p}>{p.charAt(0).toUpperCase() + p.slice(1)}</option>
                ))}
              </select>
            </div>
          </div>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Deadline</label>
              <input type="date" value={deadline} onChange={e => setDeadline(e.target.value)}
                className="w-full px-3 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] bg-white" />
            </div>
            <div>
              <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Assignee</label>
              <select value={assigneeId} onChange={e => setAssigneeId(e.target.value)}
                className="w-full px-3 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#1E293B] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] bg-white">
                <option value="">Unassigned</option>
                {USERS.filter(u => u.status === 'active').map(u => (
                  <option key={u.id} value={u.id}>{u.name}</option>
                ))}
              </select>
            </div>
          </div>

          {/* Subtasks */}
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Subtasks</label>
            <div className="space-y-2">
              {subtasks.map((st, i) => (
                <div key={st.id} className="flex items-center gap-2">
                  <input type="checkbox" checked={st.completed} onChange={e => {
                    const next = [...subtasks]; next[i] = { ...st, completed: e.target.checked }; setSubtasks(next);
                  }} className="accent-[#0BC5C1]" />
                  <span className={`text-sm flex-1 ${st.completed ? 'line-through text-[#94A3B8]' : 'text-[#1E293B]'}`}>{st.title}</span>
                  <button onClick={() => setSubtasks(subtasks.filter((_, j) => j !== i))} className="text-[#94A3B8] hover:text-red-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><path d="M18 6L6 18M6 6l12 12" /></svg>
                  </button>
                </div>
              ))}
              <div className="flex gap-2">
                <input value={newSubtask} onChange={e => setNewSubtask(e.target.value)}
                  onKeyDown={e => e.key === 'Enter' && addSubtask()}
                  placeholder="Add subtask..."
                  className="flex-1 px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm outline-none focus:border-[#0BC5C1]" />
                <button onClick={addSubtask} className="px-3 py-2 rounded-xl bg-[#E8F9F9] text-[#0BC5C1] text-sm font-medium hover:bg-[#0BC5C1] hover:text-white">Add</button>
              </div>
            </div>
          </div>
        </div>
        <div className="px-6 pb-6 flex gap-3 justify-end">
          <button onClick={onClose} className="px-5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
          <button onClick={handleSave} className="px-5 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
            {task ? 'Save Changes' : 'Create Task'}
          </button>
        </div>
      </div>
    </div>
  );
}

interface ProjectModalProps {
  project?: Project | null;
  onSave: (p: Partial<Project>) => void;
  onClose: () => void;
}

function ProjectModal({ project, onSave, onClose }: ProjectModalProps) {
  const [name, setName] = useState(project?.name ?? '');
  const [description, setDescription] = useState(project?.description ?? '');
  const [color, setColor] = useState(project?.color ?? '#0BC5C1');
  const [deadline, setDeadline] = useState(project?.deadline ?? '');
  const colors = ['#0BC5C1', '#8B5CF6', '#F59E0B', '#EF4444', '#10B981', '#3B82F6', '#EC4899'];

  return (
    <div className="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onClick={onClose}>
      <div className="bg-white rounded-2xl w-full max-w-md shadow-2xl" onClick={e => e.stopPropagation()}>
        <div className="p-6 border-b border-[#E2E8F0] flex items-center justify-between">
          <h2 className="font-display font-700 text-lg text-[#1E293B]">{project ? 'Edit Project' : 'New Project'}</h2>
          <button onClick={onClose} className="text-[#94A3B8] hover:text-[#64748B]">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-5 h-5"><path d="M18 6L6 18M6 6l12 12" /></svg>
          </button>
        </div>
        <div className="p-6 space-y-4">
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Project Name *</label>
            <input value={name} onChange={e => setName(e.target.value)} placeholder="Project name..."
              className="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1]" />
          </div>
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Description</label>
            <textarea value={description} onChange={e => setDescription(e.target.value)} rows={2} placeholder="What is this project about?"
              className="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] resize-none" />
          </div>
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Color</label>
            <div className="flex gap-2">
              {colors.map(c => (
                <button key={c} onClick={() => setColor(c)}
                  className={`w-7 h-7 rounded-full border-2 transition-transform ${color === c ? 'border-[#1E293B] scale-110' : 'border-transparent'}`}
                  style={{ background: c }} />
              ))}
            </div>
          </div>
          <div>
            <label className="text-xs font-semibold text-[#475569] uppercase tracking-wide block mb-1.5">Deadline</label>
            <input type="date" value={deadline} onChange={e => setDeadline(e.target.value)}
              className="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] bg-white" />
          </div>
        </div>
        <div className="px-6 pb-6 flex gap-3 justify-end">
          <button onClick={onClose} className="px-5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
          <button onClick={() => { if (name.trim()) onSave({ name, description, color, deadline }); }} className="px-5 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
            {project ? 'Save Changes' : 'Create Project'}
          </button>
        </div>
      </div>
    </div>
  );
}

export default function TasksPage() {
  const [tasks, setTasks] = useState<Task[]>(TASKS);
  const [projects, setProjects] = useState<Project[]>(PROJECTS);
  const [activeProjectId, setActiveProjectId] = useState<string | null>(null);
  const [filterStatus, setFilterStatus] = useState<FilterStatus>('all');
  const [filterPriority, setFilterPriority] = useState<FilterPriority>('all');
  const [sortBy, setSortBy] = useState<SortBy>('deadline');
  const [search, setSearch] = useState('');
  const [showTaskModal, setShowTaskModal] = useState(false);
  const [editingTask, setEditingTask] = useState<Task | null>(null);
  const [showProjectModal, setShowProjectModal] = useState(false);
  const [editingProject, setEditingProject] = useState<Project | null>(null);
  const [deleteConfirm, setDeleteConfirm] = useState<{ type: 'task' | 'project'; id: string } | null>(null);

  const activeProject = activeProjectId ? projects.find(p => p.id === activeProjectId) : null;

  const filteredTasks = tasks
    .filter(t => activeProjectId ? t.projectId === activeProjectId : true)
    .filter(t => filterStatus === 'all' || t.status === filterStatus)
    .filter(t => filterPriority === 'all' || t.priority === filterPriority)
    .filter(t => !search || t.title.toLowerCase().includes(search.toLowerCase()))
    .sort((a, b) => {
      if (sortBy === 'deadline') return new Date(a.deadline).getTime() - new Date(b.deadline).getTime();
      if (sortBy === 'priority') return priorityOrder[a.priority] - priorityOrder[b.priority];
      return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime();
    });

  const saveTask = (data: Partial<Task>) => {
    if (editingTask) {
      setTasks(tasks.map(t => t.id === editingTask.id ? { ...t, ...data } : t));
    } else {
      const newTask: Task = {
        id: `t${Date.now()}`, createdAt: new Date().toISOString().split('T')[0],
        tags: [], subtasks: [], assigneeId: null,
        title: '', description: '', status: 'not_started', priority: 'medium', deadline: '', projectId: activeProjectId ?? projects[0]?.id,
        ...data,
      };
      setTasks([...tasks, newTask]);
    }
    setShowTaskModal(false); setEditingTask(null);
  };

  const saveProject = (data: Partial<Project>) => {
    if (editingProject) {
      setProjects(projects.map(p => p.id === editingProject.id ? { ...p, ...data } : p));
    } else {
      const newProject: Project = {
        id: `p${Date.now()}`, ownerId: 'u2', members: [{ userId: 'u2', role: 'owner', joinedAt: new Date().toISOString().split('T')[0] }],
        invitations: [], createdAt: new Date().toISOString().split('T')[0],
        name: '', description: '', color: '#0BC5C1', deadline: '',
        ...data,
      };
      setProjects([...projects, newProject]);
    }
    setShowProjectModal(false); setEditingProject(null);
  };

  const confirmDelete = () => {
    if (!deleteConfirm) return;
    if (deleteConfirm.type === 'task') {
      setTasks(tasks.filter(t => t.id !== deleteConfirm.id));
    } else {
      setProjects(projects.filter(p => p.id !== deleteConfirm.id));
      setTasks(tasks.filter(t => t.projectId !== deleteConfirm.id));
      if (activeProjectId === deleteConfirm.id) setActiveProjectId(null);
    }
    setDeleteConfirm(null);
  };

  const daysUntilDeadline = (d: string) => {
    const diff = Math.ceil((new Date(d).getTime() - Date.now()) / 86400000);
    if (diff < 0) return { label: `${Math.abs(diff)}d overdue`, class: 'text-red-500' };
    if (diff === 0) return { label: 'Due today', class: 'text-orange-500' };
    if (diff <= 3) return { label: `${diff}d left`, class: 'text-orange-400' };
    return { label: `${diff}d left`, class: 'text-[#94A3B8]' };
  };

  return (
    <div className="flex-1 flex overflow-hidden">
      {/* Project sidebar */}
      <div className="w-56 shrink-0 border-r border-[#E2E8F0] bg-white p-4 overflow-y-auto">
        <div className="flex items-center justify-between mb-3">
          <h3 className="font-display font-700 text-xs text-[#94A3B8] uppercase tracking-wide">Projects</h3>
          <button onClick={() => { setEditingProject(null); setShowProjectModal(true); }}
            className="w-6 h-6 rounded-lg bg-[#E8F9F9] text-[#0BC5C1] flex items-center justify-center hover:bg-[#0BC5C1] hover:text-white">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" className="w-3.5 h-3.5"><path d="M12 5v14M5 12h14" /></svg>
          </button>
        </div>

        <button
          onClick={() => setActiveProjectId(null)}
          className={`w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm mb-1 ${!activeProjectId ? 'bg-[#E8F9F9] text-[#0BC5C1] font-medium' : 'text-[#64748B] hover:bg-[#F8FAFC]'}`}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-4 h-4">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
          </svg>
          All Tasks
        </button>

        {projects.map(p => {
          const pTasks = tasks.filter(t => t.projectId === p.id);
          const pDone = pTasks.filter(t => t.status === 'completed').length;
          return (
            <button
              key={p.id}
              onClick={() => setActiveProjectId(p.id)}
              className={`w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm mb-1 group relative ${activeProjectId === p.id ? 'bg-[#E8F9F9] text-[#0BC5C1] font-medium' : 'text-[#64748B] hover:bg-[#F8FAFC]'}`}>
              <div className="w-2.5 h-2.5 rounded-full shrink-0" style={{ background: p.color }} />
              <span className="flex-1 text-left truncate">{p.name}</span>
              <span className="text-xs opacity-60">{pDone}/{pTasks.length}</span>
            </button>
          );
        })}
      </div>

      {/* Main content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Toolbar */}
        <div className="px-6 py-4 border-b border-[#E2E8F0] bg-white">
          <div className="flex items-center justify-between gap-4 flex-wrap">
            <div>
              {activeProject ? (
                <div className="flex items-center gap-2">
                  <div className="w-3 h-3 rounded-full" style={{ background: activeProject.color }} />
                  <h2 className="font-display font-700 text-base text-[#1E293B]">{activeProject.name}</h2>
                  <div className="flex gap-1 ml-2">
                    <button onClick={() => { setEditingProject(activeProject); setShowProjectModal(true); }}
                      className="p-1.5 rounded-lg hover:bg-[#F1F5F9] text-[#94A3B8] hover:text-[#64748B]">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                    </button>
                    <button onClick={() => setDeleteConfirm({ type: 'project', id: activeProject.id })}
                      className="p-1.5 rounded-lg hover:bg-red-50 text-[#94A3B8] hover:text-red-500">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><polyline points="3 6 5 6 21 6" /><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6M9 6V4h6v2" /></svg>
                    </button>
                  </div>
                </div>
              ) : (
                <h2 className="font-display font-700 text-base text-[#1E293B]">All Tasks</h2>
              )}
            </div>
            <div className="flex items-center gap-2 flex-wrap">
              {/* Search */}
              <div className="flex items-center gap-2 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl px-3 py-2 w-44">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5 text-[#94A3B8]">
                  <circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" />
                </svg>
                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Search tasks..." className="bg-transparent text-xs text-[#1E293B] placeholder-[#94A3B8] outline-none w-full" />
              </div>

              {/* Filters */}
              <select value={filterStatus} onChange={e => setFilterStatus(e.target.value as FilterStatus)}
                className="px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="all">All Status</option>
                <option value="not_started">Not Started</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
              </select>

              <select value={filterPriority} onChange={e => setFilterPriority(e.target.value as FilterPriority)}
                className="px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="all">All Priority</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
              </select>

              <select value={sortBy} onChange={e => setSortBy(e.target.value as SortBy)}
                className="px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="deadline">Sort: Deadline</option>
                <option value="priority">Sort: Priority</option>
                <option value="created">Sort: Created</option>
              </select>

              <button onClick={() => { setEditingTask(null); setShowTaskModal(true); }}
                className="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0BC5C1] text-white text-xs font-semibold hover:bg-[#0AAEAA]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" className="w-3.5 h-3.5"><path d="M12 5v14M5 12h14" /></svg>
                New Task
              </button>
            </div>
          </div>
        </div>

        {/* Task list */}
        <div className="flex-1 overflow-y-auto p-6">
          {filteredTasks.length === 0 ? (
            <div className="flex flex-col items-center justify-center h-full text-center">
              <div className="w-16 h-16 rounded-2xl bg-[#E8F9F9] flex items-center justify-center mb-4">
                <svg viewBox="0 0 24 24" fill="none" stroke="#0BC5C1" strokeWidth="1.5" className="w-8 h-8"><path d="M9 11l3 3L22 4" /><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
              </div>
              <p className="font-display font-700 text-[#1E293B] mb-1">No tasks found</p>
              <p className="text-sm text-[#94A3B8]">Create a new task or adjust your filters.</p>
            </div>
          ) : (
            <div className="space-y-2">
              {filteredTasks.map(task => {
                const assignee = USERS.find(u => u.id === task.assigneeId);
                const proj = projects.find(p => p.id === task.projectId);
                const deadline = daysUntilDeadline(task.deadline);
                const subDone = task.subtasks.filter(s => s.completed).length;
                return (
                  <div key={task.id} className="bg-white rounded-xl border border-[#E2E8F0] p-4 hover:shadow-sm group">
                    <div className="flex items-start gap-3">
                      {/* Checkbox */}
                      <button
                        onClick={() => setTasks(tasks.map(t => t.id === task.id ? { ...t, status: t.status === 'completed' ? 'not_started' : 'completed' } : t))}
                        className={`w-5 h-5 rounded-full border-2 shrink-0 mt-0.5 flex items-center justify-center ${task.status === 'completed' ? 'bg-[#10B981] border-[#10B981]' : 'border-[#CBD5E1] hover:border-[#0BC5C1]'}`}>
                        {task.status === 'completed' && (
                          <svg viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="3" className="w-3 h-3"><path d="M20 6L9 17l-5-5" /></svg>
                        )}
                      </button>

                      <div className="flex-1 min-w-0">
                        <div className="flex items-start justify-between gap-2">
                          <p className={`text-sm font-medium ${task.status === 'completed' ? 'line-through text-[#94A3B8]' : 'text-[#1E293B]'}`}>{task.title}</p>
                          <div className="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100">
                            <button onClick={() => { setEditingTask(task); setShowTaskModal(true); }}
                              className="p-1 rounded-lg hover:bg-[#F1F5F9] text-[#94A3B8] hover:text-[#64748B]">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                            </button>
                            <button onClick={() => setDeleteConfirm({ type: 'task', id: task.id })}
                              className="p-1 rounded-lg hover:bg-red-50 text-[#94A3B8] hover:text-red-500">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-3.5 h-3.5"><polyline points="3 6 5 6 21 6" /><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" /></svg>
                            </button>
                          </div>
                        </div>

                        {task.description && (
                          <p className="text-xs text-[#94A3B8] mt-0.5 truncate">{task.description}</p>
                        )}

                        <div className="flex items-center gap-3 mt-2 flex-wrap">
                          {/* Priority badge */}
                          <span className="text-xs font-medium px-2 py-0.5 rounded-full" style={{ background: priorityBg[task.priority], color: priorityColor[task.priority] }}>
                            {task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}
                          </span>

                          {/* Status badge */}
                          <span className="text-xs font-medium px-2 py-0.5 rounded-full" style={{ background: statusBg[task.status], color: statusColor[task.status] }}>
                            {statusLabel[task.status]}
                          </span>

                          {/* Project */}
                          {!activeProjectId && proj && (
                            <span className="text-xs text-[#64748B] flex items-center gap-1">
                              <div className="w-1.5 h-1.5 rounded-full" style={{ background: proj.color }} />
                              {proj.name}
                            </span>
                          )}

                          {/* Subtasks */}
                          {task.subtasks.length > 0 && (
                            <span className="text-xs text-[#94A3B8]">{subDone}/{task.subtasks.length} subtasks</span>
                          )}

                          {/* Deadline */}
                          {task.deadline && (
                            <span className={`text-xs font-medium ml-auto ${deadline.class}`}>{deadline.label}</span>
                          )}

                          {/* Assignee */}
                          {assignee && (
                            <div className="flex items-center gap-1">
                              <div className="w-5 h-5 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold">
                                {assignee.avatar}
                              </div>
                              <span className="text-xs text-[#64748B]">{assignee.name.split(' ')[0]}</span>
                            </div>
                          )}
                        </div>

                        {/* Subtask progress bar */}
                        {task.subtasks.length > 0 && (
                          <div className="mt-2 h-1 bg-[#F1F5F9] rounded-full overflow-hidden w-48">
                            <div className="h-full bg-[#0BC5C1] rounded-full" style={{ width: `${(subDone / task.subtasks.length) * 100}%` }} />
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>
      </div>

      {/* Task Modal */}
      {showTaskModal && (
        <TaskModal
          task={editingTask}
          projectId={activeProjectId ?? projects[0]?.id}
          onSave={saveTask}
          onClose={() => { setShowTaskModal(false); setEditingTask(null); }}
        />
      )}

      {/* Project Modal */}
      {showProjectModal && (
        <ProjectModal
          project={editingProject}
          onSave={saveProject}
          onClose={() => { setShowProjectModal(false); setEditingProject(null); }}
        />
      )}

      {/* Delete confirm */}
      {deleteConfirm && (
        <div className="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">
            <h3 className="font-display font-700 text-lg text-[#1E293B] mb-2">Confirm Delete</h3>
            <p className="text-sm text-[#64748B] mb-6">
              Are you sure you want to delete this {deleteConfirm.type}? This action cannot be undone.
            </p>
            <div className="flex gap-3">
              <button onClick={() => setDeleteConfirm(null)} className="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]">Cancel</button>
              <button onClick={confirmDelete} className="flex-1 py-2.5 rounded-xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600">Delete</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

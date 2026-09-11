export type Role = 'admin' | 'user';
export type TaskStatus = 'not_started' | 'in_progress' | 'completed';
export type Priority = 'high' | 'medium' | 'low';
export type ProjectRole = 'owner' | 'collaborator';

export interface User {
  id: string;
  name: string;
  email: string;
  role: Role;
  status: 'active' | 'inactive';
  avatar: string;
  joinedAt: string;
  lastActive: string;
}

export interface Subtask {
  id: string;
  title: string;
  completed: boolean;
}

export interface Task {
  id: string;
  projectId: string;
  title: string;
  description: string;
  status: TaskStatus;
  priority: Priority;
  deadline: string;
  assigneeId: string | null;
  subtasks: Subtask[];
  createdAt: string;
  tags: string[];
}

export interface ProjectMember {
  userId: string;
  role: ProjectRole;
  joinedAt: string;
}

export interface Invitation {
  id: string;
  projectId: string;
  email: string;
  status: 'pending' | 'accepted' | 'rejected';
  sentAt: string;
}

export interface Project {
  id: string;
  name: string;
  description: string;
  color: string;
  ownerId: string;
  members: ProjectMember[];
  invitations: Invitation[];
  createdAt: string;
  deadline: string;
}

export interface ActivityLog {
  id: string;
  adminId: string;
  action: string;
  target: string;
  timestamp: string;
}

export interface Notification {
  id: string;
  userId: string;
  message: string;
  type: 'invite' | 'assignment' | 'deadline' | 'mention';
  read: boolean;
  createdAt: string;
}

export const USERS: User[] = [
  { id: 'u1', name: 'Arya Santoso', email: 'arya@jara.app', role: 'admin', status: 'active', avatar: 'AS', joinedAt: '2026-01-15', lastActive: '2026-09-11' },
  { id: 'u2', name: 'Budi Hartono', email: 'budi@jara.app', role: 'user', status: 'active', avatar: 'BH', joinedAt: '2026-02-03', lastActive: '2026-09-10' },
  { id: 'u3', name: 'Citra Dewi', email: 'citra@jara.app', role: 'user', status: 'active', avatar: 'CD', joinedAt: '2026-03-12', lastActive: '2026-09-09' },
  { id: 'u4', name: 'Dian Permata', email: 'dian@jara.app', role: 'user', status: 'inactive', avatar: 'DP', joinedAt: '2026-04-01', lastActive: '2026-08-20' },
  { id: 'u5', name: 'Eko Prasetyo', email: 'eko@jara.app', role: 'user', status: 'active', avatar: 'EP', joinedAt: '2026-05-18', lastActive: '2026-09-11' },
  { id: 'u6', name: 'Farah Nadia', email: 'farah@jara.app', role: 'user', status: 'active', avatar: 'FN', joinedAt: '2026-06-07', lastActive: '2026-09-08' },
  { id: 'u7', name: 'Gilang Ramadan', email: 'gilang@jara.app', role: 'user', status: 'inactive', avatar: 'GR', joinedAt: '2026-07-22', lastActive: '2026-08-30' },
];

export const PROJECTS: Project[] = [
  {
    id: 'p1',
    name: 'Website Redesign',
    description: 'Redesign the company website with modern UI/UX principles and improved conversion flow.',
    color: '#0BC5C1',
    ownerId: 'u2',
    members: [
      { userId: 'u2', role: 'owner', joinedAt: '2026-06-01' },
      { userId: 'u3', role: 'collaborator', joinedAt: '2026-06-05' },
      { userId: 'u5', role: 'collaborator', joinedAt: '2026-06-10' },
    ],
    invitations: [
      { id: 'inv1', projectId: 'p1', email: 'farah@jara.app', status: 'pending', sentAt: '2026-09-10' },
    ],
    createdAt: '2026-06-01',
    deadline: '2026-10-30',
  },
  {
    id: 'p2',
    name: 'Mobile App MVP',
    description: 'Build the minimum viable product for the JARA mobile application targeting iOS and Android.',
    color: '#8B5CF6',
    ownerId: 'u2',
    members: [
      { userId: 'u2', role: 'owner', joinedAt: '2026-07-01' },
      { userId: 'u6', role: 'collaborator', joinedAt: '2026-07-08' },
    ],
    invitations: [],
    createdAt: '2026-07-01',
    deadline: '2026-12-15',
  },
  {
    id: 'p3',
    name: 'Q4 Marketing Campaign',
    description: 'Plan and execute Q4 marketing campaign across social media, email, and paid channels.',
    color: '#F59E0B',
    ownerId: 'u3',
    members: [
      { userId: 'u3', role: 'owner', joinedAt: '2026-08-15' },
      { userId: 'u2', role: 'collaborator', joinedAt: '2026-08-20' },
      { userId: 'u5', role: 'collaborator', joinedAt: '2026-08-22' },
    ],
    invitations: [],
    createdAt: '2026-08-15',
    deadline: '2026-12-31',
  },
];

export const TASKS: Task[] = [
  // Website Redesign
  { id: 't1', projectId: 'p1', title: 'Conduct user research interviews', description: 'Interview 10 existing customers to understand pain points with current website.', status: 'completed', priority: 'high', deadline: '2026-09-05', assigneeId: 'u3', subtasks: [{ id: 'st1', title: 'Draft interview guide', completed: true }, { id: 'st2', title: 'Schedule interviews', completed: true }, { id: 'st3', title: 'Analyze findings', completed: true }], createdAt: '2026-06-10', tags: ['research', 'ux'] },
  { id: 't2', projectId: 'p1', title: 'Create wireframes for homepage', description: 'Design low-fidelity wireframes for the new homepage layout.', status: 'completed', priority: 'high', deadline: '2026-09-10', assigneeId: 'u2', subtasks: [{ id: 'st4', title: 'Sketch initial concepts', completed: true }, { id: 'st5', title: 'Digital wireframe in Figma', completed: true }], createdAt: '2026-06-15', tags: ['design', 'wireframe'] },
  { id: 't3', projectId: 'p1', title: 'Develop component library', description: 'Build a reusable React component library based on the new design system.', status: 'in_progress', priority: 'high', deadline: '2026-09-25', assigneeId: 'u5', subtasks: [{ id: 'st6', title: 'Setup Storybook', completed: true }, { id: 'st7', title: 'Build button components', completed: true }, { id: 'st8', title: 'Build form components', completed: false }, { id: 'st9', title: 'Build card components', completed: false }], createdAt: '2026-07-01', tags: ['development', 'frontend'] },
  { id: 't4', projectId: 'p1', title: 'SEO audit and optimization', description: 'Audit current SEO performance and implement improvements for the new site.', status: 'in_progress', priority: 'medium', deadline: '2026-10-05', assigneeId: 'u3', subtasks: [], createdAt: '2026-07-15', tags: ['seo', 'marketing'] },
  { id: 't5', projectId: 'p1', title: 'Performance testing', description: 'Run Lighthouse and Core Web Vitals tests on all key pages.', status: 'not_started', priority: 'medium', deadline: '2026-10-20', assigneeId: null, subtasks: [], createdAt: '2026-08-01', tags: ['testing', 'performance'] },
  { id: 't6', projectId: 'p1', title: 'Content migration', description: 'Migrate all existing content to the new CMS structure.', status: 'not_started', priority: 'low', deadline: '2026-10-28', assigneeId: null, subtasks: [], createdAt: '2026-08-10', tags: ['content'] },

  // Mobile App MVP
  { id: 't7', projectId: 'p2', title: 'Define app architecture', description: 'Decide on tech stack, state management, and API design patterns.', status: 'completed', priority: 'high', deadline: '2026-07-15', assigneeId: 'u2', subtasks: [], createdAt: '2026-07-05', tags: ['architecture'] },
  { id: 't8', projectId: 'p2', title: 'Authentication screens', description: 'Build login, register, and forgot password screens.', status: 'completed', priority: 'high', deadline: '2026-08-01', assigneeId: 'u6', subtasks: [{ id: 'st10', title: 'Login screen', completed: true }, { id: 'st11', title: 'Register screen', completed: true }], createdAt: '2026-07-16', tags: ['auth', 'mobile'] },
  { id: 't9', projectId: 'p2', title: 'Task list screen', description: 'Build the main task list view with filters and sorting.', status: 'in_progress', priority: 'high', deadline: '2026-09-30', assigneeId: 'u2', subtasks: [], createdAt: '2026-08-05', tags: ['mobile', 'tasks'] },
  { id: 't10', projectId: 'p2', title: 'Push notification integration', description: 'Integrate FCM for task deadline reminders and collaboration notifications.', status: 'not_started', priority: 'medium', deadline: '2026-11-15', assigneeId: 'u6', subtasks: [], createdAt: '2026-08-20', tags: ['notifications', 'backend'] },

  // Marketing Campaign
  { id: 't11', projectId: 'p3', title: 'Campaign strategy document', description: 'Write comprehensive Q4 marketing strategy covering goals, audience, channels, and budget.', status: 'completed', priority: 'high', deadline: '2026-09-01', assigneeId: 'u3', subtasks: [], createdAt: '2026-08-16', tags: ['strategy'] },
  { id: 't12', projectId: 'p3', title: 'Social media content calendar', description: 'Plan 3-month social content calendar for Instagram, LinkedIn, and Twitter.', status: 'in_progress', priority: 'high', deadline: '2026-09-20', assigneeId: 'u5', subtasks: [], createdAt: '2026-08-20', tags: ['social', 'content'] },
  { id: 't13', projectId: 'p3', title: 'Email campaign design', description: 'Design 4 email templates for the Q4 drip campaign.', status: 'not_started', priority: 'medium', deadline: '2026-10-10', assigneeId: 'u2', subtasks: [], createdAt: '2026-09-01', tags: ['email', 'design'] },
];

export const ACTIVITY_LOGS: ActivityLog[] = [
  { id: 'al1', adminId: 'u1', action: 'Created user account', target: 'Farah Nadia (farah@jara.app)', timestamp: '2026-09-08 14:32' },
  { id: 'al2', adminId: 'u1', action: 'Deactivated user', target: 'Dian Permata (dian@jara.app)', timestamp: '2026-08-21 09:15' },
  { id: 'al3', adminId: 'u1', action: 'Deactivated user', target: 'Gilang Ramadan (gilang@jara.app)', timestamp: '2026-08-31 11:04' },
  { id: 'al4', adminId: 'u1', action: 'Created user account', target: 'Gilang Ramadan (gilang@jara.app)', timestamp: '2026-07-22 10:00' },
  { id: 'al5', adminId: 'u1', action: 'Reset password', target: 'Budi Hartono (budi@jara.app)', timestamp: '2026-07-10 16:45' },
];

export const NOTIFICATIONS: Notification[] = [
  { id: 'n1', userId: 'u2', message: 'Citra Dewi completed "Conduct user research interviews"', type: 'mention', read: false, createdAt: '2026-09-09 10:20' },
  { id: 'n2', userId: 'u2', message: 'Task "Develop component library" is due in 2 days', type: 'deadline', read: false, createdAt: '2026-09-09 08:00' },
  { id: 'n3', userId: 'u2', message: 'Farah Nadia accepted your invitation to Website Redesign', type: 'invite', read: true, createdAt: '2026-09-08 15:30' },
  { id: 'n4', userId: 'u2', message: 'You were assigned to "Email campaign design"', type: 'assignment', read: true, createdAt: '2026-09-08 09:00' },
];

export const WEEKLY_PROGRESS = [
  { week: 'Aug W1', completed: 4, created: 6 },
  { week: 'Aug W2', completed: 7, created: 8 },
  { week: 'Aug W3', completed: 5, created: 5 },
  { week: 'Aug W4', completed: 9, created: 10 },
  { week: 'Sep W1', completed: 6, created: 7 },
  { week: 'Sep W2', completed: 3, created: 4 },
];

export const MONTHLY_PROGRESS = [
  { month: 'Apr', completed: 12, created: 15 },
  { month: 'May', completed: 18, created: 20 },
  { month: 'Jun', completed: 22, created: 25 },
  { month: 'Jul', completed: 28, created: 30 },
  { month: 'Aug', completed: 25, created: 28 },
  { month: 'Sep', completed: 9, created: 11 },
];

export const PRIORITY_DISTRIBUTION = [
  { name: 'High', value: 7, color: '#EF4444' },
  { name: 'Medium', value: 4, color: '#F59E0B' },
  { name: 'Low', value: 2, color: '#10B981' },
];

export const MEMBER_PROGRESS = [
  { name: 'Budi H.', completed: 5, inProgress: 2, total: 8 },
  { name: 'Citra D.', completed: 3, inProgress: 1, total: 5 },
  { name: 'Eko P.', completed: 2, inProgress: 2, total: 5 },
  { name: 'Farah N.', completed: 1, inProgress: 1, total: 3 },
];

// Current logged-in user (for demo)
export const CURRENT_USER: User = USERS[1]; // Budi Hartono
export const CURRENT_ADMIN: User = USERS[0]; // Arya Santoso

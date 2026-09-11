export const PROJECT_ROLE = {
    owner: 'owner',
    member: 'member',
};

export const INVITATION_STATUS = {
    pending: 'pending',
    accepted: 'accepted',
    rejected: 'rejected',
};

export const TASK_STATUS = {
    not_started: 'not_started',
    in_progress: 'in_progress',
    completed: 'completed',
};

export const USERS = [
    { id: 'u1', name: 'Arya Santoso', email: 'arya@jara.app', role: 'admin', status: 'active', avatar: 'AS', joinedAt: '2026-01-15', lastActive: '2026-09-11' },
    { id: 'u2', name: 'Budi Hartono', email: 'budi@jara.app', role: 'user', status: 'active', avatar: 'BH', joinedAt: '2026-02-03', lastActive: '2026-09-11' },
    { id: 'u3', name: 'Citra Dewi', email: 'citra@jara.app', role: 'user', status: 'active', avatar: 'CD', joinedAt: '2026-03-12', lastActive: '2026-09-10' },
    { id: 'u4', name: 'Dian Permata', email: 'dian@jara.app', role: 'user', status: 'inactive', avatar: 'DP', joinedAt: '2026-04-01', lastActive: '2026-08-20' },
    { id: 'u5', name: 'Eko Prasetyo', email: 'eko@jara.app', role: 'user', status: 'active', avatar: 'EP', joinedAt: '2026-05-18', lastActive: '2026-09-09' },
    { id: 'u6', name: 'Farah Nadia', email: 'farah@jara.app', role: 'user', status: 'active', avatar: 'FN', joinedAt: '2026-06-07', lastActive: '2026-09-08' },
    { id: 'u7', name: 'Gilang Ramadan', email: 'gilang@jara.app', role: 'user', status: 'active', avatar: 'GR', joinedAt: '2026-07-22', lastActive: '2026-09-11' },
];

export const PROJECTS = [
    {
        id: 'p1',
        name: 'Project A',
        description: 'Redesign the company website with modern UI/UX principles and improved conversion flow.',
        color: '#0BC5C1',
        ownerId: 'u2',
        members: [
            { userId: 'u2', role: 'owner', joinedAt: '2026-06-01' },
            { userId: 'u3', role: 'member', joinedAt: '2026-06-05' },
            { userId: 'u5', role: 'member', joinedAt: '2026-06-10' },
        ],
        invitations: [
            { id: 'inv1', projectId: 'p1', userId: 'u6', email: 'farah@jara.app', status: 'pending', sentAt: '2026-09-10' },
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
            { userId: 'u6', role: 'member', joinedAt: '2026-07-08' },
            { userId: 'u7', role: 'member', joinedAt: '2026-07-12' },
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
            { userId: 'u2', role: 'member', joinedAt: '2026-08-20' },
        ],
        invitations: [],
        createdAt: '2026-08-15',
        deadline: '2026-12-31',
    },
];

export const TASKS = [
    { id: 't1', projectId: 'p1', title: 'Task 1 — Conduct user research interviews', description: 'Interview 10 existing customers to understand pain points.', status: 'in_progress', priority: 'high', deadline: '2026-09-25', assigneeId: 'u3', subtasks: [], createdAt: '2026-06-10', tags: ['research'] },
    { id: 't2', projectId: 'p1', title: 'Task 2 — Create wireframes for homepage', description: 'Design low-fidelity wireframes for the new homepage layout.', status: 'completed', priority: 'high', deadline: '2026-09-10', assigneeId: 'u5', subtasks: [], createdAt: '2026-06-15', tags: ['design'] },
    { id: 't3', projectId: 'p1', title: 'Task 3 — Develop component library', description: 'Build a reusable component library based on the new design system.', status: 'not_started', priority: 'medium', deadline: '2026-10-05', assigneeId: null, subtasks: [], createdAt: '2026-07-01', tags: ['development'] },
    { id: 't4', projectId: 'p1', title: 'Performance testing', description: 'Run Lighthouse and Core Web Vitals tests on all key pages.', status: 'not_started', priority: 'medium', deadline: '2026-10-20', assigneeId: null, subtasks: [], createdAt: '2026-08-01', tags: ['testing'] },
    { id: 't5', projectId: 'p2', title: 'Define app architecture', description: 'Decide on tech stack, state management, and API design patterns.', status: 'completed', priority: 'high', deadline: '2026-07-15', assigneeId: 'u2', subtasks: [], createdAt: '2026-07-05', tags: ['architecture'] },
    { id: 't6', projectId: 'p2', title: 'Authentication screens', description: 'Build login, register, and forgot password screens.', status: 'in_progress', priority: 'high', deadline: '2026-09-30', assigneeId: 'u6', subtasks: [], createdAt: '2026-07-16', tags: ['auth', 'mobile'] },
    { id: 't7', projectId: 'p3', title: 'Campaign strategy document', description: 'Write comprehensive marketing strategy covering goals and budget.', status: 'completed', priority: 'high', deadline: '2026-09-01', assigneeId: 'u2', subtasks: [], createdAt: '2026-08-16', tags: ['strategy'] },
    { id: 't8', projectId: 'p3', title: 'Email campaign design', description: 'Design 4 email templates for the drip campaign.', status: 'not_started', priority: 'medium', deadline: '2026-10-10', assigneeId: 'u3', subtasks: [], createdAt: '2026-09-01', tags: ['email', 'design'] },
];

export const NOTIFICATIONS = [
    { id: 'n1', userId: 'u2', type: 'invite', message: 'Undangan untuk "Project A" telah dikirim ke Farah Nadia', read: false, createdAt: '2026-09-11 09:00' },
    { id: 'n2', userId: 'u6', type: 'invite', message: 'Kamu diundang untuk bergabung ke "Project A"', read: false, createdAt: '2026-09-11 09:00' },
    { id: 'n3', userId: 'u3', type: 'assignment', message: 'Task "Conduct user research interviews" ditugaskan kepadamu', read: false, createdAt: '2026-09-11 08:20' },
    { id: 'n4', userId: 'u5', type: 'assignment', message: 'Task "Create wireframes for homepage" ditugaskan kepadamu', read: true, createdAt: '2026-09-10 14:30' },
];

export const CURRENT_USER_ID = 'u2';
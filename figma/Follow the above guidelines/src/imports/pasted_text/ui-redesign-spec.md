Redesign and implement the UI for the JARA — Advanced To-Do List web application based on the provided visual reference.

IMPORTANT SCOPE:
- UI/UX ONLY.
- ONLY modify files inside `app/` and `components/`.
- DO NOT modify database, schema, ORM, API, backend logic, authentication logic, configuration files, package files, or other folders outside `app/` and `components/`.
- Do not create or modify database tables.
- Do not create API endpoints.
- Do not change existing backend functionality.
- Use dummy/mock data only when real data is not available.
- Any dummy data must be placed directly inside the relevant files/folders under `app/` or `components/`.
- Keep the implementation modular and isolated by feature.

FOLDER STRUCTURE:
Organize each feature inside its own folder.

Example:
`components/admin/`
`app/admin/`

`components/auth/`
`app/login/`
`app/register/`

`components/tasks/`
`app/tasks/`

`components/collaboration/`
`app/collaboration/`

`components/dashboard/`
`app/dashboard/`

Do not put feature-specific components into unrelated folders.

DESIGN DIRECTION:
Use the provided dashboard image as the main visual reference.

The visual style should feel like a modern productivity dashboard:
- Clean and professional
- Light interface
- Cyan/turquoise as the main accent color
- White/light-gray surfaces
- Rounded corners
- Soft shadows
- Spacious layout
- Clear typography and hierarchy
- Sidebar navigation
- Dashboard cards/widgets
- Charts and progress visualizations
- Avoid an overly generic "AI-generated dashboard" appearance
- Avoid excessive gradients, excessive glassmorphism, or overly decorative elements
- Make the UI practical and realistic for an actual task-management application
- Responsive for desktop and mobile

JARA FEATURES:

1. AUTHENTICATION
Create UI for:
- Login
- Register
- Forgot/reset password
- Logout interaction
- Form validation states
- Empty/loading/error/success states where appropriate

Use dummy users only for UI demonstration.

2. ADMIN / ACCOUNT MANAGEMENT
Create an Admin interface for:
- User list
- Search users
- Filter users by status/role
- Add user
- View user details
- Disable/deactivate user
- Display active/inactive status
- Display Admin/User role
- Activity/audit log UI

The Admin area must be isolated inside:
`app/admin/`
`components/admin/`

3. TASK & PROJECT MANAGEMENT
Create UI for:
- Project/list overview
- Create project
- Edit project
- Delete project
- Project detail page
- Task list
- Create task
- Edit task
- Delete task
- Task description
- Subtasks
- Priority: High / Medium / Low
- Deadline
- Task status: Not Started / In Progress / Completed
- Sorting by priority and deadline
- Filtering by status, priority, and deadline
- Search tasks
- Reminder/deadline indication

Keep these changes inside:
`app/tasks/`
`components/tasks/`

4. COLLABORATION
Create UI for:
- Project members
- Invite user
- Pending invitations
- Accept/reject invitation
- Remove member
- Assign task to a member
- Member role display
- Permission indicators
- Assigned-task view
- Collaboration notifications

Only the project owner should have owner-level actions such as deleting the project or managing members.

Keep these changes inside:
`app/collaboration/`
`components/collaboration/`

5. DASHBOARD / PROGRESS MONITORING
Create a dashboard inspired by the provided visual reference.

Dashboard should contain:
- Overall task progress
- Progress percentage
- Total tasks
- Not Started tasks
- In Progress tasks
- Completed tasks
- Overdue tasks
- Project summary
- Team/member progress
- Weekly/monthly progress
- Task completion chart
- Priority distribution
- Recent activity
- Upcoming deadlines

Use realistic dummy data for charts and statistics.

Keep these changes inside:
`app/dashboard/`
`components/dashboard/`

DASHBOARD LAYOUT:
Use a layout similar in spirit to the reference:
- Left sidebar for navigation
- Main content area
- Summary/statistic widgets
- Progress visualization
- Task/project charts
- Recent activity
- Upcoming deadlines
- Calendar/deadline widget if useful

The dashboard should prioritize information hierarchy rather than simply copying the reference image.

ROLE-BASED UI:
The interface should visually distinguish:
- Admin
- Regular User
- Project Owner
- Project Collaborator

For example:
- Admin can access user management.
- Project Owner can manage project members.
- Collaborator can view and update assigned tasks.
- Users should only see actions they are allowed to perform.

Do not implement real authorization/backend logic. This is UI only. Use dummy role state/data for demonstration.

INTERACTION:
Make the UI feel functional even with dummy data:
- Buttons should have appropriate visual states.
- Dropdowns should show realistic options.
- Tabs should work visually.
- Filters/sorting should have UI states.
- Modals/dialogs should be used for create/edit/delete/invite actions.
- Forms should have realistic validation states.
- Sidebar navigation should connect the available pages.
- Use mock data to demonstrate different states.

IMPORTANT:
Do not over-engineer the implementation.
Do not refactor unrelated code.
Do not modify existing modules outside the requested feature.
Do not touch database or backend code.
Do not introduce unnecessary libraries.

Before making changes, inspect the existing `app/` and `components/` structure and reuse existing components/styles where possible.

All feature-specific changes must remain isolated within their corresponding `app/<feature>/` and `components/<feature>/` folders.
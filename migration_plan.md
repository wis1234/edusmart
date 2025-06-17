Migration Plan for EduSmart Project: Blade to React with Inertia.js

1. Overview:
- Current state: Partial React with Inertia.js integration (mainly auth and some pages).
- Majority of resource controllers return Blade views.
- Goal: Migrate all resource views to React with Inertia.js for a unified frontend.

2. Resources to Migrate:
- Teachers
- Parents
- Students
- Schools
- ClassRooms
- Calendars
- Evaluations
- StudentGrades
- Subjects
- Ecommerce (Products, Orders)

3. Migration Steps:

3.1 Controllers:
- Update all resource controllers to replace Blade view returns with Inertia::render calls.
- Pass necessary data to React pages via Inertia.

3.2 React Pages/Components:
- Create React pages/components for index, create, edit, show views for each resource.
- Use existing React components/pages as reference for structure and styling.
- Ensure usage of shared layouts (AuthenticatedLayout) and components (InputLabel, TextInput, PrimaryButton, etc.).

3.3 Blade Views:
- Remove Blade views corresponding to migrated resources after successful migration.

3.4 Routes:
- Verify routes/web.php for any necessary updates (mostly resource routes remain same).

3.5 Testing:
- Test all migrated pages for functionality and UI consistency.
- Fix any issues arising from migration.

4. Dependent Files to Edit:
- app/Http/Controllers/*Controller.php (all resource controllers)
- resources/js/Pages/* (create new React pages)
- resources/js/Components/* (create/update React components)
- resources/views/* (remove Blade views after migration)
- routes/web.php (verify routes)

5. Follow-up Steps:
- Install/update npm packages for React/Inertia if needed.
- Run tests and manual UI verification.
- Update documentation if applicable.

Please confirm if you approve this detailed migration plan so I can proceed with implementation.

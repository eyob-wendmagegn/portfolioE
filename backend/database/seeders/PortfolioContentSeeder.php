<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Education;
use App\Models\LibraryDashboardImage;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categories = [
            ['name' => 'Full-Stack Applications', 'slug' => 'full-stack-applications', 'sort_order' => 1],
            ['name' => 'Laravel Projects', 'slug' => 'laravel-projects', 'sort_order' => 2],
            ['name' => 'Vue.js Applications', 'slug' => 'vue-js-applications', 'sort_order' => 3],
            ['name' => 'Backend & REST APIs', 'slug' => 'backend-rest-apis', 'sort_order' => 4],
            ['name' => 'Academic Projects', 'slug' => 'academic-projects', 'sort_order' => 5],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['slug']] = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Technologies
        $technologies = [
            'Laravel', 'Vue.js 3', 'PHP', 'JavaScript', 'Tailwind CSS',
            'PostgreSQL', 'MySQL', 'Node.js', 'Next.js', 'Vite',
            'Axios', 'Git & GitHub', 'REST API', 'Sanctum', 'TypeScript'
        ];

        $techMap = [];
        foreach ($technologies as $techName) {
            $slug = Str::slug($techName);
            $techMap[$techName] = Technology::updateOrCreate(
                ['name' => $techName],
                ['slug' => $slug, 'icon' => $slug]
            );
        }

        // 3. Projects
        // Library Management System - Main Featured Project
        $lmsProject = Project::updateOrCreate(
            ['slug' => 'library-management-system'],
            [
                'title' => 'Library Management System',
                'short_description' => 'A robust multi-role academic library automation platform with distinct dashboards for administrators, librarians, teachers, and students.',
                'description' => "The Library Management System is a comprehensive full-stack solution designed to automate and streamline academic library operations. Developed with structured role-based access control, the platform features four dedicated user portals tailored to specific workflow requirements:\n\n• Administrator Dashboard: Complete user lifecycle management, role provisioning, system analytics, and security audits.\n• Librarian Dashboard: Real-time book cataloging, circulation desk check-in/check-out, overdue tracking, and inventory reports.\n• Teacher Dashboard: Classroom resource requests, specialized curriculum book reservations, and authorized student tracking.\n• Student Dashboard: Digital catalog browsing, real-time availability checks, personal loan history, and due date alerts.\n\nBuilt following clean architecture patterns with secure authentication, relational database integrity, and responsive user interfaces.",
                'category_id' => $categoryMap['full-stack-applications']->id,
                'featured' => true,
                'status' => 'published',
                'sort_order' => 1,
                'project_date' => '2025 - 2026',
                'github_url' => 'https://github.com/eyobnigata18',
                'live_url' => null,
                'seo_title' => 'Library Management System - Multi-Role Educational Platform',
                'seo_description' => 'A comprehensive multi-role library automation system featuring custom dashboards for administrators, librarians, teachers, and students.',
            ]
        );

        $lmsProject->technologies()->sync([
            $techMap['Laravel']->id,
            $techMap['Vue.js 3']->id,
            $techMap['PHP']->id,
            $techMap['PostgreSQL']->id,
            $techMap['Tailwind CSS']->id,
            $techMap['REST API']->id,
        ]);

        // Additional Projects
        $dormProject = Project::updateOrCreate(
            ['slug' => 'dormitory-student-management-system'],
            [
                'title' => 'Dormitory & Accommodation Management System',
                'short_description' => 'A relational database-backed management system for student dormitory allocations, room assignments, and maintenance logs.',
                'description' => 'A specialized web application built to streamline campus student housing operations. Features include automatic room allocation algorithms, capacity tracking, maintenance issue logging, and student check-in/check-out verification.',
                'category_id' => $categoryMap['laravel-projects']->id,
                'featured' => true,
                'status' => 'published',
                'sort_order' => 2,
                'project_date' => '2025',
                'github_url' => 'https://github.com/eyobnigata18',
                'live_url' => null,
                'seo_title' => 'Dormitory Management System - Laravel & PostgreSQL',
                'seo_description' => 'Automated student dormitory allocation and housing management system built with Laravel and PostgreSQL.',
            ]
        );

        $dormProject->technologies()->sync([
            $techMap['Laravel']->id,
            $techMap['PHP']->id,
            $techMap['PostgreSQL']->id,
            $techMap['Tailwind CSS']->id,
        ]);

        $apiProject = Project::updateOrCreate(
            ['slug' => 'modern-e-commerce-rest-api'],
            [
                'title' => 'High-Performance E-Commerce REST API',
                'short_description' => 'A scalable RESTful API service supporting product catalogs, cart workflows, checkout processing, and Sanctum token authorization.',
                'description' => 'A modular REST API backend featuring rate limiting, Form Request validation, API resource transformations, secure checkout processing, and optimized database indexing for high query throughput.',
                'category_id' => $categoryMap['backend-rest-apis']->id,
                'featured' => true,
                'status' => 'published',
                'sort_order' => 3,
                'project_date' => '2026',
                'github_url' => 'https://github.com/eyobnigata18',
                'live_url' => null,
                'seo_title' => 'E-Commerce REST API - Laravel Sanctum',
                'seo_description' => 'A robust, scalable RESTful backend service for modern e-commerce web applications.',
            ]
        );

        $apiProject->technologies()->sync([
            $techMap['Laravel']->id,
            $techMap['PHP']->id,
            $techMap['REST API']->id,
            $techMap['Sanctum']->id,
            $techMap['PostgreSQL']->id,
        ]);

        $vueApp = Project::updateOrCreate(
            ['slug' => 'interactive-analytics-task-tracker'],
            [
                'title' => 'Interactive Workflow & Task Management App',
                'short_description' => 'A reactive Single Page Application built with Vue 3 Composition API, Pinia state management, and real-time client filtering.',
                'description' => 'A responsive productivity dashboard enabling team project tracking, drag-and-drop task status updates, tag filtering, and dynamic metrics visualization.',
                'category_id' => $categoryMap['vue-js-applications']->id,
                'featured' => false,
                'status' => 'published',
                'sort_order' => 4,
                'project_date' => '2026',
                'github_url' => 'https://github.com/eyobnigata18',
                'live_url' => null,
                'seo_title' => 'Vue 3 Task Tracker SPA',
                'seo_description' => 'Interactive Single Page Application built with Vue 3, Pinia, and Tailwind CSS.',
            ]
        );

        $vueApp->technologies()->sync([
            $techMap['Vue.js 3']->id,
            $techMap['JavaScript']->id,
            $techMap['Tailwind CSS']->id,
            $techMap['Vite']->id,
            $techMap['Axios']->id,
        ]);

        // 4. Library Dashboard Images (4 Roles)
        $dashboards = [
            [
                'role' => 'administrator',
                'title' => 'Administrator Dashboard',
                'description' => 'User registration, activation, deactivation, user management and search, system reports, activity tracking, system administration, and library statistics overview.',
                'badge_color' => 'rose',
                'alt_text' => 'Administrator portal overview with user controls and system statistics',
                'features' => [
                    'User registration, activation & deactivation',
                    'User management and search filters',
                    'Activity tracking & system audit logs',
                    'Overall library statistics and overview'
                ],
                'sort_order' => 1,
                'is_visible' => true,
            ],
            [
                'role' => 'librarian',
                'title' => 'Librarian Dashboard',
                'description' => 'Manage books and catalog data, issue borrowing and process returns, track borrowing records and overdue items, manage resources, and review circulation statistics.',
                'badge_color' => 'indigo',
                'alt_text' => 'Librarian circulation desk and inventory management dashboard',
                'features' => [
                    'Manage books and catalog information',
                    'Handle book issue, borrowing and returns',
                    'Track borrowing records and overdue items',
                    'Review real-time circulation statistics'
                ],
                'sort_order' => 2,
                'is_visible' => true,
            ],
            [
                'role' => 'teacher',
                'title' => 'Teacher Dashboard',
                'description' => 'Add new books, search and view book information, manage available academic resources, and access authorized borrowing-related features.',
                'badge_color' => 'emerald',
                'alt_text' => 'Teacher resource portal for adding books and managing academic reading lists',
                'features' => [
                    'Add new books and academic references',
                    'Search and view detailed book records',
                    'Manage available course resources',
                    'Access authorized borrowing privileges'
                ],
                'sort_order' => 3,
                'is_visible' => true,
            ],
            [
                'role' => 'student',
                'title' => 'Student Dashboard',
                'description' => 'Browse and search books, check real-time availability, borrow and return books where permitted, and review borrowing history and reading progress.',
                'badge_color' => 'amber',
                'alt_text' => 'Student self-service portal for catalog search and borrowing history',
                'features' => [
                    'Browse and search complete book catalog',
                    'View live book copies and shelf availability',
                    'Borrow and return books where permitted',
                    'Track borrowing history and due dates'
                ],
                'sort_order' => 4,
                'is_visible' => true,
            ],
        ];

        foreach ($dashboards as $dash) {
            LibraryDashboardImage::updateOrCreate(['role' => $dash['role']], $dash);
        }

        // 5. Skills (Grouped exactly as requested)
        $skillsData = [
            // Backend Development
            [
                'category' => 'Backend Development',
                'name' => 'Laravel',
                'icon' => 'Server',
                'description' => 'Robust MVC architecture, Eloquent ORM, REST API development, routing, and ecosystem tools.',
                'level' => 'Advanced',
                'sort_order' => 1,
            ],
            [
                'category' => 'Backend Development',
                'name' => 'PHP',
                'icon' => 'Code',
                'description' => 'Object-oriented programming, modern PHP 8.x features, dependency injection, and clean code principles.',
                'level' => 'Advanced',
                'sort_order' => 2,
            ],
            [
                'category' => 'Backend Development',
                'name' => 'REST API Development',
                'icon' => 'Webhook',
                'description' => 'Designing clean, stateless RESTful endpoints, API Resources, JSON responses, and HTTP error handling.',
                'level' => 'Advanced',
                'sort_order' => 3,
            ],
            [
                'category' => 'Backend Development',
                'name' => 'Authentication & Authorization',
                'icon' => 'ShieldCheck',
                'description' => 'Laravel Sanctum, session authentication, Gate policies, role-based access control (RBAC), and token guards.',
                'level' => 'Proficient',
                'sort_order' => 4,
            ],
            [
                'category' => 'Backend Development',
                'name' => 'Database Design',
                'icon' => 'Database',
                'description' => 'Relational database schema modeling, indexing, foreign keys, normalization, and query optimization.',
                'level' => 'Proficient',
                'sort_order' => 5,
            ],

            // Frontend Development
            [
                'category' => 'Frontend Development',
                'name' => 'Vue.js 3',
                'icon' => 'Layers',
                'description' => 'Composition API, script setup, reactive state, custom composables, and component-driven architecture.',
                'level' => 'Proficient',
                'sort_order' => 6,
            ],
            [
                'category' => 'Frontend Development',
                'name' => 'JavaScript (ES6+)',
                'icon' => 'FileCode',
                'description' => 'Modern asynchronous programming, promises, async/await, DOM manipulation, and modular code.',
                'level' => 'Proficient',
                'sort_order' => 7,
            ],
            [
                'category' => 'Frontend Development',
                'name' => 'HTML5 & CSS3',
                'icon' => 'Layout',
                'description' => 'Semantic structure, accessible markup, modern layout algorithms (Flexbox, CSS Grid), and CSS animations.',
                'level' => 'Advanced',
                'sort_order' => 8,
            ],
            [
                'category' => 'Frontend Development',
                'name' => 'Tailwind CSS',
                'icon' => 'Palette',
                'description' => 'Utility-first styling, design system implementation, responsive layouts, and dark mode configuration.',
                'level' => 'Advanced',
                'sort_order' => 9,
            ],
            [
                'category' => 'Frontend Development',
                'name' => 'Responsive Design',
                'icon' => 'Smartphone',
                'description' => 'Mobile-first development ensuring seamless usability across phones, tablets, and wide desktop displays.',
                'level' => 'Advanced',
                'sort_order' => 10,
            ],

            // Additional Technologies
            [
                'category' => 'Additional Technologies',
                'name' => 'Node.js',
                'icon' => 'Cpu',
                'description' => 'Server-side runtime environments, package tooling, and lightweight backend services.',
                'level' => 'Intermediate',
                'sort_order' => 11,
            ],
            [
                'category' => 'Additional Technologies',
                'name' => 'Next.js',
                'icon' => 'Globe',
                'description' => 'Full-stack React frameworks, server components, and modern web application patterns.',
                'level' => 'Intermediate',
                'sort_order' => 12,
            ],
            [
                'category' => 'Additional Technologies',
                'name' => 'Git & GitHub',
                'icon' => 'GitBranch',
                'description' => 'Version control workflows, branch management, collaborative code reviews, and remote repositories.',
                'level' => 'Proficient',
                'sort_order' => 13,
            ],
            [
                'category' => 'Additional Technologies',
                'name' => 'PostgreSQL & MySQL',
                'icon' => 'Database',
                'description' => 'Enterprise relational databases, transactions, complex joins, and data migrations.',
                'level' => 'Proficient',
                'sort_order' => 14,
            ],
            [
                'category' => 'Additional Technologies',
                'name' => 'Axios & Vite',
                'icon' => 'Zap',
                'description' => 'HTTP request handling, interceptors, error formatting, and lightning-fast frontend bundling.',
                'level' => 'Advanced',
                'sort_order' => 15,
            ],
        ];

        foreach ($skillsData as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name'], 'category' => $skill['category']],
                $skill
            );
        }

        // 6. Education
        Education::updateOrCreate(
            ['institution' => 'Woldia University'],
            [
                'degree_or_title' => 'Graduate in Information Technology / Computer Science',
                'location' => 'Woldia, Ethiopia',
                'start_year' => '2021',
                'end_year' => '2025',
                'description' => 'Completed comprehensive academic studies focusing on software engineering, database management systems, web development, algorithms, and full-stack software architecture. Spearheaded academic projects including the multi-tier Library Management System.',
                'sort_order' => 1,
            ]
        );

        // 7. Services
        $services = [
            [
                'title' => 'Laravel Web Application Development',
                'description' => 'Custom full-stack web applications built with Laravel, clean MVC architecture, robust security, and reliable database structures.',
                'icon' => 'Server',
                'action_text' => 'Get in Touch',
                'action_url' => '#contact',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Vue.js Frontend Development',
                'description' => 'Modern, reactive Single Page Applications (SPAs) with Vue 3, Pinia state management, and intuitive user experiences.',
                'icon' => 'Layout',
                'action_text' => 'Discuss Project',
                'action_url' => '#contact',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'REST API Development & Integration',
                'description' => 'Secure, well-documented RESTful APIs with Sanctum authentication, Form Request validation, and seamless frontend consumption.',
                'icon' => 'Webhook',
                'action_text' => 'Plan an API',
                'action_url' => '#contact',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Database-Driven Applications',
                'description' => 'PostgreSQL and MySQL database modeling, schema design, query optimization, and structured relational data management.',
                'icon' => 'Database',
                'action_text' => 'Inquire Details',
                'action_url' => '#contact',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Website Design Implementation',
                'description' => 'Converting Figma or design concepts into pixel-perfect, responsive, and accessible Tailwind CSS web interfaces.',
                'icon' => 'Palette',
                'action_text' => 'Start Design',
                'action_url' => '#contact',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Bug Fixing & Website Improvements',
                'description' => 'Auditing existing codebases, resolving performance bottlenecks, upgrading dependencies, and enhancing responsive styling.',
                'icon' => 'Wrench',
                'action_text' => 'Request Support',
                'action_url' => '#contact',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(['title' => $srv['title']], $srv);
        }

        // 8. Site Settings & Portfolio Content
        $settings = [
            'site_title' => 'Eyob.dev — Eyob Wendmamagegn Portfolio',
            'site_tagline' => 'Full-Stack Developer | Laravel & Vue.js',
            'hero_greeting' => "Hello, I'm",
            'hero_name' => 'Eyob Wendmamagegn',
            'hero_roles' => [
                'Laravel Developer',
                'Vue.js Developer',
                'Full-Stack Web Developer',
                'Backend Developer',
                'Web Application Developer',
            ],
            'hero_bio' => "I'm a passionate developer who builds modern, reliable, and user-friendly web applications. I work with Laravel and Vue.js to create full-stack solutions, while also exploring technologies like Node.js and Next.js. I enjoy solving real-world problems, writing clean code, and turning ideas into useful digital experiences.",
            'hero_availability' => true,
            'hero_availability_text' => 'Available for Work',
            'about_bio' => "My name is Eyob Wendmamagegn. I am a junior developer and a graduate of Woldia University. During my studies and project work, I developed an interest in building modern web applications and solving practical problems through software.\n\nI work with technologies including Laravel, PHP, Vue.js, JavaScript, Node.js, and Next.js. I enjoy creating responsive interfaces, developing backend functionality, designing database-driven applications, and connecting frontend experiences with reliable APIs.\n\nMy goal is to continue growing as a developer, strengthen my full-stack engineering skills, and contribute to meaningful projects that make people's work easier.",
            'about_location' => 'Ethiopia',
            'about_university' => 'Woldia University',
            'contact_email' => 'eyobnigata18@gmail.com',
            'contact_phone' => '+251 900 000 000',
            'contact_location' => 'Ethiopia',
            'contact_github' => 'https://github.com/eyobnigata18',
            'contact_linkedin' => 'https://linkedin.com/in/eyob-wendmamagegn',
            'default_theme' => 'dark',
            'footer_bio' => 'Junior full-stack developer specializing in Laravel, Vue.js, and modern RESTful web applications.',
            'footer_copyright' => 'Eyob Wendmamagegn. All rights reserved.',
            'seo_meta_description' => 'Eyob Wendmamagegn is a junior full-stack web developer and Laravel/Vue.js specialist based in Ethiopia, crafting modern and scalable web applications.',
            'seo_keywords' => 'Eyob Wendmamagegn, Full Stack Developer, Laravel, Vue.js, PHP, PostgreSQL, Ethiopia Web Developer, REST API',
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::setValue($key, $val, 'general');
        }
    }
}

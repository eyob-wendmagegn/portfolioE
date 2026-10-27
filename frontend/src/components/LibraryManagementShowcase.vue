<template>
  <section id="lms-showcase" class="py-24 relative overflow-hidden bg-slate-950/70 light:bg-slate-50/50">
    <!-- Ambient backlights -->
    <div class="glow-blob-blue w-120 h-120 top-1/4 -left-40 opacity-40"></div>
    <div class="glow-blob-purple w-120 h-120 bottom-1/4 -right-40 opacity-40"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 light:bg-indigo-50 border border-indigo-500/20 text-indigo-400 light:text-indigo-600 text-xs font-bold tracking-widest uppercase mb-4">
          <BookOpen class="w-3.5 h-3.5" />
          <span>Flagship Academic Project</span>
        </div>
        <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white light:text-slate-900 mb-4">
          Library Management System
        </h2>
        <p class="text-base sm:text-lg text-slate-400 light:text-slate-600 leading-relaxed">
          A multi-tiered institutional library automation platform engineered with role-based access control and custom workflows for four distinct campus user groups.
        </p>
      </div>

      <!-- Architectural Highlights & Quick Specs -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-14">
        <div class="p-4 rounded-2xl glass-panel text-center">
          <ShieldAlert class="w-6 h-6 text-rose-400 mx-auto mb-2" />
          <h4 class="text-xs font-bold text-white light:text-slate-900">4 Distinct Portals</h4>
          <p class="text-[11px] text-slate-400 light:text-slate-500">Fine-grained RBAC</p>
        </div>
        <div class="p-4 rounded-2xl glass-panel text-center">
          <Zap class="w-6 h-6 text-amber-400 mx-auto mb-2" />
          <h4 class="text-xs font-bold text-white light:text-slate-900">Real-Time Circulation</h4>
          <p class="text-[11px] text-slate-400 light:text-slate-500">Instant Borrow/Return</p>
        </div>
        <div class="p-4 rounded-2xl glass-panel text-center">
          <Database class="w-6 h-6 text-blue-400 mx-auto mb-2" />
          <h4 class="text-xs font-bold text-white light:text-slate-900">Relational Database</h4>
          <p class="text-[11px] text-slate-400 light:text-slate-500">PostgreSQL Schema</p>
        </div>
        <div class="p-4 rounded-2xl glass-panel text-center">
          <CheckCircle2 class="w-6 h-6 text-emerald-400 mx-auto mb-2" />
          <h4 class="text-xs font-bold text-white light:text-slate-900">Full Audit Trails</h4>
          <p class="text-[11px] text-slate-400 light:text-slate-500">Borrowing Records & Fines</p>
        </div>
      </div>

      <!-- 2-COLUMN BY 2-ROW GRID ON DESKTOP (Section 4F Requirement) -->
      <!-- Left: Administrator & Teacher | Right: Librarian & Student -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        
        <!-- Card 1: Administrator Dashboard (Left Top) -->
        <div 
          class="group rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 overflow-hidden hover:border-rose-500/50 transition-all duration-300 shadow-xl flex flex-col"
        >
          <!-- Screenshot Area -->
          <div class="relative aspect-video bg-slate-900 light:bg-slate-100 overflow-hidden border-b border-slate-800/80 light:border-slate-200/80">
            <!-- If custom uploaded image -->
            <img 
              v-if="adminDashboard?.image_url" 
              :src="adminDashboard.image_url" 
              :alt="adminDashboard.alt_text || 'Administrator Dashboard Screenshot'"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
              @click="openLightbox(adminDashboard)"
            />
            <!-- Attractive Mockup Placeholder with Role UI Simulation -->
            <div 
              v-else 
              class="w-full h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-slate-900 via-rose-950/20 to-slate-900 cursor-pointer"
              @click="openLightbox(adminDashboard)"
            >
              <div class="w-16 h-16 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <ShieldCheck class="w-8 h-8" />
              </div>
              <span class="text-sm font-bold text-white light:text-slate-900">Administrator Console UI</span>
              <span class="text-xs text-rose-400/80 mt-1 font-mono">Real screenshot can be uploaded in Admin</span>
              <button 
                class="mt-3 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30 flex items-center gap-1.5 hover:bg-rose-500/30 transition-colors"
              >
                <Maximize2 class="w-3.5 h-3.5" />
                <span>Preview Layout</span>
              </button>
            </div>

            <!-- Role Badge Overlay -->
            <div class="absolute top-4 left-4">
              <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-500/90 text-white shadow-md flex items-center gap-1.5 backdrop-blur-md">
                <Shield class="w-3.5 h-3.5" />
                Administrator
              </span>
            </div>

            <button 
              v-if="adminDashboard?.image_url"
              @click="openLightbox(adminDashboard)" 
              class="absolute bottom-3 right-3 px-3 py-1.5 rounded-lg text-xs font-medium bg-black/60 text-white backdrop-blur-md hover:bg-rose-600 transition-colors flex items-center gap-1"
            >
              <Maximize2 class="w-3.5 h-3.5" />
              <span>Expand</span>
            </button>
          </div>

          <!-- Card Content -->
          <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-xl font-bold text-white light:text-slate-900 mb-2">
                {{ adminDashboard?.title || 'Administrator Dashboard' }}
              </h3>
              <p class="text-sm text-slate-400 light:text-slate-600 leading-relaxed mb-4">
                {{ adminDashboard?.description || 'User registration, activation, and deactivation. User management and search. Reports and activity tracking. System administration. Library statistics and dashboard overview.' }}
              </p>
            </div>

            <!-- Role Feature Bullets -->
            <div class="pt-4 border-t border-slate-800/60 light:border-slate-200/60">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-300 light:text-slate-700">
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-rose-400" />
                  <span>User activation & roles</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-rose-400" />
                  <span>Activity logs & tracking</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-rose-400" />
                  <span>System metrics overview</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-rose-400" />
                  <span>Administrative controls</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2: Librarian Dashboard (Right Top) -->
        <div 
          class="group rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 overflow-hidden hover:border-indigo-500/50 transition-all duration-300 shadow-xl flex flex-col"
        >
          <!-- Screenshot Area -->
          <div class="relative aspect-video bg-slate-900 light:bg-slate-100 overflow-hidden border-b border-slate-800/80 light:border-slate-200/80">
            <img 
              v-if="librarianDashboard?.image_url" 
              :src="librarianDashboard.image_url" 
              :alt="librarianDashboard.alt_text || 'Librarian Dashboard Screenshot'"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
              @click="openLightbox(librarianDashboard)"
            />
            <div 
              v-else 
              class="w-full h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-slate-900 via-indigo-950/20 to-slate-900 cursor-pointer"
              @click="openLightbox(librarianDashboard)"
            >
              <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <BookmarkCheck class="w-8 h-8" />
              </div>
              <span class="text-sm font-bold text-white light:text-slate-900">Librarian Circulation UI</span>
              <span class="text-xs text-indigo-400/80 mt-1 font-mono">Real screenshot can be uploaded in Admin</span>
              <button 
                class="mt-3 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 flex items-center gap-1.5 hover:bg-indigo-500/30 transition-colors"
              >
                <Maximize2 class="w-3.5 h-3.5" />
                <span>Preview Layout</span>
              </button>
            </div>

            <!-- Role Badge Overlay -->
            <div class="absolute top-4 left-4">
              <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-md flex items-center gap-1.5 backdrop-blur-md">
                <Bookmark class="w-3.5 h-3.5" />
                Librarian
              </span>
            </div>

            <button 
              v-if="librarianDashboard?.image_url"
              @click="openLightbox(librarianDashboard)" 
              class="absolute bottom-3 right-3 px-3 py-1.5 rounded-lg text-xs font-medium bg-black/60 text-white backdrop-blur-md hover:bg-indigo-600 transition-colors flex items-center gap-1"
            >
              <Maximize2 class="w-3.5 h-3.5" />
              <span>Expand</span>
            </button>
          </div>

          <!-- Card Content -->
          <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-xl font-bold text-white light:text-slate-900 mb-2">
                {{ librarianDashboard?.title || 'Librarian Dashboard' }}
              </h3>
              <p class="text-sm text-slate-400 light:text-slate-600 leading-relaxed mb-4">
                {{ librarianDashboard?.description || 'Manage books and book information. Handle book borrowing and returns. Track borrowing records and overdue items. Manage library resources. Review borrowing statistics.' }}
              </p>
            </div>

            <div class="pt-4 border-t border-slate-800/60 light:border-slate-200/60">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-300 light:text-slate-700">
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-indigo-400" />
                  <span>Manage books catalog</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-indigo-400" />
                  <span>Borrowing & return queue</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-indigo-400" />
                  <span>Overdue item alerts</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-indigo-400" />
                  <span>Borrowing statistics</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3: Teacher Dashboard (Left Bottom) -->
        <div 
          class="group rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 overflow-hidden hover:border-emerald-500/50 transition-all duration-300 shadow-xl flex flex-col"
        >
          <!-- Screenshot Area -->
          <div class="relative aspect-video bg-slate-900 light:bg-slate-100 overflow-hidden border-b border-slate-800/80 light:border-slate-200/80">
            <img 
              v-if="teacherDashboard?.image_url" 
              :src="teacherDashboard.image_url" 
              :alt="teacherDashboard.alt_text || 'Teacher Dashboard Screenshot'"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
              @click="openLightbox(teacherDashboard)"
            />
            <div 
              v-else 
              class="w-full h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-slate-900 via-emerald-950/20 to-slate-900 cursor-pointer"
              @click="openLightbox(teacherDashboard)"
            >
              <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <GraduationCap class="w-8 h-8" />
              </div>
              <span class="text-sm font-bold text-white light:text-slate-900">Teacher Resource Portal UI</span>
              <span class="text-xs text-emerald-400/80 mt-1 font-mono">Real screenshot can be uploaded in Admin</span>
              <button 
                class="mt-3 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1.5 hover:bg-emerald-500/30 transition-colors"
              >
                <Maximize2 class="w-3.5 h-3.5" />
                <span>Preview Layout</span>
              </button>
            </div>

            <!-- Role Badge Overlay -->
            <div class="absolute top-4 left-4">
              <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-md flex items-center gap-1.5 backdrop-blur-md">
                <GraduationCap class="w-3.5 h-3.5" />
                Teacher
              </span>
            </div>

            <button 
              v-if="teacherDashboard?.image_url"
              @click="openLightbox(teacherDashboard)" 
              class="absolute bottom-3 right-3 px-3 py-1.5 rounded-lg text-xs font-medium bg-black/60 text-white backdrop-blur-md hover:bg-emerald-600 transition-colors flex items-center gap-1"
            >
              <Maximize2 class="w-3.5 h-3.5" />
              <span>Expand</span>
            </button>
          </div>

          <!-- Card Content -->
          <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-xl font-bold text-white light:text-slate-900 mb-2">
                {{ teacherDashboard?.title || 'Teacher Dashboard' }}
              </h3>
              <p class="text-sm text-slate-400 light:text-slate-600 leading-relaxed mb-4">
                {{ teacherDashboard?.description || 'Add new books. Search and view book information. Manage available library resources. Access borrowing-related features where authorized.' }}
              </p>
            </div>

            <div class="pt-4 border-t border-slate-800/60 light:border-slate-200/60">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-300 light:text-slate-700">
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-emerald-400" />
                  <span>Add curriculum books</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-emerald-400" />
                  <span>Resource search & access</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-emerald-400" />
                  <span>Academic reading lists</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-emerald-400" />
                  <span>Authorized borrowings</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 4: Student Dashboard (Right Bottom) -->
        <div 
          class="group rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 overflow-hidden hover:border-amber-500/50 transition-all duration-300 shadow-xl flex flex-col"
        >
          <!-- Screenshot Area -->
          <div class="relative aspect-video bg-slate-900 light:bg-slate-100 overflow-hidden border-b border-slate-800/80 light:border-slate-200/80">
            <img 
              v-if="studentDashboard?.image_url" 
              :src="studentDashboard.image_url" 
              :alt="studentDashboard.alt_text || 'Student Dashboard Screenshot'"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer"
              @click="openLightbox(studentDashboard)"
            />
            <div 
              v-else 
              class="w-full h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-slate-900 via-amber-950/20 to-slate-900 cursor-pointer"
              @click="openLightbox(studentDashboard)"
            >
              <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <BookOpenCheck class="w-8 h-8" />
              </div>
              <span class="text-sm font-bold text-white light:text-slate-900">Student Portal UI</span>
              <span class="text-xs text-amber-400/80 mt-1 font-mono">Real screenshot can be uploaded in Admin</span>
              <button 
                class="mt-3 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center gap-1.5 hover:bg-amber-500/30 transition-colors"
              >
                <Maximize2 class="w-3.5 h-3.5" />
                <span>Preview Layout</span>
              </button>
            </div>

            <!-- Role Badge Overlay -->
            <div class="absolute top-4 left-4">
              <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-slate-950 shadow-md flex items-center gap-1.5 backdrop-blur-md">
                <BookOpenCheck class="w-3.5 h-3.5" />
                Student
              </span>
            </div>

            <button 
              v-if="studentDashboard?.image_url"
              @click="openLightbox(studentDashboard)" 
              class="absolute bottom-3 right-3 px-3 py-1.5 rounded-lg text-xs font-medium bg-black/60 text-white backdrop-blur-md hover:bg-amber-500 transition-colors flex items-center gap-1"
            >
              <Maximize2 class="w-3.5 h-3.5" />
              <span>Expand</span>
            </button>
          </div>

          <!-- Card Content -->
          <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-xl font-bold text-white light:text-slate-900 mb-2">
                {{ studentDashboard?.title || 'Student Dashboard' }}
              </h3>
              <p class="text-sm text-slate-400 light:text-slate-600 leading-relaxed mb-4">
                {{ studentDashboard?.description || 'Browse and search books. View book details and availability. Borrow and return books where permitted. Review borrowing history and reading progress.' }}
              </p>
            </div>

            <div class="pt-4 border-t border-slate-800/60 light:border-slate-200/60">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-300 light:text-slate-700">
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-amber-400" />
                  <span>Browse & search books</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-amber-400" />
                  <span>Real-time availability</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-amber-400" />
                  <span>Borrowing history track</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <Check class="w-3.5 h-3.5 text-amber-400" />
                  <span>Reading progress reviews</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Project Narrative: Overview, Tech Stack, Challenges & Key Learnings -->
      <div class="p-8 sm:p-10 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 shadow-2xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- Column 1: Project Overview -->
          <div>
            <h4 class="text-lg font-bold text-white light:text-slate-900 mb-3 flex items-center gap-2">
              <Layers class="w-5 h-5 text-blue-400" />
              <span>Project Overview</span>
            </h4>
            <p class="text-sm text-slate-400 light:text-slate-600 leading-relaxed">
              Designed as a comprehensive solution for university and school libraries. The architecture separates concerns between administrative controls, operational inventory tasks by librarians, instructional requests by teachers, and self-service book lookup for students.
            </p>
          </div>

          <!-- Column 2: Tech Stack Actually Used -->
          <div>
            <h4 class="text-lg font-bold text-white light:text-slate-900 mb-3 flex items-center gap-2">
              <Cpu class="w-5 h-5 text-indigo-400" />
              <span>Core Technologies</span>
            </h4>
            <div class="flex flex-wrap gap-2">
              <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500/10 text-rose-300 border border-rose-500/20">Laravel</span>
              <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">Vue.js 3</span>
              <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-300 border border-blue-500/20">PostgreSQL</span>
              <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-500/10 text-purple-300 border border-purple-500/20">PHP 8</span>
              <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">Tailwind CSS</span>
              <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-300 border border-amber-500/20">RESTful API</span>
            </div>
          </div>

          <!-- Column 3: Engineering Challenges & Learnings -->
          <div>
            <h4 class="text-lg font-bold text-white light:text-slate-900 mb-3 flex items-center gap-2">
              <CheckCircle class="w-5 h-5 text-emerald-400" />
              <span>Key Learnings</span>
            </h4>
            <ul class="space-y-2 text-xs text-slate-400 light:text-slate-600">
              <li class="flex items-start gap-2">
                <span class="text-blue-400 font-bold">•</span>
                <span>Optimized database transactions for concurrent book checkout and return.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-blue-400 font-bold">•</span>
                <span>Implemented granular role authorization policies preventing privilege escalation.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-blue-400 font-bold">•</span>
                <span>Built reactive dashboard state with Vue 3 Pinia for instant search and filters.</span>
              </li>
            </ul>
          </div>

        </div>
      </div>

    </div>

    <!-- Lightbox Modal for Full Screenshot Zoom -->
    <div 
      v-if="activeLightbox" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md animate-fade-in"
      @click.self="activeLightbox = null"
    >
      <div class="relative max-w-5xl w-full max-h-[90vh] bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden flex flex-col shadow-2xl">
        <!-- Lightbox Header -->
        <div class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-600 text-white uppercase tracking-wider">
              {{ activeLightbox.role }}
            </span>
            <h3 class="text-base sm:text-lg font-bold text-white">{{ activeLightbox.title }}</h3>
          </div>
          <button 
            @click="activeLightbox = null"
            class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
            aria-label="Close Lightbox"
          >
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Lightbox Image / Mockup Body -->
        <div class="p-4 overflow-auto flex-1 flex items-center justify-center bg-slate-950">
          <img 
            v-if="activeLightbox.image_url" 
            :src="activeLightbox.image_url" 
            :alt="activeLightbox.alt_text"
            class="max-w-full max-h-[70vh] object-contain rounded-xl shadow-2xl"
          />
          <div v-else class="text-center p-12 max-w-md">
            <div class="w-20 h-20 rounded-2xl bg-blue-600/10 text-blue-400 flex items-center justify-center mx-auto mb-4 border border-blue-500/20">
              <BookOpen class="w-10 h-10" />
            </div>
            <h4 class="text-xl font-bold text-white mb-2">{{ activeLightbox.title }}</h4>
            <p class="text-sm text-slate-400 leading-relaxed mb-4">{{ activeLightbox.description }}</p>
            <p class="text-xs text-blue-400 bg-blue-900/30 py-2 px-4 rounded-xl border border-blue-500/30">
              Screenshot placeholder — you can upload the real high-res screenshot via the Admin Dashboard.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { 
  BookOpen, ShieldCheck, BookmarkCheck, GraduationCap, 
  BookOpenCheck, Shield, Bookmark, Maximize2, Check, 
  Layers, Cpu, CheckCircle, Database, Zap, ShieldAlert, 
  CheckCircle2, X 
} from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const dashboards = computed(() => portfolioStore.libraryDashboards)

const adminDashboard = computed(() => {
  return dashboards.value.find(d => d.role === 'administrator') || null
})

const librarianDashboard = computed(() => {
  return dashboards.value.find(d => d.role === 'librarian') || null
})

const teacherDashboard = computed(() => {
  return dashboards.value.find(d => d.role === 'teacher') || null
})

const studentDashboard = computed(() => {
  return dashboards.value.find(d => d.role === 'student') || null
})

const activeLightbox = ref(null)

function openLightbox(dashboard) {
  if (dashboard) {
    activeLightbox.value = dashboard
  }
}
</script>

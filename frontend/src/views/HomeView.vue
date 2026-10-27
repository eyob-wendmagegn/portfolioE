<template>
  <div class="relative bg-slate-950 light:bg-slate-50 min-h-screen text-slate-100 light:text-slate-900 selection:bg-blue-600/30">
    <!-- Subtle Grid Background Pattern -->
    <div class="fixed inset-0 bg-grid-pattern pointer-events-none opacity-40 z-0"></div>

    <!-- Navigation Bar -->
    <Navbar />

    <!-- Main Content Flow -->
    <main class="relative z-10">
      <!-- Loading State Skeleton -->
      <div v-if="portfolioStore.loading && !hasLoadedOnce" class="min-h-screen flex items-center justify-center">
        <div class="text-center space-y-4">
          <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
          <p class="text-sm text-slate-400 font-mono">Loading Eyob's Portfolio...</p>
        </div>
      </div>

      <template v-else>
        <!-- Hero Section -->
        <HeroSection />

        <!-- Professional Summary / Pillars & Dynamic Stats -->
        <StatsSection />

        <!-- Library Management System - Main Flagship Showcase -->
        <LibraryManagementShowcase />

        <!-- Featured Projects Showcase -->
        <FeaturedProjects @select-project="openProjectModal" />

        <!-- Full Work / Projects Section with Category Filters & Search -->
        <WorkProjectsSection @select-project="openProjectModal" />

        <!-- Skills & Technologies -->
        <SkillsSection />

        <!-- About Me Section -->
        <AboutSection />

        <!-- Services Section -->
        <ServicesSection />

        <!-- Contact Section -->
        <ContactSection />
      </template>
    </main>

    <!-- Footer -->
    <Footer />

    <!-- Detailed Project Modal -->
    <ProjectModal 
      :project="selectedProject" 
      @close="selectedProject = null" 
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'

import Navbar from '@/components/Navbar.vue'
import HeroSection from '@/components/HeroSection.vue'
import StatsSection from '@/components/StatsSection.vue'
import LibraryManagementShowcase from '@/components/LibraryManagementShowcase.vue'
import FeaturedProjects from '@/components/FeaturedProjects.vue'
import WorkProjectsSection from '@/components/WorkProjectsSection.vue'
import SkillsSection from '@/components/SkillsSection.vue'
import AboutSection from '@/components/AboutSection.vue'
import ServicesSection from '@/components/ServicesSection.vue'
import ContactSection from '@/components/ContactSection.vue'
import Footer from '@/components/Footer.vue'
import ProjectModal from '@/components/ProjectModal.vue'

const portfolioStore = usePortfolioStore()
const selectedProject = ref(null)
const hasLoadedOnce = ref(false)

function openProjectModal(project) {
  selectedProject.value = project
}

onMounted(async () => {
  try {
    await portfolioStore.fetchPortfolio()
  } catch (e) {
    console.error('Failed loading portfolio data', e)
  } finally {
    hasLoadedOnce.value = true
  }
})
</script>

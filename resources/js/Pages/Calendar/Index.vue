<template>
  <div>
    <Head :title="localLanguage === 'uk' ? 'Календар' : 'Calendar'" />
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ localLanguage === 'uk' ? 'Календар подій' : 'Event Calendar' }}
        </h1>
        
        <!-- Optional: Add responsive controls here if needed -->
        <div class="flex space-x-3">
          <Link 
            href="/events/create" 
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <span class="hidden sm:inline">{{ localLanguage === 'uk' ? 'Створити подію' : 'Create Event' }}</span>
            <span class="sm:hidden">{{ localLanguage === 'uk' ? 'Нова' : 'New' }}</span>
          </Link>
        </div>
      </div>

      <!-- Main container with responsive layout -->
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Calendar takes full width on small screens, 3/4 on large screens -->
        <div class="w-full lg:w-3/4">
          <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl">
            <Calendar :events="events" :language="localLanguage" />
          </div>
        </div>
        
        <!-- Task list moves below on small screens, appears on right on large screens -->
        <div class="w-full lg:w-1/4 mt-6 lg:mt-0">
          <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6 transition-all duration-300 hover:shadow-xl">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-bold text-blue-800">
                {{ localLanguage === 'uk' ? 'Найближчі завдання' : 'Upcoming Tasks' }}
              </h2>
              <Link href="/tasks" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200">
                {{ localLanguage === 'uk' ? 'Усі завдання' : 'All Tasks' }}
              </Link>
            </div>
            
            <!-- Loading indicator when tasks are fetching -->
            <div v-if="loadingTasks" class="py-8 text-center">
              <div class="animate-spin h-8 w-8 border-4 border-blue-500 border-t-transparent rounded-full mx-auto"></div>
              <p class="mt-2 text-blue-600">{{ localLanguage === 'uk' ? 'Завантаження...' : 'Loading...' }}</p>
            </div>
            
            <!-- Tasks list when available -->
            <div v-else-if="upcomingTasks.length > 0" class="space-y-3">
              <div v-for="task in upcomingTasks" :key="task.id" 
                   class="p-3 bg-blue-50 rounded-lg transition-all duration-200 hover:bg-blue-100 border-l-2"
                   :class="task.completed ? 'border-green-500' : 'border-red-500'">
                <div class="text-sm font-medium text-blue-900">{{ task.title }}</div>
                <div class="text-xs text-blue-700 mt-1 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                  </svg>
                  {{ formatDate(task.due_date) }}
                </div>
              </div>
            </div>
            
            <!-- No tasks placeholder -->
            <div v-else class="text-center py-10 text-blue-600 bg-blue-50 rounded-lg">
              {{ localLanguage === 'uk' ? 'Немає найближчих завдань' : 'No upcoming tasks' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Calendar from '@/Shared/Calendar.vue'
import dayjs from 'dayjs'
import axios from 'axios'

export default {
  components: {
    Head,
    Link,
    Calendar,
  },
  layout: Layout,
  props: {
    events: {
      type: Array,
      required: true
    },
    language: {
      type: String,
      default: () => localStorage.getItem('language') || 'uk'
    }
  },
  data() {
    return {
      localLanguage: this.language,
      tasks: [],
      loadingTasks: true
    }
  },
  computed: {
    upcomingTasks() {
      const today = dayjs().startOf('day')
      const nextWeek = dayjs().add(7, 'day').endOf('day')
      
      return this.tasks.filter(task => {
        const taskDate = dayjs(task.due_date).startOf('day')
        return taskDate.isAfter(today) && taskDate.isBefore(nextWeek)
      }).sort((a, b) => {
        return dayjs(a.due_date).diff(dayjs(b.due_date))
      }).slice(0, 5) // Show only 5 upcoming tasks
    }
  },
  methods: {
    formatDate(date) {
      return dayjs(date).format('DD.MM - HH:mm')
    },
    fetchTasks() {
      this.loadingTasks = true
      axios.get('/api/tasks')
        .then(response => {
          this.tasks = response.data.tasks || []
          this.loadingTasks = false
        })
        .catch(error => {
          console.error('Error fetching tasks:', error)
          this.loadingTasks = false
        })
    }
  },
  mounted() {
    // Fetch tasks when component mounts
    this.fetchTasks()
    
    // Listen for language changes using the event bus
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.localLanguage = lang
      })
    }
    
    // Also listen for storage events for backward compatibility
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.localLanguage = event.newValue
      }
    })
  }
}
</script>

<style scoped>
/* Add any specific styles needed for the calendar page here */
.sticky {
  position: sticky;
  top: 1.5rem;
}
</style> 
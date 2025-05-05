<template>
  <div>
    <Head :title="localLanguage === 'uk' ? 'Головна Сторінка' : 'Home Page'" />
    
    <!-- Hero Section - Only shown on first visit or until dismissed -->
    <div v-if="showWelcomeBanner" class="relative overflow-hidden mb-12 rounded-2xl">
      <!-- Background with gradient overlay -->
      <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-800 opacity-90"></div>
      
      <!-- Background pattern -->
      <div class="absolute inset-0 bg-pattern opacity-10"></div>
      
      <!-- Close button -->
      <button @click="dismissWelcomeBanner" class="absolute top-4 right-4 p-2 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white transition-all duration-200 transform hover:scale-105 focus:outline-none" aria-label="Close welcome banner">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <div class="relative max-w-5xl mx-auto px-6 py-20 sm:px-8 sm:py-28 lg:py-36 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl bg-clip-text">
          {{ localLanguage === 'uk' ? 'Шкільний Календар Подій' : 'School Event Calendar' }}
        </h1>
        <p class="mt-8 max-w-2xl mx-auto text-xl text-blue-100 leading-relaxed">
          {{ localLanguage === 'uk' ? 'Стеж за всіма важливими подіями у школі та не пропусти жодної дати!' : 'Keep track of all important school events and never miss a date!' }}
        </p>
        <div class="mt-12 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center">
          <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-6">
            <a href="/events" 
               class="flex items-center justify-center px-8 py-4 text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105">
              {{ localLanguage === 'uk' ? 'Переглянути події' : 'View Events' }}
            </a>
            <a href="/tasks" 
               class="flex items-center justify-center px-8 py-4 text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105">
              {{ localLanguage === 'uk' ? 'Мої завдання' : 'My Tasks' }}
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Parent Student Registration Banner - Only for parent role -->
    <div v-if="isParentUser && !studentBannerDismissed" class="relative overflow-hidden mb-8 rounded-xl bg-gradient-to-r from-green-500 to-green-600">
      <!-- Close button -->
      <button @click="dismissStudentBanner" class="absolute top-3 right-3 p-1.5 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white transition-all duration-200 transform hover:scale-105 focus:outline-none z-10" aria-label="Close banner">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <div class="px-6 py-5 flex items-center sm:px-8">
        <div class="flex-shrink-0 mr-8">
          <div class="h-12 w-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
          </div>
        </div>
        <div class="flex-1 mr-8">
          <h3 class="text-lg font-medium text-white">
            {{ localLanguage === 'uk' ? 'Додайте своїх учнів' : 'Add your students' }}
          </h3>
          <p class="mt-1 text-sm text-green-100">
            {{ localLanguage === 'uk' ? 'Зареєструйте інформацію про своїх дітей, щоб мати доступ до їх розкладу та завдань.' : 'Register information about your children to access their schedule and assignments.' }}
          </p>
        </div>
        <div class="flex-shrink-0 mr-6">
          <Link href="/students/create" class="whitespace-nowrap inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-green-700 text-white hover:bg-green-800 transition-all duration-300 shadow-sm">
            {{ localLanguage === 'uk' ? 'Додати учня' : 'Add student' }}
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1.5 -mr-0.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </Link>
        </div>
      </div>
    </div>
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Calendar Section - Fixed styles for better responsiveness -->
        <div class="lg:col-span-2 transform transition-all duration-300 hover:scale-[1.01] dashboard-calendar">
          <div class="calendar-wrapper overflow-hidden">
            <Calendar :events="events" :language="localLanguage" />
          </div>
        </div>

        <!-- Today's Tasks Section -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-[1.02] dashboard-tasks">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-bold text-blue-900">
                {{ localLanguage === 'uk' ? 'Завдання на сьогодні' : 'Today\'s Tasks' }}
              </h3>
              <div class="flex space-x-3">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-green-100 text-green-800 transition-all duration-300 hover:bg-green-200">
                  {{ completedTasksCount }} {{ localLanguage === 'uk' ? 'Виконано' : 'Completed' }}
                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-800 transition-all duration-300 hover:bg-red-200">
                  {{ uncompletedTasksCount }} {{ localLanguage === 'uk' ? 'Не виконано' : 'Not Completed' }}
                </span>
              </div>
            </div>

            <div class="space-y-4">
              <!-- Tasks for today -->
              <div v-for="(task, index) in todayTasks" :key="`task-${task.id}`" 
                   class="bg-blue-50 rounded-lg p-4 transition-all duration-300 hover:bg-blue-100 hover:shadow-md task-item"
                   :style="{ animationDelay: `${0.3 + index * 0.1}s` }">
                <div class="flex items-start justify-between">
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-blue-900">{{ task.title }}</p>
                    <p class="text-sm text-blue-700 mt-1">{{ task.event }}</p>
                    <p class="text-xs text-blue-600 mt-2">{{ formatTime(task.due_date) }}</p>
                  </div>
                  <Link :href="`/tasks/${task.id}/edit`" 
                        class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                    <span class="sr-only">{{ localLanguage === 'uk' ? 'Редагувати' : 'Edit' }}</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                  </Link>
                </div>
              </div>
              
              <!-- Events for today -->
              <div v-for="(event, index) in todayEvents" :key="`event-${event.id}`" 
                   class="bg-purple-50 rounded-lg p-4 transition-all duration-300 hover:bg-purple-100 hover:shadow-md task-item"
                   :style="{ animationDelay: `${0.3 + (todayTasks.length + index) * 0.1}s` }">
                <div class="flex items-start justify-between">
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-purple-900">{{ event.title }}</p>
                    <p class="text-sm text-purple-700 mt-1">{{ localLanguage === 'uk' ? 'Подія' : 'Event' }}</p>
                    <p class="text-xs text-purple-600 mt-2">{{ formatTime(event.start_date) }}</p>
                  </div>
                  <Link :href="`/events/${event.id}/edit`" 
                        class="text-purple-600 hover:text-purple-900 transition-colors duration-200">
                    <span class="sr-only">{{ localLanguage === 'uk' ? 'Редагувати' : 'Edit' }}</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                  </Link>
                </div>
              </div>

              <div v-if="todayTasks.length === 0 && todayEvents.length === 0" 
                   class="text-center text-blue-600 py-8 bg-blue-50 rounded-lg task-item"
                   :style="{ animationDelay: '0.3s' }">
                {{ localLanguage === 'uk' ? 'Немає завдань на сьогодні' : 'No tasks for today' }}
              </div>
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
    tasks: {
      type: Array,
      required: true
    },
    language: {
      type: String,
      default: () => localStorage.getItem('language') || 'uk'
    },
    userRole: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      localLanguage: this.language,
      showWelcomeBanner: true,
      hasVisitedBefore: localStorage.getItem('hasVisitedBefore') === 'true',
      isParentUser: false,
      studentBannerDismissed: false
    }
  },
  computed: {
    todayTasks() {
      const today = dayjs().startOf('day')
      return this.tasks.filter(task => {
        const taskDate = dayjs(task.due_date).startOf('day')
        return taskDate.isSame(today)
      }).sort((a, b) => {
        return dayjs(a.due_date).diff(dayjs(b.due_date))
      })
    },
    todayEvents() {
      const today = dayjs().startOf('day')
      return this.events.filter(event => {
        const eventDate = dayjs(event.start_date).startOf('day')
        return eventDate.isSame(today)
      }).sort((a, b) => {
        return dayjs(a.start_date).diff(dayjs(b.start_date))
      })
    },
    completedTasks() {
      return this.todayTasks.filter(task => task.completed)
    },
    uncompletedTasks() {
      return this.todayTasks.filter(task => !task.completed)
    },
    completedTasksCount() {
      return this.completedTasks.length
    },
    uncompletedTasksCount() {
      return this.uncompletedTasks.length + this.todayEvents.length
    }
  },
  methods: {
    formatTime(date) {
      return dayjs(date).format('HH:mm')
    },
    dismissWelcomeBanner() {
      this.showWelcomeBanner = false
      localStorage.setItem('welcomeBannerDismissed', 'true')
    },
    dismissStudentBanner() {
      this.studentBannerDismissed = true
      localStorage.setItem('studentBannerDismissed', 'true')
    },
    restoreStudentBanner() {
      this.studentBannerDismissed = false
      localStorage.setItem('studentBannerDismissed', 'false')
    }
  },
  mounted() {
    
    if (localStorage.getItem('welcomeBannerDismissed') === 'true') {
      this.showWelcomeBanner = false
    }
    
    
    if (!localStorage.getItem('hasVisitedBefore')) {
      localStorage.setItem('hasVisitedBefore', 'true')
    }
    
    
    this.isParentUser = this.userRole === 'parent'
    
    
    if (localStorage.getItem('studentBannerDismissed') === 'true') {
      this.studentBannerDismissed = true
    }
    
    
    this.localLanguage = localStorage.getItem('language') || 'uk'
    
    
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.localLanguage = lang
      })
    }
    
    
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.localLanguage = event.newValue
      }
    })
  }
}
</script>

<style scoped>
/* Added styles for calendar responsiveness */
.calendar-wrapper {
  width: 100%;
  overflow-x: auto;
  border-radius: 1rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.dashboard-calendar :deep(.calendar-container) {
  min-width: 800px;
  width: 100%;
}

@media (max-width: 1023px) {
  .dashboard-calendar :deep(.calendar-container) {
    min-width: 700px;
  }
}

/* Add animation for task items */
.task-item {
  opacity: 0;
  transform: translateY(10px);
  animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

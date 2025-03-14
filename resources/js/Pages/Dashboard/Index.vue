<template>
  <div>
    <Head :title="language === 'uk' ? 'Головна Сторінка' : 'Home Page'" />
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden mb-8">
      <!-- Background with gradient overlay -->
      <div class="absolute inset-0 bg-gradient-to-r from-amber-600 to-amber-800 opacity-90"></div>
      
      <!-- Background pattern -->
      <div class="absolute inset-0 bg-pattern opacity-10"></div>
      
      <div class="relative max-w-5xl mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:py-32 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
          {{ language === 'uk' ? 'Шкільний Календар Подій' : 'School Event Calendar' }}
        </h1>
        <p class="mt-6 max-w-2xl mx-auto text-xl text-amber-100">
          {{ language === 'uk' ? 'Стеж за всіма важливими подіями у школі та не пропусти жодної дати!' : 'Keep track of all important school events and never miss a date!' }}
        </p>
        <div class="mt-10 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center">
          <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
            <a href="/events" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-amber-700 bg-white hover:bg-amber-50 transition-all">
              {{ language === 'uk' ? 'Переглянути події' : 'View Events' }}
            </a>
            <a href="/tasks" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-amber-800 hover:bg-amber-900 transition-all">
              {{ language === 'uk' ? 'Мої завдання' : 'My Tasks' }}
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Calendar Section -->
        <div class="lg:col-span-2">
          <Calendar :events="events" :language="language" />
        </div>

        <!-- Today's Tasks Section -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-900">
                {{ language === 'uk' ? 'Завдання на сьогодні' : 'Today\'s Tasks' }}
              </h3>
              <div class="flex space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  {{ completedTasksCount }} {{ language === 'uk' ? 'Виконано' : 'Completed' }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                  {{ uncompletedTasksCount }} {{ language === 'uk' ? 'Не виконано' : 'Not Completed' }}
                </span>
              </div>
            </div>

            <div class="space-y-4">
              <!-- Completed Tasks -->
              <div v-if="completedTasks.length > 0" class="mb-6">
                <h4 class="text-sm font-medium text-gray-500 mb-2">{{ language === 'uk' ? 'Виконані завдання' : 'Completed Tasks' }}</h4>
                <div class="space-y-2">
                  <div v-for="task in completedTasks" :key="task.id" 
                       class="flex items-start space-x-3 p-3 rounded-lg bg-green-50">
                    <div class="flex-shrink-0">
                      <div class="h-6 w-6 rounded-full flex items-center justify-center bg-green-100">
                        <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                      </div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900">{{ task.title }}</p>
                      <p class="text-sm text-gray-500">{{ task.event }}</p>
                      <p class="text-xs text-gray-400 mt-1">{{ formatTime(task.due_date) }}</p>
                    </div>
                    <Link :href="`/tasks/${task.id}/edit`" 
                          class="text-green-600 hover:text-green-900">
                      <span class="sr-only">{{ language === 'uk' ? 'Редагувати' : 'Edit' }}</span>
                      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                      </svg>
                    </Link>
                  </div>
                </div>
              </div>

              <!-- Uncompleted Tasks -->
              <div v-if="uncompletedTasks.length > 0">
                <h4 class="text-sm font-medium text-gray-500 mb-2">{{ language === 'uk' ? 'Невиконані завдання' : 'Uncompleted Tasks' }}</h4>
                <div class="space-y-2">
                  <div v-for="task in uncompletedTasks" :key="task.id" 
                       class="flex items-start space-x-3 p-3 rounded-lg bg-red-50">
                    <div class="flex-shrink-0">
                      <div class="h-6 w-6 rounded-full flex items-center justify-center bg-red-100">
                        <svg class="h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                      </div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900">{{ task.title }}</p>
                      <p class="text-sm text-gray-500">{{ task.event }}</p>
                      <p class="text-xs text-gray-400 mt-1">{{ formatTime(task.due_date) }}</p>
                    </div>
                    <Link :href="`/tasks/${task.id}/edit`" 
                          class="text-red-600 hover:text-red-900">
                      <span class="sr-only">{{ language === 'uk' ? 'Редагувати' : 'Edit' }}</span>
                      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                      </svg>
                    </Link>
                  </div>
                </div>
              </div>

              <div v-if="todayTasks.length === 0" class="text-center text-gray-500 py-4">
                {{ language === 'uk' ? 'Немає завдань на сьогодні' : 'No tasks for today' }}
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
      required: true
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
      return this.uncompletedTasks.length
    }
  },
  methods: {
    formatTime(date) {
      return dayjs(date).format('HH:mm')
    }
  }
}
</script>

<style scoped>
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>

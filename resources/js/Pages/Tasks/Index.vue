<template>
  <div>
    <Head :title="language === 'uk' ? 'Завдання' : 'Tasks'" />
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-amber-800 bg-clip-text text-transparent">
        {{ language === 'uk' ? 'Завдання' : 'Tasks' }}
      </h1>
      <div class="mt-4 md:mt-0 flex items-center">
        <Link 
          class="flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-700 text-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105"
          href="/tasks/create"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          <span>{{ language === 'uk' ? 'Створити завдання' : 'Create Task' }}</span>
        </Link>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 transition-all duration-300 hover:shadow-xl">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
          <div class="relative">
            <input 
              v-model="form.search" 
              type="text" 
              :placeholder="language === 'uk' ? 'Пошук завдань...' : 'Search tasks...'" 
              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 transition-all duration-300"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
        </div>
        <div class="w-full md:w-1/3">
          <select 
            v-model="form.trashed" 
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50 transition-all duration-300"
          >
            <option :value="null">{{ language === 'uk' ? 'Всі завдання' : 'All Tasks' }}</option>
            <option value="with">{{ language === 'uk' ? 'З видаленими' : 'With Deleted' }}</option>
            <option value="only">{{ language === 'uk' ? 'Тільки видалені' : 'Only Deleted' }}</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Мобильный вид (карточки) -->
    <div class="md:hidden space-y-4">
      <div 
        v-for="task in tasks.data" 
        :key="task.id" 
        class="bg-white rounded-xl shadow-md p-4 transition-all duration-300 hover:shadow-lg hover:scale-[1.01]"
        :class="{ 'border-l-4 border-red-400': task.deleted_at }"
      >
        <div class="flex justify-between items-start">
          <div>
            <Link 
              class="text-lg font-bold text-amber-800 hover:text-amber-600 transition-colors duration-200" 
              :href="`/tasks/${task.id}/edit`"
            >
              {{ task.title }}
            </Link>
            <div class="mt-2 space-y-1">
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                {{ task.event || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                {{ task.due_date || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
              <div class="flex items-center text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span :class="{ 'text-green-600': task.completed, 'text-red-600': !task.completed }">
                  {{ task.completed ? (language === 'uk' ? 'Виконано' : 'Completed') : (language === 'uk' ? 'Не виконано' : 'Not Completed') }}
                </span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                </svg>
                {{ task.created_by || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
            </div>
          </div>
          <div class="flex space-x-2">
            <Link 
              :href="`/tasks/${task.id}/edit`" 
              class="p-2 text-amber-600 hover:text-amber-800 transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
              </svg>
            </Link>
            <button 
              v-if="!task.deleted_at"
              @click="destroy(task)" 
              class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div v-if="tasks.data.length === 0" class="bg-white rounded-xl shadow-md p-6 text-center text-gray-500">
        {{ language === 'uk' ? 'Завдань не знайдено.' : 'No tasks found.' }}
      </div>
    </div>

    <!-- Десктопный вид (таблица) -->
    <div class="hidden md:block bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
      <table class="w-full">
        <thead>
          <tr class="bg-gradient-to-r from-amber-50 to-amber-100 text-left">
            <th class="py-4 px-6 font-bold text-amber-900">{{ language === 'uk' ? 'Назва' : 'Title' }}</th>
            <th class="py-4 px-6 font-bold text-amber-900">{{ language === 'uk' ? 'Подія' : 'Event' }}</th>
            <th class="py-4 px-6 font-bold text-amber-900">{{ language === 'uk' ? 'Дата виконання' : 'Due Date' }}</th>
            <th class="py-4 px-6 font-bold text-amber-900">{{ language === 'uk' ? 'Статус' : 'Status' }}</th>
            <th class="py-4 px-6 font-bold text-amber-900">{{ language === 'uk' ? 'Створив' : 'Created By' }}</th>
            <th class="py-4 px-6 font-bold text-amber-900 text-center">{{ language === 'uk' ? 'Дії' : 'Actions' }}</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="task in tasks.data" 
            :key="task.id" 
            class="border-t border-gray-100 transition-colors duration-200 hover:bg-amber-50"
            :class="{ 'bg-red-50 hover:bg-red-100': task.deleted_at }"
          >
            <td class="py-4 px-6">
              <Link 
                class="font-medium text-amber-800 hover:text-amber-600 transition-colors duration-200 flex items-center" 
                :href="`/tasks/${task.id}/edit`"
              >
                {{ task.title }}
                <svg v-if="task.deleted_at" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </Link>
            </td>
            <td class="py-4 px-6 text-gray-600">
              {{ task.event || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
            </td>
            <td class="py-4 px-6 text-gray-600">
              {{ task.due_date || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
            </td>
            <td class="py-4 px-6">
              <div :class="{ 'text-green-600': task.completed, 'text-red-600': !task.completed }" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"
                     :class="{ 'text-green-500': task.completed, 'text-red-500': !task.completed }">
                  <path v-if="task.completed" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  <path v-else fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ task.completed ? (language === 'uk' ? 'Виконано' : 'Completed') : (language === 'uk' ? 'Не виконано' : 'Not Completed') }}
              </div>
            </td>
            <td class="py-4 px-6 text-gray-600">
              {{ task.created_by || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
            </td>
            <td class="py-4 px-6">
              <div class="flex justify-center space-x-3">
                <Link 
                  :href="`/tasks/${task.id}/edit`" 
                  class="p-2 text-amber-600 hover:text-amber-800 transition-colors duration-200 rounded-full hover:bg-amber-100"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </Link>
                <button 
                  v-if="!task.deleted_at"
                  @click="destroy(task)" 
                  class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200 rounded-full hover:bg-red-100"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="tasks.data.length === 0">
            <td class="py-6 px-6 text-center text-gray-500" colspan="6">
              {{ language === 'uk' ? 'Завдань не знайдено.' : 'No tasks found.' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-8">
      <pagination :links="tasks.links" />
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import debounce from 'lodash/debounce'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
  },
  layout: Layout,
  props: {
    tasks: Object,
    filters: Object,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: debounce(function () {
        this.$inertia.get('/tasks', this.form, { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = {
        search: null,
        trashed: null,
      }
    },
    destroy(task) {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете видалити це завдання?' : 'Are you sure you want to delete this task?')) {
        this.$inertia.delete(`/tasks/${task.id}`)
      }
    },
  },
  mounted() {
    // Listen for language changes using the event bus
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang
      })
    }
    
    // Also listen for storage events for backward compatibility
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.language = event.newValue
      }
    })
  }
}
</script>

<style scoped>
/* Анимации для карточек и элементов */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Стилизация пагинации */
:deep(.pagination) {
  @apply flex justify-center;
}

:deep(.pagination-link) {
  @apply px-4 py-2 mx-1 rounded-lg transition-colors duration-200;
}

:deep(.pagination-link:not(.active)) {
  @apply bg-white text-amber-700 hover:bg-amber-50;
}

:deep(.pagination-link.active) {
  @apply bg-gradient-to-r from-amber-500 to-amber-700 text-white;
}
</style> 
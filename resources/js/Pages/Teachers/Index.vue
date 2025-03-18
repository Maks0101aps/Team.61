<template>
  <div>
    <Head :title="language === 'uk' ? 'Вчителі' : 'Teachers'" />
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
        {{ language === 'uk' ? 'Вчителі' : 'Teachers' }}
      </h1>
      <div class="mt-4 md:mt-0 flex items-center">
        <Link 
          class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105"
          href="/teachers/create"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          <span>{{ language === 'uk' ? 'Створити вчителя' : 'Create Teacher' }}</span>
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
              :placeholder="language === 'uk' ? 'Пошук вчителів...' : 'Search teachers...'" 
              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-300"
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
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-300"
          >
            <option :value="null">{{ language === 'uk' ? 'Всі вчителі' : 'All Teachers' }}</option>
            <option value="with">{{ language === 'uk' ? 'З видаленими' : 'With Deleted' }}</option>
            <option value="only">{{ language === 'uk' ? 'Тільки видалені' : 'Only Deleted' }}</option>
        </select>
        </div>
      </div>
    </div>

    <!-- Мобильный вид (карточки) -->
    <div class="md:hidden space-y-4">
      <div 
        v-for="teacher in teachers.data" 
        :key="teacher.id" 
        class="bg-white rounded-xl shadow-md p-4 transition-all duration-300 hover:shadow-lg hover:scale-[1.01]"
        :class="{ 'border-l-4 border-red-400': teacher.deleted_at }"
      >
        <div class="flex justify-between items-start">
          <div>
            <Link 
              class="text-lg font-bold text-blue-800 hover:text-blue-600 transition-colors duration-200" 
              :href="`/teachers/${teacher.id}/edit`"
            >
              {{ teacher.name }}
            </Link>
            <div class="mt-2 space-y-1">
              <div v-if="teacher.organization" class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                {{ teacher.organization.name }}
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                </svg>
                {{ teacher.subject || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                {{ teacher.city || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                </svg>
                {{ teacher.phone || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
            </div>
          </div>
          <div class="flex space-x-2">
            <Link 
              :href="`/teachers/${teacher.id}/edit`" 
              class="p-2 text-blue-600 hover:text-blue-800 transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
              </svg>
            </Link>
            <button 
              @click="destroy(teacher)" 
              class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div v-if="teachers.data.length === 0" class="bg-white rounded-xl shadow-md p-6 text-center text-gray-500">
        {{ language === 'uk' ? 'Вчителів не знайдено.' : 'No teachers found.' }}
      </div>
    </div>

    <!-- Десктопный вид (таблица) -->
    <div class="hidden md:block bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
      <table class="w-full">
        <thead>
          <tr class="bg-gradient-to-r from-blue-50 to-blue-100 text-left">
            <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Повне ім\'я' : 'Full Name' }}</th>
            <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Організація' : 'Organization' }}</th>
            <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Предмет' : 'Subject' }}</th>
            <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Місто' : 'City' }}</th>
            <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Номер телефону' : 'Phone Number' }}</th>
            <th class="py-4 px-6 font-bold text-blue-900 text-center">{{ language === 'uk' ? 'Дії' : 'Actions' }}</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="teacher in teachers.data" 
            :key="teacher.id" 
            class="border-t border-gray-100 transition-colors duration-200 hover:bg-blue-50"
            :class="{ 'bg-red-50 hover:bg-red-100': teacher.deleted_at }"
          >
            <td class="py-4 px-6">
              <Link 
                class="font-medium text-blue-800 hover:text-blue-600 transition-colors duration-200 flex items-center" 
                :href="`/teachers/${teacher.id}/edit`"
              >
                {{ teacher.name }}
                <svg v-if="teacher.deleted_at" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </Link>
          </td>
            <td class="py-4 px-6 text-gray-600">
              <div v-if="teacher.organization">
                {{ teacher.organization.name }}
              </div>
              <div v-else class="text-gray-400">
                {{ language === 'uk' ? 'Не вказано' : 'Not specified' }}
              </div>
          </td>
            <td class="py-4 px-6 text-gray-600">
              {{ teacher.subject || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
          </td>
            <td class="py-4 px-6 text-gray-600">
              {{ teacher.city || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
          </td>
            <td class="py-4 px-6 text-gray-600">
              {{ teacher.phone || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
          </td>
            <td class="py-4 px-6">
              <div class="flex justify-center space-x-3">
                <Link 
                  :href="`/teachers/${teacher.id}/edit`" 
                  class="p-2 text-blue-600 hover:text-blue-800 transition-colors duration-200 rounded-full hover:bg-blue-100"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
            </Link>
                <button 
                  @click="destroy(teacher)" 
                  class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200 rounded-full hover:bg-red-100"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
            </button>
              </div>
          </td>
        </tr>
        <tr v-if="teachers.data.length === 0">
            <td class="py-6 px-6 text-center text-gray-500" colspan="6">
              {{ language === 'uk' ? 'Вчителів не знайдено.' : 'No teachers found.' }}
            </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-8">
      <pagination :links="teachers.links" />
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
  },
  layout: Layout,
  props: {
    filters: Object,
    teachers: Object,
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
      handler: throttle(function () {
        this.$inertia.get('/teachers', pickBy(this.form), { preserveState: true })
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
    destroy(teacher) {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете видалити цього вчителя?' : 'Are you sure you want to delete this teacher?')) {
        this.$inertia.delete(`/teachers/${teacher.id}`)
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
  @apply bg-white text-blue-700 hover:bg-blue-50;
}

:deep(.pagination-link.active) {
  @apply bg-gradient-to-r from-blue-500 to-blue-600 text-white;
}
</style> 
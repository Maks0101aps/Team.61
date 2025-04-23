<template>
  <div>
    <Head :title="language === 'uk' ? 'Списки вчителів' : 'Teacher Lists'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Списки вчителів' : 'Teacher Lists' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Списки вчителів' : 'Teacher Lists' }}
        </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/reports" 
          class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 flex items-center transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          {{ language === 'uk' ? 'Назад до звітів' : 'Back to Reports' }}
        </Link>
      </div>
    </div>

    <!-- Main content -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <div class="p-6">
        <!-- Filters -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-3">{{ language === 'uk' ? 'Фільтри' : 'Filters' }}</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Предмет' : 'Subject' }}</label>
              <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">{{ language === 'uk' ? 'Всі предмети' : 'All subjects' }}</option>
                <option>{{ language === 'uk' ? 'Математика' : 'Mathematics' }}</option>
                <option>{{ language === 'uk' ? 'Українська мова' : 'Ukrainian language' }}</option>
                <option>{{ language === 'uk' ? 'Англійська мова' : 'English language' }}</option>
                <option>{{ language === 'uk' ? 'Історія' : 'History' }}</option>
                <option>{{ language === 'uk' ? 'Фізика' : 'Physics' }}</option>
                <option>{{ language === 'uk' ? 'Хімія' : 'Chemistry' }}</option>
                <option>{{ language === 'uk' ? 'Біологія' : 'Biology' }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Досвід' : 'Experience' }}</label>
              <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">{{ language === 'uk' ? 'Всі' : 'All' }}</option>
                <option>{{ language === 'uk' ? 'Менше 5 років' : 'Less than 5 years' }}</option>
                <option>{{ language === 'uk' ? '5-10 років' : '5-10 years' }}</option>
                <option>{{ language === 'uk' ? '10-20 років' : '10-20 years' }}</option>
                <option>{{ language === 'uk' ? 'Більше 20 років' : 'More than 20 years' }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Статус' : 'Status' }}</label>
              <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">{{ language === 'uk' ? 'Всі статуси' : 'All statuses' }}</option>
                <option>{{ language === 'uk' ? 'Активний' : 'Active' }}</option>
                <option>{{ language === 'uk' ? 'Відпустка' : 'On leave' }}</option>
                <option>{{ language === 'uk' ? 'Неактивний' : 'Inactive' }}</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Data table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{ language === 'uk' ? 'Ім\'я' : 'Name' }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{ language === 'uk' ? 'Предмети' : 'Subjects' }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{ language === 'uk' ? 'Контактна інформація' : 'Contact Information' }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{ language === 'uk' ? 'Досвід' : 'Experience' }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{ language === 'uk' ? 'Статус' : 'Status' }}
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{ language === 'uk' ? 'Дії' : 'Actions' }}
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <!-- Real data from the server -->
              <tr v-for="(teacher, index) in filteredTeachers" :key="teacher.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                      <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                        {{ teacher.name.charAt(0) }}
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ teacher.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ teacher.subjects.join(', ') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ teacher.phone || (language === 'uk' ? 'Немає даних' : 'Not available') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ teacher.experience }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-green-100 text-green-800': teacher.status === 'Active',
                      'bg-red-100 text-red-800': teacher.status === 'Inactive',
                      'bg-yellow-100 text-yellow-800': teacher.status === 'On leave'
                    }">
                    {{ teacher.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">{{ language === 'uk' ? 'Перегляд' : 'View' }}</a>
                  <a href="#" class="text-blue-600 hover:text-blue-900">{{ language === 'uk' ? 'Звіт' : 'Report' }}</a>
                </td>
              </tr>
              <!-- Empty state -->
              <tr v-if="filteredTeachers.length === 0">
                <td class="px-6 py-4 whitespace-nowrap text-center" colspan="6">
                  <div class="text-gray-500">
                    {{ language === 'uk' ? 'Немає даних для відображення' : 'No data to display' }}
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 mt-4">
          <div class="flex-1 flex justify-between sm:hidden">
            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              {{ language === 'uk' ? 'Попередня' : 'Previous' }}
            </a>
            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              {{ language === 'uk' ? 'Наступна' : 'Next' }}
            </a>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                {{ language === 'uk' ? 'Показано' : 'Showing' }}
                <span class="font-medium">1</span>
                {{ language === 'uk' ? 'по' : 'to' }}
                <span class="font-medium">10</span>
                {{ language === 'uk' ? 'з' : 'of' }}
                <span class="font-medium">43</span>
                {{ language === 'uk' ? 'результатів' : 'results' }}
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span class="sr-only">{{ language === 'uk' ? 'Попередня' : 'Previous' }}</span>
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </a>
                <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                  1
                </a>
                <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                  2
                </a>
                <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                  3
                </a>
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                  ...
                </span>
                <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                  5
                </a>
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span class="sr-only">{{ language === 'uk' ? 'Наступна' : 'Next' }}</span>
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </a>
              </nav>
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

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      teachers: [],
      filters: {
        subject: '',
        experience: '',
        status: ''
      }
    }
  },
  created() {
    
    if (this.$page.props.initialTeacherData) {
      this.teachers = this.$page.props.initialTeacherData;
    }
  },
  computed: {
    filteredTeachers() {
      return this.teachers.filter(teacher => {
        
        if (this.filters.subject && !teacher.subjects.some(s => s.includes(this.filters.subject))) {
          return false;
        }
        
        
        if (this.filters.experience) {
          const yearsMatch = teacher.experience.match(/(\d+)/);
          if (yearsMatch) {
            const years = parseInt(yearsMatch[0]);
            
            if (this.filters.experience === 'Less than 5 years' && years >= 5) {
              return false;
            } else if (this.filters.experience === '5-10 years' && (years < 5 || years > 10)) {
              return false;
            } else if (this.filters.experience === '10-20 years' && (years < 10 || years > 20)) {
              return false;
            } else if (this.filters.experience === 'More than 20 years' && years <= 20) {
              return false;
            }
          }
        }
        
        
        if (this.filters.status && teacher.status !== this.filters.status) {
          return false;
        }
        
        return true;
      });
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    
    
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang
      })
    }
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  methods: {
    updateLanguage(event) {
      this.language = event.detail.language;
    },
  },
}
</script> 
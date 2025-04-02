<template>
  <div>
    <Head :title="language === 'uk' ? 'Зведена статистика' : 'Summary Statistics'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Зведена статистика' : 'Summary Statistics' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Зведена статистика' : 'Summary Statistics' }}
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
    <div class="grid grid-cols-1 gap-6">
      <!-- Period selection -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ language === 'uk' ? 'Оберіть період' : 'Select Period' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Від' : 'From' }}</label>
            <input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'До' : 'To' }}</label>
            <input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
          </div>
          <div class="flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full">
              {{ language === 'uk' ? 'Оновити' : 'Update' }}
            </button>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex flex-wrap gap-2">
            <button class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">
              {{ language === 'uk' ? 'Поточний місяць' : 'Current Month' }}
            </button>
            <button class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">
              {{ language === 'uk' ? 'Поточний квартал' : 'Current Quarter' }}
            </button>
            <button class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">
              {{ language === 'uk' ? 'Поточний рік' : 'Current Year' }}
            </button>
            <button class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">
              {{ language === 'uk' ? 'Минулий місяць' : 'Previous Month' }}
            </button>
            <button class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">
              {{ language === 'uk' ? 'Минулий квартал' : 'Previous Quarter' }}
            </button>
            <button class="px-3 py-1 text-sm bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-md">
              {{ language === 'uk' ? 'Весь навчальний рік' : 'Entire School Year' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Key metrics -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="ml-4">
              <h2 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Всього подій' : 'Total Events' }}</h2>
              <p class="text-3xl font-bold text-gray-900">76</p>
            </div>
          </div>
          <div class="mt-4">
            <div class="flex items-center text-sm">
              <span class="text-green-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                <span class="ml-1">+12%</span>
              </span>
              <span class="ml-2 text-gray-600">{{ language === 'uk' ? 'порівняно з минулим роком' : 'vs previous year' }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <h2 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Середня відвідуваність' : 'Average Attendance' }}</h2>
              <p class="text-3xl font-bold text-gray-900">82%</p>
            </div>
          </div>
          <div class="mt-4">
            <div class="flex items-center text-sm">
              <span class="text-green-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                <span class="ml-1">+3.5%</span>
              </span>
              <span class="ml-2 text-gray-600">{{ language === 'uk' ? 'порівняно з минулим роком' : 'vs previous year' }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <h2 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Всього годин' : 'Total Hours' }}</h2>
              <p class="text-3xl font-bold text-gray-900">214</p>
            </div>
          </div>
          <div class="mt-4">
            <div class="flex items-center text-sm">
              <span class="text-red-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
                <span class="ml-1">-2.3%</span>
              </span>
              <span class="ml-2 text-gray-600">{{ language === 'uk' ? 'порівняно з минулим роком' : 'vs previous year' }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <h2 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Всього учасників' : 'Total Participants' }}</h2>
              <p class="text-3xl font-bold text-gray-900">3,845</p>
            </div>
          </div>
          <div class="mt-4">
            <div class="flex items-center text-sm">
              <span class="text-green-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                <span class="ml-1">+18.2%</span>
              </span>
              <span class="ml-2 text-gray-600">{{ language === 'uk' ? 'порівняно з минулим роком' : 'vs previous year' }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Event type breakdown -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ language === 'uk' ? 'Розподіл за типами подій' : 'Event Type Breakdown' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
              <!-- Placeholder for a chart - In a real app, this would be a chart component -->
              <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                  </svg>
                </div>
                <p class="text-gray-500 mb-2">{{ language === 'uk' ? 'Тут буде діаграма' : 'Chart will be displayed here' }}</p>
                <p class="text-xs text-gray-400">{{ language === 'uk' ? 'Кругова діаграма типів подій' : 'Pie chart of event types' }}</p>
              </div>
            </div>
          </div>
          <div>
            <ul class="divide-y divide-gray-200">
              <li class="py-3 flex justify-between items-center">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                  <span class="text-gray-700">{{ language === 'uk' ? 'Концерти' : 'Concerts' }}</span>
                </div>
                <div class="flex items-center">
                  <span class="font-semibold">22%</span>
                  <span class="ml-2 text-gray-500 text-sm">(17)</span>
                </div>
              </li>
              <li class="py-3 flex justify-between items-center">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                  <span class="text-gray-700">{{ language === 'uk' ? 'Батьківські збори' : 'Parent Meetings' }}</span>
                </div>
                <div class="flex items-center">
                  <span class="font-semibold">28%</span>
                  <span class="ml-2 text-gray-500 text-sm">(21)</span>
                </div>
              </li>
              <li class="py-3 flex justify-between items-center">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                  <span class="text-gray-700">{{ language === 'uk' ? 'Олімпіади' : 'Olympiads' }}</span>
                </div>
                <div class="flex items-center">
                  <span class="font-semibold">15%</span>
                  <span class="ml-2 text-gray-500 text-sm">(11)</span>
                </div>
              </li>
              <li class="py-3 flex justify-between items-center">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                  <span class="text-gray-700">{{ language === 'uk' ? 'Екскурсії' : 'Excursions' }}</span>
                </div>
                <div class="flex items-center">
                  <span class="font-semibold">18%</span>
                  <span class="ml-2 text-gray-500 text-sm">(14)</span>
                </div>
              </li>
              <li class="py-3 flex justify-between items-center">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                  <span class="text-gray-700">{{ language === 'uk' ? 'Спортивні заходи' : 'Sports Events' }}</span>
                </div>
                <div class="flex items-center">
                  <span class="font-semibold">17%</span>
                  <span class="ml-2 text-gray-500 text-sm">(13)</span>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Monthly trends -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ language === 'uk' ? 'Щомісячна динаміка' : 'Monthly Trends' }}</h2>
        <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center mb-4">
          <!-- Placeholder for a chart - In a real app, this would be a chart component -->
          <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
              </svg>
            </div>
            <p class="text-gray-500 mb-2">{{ language === 'uk' ? 'Тут буде діаграма' : 'Chart will be displayed here' }}</p>
            <p class="text-xs text-gray-400">{{ language === 'uk' ? 'Графік відвідуваності по місяцях' : 'Monthly attendance chart' }}</p>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div class="bg-blue-50 p-4 rounded-lg">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-medium text-blue-800">{{ language === 'uk' ? 'Найвища відвідуваність' : 'Highest Attendance' }}</h3>
              <span class="text-blue-600 font-semibold">94%</span>
            </div>
            <p class="text-sm text-blue-600 mt-1">{{ language === 'uk' ? 'Грудень 2023' : 'December 2023' }}</p>
          </div>
          <div class="bg-red-50 p-4 rounded-lg">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-medium text-red-800">{{ language === 'uk' ? 'Найнижча відвідуваність' : 'Lowest Attendance' }}</h3>
              <span class="text-red-600 font-semibold">68%</span>
            </div>
            <p class="text-sm text-red-600 mt-1">{{ language === 'uk' ? 'Липень 2023' : 'July 2023' }}</p>
          </div>
          <div class="bg-green-50 p-4 rounded-lg">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-medium text-green-800">{{ language === 'uk' ? 'Найбільше подій' : 'Most Events' }}</h3>
              <span class="text-green-600 font-semibold">12</span>
            </div>
            <p class="text-sm text-green-600 mt-1">{{ language === 'uk' ? 'Травень 2023' : 'May 2023' }}</p>
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
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    
    // Also listen for language changes using the event bus
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
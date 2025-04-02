<template>
  <div>
    <Head :title="language === 'uk' ? 'Відвідуваність подій' : 'Event Attendance'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Відвідуваність подій' : 'Event Attendance' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Відвідуваність подій' : 'Event Attendance' }}
        </h1>
      </div>
      <div class="flex items-center space-x-4">
        <Link 
          href="/reports/students/attendance" 
          class="px-4 py-2 rounded-lg border border-blue-500 text-blue-600 bg-white hover:bg-blue-50 flex items-center transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          {{ language === 'uk' ? 'Відвідуваність студентів' : 'Student Attendance' }}
        </Link>
        <Link 
          href="/" 
          class="px-4 py-2 rounded-lg border border-blue-500 text-blue-600 bg-white hover:bg-blue-50 flex items-center transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          {{ language === 'uk' ? 'Перейти до Календаря' : 'Go to Calendar' }}
        </Link>
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
      <!-- Filters Card -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-3">{{ language === 'uk' ? 'Фільтри' : 'Filters' }}</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Тип події' : 'Event Type' }}</label>
              <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">{{ language === 'uk' ? 'Всі типи' : 'All types' }}</option>
                <option>{{ language === 'uk' ? 'Концерт' : 'Concert' }}</option>
                <option>{{ language === 'uk' ? 'Батьківські збори' : 'Parent meeting' }}</option>
                <option>{{ language === 'uk' ? 'Олімпіада' : 'Olympiad' }}</option>
                <option>{{ language === 'uk' ? 'Екскурсія' : 'Excursion' }}</option>
                <option>{{ language === 'uk' ? 'Спортивний захід' : 'Sports event' }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Період' : 'Period' }}</label>
              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-xs text-gray-500 mb-1">{{ language === 'uk' ? 'Від' : 'From' }}</label>
                  <input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>
                <div>
                  <label class="block text-xs text-gray-500 mb-1">{{ language === 'uk' ? 'До' : 'To' }}</label>
                  <input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                </div>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Статус' : 'Status' }}</label>
              <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">{{ language === 'uk' ? 'Всі статуси' : 'All statuses' }}</option>
                <option>{{ language === 'uk' ? 'Заплановано' : 'Planned' }}</option>
                <option>{{ language === 'uk' ? 'Завершено' : 'Completed' }}</option>
                <option>{{ language === 'uk' ? 'Скасовано' : 'Cancelled' }}</option>
              </select>
            </div>
          </div>
          <div class="mt-4 flex justify-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
              {{ language === 'uk' ? 'Застосувати фільтри' : 'Apply Filters' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-all duration-300">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Всього подій' : 'Total Events' }}</h3>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.totalEvents }}</p>
            </div>
          </div>
          <div class="mt-4 flex items-center text-sm">
            <span class="text-green-500 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
              </svg>
              <span class="ml-1">+24%</span>
            </span>
            <span class="ml-2 text-gray-600">{{ language === 'uk' ? 'порівняно з минулим роком' : 'vs previous year' }}</span>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-all duration-300">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Середня відвідуваність' : 'Average Attendance' }}</h3>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.averageAttendance }}%</p>
            </div>
          </div>
          <div class="mt-4 flex items-center text-sm">
            <span class="text-green-500 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
              </svg>
              <span class="ml-1">+5.2%</span>
            </span>
            <span class="ml-2 text-gray-600">{{ language === 'uk' ? 'порівняно з минулим роком' : 'vs previous year' }}</span>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-all duration-300">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Найвища відвідуваність' : 'Highest Attendance' }}</h3>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.highestAttendance ? statistics.highestAttendance.percentage : '0' }}%</p>
            </div>
          </div>
          <div class="mt-4 text-sm text-gray-600">
            {{ statistics.highestAttendance ? statistics.highestAttendance.event : (language === 'uk' ? 'Немає даних' : 'No data') }}
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-all duration-300">
          <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Найнижча відвідуваність' : 'Lowest Attendance' }}</h3>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.lowestAttendance ? statistics.lowestAttendance.percentage : '0' }}%</p>
            </div>
          </div>
          <div class="mt-4 text-sm text-gray-600">
            {{ statistics.lowestAttendance ? statistics.lowestAttendance.event : (language === 'uk' ? 'Немає даних' : 'No data') }}
          </div>
        </div>
      </div>

      <!-- Attendance Chart -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ language === 'uk' ? 'Динаміка відвідуваності' : 'Attendance Trends' }}</h2>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
          <!-- This would be a chart component in a real application -->
          <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
              </svg>
            </div>
            <p class="text-gray-500 mb-2">{{ language === 'uk' ? 'Тут буде діаграма відвідуваності' : 'Attendance chart will be displayed here' }}</p>
          </div>
        </div>
      </div>

      <!-- Data table -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ language === 'uk' ? 'Деталі відвідуваності подій' : 'Event Attendance Details' }}</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ language === 'uk' ? 'Назва події' : 'Event Name' }}
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ language === 'uk' ? 'Дата' : 'Date' }}
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ language === 'uk' ? 'Тип' : 'Type' }}
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ language === 'uk' ? 'Відвідуваність' : 'Attendance' }}
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
                <!-- Example rows - in a real application these would be loaded from the backend -->
                <tr v-for="(event, index) in events" :key="index" class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="ml-0">
                        <div class="text-sm font-medium text-gray-900">
                          {{ event.name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ new Date(event.date).toLocaleDateString() }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      {{ event.type }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div :class="{
                          'bg-red-500': event.attendancePercentage < 60,
                          'bg-yellow-500': event.attendancePercentage >= 60 && event.attendancePercentage < 80,
                          'bg-green-500': event.attendancePercentage >= 80
                        }" class="h-2.5 rounded-full" :style="{ width: event.attendancePercentage + '%' }"></div>
                      </div>
                      <span class="ml-2 text-sm text-gray-900 font-medium">{{ event.attendancePercentage }}%</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="{
                        'bg-blue-100 text-blue-800': event.status === 'Planned',
                        'bg-green-100 text-green-800': event.status === 'Completed',
                        'bg-red-100 text-red-800': event.status === 'Cancelled'
                      }">
                      {{ event.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3 transition-colors duration-150">{{ language === 'uk' ? 'Деталі' : 'Details' }}</a>
                    <a href="#" class="text-blue-600 hover:text-blue-900 transition-colors duration-150">{{ language === 'uk' ? 'Звіт' : 'Report' }}</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 mt-4">
            <div class="flex-1 flex justify-between sm:hidden">
              <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150">
                {{ language === 'uk' ? 'Попередня' : 'Previous' }}
              </a>
              <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150">
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
                  <span class="font-medium">42</span>
                  {{ language === 'uk' ? 'результатів' : 'results' }}
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-150">
                    <span class="sr-only">{{ language === 'uk' ? 'Попередня' : 'Previous' }}</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </a>
                  <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    1
                  </a>
                  <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-150">
                    2
                  </a>
                  <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-150">
                    3
                  </a>
                  <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                    ...
                  </span>
                  <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-150">
                    5
                  </a>
                  <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-150">
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
      events: [],
      statistics: {
        totalEvents: 0,
        averageAttendance: 0,
        highestAttendance: null,
        lowestAttendance: null
      }
    }
  },
  created() {
    // Load real data from props
    if (this.$page.props.initialEventData) {
      this.events = this.$page.props.initialEventData;
    }
    
    if (this.$page.props.statistics) {
      this.statistics = this.$page.props.statistics;
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
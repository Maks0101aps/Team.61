<template>
  <div class="calendar-container">
    <!-- Calendar Header - Redesigned for better responsive behavior -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <!-- Month name and view mode buttons -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <h2 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-300 dark:to-blue-500 bg-clip-text text-transparent">
          {{ currentMonthName }}
        </h2>
        <div class="flex flex-wrap gap-1">
          <button v-for="mode in viewModes" 
                  :key="mode.value"
                  @click="switchView(mode.value)"
                  class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-md text-xs sm:text-sm font-medium transition-all duration-200"
                  :class="currentView === mode.value 
                    ? 'bg-gradient-to-r from-blue-500 to-blue-700 dark:from-blue-400 dark:to-blue-600 text-white shadow-md' 
                    : 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 shadow-sm'">
            <!-- Show icons on small screens -->
            <span class="hidden sm:inline">{{ mode.label }}</span>
            <!-- Icons for small screens -->
            <span class="sm:hidden">
              <svg v-if="mode.value === 'month'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V4zm2 0v14h6V4H7z" />
              </svg>
              <svg v-else-if="mode.value === 'week'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
              <svg v-else-if="mode.value === 'day'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
              </svg>
              <svg v-else-if="mode.value === 'year'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 4a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1V5a1 1 0 00-1-1H5z" />
              </svg>
            </span>
          </button>
        </div>
      </div>
      
      <!-- Navigation and action buttons -->
      <div class="flex flex-wrap items-center gap-1 sm:gap-2">
        <!-- Previous/Next buttons with icons -->
        <button @click="previousPeriod" 
                class="inline-flex items-center justify-center p-1.5 sm:px-2 sm:py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-200 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline ml-1">{{ language === 'uk' ? 'Попередній' : 'Previous' }}</span>
        </button>
        
        <button @click="nextPeriod" 
                class="inline-flex items-center justify-center p-1.5 sm:px-2 sm:py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-200 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline ml-1">{{ language === 'uk' ? 'Наступний' : 'Next' }}</span>
        </button>
        
        <!-- Create Event button -->
        <Link href="/events/create"
              class="inline-flex items-center justify-center p-1.5 sm:px-3 sm:py-1.5 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-md text-xs sm:text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg dark:shadow-blue-500/20 dark:from-blue-400 dark:to-blue-600"
              @click.prevent="checkEventAccess">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline ml-1">{{ language === 'uk' ? 'Створити' : 'Create' }}</span>
        </Link>
        
        <!-- Sync Settings button -->
        <Link href="/calendar/settings"
              class="inline-flex items-center justify-center p-1.5 sm:px-3 sm:py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-200 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline ml-1">{{ language === 'uk' ? 'Синхронізація' : 'Sync' }}</span>
        </Link>
      </div>
    </div>

    <!-- Monthly View -->
    <div v-if="currentView === 'month'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-7 gap-px bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800">
        <!-- Weekday headers -->
        <div v-for="day in weekDays" :key="day" 
             class="bg-gradient-to-b from-blue-50 to-white py-3 text-center text-sm font-bold text-blue-900 dark:text-blue-200">
          {{ day }}
        </div>

        <!-- Calendar days -->
        <div v-for="day in calendarDays" :key="day.date" 
             class="bg-white min-h-[120px] p-3 relative transition-all duration-200 hover:bg-blue-50 group"
             :class="{
               'bg-gray-50': !day.isCurrentMonth,
               'bg-red-50': hasConflicts(day.date)
             }">
          <div class="flex justify-between items-start">
            <div class="text-sm font-bold mb-2" 
                 :class="{ 'text-gray-400': !day.isCurrentMonth, 'text-blue-900 dark:text-blue-200': day.isCurrentMonth }">
              {{ day.dayNumber }}
            </div>
            <!-- Add Event Button -->
            <button @click.stop="checkEventAccess(day.date)"
                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 p-1 rounded-md bg-blue-500 hover:bg-blue-600 text-white"
                    :title="language === 'uk' ? 'Створити подію' : 'Create Event'">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
          
          <!-- Events for the day -->
          <div class="space-y-1.5">
            <div v-for="event in getEventsForDay(day.date)" :key="event.id"
                 class="text-xs p-2 rounded-lg cursor-pointer transition-all duration-200 transform hover:scale-105 shadow-sm"
                 :class="getEventClasses(event)"
                 @click.stop="showEventDetails(event)">
              {{ event.title }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Weekly View -->
    <div v-else-if="currentView === 'week'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-7 gap-px bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800">
        <!-- Weekday headers -->
        <div v-for="day in weekDays" :key="day" 
             class="bg-gradient-to-b from-blue-50 to-white py-3 text-center text-sm font-bold text-blue-900 dark:text-blue-200">
          {{ day }}
        </div>

        <!-- Week days -->
        <div v-for="day in weekDays" :key="day" 
             class="bg-white min-h-[200px] p-3 transition-all duration-200 hover:bg-blue-50">
          <div class="text-sm font-bold mb-2 text-blue-900 dark:text-blue-200">
            {{ getWeekDayDate(day) }}
          </div>
          <div class="space-y-1.5">
            <div v-for="event in getEventsForWeekDay(day)" :key="event.id"
                 class="text-xs p-2 rounded-lg cursor-pointer transition-all duration-200 transform hover:scale-105 shadow-sm"
                 :class="getEventClasses(event)"
                 @click="showEventDetails(event)">
              {{ event.title }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Daily View -->
    <div v-else-if="currentView === 'day'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-1 gap-px bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800">
        <div class="bg-white dark:bg-gray-800 p-6">
          <div class="text-xl font-bold mb-6 text-blue-900 dark:text-blue-200">
            {{ formatFullDate(currentDate) }}
          </div>
          <div class="space-y-4">
            <div v-for="event in getEventsForDay(currentDate.format('YYYY-MM-DD'))" :key="event.id"
                 class="p-4 rounded-lg cursor-pointer transition-all duration-200 transform hover:scale-105 shadow-sm"
                 :class="getEventClasses(event)"
                 @click="showEventDetails(event)">
              <div class="font-bold">{{ event.title }}</div>
              <div class="text-sm mt-2">{{ formatDateTime(event.start_date) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Annual View -->
    <div v-else-if="currentView === 'year'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 sm:p-6">
        <div v-for="month in 12" :key="month" 
             class="month-card border rounded-lg p-2 sm:p-3 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 dark:border-gray-700">
          <div class="text-sm font-medium mb-2 text-blue-900 dark:text-blue-300 cursor-pointer hover:text-blue-600 hover:underline text-center"
               @click="switchToMonth(month - 1)">
            {{ getMonthName(month - 1) }}
          </div>
          <!-- Add weekday headers for better orientation -->
          <div class="year-month-headers">
            <div v-for="(day, index) in weekDays" :key="index" class="text-xs text-gray-500 dark:text-gray-300">
              {{ day.charAt(0) }}
            </div>
          </div>
          <div class="year-month-grid">
            <button v-for="day in getMonthDays(month - 1)" :key="day.date"
                 class="year-day-cell"
                 :class="{
                   'year-day-inactive': !day.isCurrentMonth,
                   'year-day-active': day.isCurrentMonth,
                   'year-day-with-events': getEventsForDay(day.date).length > 0
                 }"
                 :disabled="!day.isCurrentMonth"
                 @click="day.isCurrentMonth && goToDay(day.date)">
              {{ day.dayNumber }}
              
              <!-- Event indicator dot -->
              <div v-if="getEventsForDay(day.date).length > 0" 
                   class="year-day-indicator"></div>
              
              <!-- Event tooltip on hover -->
              <div v-if="getEventsForDay(day.date).length > 0 && day.isCurrentMonth" 
                   class="year-day-tooltip">
                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-1 mb-2">
                  {{ formatShortDate(day.date) }}
                </p>
                <div v-for="event in getEventsForDay(day.date).slice(0, 3)" :key="event.id" 
                     class="mb-1.5 last:mb-0 text-left">
                  <span class="block truncate text-xs font-medium text-gray-900 dark:text-gray-200">{{ event.title }}</span>
                  <span class="block text-xs text-gray-500 dark:text-gray-400">{{ formatTime(event.start_date) }}</span>
                </div>
                <div v-if="getEventsForDay(day.date).length > 3" class="text-blue-600 dark:text-blue-400 text-right mt-1 text-xs">
                  {{ language === 'uk' ? 'Ще ' : '+ ' }}{{ getEventsForDay(day.date).length - 3 }}
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Fallback View (during refresh) -->
    <div v-else class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden min-h-[400px] flex items-center justify-center">
      <div class="text-blue-500">
        <svg class="animate-spin h-10 w-10 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-3 text-center text-sm">{{ language === 'uk' ? 'Оновлення календаря...' : 'Updating calendar...' }}</p>
      </div>
    </div>

    <!-- Event Details Modal -->
    <div v-if="selectedEvent" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 backdrop-blur-sm">
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full p-6 shadow-2xl transform transition-all duration-300">
        <div class="flex justify-between items-start mb-6">
          <h3 class="text-xl font-bold text-blue-900 dark:text-blue-200">{{ selectedEvent.title }}</h3>
          <button @click="selectedEvent = null" 
                  class="text-gray-400 hover:text-gray-500 transition-colors duration-200">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-6">
          <div>
            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ language === 'uk' ? 'Час' : 'Time' }}</h4>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">
              {{ formatDateTime(selectedEvent.start_date) }}
            </p>
          </div>
          
          <div>
            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ language === 'uk' ? 'Тип' : 'Type' }}</h4>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ selectedEvent.type }}</p>
          </div>
          
          <div v-if="selectedEvent.location">
            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ language === 'uk' ? 'Місце' : 'Location' }}</h4>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ selectedEvent.location }}</p>
          </div>
          
          <div v-if="selectedEvent.online_link">
            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ language === 'uk' ? 'Онлайн посилання' : 'Online Link' }}</h4>
            <a :href="selectedEvent.online_link" 
               target="_blank" 
               class="mt-1 text-sm text-blue-600 hover:text-blue-900 dark:text-blue-300 dark:hover:text-blue-200 transition-colors duration-200">
              {{ selectedEvent.online_link }}
            </a>
          </div>

          <!-- Display attachments if available -->
          <div v-if="selectedEvent.attachments && selectedEvent.attachments.length > 0">
            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ language === 'uk' ? 'Файли' : 'Attachments' }}</h4>
            <ul class="mt-2 space-y-2">
              <li v-for="attachment in selectedEvent.attachments" :key="attachment.id" 
                  class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-300 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span class="text-sm text-gray-700 dark:text-gray-200 truncate">{{ attachment.original_filename }}</span>
                </div>
                <a :href="attachment.download_url" 
                   class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-200" 
                   download>
                  {{ language === 'uk' ? 'Завантажити' : 'Download' }}
                </a>
              </li>
            </ul>
          </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-3">
          <!-- Add delete button that only appears for events created by the current user -->
          <button v-if="canDeleteEvent(selectedEvent)" 
                  @click="deleteEvent(selectedEvent)"
                  class="px-4 py-2 bg-red-500 text-white rounded-lg shadow-sm text-sm font-medium hover:bg-red-600 transition-all duration-200">
            {{ language === 'uk' ? 'Видалити' : 'Delete' }}
          </button>
          <a :href="`/events/${selectedEvent.id}`" 
             class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-sm text-sm font-medium hover:bg-blue-600 transition-all duration-200">
            {{ language === 'uk' ? 'Детальніше' : 'View Details' }}
          </a>
          <button @click="selectedEvent = null" 
                  class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-200">
            {{ language === 'uk' ? 'Закрити' : 'Close' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue'
import dayjs from 'dayjs'
import { Link } from '@inertiajs/vue3'
import 'dayjs/locale/uk'
import 'dayjs/locale/en'
import axios from 'axios'

export default {
  components: {
    Link
  },
  props: {
    events: {
      type: Array,
      required: true
    },
    language: {
      type: String,
      default: 'uk'
    }
  },
  setup(props) {
    
    const currentDate = ref(dayjs())
    const selectedEvent = ref(null)
    const currentView = ref('month')
    
    // Слежение за изменениями языка
    watch(() => props.language, (newLanguage) => {
      // Сначала установим новую локаль
      if (newLanguage === 'uk') {
        dayjs.locale('uk')
      } else {
        dayjs.locale('en')
      }
      
      // Затем создаём новый объект для текущей даты с применённой локалью
      currentDate.value = dayjs(currentDate.value.toDate())
    }, { immediate: true })

    const viewModes = computed(() => [
      { value: 'month', label: props.language === 'uk' ? 'Місяць' : 'Month' },
      { value: 'week', label: props.language === 'uk' ? 'Тиждень' : 'Week' },
      { value: 'day', label: props.language === 'uk' ? 'День' : 'Day' },
      { value: 'year', label: props.language === 'uk' ? 'Рік' : 'Year' }
    ])

    const currentMonthName = computed(() => {
      // Получаем месяц и год в текущей локали
      const localizedMonth = currentDate.value.format('MMMM')
      const year = currentDate.value.format('YYYY')
      
      // Для украинского языка делаем первую букву месяца заглавной
      if (props.language === 'uk') {
        return `${localizedMonth.charAt(0).toUpperCase()}${localizedMonth.slice(1)} ${year}`
      }
      
      // Для английского возвращаем как есть
      return `${localizedMonth} ${year}`
    })

    const weekDays = computed(() => {
      return props.language === 'uk' 
        ? ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд']
        : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    })

    const calendarDays = computed(() => {
      const start = currentDate.value.startOf('month').startOf('week')
      const end = currentDate.value.endOf('month').endOf('week')
      const days = []
      
      let day = start
      while (day.isBefore(end)) {
        days.push({
          date: day.format('YYYY-MM-DD'),
          dayNumber: day.date(),
          isCurrentMonth: day.month() === currentDate.value.month()
        })
        day = day.add(1, 'day')
      }
      
      return days
    })

    const getEventsForDay = (date) => {
      return props.events.filter(event => {
        const eventDate = dayjs(event.start_date).format('YYYY-MM-DD')
        return eventDate === date
      })
    }

    const getEventsForWeekDay = (weekDay) => {
      const dayIndex = weekDays.value.indexOf(weekDay)
      const date = currentDate.value.startOf('week').add(dayIndex, 'day')
      return getEventsForDay(date.format('YYYY-MM-DD'))
    }

    const getWeekDayDate = (weekDay) => {
      // Получаем индекс дня недели и соответствующую дату
      const dayIndex = weekDays.value.indexOf(weekDay)
      const date = currentDate.value.startOf('week').add(dayIndex, 'day')
      
      // Форматируем дату и месяц в текущей локали
      const formatted = date.format('DD MMMM')
      
      // Для украинского языка делаем первую букву месяца заглавной
      if (props.language === 'uk') {
        const [day, month] = formatted.split(' ')
        return `${day} ${month.charAt(0).toUpperCase()}${month.slice(1)}`
      }
      
      // Для английского возвращаем как есть
      return formatted
    }

    const getMonthName = (monthIndex) => {
      // Создаем дату для указанного месяца и получаем его название в текущей локали
      const date = dayjs().month(monthIndex).date(1)
      const localizedMonth = date.format('MMMM')
      
      // Для украинского языка делаем первую букву заглавной
      if (props.language === 'uk') {
        return `${localizedMonth.charAt(0).toUpperCase()}${localizedMonth.slice(1)}`
      }
      
      // Для английского возвращаем как есть
      return localizedMonth
    }

    const getMonthDays = (monthIndex) => {
      const month = currentDate.value.month(monthIndex)
      const start = month.startOf('month').startOf('week')
      const end = month.endOf('month').endOf('week')
      const days = []
      
      let day = start
      while (day.isBefore(end)) {
        days.push({
          date: day.format('YYYY-MM-DD'),
          dayNumber: day.date(),
          isCurrentMonth: day.month() === monthIndex
        })
        day = day.add(1, 'day')
      }
      
      return days
    }

    const hasConflicts = (date) => {
      const dayEvents = getEventsForDay(date)
      if (dayEvents.length <= 1) return false
      
      for (let i = 0; i < dayEvents.length; i++) {
        for (let j = i + 1; j < dayEvents.length; j++) {
          const event1 = dayEvents[i]
          const event2 = dayEvents[j]
          const start1 = dayjs(event1.start_date)
          const end1 = start1.add(event1.duration, 'minute')
          const start2 = dayjs(event2.start_date)
          const end2 = start2.add(event2.duration, 'minute')
          
          if (start1.isBefore(end2) && start2.isBefore(end1)) {
            return true
          }
        }
      }
      return false
    }

    const getEventClasses = (event) => {
      const baseClasses = 'block truncate font-medium'
      const typeClasses = {
        exam: 'bg-red-200 text-red-800',
        test: 'bg-yellow-200 text-yellow-800',
        school_event: 'bg-green-200 text-green-800',
        parent_meeting: 'bg-blue-200 text-blue-800',
        personal: 'bg-purple-200 text-purple-800'
      }
      return `${baseClasses} ${typeClasses[event.type] || 'bg-gray-200 text-gray-800'}`
    }

    const showEventDetails = (event) => {
      
      selectedEvent.value = event
      
      
      axios.get(`/api/events/${event.id}`)
        .then(response => {
          
          if (response.data && response.data.event) {
            selectedEvent.value = response.data.event
          }
        })
        .catch(error => {
          console.error('Error fetching event details:', error)
        })
    }

    const formatDateTime = (date) => {
      
      return dayjs(date).format('DD.MM.YYYY HH:mm')
    }

    
    const canDeleteEvent = (event) => {
      
      if (!event) return false
      
      
      
      
      return true
    }
    
    
    const deleteEvent = (event) => {
      if (!confirm(props.language === 'uk' ? 'Ви впевнені, що хочете видалити цю подію?' : 'Are you sure you want to delete this event?')) {
        return;
      }
      
      
      const eventId = event.id;
      
      
      selectedEvent.value = null;
      
      
      axios.delete(`/events/${eventId}`, {
        headers: {
          'X-Inertia': 'false'
        }
      })
        .then(response => {
          
          const successMsg = props.language === 'uk' ? 'Подію видалено' : 'Event deleted';
          const flashEvent = new CustomEvent('inertia:flash', {
            detail: {
              type: 'success',
              message: successMsg
            }
          });
          window.dispatchEvent(flashEvent);
          
          
          const eventIndex = props.events.findIndex(e => e.id === eventId);
          if (eventIndex !== -1) {
            props.events.splice(eventIndex, 1);
          }
        })
        .catch(error => {
          
          let errorMsg = props.language === 'uk' ? 'Помилка при видаленні події' : 'Error deleting event';
          
          if (error.response && error.response.data) {
            if (error.response.data.message) {
              errorMsg = error.response.data.message;
            } else if (error.response.data.error) {
              errorMsg = error.response.data.error;
            }
          }
          
          
          const errorEvent = new CustomEvent('inertia:flash', {
            detail: {
              type: 'error',
              message: errorMsg
            }
          });
          window.dispatchEvent(errorEvent);
        });
    }

    const previousPeriod = () => {
      // Обрабатываем разные режимы просмотра
      if (currentView.value === 'year') {
        currentDate.value = dayjs(currentDate.value.toDate()).subtract(1, 'year')
      } else if (currentView.value === 'month') {
        currentDate.value = dayjs(currentDate.value.toDate()).subtract(1, 'month')
      } else if (currentView.value === 'week') {
        currentDate.value = dayjs(currentDate.value.toDate()).subtract(1, 'week')
      } else if (currentView.value === 'day') {
        currentDate.value = dayjs(currentDate.value.toDate()).subtract(1, 'day')
      }
    }

    const nextPeriod = () => {
      // Обрабатываем разные режимы просмотра
      if (currentView.value === 'year') {
        currentDate.value = dayjs(currentDate.value.toDate()).add(1, 'year')
      } else if (currentView.value === 'month') {
        currentDate.value = dayjs(currentDate.value.toDate()).add(1, 'month')
      } else if (currentView.value === 'week') {
        currentDate.value = dayjs(currentDate.value.toDate()).add(1, 'week')
      } else if (currentView.value === 'day') {
        currentDate.value = dayjs(currentDate.value.toDate()).add(1, 'day')
      }
    }

    const createEventOnDate = (date) => {
      window.location.href = `/events/create?date=${date}`
    }
    
    const checkEventAccess = (date) => {
      
      const selectedDate = date ? date : currentDate.value.format('YYYY-MM-DD')

      
      axios.get('/events/create-permissions', { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => {
        
        window.location.href = `/events/create?date=${selectedDate}`
      })
      .catch(error => {
        
        if (error.response && error.response.status === 403) {
          
          alert(error.response.data?.message || 
               (props.language === 'uk' ? 'Ви не маєте права створювати події' : 'You do not have permission to create events'))
        } else {
          
          alert(props.language === 'uk' ? 'Виникла помилка' : 'An error occurred')
        }
      })
    }

    const formatFullDate = (date) => {
      // Форматируем полную дату в текущей локали
      const formatted = date.format('DD MMMM YYYY')
      
      // Для украинского языка делаем первую букву месяца заглавной
      if (props.language === 'uk') {
        const parts = formatted.split(' ')
        if (parts.length === 3) {
          const [day, month, year] = parts
          return `${day} ${month.charAt(0).toUpperCase()}${month.slice(1)} ${year}`
        }
      }
      
      // Для английского возвращаем как есть
      return formatted
    }

    
    const switchView = (viewMode) => {
      
      currentView.value = viewMode;
    }

    
    const switchToMonth = (monthIndex) => {
      // Создаем новую дату с правильной локалью
      currentDate.value = dayjs(currentDate.value.toDate()).month(monthIndex);
      
      // Переключаемся на просмотр месяца
      currentView.value = 'month';
    }

    
    const goToDay = (date) => {
      axios.get('/events/create-permissions', { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => {
        window.location.href = `/events/create?date=${date}`
      })
      .catch(error => {
        // Создаем новую дату с правильной локалью
        currentDate.value = dayjs(date).locale(props.language);
        currentView.value = 'day';
      });
    }

    
    const language = computed(() => props.language)

    
    const formatShortDate = (date) => {
      // Форматируем короткую дату в текущей локали
      const formatted = dayjs(date).format('DD MMM');
      
      // Для украинского языка делаем первую букву месяца заглавной
      if (props.language === 'uk') {
        const [day, month] = formatted.split(' ')
        return `${day} ${month.charAt(0).toUpperCase()}${month.slice(1)}`;
      }
      
      // Для английского возвращаем как есть
      return formatted;
    }

    const formatTime = (datetime) => {
      return dayjs(datetime).format('HH:mm');
    }

    return {
      currentDate,
      selectedEvent,
      currentView,
      viewModes,
      currentMonthName,
      weekDays,
      calendarDays,
      getEventsForDay,
      getEventsForWeekDay,
      getWeekDayDate,
      getMonthName,
      getMonthDays,
      hasConflicts,
      getEventClasses,
      showEventDetails,
      formatDateTime,
      previousPeriod,
      nextPeriod,
      createEventOnDate,
      checkEventAccess,
      canDeleteEvent,
      deleteEvent,
      formatFullDate,
      switchView,
      switchToMonth,
      goToDay,
      language,
      formatShortDate,
      formatTime
    }
  }
}
</script>

<style scoped>
.calendar-container {
  @apply bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6;
}


@media (max-width: 640px) {
  .calendar-container {
    @apply p-3;
  }
}


.fixed {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}


.month-card {
  display: flex;
  flex-direction: column;
}

.year-month-headers {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  text-align: center;
  margin-bottom: 4px;
}

.year-month-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  grid-auto-rows: 1fr;
  gap: 0;
  margin: 0 auto;
  max-width: 100%;
}

.year-day-cell {
  position: relative;
  width: 100%;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: normal;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  padding: 0;
  line-height: 1;
  border-radius: 4px;
  user-select: none;
  margin: 1px;
  border: none;
  background: transparent;
  cursor: pointer;
  overflow: visible;
}

.year-day-active {
  color: #1e40af;
  font-weight: 500;
}

/* Update for dark mode */
:root.dark .year-day-active {
  color: #93c5fd;
  font-weight: 600;
}

.year-day-inactive {
  color: #9ca3af;
  cursor: default;
  pointer-events: none;
}

.year-day-with-events {
  background-color: #eff6ff;
  font-weight: 600;
}

:root.dark .year-day-with-events {
  background-color: rgba(59, 130, 246, 0.2); /* blue-500 with opacity */
  font-weight: 600;
}

.year-day-cell:hover:not(:disabled) {
  background-color: #dbeafe;
  transform: scale(1.15);
  z-index: 10;
  box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
}

.year-day-indicator {
  position: absolute;
  bottom: 1px;
  left: 50%;
  transform: translateX(-50%);
  height: 3px;
  width: 3px;
  border-radius: 50%;
  background-color: #3b82f6;
}

.year-day-tooltip {
  position: absolute;
  visibility: hidden;
  z-index: 20;
  background-color: white;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 8px;
  min-width: 180px;
  max-width: 220px;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  bottom: calc(100% + 10px);
  left: 50%;
  transform: translateX(-50%);
  font-size: 12px;
  opacity: 0;
  transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
  pointer-events: none;
}

:root.dark .year-day-tooltip {
  background-color: #1f2937; /* gray-800 */
  border-color: #374151; /* gray-700 */
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
}

.year-day-cell:hover .year-day-tooltip {
  visibility: visible;
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}


.year-day-tooltip:after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 50%;
  margin-left: -5px;
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid white;
}

:root.dark .year-day-tooltip:after {
  border-top-color: #1f2937; /* gray-800, same as tooltip background */
}

.year-month-grid > div:nth-child(7n) .year-day-tooltip,
.year-month-grid > div:nth-child(7n-1) .year-day-tooltip {
  transform: translateX(-90%);
}

.year-month-grid > div:nth-child(7n) .year-day-tooltip:after,
.year-month-grid > div:nth-child(7n-1) .year-day-tooltip:after {
  left: 90%;
}

.year-month-grid > div:nth-child(7n-6) .year-day-tooltip,
.year-month-grid > div:nth-child(7n-5) .year-day-tooltip {
  transform: translateX(-10%);
}

.year-month-grid > div:nth-child(7n-6) .year-day-tooltip:after,
.year-month-grid > div:nth-child(7n-5) .year-day-tooltip:after {
  left: 10%;
}

.year-month-grid > div:nth-child(7n) .year-day-tooltip,
.year-month-grid > div:nth-child(7n-1) .year-day-tooltip {
  transform: translateX(-90%);
}

.year-month-grid > div:nth-child(7n) .year-day-tooltip:after,
.year-month-grid > div:nth-child(7n-1) .year-day-tooltip:after {
  left: 90%;
}

.year-month-grid > div:nth-child(7n-6) .year-day-tooltip,
.year-month-grid > div:nth-child(7n-5) .year-day-tooltip {
  transform: translateX(-10%);
}

.year-month-grid > div:nth-child(7n-6) .year-day-tooltip:after,
.year-month-grid > div:nth-child(7n-5) .year-day-tooltip:after {
  left: 10%;
}

.year-month-grid > div:hover .year-day-tooltip {
  transform: translateX(-50%) translateY(0);
}

.year-month-grid > div:nth-child(7n):hover .year-day-tooltip,
.year-month-grid > div:nth-child(7n-1):hover .year-day-tooltip {
  transform: translateX(-90%) translateY(0);
}

.year-month-grid > div:nth-child(7n-6):hover .year-day-tooltip,
.year-month-grid > div:nth-child(7n-5):hover .year-day-tooltip {
  transform: translateX(-10%) translateY(0);
}


@media (max-width: 640px) {
  .year-day-cell {
    height: 20px;
    font-size: 11px;
  }
  
  .year-day-indicator {
    height: 2px;
    width: 2px;
  }
}


@media (max-width: 375px) {
  .year-day-cell {
    height: 16px;
    font-size: 10px;
  }
}
</style> 
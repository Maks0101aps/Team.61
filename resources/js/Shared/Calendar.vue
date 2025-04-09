<template>
  <div class="calendar-container">
    <!-- Calendar Header - Redesigned for better responsive behavior -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <!-- Month name and view mode buttons -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <h2 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
          {{ currentMonthName }}
        </h2>
        <div class="flex flex-wrap gap-1">
          <button v-for="mode in viewModes" 
                  :key="mode.value"
                  @click="switchView(mode.value)"
                  class="inline-flex items-center justify-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-md text-xs sm:text-sm font-medium transition-all duration-200"
                  :class="currentView === mode.value 
                    ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' 
                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-300 shadow-sm'">
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
                class="inline-flex items-center justify-center p-1.5 sm:px-2 sm:py-1.5 bg-white border border-gray-300 rounded-md text-xs sm:text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline ml-1">{{ language === 'uk' ? 'Попередній' : 'Previous' }}</span>
        </button>
        
        <button @click="nextPeriod" 
                class="inline-flex items-center justify-center p-1.5 sm:px-2 sm:py-1.5 bg-white border border-gray-300 rounded-md text-xs sm:text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline ml-1">{{ language === 'uk' ? 'Наступний' : 'Next' }}</span>
        </button>
        
        <!-- Create Event button -->
        <Link href="/events/create"
              class="inline-flex items-center justify-center p-1.5 sm:px-3 sm:py-1.5 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-md text-xs sm:text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg"
              @click.prevent="checkEventAccess">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline ml-1">{{ language === 'uk' ? 'Створити' : 'Create' }}</span>
        </Link>
      </div>
    </div>

    <!-- Monthly View -->
    <div v-if="currentView === 'month'" class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-7 gap-px bg-gradient-to-r from-blue-100 to-blue-200">
        <!-- Weekday headers -->
        <div v-for="day in weekDays" :key="day" 
             class="bg-gradient-to-b from-blue-50 to-white py-3 text-center text-sm font-bold text-blue-900">
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
                 :class="{ 'text-gray-400': !day.isCurrentMonth, 'text-blue-900': day.isCurrentMonth }">
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
    <div v-else-if="currentView === 'week'" class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-7 gap-px bg-gradient-to-r from-blue-100 to-blue-200">
        <!-- Weekday headers -->
        <div v-for="day in weekDays" :key="day" 
             class="bg-gradient-to-b from-blue-50 to-white py-3 text-center text-sm font-bold text-blue-900">
          {{ day }}
        </div>

        <!-- Week days -->
        <div v-for="day in weekDays" :key="day" 
             class="bg-white min-h-[200px] p-3 transition-all duration-200 hover:bg-blue-50">
          <div class="text-sm font-bold mb-2 text-blue-900">
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
    <div v-else-if="currentView === 'day'" class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-1 gap-px bg-gradient-to-r from-blue-100 to-blue-200">
        <div class="bg-white p-6">
          <div class="text-xl font-bold mb-6 text-blue-900">
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
    <div v-else-if="currentView === 'year'" class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 sm:p-6">
        <div v-for="month in 12" :key="month" 
             class="month-card border rounded-lg p-2 sm:p-3 transition-all duration-200 hover:bg-blue-50">
          <div class="text-sm font-medium mb-2 text-blue-900 cursor-pointer hover:text-blue-600 hover:underline text-center"
               @click="switchToMonth(month - 1)">
            {{ getMonthName(month - 1) }}
          </div>
          <!-- Add weekday headers for better orientation -->
          <div class="year-month-headers">
            <div v-for="(day, index) in weekDays" :key="index" class="text-xs text-gray-500">
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
                <p class="text-xs font-semibold text-gray-700 border-b pb-1 mb-2">
                  {{ formatShortDate(day.date) }}
                </p>
                <div v-for="event in getEventsForDay(day.date).slice(0, 3)" :key="event.id" 
                     class="mb-1.5 last:mb-0 text-left">
                  <span class="block truncate text-xs font-medium">{{ event.title }}</span>
                  <span class="block text-xs text-gray-500">{{ formatTime(event.start_date) }}</span>
                </div>
                <div v-if="getEventsForDay(day.date).length > 3" class="text-blue-600 text-right mt-1 text-xs">
                  {{ language === 'uk' ? 'Ще ' : '+ ' }}{{ getEventsForDay(day.date).length - 3 }}
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Fallback View (during refresh) -->
    <div v-else class="bg-white rounded-xl shadow-lg overflow-hidden min-h-[400px] flex items-center justify-center">
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
      <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-2xl transform transition-all duration-300">
        <div class="flex justify-between items-start mb-6">
          <h3 class="text-xl font-bold text-blue-900">{{ selectedEvent.title }}</h3>
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
            <h4 class="text-sm font-medium text-blue-700">{{ language === 'uk' ? 'Час' : 'Time' }}</h4>
            <p class="mt-1 text-sm text-gray-900">
              {{ formatDateTime(selectedEvent.start_date) }}
            </p>
          </div>
          
          <div>
            <h4 class="text-sm font-medium text-blue-700">{{ language === 'uk' ? 'Тип' : 'Type' }}</h4>
            <p class="mt-1 text-sm text-gray-900">{{ selectedEvent.type }}</p>
          </div>
          
          <div v-if="selectedEvent.location">
            <h4 class="text-sm font-medium text-blue-700">{{ language === 'uk' ? 'Місце' : 'Location' }}</h4>
            <p class="mt-1 text-sm text-gray-900">{{ selectedEvent.location }}</p>
          </div>
          
          <div v-if="selectedEvent.online_link">
            <h4 class="text-sm font-medium text-blue-700">{{ language === 'uk' ? 'Онлайн посилання' : 'Online Link' }}</h4>
            <a :href="selectedEvent.online_link" 
               target="_blank" 
               class="mt-1 text-sm text-blue-600 hover:text-blue-900 transition-colors duration-200">
              {{ selectedEvent.online_link }}
            </a>
          </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-3">
          <!-- Add delete button that only appears for events created by the current user -->
          <button v-if="canDeleteEvent(selectedEvent)" 
                  @click="deleteEvent(selectedEvent)"
                  class="px-4 py-2 bg-red-500 text-white rounded-lg shadow-sm text-sm font-medium hover:bg-red-600 transition-all duration-200">
            {{ language === 'uk' ? 'Видалити' : 'Delete' }}
          </button>
          <button @click="selectedEvent = null" 
                  class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
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
    // Create reactive refs first before using them
    const currentDate = ref(dayjs())
    const selectedEvent = ref(null)
    const currentView = ref('month')
    
    const updateLocale = () => {
      // Set dayjs locale based on current language
      if (props.language === 'uk') {
        dayjs.locale('uk')
      } else {
        dayjs.locale('en')
      }
      
      // No need to force a refresh by changing view mode
      // Just update the date object to trigger reactivity
      currentDate.value = dayjs(currentDate.value)
    }
    
    // Watch for language changes and update locale
    watch(() => props.language, (newLanguage) => {
      updateLocale()
      
      // No need to force a refresh by changing view mode
      // Just update the date object to trigger reactivity
      currentDate.value = dayjs(currentDate.value)
    }, { immediate: true })

    const viewModes = computed(() => [
      { value: 'month', label: props.language === 'uk' ? 'Місяць' : 'Month' },
      { value: 'week', label: props.language === 'uk' ? 'Тиждень' : 'Week' },
      { value: 'day', label: props.language === 'uk' ? 'День' : 'Day' },
      { value: 'year', label: props.language === 'uk' ? 'Рік' : 'Year' }
    ])

    const currentMonthName = computed(() => {
      updateLocale()
      const month = currentDate.value.format('MMMM')
      const year = currentDate.value.format('YYYY')
      
      // Force dayjs to use the current language setting
      dayjs.locale(props.language)
      
      // Get the month name in the current locale
      const localizedMonth = currentDate.value.format('MMMM')
      
      if (props.language === 'uk') {
        return `${localizedMonth.charAt(0).toUpperCase()}${localizedMonth.slice(1)} ${year}`
      }
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
      // Force dayjs to use the current language setting
      dayjs.locale(props.language)
      
      const dayIndex = weekDays.value.indexOf(weekDay)
      const date = currentDate.value.startOf('week').add(dayIndex, 'day')
      const formatted = date.format('DD MMMM')
      if (props.language === 'uk') {
        const [day, month] = formatted.split(' ')
        return `${day} ${month.charAt(0).toUpperCase()}${month.slice(1)}`
      }
      return formatted
    }

    const getMonthName = (monthIndex) => {
      // Force dayjs to use the current language setting
      dayjs.locale(props.language)
      
      // Create a date object for the first day of the specified month
      const date = dayjs().month(monthIndex).date(1)
      const month = date.format('MMMM')
      
      if (props.language === 'uk') {
        return `${month.charAt(0).toUpperCase()}${month.slice(1)}`
      }
      return month
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
      const baseClasses = 'block truncate'
      const typeClasses = {
        exam: 'bg-red-100 text-red-800',
        test: 'bg-yellow-100 text-yellow-800',
        school_event: 'bg-green-100 text-green-800',
        parent_meeting: 'bg-blue-100 text-blue-800',
        personal: 'bg-purple-100 text-purple-800'
      }
      return `${baseClasses} ${typeClasses[event.type] || 'bg-gray-100 text-gray-800'}`
    }

    const showEventDetails = (event) => {
      selectedEvent.value = event
    }

    const formatDateTime = (date) => {
      // Force dayjs to use the current language setting
      dayjs.locale(props.language)
      
      return dayjs(date).format('DD.MM.YYYY HH:mm')
    }

    // Check if current user can delete this event
    const canDeleteEvent = (event) => {
      // If there's no event, return false
      if (!event) return false
      
      // Check if current user created this event
      // We'll rely on the browser to send the authenticated session with the request
      // The backend will handle the actual authorization check
      return true
    }
    
    // Delete an event
    const deleteEvent = (event) => {
      if (!confirm(props.language === 'uk' ? 'Ви впевнені, що хочете видалити цю подію?' : 'Are you sure you want to delete this event?')) {
        return;
      }
      
      // Store the event ID before sending the delete request
      const eventId = event.id;
      
      // Close the modal first to prevent interaction with a potentially deleted event
      selectedEvent.value = null;
      
      // Send the delete request - the server will check if the user has permission
      axios.delete(`/events/${eventId}`, {
        headers: {
          'X-Inertia': 'false'
        }
      })
        .then(response => {
          // Show success message
          const successMsg = props.language === 'uk' ? 'Подію видалено' : 'Event deleted';
          const flashEvent = new CustomEvent('inertia:flash', {
            detail: {
              type: 'success',
              message: successMsg
            }
          });
          window.dispatchEvent(flashEvent);
          
          // Remove the event from the local events array
          const eventIndex = props.events.findIndex(e => e.id === eventId);
          if (eventIndex !== -1) {
            props.events.splice(eventIndex, 1);
          }
        })
        .catch(error => {
          // Show error message
          let errorMsg = props.language === 'uk' ? 'Помилка при видаленні події' : 'Error deleting event';
          
          if (error.response && error.response.data) {
            if (error.response.data.message) {
              errorMsg = error.response.data.message;
            } else if (error.response.data.error) {
              errorMsg = error.response.data.error;
            }
          }
          
          // Show error in UI
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
      // Ensure we're using the right locale before changing date
      dayjs.locale(props.language)
      
      if (currentView.value === 'year') {
        currentDate.value = dayjs(currentDate.value).subtract(1, 'year')
      } else {
        // Update the date directly without temporary view change
        if (currentView.value === 'month') {
          currentDate.value = dayjs(currentDate.value).subtract(1, 'month')
        } else if (currentView.value === 'week') {
          currentDate.value = dayjs(currentDate.value).subtract(1, 'week')
        } else if (currentView.value === 'day') {
          currentDate.value = dayjs(currentDate.value).subtract(1, 'day')
        }
      }
    }

    const nextPeriod = () => {
      // Ensure we're using the right locale before changing date
      dayjs.locale(props.language)
      
      if (currentView.value === 'year') {
        currentDate.value = dayjs(currentDate.value).add(1, 'year')
      } else {
        // Update the date directly without temporary view change
        if (currentView.value === 'month') {
          currentDate.value = dayjs(currentDate.value).add(1, 'month')
        } else if (currentView.value === 'week') {
          currentDate.value = dayjs(currentDate.value).add(1, 'week')
        } else if (currentView.value === 'day') {
          currentDate.value = dayjs(currentDate.value).add(1, 'day')
        }
      }
    }

    const createEventOnDate = (date) => {
      window.location.href = `/events/create?date=${date}`
    }
    
    const checkEventAccess = (date) => {
      // Default to current date if none provided
      const selectedDate = date ? date : currentDate.value.format('YYYY-MM-DD')

      // Make an axios request to check if the user can access event creation
      axios.get('/events/create-permissions', { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => {
        // Redirect to event creation with the selected date pre-filled
        window.location.href = `/events/create?date=${selectedDate}`
      })
      .catch(error => {
        // Handle permission errors
        if (error.response && error.response.status === 403) {
          // If we have a specific error message from the server
          alert(error.response.data?.message || 
               (props.language === 'uk' ? 'Ви не маєте права створювати події' : 'You do not have permission to create events'))
        } else {
          // For other errors, show generic message
          alert(props.language === 'uk' ? 'Виникла помилка' : 'An error occurred')
        }
      })
    }

    const formatFullDate = (date) => {
      // Force dayjs to use the current language setting
      dayjs.locale(props.language)
      
      return date.format('DD MMMM YYYY')
    }

    // Add new function to handle view switching
    const switchView = (viewMode) => {
      // Directly change to the new view without setting to '_refresh'
      currentView.value = viewMode;
    }

    // Add method to switch to a specific month
    const switchToMonth = (monthIndex) => {
      // Set the current date to the selected month
      currentDate.value = dayjs(currentDate.value).month(monthIndex);
      // Switch to month view
      currentView.value = 'month';
    }

    // Add new method to go to specific day
    const goToDay = (date) => {
      // First check if we can create events, if so, open the create form
      axios.get('/events/create-permissions', { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => {
        // User has permissions, redirect to create event page
        window.location.href = `/events/create?date=${date}`
      })
      .catch(error => {
        // User doesn't have permissions, switch to day view for that date
        currentDate.value = dayjs(date);
        currentView.value = 'day';
      });
    }

    // Add a computed helper for the language
    const language = computed(() => props.language)

    // Add new formatting helper methods
    const formatShortDate = (date) => {
      dayjs.locale(props.language);
      return dayjs(date).format('DD MMM');
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
  @apply bg-white rounded-xl shadow-lg p-4 sm:p-6;
}

/* Responsive adjustments for small screens */
@media (max-width: 640px) {
  .calendar-container {
    @apply p-3;
  }
}

/* Добавляем анимацию для модального окна */
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

/* Year view improvements */
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

.year-day-inactive {
  color: #9ca3af;
  cursor: default;
  pointer-events: none;
}

.year-day-with-events {
  background-color: #eff6ff;
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

.year-day-cell:hover .year-day-tooltip {
  visibility: visible;
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}

/* Add a tooltip arrow */
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

/* Adjust tooltip position for edge cells to keep it visible */
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

/* Adjust grid dimensions for smaller screens */
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

/* Further reduce for very small screens */
@media (max-width: 375px) {
  .year-day-cell {
    height: 16px;
    font-size: 10px;
  }
}
</style> 
<template>
  <div class="calendar-container">
    <div class="flex justify-between items-center mb-6">
      <div class="flex items-center space-x-4">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
          {{ currentMonthName }}
        </h2>
        <div class="flex space-x-2">
          <button v-for="mode in viewModes" 
                  :key="mode.value"
                  @click="currentView = mode.value"
                  class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105"
                  :class="currentView === mode.value 
                    ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-lg' 
                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-300 shadow-sm'">
            {{ mode.label }}
          </button>
        </div>
      </div>
      <div class="flex space-x-3">
        <button @click="previousPeriod" 
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105 shadow-sm">
          {{ language === 'uk' ? 'Попередній' : 'Previous' }}
        </button>
        <button @click="nextPeriod" 
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105 shadow-sm">
          {{ language === 'uk' ? 'Наступний' : 'Next' }}
        </button>
       <Link href="/events/create"
              class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
          {{ language === 'uk' ? 'Створити подію' : 'Create Event' }}
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
            <button @click.stop="createEventOnDate(day.date)"
                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 p-1 rounded-lg bg-blue-500 hover:bg-blue-600 text-white"
                    :title="language === 'uk' ? 'Створити подію' : 'Create Event'">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
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
            {{ currentDate.format('DD MMMM YYYY') }}
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
      <div class="grid grid-cols-4 gap-4 p-6">
        <div v-for="month in 12" :key="month" 
             class="border rounded-lg p-4 transition-all duration-200 hover:bg-blue-50">
          <div class="text-sm font-bold mb-3 text-blue-900">
            {{ getMonthName(month - 1) }}
          </div>
          <div class="grid grid-cols-7 gap-1">
            <div v-for="day in getMonthDays(month - 1)" :key="day.date"
                 class="text-xs p-1.5 rounded-lg transition-all duration-200 hover:bg-blue-100"
                 :class="{
                   'bg-red-50': hasConflicts(day.date),
                   'text-gray-400': !day.isCurrentMonth,
                   'text-blue-900': day.isCurrentMonth
                 }">
              {{ day.dayNumber }}
            </div>
          </div>
        </div>
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
        
        <div class="mt-8 flex justify-end">
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
    const updateLocale = () => {
      if (props.language === 'uk') {
        dayjs.locale('uk')
      } else {
        dayjs.locale('en')
      }
    }
    
    watch(() => props.language, updateLocale, { immediate: true })

    const currentDate = ref(dayjs())
    const selectedEvent = ref(null)
    const currentView = ref('month')

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
      if (props.language === 'uk') {
        return `${month.charAt(0).toUpperCase()}${month.slice(1)} ${year}`
      }
      return `${month} ${year}`
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
      updateLocale()
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
      updateLocale()
      const month = dayjs().month(monthIndex).format('MMMM')
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
      updateLocale()
      return dayjs(date).format('DD.MM.YYYY HH:mm')
    }

    const previousPeriod = () => {
      switch (currentView.value) {
        case 'month':
          currentDate.value = currentDate.value.subtract(1, 'month')
          break
        case 'week':
          currentDate.value = currentDate.value.subtract(1, 'week')
          break
        case 'day':
          currentDate.value = currentDate.value.subtract(1, 'day')
          break
        case 'year':
          currentDate.value = currentDate.value.subtract(1, 'year')
          break
      }
    }

    const nextPeriod = () => {
      switch (currentView.value) {
        case 'month':
          currentDate.value = currentDate.value.add(1, 'month')
          break
        case 'week':
          currentDate.value = currentDate.value.add(1, 'week')
          break
        case 'day':
          currentDate.value = currentDate.value.add(1, 'day')
          break
        case 'year':
          currentDate.value = currentDate.value.add(1, 'year')
          break
      }
    }

    const createEventOnDate = (date) => {
      window.location.href = `/events/create?date=${date}`
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
      createEventOnDate
    }
  }
}
</script>

<style scoped>
.calendar-container {
  @apply bg-white rounded-xl shadow-lg p-6;
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
</style> 
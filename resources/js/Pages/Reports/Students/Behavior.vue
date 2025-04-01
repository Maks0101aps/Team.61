<template>
  <div>
    <Head :title="language === 'uk' ? 'Звіт поведінки студентів' : 'Student Behavior Report'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Поведінка студентів' : 'Student Behavior' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Звіт поведінки студентів' : 'Student Behavior Report' }}
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

    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
      <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
          {{ language === 'uk' ? 'Фільтри' : 'Filters' }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ language === 'uk' ? 'Група' : 'Group' }}
            </label>
            <select 
              v-model="filters.group" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі групи' : 'All groups' }}</option>
              <option value="1">{{ language === 'uk' ? 'Група 1' : 'Group 1' }}</option>
              <option value="2">{{ language === 'uk' ? 'Група 2' : 'Group 2' }}</option>
              <option value="3">{{ language === 'uk' ? 'Група 3' : 'Group 3' }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ language === 'uk' ? 'Період' : 'Period' }}
            </label>
            <select 
              v-model="filters.period" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="month">{{ language === 'uk' ? 'Останній місяць' : 'Last Month' }}</option>
              <option value="quarter">{{ language === 'uk' ? 'Поточна чверть' : 'Current Quarter' }}</option>
              <option value="semester">{{ language === 'uk' ? 'Поточний семестр' : 'Current Semester' }}</option>
              <option value="year">{{ language === 'uk' ? 'Навчальний рік' : 'Academic Year' }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ language === 'uk' ? 'Тип події' : 'Event Type' }}
            </label>
            <select 
              v-model="filters.eventType" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі події' : 'All events' }}</option>
              <option value="positive">{{ language === 'uk' ? 'Позитивні' : 'Positive' }}</option>
              <option value="negative">{{ language === 'uk' ? 'Негативні' : 'Negative' }}</option>
              <option value="warning">{{ language === 'uk' ? 'Попередження' : 'Warnings' }}</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
          {{ language === 'uk' ? 'Звіт поведінки' : 'Behavior Report' }}
        </h3>
        <div class="flex space-x-2">
          <button 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            @click="exportToPdf"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            {{ language === 'uk' ? 'Експорт PDF' : 'Export PDF' }}
          </button>
          <button 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            @click="exportToExcel"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            {{ language === 'uk' ? 'Експорт Excel' : 'Export Excel' }}
          </button>
        </div>
      </div>
      
      <!-- Behavior Summary -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200">
          <div class="flex items-center mb-2">
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div class="ml-3">
              <h4 class="text-sm font-medium text-gray-900">
                {{ language === 'uk' ? 'Загальна кількість подій' : 'Total Events' }}
              </h4>
              <p class="text-xl font-bold text-gray-800">{{ behaviorSummary.total }}</p>
            </div>
          </div>
          <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
            <div class="h-full rounded-full bg-blue-500" :style="{ width: '100%' }"></div>
          </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200">
          <div class="flex items-center mb-2">
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-3">
              <h4 class="text-sm font-medium text-gray-900">
                {{ language === 'uk' ? 'Позитивні події' : 'Positive Events' }}
              </h4>
              <p class="text-xl font-bold text-gray-800">{{ behaviorSummary.positive }}</p>
            </div>
          </div>
          <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
            <div class="h-full rounded-full bg-green-500" :style="{ width: (behaviorSummary.positive / behaviorSummary.total * 100) + '%' }"></div>
          </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200">
          <div class="flex items-center mb-2">
            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="ml-3">
              <h4 class="text-sm font-medium text-gray-900">
                {{ language === 'uk' ? 'Негативні події' : 'Negative Events' }}
              </h4>
              <p class="text-xl font-bold text-gray-800">{{ behaviorSummary.negative }}</p>
            </div>
          </div>
          <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
            <div class="h-full rounded-full bg-red-500" :style="{ width: (behaviorSummary.negative / behaviorSummary.total * 100) + '%' }"></div>
          </div>
        </div>
      </div>
      
      <!-- Behavior events table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Студент' : 'Student' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Група' : 'Group' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Дата' : 'Date' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Тип' : 'Type' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Опис' : 'Description' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Записав' : 'Recorded By' }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="event in filteredEvents" :key="event.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                      {{ event.student.name.charAt(0) }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ getStudentName(event.student) }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ getGroupName(event.student.group) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ formatDate(event.date) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getEventTypeClass(event.type)">
                  {{ getEventTypeName(event.type) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ getEventDescription(event) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ getTeacherName(event.recordedBy) }}</div>
              </td>
            </tr>
          </tbody>
        </table>
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
      filters: {
        group: '',
        period: 'month',
        eventType: ''
      },
      behaviorSummary: {
        total: 38,
        positive: 24,
        negative: 14
      },
      events: [
        {
          id: 1,
          student: { id: 1, name: 'Петро Іваненко', nameEn: 'Petro Ivanenko', group: '1' },
          date: '2023-09-05',
          type: 'positive',
          description: 'Відмінна робота на уроці літератури',
          descriptionEn: 'Excellent work in literature class',
          recordedBy: { id: 5, name: 'Мельник Т.С.', nameEn: 'Melnyk T.' }
        },
        {
          id: 2,
          student: { id: 2, name: 'Марія Коваленко', nameEn: 'Maria Kovalenko', group: '1' },
          date: '2023-09-08',
          type: 'positive',
          description: 'Допомога у підготовці шкільного заходу',
          descriptionEn: 'Help with preparing school event',
          recordedBy: { id: 3, name: 'Ковальчук Н.І.', nameEn: 'Kovalchuk N.' }
        },
        {
          id: 3,
          student: { id: 3, name: 'Олександр Шевченко', nameEn: 'Oleksandr Shevchenko', group: '2' },
          date: '2023-09-12',
          type: 'negative',
          description: 'Запізнення на урок',
          descriptionEn: 'Late for class',
          recordedBy: { id: 1, name: 'Іваненко О.П.', nameEn: 'Ivanenko O.' }
        },
        {
          id: 4,
          student: { id: 4, name: 'Анна Мельник', nameEn: 'Anna Melnyk', group: '2' },
          date: '2023-09-15',
          type: 'positive',
          description: 'Перемога в олімпіаді з математики',
          descriptionEn: 'Victory in mathematics olympiad',
          recordedBy: { id: 1, name: 'Іваненко О.П.', nameEn: 'Ivanenko O.' }
        },
        {
          id: 5,
          student: { id: 5, name: 'Микола Бондаренко', nameEn: 'Mykola Bondarenko', group: '3' },
          date: '2023-09-18',
          type: 'warning',
          description: 'Не виконане домашнє завдання з фізики',
          descriptionEn: 'Unfinished physics homework',
          recordedBy: { id: 2, name: 'Петренко І.М.', nameEn: 'Petrenko I.' }
        },
        {
          id: 6,
          student: { id: 6, name: 'Юлія Ткаченко', nameEn: 'Yulia Tkachenko', group: '3' },
          date: '2023-09-22',
          type: 'negative',
          description: 'Використання мобільного телефону під час уроку',
          descriptionEn: 'Using mobile phone during class',
          recordedBy: { id: 4, name: 'Шевченко В.А.', nameEn: 'Shevchenko V.' }
        },
        {
          id: 7,
          student: { id: 1, name: 'Петро Іваненко', nameEn: 'Petro Ivanenko', group: '1' },
          date: '2023-09-25',
          type: 'positive',
          description: 'Участь у шкільному волонтерському проекті',
          descriptionEn: 'Participating in school volunteer project',
          recordedBy: { id: 5, name: 'Мельник Т.С.', nameEn: 'Melnyk T.' }
        },
        {
          id: 8,
          student: { id: 3, name: 'Олександр Шевченко', nameEn: 'Oleksandr Shevchenko', group: '2' },
          date: '2023-09-28',
          type: 'negative',
          description: 'Порушення дисципліни на перерві',
          descriptionEn: 'Breaking discipline during break',
          recordedBy: { id: 3, name: 'Ковальчук Н.І.', nameEn: 'Kovalchuk N.' }
        }
      ]
    }
  },
  computed: {
    filteredEvents() {
      let result = this.events;
      
      // Filter by group
      if (this.filters.group) {
        result = result.filter(event => event.student.group === this.filters.group);
      }
      
      // Filter by event type
      if (this.filters.eventType) {
        result = result.filter(event => event.type === this.filters.eventType);
      }
      
      // Sort by date (recent first)
      result = result.sort((a, b) => new Date(b.date) - new Date(a.date));
      
      return result;
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    
    // Also listen for language changes using the event bus
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang;
      });
    }
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  methods: {
    updateLanguage(event) {
      this.language = event.detail.language;
    },
    getStudentName(student) {
      return this.language === 'uk' ? student.name : student.nameEn;
    },
    getGroupName(groupId) {
      return this.language === 'uk' ? `Група ${groupId}` : `Group ${groupId}`;
    },
    getTeacherName(teacher) {
      return this.language === 'uk' ? teacher.name : teacher.nameEn;
    },
    getEventTypeName(type) {
      const types = {
        'positive': this.language === 'uk' ? 'Позитивна' : 'Positive',
        'negative': this.language === 'uk' ? 'Негативна' : 'Negative',
        'warning': this.language === 'uk' ? 'Попередження' : 'Warning'
      };
      
      return types[type] || type;
    },
    getEventTypeClass(type) {
      const classes = {
        'positive': 'bg-green-100 text-green-800',
        'negative': 'bg-red-100 text-red-800',
        'warning': 'bg-yellow-100 text-yellow-800'
      };
      
      return classes[type] || 'bg-gray-100 text-gray-800';
    },
    getEventDescription(event) {
      return this.language === 'uk' ? event.description : event.descriptionEn;
    },
    formatDate(dateStr) {
      const date = new Date(dateStr);
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return date.toLocaleDateString(this.language === 'uk' ? 'uk-UA' : 'en-US', options);
    },
    exportToPdf() {
      alert(this.language === 'uk' ? 'Експорт у PDF...' : 'Exporting to PDF...');
    },
    exportToExcel() {
      alert(this.language === 'uk' ? 'Експорт у Excel...' : 'Exporting to Excel...');
    }
  }
}
</script> 
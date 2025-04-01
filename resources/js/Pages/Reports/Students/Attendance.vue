<template>
  <div>
    <Head :title="language === 'uk' ? 'Звіт відвідуваності студентів' : 'Student Attendance Report'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Відвідуваність студентів' : 'Student Attendance' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Звіт відвідуваності студентів' : 'Student Attendance Report' }}
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
              <option value="week">{{ language === 'uk' ? 'Тиждень' : 'Week' }}</option>
              <option value="month">{{ language === 'uk' ? 'Місяць' : 'Month' }}</option>
              <option value="semester">{{ language === 'uk' ? 'Семестр' : 'Semester' }}</option>
              <option value="year">{{ language === 'uk' ? 'Рік' : 'Year' }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ language === 'uk' ? 'Статус відвідуваності' : 'Attendance Status' }}
            </label>
            <select 
              v-model="filters.status" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі' : 'All' }}</option>
              <option value="present">{{ language === 'uk' ? 'Присутні' : 'Present' }}</option>
              <option value="absent">{{ language === 'uk' ? 'Відсутні' : 'Absent' }}</option>
              <option value="late">{{ language === 'uk' ? 'Запізнилися' : 'Late' }}</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
          {{ language === 'uk' ? 'Загальна статистика' : 'Overall Statistics' }}
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
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm text-blue-600 font-medium">
                {{ language === 'uk' ? 'Середня відвідуваність' : 'Average Attendance' }}
              </p>
              <h4 class="text-2xl font-bold text-blue-800 mt-1">87.5%</h4>
            </div>
            <div class="p-2 bg-blue-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
          </div>
          <div class="w-full h-2 bg-blue-200 rounded-full mt-3">
            <div class="h-full bg-blue-600 rounded-full" style="width: 87.5%"></div>
          </div>
        </div>
        
        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm text-green-600 font-medium">
                {{ language === 'uk' ? 'Присутні' : 'Present' }}
              </p>
              <h4 class="text-2xl font-bold text-green-800 mt-1">126 / 144</h4>
            </div>
            <div class="p-2 bg-green-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <div class="text-xs text-green-600 mt-3">
            {{ language === 'uk' ? 'Збільшення на 3.2% у порівнянні з минулим періодом' : 'Increased by 3.2% compared to previous period' }}
          </div>
        </div>
        
        <div class="bg-red-50 border border-red-100 rounded-lg p-4">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm text-red-600 font-medium">
                {{ language === 'uk' ? 'Відсутні' : 'Absent' }}
              </p>
              <h4 class="text-2xl font-bold text-red-800 mt-1">18 / 144</h4>
            </div>
            <div class="p-2 bg-red-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
          </div>
          <div class="text-xs text-red-600 mt-3">
            {{ language === 'uk' ? 'Зменшення на 1.8% у порівнянні з минулим періодом' : 'Decreased by 1.8% compared to previous period' }}
          </div>
        </div>
      </div>
      
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
                {{ language === 'uk' ? 'Присутність (%)' : 'Attendance (%)' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Присутні дні' : 'Days Present' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Відсутні дні' : 'Days Absent' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Запізнення' : 'Late' }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="student in filteredAttendanceData" :key="student.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                      {{ student.name.charAt(0) }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                    <div class="text-sm text-gray-500">{{ student.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ student.group }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="mr-2 w-16 bg-gray-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full" 
                      :class="{
                        'bg-green-600': student.attendancePercentage >= 90,
                        'bg-yellow-500': student.attendancePercentage >= 75 && student.attendancePercentage < 90,
                        'bg-red-500': student.attendancePercentage < 75
                      }"
                      :style="{ width: student.attendancePercentage + '%' }"></div>
                  </div>
                  <span class="text-sm font-medium text-gray-900">{{ student.attendancePercentage }}%</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ student.daysPresent }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm" :class="student.daysAbsent > 3 ? 'text-red-600 font-medium' : 'text-gray-900'">
                  {{ student.daysAbsent }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ student.late }}</div>
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
        status: ''
      },
      attendanceData: [
        {
          id: 1,
          name: language === 'uk' ? 'Петро Іваненко' : 'Petro Ivanenko',
          email: 'petro@example.com',
          group: language === 'uk' ? 'Група 1' : 'Group 1',
          attendancePercentage: 95,
          daysPresent: 19,
          daysAbsent: 1,
          late: 2
        },
        {
          id: 2,
          name: language === 'uk' ? 'Марія Коваленко' : 'Maria Kovalenko',
          email: 'maria@example.com',
          group: language === 'uk' ? 'Група 1' : 'Group 1',
          attendancePercentage: 85,
          daysPresent: 17,
          daysAbsent: 3,
          late: 1
        },
        {
          id: 3,
          name: language === 'uk' ? 'Олександр Шевченко' : 'Oleksandr Shevchenko',
          email: 'oleksandr@example.com',
          group: language === 'uk' ? 'Група 2' : 'Group 2',
          attendancePercentage: 100,
          daysPresent: 20,
          daysAbsent: 0,
          late: 0
        },
        {
          id: 4,
          name: language === 'uk' ? 'Анна Мельник' : 'Anna Melnyk',
          email: 'anna@example.com',
          group: language === 'uk' ? 'Група 2' : 'Group 2',
          attendancePercentage: 70,
          daysPresent: 14,
          daysAbsent: 6,
          late: 3
        },
        {
          id: 5,
          name: language === 'uk' ? 'Микола Бондаренко' : 'Mykola Bondarenko',
          email: 'mykola@example.com',
          group: language === 'uk' ? 'Група 3' : 'Group 3',
          attendancePercentage: 90,
          daysPresent: 18,
          daysAbsent: 2,
          late: 1
        },
        {
          id: 6,
          name: language === 'uk' ? 'Юлія Ткаченко' : 'Yulia Tkachenko',
          email: 'yulia@example.com',
          group: language === 'uk' ? 'Група 3' : 'Group 3',
          attendancePercentage: 80,
          daysPresent: 16,
          daysAbsent: 4,
          late: 2
        },
      ]
    }
  },
  computed: {
    filteredAttendanceData() {
      return this.attendanceData.filter(student => {
        // Filter by group
        if (this.filters.group && student.group !== this.filters.group) {
          return false;
        }
        
        // Filter by status
        if (this.filters.status === 'present' && student.attendancePercentage < 90) {
          return false;
        } else if (this.filters.status === 'absent' && student.daysAbsent <= 3) {
          return false;
        } else if (this.filters.status === 'late' && student.late === 0) {
          return false;
        }
        
        return true;
      });
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    
    // Also listen for language changes using the event bus
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang;
        this.updateStudentNames();
      });
    }
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  methods: {
    updateLanguage(event) {
      this.language = event.detail.language;
      this.updateStudentNames();
    },
    updateStudentNames() {
      // Update student names based on language
      this.attendanceData = this.attendanceData.map(student => {
        const isUkrainian = this.language === 'uk';
        if (student.id === 1) {
          student.name = isUkrainian ? 'Петро Іваненко' : 'Petro Ivanenko';
          student.group = isUkrainian ? 'Група 1' : 'Group 1';
        } else if (student.id === 2) {
          student.name = isUkrainian ? 'Марія Коваленко' : 'Maria Kovalenko';
          student.group = isUkrainian ? 'Група 1' : 'Group 1';
        } else if (student.id === 3) {
          student.name = isUkrainian ? 'Олександр Шевченко' : 'Oleksandr Shevchenko';
          student.group = isUkrainian ? 'Група 2' : 'Group 2';
        } else if (student.id === 4) {
          student.name = isUkrainian ? 'Анна Мельник' : 'Anna Melnyk';
          student.group = isUkrainian ? 'Група 2' : 'Group 2';
        } else if (student.id === 5) {
          student.name = isUkrainian ? 'Микола Бондаренко' : 'Mykola Bondarenko';
          student.group = isUkrainian ? 'Група 3' : 'Group 3';
        } else if (student.id === 6) {
          student.name = isUkrainian ? 'Юлія Ткаченко' : 'Yulia Tkachenko';
          student.group = isUkrainian ? 'Група 3' : 'Group 3';
        }
        return student;
      });
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
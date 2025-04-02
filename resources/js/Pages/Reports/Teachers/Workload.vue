<template>
  <div>
    <Head :title="language === 'uk' ? 'Звіт навантаження вчителів' : 'Teacher Workload Report'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Навантаження вчителів' : 'Teacher Workload' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Звіт навантаження вчителів' : 'Teacher Workload Report' }}
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
              {{ language === 'uk' ? 'Предмет' : 'Subject' }}
            </label>
            <select 
              v-model="filters.subject" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі предмети' : 'All subjects' }}</option>
              <option value="math">{{ language === 'uk' ? 'Математика' : 'Mathematics' }}</option>
              <option value="phys">{{ language === 'uk' ? 'Фізика' : 'Physics' }}</option>
              <option value="chem">{{ language === 'uk' ? 'Хімія' : 'Chemistry' }}</option>
              <option value="bio">{{ language === 'uk' ? 'Біологія' : 'Biology' }}</option>
              <option value="hist">{{ language === 'uk' ? 'Історія' : 'History' }}</option>
              <option value="lit">{{ language === 'uk' ? 'Література' : 'Literature' }}</option>
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
              <option value="current">{{ language === 'uk' ? 'Поточний семестр' : 'Current Semester' }}</option>
              <option value="previous">{{ language === 'uk' ? 'Попередній семестр' : 'Previous Semester' }}</option>
              <option value="academic_year">{{ language === 'uk' ? 'Навчальний рік' : 'Academic Year' }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ language === 'uk' ? 'Сортування' : 'Sort By' }}
            </label>
            <select 
              v-model="filters.sort" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="name">{{ language === 'uk' ? 'Ім\'я' : 'Name' }}</option>
              <option value="hours">{{ language === 'uk' ? 'Години (за зростанням)' : 'Hours (ascending)' }}</option>
              <option value="hours_desc">{{ language === 'uk' ? 'Години (за спаданням)' : 'Hours (descending)' }}</option>
              <option value="classes">{{ language === 'uk' ? 'Кількість класів' : 'Number of Classes' }}</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
          {{ language === 'uk' ? 'Навантаження вчителів' : 'Teacher Workload' }}
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
      
      <!-- Workload summary statistics cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm text-indigo-600 font-medium">
                {{ language === 'uk' ? 'Середнє навантаження' : 'Average Workload' }}
              </p>
              <h4 class="text-2xl font-bold text-indigo-800 mt-1">22.8 {{ language === 'uk' ? 'год/тиждень' : 'hrs/week' }}</h4>
            </div>
            <div class="p-2 bg-indigo-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="text-xs text-indigo-600 mt-3">
            {{ language === 'uk' ? 'На основі даних всіх вчителів' : 'Based on data from all teachers' }}
          </div>
        </div>
        
        <div class="bg-amber-50 border border-amber-100 rounded-lg p-4">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm text-amber-600 font-medium">
                {{ language === 'uk' ? 'Загальна кількість класів' : 'Total Classes' }}
              </p>
              <h4 class="text-2xl font-bold text-amber-800 mt-1">36</h4>
            </div>
            <div class="p-2 bg-amber-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
          <div class="text-xs text-amber-600 mt-3">
            {{ language === 'uk' ? 'Активні групи в поточному семестрі' : 'Active groups in current semester' }}
          </div>
        </div>
        
        <div class="bg-teal-50 border border-teal-100 rounded-lg p-4">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm text-teal-600 font-medium">
                {{ language === 'uk' ? 'Найбільше навантаження' : 'Highest Workload' }}
              </p>
              <h4 class="text-2xl font-bold text-teal-800 mt-1">28.5 {{ language === 'uk' ? 'год/тиждень' : 'hrs/week' }}</h4>
            </div>
            <div class="p-2 bg-teal-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
          </div>
          <div class="text-xs text-teal-600 mt-3">
            {{ language === 'uk' ? 'Максимальне значення серед вчителів' : 'Maximum value among teachers' }}
          </div>
        </div>
      </div>
      
      <!-- Teacher workload table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Вчитель' : 'Teacher' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Предмети' : 'Subjects' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Кількість класів' : 'Number of Classes' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Години на тиждень' : 'Hours per Week' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Завантаженість' : 'Workload Level' }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="teacher in filteredTeachers" :key="teacher.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                      {{ teacher.name.charAt(0) }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ teacher.name }}</div>
                    <div class="text-sm text-gray-500">{{ teacher.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex flex-wrap gap-1">
                  <span v-for="subject in teacher.subjects" :key="subject" 
                        class="px-2 py-1 text-xs rounded-full"
                        :class="getSubjectClass(subject)">
                    {{ getSubjectName(subject) }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ teacher.classCount }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="mr-2 w-16 bg-gray-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full" 
                      :class="{
                        'bg-green-600': teacher.hoursPerWeek <= 20,
                        'bg-yellow-500': teacher.hoursPerWeek > 20 && teacher.hoursPerWeek <= 25,
                        'bg-red-500': teacher.hoursPerWeek > 25
                      }"
                      :style="{ width: (teacher.hoursPerWeek / 30) * 100 + '%' }"></div>
                  </div>
                  <span class="text-sm font-medium text-gray-900">{{ teacher.hoursPerWeek }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full" 
                      :class="{
                        'bg-green-100 text-green-800': teacher.hoursPerWeek <= 20,
                        'bg-yellow-100 text-yellow-800': teacher.hoursPerWeek > 20 && teacher.hoursPerWeek <= 25,
                        'bg-red-100 text-red-800': teacher.hoursPerWeek > 25
                      }">
                  {{ getWorkloadLevel(teacher.hoursPerWeek) }}
                </span>
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
        subject: '',
        period: 'current',
        sort: 'name'
      },
      teachers: []
    }
  },
  created() {
    // Load real data from props
    if (this.$page.props.teacherData) {
      this.teachers = this.$page.props.teacherData.map(teacher => ({
        id: teacher.id,
        name: teacher.name,
        email: teacher.email || '',
        subjects: teacher.subject ? [teacher.subject] : [],
        classCount: teacher.totalClasses,
        hoursPerWeek: teacher.totalHours
      }));
    }
  },
  computed: {
    filteredTeachers() {
      let result = this.teachers;
      
      // Filter by subject
      if (this.filters.subject) {
        result = result.filter(teacher => 
          teacher.subjects.includes(this.filters.subject)
        );
      }
      
      // Sort results
      result = result.sort((a, b) => {
        if (this.filters.sort === 'name') {
          return a.name.localeCompare(b.name);
        } else if (this.filters.sort === 'hours') {
          return a.hoursPerWeek - b.hoursPerWeek;
        } else if (this.filters.sort === 'hours_desc') {
          return b.hoursPerWeek - a.hoursPerWeek;
        } else if (this.filters.sort === 'classes') {
          return b.classCount - a.classCount;
        }
        return 0;
      });
      
      return result;
    },
    translatedTeachers() {
      if (this.language === 'uk') {
        return this.filteredTeachers;
      } else {
        // Translate teacher names to English for demo purposes
        return this.filteredTeachers.map(teacher => {
          const translatedNames = {
            'Іванова Олена Петрівна': 'Ivanova Olena',
            'Петренко Андрій Миколайович': 'Petrenko Andriy',
            'Коваленко Марія Степанівна': 'Kovalenko Maria',
            'Шевченко Олег Іванович': 'Shevchenko Oleg',
            'Бондаренко Ірина Василівна': 'Bondarenko Iryna',
            'Мельник Сергій Олександрович': 'Melnyk Serhiy'
          };
          
          return {
            ...teacher,
            name: translatedNames[teacher.name] || teacher.name
          };
        });
      }
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
    getSubjectName(subjectCode) {
      const subjects = {
        'math': this.language === 'uk' ? 'Математика' : 'Mathematics',
        'phys': this.language === 'uk' ? 'Фізика' : 'Physics',
        'chem': this.language === 'uk' ? 'Хімія' : 'Chemistry',
        'bio': this.language === 'uk' ? 'Біологія' : 'Biology',
        'hist': this.language === 'uk' ? 'Історія' : 'History',
        'lit': this.language === 'uk' ? 'Література' : 'Literature'
      };
      
      return subjects[subjectCode] || subjectCode;
    },
    getSubjectClass(subjectCode) {
      const classes = {
        'math': 'bg-blue-100 text-blue-800',
        'phys': 'bg-indigo-100 text-indigo-800',
        'chem': 'bg-purple-100 text-purple-800',
        'bio': 'bg-green-100 text-green-800',
        'hist': 'bg-amber-100 text-amber-800',
        'lit': 'bg-pink-100 text-pink-800'
      };
      
      return classes[subjectCode] || 'bg-gray-100 text-gray-800';
    },
    getWorkloadLevel(hours) {
      if (hours <= 20) {
        return this.language === 'uk' ? 'Нормальне' : 'Normal';
      } else if (hours <= 25) {
        return this.language === 'uk' ? 'Підвищене' : 'Elevated';
      } else {
        return this.language === 'uk' ? 'Високе' : 'High';
      }
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
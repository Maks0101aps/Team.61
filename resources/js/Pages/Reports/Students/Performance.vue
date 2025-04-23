<template>
  <div>
    <Head :title="language === 'uk' ? 'Звіт успішності студентів' : 'Student Performance Report'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Успішність студентів' : 'Student Performance' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Звіт успішності студентів' : 'Student Performance Report' }}
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
              {{ language === 'uk' ? 'Рівень успішності' : 'Performance Level' }}
            </label>
            <select 
              v-model="filters.level" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі' : 'All' }}</option>
              <option value="high">{{ language === 'uk' ? 'Високий (10-12)' : 'High (10-12)' }}</option>
              <option value="medium">{{ language === 'uk' ? 'Достатній (7-9)' : 'Medium (7-9)' }}</option>
              <option value="low">{{ language === 'uk' ? 'Середній (4-6)' : 'Low (4-6)' }}</option>
              <option value="critical">{{ language === 'uk' ? 'Критичний (1-3)' : 'Critical (1-3)' }}</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
          {{ language === 'uk' ? 'Результати успішності' : 'Performance Results' }}
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
      
      <!-- Performance charts and graphs -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Class average by subject -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200">
          <h4 class="text-base font-semibold text-gray-800 mb-4">
            {{ language === 'uk' ? 'Середній бал за предметами' : 'Average Score by Subject' }}
          </h4>
          <div class="space-y-4">
            <div v-for="subject in subjectPerformance" :key="subject.id">
              <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-gray-700">{{ getSubjectName(subject.id) }}</span>
                <span class="text-sm font-medium" :class="getGradeColor(subject.average)">{{ subject.average }}</span>
              </div>
              <div class="w-full h-2 bg-gray-200 rounded-full">
                <div class="h-full rounded-full" 
                  :class="getGradeColorBar(subject.average)"
                  :style="{ width: (subject.average / 12) * 100 + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Performance distribution chart -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200">
          <h4 class="text-base font-semibold text-gray-800 mb-4">
            {{ language === 'uk' ? 'Розподіл успішності' : 'Performance Distribution' }}
          </h4>
          <div class="space-y-3">
            <div>
              <div class="flex justify-between mb-1">
                <span class="text-sm text-gray-700">{{ language === 'uk' ? 'Високий (10-12)' : 'High (10-12)' }}</span>
                <span class="text-sm font-medium text-gray-700">{{ performanceDistribution.high }} {{ language === 'uk' ? 'студентів' : 'students' }}</span>
              </div>
              <div class="w-full h-4 bg-emerald-100 rounded-full">
                <div class="h-full rounded-full bg-emerald-500" style="width: 35%"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between mb-1">
                <span class="text-sm text-gray-700">{{ language === 'uk' ? 'Достатній (7-9)' : 'Medium (7-9)' }}</span>
                <span class="text-sm font-medium text-gray-700">{{ performanceDistribution.medium }} {{ language === 'uk' ? 'студентів' : 'students' }}</span>
              </div>
              <div class="w-full h-4 bg-blue-100 rounded-full">
                <div class="h-full rounded-full bg-blue-500" style="width: 45%"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between mb-1">
                <span class="text-sm text-gray-700">{{ language === 'uk' ? 'Середній (4-6)' : 'Low (4-6)' }}</span>
                <span class="text-sm font-medium text-gray-700">{{ performanceDistribution.low }} {{ language === 'uk' ? 'студентів' : 'students' }}</span>
              </div>
              <div class="w-full h-4 bg-amber-100 rounded-full">
                <div class="h-full rounded-full bg-amber-500" style="width: 15%"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between mb-1">
                <span class="text-sm text-gray-700">{{ language === 'uk' ? 'Критичний (1-3)' : 'Critical (1-3)' }}</span>
                <span class="text-sm font-medium text-gray-700">{{ performanceDistribution.critical }} {{ language === 'uk' ? 'студентів' : 'students' }}</span>
              </div>
              <div class="w-full h-4 bg-red-100 rounded-full">
                <div class="h-full rounded-full bg-red-500" style="width: 5%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Student performance table -->
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
              <th v-for="subject in displayedSubjects" :key="subject" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ getSubjectName(subject) }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Середній бал' : 'Average' }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="student in filteredStudents" :key="student.id">
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
              <td v-for="subject in displayedSubjects" :key="subject" class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium" :class="getGradeColor(student.grades[subject])">
                  {{ student.grades[subject] }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <span class="text-sm font-medium" :class="getGradeColor(student.average)">
                    {{ student.average }}
                  </span>
                  <span v-if="student.trend > 0" class="ml-2 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                  </span>
                  <span v-else-if="student.trend < 0" class="ml-2 text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </div>
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
        subject: '',
        period: 'current',
        level: ''
      },
      students: [],
      subjects: [],
      subjectPerformance: [],
      performanceDistribution: {
        high: 0,
        medium: 0,
        low: 0,
        critical: 0
      }
    }
  },
  created() {
    
    if (this.$page.props.studentData) {
      this.students = this.$page.props.studentData;
    }
    
    if (this.$page.props.subjects) {
      this.subjects = this.$page.props.subjects;
    }
    
    if (this.$page.props.statistics) {
      
      if (this.subjects && this.subjects.length > 0) {
        this.subjectPerformance = this.subjects.map(subject => {
          return {
            id: subject,
            name: this.getSubjectName(subject),
            average: this.$page.props.statistics.subjectAverages[subject] || 0
          };
        });
      }
      
      
      if (this.students && this.students.length > 0) {
        let high = 0;
        let medium = 0;
        let low = 0;
        let critical = 0;
        
        this.students.forEach(student => {
          const avg = student.averageGrade.current;
          if (avg >= 10) {
            high++;
          } else if (avg >= 7) {
            medium++;
          } else if (avg >= 4) {
            low++;
          } else {
            critical++;
          }
        });
        
        this.performanceDistribution = {
          high,
          medium,
          low,
          critical
        };
      }
    }
  },
  computed: {
    displayedSubjects() {
      if (this.filters.subject) {
        return [this.filters.subject];
      }
      return ['math', 'phys', 'chem', 'bio', 'hist', 'lit'];
    },
    filteredStudents() {
      let result = this.students;
      
      
      if (this.filters.group) {
        result = result.filter(student => 
          student.group === `Група ${this.filters.group}`
        );
      }
      
      
      if (this.filters.level) {
        result = result.filter(student => {
          const avg = student.average;
          if (this.filters.level === 'high') {
            return avg >= 10 && avg <= 12;
          } else if (this.filters.level === 'medium') {
            return avg >= 7 && avg < 10;
          } else if (this.filters.level === 'low') {
            return avg >= 4 && avg < 7;
          } else if (this.filters.level === 'critical') {
            return avg >= 1 && avg < 4;
          }
          return true;
        });
      }
      
      
      if (this.language === 'uk') {
        return result;
      } else {
        return result.map(student => {
          const translatedNames = {
            'Петро Іваненко': 'Petro Ivanenko',
            'Марія Коваленко': 'Maria Kovalenko',
            'Олександр Шевченко': 'Oleksandr Shevchenko',
            'Анна Мельник': 'Anna Melnyk',
            'Микола Бондаренко': 'Mykola Bondarenko',
            'Юлія Ткаченко': 'Yulia Tkachenko'
          };
          
          return {
            ...student,
            name: translatedNames[student.name] || student.name,
            group: student.group.replace('Група', 'Group')
          };
        });
      }
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    
    
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
    getGradeColor(grade) {
      if (grade >= 10) {
        return 'text-emerald-600';
      } else if (grade >= 7) {
        return 'text-blue-600';
      } else if (grade >= 4) {
        return 'text-amber-600';
      } else {
        return 'text-red-600';
      }
    },
    getGradeColorBar(grade) {
      if (grade >= 10) {
        return 'bg-emerald-500';
      } else if (grade >= 7) {
        return 'bg-blue-500';
      } else if (grade >= 4) {
        return 'bg-amber-500';
      } else {
        return 'bg-red-500';
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
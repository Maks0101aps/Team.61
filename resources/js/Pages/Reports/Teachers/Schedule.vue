<template>
  <div>
    <Head :title="language === 'uk' ? 'Розклад вчителів' : 'Teacher Schedule Report'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Розклад вчителів' : 'Teacher Schedule' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Звіт розкладу вчителів' : 'Teacher Schedule Report' }}
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
              {{ language === 'uk' ? 'Вчитель' : 'Teacher' }}
            </label>
            <select 
              v-model="filters.teacher" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі вчителі' : 'All teachers' }}</option>
              <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                {{ getTeacherName(teacher) }}
              </option>
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
              {{ language === 'uk' ? 'День тижня' : 'Day of Week' }}
            </label>
            <select 
              v-model="filters.day" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі дні' : 'All days' }}</option>
              <option value="monday">{{ language === 'uk' ? 'Понеділок' : 'Monday' }}</option>
              <option value="tuesday">{{ language === 'uk' ? 'Вівторок' : 'Tuesday' }}</option>
              <option value="wednesday">{{ language === 'uk' ? 'Середа' : 'Wednesday' }}</option>
              <option value="thursday">{{ language === 'uk' ? 'Четвер' : 'Thursday' }}</option>
              <option value="friday">{{ language === 'uk' ? 'П\'ятниця' : 'Friday' }}</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
          {{ language === 'uk' ? 'Розклад занять' : 'Class Schedule' }}
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
      
      <!-- Weekly schedule view -->
      <div class="relative overflow-x-auto mb-8">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                {{ language === 'uk' ? 'Урок' : 'Period' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Понеділок' : 'Monday' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Вівторок' : 'Tuesday' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Середа' : 'Wednesday' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'Четвер' : 'Thursday' }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ language === 'uk' ? 'П\'ятниця' : 'Friday' }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="period in periods" :key="period.id">
              <td class="px-6 py-4 whitespace-nowrap bg-gray-50">
                <div class="text-sm font-medium text-gray-900">{{ period.number }}</div>
                <div class="text-xs text-gray-500">{{ period.time }}</div>
              </td>
              
              <td v-for="day in days" :key="day" class="px-4 py-3">
                <template v-if="getScheduleItem(period.id, day)">
                  <div 
                    class="p-2 rounded-lg text-sm" 
                    :class="getSubjectColorClass(getScheduleItem(period.id, day).subject)"
                  >
                    <div class="font-medium">
                      {{ getSubjectName(getScheduleItem(period.id, day).subject) }}
                    </div>
                    <div class="text-xs mt-1">
                      {{ language === 'uk' ? 'Клас' : 'Class' }}: {{ getScheduleItem(period.id, day).class }}
                    </div>
                    <div class="text-xs mt-1">
                      {{ language === 'uk' ? 'Каб.' : 'Room' }}: {{ getScheduleItem(period.id, day).room }}
                    </div>
                  </div>
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Teacher schedule list -->
      <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
          {{ language === 'uk' ? 'Детальний розклад вчителів' : 'Detailed Teacher Schedule' }}
        </h3>
        
        <div v-for="teacher in filteredTeachers" :key="teacher.id" class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
          <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
            <h4 class="text-base font-medium text-gray-900">{{ getTeacherName(teacher) }}</h4>
            <p class="text-sm text-gray-500">{{ getSubjectName(teacher.subject) }}</p>
          </div>
          
          <div class="p-4">
            <div v-for="scheduleDay in getTeacherSchedule(teacher.id)" :key="scheduleDay.day" class="mb-4 last:mb-0">
              <h5 class="text-sm font-medium text-gray-700 mb-2">
                {{ getDayName(scheduleDay.day) }}
              </h5>
              
              <div class="space-y-2">
                <div 
                  v-for="lesson in scheduleDay.lessons" 
                  :key="`${scheduleDay.day}-${lesson.period}`" 
                  class="flex items-center py-2 px-3 bg-gray-50 rounded-md"
                >
                  <div class="flex-shrink-0 w-12 h-12 bg-white rounded-md border border-gray-200 flex items-center justify-center">
                    <span class="text-lg font-bold text-gray-700">{{ lesson.period }}</span>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">
                      {{ lesson.time }}
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ language === 'uk' ? 'Клас' : 'Class' }}: {{ lesson.class }} | 
                      {{ language === 'uk' ? 'Кабінет' : 'Room' }}: {{ lesson.room }}
                    </div>
                  </div>
                  <div class="ml-auto">
                    <span class="px-2 py-1 text-xs rounded-full" :class="getSubjectColorClass(teacher.subject)">
                      {{ getSubjectName(teacher.subject) }}
                    </span>
                  </div>
                </div>
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
      filters: {
        teacher: '',
        subject: '',
        day: ''
      },
      days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
      periods: [
        { id: 1, number: '1', time: '08:30 - 09:15' },
        { id: 2, number: '2', time: '09:25 - 10:10' },
        { id: 3, number: '3', time: '10:30 - 11:15' },
        { id: 4, number: '4', time: '11:35 - 12:20' },
        { id: 5, number: '5', time: '12:30 - 13:15' },
        { id: 6, number: '6', time: '13:25 - 14:10' },
        { id: 7, number: '7', time: '14:20 - 15:05' }
      ],
      teachers: [],
      schedule: []
    }
  },
  created() {
    
    if (this.$page.props.days) {
      this.days = this.$page.props.days.map(day => day.toLowerCase());
    }
    
    if (this.$page.props.periods) {
      this.periods = Object.entries(this.$page.props.periods).map(([id, time]) => ({
        id: parseInt(id),
        number: id,
        time: time
      }));
    }
    
    
    if (this.$page.props.teacherSchedules) {
      this.teachers = this.$page.props.teacherSchedules.map(teacher => ({
        id: teacher.id,
        name: teacher.name,
        nameEn: teacher.name,  
        subject: teacher.subject
      }));
      
      
      let scheduleItems = [];
      
      this.$page.props.teacherSchedules.forEach(teacher => {
        const days = Object.keys(teacher.schedule);
        
        days.forEach(day => {
          const lessons = teacher.schedule[day];
          
          lessons.forEach(lesson => {
            scheduleItems.push({
              teacherId: teacher.id,
              day: day.toLowerCase(),
              period: lesson.period,
              class: lesson.class,
              room: lesson.room,
              subject: teacher.subject
            });
          });
        });
      });
      
      this.schedule = scheduleItems;
    }
  },
  computed: {
    filteredTeachers() {
      let result = this.teachers;
      
      
      if (this.filters.teacher) {
        result = result.filter(teacher => teacher.id.toString() === this.filters.teacher);
      }
      
      
      if (this.filters.subject) {
        result = result.filter(teacher => teacher.subject === this.filters.subject);
      }
      
      return result;
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
    getTeacherName(teacher) {
      return this.language === 'uk' ? teacher.name : teacher.nameEn;
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
    getDayName(day) {
      const days = {
        'monday': this.language === 'uk' ? 'Понеділок' : 'Monday',
        'tuesday': this.language === 'uk' ? 'Вівторок' : 'Tuesday',
        'wednesday': this.language === 'uk' ? 'Середа' : 'Wednesday',
        'thursday': this.language === 'uk' ? 'Четвер' : 'Thursday',
        'friday': this.language === 'uk' ? 'П\'ятниця' : 'Friday'
      };
      
      return days[day] || day;
    },
    getSubjectColorClass(subject) {
      const colors = {
        'math': 'bg-blue-100 text-blue-800',
        'phys': 'bg-indigo-100 text-indigo-800',
        'chem': 'bg-purple-100 text-purple-800',
        'bio': 'bg-green-100 text-green-800',
        'hist': 'bg-amber-100 text-amber-800',
        'lit': 'bg-rose-100 text-rose-800'
      };
      
      return colors[subject] || 'bg-gray-100 text-gray-800';
    },
    getScheduleItem(periodId, day) {
      if (this.filters.teacher) {
        const teacherSchedule = this.schedule.find(item => 
          item.teacherId.toString() === this.filters.teacher && 
          item.period === periodId && 
          item.day === day
        );
        
        if (teacherSchedule) {
          const teacher = this.teachers.find(t => t.id === teacherSchedule.teacherId);
          return {
            ...teacherSchedule,
            subject: teacher ? teacher.subject : ''
          };
        }
        
        return null;
      }
      
      
      const scheduleItems = this.schedule.filter(item => item.period === periodId && item.day === day);
      
      if (scheduleItems.length > 0) {
        
        const item = scheduleItems[0];
        const teacher = this.teachers.find(t => t.id === item.teacherId);
        
        return {
          ...item,
          subject: teacher ? teacher.subject : ''
        };
      }
      
      return null;
    },
    getTeacherSchedule(teacherId) {
      
      const teacherSchedule = this.schedule.filter(item => item.teacherId === teacherId);
      
      
      let filteredSchedule = teacherSchedule;
      if (this.filters.day) {
        filteredSchedule = filteredSchedule.filter(item => item.day === this.filters.day);
      }
      
      
      const groupedByDay = {};
      
      filteredSchedule.forEach(item => {
        if (!groupedByDay[item.day]) {
          groupedByDay[item.day] = { day: item.day, lessons: [] };
        }
        
        const period = this.periods.find(p => p.id === item.period);
        
        groupedByDay[item.day].lessons.push({
          period: item.period,
          time: period ? period.time : '',
          class: item.class,
          room: item.room
        });
      });
      
      
      Object.values(groupedByDay).forEach(daySchedule => {
        daySchedule.lessons.sort((a, b) => a.period - b.period);
      });
      
      
      const dayOrder = { monday: 1, tuesday: 2, wednesday: 3, thursday: 4, friday: 5 };
      return Object.values(groupedByDay).sort((a, b) => dayOrder[a.day] - dayOrder[b.day]);
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
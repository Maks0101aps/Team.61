<template>
  <div>
    <Head :title="language === 'uk' ? 'Звіт про майбутні заходи' : 'Upcoming Events Report'" />
    
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/reports">
            {{ language === 'uk' ? 'Звіти' : 'Reports' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Майбутні заходи' : 'Upcoming Events' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Звіт про майбутні заходи' : 'Upcoming Events Report' }}
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
              {{ language === 'uk' ? 'Тип заходу' : 'Event Type' }}
            </label>
            <select 
              v-model="filters.type" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі типи' : 'All types' }}</option>
              <option value="academic">{{ language === 'uk' ? 'Академічні' : 'Academic' }}</option>
              <option value="cultural">{{ language === 'uk' ? 'Культурні' : 'Cultural' }}</option>
              <option value="sports">{{ language === 'uk' ? 'Спортивні' : 'Sports' }}</option>
              <option value="extracurricular">{{ language === 'uk' ? 'Позакласні' : 'Extracurricular' }}</option>
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
              <option value="week">{{ language === 'uk' ? 'Наступний тиждень' : 'Next Week' }}</option>
              <option value="month">{{ language === 'uk' ? 'Наступний місяць' : 'Next Month' }}</option>
              <option value="semester">{{ language === 'uk' ? 'Поточний семестр' : 'Current Semester' }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ language === 'uk' ? 'Організатор' : 'Organizer' }}
            </label>
            <select 
              v-model="filters.organizer" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">{{ language === 'uk' ? 'Всі організатори' : 'All organizers' }}</option>
              <option value="school">{{ language === 'uk' ? 'Школа' : 'School' }}</option>
              <option value="students">{{ language === 'uk' ? 'Учнівське самоврядування' : 'Student Council' }}</option>
              <option value="teachers">{{ language === 'uk' ? 'Вчителі' : 'Teachers' }}</option>
              <option value="external">{{ language === 'uk' ? 'Зовнішні організації' : 'External Organizations' }}</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
          {{ language === 'uk' ? 'Майбутні заходи' : 'Upcoming Events' }}
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
      
      <!-- Event Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200 flex flex-col items-center">
          <div class="rounded-full bg-blue-100 p-3 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <span class="text-xl font-bold text-gray-900">{{ eventSummary.total }}</span>
          <span class="text-sm text-gray-500">{{ language === 'uk' ? 'Всього заходів' : 'Total Events' }}</span>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200 flex flex-col items-center">
          <div class="rounded-full bg-amber-100 p-3 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <span class="text-xl font-bold text-gray-900">{{ eventSummary.academic }}</span>
          <span class="text-sm text-gray-500">{{ language === 'uk' ? 'Академічні' : 'Academic' }}</span>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200 flex flex-col items-center">
          <div class="rounded-full bg-pink-100 p-3 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
          </div>
          <span class="text-xl font-bold text-gray-900">{{ eventSummary.cultural }}</span>
          <span class="text-sm text-gray-500">{{ language === 'uk' ? 'Культурні' : 'Cultural' }}</span>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow duration-200 flex flex-col items-center">
          <div class="rounded-full bg-emerald-100 p-3 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <span class="text-xl font-bold text-gray-900">{{ eventSummary.sports }}</span>
          <span class="text-sm text-gray-500">{{ language === 'uk' ? 'Спортивні' : 'Sports' }}</span>
        </div>
      </div>
      
      <!-- Events list -->
      <div class="space-y-4">
        <div v-for="event in filteredEvents" :key="event.id" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200 bg-white">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-2 md:mb-0">
              <h4 class="text-lg font-semibold text-gray-900">{{ event.title }}</h4>
              <div class="flex items-center text-sm text-gray-500 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(event.date) }}
                <span class="mx-2">•</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ event.time }}
              </div>
            </div>
            <div class="flex items-center">
              <span class="px-3 py-1 text-xs rounded-full" :class="getEventTypeClass(event.type)">
                {{ getEventTypeName(event.type) }}
              </span>
            </div>
          </div>
          <div class="mt-3 text-sm text-gray-600">
            <p>{{ event.description }}</p>
          </div>
          <div class="mt-4 flex flex-wrap items-center gap-4 text-sm">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span class="text-gray-600">{{ event.location }}</span>
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span class="text-gray-600">{{ getOrganizerName(event.organizer) }}</span>
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <span class="text-gray-600">{{ event.participants }} {{ language === 'uk' ? 'учасників' : 'participants' }}</span>
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
        type: '',
        period: 'month',
        organizer: ''
      },
      events: [
        {
          id: 1,
          title: 'Шкільна олімпіада з математики',
          description: 'Річна олімпіада з математики для учнів 5-11 класів. Переможці представлятимуть школу на міському рівні.',
          date: '2023-09-25',
          time: '10:00 - 12:00',
          location: 'Актова зала',
          type: 'academic',
          organizer: 'school',
          participants: 120
        },
        {
          id: 2,
          title: 'Концерт до Дня вчителя',
          description: 'Святковий концерт, організований учнями школи з нагоди професійного свята педагогів.',
          date: '2023-10-02',
          time: '14:00 - 16:00',
          location: 'Актова зала',
          type: 'cultural',
          organizer: 'students',
          participants: 200
        },
        {
          id: 3,
          title: 'Міський чемпіонат з футболу',
          description: 'Фінальні змагання з футболу серед шкільних команд міста. Наша команда є діючим чемпіоном турніру.',
          date: '2023-10-10',
          time: '15:00 - 17:00',
          location: 'Спортивне поле',
          type: 'sports',
          organizer: 'external',
          participants: 80
        },
        {
          id: 4,
          title: 'Відкритий урок з історії України',
          description: 'Відкритий урок з історії України присвячений Дню захисника України. Запрошені ветерани АТО та ООС.',
          date: '2023-10-13',
          time: '12:00 - 13:30',
          location: 'Клас №215',
          type: 'academic',
          organizer: 'teachers',
          participants: 60
        },
        {
          id: 5,
          title: 'Осінній бал',
          description: 'Традиційний шкільний осінній бал для учнів старших класів з нагоди завершення першої чверті.',
          date: '2023-10-27',
          time: '17:00 - 20:00',
          location: 'Актова зала',
          type: 'cultural',
          organizer: 'students',
          participants: 150
        },
        {
          id: 6,
          title: 'Екскурсія до Музею науки',
          description: 'Пізнавальна екскурсія для учнів 8-9 класів до Музею науки та технологій.',
          date: '2023-11-05',
          time: '09:00 - 13:00',
          location: 'Музей науки',
          type: 'extracurricular',
          organizer: 'teachers',
          participants: 45
        }
      ],
      eventSummary: {
        total: 12,
        academic: 5,
        cultural: 3,
        sports: 2,
        extracurricular: 2
      }
    }
  },
  computed: {
    filteredEvents() {
      let result = this.events;
      
      
      if (this.filters.type) {
        result = result.filter(event => event.type === this.filters.type);
      }
      
      
      if (this.filters.organizer) {
        result = result.filter(event => event.organizer === this.filters.organizer);
      }
      
      
      if (this.language === 'en') {
        return result.map(event => {
          const translatedTitles = {
            'Шкільна олімпіада з математики': 'School Mathematics Olympiad',
            'Концерт до Дня вчителя': 'Teacher\'s Day Concert',
            'Міський чемпіонат з футболу': 'City Football Championship',
            'Відкритий урок з історії України': 'Open Lesson on Ukrainian History',
            'Осінній бал': 'Autumn Ball',
            'Екскурсія до Музею науки': 'Excursion to the Science Museum'
          };
          
          const translatedDesc = {
            'Річна олімпіада з математики для учнів 5-11 класів. Переможці представлятимуть школу на міському рівні.': 
              'Annual mathematics olympiad for students in grades 5-11. Winners will represent the school at the city level.',
            'Святковий концерт, організований учнями школи з нагоди професійного свята педагогів.': 
              'Festive concert organized by school students on the occasion of the professional holiday of teachers.',
            'Фінальні змагання з футболу серед шкільних команд міста. Наша команда є діючим чемпіоном турніру.': 
              'Final football competition among school teams in the city. Our team is the current champion of the tournament.',
            'Відкритий урок з історії України присвячений Дню захисника України. Запрошені ветерани АТО та ООС.': 
              'Open lesson on the history of Ukraine dedicated to the Defender of Ukraine Day. ATO and JFO veterans are invited.',
            'Традиційний шкільний осінній бал для учнів старших класів з нагоди завершення першої чверті.': 
              'Traditional school autumn ball for high school students to mark the end of the first quarter.',
            'Пізнавальна екскурсія для учнів 8-9 класів до Музею науки та технологій.': 
              'Educational excursion for students in grades 8-9 to the Museum of Science and Technology.'
          };
          
          const translatedLocations = {
            'Актова зала': 'Assembly Hall',
            'Спортивне поле': 'Sports Field',
            'Клас №215': 'Classroom #215',
            'Музей науки': 'Science Museum'
          };
          
          return {
            ...event,
            title: translatedTitles[event.title] || event.title,
            description: translatedDesc[event.description] || event.description,
            location: translatedLocations[event.location] || event.location
          };
        });
      } else {
        return result;
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
    formatDate(dateStr) {
      const date = new Date(dateStr);
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return date.toLocaleDateString(this.language === 'uk' ? 'uk-UA' : 'en-US', options);
    },
    getEventTypeName(type) {
      const types = {
        'academic': this.language === 'uk' ? 'Академічний' : 'Academic',
        'cultural': this.language === 'uk' ? 'Культурний' : 'Cultural',
        'sports': this.language === 'uk' ? 'Спортивний' : 'Sports',
        'extracurricular': this.language === 'uk' ? 'Позакласний' : 'Extracurricular'
      };
      
      return types[type] || type;
    },
    getEventTypeClass(type) {
      const classes = {
        'academic': 'bg-amber-100 text-amber-800',
        'cultural': 'bg-pink-100 text-pink-800',
        'sports': 'bg-emerald-100 text-emerald-800',
        'extracurricular': 'bg-purple-100 text-purple-800'
      };
      
      return classes[type] || 'bg-gray-100 text-gray-800';
    },
    getOrganizerName(organizer) {
      const organizers = {
        'school': this.language === 'uk' ? 'Школа' : 'School',
        'students': this.language === 'uk' ? 'Учнівське самоврядування' : 'Student Council',
        'teachers': this.language === 'uk' ? 'Вчителі' : 'Teachers',
        'external': this.language === 'uk' ? 'Зовнішні організації' : 'External Organizations'
      };
      
      return organizers[organizer] || organizer;
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
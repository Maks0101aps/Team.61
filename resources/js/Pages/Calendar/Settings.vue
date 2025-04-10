<template>
  <div>
    <Head :title="localLanguage === 'uk' ? 'Налаштування календаря' : 'Calendar Settings'" />
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ localLanguage === 'uk' ? 'Налаштування календаря' : 'Calendar Settings' }}
        </h1>
        
        <Link 
          href="/calendar" 
          class="flex items-center px-4 py-2 text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
          </svg>
          <span>{{ localLanguage === 'uk' ? 'Назад до календаря' : 'Back to Calendar' }}</span>
        </Link>
      </div>
      
      <!-- Flash Messages -->
      <div v-if="$page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
        <p>{{ $page.props.flash.success }}</p>
      </div>
      
      <div v-if="$page.props.flash.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow">
        <p>{{ $page.props.flash.error }}</p>
      </div>
      
      <!-- Main Settings -->
      <div class="bg-white rounded-xl p-6 shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4 text-blue-900">
          {{ localLanguage === 'uk' ? 'Синхронізація з Google Календарем' : 'Google Calendar Sync' }}
        </h2>
        
        <p class="mb-6 text-gray-700">
          {{ localLanguage === 'uk' ? 
            'Підключіть свій Google Календар, щоб синхронізувати шкільні події з вашим особистим календарем.' : 
            'Connect your Google Calendar to sync school events with your personal calendar.' 
          }}
        </p>
        
        <div v-if="!isGoogleConnected" class="mb-6">
          <a 
            :href="'/google-calendar/auth'"
            class="inline-flex items-center px-5 py-3 text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 0C5.372 0 0 5.373 0 12s5.372 12 12 12c6.627 0 12-5.373 12-12S18.627 0 12 0zm.14 19.018c-3.868 0-7-3.14-7-7.018 0-3.878 3.132-7.018 7-7.018 1.89 0 3.47.697 4.682 1.829l-1.974 1.978v-.004c-.735-.702-1.667-1.062-2.708-1.062-2.31 0-4.187 1.956-4.187 4.273 0 2.315 1.877 4.277 4.187 4.277 2.096 0 3.522-1.202 3.816-2.852H12.14v-2.737h6.585c.088.47.125.96.125 1.497 0 4.007-2.747 6.837-6.71 6.837z" />
            </svg>
            {{ localLanguage === 'uk' ? 'Підключити Google Календар' : 'Connect Google Calendar' }}
          </a>
        </div>
        
        <div v-else>
          <div class="flex items-center mb-6">
            <span class="w-4 h-4 bg-green-500 rounded-full mr-2"></span>
            <span class="text-green-700 font-medium">
              {{ localLanguage === 'uk' ? 'Підключено до Google Календаря' : 'Connected to Google Calendar' }}
            </span>
          </div>
          
          <div class="flex flex-wrap gap-3">
            <a 
              :href="'/google-calendar/sync-to'"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              {{ localLanguage === 'uk' ? 'Експортувати події до Google' : 'Export Events to Google' }}
            </a>
            
            <a 
              :href="'/google-calendar/sync-from'"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              {{ localLanguage === 'uk' ? 'Імпортувати події з Google' : 'Import Events from Google' }}
            </a>
            
            <a 
              :href="'/google-calendar/disconnect'"
              class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg shadow-md text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all duration-200 transform hover:scale-105">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
              </svg>
              {{ localLanguage === 'uk' ? 'Відключити' : 'Disconnect' }}
            </a>
          </div>
        </div>
      </div>
      
      <!-- Instructions Panel -->
      <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
        <h3 class="text-lg font-bold mb-3 text-blue-900">
          {{ localLanguage === 'uk' ? 'Як це працює?' : 'How does it work?' }}
        </h3>
        
        <ul class="list-disc pl-5 space-y-2 text-blue-800">
          <li>
            {{ localLanguage === 'uk' ? 
              'Підключіть свій Google Календар, натиснувши кнопку вище.' : 
              'Connect your Google Calendar by clicking the button above.' 
            }}
          </li>
          <li>
            {{ localLanguage === 'uk' ? 
              'Експортуйте шкільні події до свого Google Календаря.' : 
              'Export school events to your Google Calendar.' 
            }}
          </li>
          <li>
            {{ localLanguage === 'uk' ? 
              'Імпортуйте події з Google Календаря до шкільного календаря.' : 
              'Import events from your Google Calendar to the school calendar.' 
            }}
          </li>
          <li>
            {{ localLanguage === 'uk' ? 
              'Усі події будуть доступні на всіх ваших пристроях, де ви використовуєте Google Календар.' : 
              'All events will be available on all your devices where you use Google Calendar.' 
            }}
          </li>
        </ul>
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
    Link
  },
  layout: Layout,
  props: {
    isGoogleConnected: {
      type: Boolean,
      required: true
    },
    language: {
      type: String,
      default: () => localStorage.getItem('language') || 'uk'
    }
  },
  data() {
    return {
      localLanguage: this.language
    }
  }
}
</script> 
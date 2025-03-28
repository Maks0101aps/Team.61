<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:shrink-0">
          <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-[#6CB4EE] to-[#4A90E2] md:shrink-0 md:justify-center md:w-64 shadow-lg">
            <div class="flex items-center space-x-4">
              <Link href="/" class="flex items-center group transition-transform duration-300 hover:scale-105">
                <img src="/images/school-calendar-logo.svg" alt="Логотип" class="h-12 w-12 rounded-full shadow-lg transform transition-all duration-300 group-hover:shadow-xl" />
                <span class="text-white text-xl font-bold ml-3 group-hover:text-blue-100">
                  {{ __('school_calendar', language === 'uk' ? 'Шкільний календар' : 'School Calendar') }}
                </span>
              </Link>
            </div>

            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <button class="text-white hover:text-blue-100 focus:outline-none transition-colors duration-200">
                  <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                  </svg>
                </button>
              </template>
              <template #dropdown>
                <div class="mt-2 px-8 py-4 bg-white rounded-lg shadow-xl">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md flex items-center justify-between p-4 w-full bg-white border-b shadow-sm md:px-12 md:py-0">
            <div class="mr-4 mt-1 text-blue-900 font-medium">
              <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                {{ auth.user.role ? (language === 'uk' ? 
                    (auth.user.role === 'teacher' ? 'Вчитель' : 
                     auth.user.role === 'student' ? 'Учень' : 
                     auth.user.role === 'parent' ? (parentType === 'mother' ? 'Мати' : 'Батько') : auth.user.role) : 
                    (auth.user.role === 'teacher' ? 'Teacher' : 
                     auth.user.role === 'student' ? 'Student' : 
                     auth.user.role === 'parent' ? (parentType === 'mother' ? 'Mother' : 'Father') : auth.user.role)) : '' }}
              </span>
            </div>
            <div class="flex items-center space-x-6">
              <div class="hidden md:flex items-center">
                <div class="inline-flex rounded-lg shadow-sm" role="group">
                  <button @click="setLanguage('uk')" 
                          type="button" 
                          class="px-4 py-2 text-sm font-medium transition-all duration-200"
                          :class="language === 'uk' 
                            ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' 
                            : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-700 border border-gray-200'">
                    UA
                  </button>
                  <button @click="setLanguage('en')" 
                          type="button" 
                          class="px-4 py-2 text-sm font-medium transition-all duration-200"
                          :class="language === 'en' 
                            ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' 
                            : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-700 border border-gray-200'">
                    EN
                  </button>
                </div>
              </div>
              <dropdown class="mt-1" placement="bottom-end">
                <template #default>
                  <div class="group flex items-center cursor-pointer select-none">
                    <div class="mr-1 text-gray-700 group-hover:text-blue-600 transition-colors duration-200">
                      <span>{{ auth.user.first_name }}</span>
                      <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span>
                    </div>
                    <icon class="w-5 h-5 fill-gray-700 group-hover:fill-blue-600 transition-colors duration-200" name="cheveron-down" />
                  </div>
                </template>
                <template #dropdown>
                  <div class="mt-2 py-2 bg-white rounded-lg shadow-xl border border-gray-100">
                    <Link class="block px-6 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200" 
                          :href="`/users/${auth.user.id}/edit`">
                      {{ __('profile', language === 'uk' ? 'Мій профіль' : 'My Profile') }}
                    </Link>
                    <Link class="block px-6 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200" 
                          href="/users">
                      {{ __('users', language === 'uk' ? 'Користувачі' : 'Users') }}
                    </Link>
                    <div class="border-t border-gray-100 my-1"></div>
                    <Link class="block px-6 py-2 w-full text-left hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200" 
                          href="/logout" 
                          method="delete" 
                          as="button">
                      {{ __('logout', language === 'uk' ? 'Вийти' : 'Logout') }}
                    </Link>
                  </div>
                </template>
              </dropdown>
            </div>
          </div>
        </div>
        <div class="md:flex md:grow md:overflow-hidden">
          <div class="hidden shrink-0 p-6 w-64 bg-gradient-to-b from-[#4A90E2] to-[#357ABD] overflow-y-auto md:block">
            <main-menu />
          </div>
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto bg-gradient-to-br from-blue-50 to-gray-100" scroll-region>
            <flash-messages />
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl">
              <template v-if="$slots.default">
                <slot />
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Logo from '@/Shared/Logo.vue'
import Dropdown from '@/Shared/Dropdown.vue'
import MainMenu from '@/Shared/MainMenu.vue'
import FlashMessages from '@/Shared/FlashMessages.vue'

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    Logo,
    MainMenu,
  },
  props: {
    auth: Object,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk', // Default to Ukrainian
      parentType: 'mother', // Default value
    }
  },
  methods: {
    setLanguage(lang) {
      this.language = lang
      localStorage.setItem('language', lang)
      
      // Set a cookie for server-side language detection
      const date = new Date();
      date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
      document.cookie = `language=${lang}; path=/; expires=${date.toUTCString()}; SameSite=Lax`;
      
      // Use the event bus to notify all components
      if (this.$languageEventBus) {
        this.$languageEventBus.emit('language-changed', lang)
      }
      
      // Don't reload the page, let the event bus handle the changes
      // window.location.reload()
    }
  },
  mounted() {
    // Ensure we have a language set in localStorage
    if (!localStorage.getItem('language')) {
      localStorage.setItem('language', 'uk')
    }
    
    // Fetch parent type if the user is a parent
    if (this.auth.user.role === 'parent') {
      fetch(`/api/parent-type/${this.auth.user.id}`)
        .then(response => response.json())
        .then(data => {
          if (data.parentType) {
            this.parentType = data.parentType;
          }
        })
        .catch(error => {
          console.error('Error fetching parent type:', error);
        });
    }
  }
}
</script>

<style>
/* Добавляем плавные переходы для всех интерактивных элементов */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Улучшаем внешний вид скроллбара */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #6CB4EE;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #4A90E2;
}
</style>

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
                  <div class="mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                      <span class="text-sm font-medium text-gray-600">{{ language === 'uk' ? 'Мова' : 'Language' }}</span>
                      <theme-toggle />
                    </div>
                    <div class="flex rounded-full shadow-md overflow-hidden">
                      <button @click="setLanguage('uk')" 
                              type="button" 
                              class="relative flex-1 py-2 text-sm font-medium transition-all duration-300"
                              :class="language === 'uk' 
                                ? 'bg-gradient-to-r from-blue-400 to-blue-600 dark:from-blue-300 dark:to-blue-500 text-white' 
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700'">
                        <span class="relative z-10">UA</span>
                      </button>
                      <button @click="setLanguage('en')" 
                              type="button" 
                              class="relative flex-1 py-2 text-sm font-medium transition-all duration-300"
                              :class="language === 'en' 
                                ? 'bg-gradient-to-r from-blue-400 to-blue-600 dark:from-blue-300 dark:to-blue-500 text-white' 
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700'">
                        <span class="relative z-10">EN</span>
                      </button>
                    </div>
                  </div>
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md flex items-center justify-between p-4 w-full bg-white border-b shadow-sm md:px-12 md:py-0">
            <div class="mr-4 mt-1">
              <span class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-3 py-1.5 rounded-lg text-sm font-medium shadow-md">
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
              <div class="hidden md:flex items-center space-x-2">
                <div class="inline-flex rounded-full shadow-md overflow-hidden">
                  <button @click="setLanguage('uk')" 
                          type="button" 
                          class="relative px-5 py-2 text-sm font-medium transition-all duration-300 group overflow-hidden"
                          :class="language === 'uk' 
                            ? 'bg-gradient-to-r from-blue-400 to-blue-600 dark:from-blue-300 dark:to-blue-500 text-white' 
                            : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700'">
                    <span class="relative z-10">UA</span>
                  </button>
                  <button @click="setLanguage('en')" 
                          type="button" 
                          class="relative px-5 py-2 text-sm font-medium transition-all duration-300 group overflow-hidden"
                          :class="language === 'en' 
                            ? 'bg-gradient-to-r from-blue-400 to-blue-600 dark:from-blue-300 dark:to-blue-500 text-white' 
                            : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700'">
                    <span class="relative z-10">EN</span>
                  </button>
                </div>
                <!-- Theme Toggle Button -->
                <theme-toggle />
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
          <div class="hidden shrink-0 p-6 w-64 bg-gradient-to-b from-[#4A90E2] to-[#357ABD] overflow-y-auto md:block menu-sidebar" :class="{ 'slide-in': !hasVisitedBefore }">
            <main-menu />
          </div>
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto bg-gradient-to-br from-blue-50 to-gray-100" scroll-region>
            <!-- Flash Messages are now handled by the FlashMessages component -->
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
import ThemeToggle from '@/Components/ThemeToggle.vue'

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    Logo,
    MainMenu,
    ThemeToggle,
  },
  props: {
    auth: Object,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk', 
      parentType: 'mother', 
      hasVisitedBefore: localStorage.getItem('hasVisitedBefore') === 'true'
    }
  },
  methods: {
    setLanguage(lang) {
      this.language = lang
      localStorage.setItem('language', lang)
      
      
      const date = new Date()
      date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000)) 
      document.cookie = `language=${lang}; path=/; expires=${date.toUTCString()}; SameSite=Lax`;
      
      
      if (this.$languageEventBus) {
        this.$languageEventBus.emit('language-changed', lang)
      }
    }
  },
  mounted() {
    
    if (!localStorage.getItem('language')) {
      localStorage.setItem('language', 'uk')
    }
    
    
    if (!localStorage.getItem('hasVisitedBefore')) {
      localStorage.setItem('hasVisitedBefore', 'true')
    }
    
    
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
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}


.menu-sidebar {
  transform: translateX(0);
  transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.menu-sidebar.slide-in {
  animation: slideInFromLeft 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes slideInFromLeft {
  0% {
    transform: translateX(-100%);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
    opacity: 1;
  }
}


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

/* Dark mode language selector enhancements */
.dark button[class*="from-blue-300"] {
  box-shadow: 0 0 10px rgba(96, 165, 250, 0.4);
  border: 1px solid rgba(96, 165, 250, 0.3);
}

.dark button.dark\:bg-gray-800 {
  border: 1px solid rgba(55, 65, 81, 0.8);
}

.dark button.dark\:bg-gray-800:hover {
  box-shadow: 0 0 5px rgba(96, 165, 250, 0.2);
}

.dark .bg-white {
  background-color: #1e293b;
}
</style>

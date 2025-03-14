<template>
  <div>
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:shrink-0">
          <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-br from-amber-500 to-amber-600 md:shrink-0 md:justify-center md:w-56">
            <div class="flex items-center space-x-4">
              <Link href="/" class="flex items-center">
                <img src="/images/school-calendar-logo.svg" alt="Логотип" class="h-12 w-12 rounded-full shadow-md" />
                <span class="text-white text-xl font-bold ml-2">{{ __('school_calendar', language === 'uk' ? 'Шкільний календар' : 'School Calendar') }}</span>
              </Link>
            </div>

            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <button class="text-white hover:text-amber-100 focus:outline-none">
                  <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
                </button>
              </template>
              <template #dropdown>
                <div class="mt-2 px-8 py-4 bg-white rounded-lg shadow-lg">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b shadow-sm md:px-12 md:py-0">
            <div class="mr-4 mt-1 text-gray-600 font-medium">{{ auth.user.account.name }}</div>
            <div class="flex items-center space-x-4">
              <div class="hidden md:flex items-center">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                  <button @click="setLanguage('uk')" type="button" class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-l-lg hover:bg-amber-50 hover:text-amber-700 focus:z-10 focus:ring-2 focus:ring-amber-500" :class="{ 'bg-amber-100 text-amber-700': language === 'uk' }">
                    UA
                  </button>
                  <button @click="setLanguage('en')" type="button" class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-r-lg hover:bg-amber-50 hover:text-amber-700 focus:z-10 focus:ring-2 focus:ring-amber-500" :class="{ 'bg-amber-100 text-amber-700': language === 'en' }">
                    EN
                  </button>
                </div>
              </div>
              <dropdown class="mt-1" placement="bottom-end">
                <template #default>
                  <div class="group flex items-center cursor-pointer select-none">
                    <div class="mr-1 text-gray-700 group-hover:text-amber-600 focus:text-amber-600 whitespace-nowrap">
                      <span>{{ auth.user.first_name }}</span>
                      <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span>
                    </div>
                    <icon class="w-5 h-5 fill-gray-700 group-hover:fill-amber-600 focus:fill-amber-600" name="cheveron-down" />
                  </div>
                </template>
                <template #dropdown>
                  <div class="mt-2 py-2 text-sm bg-white rounded-lg shadow-xl">
                    <Link class="block px-6 py-2 hover:bg-amber-50 hover:text-amber-700" :href="`/users/${auth.user.id}/edit`">{{ __('profile', language === 'uk' ? 'Мій профіль' : 'My Profile') }}</Link>
                    <Link class="block px-6 py-2 hover:bg-amber-50 hover:text-amber-700" href="/users">{{ __('users', language === 'uk' ? 'Користувачі' : 'Users') }}</Link>
                    <div class="border-t border-gray-100 my-1"></div>
                    <Link class="block px-6 py-2 w-full text-left hover:bg-amber-50 hover:text-amber-700" href="/logout" method="delete" as="button">{{ __('logout', language === 'uk' ? 'Вийти' : 'Logout') }}</Link>
                  </div>
                </template>
              </dropdown>
            </div>
          </div>
        </div>
        <div class="md:flex md:grow md:overflow-hidden">
          <div class="hidden shrink-0 p-6 w-56 bg-amber-800 overflow-y-auto md:block">
            <main-menu />
          </div>
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto bg-gradient-to-br from-amber-50 to-gray-100" scroll-region>
            <flash-messages />
            <div class="bg-white rounded-lg shadow-sm p-6">
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
    }
  },
  methods: {
    setLanguage(lang) {
      this.language = lang
      localStorage.setItem('language', lang)
      
      // Set a cookie for server-side language detection
      document.cookie = `language=${lang}; path=/; max-age=${60*60*24*365}`;
      
      // Use the event bus to notify all components
      if (this.$languageEventBus) {
        this.$languageEventBus.emit('language-changed', lang)
      }
      
      // Reload the page to apply language changes
      window.location.reload()
    }
  },
  mounted() {
    // Ensure we have a language set in localStorage
    if (!localStorage.getItem('language')) {
      localStorage.setItem('language', 'uk')
    }
  }
}
</script>

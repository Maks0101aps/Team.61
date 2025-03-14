<template>
  <div class="py-4">
    <div class="mb-4">
      <Link class="group flex items-center py-3 px-4 rounded-md transition-colors" :class="isUrl('') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-700 hover:text-white'" href="/">
        <icon name="dashboard" class="mr-2 w-5 h-5" :class="isUrl('') ? 'fill-white' : 'fill-amber-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('home', language === 'uk' ? 'Головна Сторінка' : 'Home Page') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 px-4 rounded-md transition-colors" :class="isUrl('contacts') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-700 hover:text-white'" href="/contacts">
        <icon name="users" class="mr-2 w-5 h-5" :class="isUrl('contacts') ? 'fill-white' : 'fill-amber-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('students', language === 'uk' ? 'Студенти' : 'Students') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 px-4 rounded-md transition-colors" :class="isUrl('parents') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-700 hover:text-white'" href="/parents">
        <icon name="users" class="mr-2 w-5 h-5" :class="isUrl('parents') ? 'fill-white' : 'fill-amber-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('parents', language === 'uk' ? 'Батьки' : 'Parents') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 px-4 rounded-md transition-colors" :class="isUrl('teachers') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-700 hover:text-white'" href="/teachers">
        <icon name="users" class="mr-2 w-5 h-5" :class="isUrl('teachers') ? 'fill-white' : 'fill-amber-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('teachers', language === 'uk' ? 'Вчителі' : 'Teachers') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 px-4 rounded-md transition-colors" :class="isUrl('events') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-700 hover:text-white'" href="/events">
        <icon name="calendar" class="mr-2 w-5 h-5" :class="isUrl('events') ? 'fill-white' : 'fill-amber-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('events', language === 'uk' ? 'Події' : 'Events') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 px-4 rounded-md transition-colors" :class="isUrl('tasks') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-700 hover:text-white'" href="/tasks">
        <icon name="list" class="mr-2 w-5 h-5" :class="isUrl('tasks') ? 'fill-white' : 'fill-amber-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('tasks', language === 'uk' ? 'Завдання' : 'Tasks') }}</div>
      </Link>
    </div>
    
    <!-- Mobile Language Switcher -->
    <div class="md:hidden mt-8">
      <div class="px-4">
        <p class="text-amber-200 text-xs uppercase font-semibold mb-2">{{ language === 'uk' ? 'Мова' : 'Language' }}</p>
        <div class="flex space-x-2">
          <button @click="setLanguage('uk')" class="px-3 py-1 text-sm font-medium rounded-md" :class="language === 'uk' ? 'bg-amber-600 text-white' : 'bg-amber-700 text-amber-200 hover:bg-amber-600 hover:text-white'">
            UA
          </button>
          <button @click="setLanguage('en')" class="px-3 py-1 text-sm font-medium rounded-md" :class="language === 'en' ? 'bg-amber-600 text-white' : 'bg-amber-700 text-amber-200 hover:bg-amber-600 hover:text-white'">
            EN
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
    }
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
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
    // Listen for language changes from other components
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang
      })
    }
    
    // Also listen for storage events for backward compatibility
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.language = event.newValue
      }
    })
  }
}
</script>

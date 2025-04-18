<template>
  <div class="py-4">
    <div class="mb-6 menu-item" :style="{ animationDelay: '0.1s' }">
      <Link class="group flex items-center py-3 px-4 rounded-xl transition-all duration-200" 
            :class="isUrl('') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white'" 
            href="/">
        <icon name="dashboard" 
              class="mr-3 w-5 h-5 transition-transform duration-300 group-hover:scale-110" 
              :class="isUrl('') ? 'fill-white' : 'fill-blue-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('home', language === 'uk' ? 'Головна Сторінка' : 'Home Page') }}</div>
      </Link>
    </div>

    <div class="mb-6 menu-item" :style="{ animationDelay: '0.2s' }">
      <Link class="group flex items-center py-3 px-4 rounded-xl transition-all duration-200" 
            :class="isUrl('students') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white'" 
            href="/students">
        <icon name="user-group" 
              class="mr-3 w-5 h-5 transition-transform duration-300 group-hover:scale-110" 
              :class="isUrl('students') ? 'fill-white' : 'fill-blue-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('students', language === 'uk' ? 'Студенти' : 'Students') }}</div>
      </Link>
    </div>

    <div class="mb-6 menu-item" :style="{ animationDelay: '0.3s' }">
      <Link class="group flex items-center py-3 px-4 rounded-xl transition-all duration-200" 
            :class="isUrl('parents') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white'" 
            href="/parents">
        <icon name="home" 
              class="mr-3 w-5 h-5 transition-transform duration-300 group-hover:scale-110" 
              :class="isUrl('parents') ? 'fill-white' : 'fill-blue-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('parents', language === 'uk' ? 'Батьки' : 'Parents') }}</div>
      </Link>
    </div>

    <div class="mb-6 menu-item" :style="{ animationDelay: '0.4s' }">
      <Link class="group flex items-center py-3 px-4 rounded-xl transition-all duration-200" 
            :class="isUrl('teachers') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white'" 
            href="/teachers">
        <icon name="book-open" 
              class="mr-3 w-5 h-5 transition-transform duration-300 group-hover:scale-110" 
              :class="isUrl('teachers') ? 'fill-white' : 'fill-blue-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('teachers', language === 'uk' ? 'Вчителі' : 'Teachers') }}</div>
      </Link>
    </div>

    <div class="mb-6 menu-item" :style="{ animationDelay: '0.5s' }">
      <Link class="group flex items-center py-3 px-4 rounded-xl transition-all duration-200" 
            :class="isUrl('events') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white'" 
            href="/events">
        <icon name="calendar" 
              class="mr-3 w-5 h-5 transition-transform duration-300 group-hover:scale-110" 
              :class="isUrl('events') ? 'fill-white' : 'fill-blue-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('events', language === 'uk' ? 'Події' : 'Events') }}</div>
      </Link>
    </div>

    <div class="mb-6 menu-item" :style="{ animationDelay: '0.6s' }">
      <Link class="group flex items-center py-3 px-4 rounded-xl transition-all duration-200" 
            :class="isUrl('tasks') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white'" 
            href="/tasks">
        <icon name="list" 
              class="mr-3 w-5 h-5 transition-transform duration-300 group-hover:scale-110" 
              :class="isUrl('tasks') ? 'fill-white' : 'fill-blue-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('tasks', language === 'uk' ? 'Завдання' : 'Tasks') }}</div>
      </Link>
    </div>
    
    <div class="mb-6 menu-item" :style="{ animationDelay: '0.7s' }">
      <Link class="group flex items-center py-3 px-4 rounded-xl transition-all duration-200" 
            :class="isUrl('reports') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white'" 
            href="/reports">
        <icon name="printer" 
              class="mr-3 w-5 h-5 transition-transform duration-300 group-hover:scale-110" 
              :class="isUrl('reports') ? 'fill-white' : 'fill-blue-300 group-hover:fill-white'" />
        <div class="text-sm font-medium">{{ __('reports', language === 'uk' ? 'Звіти' : 'Reports') }}</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import { ref } from 'vue'

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
      const date = new Date()
      date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000)) // Expires in one year
      document.cookie = `language=${lang}; path=/; expires=${date.toUTCString()}; SameSite=Lax`;
      
      // Use the event bus for language change events (it will handle dispatching the custom event)
      if (this.$languageEventBus) {
        this.$languageEventBus.emit('language-changed', lang)
      }
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

<style scoped>
.menu-item {
  opacity: 0;
  transform: translateX(-20px);
  animation: fadeInRight 0.5s ease forwards;
}

@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.menu-item:hover svg {
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

/* Стили для мобильных устройств */
@media (max-width: 768px) {
  .group {
    @apply bg-blue-600/30 text-blue-100 dark:bg-gray-700/30 dark:text-gray-200;
  }
  
  .group:hover {
    @apply bg-blue-500/50 text-white dark:bg-gray-600/50 dark:text-white;
  }
  
  .group svg {
    @apply fill-blue-300 dark:fill-gray-300;
  }
  
  .group:hover svg {
    @apply fill-white dark:fill-white;
  }
}

/* Дополнительные стили для темной темы */
.dark .menu-container {
    @apply bg-gray-800;
}

.dark .menu-header {
    @apply bg-gray-800 text-white;
}

.dark .menu-item {
    @apply text-gray-300;
}

.dark .menu-item:hover {
    @apply text-white;
}

.dark .group:not(.bg-gradient-to-r) {
    @apply text-gray-300 hover:bg-gray-700 hover:text-white;
}

.dark .group.bg-gradient-to-r {
    @apply from-blue-600 to-blue-800 text-white shadow-blue-900/50;
}

.dark .text-blue-100 {
    @apply text-blue-300;
}
</style>

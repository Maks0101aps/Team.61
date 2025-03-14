<template>
  <div>
    <Head :title="localLanguage === 'uk' ? 'Календар' : 'Calendar'" />
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ localLanguage === 'uk' ? 'Календар подій' : 'Event Calendar' }}
        </h1>
      </div>

      <Calendar :events="events" :language="localLanguage" />
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Calendar from '@/Shared/Calendar.vue'

export default {
  components: {
    Head,
    Calendar,
  },
  layout: Layout,
  props: {
    events: {
      type: Array,
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
  },
  mounted() {
    // Listen for language changes using the event bus
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.localLanguage = lang
      })
    }
    
    // Also listen for storage events for backward compatibility
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.localLanguage = event.newValue
      }
    })
  }
}
</script> 
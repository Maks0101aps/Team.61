import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { __ } from './Helpers/translate'

// Create a simple event bus for language changes
const languageEventBus = {
  install: (app) => {
    app.config.globalProperties.$languageEventBus = {
      emit(event, ...args) {
        window.dispatchEvent(new CustomEvent(event, { detail: args }))
      },
      on(event, callback) {
        window.addEventListener(event, (e) => callback(...(e.detail || [])))
      },
      off(event, callback) {
        window.removeEventListener(event, callback)
      }
    }
  }
}

// Global mixin for language
const languageMixin = {
  data() {
    return {
      language: localStorage.getItem('language') || 'uk'
    }
  },
  methods: {
    // Add translation helper method
    __(key, fallback = '', replacements = {}) {
      return __(key, fallback, replacements);
    }
  },
  mounted() {
    // Listen for language changes
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.language = event.newValue
      }
    })
  }
}

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  title: title => title ? `${title} - Ping CRM` : 'Ping CRM',
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.use(languageEventBus)
    app.mixin(languageMixin)
    app.mount(el)
  },
})

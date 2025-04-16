import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { __ } from './Helpers/translate'

const languageEventBus = {
  install: (app) => {
    app.config.globalProperties.$languageEventBus = {
      emit(event, ...args) {
        if (event === 'language-changed' && args.length > 0) {
          const lang = args[0];
          window.dispatchEvent(new CustomEvent(event, { detail: [lang] }))
        } else {
          window.dispatchEvent(new CustomEvent(event, { detail: args }))
        }
      },
      on(event, callback) {
        const wrappedCallback = (e) => {
          if (event === 'language-changed') {
            const detail = e.detail;
            if (Array.isArray(detail) && detail.length > 0) {
              callback(detail[0]);
            } else if (detail && detail.language) {
              callback(detail.language);
            } else {
              callback(...(detail || []));
            }
          } else {
            callback(...(e.detail || []));
          }
        };
        
        if (!callback._wrappedCallback) {
          callback._wrappedCallback = wrappedCallback;
        }
        
        window.addEventListener(event, wrappedCallback);
      },
      off(event, callback) {
        const wrappedCallback = callback._wrappedCallback || callback;
        window.removeEventListener(event, wrappedCallback);
      }
    }
  }
}

const languageMixin = {
  data() {
    return {
      language: localStorage.getItem('language') || 'uk'
    }
  },
  methods: {
    
    __(key, fallback = '', replacements = {}) {
      return __(key, fallback, replacements);
    }
  },
  mounted() {
    
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
  title: title => title ? `${title} - School Calendar` : 'School Calendar',
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.use(languageEventBus)
    app.mixin(languageMixin)
    app.mount(el)
    
    
    window.addEventListener('inertia:flash', (event) => {
      const { type, message } = event.detail;
      if (type && message) {
        
        if (window.Inertia) {
          window.Inertia.shared.flash[type] = message;
          
          window.dispatchEvent(new Event('inertia:success'));
        }
      }
    });
  },
})

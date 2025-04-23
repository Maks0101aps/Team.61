<template>
  <svg viewBox="0 0 300 100" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="textGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#4A90E2" />
        <stop offset="100%" stop-color="#357ABD" />
      </linearGradient>
      
      <linearGradient id="textGradientDark" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#60A5FA" />
        <stop offset="100%" stop-color="#3B82F6" />
      </linearGradient>
      
      <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#6CB4EE" />
        <stop offset="50%" stop-color="#4A90E2" />
        <stop offset="100%" stop-color="#357ABD" />
      </linearGradient>
    </defs>

    <rect x="10" y="10" width="280" height="80" rx="10" fill="transparent" opacity="0.5" class="logo-background" />
    
    <text x="150" y="60" font-family="'Segoe UI', 'Roboto', sans-serif" font-size="36" font-weight="500" text-anchor="middle" :fill="isDarkMode ? '#60A5FA' : 'url(#textGradient)'" class="logo-text">{{ logoText }}</text>
    
    <rect x="50" y="85" width="200" height="2" rx="1" fill="url(#goldGradient)" opacity="0.7" />
  </svg>
</template>

<script>
export default {
  props: {
    language: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      currentLanguage: this.language || localStorage.getItem('language') || 'uk',
      isDarkMode: false
    };
  },
  computed: {
    logoText() {
      return this.currentLanguage === 'uk' ? 'Шкільний календар' : 'School Calendar';
    }
  },
  mounted() {
    window.addEventListener('language-change', this.handleLanguageChange);
    window.addEventListener('storage', this.handleStorageChange);
    window.addEventListener('theme-changed', this.handleThemeChange);
    
    this.checkDarkMode();
    
    this.intervalId = setInterval(() => {
      const storedLanguage = localStorage.getItem('language') || 'uk';
      if (storedLanguage !== this.currentLanguage) {
        this.currentLanguage = storedLanguage;
      }
      this.checkDarkMode();
    }, 1000);
  },
  beforeUnmount() {
    window.removeEventListener('language-change', this.handleLanguageChange);
    window.removeEventListener('storage', this.handleStorageChange);
    window.removeEventListener('theme-changed', this.handleThemeChange);
    clearInterval(this.intervalId);
  },
  methods: {
    handleLanguageChange(event) {
      this.currentLanguage = event.detail.language;
    },
    handleStorageChange(event) {
      if (event.key === 'language') {
        this.currentLanguage = event.newValue;
      }
      if (event.key === 'theme') {
        this.checkDarkMode();
      }
    },
    handleThemeChange(event) {
      this.isDarkMode = event.detail === 'dark';
    },
    checkDarkMode() {
      const savedTheme = localStorage.getItem('theme');
      const htmlEl = document.documentElement;
      this.isDarkMode = htmlEl.classList.contains('dark') || savedTheme === 'dark';
    }
  },
  watch: {
    language(newValue) {
      if (newValue) {
        this.currentLanguage = newValue;
      }
    }
  }
}
</script>

<style>
.dark .logo-background {
  fill: transparent !important;
  stroke: transparent !important;
  opacity: 0 !important;
}

.dark .logo-text {
  fill: #60A5FA !important;
  background: transparent !important;
  background-color: transparent !important;
  background-image: none !important;
  -webkit-text-fill-color: #60A5FA !important;
  filter: none !important;
  box-shadow: none !important;
  -webkit-box-decoration-break: clone;
  box-decoration-break: clone;
  text-shadow: none !important;
  stroke: none !important;
  border: none !important;
  outline: none !important;
}
</style>

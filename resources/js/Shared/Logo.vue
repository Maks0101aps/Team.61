<template>
  <svg viewBox="0 0 300 100" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="textGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#4A90E2" />
        <stop offset="100%" stop-color="#357ABD" />
      </linearGradient>
      
      <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#6CB4EE" />
        <stop offset="50%" stop-color="#4A90E2" />
        <stop offset="100%" stop-color="#357ABD" />
      </linearGradient>
    </defs>

    <rect x="10" y="10" width="280" height="80" rx="10" fill="#f8f8f8" opacity="0.5" />
    
    <text x="150" y="60" font-family="'Segoe UI', 'Roboto', sans-serif" font-size="36" font-weight="500" text-anchor="middle" fill="url(#textGradient)">{{ logoText }}</text>
    
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
      currentLanguage: this.language || localStorage.getItem('language') || 'uk'
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
    
    this.intervalId = setInterval(() => {
      const storedLanguage = localStorage.getItem('language') || 'uk';
      if (storedLanguage !== this.currentLanguage) {
        this.currentLanguage = storedLanguage;
      }
    }, 1000);
  },
  beforeUnmount() {
    window.removeEventListener('language-change', this.handleLanguageChange);
    window.removeEventListener('storage', this.handleStorageChange);
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

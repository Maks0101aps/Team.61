<template>
  <span>{{ translatedText }}</span>
</template>

<script>
export default {
  props: {
    key: {
      type: String,
      required: true
    },
    fallback: {
      type: String,
      default: ''
    }
  },
  computed: {
    translatedText() {
      const language = localStorage.getItem('language') || 'uk';
      
      if (this.$page.props.translations && 
          this.$page.props.translations[language] && 
          this.$page.props.translations[language][this.key]) {
        return this.$page.props.translations[language][this.key];
      }
      
      return this.fallback || this.key;
    }
  }
}
</script> 
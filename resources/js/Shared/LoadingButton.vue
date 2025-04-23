<template>
  <button
    :disabled="loading"
    :type="buttonType"
    class="flex items-center justify-center transition-all duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed"
    :class="[
      buttonClass,
      { 'relative !text-transparent': loading }
    ]"
  >
    <div v-if="loading" class="absolute inset-0 flex items-center justify-center">
      <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
    buttonClass: {
      type: String,
      default: 'px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow focus:ring-blue-500'
    },
    size: {
      type: String,
      default: 'md',
      validator: value => ['sm', 'md', 'lg'].includes(value)
    },
    variant: {
      type: String,
      default: 'primary',
      validator: value => ['primary', 'secondary', 'success', 'danger', 'warning', 'info'].includes(value)
    },
    buttonType: {
      type: String,
      default: 'button',
      validator: value => ['button', 'submit', 'reset'].includes(value)
    },
    
    type: {
      type: String,
      default: undefined
    }
  },
  computed: {
    classes() {
      let sizeClasses = {
        sm: 'py-1.5 px-3 text-sm',
        md: 'py-2.5 px-6 text-base',
        lg: 'py-3 px-8 text-lg'
      };
      
      let typeClasses = {
        primary: 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white focus:ring-blue-500',
        secondary: 'bg-gray-600 hover:bg-gray-700 active:bg-gray-800 text-white focus:ring-gray-500',
        success: 'bg-green-600 hover:bg-green-700 active:bg-green-800 text-white focus:ring-green-500',
        danger: 'bg-red-600 hover:bg-red-700 active:bg-red-800 text-white focus:ring-red-500',
        warning: 'bg-yellow-500 hover:bg-yellow-600 active:bg-yellow-700 text-white focus:ring-yellow-400',
        info: 'bg-cyan-500 hover:bg-cyan-600 active:bg-cyan-700 text-white focus:ring-cyan-400'
      };
      
      
      const buttonVariant = this.variant || this.type || 'primary';
      return `${sizeClasses[this.size]} ${typeClasses[buttonVariant]} rounded-lg shadow-sm`;
    }
  }
}
</script>

<style scoped>

</style>

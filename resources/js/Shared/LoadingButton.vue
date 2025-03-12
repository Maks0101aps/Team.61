<template>
  <button
    :disabled="loading"
    class="flex items-center"
    :class="{ 'opacity-50 cursor-not-allowed': loading }"
  >
    <div v-if="loading" class="btn-spinner mr-2" />
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
  },
}
</script>

<style>
.btn-spinner {
  @apply inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em];
  border-color: white;
}
</style>

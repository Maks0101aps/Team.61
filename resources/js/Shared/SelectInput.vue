<template>
  <div>
    <label class="block text-sm font-medium text-gray-700">
      <span v-if="modelValue">{{ label }}</span>
      <span v-else class="text-gray-500">{{ label }}</span>
    </label>
    <select
      :multiple="multiple"
      :value="modelValue"
      @change="$emit('update:modelValue', multiple ? [...$event.target.selectedOptions].map(o => o.value) : $event.target.value)"
      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md"
    >
      <slot />
    </select>
    <div v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</div>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: {
      type: [String, Number, Array],
      default: () => []
    },
    label: String,
    error: String,
    multiple: Boolean,
  },
  emits: ['update:modelValue'],
}
</script>

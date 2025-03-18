<template>
  <div>
    <label class="block text-sm font-medium text-gray-700">
      <span v-if="value">{{ label }}</span>
      <span v-else class="text-gray-500">{{ label }}</span>
    </label>
    <input
      :type="type"
      :value="value"
      @input="$emit('update:modelValue', $event.target.value)"
      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
      :placeholder="placeholder"
    >
    <div v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</div>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: [String, Number],
    label: String,
    error: String,
    type: {
      type: String,
      default: 'text',
    },
    placeholder: String,
  },
  emits: ['update:modelValue'],
  computed: {
    value() {
      return this.modelValue
    },
  },
}
</script>

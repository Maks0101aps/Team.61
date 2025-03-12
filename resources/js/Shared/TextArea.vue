<template>
  <div>
    <label class="block text-sm font-medium text-gray-700">
      <span v-if="value">{{ label }}</span>
      <span v-else class="text-gray-500">{{ label }}</span>
    </label>
    <textarea
      :value="value"
      @input="$emit('update:modelValue', $event.target.value)"
      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
      :rows="rows"
      :placeholder="placeholder"
    ></textarea>
    <div v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</div>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: String,
    label: String,
    error: String,
    rows: {
      type: Number,
      default: 3,
    },
    placeholder: String,
  },
  emits: ['update:modelValue'],
  computed: {
    value: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      },
    },
  },
}
</script> 
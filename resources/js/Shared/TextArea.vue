<template>
  <div>
    <label v-if="label" class="form-label block mb-2 text-sm font-medium text-gray-700" :for="id">{{ label }}</label>
    <textarea
      :id="id"
      :value="value"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-textarea w-full px-4 py-2.5 rounded-lg border-gray-400 border-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
      :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-200': error }"
      :rows="rows"
      :placeholder="placeholder"
    ></textarea>
    <div v-if="error" class="form-error mt-1 text-sm text-red-600">{{ error }}</div>
    <div v-if="helpText" class="text-xs mt-1" :class="helpTextClass || 'text-gray-500'">{{ helpText }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

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
    helpText: String,
    helpTextClass: String,
    id: {
      type: String,
      default() {
        return `textarea-${uuid()}`
      },
    },
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
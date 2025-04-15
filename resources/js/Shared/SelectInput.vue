<template>
  <div class="form-group">
    <label v-if="label" class="form-label block mb-2 text-sm font-medium text-gray-700" :for="id">{{ label }}</label>
    <div class="relative">
      <select
        :id="id"
        :multiple="multiple"
        :value="modelValue"
        :disabled="disabled"
        @change="$emit('update:modelValue', multiple ? [...$event.target.selectedOptions].map(o => o.value) : $event.target.value)"
        class="form-select appearance-none w-full px-4 py-2.5 rounded-lg bg-white border-gray-400 border-2 text-gray-700 shadow-sm transition-all duration-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
        :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-200': error, 'pr-10': !multiple }"
      >
        <slot />
      </select>
      
      <!-- Dropdown arrow for single selects -->
      <div v-if="!multiple" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </div>
    </div>
    <div v-if="error" class="form-error mt-1 text-sm text-red-600">{{ error }}</div>
    <div v-if="helpText" class="form-help mt-1 text-sm text-gray-500">{{ helpText }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

export default {
  props: {
    modelValue: {
      type: [String, Number, Array],
      default: () => []
    },
    label: String,
    error: String,
    multiple: Boolean,
    disabled: Boolean,
    helpText: String,
    id: {
      type: String,
      default() {
        return `select-input-${uuid()}`
      },
    },
  },
  emits: ['update:modelValue'],
}
</script>

<style scoped>
.form-group {
  margin-bottom: 1.25rem;
}

.form-select:focus {
  outline: none;
}

.form-select[multiple]::-webkit-scrollbar {
  width: 8px;
}

.form-select[multiple]::-webkit-scrollbar-track {
  background: #f7fafc;
  border-radius: 4px;
}

.form-select[multiple]::-webkit-scrollbar-thumb {
  background-color: #4299e1;
  border-radius: 4px;
  border: 2px solid #f7fafc;
}
</style>

<template>
  <div>
    <div class="flex items-center">
      <input
        :id="inputId"
        type="checkbox"
        :checked="modelValue"
        @input="$emit('update:modelValue', $event.target.checked)"
        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 focus:ring-opacity-50 border-gray-300 rounded"
      />
      <label :for="inputId" class="ml-2 block text-sm text-gray-900">
        {{ label }}
      </label>
    </div>
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

export default {
  props: {
    modelValue: Boolean,
    label: String,
    error: String,
  },
  emits: ['update:modelValue'],
  computed: {
    inputId() {
      return `checkbox-${uuid()}`
    }
  }
}
</script> 
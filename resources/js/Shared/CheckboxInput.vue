<template>
  <div>
    <div class="flex items-center">
      <input
        :id="inputId"
        type="checkbox"
        :checked="modelValue"
        @input="$emit('update:modelValue', $event.target.checked)"
        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
      />
      <label :for="inputId" class="ml-2 block text-sm text-gray-900">
        {{ label }}
      </label>
    </div>
    <div v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</div>
  </div>
</template>

<script>
let nextId = 0

export default {
  props: {
    modelValue: Boolean,
    label: String,
    error: String,
  },
  emits: ['update:modelValue'],
  data() {
    return {
      inputId: `checkbox-${nextId++}`
    }
  }
}
</script> 
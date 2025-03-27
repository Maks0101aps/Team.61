<template>
  <div>
    <label v-if="label" class="form-label" :for="id">{{ label }}</label>
    <select
      :id="id"
      :multiple="multiple"
      :value="modelValue"
      @change="$emit('update:modelValue', multiple ? [...$event.target.selectedOptions].map(o => o.value) : $event.target.value)"
      class="form-select"
      :class="{ error: error }"
    >
      <slot />
    </select>
    <div v-if="error" class="form-error">{{ error }}</div>
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

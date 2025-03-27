<template>
  <div>
    <label v-if="label" class="form-label" :for="id">{{ label }}</label>
    <input
      :id="id"
      :type="type"
      :value="value"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-input"
      :class="{ error: error }"
      :placeholder="placeholder"
    >
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

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
    id: {
      type: String,
      default() {
        return `text-input-${uuid()}`
      },
    },
  },
  emits: ['update:modelValue'],
  computed: {
    value() {
      return this.modelValue
    },
  },
}
</script>

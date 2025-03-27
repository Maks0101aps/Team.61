<template>
  <div>
    <label v-if="label" class="form-label" :for="id">{{ label }}</label>
    <textarea
      :id="id"
      :value="value"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-textarea"
      :class="{ error: error }"
      :rows="rows"
      :placeholder="placeholder"
    ></textarea>
    <div v-if="error" class="form-error">{{ error }}</div>
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
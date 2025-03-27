<template>
  <div class="form-group">
    <label v-if="label" class="form-label block mb-2 text-sm font-medium text-gray-700" :for="id">{{ label }}</label>
    <div class="relative">
      <input
        :id="id"
        :type="actualType"
        :value="value"
        @input="$emit('update:modelValue', $event.target.value)"
        class="form-input w-full px-4 py-2.5 rounded-lg border-gray-300 shadow-sm transition-all duration-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 disabled:bg-gray-100 disabled:text-gray-500"
        :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-200': error, 'pr-10': type === 'email' || type === 'password' || type === 'search' }"
        :placeholder="placeholder"
      >
      
      <!-- Email icon -->
      <div v-if="type === 'email'" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
        </svg>
      </div>
      
      <!-- Password visibility toggle icon -->
      <div v-if="type === 'password'" class="absolute inset-y-0 right-0 flex items-center pr-3">
        <button 
          type="button" 
          @click="togglePasswordVisibility" 
          class="text-gray-400 hover:text-gray-500 focus:outline-none"
        >
          <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
          </svg>
        </button>
      </div>
      
      <!-- Search icon -->
      <div v-if="type === 'search'" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
        </svg>
      </div>
    </div>
    <div v-if="error" class="form-error mt-1 text-sm text-red-600">{{ error }}</div>
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
  data() {
    return {
      showPassword: false
    }
  },
  computed: {
    value() {
      return this.modelValue
    },
    actualType() {
      if (this.type === 'password' && this.showPassword) {
        return 'text'
      }
      return this.type
    }
  },
  methods: {
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword
    }
  }
}
</script>

<style scoped>
.form-group {
  margin-bottom: 1.25rem;
}

.form-input:focus {
  outline: none;
}

.form-input::placeholder {
  color: #a0aec0;
  opacity: 1;
}
</style>

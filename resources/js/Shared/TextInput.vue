<template>
  <div class="form-group">
    <label v-if="label" class="form-label block mb-2 text-sm font-medium text-gray-700" :for="id">{{ label }}</label>
    <div class="relative">
      <input
        v-if="type !== 'phone'"
        :id="id"
        :type="actualType"
        :value="value"
        :disabled="disabled"
        @input="$emit('update:modelValue', $event.target.value)"
        class="form-input w-full px-4 py-2.5 rounded-lg border-gray-400 border-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 disabled:bg-gray-100 disabled:text-gray-500"
        :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-200': error, 'pr-10': type === 'email' || type === 'password' || type === 'search' }"
        :placeholder="placeholder"
      >

      <input
        v-if="type === 'phone'"
        :id="id"
        type="tel"
        :value="formattedPhoneValue"
        :disabled="disabled"
        @input="handlePhoneInput"
        @blur="validatePhone"
        class="form-input w-full px-4 py-2.5 rounded-lg border-gray-400 border-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 disabled:bg-gray-100 disabled:text-gray-500"
        :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-200': error }"
        placeholder="+380 (XX) XXX-XX-XX"
        maxlength="19"
      >
      
      <!-- Email icon -->
      <div v-if="type === 'email'" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
        </svg>
      </div>
      
      <!-- Phone icon -->
      <div v-if="type === 'phone'" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
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
    <div v-if="helpText" class="text-xs mt-1" :class="helpTextClass || 'text-gray-500'">{{ helpText }}</div>
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
    disabled: {
      type: Boolean,
      default: false
    },
    helpText: String,
    helpTextClass: String,
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
      showPassword: false,
      phoneError: '',
      rawPhoneValue: this.modelValue || ''
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
    },
    formattedPhoneValue() {
      
      if (!this.value) return '';
      
      
      let digits = this.value.replace(/\D/g, '');
      
      
      if (digits.length > 12) {
        digits = digits.substring(0, 12);
      }
      
      if (digits.length <= 3) {
        return '+' + digits;
      }
      
      
      let formatted = '+380';
      
      if (digits.length > 3) {
        formatted = '+380 (' + digits.substring(3, 5);
        
        if (digits.length > 5) {
          formatted += ') ' + digits.substring(5, 8);
          
          if (digits.length > 8) {
            formatted += '-' + digits.substring(8, 10);
            
            if (digits.length > 10) {
              formatted += '-' + digits.substring(10, 12);
            }
          }
        }
      }
      
      return formatted;
    }
  },
  methods: {
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword
    },
    handlePhoneInput(event) {
      
      const inputValue = event.target.value;
      
      
      let cleanedValue = inputValue.replace(/[^\d+]/g, '');
      
      
      if (!cleanedValue.startsWith('+')) {
        if (cleanedValue.startsWith('380')) {
          
          cleanedValue = '+' + cleanedValue;
        } else if (cleanedValue.startsWith('80')) {
          
          cleanedValue = '+3' + cleanedValue;
        } else if (cleanedValue.startsWith('0')) {
          
          cleanedValue = '+380' + cleanedValue.substring(1);
        } else if (cleanedValue.length > 0) {
          
          cleanedValue = '+380' + cleanedValue;
        }
      }
      
      
      if (cleanedValue.startsWith('+380')) {
        
        const nationalNumber = cleanedValue.substring(4);
        if (nationalNumber.length > 9) {
          
          cleanedValue = '+380' + nationalNumber.substring(0, 9);
        }
      }
      
      
      this.$emit('update:modelValue', cleanedValue);
    },
    validatePhone() {
      if (this.value) {
        
        const cleanPhone = this.value.replace(/[^\d+]/g, '');
        
        
        const phoneRegex = /^\+380\d{9}$/;
        if (!phoneRegex.test(cleanPhone)) {
          
          let formattedNumber = cleanPhone;
          
          
          if (cleanPhone.startsWith('0')) {
            formattedNumber = '+380' + cleanPhone.substring(1);
          } 
          
          else if (!cleanPhone.startsWith('+') && cleanPhone.startsWith('380')) {
            formattedNumber = '+' + cleanPhone;
          }
          
          else if (cleanPhone.startsWith('80')) {
            formattedNumber = '+380' + cleanPhone.substring(2);
          }
          
          else if (!cleanPhone.startsWith('+380') && /^\d{9}$/.test(cleanPhone)) {
            formattedNumber = '+380' + cleanPhone;
          }
          
          
          if (phoneRegex.test(formattedNumber)) {
            this.$emit('update:modelValue', formattedNumber);
            this.phoneError = '';
          } else {
            
            this.phoneError = 'Номер телефону має бути у форматі +380 XX XXX-XX-XX. Ви можете ввести номер в формате 0XXXXXXXXX и он будет автоматически преобразован.';
            this.$emit('update:error', this.phoneError);
          }
        } else {
          
          this.phoneError = '';
        }
      }
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

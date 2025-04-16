<template>
  <div class="form-group">
    <label v-if="label" class="form-label block mb-2 text-sm font-medium text-gray-700" :for="id">
      {{ label }}
      <span v-if="required" class="text-red-500 ml-1">*</span>
    </label>
    
    <div class="relative">
      <input
        :id="id"
        ref="input"
        v-bind="$attrs"
        :value="modelValue" 
        class="form-input w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 shadow-sm"
        :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': error }"
        :placeholder="placeholder"
        :disabled="disabled"
        maxlength="13"
        type="tel"
        @input="handleInput"
        @paste="handlePaste"
        @keypress="validateKeypress"
        @blur="onBlur"
        @focus="onFocus"
      />
    </div>
    
    <div v-if="error" class="text-red-500 mt-1 text-xs">{{ error }}</div>
    <div v-else-if="helpText" class="text-gray-500 mt-1 text-xs">{{ helpText }}</div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    modelValue: String,
    label: String,
    error: String,
    helpText: String,
    placeholder: {
      type: String,
      default: '+380XXXXXXXXX'
    },
    id: {
      type: String,
      default() {
        return `phone-input-${Math.random().toString(36).substring(2, 15)}`
      },
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue'],
  methods: {
    formatPhoneNumber(value) {
      if (!value) return '+380';
      
      // Сначала очистим от всех нецифровых символов, кроме + в начале
      let cleaned = value.replace(/[^\d+]/g, '');
      
      // Форматируем номер, чтобы он начинался с +380
      if (!cleaned.startsWith('+380') && cleaned.startsWith('+')) {
        cleaned = '+380' + cleaned.substring(1);
      } else if (!cleaned.startsWith('+')) {
        if (cleaned.startsWith('380')) {
          cleaned = '+' + cleaned;
        } else {
          cleaned = '+380' + cleaned;
        }
      }
      
      // Обрезаем до 13 символов
      if (cleaned.length > 13) {
        cleaned = cleaned.substring(0, 13);
      }
      
      return cleaned;
    },
    handleInput(event) {
      // Форматируем ввод и обновляем значение
      const formatted = this.formatPhoneNumber(event.target.value);
      this.$refs.input.value = formatted;
      this.$emit('update:modelValue', formatted);
    },
    validateKeypress(event) {
      // Предотвращаем ввод не цифровых символов
      if (this.modelValue && this.modelValue.length >= 13) {
        event.preventDefault();
        return;
      }
      
      // Разрешаем только цифры и + в начале
      if (!/^\d$/.test(event.key) && !(event.key === '+' && (!this.modelValue || this.modelValue.length === 0))) {
        event.preventDefault();
      }
    },
    handlePaste(event) {
      event.preventDefault();
      const clipboardData = event.clipboardData || window.clipboardData;
      const pastedText = clipboardData.getData('text');
      
      const formatted = this.formatPhoneNumber(pastedText);
      this.$refs.input.value = formatted;
      this.$emit('update:modelValue', formatted);
    },
    onFocus() {
      // Если поле пустое, автоматически добавляем +380
      if (!this.modelValue) {
        this.$emit('update:modelValue', '+380');
      }
    },
    onBlur() {
      // При потере фокуса проверяем, что номер соответствует формату
      if (this.modelValue) {
        const formatted = this.formatPhoneNumber(this.modelValue);
        if (formatted !== this.modelValue) {
          this.$emit('update:modelValue', formatted);
        }
      }
    }
  }
}
</script>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
</style> 
<template>
  <div>
    <label v-if="label" class="form-label">{{ label }}:</label>
    <div class="form-input p-0" :class="{ error: errors.length }">
      <input ref="file" type="file" :accept="accept" class="hidden" @change="change" />
      <div v-if="!modelValue && !previewUrl" class="p-2">
        <button type="button" class="px-4 py-1 text-white text-xs font-medium bg-blue-500 hover:bg-blue-700 rounded-sm" @click="browse">Выбрать файл</button>
      </div>
      <div v-else-if="isImage" class="p-2">
        <div class="flex items-center">
          <div class="mr-2">
            <img :src="previewUrl || imageUrl" class="h-20 w-20 object-cover rounded-full border border-gray-200" alt="Preview" />
          </div>
          <div class="flex-1">
            <div v-if="modelValue" class="text-sm text-gray-700 mb-1">
              {{ modelValue.name }} <span class="text-gray-500 text-xs">({{ filesize(modelValue.size) }})</span>
            </div>
            <div class="flex space-x-2">
              <button type="button" class="px-3 py-1 text-white text-xs font-medium bg-blue-500 hover:bg-blue-700 rounded-sm" @click="browse">
                {{ modelValue ? 'Изменить' : 'Выбрать' }}
              </button>
              <button v-if="modelValue || previewUrl" type="button" class="px-3 py-1 text-white text-xs font-medium bg-red-500 hover:bg-red-700 rounded-sm" @click="remove">Удалить</button>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="flex items-center justify-between p-2">
        <div class="flex-1 pr-1">
          {{ modelValue.name }} <span class="text-gray-500 text-xs">({{ filesize(modelValue.size) }})</span>
        </div>
        <div class="flex space-x-2">
          <button type="button" class="px-3 py-1 text-white text-xs font-medium bg-blue-500 hover:bg-blue-700 rounded-sm" @click="browse">Изменить</button>
          <button type="button" class="px-3 py-1 text-white text-xs font-medium bg-red-500 hover:bg-red-700 rounded-sm" @click="remove">Удалить</button>
        </div>
      </div>
    </div>
    <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: File,
    label: String,
    accept: String,
    imageUrl: String,
    errors: {
      type: Array,
      default: () => [],
    },
  },
  emits: ['update:modelValue'],
  data() {
    return {
      previewUrl: null
    }
  },
  computed: {
    isImage() {
      return this.accept && this.accept.includes('image') || 
             (this.modelValue && this.modelValue.type && this.modelValue.type.startsWith('image/')) ||
             this.imageUrl;
    }
  },
  watch: {
    modelValue(value) {
      if (!value) {
        this.$refs.file.value = ''
        this.previewUrl = null
      }
    },
  },
  methods: {
    filesize(size) {
      var i = Math.floor(Math.log(size) / Math.log(1024))
      return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
    },
    browse() {
      this.$refs.file.click()
    },
    change(e) {
      const file = e.target.files[0]
      if (file) {
        this.$emit('update:modelValue', file)
        
        if (this.isImage) {
          const reader = new FileReader()
          reader.onload = e => {
            this.previewUrl = e.target.result
          }
          reader.readAsDataURL(file)
        }
      }
    },
    remove() {
      this.$emit('update:modelValue', null)
      this.previewUrl = null
    },
  },
}
</script>


<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link v-if="user.role !== 'parent'" class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
        <span v-if="user.role !== 'parent'" class="text-indigo-400 font-medium">/</span>
        {{ form.first_name }} {{ form.last_name }}
      </h1>
      <img v-if="user.photo" class="block ml-4 w-8 h-8 rounded-full" :src="user.photo_path ? `/storage/${user.photo_path}` : user.photo" />
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore"> This user has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <!-- Personal Information Section -->
          <div class="w-full pb-4">
            <h2 class="text-lg font-bold mb-4 text-gray-700">{{ language === 'uk' ? 'Особиста інформація' : 'Personal Information' }}</h2>
          </div>
          
          <!-- Profile photo section - visible for all users -->
          <div class="pb-8 pr-6 w-full">
            <label class="form-label">{{ language === 'uk' ? 'Фото профілю' : 'Profile Photo' }}:</label>
            <div class="flex items-center mt-2">
              <div 
                v-if="user.photo" 
                class="relative w-16 h-16 mr-4"
                @mouseenter="showDeletePhotoButton = true"
                @mouseleave="showDeletePhotoButton = false"
              >
                <img 
                  class="w-16 h-16 rounded-full object-cover" 
                  :src="user.photo_path ? `/storage/${user.photo_path}` : user.photo" 
                  alt="Фото профілю" 
                />
                <button 
                  v-if="showDeletePhotoButton"
                  type="button"
                  @click="deletePhoto"
                  class="absolute inset-0 flex items-center justify-center bg-red-500 bg-opacity-70 rounded-full text-white"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-else class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                <span class="text-gray-500 text-xl">{{ form.first_name.charAt(0) }}{{ form.last_name.charAt(0) }}</span>
              </div>
              
              <div class="flex-1">
                <file-input 
                  v-model="form.photo" 
                  :error="form.errors.photo" 
                  type="file" 
                  accept="image/*" 
                  :label="false" />
                <p class="mt-1 text-xs text-gray-500">
                  {{ language === 'uk' ? 'Максимальний розмір файлу: 2МБ' : 'Maximum file size: 3MB' }}
                </p>
              </div>
            </div>
            <p v-if="form.errors.photo" class="mt-2 text-sm text-red-600">
              {{ form.errors.photo }}
            </p>
            <p v-if="photoUploadSuccess" class="mt-2 text-sm text-green-600">
              {{ language === 'uk' ? 'Фото успішно оновлено' : 'Photo successfully updated' }}
            </p>
          </div>
          
          <!-- For parent users, make name fields read-only -->
          <template v-if="user.role === 'parent'">
            <text-input v-model="form.first_name" 
                        :error="form.errors.first_name" 
                        class="pb-8 pr-6 w-full lg:w-1/3" 
                        :label="language === 'uk' ? 'Ім\'я' : 'First name'"
                        :disabled="true" />
                        
            <text-input v-model="form.middle_name" 
                        :error="form.errors.middle_name" 
                        class="pb-8 pr-6 w-full lg:w-1/3" 
                        :label="language === 'uk' ? 'По батькові' : 'Middle name'" 
                        :disabled="true" />
                        
            <text-input v-model="form.last_name" 
                        :error="form.errors.last_name" 
                        class="pb-8 pr-6 w-full lg:w-1/3" 
                        :label="language === 'uk' ? 'Прізвище' : 'Last name'" 
                        :disabled="true" />
          </template>
          
          <!-- For other users, editable name fields -->
          <template v-else>
            <text-input v-model="form.first_name" 
                        :error="form.errors.first_name" 
                        class="pb-8 pr-6 w-full lg:w-1/2" 
                        :label="language === 'uk' ? 'Ім\'я' : 'First name'" />
                        
            <text-input v-model="form.last_name" 
                        :error="form.errors.last_name" 
                        class="pb-8 pr-6 w-full lg:w-1/2" 
                        :label="language === 'uk' ? 'Прізвище' : 'Last name'" />
          </template>
          
          <!-- Common fields -->
          <text-input v-model="form.email" 
                      :error="form.errors.email" 
                      class="pb-8 pr-6 w-full lg:w-1/2" 
                      :label="language === 'uk' ? 'Email' : 'Email'" />
                      
          <text-input v-model="form.password" 
                      :error="form.errors.password" 
                      class="pb-8 pr-6 w-full lg:w-1/2" 
                      type="password" 
                      autocomplete="new-password" 
                      :label="language === 'uk' ? 'Пароль' : 'Password'" />
          
          <!-- Owner field only for non-parent users -->
          <select-input v-if="user.role !== 'parent'" 
                        v-model="form.owner" 
                        :error="form.errors.owner" 
                        class="pb-8 pr-6 w-full lg:w-1/2" 
                        :label="language === 'uk' ? 'Власник' : 'Owner'">
            <option :value="true">{{ language === 'uk' ? 'Так' : 'Yes' }}</option>
            <option :value="false">{{ language === 'uk' ? 'Ні' : 'No' }}</option>
          </select-input>
          
          <!-- Address Section for parents -->
          <template v-if="user.role === 'parent'">
            <div class="w-full pb-4 pt-4">
              <h2 class="text-lg font-bold mb-4 text-gray-700">{{ language === 'uk' ? 'Адреса' : 'Address' }}</h2>
            </div>
            
            <text-input v-model="form.street" 
                        :error="form.errors.street" 
                        class="pb-8 pr-6 w-full lg:w-1/2" 
                        :label="language === 'uk' ? 'Вулиця' : 'Street'" />
                        
            <text-input v-model="form.house_number" 
                        :error="form.errors.house_number" 
                        class="pb-8 pr-6 w-full lg:w-1/2" 
                        :label="language === 'uk' ? 'Номер будинку' : 'House number'" />
                        
            <text-input v-model="form.city" 
                        :error="form.errors.city" 
                        class="pb-8 pr-6 w-full lg:w-1/3" 
                        :label="language === 'uk' ? 'Місто' : 'City'" />
                        
            <text-input v-model="form.region" 
                        :error="form.errors.region" 
                        class="pb-8 pr-6 w-full lg:w-1/3" 
                        :label="language === 'uk' ? 'Область' : 'Region'" />
                        
            <text-input v-model="form.postal_code" 
                        :error="form.errors.postal_code" 
                        class="pb-8 pr-6 w-full lg:w-1/3" 
                        :label="language === 'uk' ? 'Поштовий індекс' : 'Postal code'" />
                        
            <text-input v-model="form.phone" 
                        :error="form.errors.phone" 
                        class="pb-8 pr-6 w-full" 
                        :label="language === 'uk' ? 'Телефон' : 'Phone'" />
            
            <!-- Children Information Section -->
            <div v-if="user.children && user.children.length > 0" class="w-full pb-4 pt-4">
              <h2 class="text-lg font-bold mb-4 text-gray-700">{{ language === 'uk' ? 'Діти' : 'Children' }}</h2>
              
              <div class="bg-blue-50 p-4 rounded-lg">
                <ul class="space-y-2">
                  <li v-for="child in user.children" :key="child.id" class="flex items-center p-2 border-b border-blue-100">
                    <div class="flex-1">
                      <p class="font-medium text-blue-900">{{ child.name || child.first_name + ' ' + child.last_name }}</p>
                      <p v-if="child.class" class="text-sm text-blue-700">{{ language === 'uk' ? 'Клас:' : 'Class:' }} {{ child.class }}</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </template>
        </div>
        
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!user.deleted_at && user.role !== 'parent'" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">
            {{ language === 'uk' ? 'Видалити користувача' : 'Delete User' }}
          </button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" buttonType="submit">
            {{ language === 'uk' ? 'Оновити' : 'Update' }}
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import FileInput from '@/Shared/FileInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    user: Object,
  },
  remember: 'form',
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      showDeletePhotoButton: false,
      photoUploadSuccess: false,
      form: this.$inertia.form({
        _method: 'put',
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        middle_name: this.user.middle_name || '',
        email: this.user.email,
        password: '',
        owner: this.user.owner,
        photo: null,
        delete_photo: false,
        street: this.user.street || '',
        house_number: this.user.house_number || '',
        city: this.user.city || '',
        region: this.user.region || '',
        country: this.user.country || '',
        postal_code: this.user.postal_code || '',
        phone: this.user.phone || '',
      }),
    }
  },
  methods: {
    update() {
      this.form.post(`/users/${this.user.id}`, {
        onSuccess: () => {
          this.form.reset('password', 'photo', 'delete_photo')
          this.photoUploadSuccess = true
          setTimeout(() => {
            this.photoUploadSuccess = false
          }, 3000)
        },
        preserveScroll: true,
      })
    },
    deletePhoto() {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете видалити фото профілю?' : 'Are you sure you want to delete your profile photo?')) {
        this.form.delete_photo = true
        this.form.photo = null
        this.form.post(`/users/${this.user.id}`, {
          onSuccess: () => {
            this.form.reset('password', 'photo', 'delete_photo')
            this.photoUploadSuccess = true
            setTimeout(() => {
              this.photoUploadSuccess = false
            }, 3000)
          },
          preserveScroll: true,
        })
      }
    },
    destroy() {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете видалити цього користувача?' : 'Are you sure you want to delete this user?')) {
        this.$inertia.delete(`/users/${this.user.id}`)
      }
    },
    restore() {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете відновити цього користувача?' : 'Are you sure you want to restore this user?')) {
        this.$inertia.put(`/users/${this.user.id}/restore`)
      }
    },
  },
  mounted() {
    // Listen for language changes
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.language = event.newValue
      }
    })
    
    // Also listen for custom event
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang
      })
    }
  }
}
</script>

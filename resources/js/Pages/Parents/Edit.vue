<template>
  <div>
    <Head :title="`${language === 'uk' ? 'Редагувати' : 'Edit'} ${parent.first_name} ${parent.last_name}`" />
    
    <!-- Header with breadcrumbs and title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/parents">
            {{ language === 'uk' ? 'Батьки' : 'Parents' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Редагування' : 'Edit' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Редагування батьків' : 'Edit Parent' }}: {{ parent.first_name }} {{ parent.last_name }}
        </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/parents" 
          class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 flex items-center transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          {{ language === 'uk' ? 'Назад' : 'Back' }}
        </Link>
      </div>
    </div>

    <!-- Main form card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <form @submit.prevent="update">
        <!-- Form header -->
        <div class="px-6 sm:px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            {{ language === 'uk' ? 'Інформація про батьків' : 'Parent Information' }}
          </h2>
          <p class="mt-1" style="color: #000000 !important; opacity: 1 !important;">
            {{ language === 'uk' ? 'Оновіть дані батьків в системі' : 'Update parent information in the system' }}
          </p>
        </div>
        
        <!-- Form body -->
        <div class="p-4 sm:p-6 md:p-8">
          <!-- Personal Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Особисті дані' : 'Personal Information' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
              <div class="md:col-span-2 mb-4">
                <label class="form-label block mb-2 text-sm font-medium text-gray-700">{{ language === 'uk' ? 'Аватар' : 'Avatar' }}</label>
                <div class="flex justify-center">
                  <div 
                    class="w-24 h-24 overflow-hidden rounded-full border border-gray-200 shadow-sm relative cursor-pointer transition-transform hover:scale-105"
                    @click="showImageModal = true"
                  >
                    <img 
                      v-if="avatarUrl" 
                      :src="avatarUrl" 
                      class="w-full h-full object-cover" 
                      alt="Avatar" 
                      @error="handleAvatarError"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500 text-xl font-bold">
                      {{ parent.first_name ? parent.first_name.charAt(0).toUpperCase() : '' }}{{ parent.last_name ? parent.last_name.charAt(0).toUpperCase() : '' }}
                    </div>
                  </div>
                </div>
                <p class="text-center text-xs text-gray-500 mt-2">
                  {{ language === 'uk' ? 'Аватар можна змінити через користувацький профіль' : 'Avatar can be changed through the user profile' }}
                </p>
              </div>
              
              <!-- Модальное окно для просмотра аватара -->
              <teleport to="body">
                <div v-if="showImageModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                  <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Затемнение фона -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showImageModal = false"></div>

                    <!-- Модальное окно -->
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center pb-3">
                          <h3 class="text-lg font-medium text-gray-900">
                            {{ language === 'uk' ? 'Фото користувача' : 'User Photo' }}
                          </h3>
                          <button 
                            @click="showImageModal = false" 
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"
                          >
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                          </button>
                        </div>
                        
                        <div class="flex justify-center">
                          <div class="relative w-64 h-64 overflow-hidden rounded-lg">
                            <img 
                              v-if="enlargedAvatarUrl" 
                              :src="enlargedAvatarUrl" 
                              class="w-full h-full object-cover" 
                              alt="Avatar Enlarged" 
                              @error="handleEnlargedAvatarError"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-700 text-4xl font-bold">
                              {{ parent.first_name ? parent.first_name.charAt(0).toUpperCase() : '' }}{{ parent.last_name ? parent.last_name.charAt(0).toUpperCase() : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                          type="button" 
                          class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                          @click="showImageModal = false"
                        >
                          {{ language === 'uk' ? 'Закрити' : 'Close' }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </teleport>
              
              <select-input 
                v-model="form.parent_type" 
                :error="form.errors.parent_type" 
                :label="language === 'uk' ? 'Тип батьків' : 'Parent Type'"
                :help-text="language === 'uk' ? 'Виберіть тип батьків' : 'Select parent type'"
                class="md:col-span-2"
              >
                <option :value="null">{{ language === 'uk' ? 'Виберіть тип' : 'Select type' }}</option>
                <option value="mother">{{ language === 'uk' ? 'Мати' : 'Mother' }}</option>
                <option value="father">{{ language === 'uk' ? 'Батько' : 'Father' }}</option>
              </select-input>
              
              <text-input 
                v-model="form.first_name" 
                :error="form.errors.first_name" 
                :label="currentLanguageLabels.first_name" 
                :help-text="language === 'uk' ? 'Введіть ім\'я' : 'Enter parent\'s first name'"
              />
              
              <text-input 
                v-model="form.middle_name" 
                :error="form.errors.middle_name" 
                :label="currentLanguageLabels.middle_name" 
                :help-text="language === 'uk' ? 'Введіть по батькові' : 'Enter parent\'s middle name'"
              />
              
              <text-input 
                v-model="form.last_name" 
                :error="form.errors.last_name" 
                :label="currentLanguageLabels.last_name" 
                :help-text="language === 'uk' ? 'Введіть прізвище' : 'Enter parent\'s last name'"
                class="md:col-span-2"
              />
            </div>
          </div>
          
          <!-- Contact Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Контактна інформація' : 'Contact Information' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
              <text-input 
                v-model="form.email" 
                :error="form.errors.email" 
                :label="currentLanguageLabels.email" 
                type="email"
                :help-text="language === 'uk' ? 'Введіть електронну пошту для зв\'язку' : 'Enter email for contact'"
              />
              
              <text-input 
                v-model="form.phone" 
                :error="form.errors.phone" 
                :label="currentLanguageLabels.phone" 
                type="phone"
                :help-text="language === 'uk' ? 'Введіть номер телефону у форматі +380XXXXXXXXX' : 'Enter phone number in format +380XXXXXXXXX'"
              />
            </div>
          </div>
          
          <!-- Address Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Адреса' : 'Address' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
              <text-input 
                v-model="form.address" 
                :error="form.errors.address" 
                :label="currentLanguageLabels.address" 
                :help-text="language === 'uk' ? 'Введіть адресу проживання' : 'Enter residential address'"
                class="md:col-span-2"
              />
              
              <select-input 
                v-model="form.region" 
                :error="form.errors.region" 
                :label="currentLanguageLabels.region" 
                @change="loadCities"
                :help-text="language === 'uk' ? 'Оберіть область проживання' : 'Select region of residence'"
              >
                <option :value="null">{{ language === 'uk' ? 'Оберіть область' : 'Select region' }}</option>
                <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
              </select-input>
              
              <select-input 
                v-model="form.city" 
                :error="form.errors.city" 
                :label="currentLanguageLabels.city" 
                :disabled="!cities.length"
                :help-text="language === 'uk' ? 'Спочатку оберіть область для завантаження міст' : 'First select a region to load cities'"
              >
                <option :value="null">{{ cities.length ? (language === 'uk' ? 'Оберіть місто' : 'Select city') : (language === 'uk' ? 'Спочатку оберіть область' : 'First select a region') }}</option>
                <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
              </select-input>
              
              <div class="form-group">
                <label class="form-label block mb-2 text-sm font-medium text-gray-700">{{ currentLanguageLabels.country }}</label>
                <div class="form-input w-full px-4 py-2.5 rounded-lg border-gray-300 bg-gray-50 border border-gray-200 flex items-center shadow-sm">
                  <span class="text-blue-800 font-medium">🇺🇦 {{ language === 'uk' ? 'Україна' : 'Ukraine' }}</span>
                  <input type="hidden" v-model="form.country" />
                </div>
                <p class="text-gray-500 text-xs mt-1">{{ language === 'uk' ? 'Країна за замовчуванням - Україна' : 'Default country - Ukraine' }}</p>
              </div>
              
              <text-input 
                v-model="form.postal_code" 
                :error="form.errors.postal_code" 
                :label="currentLanguageLabels.postal_code" 
                :help-text="language === 'uk' ? 'Введіть поштовий індекс' : 'Enter postal code'"
                maxlength="5"
                @input="formatPostalCode"
              />
            </div>
          </div>
          
          <!-- Children Section -->
          <div class="mb-4">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Зв\'язок з учнями' : 'Connection with students' }}
            </h3>
            <div class="mb-6">
              <multi-select 
                v-model="form.children" 
                :options="students" 
                :error="form.errors.children"
                :label="currentLanguageLabels.children"
                :option-label="'full_name'"
                :option-value="'id'"
                :placeholder="language === 'uk' ? 'Оберіть учнів, які є дітьми цих батьків' : 'Select students who are children of this parent'"
                :help-text="language === 'uk' ? 'Оберіть учнів зі списку, які пов\'язані з цими батьками' : 'Select students from the list who are related to this parent'"
              />
            </div>
          </div>
      </div>
        
        <!-- Form footer -->
        <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-end gap-3">
          <button
            type="button"
            class="w-full sm:w-auto px-4 py-2 rounded-lg border border-red-300 text-red-700 bg-white hover:bg-red-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            @click="destroy"
          >
            <span>{{ language === 'uk' ? 'Видалити батьків' : 'Delete Parent' }}</span>
        </button>
          <div class="flex-grow"></div>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md"
            @click="update">
            {{ language === 'uk' ? 'Оновити батьків' : 'Update Parent' }}
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
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import MultiSelect from '@/Shared/MultiSelect.vue'
import axios from 'axios'

export default {
  components: {
    Head,
    Link,
    TextInput,
    SelectInput,
    LoadingButton,
    MultiSelect,
  },
  layout: Layout,
  props: {
    parent: {
      type: Object,
      required: true,
    },
    students: Array,
    regions: {
      type: Array,
      default: () => []
    }
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.parent.first_name,
        middle_name: this.parent.middle_name,
        last_name: this.parent.last_name,
        email: this.parent.email,
        phone: this.parent.phone,
        parent_type: this.parent.parent_type,
        address: this.parent.address,
        city: this.parent.city,
        region: this.parent.region,
        country: "UA",
        postal_code: this.parent.postal_code,
        children: this.parent.children || [],
        avatar: null,
      }),
      cities: [],
      language: localStorage.getItem('language') || 'uk',
      showImageModal: false,
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    if (this.form.region) {
      this.loadCities();
    }
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  methods: {
    update() {
      this.form.avatar = null;
      
      this.form.put(`/parents/${this.parent.id}`);
    },
    destroy() {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете видалити цих батьків?' : 'Are you sure you want to delete this parent?')) {
        this.$inertia.delete(`/parents/${this.parent.id}`);
      }
    },
    loadCities() {
      this.form.city = null;
      this.cities = [];
      
      if (this.form.region) {
        axios.get(`/cities/${encodeURIComponent(this.form.region)}`)
          .then(response => {
            this.cities = response.data.cities;
            if (this.parent.city && this.cities.includes(this.parent.city)) {
              this.form.city = this.parent.city;
            }
          })
          .catch(error => {
            console.error('Error loading cities:', error);
          });
      }
    },
    updateLanguage(event) {
      this.language = event.detail.language;
    },
    handleAvatarError(e) {
      const fallbackUrl = 'https://ui-avatars.com/api/?name=' + 
        encodeURIComponent(this.parent.first_name + ' ' + this.parent.last_name) + 
        '&color=7F9CF5&background=EBF4FF';
      
      if (!e.target.src.includes('ui-avatars.com')) {
        e.target.src = fallbackUrl;
      } else {
        e.target.style.display = 'none';
      }
    },
    handleEnlargedAvatarError(e) {
      const fallbackUrl = 'https://ui-avatars.com/api/?name=' + 
        encodeURIComponent(this.parent.first_name + ' ' + this.parent.last_name) + 
        '&color=7F9CF5&background=EBF4FF&size=256';
      
      if (!e.target.src.includes('ui-avatars.com')) {
        e.target.src = fallbackUrl;
      } else {
        e.target.style.display = 'none';
      }
    },
    formatPostalCode() {
      let digitsOnly = this.form.postal_code?.replace(/\D/g, '') || '';
      
      if (digitsOnly.length > 5) {
        digitsOnly = digitsOnly.substring(0, 5);
      }
      
      this.form.postal_code = digitsOnly;
    }
  },
  computed: {
    avatarUrl() {
      if (this.parent.photo) {
        return this.parent.photo;
      }
      
      if (this.parent.photo_path) {
        return `/storage/${this.parent.photo_path}`;
      }
      
      const possibleProps = ['avatar_url', 'avatar', 'photo_url', 'image_url'];
      for (const prop of possibleProps) {
        if (this.parent[prop]) {
          if (this.parent[prop].startsWith('/')) {
            return window.location.origin + this.parent[prop];
          }
          if (this.parent[prop].startsWith('http')) {
            return this.parent[prop];
          }
          return this.parent[prop];
        }
      }
      
      return 'https://ui-avatars.com/api/?name=' + 
        encodeURIComponent(this.parent.first_name + ' ' + this.parent.last_name) + 
        '&color=7F9CF5&background=EBF4FF';
    },
    enlargedAvatarUrl() {
      const baseUrl = this.avatarUrl;
      
      if (baseUrl.includes('ui-avatars.com')) {
        return baseUrl + '&size=256';
      }
      
      return baseUrl;
    },
    currentLanguageLabels() {
      return {
        en: {
          first_name: 'First Name',
          middle_name: 'Middle Name',
          last_name: 'Last Name',
          email: 'Email',
          phone: 'Phone',
          address: 'Address',
          city: 'City',
          region: 'Region',
          country: 'Country',
          postal_code: 'Postal Code',
          children: 'Children',
        },
        uk: {
          first_name: 'Ім\'я',
          middle_name: 'По батькові',
          last_name: 'Прізвище',
          email: 'Email',
          phone: 'Телефон',
          address: 'Адреса',
          city: 'Місто',
          region: 'Область',
          country: 'Країна',
          postal_code: 'Поштовий індекс',
          children: 'Діти',
        },
      }[this.language || 'uk']
    },
  },
}
</script> 

<style scoped>
/* Add any specific styles needed for the form here */
</style> 
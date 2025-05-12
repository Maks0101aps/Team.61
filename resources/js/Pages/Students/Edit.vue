<template>
  <div>
    <Head :title="`${language === 'uk' ? 'Редагувати' : 'Edit'} ${student.first_name} ${student.last_name}`" />
    
    <!-- Header with breadcrumbs and title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/students">
            {{ language === 'uk' ? 'Студенти' : 'Students' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Редагування' : 'Edit' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Редагування студента' : 'Edit Student' }}: {{ student.first_name }} {{ student.last_name }}
    </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/students" 
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
            {{ language === 'uk' ? 'Інформація про студента' : 'Student Information' }}
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            {{ language === 'uk' ? 'Оновіть особисті дані студента' : 'Update the student personal information' }}
          </p>
        </div>
        
        <!-- Form body -->
        <div class="p-4 sm:p-6 md:p-8">
          <!-- Personal Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Особисті дані' : 'Personal Data' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
              <!-- Только аватар без кнопок -->
              <div class="md:col-span-2 mb-4">
                <label class="form-label block mb-2 text-sm font-medium text-gray-700">{{ language === 'uk' ? 'Аватар' : 'Avatar' }}</label>
                <div class="flex justify-center">
                  <img v-if="avatarUrl" :src="avatarUrl" class="h-24 w-24 object-cover rounded-full border border-gray-200 shadow-sm" alt="Avatar" />
                </div>
              </div>
              
              <text-input v-model="form.first_name" :error="form.errors.first_name" :label="language === 'uk' ? 'Ім\'я' : 'First Name'" />
              <text-input v-model="form.middle_name" :error="form.errors.middle_name" :label="language === 'uk' ? 'По батькові' : 'Middle Name'" />
              <text-input v-model="form.last_name" :error="form.errors.last_name" :label="language === 'uk' ? 'Прізвище' : 'Last Name'" class="md:col-span-2" />
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
                :label="language === 'uk' ? 'Електронна пошта' : 'Email'" 
                type="email" 
              />
              <phone-input 
                v-model="form.phone" 
                :error="form.errors.phone" 
                :label="language === 'uk' ? 'Телефон' : 'Phone'" 
                :help-text="language === 'uk' ? 'Введіть номер телефону у форматі +380XXXXXXXXX' : 'Enter phone number in format +380XXXXXXXXX'"
              />
            </div>
          </div>
          
          <!-- Class Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Інформація про клас' : 'Class Information' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
              <div class="flex space-x-4">
                <select-input 
                  v-model="selectedGrade" 
                  :error="form.errors.class" 
                  :label="language === 'uk' ? 'Номер класу' : 'Grade Number'"
                  @change="updateClass"
                  class="w-1/2"
                >
                  <option :value="null">{{ language === 'uk' ? 'Виберіть' : 'Select' }}</option>
                  <option v-for="num in 12" :key="num" :value="num">{{ num }}</option>
                </select-input>
                
                <select-input 
                  v-model="selectedLetter" 
                  :error="form.errors.class" 
                  :label="language === 'uk' ? 'Літера класу' : 'Grade Letter'"
                  @change="updateClass"
                  class="w-1/2"
                >
                  <option :value="null">{{ language === 'uk' ? 'Виберіть' : 'Select' }}</option>
                  <option value="A">А</option>
                  <option value="B">Б</option>
                  <option value="C">В</option>
                  <option value="D">Г</option>
                  <option value="E">Д</option>
                </select-input>
              </div>
              
              <text-input 
                v-model="form.class" 
                :error="form.errors.class" 
                :label="language === 'uk' ? 'Повний клас' : 'Full Class'" 
                disabled
                :help-text="language === 'uk' ? 'Автоматично заповнюється на основі вибраних значень' : 'Automatically filled based on selected values'"
              />
            </div>
          </div>
          
          <!-- Address Section -->
          <div class="mb-4">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Адреса' : 'Address' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
              <text-input 
                v-model="form.address" 
                :error="form.errors.address" 
                :label="language === 'uk' ? 'Адреса' : 'Address'" 
                class="md:col-span-2"
              />
              
              <select-input 
                v-model="form.region" 
                :error="form.errors.region" 
                :label="language === 'uk' ? 'Область' : 'Region'" 
                @change="loadCities"
                :help-text="language === 'uk' ? 'Оберіть область проживання' : 'Select your region'"
              >
                <option :value="null">{{ language === 'uk' ? 'Оберіть область' : 'Select region' }}</option>
                <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
              </select-input>
              
              <select-input 
                v-model="form.city" 
                :error="form.errors.city" 
                :label="language === 'uk' ? 'Місто' : 'City'" 
                :disabled="!cities.length"
                :help-text="language === 'uk' ? 'Спочатку оберіть область для завантаження міст' : 'First select a region to load cities'"
              >
                <option :value="null">{{ cities.length ? (language === 'uk' ? 'Оберіть місто' : 'Select city') : (language === 'uk' ? 'Спочатку оберіть область' : 'First select a region') }}</option>
                <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
              </select-input>
              
              <div class="form-group">
                <label class="form-label block mb-2 text-sm font-medium text-gray-700">{{ language === 'uk' ? 'Країна' : 'Country' }}</label>
                <div class="form-input w-full px-4 py-2.5 rounded-lg border-gray-300 bg-gray-50 border border-gray-200 flex items-center shadow-sm">
                  <span class="text-blue-800 font-medium">🇺🇦 {{ language === 'uk' ? 'Україна' : 'Ukraine' }}</span>
                  <input type="hidden" v-model="form.country" />
                </div>
                <p class="text-gray-500 text-xs mt-1">{{ language === 'uk' ? 'Країна за замовчуванням - Україна' : 'Default country - Ukraine' }}</p>
              </div>
              
              <text-input 
                v-model="form.postal_code" 
                :error="form.errors.postal_code" 
                :label="language === 'uk' ? 'Поштовий індекс' : 'Postal Code'" 
                maxlength="5"
                @input="formatPostalCode"
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
            <span>{{ language === 'uk' ? 'Видалити студента' : 'Delete Student' }}</span>
          </button>
          <div class="flex-grow"></div>
          <Link 
            :href="`/students/${student.id}`" 
            class="w-full sm:w-auto px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
            {{ language === 'uk' ? 'Скасувати' : 'Cancel' }}
          </Link>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
            {{ language === 'uk' ? 'Оновити студента' : 'Update Student' }}
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
import PhoneInput from '@/Shared/PhoneInput.vue'
import axios from 'axios'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    PhoneInput,
  },
  layout: Layout,
  props: {
    student: {
      type: Object,
      required: true,
    },
    regions: {
      type: Array,
      default: () => []
    }
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.student.first_name,
        middle_name: this.student.middle_name,
        last_name: this.student.last_name,
        email: this.student.email,
        phone: this.student.phone,
        address: this.student.address,
        city: this.student.city,
        region: this.student.region,
        country: "UA",
        postal_code: this.student.postal_code,
        class: this.student.class,
        avatar: null,
      }),
      language: localStorage.getItem('language') || 'uk',
      cities: [],
      selectedGrade: null,
      selectedLetter: null,
      classLetters: ['A', 'B', 'C', 'D', 'E'],
    }
  },
  computed: {
    avatarUrl() {
      // For photo_path coming from storage
      if (this.student.photo_path) {
        return `/storage/${this.student.photo_path}`;
      }
      
      if (this.student.avatar_url && this.student.avatar_url.startsWith('/')) {
        // Если путь относительный, добавляем базовый URL
        return window.location.origin + this.student.avatar_url;
      } else if (this.student.avatar_url) {
        // Если путь уже полный
        return this.student.avatar_url;
      } else if (this.student.avatar) {
        return this.student.avatar;
      } else if (this.student.photo_url) {
        return this.student.photo_url;
      } else if (this.student.image_url) {
        return this.student.image_url;
      }
      
      // Запасной вариант - генерируем аватарку
      return 'https://ui-avatars.com/api/?name=' + 
        encodeURIComponent(this.student.first_name + ' ' + this.student.last_name) + 
        '&color=7F9CF5&background=EBF4FF';
    },
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    if (this.form.region) {
      this.loadCities();
    }
    
    // Разбор существующего значения класса
    if (this.form.class) {
      const match = this.form.class.match(/^(\d+)([A-E])$/);
      if (match) {
        this.selectedGrade = parseInt(match[1]);
        this.selectedLetter = match[2];
      }
    }
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  methods: {
    update() {
      // Avatar is not editable from this form
      this.form.avatar = null;
      
      this.form.put(`/students/${this.student.id}`);
    },
    destroy() {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете видалити цього студента?' : 'Are you sure you want to delete this student?')) {
        this.$inertia.delete(`/students/${this.student.id}`);
      }
    },
    updateLanguage(event) {
      this.language = event.detail.language;
    },
    loadCities() {
      this.form.city = null;
      this.form.district = null;
      this.cities = [];
      this.isKyivSelected = false;
      
      if (this.form.region) {
        axios.get(`/cities/${encodeURIComponent(this.form.region)}`)
          .then(response => {
            this.cities = response.data.cities;
            
            // If any of the cities is Kyiv, check and handle it
            const kyivCity = this.cities.find(city => 
              ['Київ', 'Киев', 'Kyiv'].includes(city)
            );
            
            if (kyivCity) {
              this.form.city = kyivCity;
              this.isKyivSelected = true;
            }
          })
          .catch(error => {
            console.error('Error loading cities:', error);
          });
      }
    },
    updateClass() {
      this.form.class = `${this.selectedGrade}${this.selectedLetter}`;
    },
    formatPostalCode() {
      let digitsOnly = this.form.postal_code?.replace(/\D/g, '') || '';
      
      if (digitsOnly.length > 5) {
        digitsOnly = digitsOnly.substring(0, 5);
      }
      
      this.form.postal_code = digitsOnly;
    },
  },
}
</script>

<style scoped>
/* Add any specific styles needed for the form here */
</style>

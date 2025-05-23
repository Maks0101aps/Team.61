<template>
  <div>
  <Head :title="language === 'uk' ? 'Створити вчителя' : 'Create Teacher'" />

    <!-- Header with breadcrumbs and title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/teachers">
            {{ language === 'uk' ? 'Вчителі' : 'Teachers' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Створення' : 'Create' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Створення вчителя' : 'Create Teacher' }}
  </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/teachers" 
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
      <form @submit.prevent="store">
        <!-- Form header -->
        <div class="px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            {{ language === 'uk' ? 'Інформація про вчителя' : 'Teacher Information' }}
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            {{ language === 'uk' ? 'Заповніть дані для створення нового вчителя в системі' : 'Fill in the data to create a new teacher in the system' }}
          </p>
        </div>
        
        <!-- Form body -->
        <div class="p-8">
          <!-- Personal Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Особисті дані' : 'Personal Information' }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input v-model="form.first_name" :error="form.errors.first_name" :label="currentLanguageLabels.first_name" />
              <text-input v-model="form.middle_name" :error="form.errors.middle_name" :label="currentLanguageLabels.middle_name" />
              <text-input v-model="form.last_name" :error="form.errors.last_name" :label="currentLanguageLabels.last_name" />
            </div>
          </div>
          
          <!-- Contact Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Контактна інформація' : 'Contact Information' }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.email" 
                :error="form.errors.email" 
                :label="currentLanguageLabels.email" 
                type="email" 
              />
              <text-input 
                v-model="form.phone" 
                :error="form.errors.phone" 
                :label="currentLanguageLabels.phone" 
                type="phone"
                :help-text="language === 'uk' ? 'Введіть номер телефону у форматі +380 XX XXX-XX-XX' : 'Enter phone number in format +380 XX XXX-XX-XX'"
              />
            </div>
          </div>
          
          <!-- Professional Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Професійна інформація' : 'Professional Information' }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <select-input 
                v-model="form.subject" 
                :error="form.errors.subject" 
                :label="currentLanguageLabels.subject"
                :help-text="language === 'uk' ? 'Оберіть предмет, який викладатиме вчитель' : 'Select the subject the teacher will teach'"
              >
                <option :value="null">{{ language === 'uk' ? 'Оберіть предмет' : 'Select subject' }}</option>
                <option v-for="subject in subjects" :key="subject" :value="subject">{{ subject }}</option>
              </select-input>
              
              <select-input 
                v-model="form.qualification" 
                :error="form.errors.qualification" 
                :label="currentLanguageLabels.qualification"
                :help-text="language === 'uk' ? 'Рівень кваліфікації вчителя' : 'Teacher qualification level'"
              >
                <option :value="null">{{ language === 'uk' ? 'Оберіть кваліфікацію' : 'Select qualification' }}</option>
                <option v-for="qualification in qualifications" :key="qualification" :value="qualification">{{ qualification }}</option>
              </select-input>
            </div>
          </div>
          
          <!-- Address Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Адреса' : 'Address' }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.address" 
                :error="form.errors.address" 
                :label="currentLanguageLabels.address" 
                class="lg:col-span-2"
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
                v-if="!isKyivSelected"
                v-model="form.city" 
                :error="form.errors.city" 
                :label="currentLanguageLabels.city"
                :disabled="!cities.length"
                @change="handleCityChange"
                :help-text="language === 'uk' ? 'Спочатку оберіть область для завантаження міст' : 'First select a region to load cities'"
              >
                <option :value="null">{{ cities.length ? (language === 'uk' ? 'Оберіть місто' : 'Select city') : (language === 'uk' ? 'Спочатку оберіть область' : 'First select a region') }}</option>
                <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
              </select-input>
              
              <select-input 
                v-if="isKyivSelected"
                v-model="form.district" 
                :error="form.errors.district" 
                :label="currentLanguageLabels.district"
                :help-text="language === 'uk' ? 'Оберіть район Києва' : 'Select Kyiv district'"
              >
                <option :value="null">{{ language === 'uk' ? 'Оберіть район' : 'Select district' }}</option>
                <option v-for="district in kyivDistricts" :key="district" :value="district">{{ district }}</option>
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
                maxlength="5"
                @input="formatPostalCode" 
              />
            </div>
          </div>
        </div>
        
        <!-- Form footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-end gap-3">
          <Link 
            href="/teachers" 
            class="w-full sm:w-auto px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
            {{ language === 'uk' ? 'Скасувати' : 'Cancel' }}
          </Link>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            buttonType="submit"
            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
            {{ language === 'uk' ? 'Створити вчителя' : 'Create Teacher' }}
          </loading-button>
      </div>
    </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import TextInput from '@/Shared/TextInput.vue'
import axios from 'axios'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    subjects: {
      type: Array,
      default: () => []
    },
    qualifications: {
      type: Array,
      default: () => []
    },
    regions: {
      type: Array,
      default: () => []
    },
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: null,
        middle_name: null,
        last_name: null,
        email: null,
        phone: null,
        subject: null,
        qualification: null,
        address: null,
        city: null,
        district: null,
        region: null,
        country: "UA",
        postal_code: null,
      }),
      cities: [],
      language: localStorage.getItem('language') || 'uk',
      isKyivSelected: false,
      kyivDistricts: [
        'Голосіївський',
        'Дарницький',
        'Деснянський',
        'Дніпровський',
        'Оболонський',
        'Печерський',
        'Подільський',
        'Святошинський',
        'Солом\'янський',
        'Шевченківський'
      ],
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  methods: {
    store() {
      this.form.post('/teachers')
    },
    loadCities() {
      this.form.city = null;
      this.form.district = null;
      this.cities = [];
      this.isKyivSelected = false;
      
      if (this.form.region) {
        axios.get(`/teachers/cities/${encodeURIComponent(this.form.region)}`)
          .then(response => {
            this.cities = response.data.cities;
            
            
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
    handleCityChange() {
      this.form.district = null;
      this.isKyivSelected = ['Київ', 'Киев', 'Kyiv'].includes(this.form.city);
    },
    updateLanguage(event) {
      
      if (Array.isArray(event.detail)) {
        this.language = event.detail[0];
      } else if (event.detail && event.detail.language) {
        this.language = event.detail.language;
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
    currentLanguageLabels() {
      return {
        en: {
          first_name: 'First Name',
          middle_name: 'Middle Name',
          last_name: 'Last Name',
          email: 'Email',
          phone: 'Phone',
          subject: 'Subject',
          qualification: 'Qualification',
          address: 'Address',
          city: 'City',
          district: 'District',
          region: 'Region',
          country: 'Country',
          postal_code: 'Postal Code',
        },
        uk: {
          first_name: 'Ім\'я',
          middle_name: 'По батькові',
          last_name: 'Прізвище',
          email: 'Email',
          phone: 'Телефон',
          subject: 'Предмет',
          qualification: 'Кваліфікація',
          address: 'Адреса',
          city: 'Місто',
          district: 'Район',
          region: 'Область',
          country: 'Країна',
          postal_code: 'Поштовий індекс',
        },
      }[this.language || 'uk']
    },
  },
}
</script> 

<style scoped>

</style> 
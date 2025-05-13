<template>
  <div>
    <Head :title="language === 'uk' ? 'Створення студента' : 'Create Student'" />
    
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
          <span class="text-gray-700">{{ language === 'uk' ? 'Створення' : 'Create' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Створення студента' : 'Create Student' }}
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
      <form @submit.prevent="store">
        <!-- Form header -->
        <div class="px-6 sm:px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            {{ language === 'uk' ? 'Інформація про студента' : 'Student Information' }}
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            {{ language === 'uk' ? 'Заповніть особисті дані студента' : 'Fill in the student personal information' }}
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
                :required="true"
                :help-text="language === 'uk' ? 'На цю адресу буде відправлено дані для входу в систему' : 'Login credentials will be sent to this email'"
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
                @change="handleCityChange"
                :disabled="!cities.length"
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
          <Link 
            href="/students" 
            class="w-full sm:w-auto px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
            {{ language === 'uk' ? 'Скасувати' : 'Cancel' }}
          </Link>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            buttonType="submit"
            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
            {{ language === 'uk' ? 'Створити студента' : 'Create Student' }}
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
import PhoneInput from '@/Components/PhoneInput.vue'
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
    regions: {
      type: Array,
      default: () => []
    }
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
        date_of_birth: null,
        address: null,
        city: null,
        district: null,
        region: null,
        country: "UA",
        postal_code: null,
        gender: null,
        course: null,
        grade: null,
        class: null,
      }),
      language: localStorage.getItem('language') || 'uk',
      cities: [],
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
      selectedGrade: null,
      selectedLetter: null,
      classLetters: ['A', 'B', 'C', 'D', 'E'],
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
      this.form.post('/students')
    },
    updateLanguage(event) {
      if (event.detail && event.detail.language) {
        this.language = event.detail.language;
      } else if (Array.isArray(event.detail) && event.detail.length > 0) {
        this.language = event.detail[0];
      } else if (typeof event === 'string') {
        this.language = event;
      } else {
        
        this.language = localStorage.getItem('language') || 'uk';
      }
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
    updateClass() {
      const letterMapping = {
        'A': 'А',
        'B': 'Б',
        'C': 'В',
        'D': 'Г',
        'E': 'Д',
      };
      const ukrainianLetter = letterMapping[this.selectedLetter] || this.selectedLetter;
      if (this.selectedGrade && this.selectedLetter) {
        this.form.class = `${this.selectedGrade}-${ukrainianLetter}`;
      } else {
        this.form.class = null; 
      }
    },
    formatPostalCode() {
      let digitsOnly = this.form.postal_code?.replace(/\D/g, '') || '';
      
      if (digitsOnly.length > 5) {
        digitsOnly = digitsOnly.substring(0, 5);
      }
      
      this.form.postal_code = digitsOnly;
    },
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
          date_of_birth: 'Date of Birth',
          address: 'Address',
          city: 'City',
          district: 'District',
          region: 'Region',
          country: 'Country',
          postal_code: 'Postal Code',
          gender: 'Gender',
          course: 'Course',
          grade: 'Grade',
        },
        uk: {
          first_name: 'Ім\'я',
          middle_name: 'По батькові',
          last_name: 'Прізвище',
          email: 'Email',
          phone: 'Телефон',
          date_of_birth: 'Дата народження',
          address: 'Адреса',
          city: 'Місто',
          district: 'Район',
          region: 'Область',
          country: 'Країна',
          postal_code: 'Поштовий індекс',
          gender: 'Стать',
          course: 'Курс',
          grade: 'Клас',
        },
      }[this.language || 'uk']
    },
  },
}
</script>

<style scoped>

</style>

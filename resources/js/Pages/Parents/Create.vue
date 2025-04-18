<template>
  <div>
    <Head :title="language === 'uk' ? 'Створити батьків' : 'Create Parent'" />
    
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
          <span class="text-gray-700">{{ language === 'uk' ? 'Створення' : 'Create' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Створення батьків' : 'Create Parent' }}
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
      <form @submit.prevent="store">
        <!-- Form header -->
        <div class="px-6 sm:px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            {{ language === 'uk' ? 'Інформація про батьків' : 'Parent Information' }}
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            {{ language === 'uk' ? 'Заповніть дані для створення нового запису про батьків в системі' : 'Fill out the data to create a new parent record in the system' }}
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
                option-label="full_name"
                option-value="id"
                :placeholder="language === 'uk' ? 'Оберіть учнів, які є дітьми цих батьків' : 'Select students who are children of this parent'"
                :help-text="language === 'uk' ? 'Оберіть учнів зі списку, які пов\'язані з цими батьками' : 'Select students from the list who are related to this parent'"
              />
            </div>
          </div>
        </div>
        
        <!-- Form footer -->
        <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-end gap-3">
          <Link 
            href="/parents" 
            class="w-full sm:w-auto px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
            {{ language === 'uk' ? 'Скасувати' : 'Cancel' }}
          </Link>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            buttonType="submit"
            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
            {{ language === 'uk' ? 'Створити батьків' : 'Create Parent' }}
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
    students: Array,
    regions: {
      type: Array,
      default: () => []
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: null,
        middle_name: null,
        last_name: null,
        email: null,
        phone: null,
        parent_type: null,
        address: null,
        city: null,
        district: null,
        region: null,
        country: "UA",
        postal_code: null,
        children: [],
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
      this.form.post('/parents')
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
    updateLanguage(event) {
      this.language = event.detail.language;
    },
    handleCityChange() {
      this.form.district = null;
      this.isKyivSelected = ['Київ', 'Киев', 'Kyiv'].includes(this.form.city);
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
          address: 'Address',
          city: 'City',
          region: 'Region',
          country: 'Country',
          postal_code: 'Postal Code',
          children: 'Children',
          district: 'District',
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
          district: 'Район',
        },
      }[this.language || 'uk']
    },
  },
}
</script> 
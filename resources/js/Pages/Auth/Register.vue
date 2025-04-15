<template>
  <Head title="Register" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <div class="w-full max-w-xl">
      <div class="flex justify-end mb-4">
        <div class="inline-flex rounded-full shadow-md overflow-hidden">
          <button @click="setLanguage('uk')" 
                  type="button" 
                  class="relative px-5 py-2 text-sm font-medium transition-all duration-300 group overflow-hidden"
                  :class="language === 'uk' 
                    ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white' 
                    : 'bg-white text-gray-700 hover:bg-blue-50'">
            <span class="relative z-10">Українська</span>
            <span v-if="language === 'uk'" class="absolute inset-0 bg-blue-600 animate-pulse opacity-20"></span>
            <span class="absolute bottom-0 left-0 h-1 w-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                  :class="language === 'uk' ? 'bg-white' : 'bg-blue-500'"></span>
          </button>
          <button @click="setLanguage('en')" 
                  type="button" 
                  class="relative px-5 py-2 text-sm font-medium transition-all duration-300 group overflow-hidden"
                  :class="language === 'en' 
                    ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white' 
                    : 'bg-white text-gray-700 hover:bg-blue-50'">
            <span class="relative z-10">English</span>
            <span v-if="language === 'en'" class="absolute inset-0 bg-blue-600 animate-pulse opacity-20"></span>
            <span class="absolute bottom-0 left-0 h-1 w-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                  :class="language === 'en' ? 'bg-white' : 'bg-blue-500'"></span>
          </button>
        </div>
      </div>
      
      <logo class="block mx-auto w-full max-w-md" height="100" />
      <div class="mt-6 scale-110">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden register-form">
          <form @submit.prevent="register">
            <div class="px-12 py-14">
              <h1 class="text-center text-4xl font-bold text-gray-800">{{ language === 'uk' ? 'Реєстрація' : 'Register' }}</h1>
              <div class="mt-6 mx-auto w-32 border-b-2 border-blue-300" />
              
              <div class="mt-12 grid grid-cols-2 gap-6 register-form-item" :style="{ animationDelay: '0.1s' }">
                <text-input v-model="form.first_name" :error="form.errors.first_name" class="col-span-1 text-lg" :label="language === 'uk' ? 'Ім\'я' : 'First Name'" />
                <text-input 
                  v-model="form.last_name" 
                  :error="form.errors.last_name" 
                  class="col-span-1 text-lg" 
                  :label="language === 'uk' ? 'Прізвище' : 'Last Name'" 
                  @update:modelValue="onLastNameChange"
                />
              </div>
              
              <text-input v-model="form.middle_name" :error="form.errors.middle_name" class="mt-8 text-lg register-form-item" :style="{ animationDelay: '0.2s' }" :label="language === 'uk' ? 'По батькові (необов\'язково)' : 'Middle Name (Optional)'" />
              <text-input v-model="form.email" :error="form.errors.email" class="mt-8 text-lg register-form-item" :style="{ animationDelay: '0.3s' }" :label="language === 'uk' ? 'Електронна пошта' : 'Email'" type="email" autocapitalize="off" />
              <text-input v-model="form.password" :error="form.errors.password" class="mt-8 text-lg register-form-item" :style="{ animationDelay: '0.4s' }" :label="language === 'uk' ? 'Пароль' : 'Password'" type="password" />
              <text-input v-model="form.password_confirmation" :error="form.errors.password_confirmation" class="mt-8 text-lg register-form-item" :style="{ animationDelay: '0.5s' }" :label="language === 'uk' ? 'Підтвердження паролю' : 'Confirm Password'" type="password" />
              
              <select-input v-model="form.role" :error="form.errors.role" class="mt-8 text-lg register-form-item" :style="{ animationDelay: '0.6s' }" :label="language === 'uk' ? 'Роль' : 'Role'" @update:modelValue="onRoleChange">
                <option value="" disabled>{{ language === 'uk' ? 'Оберіть роль' : 'Select a role' }}</option>
                <option v-for="(label, value) in getLocalizedRoles" :key="value" :value="value">{{ label }}</option>
              </select-input>
              
              <!-- Teacher fields (only visible for teacher role) -->
              <div v-if="form.role === 'teacher'" class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                  {{ language === 'uk' ? 'Інформація про вчителя' : 'Teacher Information' }}
                </h3>
                
                <!-- Professional Info Section -->
                <div class="mb-4">
                  <select-input 
                    v-model="form.subject" 
                    :error="form.errors.subject" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Предмет' : 'Subject'" 
                    :help-text="language === 'uk' ? 'Оберіть предмет, який викладатимете' : 'Select the subject you will teach'"
                  >
                    <option value="" disabled>{{ language === 'uk' ? 'Оберіть предмет' : 'Select subject' }}</option>
                    <option v-for="subject in subjects" :key="subject" :value="subject">{{ subject }}</option>
                  </select-input>
                  
                  <select-input 
                    v-model="form.qualification" 
                    :error="form.errors.qualification" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Кваліфікація' : 'Qualification'" 
                    :help-text="language === 'uk' ? 'Рівень вашої кваліфікації' : 'Your qualification level'"
                  >
                    <option value="" disabled>{{ language === 'uk' ? 'Оберіть кваліфікацію' : 'Select qualification' }}</option>
                    <option v-for="qualification in qualifications" :key="qualification" :value="qualification">{{ qualification }}</option>
                  </select-input>
                </div>
                
                <!-- Address Section -->
                <div class="mb-4">
                  <text-input 
                    v-model="form.phone" 
                    :error="form.errors.phone" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Телефон' : 'Phone'" 
                    placeholder="+380"
                    maxlength="13"
                    @focus="ensurePhonePrefix"
                    @input="formatPhoneNumber"
                    @keypress="validatePhoneInput"
                  />
                  
                  <select-input 
                    v-model="form.region" 
                    :error="form.errors.region" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Область' : 'Region'" 
                    @change="onRegionChange"
                    :help-text="language === 'uk' ? 'Оберіть область проживання' : 'Select region of residence'"
                  >
                    <option value="" disabled>{{ language === 'uk' ? 'Оберіть область' : 'Select region' }}</option>
                    <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                  </select-input>
                  
                  <!-- City selector (only show if region is not м. Київ) -->
                  <select-input 
                    v-if="form.region && form.region !== 'м. Київ'"
                    v-model="form.city" 
                    :error="form.errors.city" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Місто' : 'City'" 
                    :disabled="!cities.length"
                    :help-text="cities.length ? (language === 'uk' ? 'Оберіть місто' : 'Select city') : (language === 'uk' ? 'Спочатку оберіть область для завантаження міст' : 'First select a region to load cities')"
                    @change="onCityChange"
                  >
                    <option value="" disabled>{{ cities.length ? (language === 'uk' ? 'Оберіть місто' : 'Select city') : (language === 'uk' ? 'Спочатку оберіть область' : 'First select a region') }}</option>
                    <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                  </select-input>

                  <!-- Kyiv districts selector (only show if region is м. Київ) -->
                  <select-input 
                    v-if="form.region === 'м. Київ'"
                    v-model="form.district" 
                    :error="form.errors.district" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Район Києва' : 'Kyiv District'" 
                    :help-text="language === 'uk' ? 'Оберіть район Києва' : 'Select Kyiv district'"
                  >
                    <option value="" disabled>{{ language === 'uk' ? 'Оберіть район' : 'Select district' }}</option>
                    <option v-for="district in kyivDistricts" :key="district" :value="district">{{ district }}</option>
                  </select-input>

                  <text-input 
                    v-model="form.street" 
                    :error="form.errors.street" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Вулиця' : 'Street'" 
                    :help-text="language === 'uk' ? 'Введіть назву вулиці' : 'Enter street name'"
                  />

                  <text-input 
                    v-model="form.house_number" 
                    :error="form.errors.house_number" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Номер будинку' : 'House number'" 
                    :help-text="language === 'uk' ? 'Введіть номер будинку' : 'Enter house number'"
                  />
                  
                  <text-input 
                    v-model="form.postal_code" 
                    :error="form.errors.postal_code" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Поштовий індекс' : 'Postal Code'" 
                    :help-text="language === 'uk' ? 'Введіть поштовий індекс у форматі 12345' : 'Enter postal code in 12345 format'"
                    @input="formatPostalCode"
                  />
                </div>
              </div>
              
              <!-- Parent type selection (only visible for parent role) -->
              <select-input 
                v-if="form.role === 'parent'" 
                v-model="form.parent_type" 
                :error="form.errors.parent_type" 
                class="mt-8 text-lg" 
                :label="language === 'uk' ? 'Тип (мати/батько)' : 'Parent Type'"
              >
                <option value="" disabled>{{ language === 'uk' ? 'Оберіть тип' : 'Select type' }}</option>
                <option v-for="(label, value) in getLocalizedParentTypes" :key="value" :value="value">{{ label }}</option>
              </select-input>
              
              <!-- Parent address fields (only visible for parent role) -->
              <div v-if="form.role === 'parent'" class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                  {{ language === 'uk' ? 'Адресна інформація' : 'Address Information' }}
                </h3>
                
                <div class="mb-4">
                  <select-input 
                    v-model="form.region" 
                    :error="form.errors.region" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Область' : 'Region'" 
                    @change="loadCities"
                    :help-text="language === 'uk' ? 'Оберіть область проживання' : 'Select region of residence'"
                  >
                    <option value="" disabled>{{ language === 'uk' ? 'Оберіть область' : 'Select region' }}</option>
                    <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                  </select-input>
                  
                  <select-input 
                    v-model="form.city" 
                    :error="form.errors.city" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Місто' : 'City'" 
                    :disabled="!cities.length"
                    :help-text="cities.length ? (language === 'uk' ? 'Оберіть місто' : 'Select city') : (language === 'uk' ? 'Спочатку оберіть область для завантаження міст' : 'First select a region to load cities')"
                  >
                    <option value="" disabled>{{ cities.length ? (language === 'uk' ? 'Оберіть місто' : 'Select city') : (language === 'uk' ? 'Спочатку оберіть область' : 'First select a region') }}</option>
                    <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                  </select-input>

                  <text-input 
                    v-model="form.street" 
                    :error="form.errors.street" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Вулиця' : 'Street'" 
                    :help-text="language === 'uk' ? 'Введіть назву вулиці' : 'Enter street name'"
                  />

                  <text-input 
                    v-model="form.house_number" 
                    :error="form.errors.house_number" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Номер будинку' : 'House number'" 
                    :help-text="language === 'uk' ? 'Введіть номер будинку' : 'Enter house number'"
                  />
                  
                  <text-input 
                    v-model="form.postal_code" 
                    :error="form.errors.postal_code" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Поштовий індекс' : 'Postal Code'" 
                    :help-text="language === 'uk' ? 'Введіть поштовий індекс у форматі 12345' : 'Enter postal code in 12345 format'"
                    @input="formatPostalCode"
                  />

                  <text-input 
                    v-model="form.phone" 
                    :error="form.errors.phone" 
                    class="mt-4 text-lg"
                    :label="language === 'uk' ? 'Телефон' : 'Phone'" 
                    placeholder="+380"
                    maxlength="13"
                    @focus="ensurePhonePrefix"
                    @input="formatPhoneNumber"
                    @keypress="validatePhoneInput"
                  />
                </div>
              </div>
              
              <!-- Parent selection (only visible for student role) -->
              <select-input 
                v-if="form.role === 'student'" 
                v-model="form.parent_id" 
                :error="form.errors.parent_id" 
                class="mt-8 text-lg" 
                :label="language === 'uk' ? 'Виберіть батька/матір' : 'Select Parent'"
                :disabled="!form.last_name || filteredParents.length === 0"
                @change="onParentChange"
              >
                <option value="" disabled>
                  <template v-if="!form.last_name">
                    {{ language === 'uk' ? 'Спочатку введіть прізвище' : 'Enter last name first' }}
                  </template>
                  <template v-else-if="filteredParents.length === 0">
                    {{ language === 'uk' ? 'Немає батьків з таким прізвищем' : 'No parents with this last name' }}
                  </template>
                  <template v-else>
                    {{ language === 'uk' ? 'Оберіть батька/матір' : 'Select parent' }}
                  </template>
                </option>
                <option v-for="parent in filteredParents" :key="parent.id" :value="parent.id">
                  {{ parent.first_name }} {{ parent.middle_name }} {{ parent.last_name }}
                </option>
              </select-input>
              
              <!-- Display parent address for student (non-editable) -->
              <div v-if="form.role === 'student' && form.parent_id && parentAddress" class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                  {{ language === 'uk' ? 'Адресна інформація (від батька/матері)' : 'Address Information (from parent)' }}
                </h3>
                
                <div class="mb-4 grid grid-cols-2 gap-6">
                  <div class="col-span-1">
                    <p class="text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Область' : 'Region' }}</p>
                    <p class="text-base border p-2 rounded-md bg-gray-50">{{ parentAddress.region }}</p>
                  </div>
                  <div class="col-span-1">
                    <p class="text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Місто' : 'City' }}</p>
                    <p class="text-base border p-2 rounded-md bg-gray-50">{{ parentAddress.city }}</p>
                  </div>
                  <div class="col-span-1">
                    <p class="text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Вулиця' : 'Street' }}</p>
                    <p class="text-base border p-2 rounded-md bg-gray-50">{{ parentAddress.street }}</p>
                  </div>
                  <div class="col-span-1">
                    <p class="text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Номер будинку' : 'House number' }}</p>
                    <p class="text-base border p-2 rounded-md bg-gray-50">{{ parentAddress.house_number }}</p>
                  </div>
                  <div class="col-span-1">
                    <p class="text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Поштовий індекс' : 'Postal Code' }}</p>
                    <p class="text-base border p-2 rounded-md bg-gray-50">{{ parentAddress.postal_code }}</p>
                  </div>
                  <div class="col-span-1">
                    <p class="text-sm font-medium text-gray-700 mb-1">{{ language === 'uk' ? 'Телефон' : 'Phone' }}</p>
                    <p class="text-base border p-2 rounded-md bg-gray-50">{{ parentAddress.phone }}</p>
                  </div>
                </div>
              </div>
              
              <div class="mt-8 flex items-center justify-between register-form-item" :style="{ animationDelay: '0.7s' }">
                <div class="flex items-center">
                  <Link href="/login" class="text-blue-700 hover:text-blue-900 text-base login-link">
                    {{ language === 'uk' ? 'Вже маєте обліковий запис? Увійти' : 'Already have an account? Login' }}
                  </Link>
                </div>
              </div>
            </div>
            <div class="flex px-12 py-5 bg-gradient-to-r from-blue-50 to-gray-50 border-t border-gray-100 register-form-item" :style="{ animationDelay: '0.8s' }">
              <loading-button 
                :loading="form.processing" 
                class="btn-blue ml-auto" 
                type="submit"
                :disabled="form.processing"
              >
                {{ language === 'uk' ? 'Зареєструватися' : 'Register' }}
              </loading-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link, router } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import axios from 'axios'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    Logo,
    TextInput,
    SelectInput,
  },
  props: {
    roles: Object,
    parents: Array,
    parentTypes: Object,
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
  data() {
    console.log('Parents received:', this.parents);
    
    // Выводим детальную информацию о первом родителе для отладки структуры данных
    if (this.parents && this.parents.length > 0) {
      console.log('First parent structure:');
      for (const key in this.parents[0]) {
        console.log(`${key}:`, this.parents[0][key]);
      }
    }
    
    return {
      language: localStorage.getItem('language') || 'uk',
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        middle_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
        parent_id: '',
        parent_type: '',
        subject: '',
        qualification: '',
        phone: '',
        region: '',
        city: '',
        district: '',
        street: '',
        house_number: '',
        postal_code: '',
      }),
      ukRoles: {
        teacher: 'Вчитель',
        student: 'Учень',
        parent: 'Батько/Мати',
        admin: 'Адміністратор',
      },
      cities: [],
      parentAddress: null,
      kyivDistricts: [
        'Голосіївський',
        'Дарницький',
        'Деснянський',
        'Дніпровський',
        'Оболонський',
        'Печерський',
        'Подільський',
        'Святошинський',
        'Соломянський',
        'Шевченківський'
      ],
    }
  },
  computed: {
    getLocalizedRoles() {
      if (this.language === 'uk') {
        const localizedRoles = {};
        for (const [key, value] of Object.entries(this.roles)) {
          localizedRoles[key] = this.ukRoles[key] || value;
        }
        return localizedRoles;
      }
      return this.roles;
    },
    getLocalizedParentTypes() {
      return this.parentTypes;
    },
    filteredParents() {
      if (!this.form.last_name || !this.parents) return [];
      
      return this.parents.filter(parent => 
        parent.last_name.toLowerCase() === this.form.last_name.toLowerCase()
      );
    }
  },
  methods: {
    register() {
      console.log('Register method called');

      // Add basic validation
      this.form.clearErrors();

      // Check if required fields are filled
      if (!this.form.first_name) {
        this.form.errors.first_name = this.language === 'uk' ? 'Ім\'я обов\'язкове' : 'First name is required';
        return;
      }

      if (!this.form.last_name) {
        this.form.errors.last_name = this.language === 'uk' ? 'Прізвище обов\'язкове' : 'Last name is required';
        return;
      }

      if (!this.form.email) {
        this.form.errors.email = this.language === 'uk' ? 'Email обов\'язковий' : 'Email is required';
        return;
      }

      if (!this.form.password) {
        this.form.errors.password = this.language === 'uk' ? 'Пароль обов\'язковий' : 'Password is required';
        return;
      }

      if (!this.form.role) {
        this.form.errors.role = this.language === 'uk' ? 'Роль обов\'язкова' : 'Role is required';
        return;
      }
      
      // Add validation for phone number
      if (this.form.phone && !this.validatePhoneNumber()) {
        console.log('Phone validation failed');
        return;
      }
      
      // Add validation for required fields based on role
      if (this.form.role === 'teacher') {
        if (!this.form.subject) {
          this.form.errors.subject = this.language === 'uk' ? 'Оберіть предмет' : 'Please select a subject';
          return;
        }
        if (!this.form.qualification) {
          this.form.errors.qualification = this.language === 'uk' ? 'Оберіть кваліфікацію' : 'Please select qualification';
          return;
        }
      }
      
      if (this.form.role === 'parent' && !this.form.parent_type) {
        this.form.errors.parent_type = this.language === 'uk' ? 'Оберіть тип' : 'Please select parent type';
        return;
      }
      
      if (this.form.role === 'student' && !this.form.parent_id) {
        this.form.errors.parent_id = this.language === 'uk' ? 'Оберіть батька/матір' : 'Please select a parent';
        return;
      }
      
      // Save original phone for restoration if needed
      const originalPhone = this.form.phone;
      
      // Clean phone number (remove formatting)
      if (this.form.phone) {
        this.form.phone = this.form.phone.replace(/\s+/g, '').replace(/[()-]/g, '');
      }
      
      console.log('Submitting form with data:', this.form);
      
      // Submit the form
      this.form.post('/register', {
        onFinish: () => {
          console.log('Form submission finished');
          // Only reset password fields, not the whole form
          this.form.reset('password', 'password_confirmation');
          // Restore formatted phone number
          this.form.phone = originalPhone;
        },
        onSuccess: () => {
          console.log('Registration successful');
        },
        onError: (errors) => {
          console.error('Registration failed with errors:', errors);
          // Restore formatted phone number
          this.form.phone = originalPhone;
        }
      });
    },
    setLanguage(lang) {
      this.language = lang
      localStorage.setItem('language', lang)
    },
    onRoleChange(role) {
      console.log('Role changed to:', role);
      // Reset parent_id and parent_type when changing roles
      this.form.parent_id = '';
      this.form.parent_type = '';
      this.parentAddress = null;
      
      // Reset teacher fields when changing roles
      if (role !== 'teacher') {
        this.form.subject = '';
        this.form.qualification = '';
        this.form.phone = '';
        this.form.region = '';
        this.form.city = '';
        this.form.street = '';
        this.form.house_number = '';
        this.form.postal_code = '';
        this.cities = [];
      } else {
        // If switching to teacher role, make sure we have the city options if region is already selected
        if (this.form.region) {
          this.loadCities();
        }
      }
    },
    onLastNameChange(value) {
      this.form.parent_id = '';
      
      if (this.form.role === 'student') {
        console.log('Last name changed to:', value);
        console.log('Filtered parents:', this.filteredParents);
      }
    },
    formatPostalCode() {
      // Удаляем все нецифровые символы
      let digitsOnly = this.form.postal_code?.replace(/\D/g, '') || '';
      
      // Ограничиваем до 5 цифр для украинского индекса
      if (digitsOnly.length > 5) {
        digitsOnly = digitsOnly.substring(0, 5);
      }
      
      // Обновляем значение в форме
      this.form.postal_code = digitsOnly;
      
      // Если у нас полный индекс (5 цифр), ищем адрес
      if (digitsOnly.length === 5) {
        this.lookupAddressByPostalCode(digitsOnly);
      }
    },
    lookupAddressByPostalCode(postalCode) {
      console.log('Looking up address for postal code:', postalCode);
      
      // Здесь можно реализовать запрос к API Укрпочты или использовать статический словарь индексов
      // Для демонстрации будем использовать несколько тестовых индексов
      const postalCodeDatabase = {
        // Киев
        '01001': { region: 'м. Київ', city: 'Київ' },
        '02000': { region: 'м. Київ', city: 'Київ' },
        '03000': { region: 'м. Київ', city: 'Київ' },
        '04000': { region: 'м. Київ', city: 'Київ' },
        
        // Львовская область
        '79000': { region: 'Львівська область', city: 'Львів' },
        '79013': { region: 'Львівська область', city: 'Львів' },
        '80100': { region: 'Львівська область', city: 'Червоноград' },
        '80300': { region: 'Львівська область', city: 'Жовква' },
        '82100': { region: 'Львівська область', city: 'Дрогобич' },
        
        // Днепропетровская область
        '49000': { region: 'Дніпропетровська область', city: 'Дніпро' },
        '49044': { region: 'Дніпропетровська область', city: 'Дніпро' },
        '50000': { region: 'Дніпропетровська область', city: 'Кривий Ріг' },
        '51400': { region: 'Дніпропетровська область', city: 'Павлоград' },
        '52200': { region: 'Дніпропетровська область', city: 'Жовті Води' },
        
        // Одесская область
        '65000': { region: 'Одеська область', city: 'Одеса' },
        '65009': { region: 'Одеська область', city: 'Одеса' },
        '68000': { region: 'Одеська область', city: 'Чорноморськ' },
        '68600': { region: 'Одеська область', city: 'Ізмаїл' },
        '66000': { region: 'Одеська область', city: 'Подільськ' },
        
        // Харьковская область
        '61000': { region: 'Харківська область', city: 'Харків' },
        '61166': { region: 'Харківська область', city: 'Харків' },
        '62300': { region: 'Харківська область', city: 'Дергачі' },
        '63700': { region: 'Харківська область', city: 'Куп\'янськ' },
        '64700': { region: 'Харківська область', city: 'Зміїв' },
        
        // Винницкая область
        '21000': { region: 'Вінницька область', city: 'Вінниця' },
        '21100': { region: 'Вінницька область', city: 'Вінниця' },
        '24000': { region: 'Вінницька область', city: 'Могилів-Подільський' },
        
        // Волынская область
        '43000': { region: 'Волинська область', city: 'Луцьк' },
        '44700': { region: 'Волинська область', city: 'Володимир' },
        '45000': { region: 'Волинська область', city: 'Ковель' },
        
        // Закарпатская область
        '88000': { region: 'Закарпатська область', city: 'Ужгород' },
        '89600': { region: 'Закарпатська область', city: 'Мукачево' },
        '90500': { region: 'Закарпатська область', city: 'Хуст' },
      };
      
      // Проверяем, есть ли данные для этого индекса
      const addressInfo = postalCodeDatabase[postalCode];
      
      if (addressInfo) {
        console.log('Found address information:', addressInfo);
        
        // Устанавливаем регион и загружаем города
        if (addressInfo.region && this.regions.includes(addressInfo.region)) {
          this.form.region = addressInfo.region;
          
          // Загружаем города для выбранного региона
          this.loadCities().then(() => {
            // После загрузки городов, выбираем город, если он доступен
            if (addressInfo.city && this.cities.includes(addressInfo.city)) {
              this.form.city = addressInfo.city;
            } else if (addressInfo.city) {
              // Если город не найден в списке доступных городов, но известен из базы индексов,
              // можно показать уведомление пользователю или попытаться использовать другой подход
              console.warn('City not found in available cities list:', addressInfo.city);
              
              // Опциональная обработка - можно добавить город в список доступных
              // Для целей демонстрации, добавляем город в список, если его там нет
              if (!this.cities.includes(addressInfo.city)) {
                this.cities.push(addressInfo.city);
                this.form.city = addressInfo.city;
              }
            }
          });
        }
      } else {
        console.log('No address information found for postal code:', postalCode);
      }
    },
    loadCities() {
      this.form.city = '';
      this.cities = [];
      
      if (this.form.region) {
        console.log('Loading cities for region:', this.form.region);
        return axios.get(`/public/cities/${encodeURIComponent(this.form.region)}`)
          .then(response => {
            this.cities = response.data.cities;
            console.log('Loaded cities:', this.cities);
            return this.cities; // Возвращаем массив городов
          })
          .catch(error => {
            console.error('Error loading cities:', error);
            return []; // В случае ошибки возвращаем пустой массив
          });
      }
      return Promise.resolve([]); // Если регион не выбран, возвращаем пустой массив
    },
    ensurePhonePrefix() {
      // Если поле пустое, устанавливаем префикс +380
      if (!this.form.phone || this.form.phone.trim() === '') {
        this.form.phone = '+380';
      } 
      // Если не начинается с +380, добавляем его
      else if (!this.form.phone.startsWith('+380')) {
        this.form.phone = '+380' + this.form.phone.replace(/\D/g, '');
      }
    },
    formatPhoneNumber() {
      // Сначала убеждаемся, что префикс +380 на месте
      this.ensurePhonePrefix();
      
      // Удаляем все нецифровые символы, кроме +
      let phoneValue = this.form.phone;
      const hasPlus = phoneValue.startsWith('+');
      
      // Получаем только цифры
      let digitsOnly = phoneValue.replace(/\D/g, '');
      
      // Строго ограничиваем до 12 цифр (380 + 9 цифр номера)
      if (digitsOnly.length > 12) {
        digitsOnly = digitsOnly.substring(0, 12);
      }
      
      // Собираем форматированный номер
      let formatted = '+380';
      
      // Добавляем оставшиеся цифры номера
      if (digitsOnly.length > 3) {
        const numberPart = digitsOnly.substring(3);
        formatted += numberPart;
      }
      
      // Форматируем номер в виде +380 XX XXX XX XX
      if (formatted.length > 4) {
        const number = formatted.substring(4);
        formatted = '+380';
        
        if (number.length >= 2) {
          formatted += ' ' + number.substring(0, 2);
        }
        if (number.length >= 5) {
          formatted += ' ' + number.substring(2, 5);
        }
        if (number.length >= 7) {
          formatted += ' ' + number.substring(5, 7);
        }
        if (number.length >= 9) {
          formatted += ' ' + number.substring(7, 9);
        }
      }
      
      this.form.phone = formatted;
    },
    validatePhoneInput(event) {
      const char = String.fromCharCode(event.keyCode || event.which);
      const phoneValue = this.form.phone;
      
      // Получаем только цифры из текущего значения
      const currentDigits = phoneValue.replace(/\D/g, '');
      
      // Если уже есть 12 цифр (380 + 9 цифр номера), блокируем ввод
      if (currentDigits.length >= 12) {
        event.preventDefault();
        return;
      }
      
      // Разрешаем только цифры после +380
      if (phoneValue.startsWith('+380')) {
        if (!/^\d$/.test(char)) {
          event.preventDefault();
        }
      }
    },
    validatePhoneNumber() {
      // Проверяем, что номер соответствует формату +380XXXXXXXXX
      const phoneRegex = /^\+380[0-9]{9}$/;
      const cleanPhone = this.form.phone.replace(/\s+/g, '').replace(/[()-]/g, '');
      
      if (!phoneRegex.test(cleanPhone)) {
        this.form.errors.phone = this.language === 'uk' 
          ? 'Номер телефону повинен відповідати формату +380XXXXXXXXX' 
          : 'Phone number must match the format +380XXXXXXXXX';
        return false;
      }
      return true;
    },
    onParentChange() {
      if (this.form.parent_id) {
        // Находим родителя в массиве parents
        const selectedParent = this.parents.find(parent => parent.id == this.form.parent_id);
        if (selectedParent) {
          console.log('Selected parent from list:', selectedParent);
          
          // Получаем полные данные родителя с сервера
          axios.get(`/public/parent/${this.form.parent_id}`)
            .then(response => {
              const parentData = response.data.parent || {};
              console.log('Parent data from server:', parentData);
              
              // Создаем объект с адресными данными родителя
              this.parentAddress = {
                region: parentData.region || selectedParent.region || 'Не указано',
                city: parentData.city || selectedParent.city || 'Не указано',
                street: parentData.street || selectedParent.street || 'Не указано',
                house_number: parentData.house_number || selectedParent.house_number || 'Не указано',
                postal_code: parentData.postal_code || selectedParent.postal_code || 'Не указано',
                phone: parentData.phone || selectedParent.phone || 'Не указано'
              };
              
              // Скрытые поля для отправки на сервер
              this.form.region = parentData.region || selectedParent.region || '';
              this.form.city = parentData.city || selectedParent.city || '';
              this.form.street = parentData.street || selectedParent.street || '';
              this.form.house_number = parentData.house_number || selectedParent.house_number || '';
              this.form.postal_code = parentData.postal_code || selectedParent.postal_code || '';
              this.form.phone = parentData.phone || selectedParent.phone || '';
              
              console.log('Final parent address data:', this.parentAddress);
            })
            .catch(error => {
              console.error('Error fetching parent details:', error);
              
              // Используем данные из переданного массива
              this.parentAddress = {
                region: selectedParent.region || 'Не указано',
                city: selectedParent.city || 'Не указано',
                street: selectedParent.street || 'Не указано',
                house_number: selectedParent.house_number || 'Не указано',
                postal_code: selectedParent.postal_code || 'Не указано',
                phone: selectedParent.phone || 'Не указано'
              };
              
              // Скрытые поля для отправки на сервер
              this.form.region = selectedParent.region || '';
              this.form.city = selectedParent.city || '';
              this.form.street = selectedParent.street || '';
              this.form.house_number = selectedParent.house_number || '';
              this.form.postal_code = selectedParent.postal_code || '';
              this.form.phone = selectedParent.phone || '';
              
              console.log('Using parent address from list:', this.parentAddress);
            });
        } else {
          console.warn('Parent not found in parents array');
          this.parentAddress = null;
        }
      } else {
        this.parentAddress = null;
      }
    },
    onCityChange() {
      // Reset district when city changes
      this.form.district = '';
    },
    onRegionChange() {
      // Reset city and district when region changes
      this.form.city = '';
      this.form.district = '';
      
      // If region is м. Київ, set city to Київ automatically
      if (this.form.region === 'м. Київ') {
        this.form.city = 'Київ';
      } else {
        // Load cities for other regions
        this.loadCities();
      }
    },
  },
  mounted() {
    if (!localStorage.getItem('language')) {
      localStorage.setItem('language', 'uk')
    }
    
    // Debug parents
    console.log('Parents in mounted:', this.parents);
    if (this.parents) {
      console.log('Parents count:', this.parents.length);
      if (this.parents.length > 0) {
        console.log('First parent:', this.parents[0]);
      }
    }
    
    // Debug subjects and regions
    console.log('Subjects:', this.subjects);
    console.log('Qualifications:', this.qualifications);
    console.log('Regions:', this.regions);
  }
}
</script>

<style>
.btn-blue {
  padding: 0.75rem 2rem;
  border-radius: 0.375rem;
  background-image: linear-gradient(to right, var(--tw-gradient-stops));
  --tw-gradient-from: #6CB4EE;
  --tw-gradient-to: #4A90E2;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
  color: white;
  font-size: 1rem;
  line-height: 1.25rem;
  font-weight: 700;
  white-space: nowrap;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.btn-blue:hover {
  --tw-gradient-from: #4A90E2;
  --tw-gradient-to: #357ABD;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}

.btn-blue:focus {
  --tw-gradient-from: #4A90E2;
  --tw-gradient-to: #357ABD;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}

/* Анимация для формы регистрации */
.register-form {
  animation: fadeIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideInFromRight {
  from {
    opacity: 0;
    transform: translateX(50px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.register-form-item {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Анимация при нажатии на ссылку логина */
.login-link {
  position: relative;
  transition: all 0.3s ease;
}

.login-link:hover {
  transform: translateX(-5px);
}

.login-link:before {
  content: "←";
  opacity: 0;
  margin-right: -5px;
  transition: all 0.3s ease;
}

.login-link:hover:before {
  opacity: 1;
  margin-right: 5px;
}
</style> 
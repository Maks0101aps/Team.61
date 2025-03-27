<template>
  <Head title="Створити вчителя" />

  <h1 class="mb-8 text-3xl font-bold">
    <Link class="text-indigo-400 hover:text-indigo-600" href="/teachers">Вчителі</Link>
    <span class="text-indigo-400 font-medium">/</span>
    Створити
  </h1>

  <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
    <form @submit.prevent="store">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Ім'я" />
        <text-input v-model="form.middle_name" :error="form.errors.middle_name" class="pb-8 pr-6 w-full lg:w-1/2" label="По батькові" />
        <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Прізвище" />
        <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" type="email" />
        <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Телефон" />
        
        <select-input v-model="form.subject" :error="form.errors.subject" class="pb-8 pr-6 w-full lg:w-1/2" label="Предмет">
          <option :value="null">Оберіть предмет</option>
          <option v-for="subject in subjects" :key="subject" :value="subject">{{ subject }}</option>
        </select-input>
        
        <select-input v-model="form.qualification" :error="form.errors.qualification" class="pb-8 pr-6 w-full lg:w-1/2" label="Кваліфікація">
          <option :value="null">Оберіть кваліфікацію</option>
          <option v-for="qualification in qualifications" :key="qualification" :value="qualification">{{ qualification }}</option>
        </select-input>
        
        <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="Адреса" />
        
        <select-input v-model="form.region" :error="form.errors.region" class="pb-8 pr-6 w-full lg:w-1/2" label="Область" @change="loadCities">
          <option :value="null">Оберіть область</option>
          <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
        </select-input>
        
        <select-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="Місто" :disabled="!cities.length">
          <option :value="null">{{ cities.length ? 'Оберіть місто' : 'Спочатку оберіть область' }}</option>
          <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
        </select-input>
        
        <div class="pb-8 pr-6 w-full lg:w-1/2">
          <label class="form-label">Країна</label>
          <div class="form-input bg-gray-50 border border-gray-200 rounded-md flex items-center px-4 py-2">
            <span class="text-blue-800 font-medium">🇺🇦 Україна</span>
            <input type="hidden" v-model="form.country" value="UA" />
          </div>
        </div>
        
        <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="Поштовий індекс" />
      </div>
      <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
        <loading-button :loading="form.processing" class="btn-indigo" type="submit">
          Створити вчителя
        </loading-button>
      </div>
    </form>
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
        subject: null,
        qualification: null,
        address: null,
        city: null,
        region: null,
        country: "UA",
        postal_code: null,
      }),
      cities: [],
    }
  },
  methods: {
    store() {
      this.form.post('/teachers')
    },
    loadCities() {
      this.form.city = null;
      this.cities = [];
      
      if (this.form.region) {
        axios.get(`/teachers/cities/${encodeURIComponent(this.form.region)}`)
          .then(response => {
            this.cities = response.data.cities;
          })
          .catch(error => {
            console.error('Error loading cities:', error);
          });
      }
    }
  },
}
</script> 
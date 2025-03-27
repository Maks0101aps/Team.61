<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/teachers">Вчителі</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
    <trashed-message v-if="teacher.deleted_at" class="mb-6" @restore="restore">
      This teacher has been deleted.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Ім'я" />
          <text-input v-model="form.middle_name" :error="errors.middle_name" class="pb-8 pr-6 w-full lg:w-1/2" label="По-батькові" />
          <text-input v-model="form.last_name" :error="errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Прізвище" />
          <text-input v-model="form.email" :error="errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Телефон" />
          
          <select-input v-model="form.subject" :error="errors.subject" class="pb-8 pr-6 w-full lg:w-1/2" label="Предмет">
            <option :value="null">Оберіть предмет</option>
            <option v-for="subject in subjects" :key="subject" :value="subject">{{ subject }}</option>
          </select-input>
          
          <select-input v-model="form.qualification" :error="errors.qualification" class="pb-8 pr-6 w-full lg:w-1/2" label="Кваліфікація">
            <option :value="null">Оберіть кваліфікацію</option>
            <option v-for="qualification in qualifications" :key="qualification" :value="qualification">{{ qualification }}</option>
          </select-input>
          
          <text-input v-model="form.address" :error="errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="Адреса" />
          
          <select-input v-model="form.region" :error="errors.region" class="pb-8 pr-6 w-full lg:w-1/2" label="Область" @change="loadCities">
            <option :value="null">Оберіть область</option>
            <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
          </select-input>
          
          <select-input v-model="form.city" :error="errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="Місто" :disabled="!cities.length">
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
          
          <text-input v-model="form.postal_code" :error="errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="Поштовий індекс" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!teacher.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Teacher</button>
          <loading-button :loading="processing" class="btn-indigo ml-auto" type="submit">Update Teacher</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import axios from 'axios'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    teacher: Object,
    subjects: Array,
    qualifications: Array,
    regions: Array,
    cities: {
      type: Array,
      default: () => []
    },
    errors: Object,
  },
  data() {
    return {
      form: {
        first_name: this.teacher.first_name,
        middle_name: this.teacher.middle_name,
        last_name: this.teacher.last_name,
        email: this.teacher.email,
        phone: this.teacher.phone,
        subject: this.teacher.subject,
        qualification: this.teacher.qualification,
        address: this.teacher.address,
        city: this.teacher.city,
        region: this.teacher.region,
        country: "UA",
        postal_code: this.teacher.postal_code,
      },
      processing: false,
      cities: this.cities || [],
    }
  },
  methods: {
    update() {
      this.processing = true
      this.$inertia.put(`/teachers/${this.teacher.id}`, this.form, {
        onFinish: () => this.processing = false,
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this teacher?')) {
        this.$inertia.delete(`/teachers/${this.teacher.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this teacher?')) {
        this.$inertia.put(`/teachers/${this.teacher.id}/restore`)
      }
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
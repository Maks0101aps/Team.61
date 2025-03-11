<template>
  <div>
    <Head title="Create Teacher" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/teachers">Вчителі</Link>
      <span class="text-indigo-400 font-medium">/</span> Створити
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Ім'я" />
          <text-input v-model="form.middle_name" :error="errors.middle_name" class="pb-8 pr-6 w-full lg:w-1/2" label="По-батькові" />
          <text-input v-model="form.last_name" :error="errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Прізвище" />
          <select-input v-model="form.organization_id" :error="errors.organization_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Організація">
            <option :value="null" />
            <option v-for="organization in organizations" :key="organization.id" :value="organization.id">{{ organization.name }}</option>
          </select-input>
          <text-input v-model="form.email" :error="errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Телефон" />
          <text-input v-model="form.subject" :error="errors.subject" class="pb-8 pr-6 w-full lg:w-1/2" label="Предмет" />
          <text-input v-model="form.qualification" :error="errors.qualification" class="pb-8 pr-6 w-full lg:w-1/2" label="Кваліфікація" />
          <text-input v-model="form.address" :error="errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="Адреса" />
          <text-input v-model="form.city" :error="errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="Місто" />
          <text-input v-model="form.region" :error="errors.region" class="pb-8 pr-6 w-full lg:w-1/2" label="Область" />
          <select-input v-model="form.country" :error="errors.country" class="pb-8 pr-6 w-full lg:w-1/2" label="Країна">
            <option :value="null" />
            <option value="UA">Ukraine</option>
          </select-input>
          <text-input v-model="form.postal_code" :error="errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="Поштовий індекс" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button class="btn-indigo ml-auto" :disabled="processing">Create Teacher</button>
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

export default {
  components: {
    Head,
    Link,
    TextInput,
    SelectInput,
  },
  layout: Layout,
  props: {
    organizations: Array,
    errors: Object,
  },
  data() {
    return {
      form: {
        first_name: null,
        middle_name: null,
        last_name: null,
        organization_id: null,
        email: null,
        phone: null,
        subject: null,
        qualification: null,
        address: null,
        city: null,
        region: null,
        country: null,
        postal_code: null,
      },
      processing: false,
    }
  },
  methods: {
    store() {
      this.processing = true
      this.$inertia.post('/teachers', this.form, {
        onFinish: () => this.processing = false,
      })
    },
  },
}
</script> 
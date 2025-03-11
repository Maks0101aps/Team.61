<template>
  <Head title="Створити батька" />

  <h1 class="mb-8 text-3xl font-bold">
    <Link class="text-indigo-400 hover:text-indigo-600" href="/parents">Батьки</Link>
    <span class="text-indigo-400 font-medium">/</span>
    Створити
  </h1>

  <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
    <form @submit.prevent="store">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/3" label="Ім'я" />
        <text-input v-model="form.middle_name" :error="form.errors.middle_name" class="pb-8 pr-6 w-full lg:w-1/3" label="По батькові" />
        <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/3" label="Прізвище" />
        <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" type="email" />
        <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Телефон" />
        <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full lg:w-full" label="Адреса" />
        <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="Місто" />
        <text-input v-model="form.region" :error="form.errors.region" class="pb-8 pr-6 w-full lg:w-1/2" label="Область" />
        <select-input v-model="form.country" :error="form.errors.country" class="pb-8 pr-6 w-full lg:w-1/2" label="Країна">
          <option value="UA">Україна</option>
        </select-input>
        <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="Поштовий індекс" />
      </div>
      <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
        <loading-button :loading="form.processing" class="btn-indigo" type="submit">
          Створити батька
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

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: null,
        middle_name: null,
        last_name: null,
        email: null,
        phone: null,
        address: null,
        city: null,
        region: null,
        country: 'UA',
        postal_code: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/parents')
    },
  },
}
</script> 
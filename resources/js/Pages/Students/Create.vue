<template>
  <div>
    <Head :title="language === 'uk' ? 'Створення студента' : 'Create Student'" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/students">{{ language === 'uk' ? 'Студенти' : 'Students' }}</Link>
      <span class="text-indigo-400 font-medium">/</span> {{ language === 'uk' ? 'Створення' : 'Create' }}
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Ім\'я' : 'First Name'" />
          <text-input v-model="form.middle_name" :error="form.errors.middle_name" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'По батькові' : 'Middle Name'" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Прізвище' : 'Last Name'" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Електронна пошта' : 'Email'" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Телефон' : 'Phone'" />
          <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Адреса' : 'Address'" />
          <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Місто' : 'City'" />
          <text-input v-model="form.region" :error="form.errors.region" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Область' : 'Region'" />
          <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Поштовий індекс' : 'Postal Code'" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">{{ language === 'uk' ? 'Створити студента' : 'Create Student' }}</loading-button>
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
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        phone: '',
        address: '',
        city: '',
        region: '',
        postal_code: '',
      }),
      language: localStorage.getItem('language') || 'uk',
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
      this.language = event.detail.language;
    },
  },
}
</script>

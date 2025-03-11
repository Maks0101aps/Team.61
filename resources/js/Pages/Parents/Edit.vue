<template>
  <Head :title="`${form.first_name} ${form.last_name}`" />

  <h1 class="mb-8 text-3xl font-bold">
    <Link class="text-indigo-400 hover:text-indigo-600" href="/parents">Батьки</Link>
    <span class="text-indigo-400 font-medium">/</span>
    {{ form.first_name }} {{ form.last_name }}
  </h1>

  <trashed-message v-if="parent.deleted_at" class="mb-6" @restore="restore">
    Цього батька було видалено.
  </trashed-message>

  <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
    <form @submit.prevent="update">
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
      <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
        <button v-if="!parent.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">
          Видалити батька
        </button>
        <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">
          Оновити батька
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

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
    parent: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.parent.first_name,
        middle_name: this.parent.middle_name,
        last_name: this.parent.last_name,
        email: this.parent.email,
        phone: this.parent.phone,
        address: this.parent.address,
        city: this.parent.city,
        region: this.parent.region,
        country: this.parent.country,
        postal_code: this.parent.postal_code,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/parents/${this.parent.id}`)
    },
    destroy() {
      if (confirm('Ви впевнені, що хочете видалити цього батька?')) {
        this.$inertia.delete(`/parents/${this.parent.id}`)
      }
    },
    restore() {
      if (confirm('Ви впевнені, що хочете відновити цього батька?')) {
        this.$inertia.get(`/parents/${this.parent.id}/restore`)
      }
    },
  },
}
</script> 
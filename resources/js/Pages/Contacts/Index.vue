<template>
  <div>
    <Head title="Студенти" />
    <h1 class="mb-8 text-3xl font-bold">Студенти</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Видалені:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with"> видаленими</option>
          <option value="only">Тільки видалені</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/contacts/create">
        <span>Створити</span>
        <span class="hidden md:inline">&nbsp;студента</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Повне ім'я</th>
          <th class="pb-4 pt-6 px-6">Організація</th>
          <th class="pb-4 pt-6 px-6">Місто</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Номер телефону</th>
        </tr>
        <tr v-for="contact in contacts.data" :key="contact.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/contacts/${contact.id}/edit`">
              {{ contact.name }}
              <icon v-if="contact.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
              <div v-if="contact.organization">
                {{ contact.organization.name }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
              {{ contact.city }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
              {{ contact.phone }}
            </Link>
          </td>
          <td class="border-t w-px">
            <Link class="flex items-center px-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
              <icon name="edit" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t w-px">
            <button class="flex items-center px-4" @click="destroy(contact)" tabindex="-1">
              <icon name="trash" class="block w-6 h-6 fill-gray-400" />
            </button>
          </td>
        </tr>
        <tr v-if="contacts.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">Студентів не знайдено.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="contacts.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    contacts: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/contacts', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = {
        search: null,
        trashed: null,
      }
    },
    destroy(contact) {
      if (confirm('Ви впевнені, що хочете видалити цього студента?')) {
        this.$inertia.delete(`/contacts/${contact.id}`)
      }
    },
  },
}
</script>

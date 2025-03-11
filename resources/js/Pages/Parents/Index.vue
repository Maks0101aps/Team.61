<template>
  <Head title="Батьки" />

  <h1 class="mb-8 text-3xl font-bold">Батьки</h1>

  <div class="flex items-center justify-between mb-6">
    <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
      <label class="block text-gray-700">Видалені:</label>
      <select v-model="form.trashed" class="mt-1 w-full">
        <option :value="null" />
        <option value="with">З видаленими</option>
        <option value="only">Тільки видалені</option>
      </select>
    </search-filter>
    <Link class="btn-indigo" href="/parents/create">
      <span>Створити</span>
      <span class="hidden md:inline">&nbsp;батька</span>
    </Link>
  </div>

  <div class="bg-white rounded-md shadow overflow-x-auto">
    <table class="w-full whitespace-nowrap">
      <tr class="text-left font-bold">
        <th class="pb-4 pt-6 px-6">Ім'я</th>
        <th class="pb-4 pt-6 px-6">Телефон</th>
        <th class="pb-4 pt-6 px-6">Місто</th>
        <th class="pb-4 pt-6 px-6" colspan="2">Дії</th>
      </tr>
      <tr v-for="parent in parents.data" :key="parent.id" class="hover:bg-gray-100">
        <td class="border-t">
          <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/parents/${parent.id}/edit`">
            {{ parent.name }}
            <icon v-if="parent.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
          </Link>
        </td>
        <td class="border-t">
          <div class="flex items-center px-6 py-4">
            {{ parent.phone }}
          </div>
        </td>
        <td class="border-t">
          <div class="flex items-center px-6 py-4">
            {{ parent.city }}
          </div>
        </td>
        <td class="border-t w-px">
          <Link class="flex items-center px-4" :href="`/parents/${parent.id}/edit`" tabindex="-1">
            <icon name="edit" class="block w-6 h-6 fill-gray-400" />
          </Link>
        </td>
        <td class="border-t w-px">
          <button class="flex items-center px-4" @click="destroy(parent)" tabindex="-1">
            <icon name="trash" class="block w-6 h-6 fill-gray-400" />
          </button>
        </td>
      </tr>
      <tr v-if="parents.data.length === 0">
        <td class="border-t px-6 py-4" colspan="4">Записів не знайдено.</td>
      </tr>
    </table>
  </div>

  <pagination class="mt-6" :links="parents.links" />
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import throttle from 'lodash/throttle'

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
    parents: Object,
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
        this.$inertia.get('/parents', this.form, { preserveState: true })
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
    destroy(parent) {
      if (confirm('Ви впевнені, що хочете видалити цього батька?')) {
        this.$inertia.delete(`/parents/${parent.id}`)
      }
    },
  },
}
</script> 
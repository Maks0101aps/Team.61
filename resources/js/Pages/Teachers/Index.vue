<template>
  <div>
    <Head title="Teachers" />
    <h1 class="mb-8 text-3xl font-bold">Вчителі</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Видалені:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">З видаленими</option>
          <option value="only">Тільки видалені</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/teachers/create">
        <span>Створити</span>
        <span class="hidden md:inline">&nbsp;вчителя</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Повне ім'я</th>
          <th class="pb-4 pt-6 px-6">Організація</th>
          <th class="pb-4 pt-6 px-6">Предмет</th>
          <th class="pb-4 pt-6 px-6">Місто</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Номер телефону</th>
        </tr>
        <tr v-for="teacher in teachers.data" :key="teacher.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/teachers/${teacher.id}/edit`">
              {{ teacher.name }}
              <icon v-if="teacher.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/teachers/${teacher.id}/edit`" tabindex="-1">
              <div v-if="teacher.organization">
                {{ teacher.organization.name }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/teachers/${teacher.id}/edit`" tabindex="-1">
              {{ teacher.subject }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/teachers/${teacher.id}/edit`" tabindex="-1">
              {{ teacher.city }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/teachers/${teacher.id}/edit`" tabindex="-1">
              {{ teacher.phone }}
            </Link>
          </td>
          <td class="border-t w-px">
            <Link class="flex items-center px-4" :href="`/teachers/${teacher.id}/edit`" tabindex="-1">
              <icon name="edit" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t w-px">
            <button class="flex items-center px-4" @click="destroy(teacher)" tabindex="-1">
              <icon name="trash" class="block w-6 h-6 fill-gray-400" />
            </button>
          </td>
        </tr>
        <tr v-if="teachers.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="5">Вчителів не знайдено.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="teachers.links" />
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
    teachers: Object,
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
        this.$inertia.get('/teachers', pickBy(this.form), { preserveState: true })
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
    destroy(teacher) {
      if (confirm('Ви впевнені, що хочете видалити цього вчителя?')) {
        this.$inertia.delete(`/teachers/${teacher.id}`)
      }
    },
  },
}
</script> 
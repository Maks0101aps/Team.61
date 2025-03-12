<template>
  <div>
    <Head title="Події" />
    <h1 class="mb-8 text-3xl font-bold">Події</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Видалені:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">З видаленими</option>
          <option value="only">Тільки видалені</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/events/create">
        <span>Створити</span>
        <span class="hidden md:inline">&nbsp;подію</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Назва</th>
          <th class="pb-4 pt-6 px-6">Тип</th>
          <th class="pb-4 pt-6 px-6">Дата початку</th>
          <th class="pb-4 pt-6 px-6">Тривалість</th>
          <th class="pb-4 pt-6 px-6">Місце</th>
          <th class="pb-4 pt-6 px-6">Створив</th>
          <th class="pb-4 pt-6 px-6">Посилання</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Дії</th>
        </tr>
        <tr v-for="event in events.data" :key="event.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/events/${event.id}/edit`">
              {{ event.title }}
              <icon v-if="event.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
              {{ event.type }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
              {{ event.start_date }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
              {{ event.duration }} хв.
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
              {{ event.location }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
              {{ event.created_by }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
              <div v-if="event.online_link">
                <a :href="event.online_link" target="_blank" class="text-blue-600 hover:underline">Посилання</a>
              </div>
            </Link>
          </td>
          <td class="border-t w-px">
            <Link class="flex items-center px-4" :href="`/events/${event.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t w-px">
            <button class="flex items-center px-4" @click="destroy(event)" tabindex="-1">
              <icon name="trash" class="block w-6 h-6 fill-gray-400" />
            </button>
          </td>
        </tr>
        <tr v-if="events.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="8">Подій не знайдено.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="events.links" />
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
    events: Object,
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
        this.$inertia.get('/events', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    destroy(event) {
      if (confirm('Ви впевнені, що хочете видалити цю подію?')) {
        this.$inertia.delete(`/events/${event.id}`)
      }
    },
  },
}
</script> 
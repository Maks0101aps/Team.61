<template>
  <div>
    <Head title="Завдання" />
    <h1 class="mb-8 text-3xl font-bold">Завдання</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
        <label class="block text-gray-700">Пошук:</label>
        <input
          v-model="form.search"
          class="mt-1 w-full form-input"
          type="text"
          placeholder="Пошук..."
        >
      </search-filter>
      <Link class="btn-indigo" href="/tasks/create">
        <span>Створити</span>
        <span class="hidden md:inline">&nbsp;завдання</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Назва</th>
          <th class="px-6 pt-6 pb-4">Подія</th>
          <th class="px-6 pt-6 pb-4">Дата виконання</th>
          <th class="px-6 pt-6 pb-4">Статус</th>
          <th class="px-6 pt-6 pb-4">Створив</th>
          <th class="px-6 pt-6 pb-4"></th>
        </tr>
        <tr v-for="task in tasks.data" :key="task.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tasks/${task.id}/edit`">
              {{ task.title }}
              <icon v-if="task.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
              {{ task.event }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
              {{ task.due_date }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
              <div :class="{ 'text-green-500': task.completed, 'text-red-500': !task.completed }">
                {{ task.completed ? 'Виконано' : 'Не виконано' }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
              {{ task.created_by }}
            </Link>
          </td>
          <td class="border-t w-px">
            <div class="flex items-center px-4">
              <Link class="block w-6 h-6" :href="`/tasks/${task.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
              <button
                v-if="!task.deleted_at"
                class="block w-6 h-6 ml-4"
                tabindex="-1"
                type="button"
                @click="destroy(task)"
              >
                <icon name="trash" class="block w-6 h-6 fill-gray-400 hover:fill-red-600" />
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="tasks.data.length === 0">
          <td class="border-t px-6 py-4" colspan="6">Завдань не знайдено.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="tasks.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import debounce from 'lodash/debounce'

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
    tasks: Object,
    filters: Object,
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
      handler: debounce(function () {
        this.$inertia.get('/tasks', this.form, { preserveState: true })
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
    destroy(task) {
      if (confirm('Ви впевнені, що хочете видалити це завдання?')) {
        this.$inertia.delete(`/tasks/${task.id}`)
      }
    },
  },
}
</script> 
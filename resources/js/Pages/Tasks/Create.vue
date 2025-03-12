<template>
  <div>
    <Head title="Створення завдання" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/tasks">Завдання</Link>
      <span class="text-indigo-400 font-medium">/</span> Створення
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input
            v-model="form.event_id"
            :error="form.errors.event_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Подія"
          >
            <option :value="null">Оберіть подію</option>
            <option v-for="event in events" :key="event.id" :value="event.id">{{ event.title }}</option>
          </select-input>
          <text-input
            v-model="form.title"
            :error="form.errors.title"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Назва"
          />
          <text-input
            v-model="form.due_date"
            :error="form.errors.due_date"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Дата виконання"
            type="datetime-local"
          />
          <text-area
            v-model="form.content"
            :error="form.errors.content"
            class="pb-8 pr-6 w-full lg:w-full"
            label="Контент"
          />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">
            Створити завдання
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TextArea from '@/Shared/TextArea.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextArea,
  },
  layout: Layout,
  props: {
    events: {
      type: Array,
      required: true
    }
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        event_id: null,
        title: null,
        content: null,
        due_date: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/tasks')
    },
  },
}
</script> 
<template>
  <div>
    <Head title="Редагування завдання" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/tasks">Завдання</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.title }}
    </h1>
    <trashed-message v-if="task.deleted_at" class="mb-6" @restore="restore">
      Це завдання було видалено.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
          <checkbox-input
            v-model="form.completed"
            :error="form.errors.completed"
            class="pb-8 pr-6 w-full lg:w-full"
            label="Завдання виконано"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">
            Оновити завдання
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
import CheckboxInput from '@/Shared/CheckboxInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextArea,
    CheckboxInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    task: {
      type: Object,
      required: true,
    },
    events: {
      type: Array,
      required: true,
    },
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        event_id: this.task.event_id,
        title: this.task.title,
        content: this.task.content,
        due_date: this.task.due_date,
        completed: this.task.completed,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/tasks/${this.task.id}`)
    },
    restore() {
      if (confirm('Ви впевнені, що хочете відновити це завдання?')) {
        this.$inertia.put(`/tasks/${this.task.id}/restore`)
      }
    },
  },
}
</script> 
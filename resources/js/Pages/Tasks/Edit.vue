<template>
  <div>
    <Head :title="language === 'uk' ? 'Редагування завдання' : 'Edit Task'" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/tasks">{{ language === 'uk' ? 'Завдання' : 'Tasks' }}</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.title }}
    </h1>
    <trashed-message v-if="task.deleted_at" class="mb-6" @restore="restore">
      {{ language === 'uk' ? 'Це завдання було видалено.' : 'This task has been deleted.' }}
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input
            v-model="form.event_id"
            :error="form.errors.event_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Подія' : 'Event'"
          >
            <option :value="null">{{ language === 'uk' ? 'Оберіть подію' : 'Select an event' }}</option>
            <option v-for="event in events" :key="event.id" :value="event.id">{{ event.title }}</option>
          </select-input>
          <text-input
            v-model="form.title"
            :error="form.errors.title"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Назва' : 'Title'"
          />
          <text-input
            v-model="form.due_date"
            :error="form.errors.due_date"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Дата виконання' : 'Due Date'"
            type="datetime-local"
          />
          <text-area
            v-model="form.content"
            :error="form.errors.content"
            class="pb-8 pr-6 w-full lg:w-full"
            :label="language === 'uk' ? 'Контент' : 'Content'"
          />
          <checkbox-input
            v-model="form.completed"
            :error="form.errors.completed"
            class="pb-8 pr-6 w-full lg:w-full"
            :label="language === 'uk' ? 'Завдання виконано' : 'Task completed'"
          />
          <multi-select
            v-model="form.students"
            :options="students"
            :error="form.errors.students"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Студенти' : 'Students'"
            :placeholder="language === 'uk' ? 'Оберіть студентів' : 'Select students'"
            track-by="id"
            label-prop="name"
          >
            <template #option="{ option }">
              <div class="flex items-center">
                <div class="text-sm">{{ option.name }}</div>
              </div>
            </template>
          </multi-select>
          <multi-select
            v-model="form.teachers"
            :options="teachers"
            :error="form.errors.teachers"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Вчителі' : 'Teachers'"
            :placeholder="language === 'uk' ? 'Оберіть вчителів' : 'Select teachers'"
            track-by="id"
            label-prop="name"
          >
            <template #option="{ option }">
              <div class="flex items-center">
                <div class="text-sm">{{ option.name }}</div>
              </div>
            </template>
          </multi-select>
          <multi-select
            v-model="form.parents"
            :options="parents"
            :error="form.errors.parents"
            class="pb-8 pr-6 w-full lg:w-full"
            :label="language === 'uk' ? 'Батьки' : 'Parents'"
            :placeholder="language === 'uk' ? 'Оберіть батьків' : 'Select parents'"
            track-by="id"
            label-prop="name"
          >
            <template #option="{ option }">
              <div class="flex items-center">
                <div class="text-sm">{{ option.name }}</div>
              </div>
            </template>
          </multi-select>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">
            {{ language === 'uk' ? 'Оновити завдання' : 'Update Task' }}
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
import MultiSelect from '@/Shared/MultiSelect.vue'

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
    MultiSelect,
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
    students: {
      type: Array,
      required: true,
    },
    teachers: {
      type: Array,
      required: true,
    },
    parents: {
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
        students: this.task.students || [],
        teachers: this.task.teachers || [],
        parents: this.task.parents || [],
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
    update() {
      this.form.put(`/tasks/${this.task.id}`)
    },
    restore() {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете відновити це завдання?' : 'Are you sure you want to restore this task?')) {
        this.$inertia.put(`/tasks/${this.task.id}/restore`)
      }
    },
    updateLanguage(event) {
      this.language = event.detail.language;
    },
  },
}
</script> 
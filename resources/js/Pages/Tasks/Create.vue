<template>
  <div>
    <Head :title="language === 'uk' ? 'Створення завдання' : 'Create Task'" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/tasks">{{ language === 'uk' ? 'Завдання' : 'Tasks' }}</Link>
      <span class="text-indigo-400 font-medium">/</span> {{ language === 'uk' ? 'Створення' : 'Create' }}
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
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
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">
            {{ language === 'uk' ? 'Створити завдання' : 'Create Task' }}
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
import MultiSelect from '@/Shared/MultiSelect.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextArea,
    MultiSelect,
  },
  layout: Layout,
  props: {
    events: {
      type: Array,
      required: true
    },
    students: {
      type: Array,
      required: true
    },
    teachers: {
      type: Array,
      required: true
    },
    parents: {
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
        students: [],
        parents: [],
        teachers: [],
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
      this.form.post('/tasks')
    },
    updateLanguage(event) {
      this.language = event.detail.language;
    },
  },
}
</script> 
<template>
  <div>
    <Head title="Створення події" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/events">Події</Link>
      <span class="text-indigo-400 font-medium">/</span> Створення
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2" label="Назва" />
          <select-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full lg:w-1/2" label="Тип">
            <option :value="null">Оберіть тип події</option>
            <option v-for="(label, value) in types" :key="value" :value="value">{{ label }}</option>
          </select-input>
          <text-input
            v-model="form.start_date"
            :error="form.errors.start_date"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Дата початку"
            type="datetime-local"
          />
          <text-input
            v-model="form.duration"
            :error="form.errors.duration"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Тривалість (хв.)"
            type="number"
          />
          <text-area
            v-model="form.content"
            :error="form.errors.content"
            class="pb-8 pr-6 w-full lg:w-full"
            label="Контент"
          />
          <checkbox-input
            v-model="form.is_content_hidden"
            :error="form.errors.is_content_hidden"
            class="pb-8 pr-6 w-full lg:w-full"
            label="Приховати контент до початку події"
          />
          <text-input
            v-model="form.location"
            :error="form.errors.location"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Місце проведення"
          />
          <text-input
            v-model="form.online_link"
            :error="form.errors.online_link"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Посилання на онлайн-зустріч"
          />
          <text-area
            v-model="form.tasks"
            :error="form.errors.tasks"
            class="pb-8 pr-6 w-full lg:w-full"
            label="Завдання"
          />
          <select-input
            v-model="form.student_ids"
            :error="form.errors.student_ids"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Студенти"
            multiple
          >
            <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name }}</option>
          </select-input>
          <select-input
            v-model="form.teacher_ids"
            :error="form.errors.teacher_ids"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Вчителі"
            multiple
          >
            <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.name }}</option>
          </select-input>
          <select-input
            v-model="form.parent_ids"
            :error="form.errors.parent_ids"
            class="pb-8 pr-6 w-full lg:w-full"
            label="Батьки"
            multiple
          >
            <option v-for="parent in parents" :key="parent.id" :value="parent.id">{{ parent.name }}</option>
          </select-input>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">
            Створити подію
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

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextArea,
    CheckboxInput,
  },
  layout: Layout,
  props: {
    types: {
      type: Object,
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
        title: null,
        type: null,
        start_date: null,
        duration: 60,
        content: null,
        is_content_hidden: false,
        location: null,
        online_link: null,
        tasks: null,
        student_ids: [],
        teacher_ids: [],
        parent_ids: [],
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/events', {
        preserveScroll: true,
      })
    },
  },
}
</script> 
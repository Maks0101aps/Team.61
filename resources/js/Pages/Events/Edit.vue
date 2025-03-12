<template>
  <div>
    <Head :title="form.title" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/events">Події</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.title }}
    </h1>
    <trashed-message v-if="event.deleted_at" class="mb-6" @restore="restore">
      Цю подію було видалено.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2" label="Назва" />
          <select-input v-model="form.type" :error="form.errors.type" class="pb-8 pr-6 w-full lg:w-1/2" label="Тип">
            <option :value="null" />
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
          <multi-select
            v-model="form.student_ids"
            :options="students"
            :error="form.errors.student_ids"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Студенти"
            placeholder="Оберіть студентів"
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
            v-model="form.teacher_ids"
            :options="teachers"
            :error="form.errors.teacher_ids"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Вчителі"
            placeholder="Оберіть вчителів"
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
            v-model="form.parent_ids"
            :options="parents"
            :error="form.errors.parent_ids"
            class="pb-8 pr-6 w-full lg:w-full"
            label="Батьки"
            placeholder="Оберіть батьків"
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
            Оновити подію
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
    event: Object,
    types: Object,
    students: Array,
    teachers: Array,
    parents: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        title: this.event.title,
        type: this.event.type,
        start_date: this.event.start_date,
        duration: this.event.duration,
        content: this.event.content,
        is_content_hidden: this.event.is_content_hidden,
        location: this.event.location,
        online_link: this.event.online_link,
        tasks: this.event.tasks,
        student_ids: this.event.student_ids || [],
        teacher_ids: this.event.teacher_ids || [],
        parent_ids: this.event.parent_ids || [],
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/events/${this.event.id}`)
    },
    destroy() {
      if (confirm('Ви впевнені, що хочете видалити цю подію?')) {
        this.$inertia.delete(`/events/${this.event.id}`)
      }
    },
    restore() {
      if (confirm('Ви впевнені, що хочете відновити цю подію?')) {
        this.$inertia.put(`/events/${this.event.id}/restore`)
      }
    },
  },
}
</script> 
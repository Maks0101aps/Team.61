<template>
  <div>
    <Head title="Створити завдання" />
    
    <!-- Header with breadcrumbs and title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/tasks">
            Завдання
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">Створення</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          Створення завдання
        </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/tasks" 
          class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 flex items-center transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Назад
        </Link>
      </div>
    </div>

    <!-- Main form card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <form @submit.prevent="store">
        <!-- Form header -->
        <div class="px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            Інформація про завдання
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Заповніть дані для створення нового завдання в системі
          </p>
        </div>
        
        <!-- Form body -->
        <div class="p-8">
          <!-- Basic Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Основна інформація
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <select-input 
                v-model="form.event_id" 
                :error="form.errors.event_id" 
                label="Подія"
                help-text="Оберіть подію, до якої відноситься завдання (необов'язково)"
              >
                <option :value="null">- Без події -</option>
                <option v-for="event in events" :key="event.id" :value="event.id">{{ event.title }}</option>
              </select-input>
              
              <text-input 
                v-model="form.title" 
                :error="form.errors.title" 
                label="Назва завдання" 
                help-text="Введіть інформативну назву для завдання"
              />
            </div>
          </div>
          
          <!-- Timing Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Часова інформація
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.due_date" 
                :error="form.errors.due_date" 
                label="Термін виконання" 
                type="datetime-local"
                help-text="Вкажіть дату і час кінцевого терміну виконання завдання"
              />
              
              <select-input 
                v-model="form.priority" 
                :error="form.errors.priority" 
                label="Пріоритет"
                help-text="Оберіть пріоритет завдання"
              >
                <option value="low">Низький</option>
                <option value="medium">Середній</option>
                <option value="high">Високий</option>
              </select-input>
            </div>
          </div>
          
          <!-- Description Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Опис завдання
            </h3>
            <div class="mb-6">
              <text-area 
                v-model="form.content" 
                :error="form.errors.content" 
                label="Детальний опис" 
                rows="6"
                help-text="Детально опишіть завдання, включаючи всі важливі деталі"
              />
            </div>
          </div>
          
          <!-- Participants Section -->
          <div class="mb-4">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Учасники завдання
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
              <multi-select
                v-model="form.teacher_ids"
                :options="teachers"
                option-label="full_name"
                option-value="id"
                label="Вчителі"
                placeholder="Оберіть вчителів для завдання"
                :error="form.errors.teacher_ids"
                help-text="Оберіть вчителів, які відповідальні за завдання"
              />
              
              <multi-select
                v-model="form.student_ids"
                :options="students"
                option-label="full_name"
                option-value="id"
                label="Учні"
                placeholder="Оберіть учнів для завдання"
                :error="form.errors.student_ids"
                help-text="Оберіть учнів, які мають виконати завдання"
              />
              
              <multi-select
                v-model="form.parent_ids"
                :options="parents"
                option-label="full_name"
                option-value="id"
                label="Батьки"
                placeholder="Оберіть батьків для завдання"
                :error="form.errors.parent_ids"
                help-text="Оберіть батьків, які мають бути повідомлені про завдання"
              />
            </div>
            
            <div class="mt-6">
              <checkbox-input 
                v-model="form.notify_participants" 
                :error="form.errors.notify_participants" 
                label="Повідомити всіх учасників про завдання" 
                help-text="Відмітьте цю опцію, щоб надіслати повідомлення про завдання всім обраним учасникам"
              />
            </div>
          </div>
        </div>
        
        <!-- Form footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end">
          <Link 
            href="/tasks" 
            class="px-4 py-2 mr-3 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Скасувати
          </Link>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
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
import CheckboxInput from '@/Shared/CheckboxInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import MultiSelect from '@/Shared/MultiSelect.vue'

export default {
  components: {
    Head,
    Link,
    TextInput,
    TextArea,
    SelectInput,
    CheckboxInput,
    LoadingButton,
    MultiSelect,
  },
  layout: Layout,
  props: {
    events: Array,
    teachers: Array,
    students: Array,
    parents: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        event_id: null,
        title: '',
        due_date: '',
        content: '',
        priority: 'medium',
        student_ids: [],
        teacher_ids: [],
        parent_ids: [],
        notify_participants: false,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/tasks')
    },
  },
  computed: {
    currentLanguageLabels() {
      return {
        en: {
          event_id: 'Event',
          title: 'Title',
          due_date: 'Due Date',
          content: 'Content',
          priority: 'Priority',
          teachers: 'Teachers',
          students: 'Students',
          parents: 'Parents',
          notify_participants: 'Notify Participants',
        },
        uk: {
          event_id: 'Подія',
          title: 'Назва',
          due_date: 'Термін виконання',
          content: 'Опис',
          priority: 'Пріоритет',
          teachers: 'Вчителі',
          students: 'Учні',
          parents: 'Батьки',
          notify_participants: 'Повідомити учасників',
        },
      }[this.$page.props.locale || 'uk']
    },
  },
}
</script> 
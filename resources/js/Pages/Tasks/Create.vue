<template>
  <div>
    <Head :title="currentLanguageLabels.create_task" />
    
    <!-- Header with breadcrumbs and title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/tasks">
            {{ currentLanguageLabels.tasks }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ currentLanguageLabels.creation }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ currentLanguageLabels.creating_task }}
        </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/tasks" 
          class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 flex items-center transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          {{ currentLanguageLabels.back }}
        </Link>
      </div>
    </div>

    <!-- Main form card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <form @submit.prevent="store">
        <!-- Form header -->
        <div class="px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            {{ currentLanguageLabels.task_information }}
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            {{ currentLanguageLabels.fill_task_info }}
          </p>
        </div>
        
        <!-- Form body -->
        <div class="p-8">
          <!-- Basic Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ currentLanguageLabels.basic_info }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <select-input 
                v-model="form.event_id" 
                :error="form.errors.event_id" 
                :label="currentLanguageLabels.event_id"
                :help-text="currentLanguageLabels.choose_event"
              >
                <option :value="null">- {{ currentLanguageLabels.no_event }} -</option>
                <option v-for="event in events" :key="event.id" :value="event.id">{{ event.title }}</option>
              </select-input>
              
              <text-input 
                v-model="form.title" 
                :error="form.errors.title" 
                :label="currentLanguageLabels.title" 
                :help-text="currentLanguageLabels.enter_title"
              />
            </div>
          </div>
          
          <!-- Timing Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ currentLanguageLabels.time_info }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.due_date" 
                :error="form.errors.due_date" 
                :label="currentLanguageLabels.due_date" 
                type="datetime-local"
                :help-text="currentLanguageLabels.due_date_help"
              />
              
              <select-input 
                v-model="form.priority" 
                :error="form.errors.priority" 
                :label="currentLanguageLabels.priority"
                :help-text="currentLanguageLabels.priority_help"
              >
                <option value="low">{{ currentLanguageLabels.low }}</option>
                <option value="medium">{{ currentLanguageLabels.medium }}</option>
                <option value="high">{{ currentLanguageLabels.high }}</option>
              </select-input>
            </div>
          </div>
          
          <!-- Description Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ currentLanguageLabels.task_description }}
            </h3>
            <div class="mb-6">
              <text-area 
                v-model="form.content" 
                :error="form.errors.content" 
                :label="currentLanguageLabels.detailed_description" 
                :rows="6"
                :help-text="currentLanguageLabels.description_help"
              />
            </div>
          </div>
          
          <!-- Participants Section -->
          <div class="mb-4">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ currentLanguageLabels.task_participants }}
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
              <multi-select
                v-model="form.teacher_ids"
                :options="teachers"
                option-label="full_name"
                option-value="id"
                :label="currentLanguageLabels.teachers"
                :placeholder="currentLanguageLabels.choose_teachers"
                :error="form.errors.teacher_ids"
                :help-text="currentLanguageLabels.teachers_help"
              />
              
              <multi-select
                v-model="form.student_ids"
                :options="students"
                option-label="full_name"
                option-value="id"
                :label="currentLanguageLabels.students"
                :placeholder="currentLanguageLabels.choose_students"
                :error="form.errors.student_ids"
                :help-text="currentLanguageLabels.students_help"
              />
              
              <multi-select
                v-model="form.parent_ids"
                :options="parents"
                option-label="full_name"
                option-value="id"
                :label="currentLanguageLabels.parents"
                :placeholder="currentLanguageLabels.choose_parents"
                :error="form.errors.parent_ids"
                :help-text="currentLanguageLabels.parents_help"
              />
            </div>
            
            <div class="mt-6">
              <checkbox-input 
                v-model="form.notify_participants" 
                :error="form.errors.notify_participants" 
                :label="currentLanguageLabels.notify_participants" 
                :help-text="currentLanguageLabels.notify_help"
              />
            </div>
          </div>
        </div>
        
        <!-- Form footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end">
          <Link 
            href="/tasks" 
            class="px-4 py-2 mr-3 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            {{ currentLanguageLabels.cancel }}
          </Link>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
            {{ currentLanguageLabels.create_task_button }}
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
      language: localStorage.getItem('language') || 'uk',
    }
  },
  methods: {
    store() {
      this.form.post('/tasks')
    },
    updateLanguage(event) {
      if (event.detail && event.detail.language) {
        this.language = event.detail.language;
      } else if (Array.isArray(event.detail) && event.detail.length > 0) {
        this.language = event.detail[0];
      }
    },
  },
  mounted() {
    // Listen for language changes
    window.addEventListener('language-changed', this.updateLanguage);
    
    // Also listen for language changes using the event bus
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang;
      });
    }
    
    // Listen for storage events for changes from other tabs
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.language = event.newValue;
      }
    });
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  computed: {
    currentLanguageLabels() {
      return {
        en: {
          tasks: 'Tasks',
          creation: 'Creation',
          create_task: 'Create Task',
          creating_task: 'Creating a Task',
          back: 'Back',
          task_information: 'Task Information',
          fill_task_info: 'Fill in the details to create a new task in the system',
          basic_info: 'Basic Information',
          event_id: 'Event',
          choose_event: 'Choose the event this task belongs to (optional)',
          no_event: 'No Event',
          title: 'Task Name',
          enter_title: 'Enter an informative name for the task',
          time_info: 'Time Information',
          due_date: 'Due Date',
          due_date_help: 'Specify the date and time of the task deadline',
          priority: 'Priority',
          priority_help: 'Choose the task priority',
          low: 'Low',
          medium: 'Medium',
          high: 'High',
          task_description: 'Task Description',
          detailed_description: 'Detailed Description',
          description_help: 'Describe the task in detail, including all important information',
          task_participants: 'Task Participants',
          teachers: 'Teachers',
          choose_teachers: 'Choose teachers for the task',
          teachers_help: 'Select teachers who are responsible for the task',
          students: 'Students',
          choose_students: 'Choose students for the task',
          students_help: 'Select students who need to complete the task',
          parents: 'Parents',
          choose_parents: 'Choose parents for the task',
          parents_help: 'Select parents who should be notified about the task',
          notify_participants: 'Notify all participants about the task',
          notify_help: 'Check this option to send a notification about the task to all selected participants',
          cancel: 'Cancel',
          create_task_button: 'Create Task',
        },
        uk: {
          tasks: 'Завдання',
          creation: 'Створення',
          create_task: 'Створити завдання',
          creating_task: 'Створення завдання',
          back: 'Назад',
          task_information: 'Інформація про завдання',
          fill_task_info: 'Заповніть дані для створення нового завдання в системі',
          basic_info: 'Основна інформація',
          event_id: 'Подія',
          choose_event: 'Оберіть подію, до якої відноситься завдання (необов\'язково)',
          no_event: 'Без події',
          title: 'Назва завдання',
          enter_title: 'Введіть інформативну назву для завдання',
          time_info: 'Часова інформація',
          due_date: 'Термін виконання',
          due_date_help: 'Вкажіть дату і час кінцевого терміну виконання завдання',
          priority: 'Пріоритет',
          priority_help: 'Оберіть пріоритет завдання',
          low: 'Низький',
          medium: 'Середній',
          high: 'Високий',
          task_description: 'Опис завдання',
          detailed_description: 'Детальний опис',
          description_help: 'Детально опишіть завдання, включаючи всі важливі деталі',
          task_participants: 'Учасники завдання',
          teachers: 'Вчителі',
          choose_teachers: 'Оберіть вчителів для завдання',
          teachers_help: 'Оберіть вчителів, які відповідальні за завдання',
          students: 'Учні',
          choose_students: 'Оберіть учнів для завдання',
          students_help: 'Оберіть учнів, які мають виконати завдання',
          parents: 'Батьки',
          choose_parents: 'Оберіть батьків для завдання',
          parents_help: 'Оберіть батьків, які мають бути повідомлені про завдання',
          notify_participants: 'Повідомити всіх учасників про завдання',
          notify_help: 'Відмітьте цю опцію, щоб надіслати повідомлення про завдання всім обраним учасникам',
          cancel: 'Скасувати',
          create_task_button: 'Створити завдання',
        },
      }[this.$page.props.language?.current || this.language || 'uk']
    },
  },
}
</script> 
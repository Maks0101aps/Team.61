<template>
  <div>
    <Head title="Створити подію" />
    
    <!-- Header with breadcrumbs and title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/events">
            Події
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">Створення</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          Створення події
        </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/events" 
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
            Інформація про подію
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Заповніть дані для створення нової події в системі
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
              <text-input 
                v-model="form.title" 
                :error="form.errors.title" 
                label="Назва події" 
                help-text="Введіть інформативну назву для події"
              />
              
              <div v-if="isStudent">
                <div class="border border-amber-200 bg-amber-50 rounded-md p-4">
                  <div class="text-sm font-medium text-amber-800 mb-1">Тип події</div>
                  <div class="text-sm text-amber-700">
                    Учні можуть створювати тільки особисті події.
                  </div>
                  <div class="mt-2 font-semibold">
                    Тип: Особиста подія
                  </div>
                </div>
                <input type="hidden" v-model="form.type" />
              </div>
              <select-input 
                v-else
                v-model="form.type" 
                :error="form.errors.type" 
                label="Тип події"
                help-text="Оберіть тип події для правильної категоризації"
              >
                <option value="" disabled>Оберіть тип події</option>
                <option v-for="(label, value) in types" :key="value" :value="value">{{ label }}</option>
              </select-input>
            </div>
          </div>
          
          <!-- Timing Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Часова інформація
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.start_date" 
                :error="form.errors.start_date" 
                label="Дата початку" 
                type="datetime-local"
                help-text="Вкажіть дату і час початку події"
              />
              
              <text-input 
                v-model="form.duration" 
                :error="form.errors.duration" 
                label="Тривалість (у хвилинах)" 
                type="number"
                help-text="Вкажіть тривалість події у хвилинах"
              />
            </div>
          </div>
          
          <!-- Location Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Місце проведення
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.location" 
                :error="form.errors.location" 
                label="Фізичне місце" 
                help-text="Вкажіть фізичне місце проведення (за наявності)"
              />
              
              <text-input 
                v-model="form.online_link" 
                :error="form.errors.online_link" 
                label="Посилання для онлайн-участі" 
                type="url"
                help-text="Вкажіть посилання для онлайн-участі (за наявності)"
              />
            </div>
          </div>
          
          <!-- Description Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Опис події
            </h3>
            <div class="mb-6">
              <text-area 
                v-model="form.content" 
                :error="form.errors.content" 
                label="Детальний опис" 
                rows="6"
                help-text="Детально опишіть подію, включаючи всі важливі деталі"
              />
            </div>
            <div class="mb-6">
              <text-area 
                v-model="form.tasks" 
                :error="form.errors.tasks" 
                label="Завдання" 
                rows="4"
                help-text="Опишіть завдання, які потрібно виконати в рамках цієї події"
              />
            </div>
          </div>
          
          <!-- Participants Section -->
          <div class="mb-4">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              Учасники події
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
              <div v-if="isStudent" class="border border-amber-200 bg-amber-50 rounded-md p-4">
                <div class="text-sm font-medium text-amber-800 mb-1">Вчителі</div>
                <div class="text-sm text-amber-700">
                  Учні не можуть запрошувати вчителів на події. Це обмеження встановлено системою.
                </div>
              </div>
              <div v-else>
                <multi-select
                  v-model="form.teachers"
                  :options="teachers"
                  option-label="name"
                  option-value="id"
                  label="Вчителі"
                  placeholder="Оберіть вчителів для події"
                  :error="form.errors.teachers"
                  help-text="Оберіть вчителів, які мають бути присутніми на події"
                />
              </div>
              
              <multi-select
                v-model="form.students"
                :options="students"
                option-label="name"
                option-value="id"
                label="Учні"
                placeholder="Оберіть учнів для події"
                :error="form.errors.students"
                help-text="Оберіть учнів, які мають бути присутніми на події"
              />
              
              <div v-if="isStudent" class="border border-amber-200 bg-amber-50 rounded-md p-4">
                <div class="text-sm font-medium text-amber-800 mb-1">Батьки</div>
                <div class="text-sm text-amber-700">
                  Учні не можуть запрошувати батьків на події. Це обмеження встановлено системою.
                </div>
              </div>
              <div v-else>
                <multi-select
                  v-model="form.parents"
                  :options="parents"
                  option-label="name"
                  option-value="id"
                  label="Батьки"
                  placeholder="Оберіть батьків для події"
                  :error="form.errors.parents"
                  help-text="Оберіть батьків, які мають бути присутніми на події"
                />
              </div>
            </div>
            
            <div class="mt-6">
              <checkbox-input 
                v-model="form.notify_participants" 
                :error="form.errors.notify_participants" 
                label="Повідомити всіх учасників про подію" 
                help-text="Відмітьте цю опцію, щоб надіслати повідомлення про подію всім обраним учасникам"
              />
            </div>
          </div>
        </div>
        
        <!-- Form footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end">
          <Link 
            href="/events" 
            class="px-4 py-2 mr-3 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Скасувати
          </Link>
          <loading-button 
            :loading="form.processing" 
            type="primary" 
            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
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
    types: Array,
    teachers: Array,
    students: Array,
    parents: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        title: '',
        type: this.$page.props.auth.user.role === 'student' ? 'personal' : '',
        start_date: '',
        duration: 60,
        content: '',
        location: '',
        online_link: '',
        tasks: '',
        teachers: [],
        students: [],
        parents: [],
        notify_participants: false,
      }),
    }
  },
  methods: {
    store() {
      // If user is a student, ensure teachers and parents arrays are empty
      if (this.isStudent) {
        this.form.teachers = [];
        this.form.parents = [];
      }
      
      this.form.post('/events')
    },
  },
  computed: {
    isStudent() {
      return this.$page.props.auth.user.role === 'student';
    },
    currentLanguageLabels() {
      return {
        en: {
          title: 'Title',
          type: 'Type',
          start_date: 'Start Date',
          duration: 'Duration (in minutes)',
          content: 'Content',
          location: 'Location',
          online_link: 'Online Link',
          tasks: 'Tasks',
          teachers: 'Teachers',
          students: 'Students',
          parents: 'Parents',
          notify_participants: 'Notify Participants',
        },
        uk: {
          title: 'Назва',
          type: 'Тип',
          start_date: 'Дата початку',
          duration: 'Тривалість (у хвилинах)',
          content: 'Опис',
          location: 'Місце проведення',
          online_link: 'Посилання для онлайн-участі',
          tasks: 'Завдання',
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
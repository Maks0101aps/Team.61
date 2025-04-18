<template>
  <div>
    <Head :title="language === 'uk' ? 'Створити подію' : 'Create Event'" />
    
    <!-- Header with breadcrumbs and title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="flex items-center">
          <Link class="text-blue-600 hover:text-blue-700 transition-colors duration-200" href="/events">
            {{ language === 'uk' ? 'Події' : 'Events' }}
          </Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-700">{{ language === 'uk' ? 'Створення' : 'Creation' }}</span>
        </div>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">
          {{ language === 'uk' ? 'Створення події' : 'Create Event' }}
        </h1>
      </div>
      <div class="mt-4 sm:mt-0">
        <Link 
          href="/events" 
          class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 flex items-center transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          {{ language === 'uk' ? 'Назад' : 'Back' }}
        </Link>
      </div>
    </div>

    <!-- Main form card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <form @submit.prevent="store">
        <!-- Form header -->
        <div class="px-8 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">
            {{ language === 'uk' ? 'Інформація про подію' : 'Event Information' }}
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            {{ language === 'uk' ? 'Заповніть дані для створення нової події в системі' : 'Fill in the details to create a new event in the system' }}
          </p>
        </div>
        
        <!-- Form body -->
        <div class="p-8">
          <!-- Basic Info Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Основна інформація' : 'Basic Information' }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.title" 
                :error="form.errors.title" 
                :label="language === 'uk' ? 'Назва події' : 'Event Name'" 
                :help-text="language === 'uk' ? 'Введіть інформативну назву для події' : 'Enter an informative name for the event'"
              />
              
              <div v-if="isStudent">
                <div class="border border-amber-200 bg-amber-50 rounded-md p-4">
                  <div class="text-sm font-medium text-amber-800 mb-1">{{ language === 'uk' ? 'Тип події' : 'Event Type' }}</div>
                  <div class="text-sm text-amber-700">
                    {{ language === 'uk' ? 'Учні можуть створювати тільки особисті події.' : 'Students can only create personal events.' }}
                  </div>
                  <div class="mt-2 font-semibold">
                    {{ language === 'uk' ? 'Тип: Особиста подія' : 'Type: Personal event' }}
                  </div>
                </div>
                <input type="hidden" v-model="form.type" />
              </div>
              <select-input 
                v-else
                v-model="form.type" 
                :error="form.errors.type" 
                :label="language === 'uk' ? 'Тип події' : 'Event Type'"
                :help-text="language === 'uk' ? 'Оберіть тип події для правильної категоризації' : 'Choose the event type for proper categorization'"
              >
                <option value="" disabled>{{ language === 'uk' ? 'Оберіть тип події' : 'Select event type' }}</option>
                <option v-for="(label, value) in types" :key="value" :value="value">{{ label }}</option>
              </select-input>
            </div>
          </div>
          
          <!-- Timing Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Часова інформація' : 'Time Information' }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.start_date" 
                :error="form.errors.start_date" 
                :label="language === 'uk' ? 'Дата початку' : 'Start Date'" 
                type="datetime-local"
                :help-text="language === 'uk' ? 'Вкажіть дату і час початку події' : 'Specify the date and time of the event start'"
              />
              
              <text-input 
                v-model="form.duration" 
                :error="form.errors.duration" 
                :label="language === 'uk' ? 'Тривалість (у хвилинах)' : 'Duration (in minutes)'" 
                type="number"
                :help-text="language === 'uk' ? 'Вкажіть тривалість події у хвилинах' : 'Specify the duration of the event in minutes'"
              />
            </div>
          </div>
          
          <!-- Location Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Місце проведення' : 'Location' }}
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <text-input 
                v-model="form.location" 
                :error="form.errors.location" 
                :label="language === 'uk' ? 'Фізичне місце' : 'Physical Location'" 
                :help-text="language === 'uk' ? 'Вкажіть фізичне місце проведення (за наявності)' : 'Specify the physical location (if applicable)'"
              />
              
              <text-input 
                v-model="form.online_link" 
                :error="form.errors.online_link" 
                :label="language === 'uk' ? 'Посилання для онлайн-участі' : 'Online Meeting Link'" 
                type="url"
                :help-text="language === 'uk' ? 'Вкажіть посилання для онлайн-участі (за наявності)' : 'Specify the link for online participation (if applicable)'"
              />
            </div>
          </div>
          
          <!-- Description Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Опис події' : 'Event Description' }}
            </h3>
            <div class="mb-6">
              <text-area 
                v-model="form.content" 
                :error="form.errors.content" 
                :label="language === 'uk' ? 'Детальний опис' : 'Detailed Description'" 
                rows="6"
                :help-text="language === 'uk' ? 'Детально опишіть подію, включаючи всі важливі деталі' : 'Describe the event in detail, including all important information'"
              />
            </div>
            <div class="mb-6">
              <text-area 
                v-model="form.tasks" 
                :error="form.errors.tasks" 
                :label="language === 'uk' ? 'Завдання' : 'Tasks'" 
                rows="4"
                :help-text="language === 'uk' ? 'Опишіть завдання, які потрібно виконати в рамках цієї події' : 'Describe the tasks that need to be completed as part of this event'"
              />
            </div>
          </div>
          
          <!-- Attachments Section -->
          <div class="mb-8">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Файли та посилання' : 'Files and Links' }}
            </h3>
            <div class="mb-6">
              <label class="form-label">{{ language === 'uk' ? 'Прикріплені файли:' : 'Attached Files:' }}</label>
              <div class="mt-2">
                <input 
                  type="file" 
                  id="attachments" 
                  name="attachments[]" 
                  multiple 
                  @change="handleFileUpload"
                  ref="fileInput"
                  class="hidden"
                  accept="*/*"
                />
                <div 
                  class="py-4 px-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
                  @click="$refs.fileInput.click()"
                  @dragover.prevent="dragover = true"
                  @dragleave.prevent="dragover = false"
                  @drop.prevent="handleFileDrop"
                  :class="{'bg-blue-50 border-blue-300': dragover}"
                >
                  <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">
                      {{ language === 'uk' ? 'Перетягніть файли сюди або клікніть для вибору' : 'Drag files here or click to select' }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                      {{ language === 'uk' ? 'Максимальний розмір файлу: 100MB' : 'Maximum file size: 100MB' }}
                    </p>
                  </div>
                </div>
              </div>
              
              <!-- File List -->
              <div v-if="form.attachments.length > 0" class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ language === 'uk' ? 'Обрані файли:' : 'Selected files:' }}</h4>
                <ul class="space-y-2">
                  <li v-for="(file, index) in form.attachments" :key="index" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <div>
                        <p class="text-sm font-medium text-gray-800">{{ file.name }}</p>
                        <p class="text-xs text-gray-500">{{ formatFileSize(file.size) }}</p>
                      </div>
                    </div>
                    <button 
                      type="button" 
                      @click="removeFile(index)" 
                      class="p-1 text-red-600 hover:text-red-800 rounded-full hover:bg-red-100"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </li>
                </ul>
              </div>
              
              <div v-if="form.errors.attachments" class="form-error">
                {{ form.errors.attachments }}
              </div>
            </div>
          </div>
          
          <!-- Participants Section -->
          <div class="mb-4">
            <h3 class="text-base font-medium text-gray-900 mb-4">
              {{ language === 'uk' ? 'Учасники події' : 'Event Participants' }}
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
              <div v-if="isStudent" class="border border-amber-200 bg-amber-50 rounded-md p-4">
                <div class="text-sm font-medium text-amber-800 mb-1">{{ language === 'uk' ? 'Вчителі' : 'Teachers' }}</div>
                <div class="text-sm text-amber-700">
                  {{ language === 'uk' ? 'Учні не можуть запрошувати вчителів на події. Це обмеження встановлено системою.' : 'Students cannot invite teachers to events. This restriction is set by the system.' }}
                </div>
              </div>
              <div v-else>
                <multi-select
                  v-model="form.teachers"
                  :options="teachers"
                  option-label="name"
                  option-value="id"
                  :label="language === 'uk' ? 'Вчителі' : 'Teachers'"
                  :placeholder="language === 'uk' ? 'Оберіть вчителів для події' : 'Choose teachers for the event'"
                  :error="form.errors.teachers"
                  :help-text="language === 'uk' ? 'Оберіть вчителів, які мають бути присутніми на події' : 'Select teachers who should attend the event'"
                />
              </div>
              
              <multi-select
                v-model="form.students"
                :options="students"
                option-label="name"
                option-value="id"
                :label="language === 'uk' ? 'Учні' : 'Students'"
                :placeholder="language === 'uk' ? 'Оберіть учнів для події' : 'Choose students for the event'"
                :error="form.errors.students"
                :help-text="language === 'uk' ? 'Оберіть учнів, які мають бути присутніми на події' : 'Select students who should attend the event'"
              />
              
              <div v-if="isStudent" class="border border-amber-200 bg-amber-50 rounded-md p-4">
                <div class="text-sm font-medium text-amber-800 mb-1">{{ language === 'uk' ? 'Батьки' : 'Parents' }}</div>
                <div class="text-sm text-amber-700">
                  {{ language === 'uk' ? 'Учні не можуть запрошувати батьків на події. Це обмеження встановлено системою.' : 'Students cannot invite parents to events. This restriction is set by the system.' }}
                </div>
              </div>
              <div v-else>
                <multi-select
                  v-model="form.parents"
                  :options="parents"
                  option-label="name"
                  option-value="id"
                  :label="language === 'uk' ? 'Батьки' : 'Parents'"
                  :placeholder="language === 'uk' ? 'Оберіть батьків для події' : 'Choose parents for the event'"
                  :error="form.errors.parents"
                  :help-text="language === 'uk' ? 'Оберіть батьків, які мають бути присутніми на події' : 'Select parents who should attend the event'"
                />
              </div>
            </div>
            
            <div class="mt-6">
              <checkbox-input 
                v-model="form.notify_participants" 
                :error="form.errors.notify_participants" 
                :label="language === 'uk' ? 'Повідомити всіх учасників про подію' : 'Notify all participants about the event'" 
                :help-text="language === 'uk' ? 'Відмітьте цю опцію, щоб надіслати повідомлення про подію всім обраним учасникам' : 'Check this option to send a notification about the event to all selected participants'"
              />
            </div>
          </div>
        </div>
        
        <!-- Form footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end">
          <Link 
            href="/events" 
            class="px-4 py-2 mr-3 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            {{ language === 'uk' ? 'Скасувати' : 'Cancel' }}
          </Link>
          <loading-button 
            :loading="form.processing" 
            variant="primary"
            buttonType="submit" 
            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm focus:ring-blue-500"
            size="md">
            {{ language === 'uk' ? 'Створити подію' : 'Create Event' }}
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
    types: Object,
    eventTypes: Object,
    teachers: Array,
    students: Array,
    parents: Array,
    selectedDate: String,
    isStudent: Boolean,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: this.$inertia.form({
        title: '',
        type: this.$page.props.auth.user.role === 'student' ? 'personal' : '',
        start_date: this.formatDateTimeForInput(this.selectedDate || new Date()),
        duration: 60,
        content: '',
        location: '',
        online_link: '',
        tasks: '',
        teachers: [],
        students: [],
        parents: [],
        notify_participants: false,
        attachments: [],
      }),
      dragover: false,
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    
    // Also listen for language changes using the event bus
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang;
      });
    }
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
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
    formatFileSize(size) {
      if (size < 1024) {
        return size + ' bytes';
      } else if (size < 1024 * 1024) {
        return (size / 1024).toFixed(2) + ' KB';
      } else if (size < 1024 * 1024 * 1024) {
        return (size / (1024 * 1024)).toFixed(2) + ' MB';
      } else {
        return (size / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
      }
    },
    handleFileUpload(event) {
      const files = event.target.files;
      if (!files.length) return;
      
      this.addFiles(files);
    },
    handleFileDrop(event) {
      this.dragover = false;
      const files = event.dataTransfer.files;
      if (!files.length) return;
      
      this.addFiles(files);
    },
    addFiles(files) {
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check file size (100MB limit)
        if (file.size > 100 * 1024 * 1024) {
          alert(`Файл "${file.name}" перевищує ліміт 100MB`);
          continue;
        }
        
        this.form.attachments.push(file);
      }
    },
    removeFile(index) {
      this.form.attachments.splice(index, 1);
    },
    formatDateTimeForInput(date) {
      // Format date as YYYY-MM-DDThh:mm
      if (typeof date === 'string' && !date.includes('T')) {
        // If it's just a date (YYYY-MM-DD), add the current time
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${date}T${hours}:${minutes}`;
      }
      
      const d = new Date(date);
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      const hours = String(d.getHours()).padStart(2, '0');
      const minutes = String(d.getMinutes()).padStart(2, '0');
      
      return `${year}-${month}-${day}T${hours}:${minutes}`;
    },
    updateLanguage(lang) {
      this.language = lang;
    }
  },
  computed: {
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
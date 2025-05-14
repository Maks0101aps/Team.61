<template>
  <div>
    <Head :title="form.title" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/events">{{ language === 'uk' ? 'Події' : 'Events' }}</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.title }}
    </h1>
    <trashed-message v-if="event.deleted_at" class="mb-6" @restore="restore">
      {{ language === 'uk' ? 'Цю подію було видалено.' : 'This event has been deleted.' }}
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2" :label="language === 'uk' ? 'Назва' : 'Title'" />
          <div v-if="isStudent" class="pb-8 pr-6 w-full lg:w-1/2">
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
            class="pb-8 pr-6 w-full lg:w-1/2" 
            :label="language === 'uk' ? 'Тип' : 'Type'"
          >
            <option :value="null" />
            <option v-for="(label, value) in types" :key="value" :value="value">{{ label }}</option>
          </select-input>
          <text-input
            v-model="form.start_date"
            :error="form.errors.start_date"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Дата початку' : 'Start Date'"
            type="datetime-local"
          />
          <text-input
            v-model="form.duration"
            :error="form.errors.duration"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Тривалість (хв.)' : 'Duration (min.)'"
            type="number"
          />
          <text-area
            v-model="form.content"
            :error="form.errors.content"
            class="pb-8 pr-6 w-full lg:w-full"
            :label="language === 'uk' ? 'Контент' : 'Content'"
          />
          <checkbox-input
            v-model="form.is_content_hidden"
            :error="form.errors.is_content_hidden"
            class="pb-8 pr-6 w-full lg:w-full"
            :label="language === 'uk' ? 'Приховати контент до початку події' : 'Hide content until event starts'"
          />
          <text-input
            v-model="form.location"
            :error="form.errors.location"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Місце проведення' : 'Location'"
          />
          <text-input
            v-model="form.online_link"
            :error="form.errors.online_link"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Посилання на онлайн-зустріч' : 'Online Meeting Link'"
          />
          <text-area
            v-model="form.tasks"
            :error="form.errors.tasks"
            class="pb-8 pr-6 w-full lg:w-full"
            :label="language === 'uk' ? 'Завдання' : 'Tasks'"
          />
          
          <!-- Attachments -->
          <div class="pb-8 pr-6 w-full lg:w-full">
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
            
            <!-- File List - New files to upload -->
            <div v-if="form.attachments && form.attachments.length > 0" class="mt-4">
              <h4 class="text-sm font-medium text-gray-700 mb-2">{{ language === 'uk' ? 'Нові файли:' : 'New Files:' }}</h4>
              <ul class="space-y-2">
                <li v-for="(file, index) in form.attachments" :key="'new-' + index" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
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
            
            <!-- Existing Attachments -->
            <div v-if="event.attachments && event.attachments.length > 0" class="mt-4">
              <h4 class="text-sm font-medium text-gray-700 mb-2">{{ language === 'uk' ? 'Існуючі файли:' : 'Existing Files:' }}</h4>
              <ul class="space-y-2">
                <li v-for="attachment in event.attachments" :key="attachment.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div>
                      <p class="text-sm font-medium text-gray-800">{{ attachment.original_filename }}</p>
                      <p class="text-xs text-gray-500">{{ formatFileSize(attachment.size) }}</p>
                    </div>
                  </div>
                  <div class="flex space-x-2">
                    <a 
                      :href="attachment.download_url" 
                      class="px-3 py-1 text-xs font-medium bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-200"
                      download
                    >
                      {{ language === 'uk' ? 'Завантажити' : 'Download' }}
                    </a>
                    <button 
                      type="button" 
                      @click="deleteAttachment(attachment.id)" 
                      class="px-3 py-1 text-xs font-medium bg-red-500 text-white rounded hover:bg-red-600 transition-colors duration-200"
                    >
                      {{ language === 'uk' ? 'Видалити' : 'Delete' }}
                    </button>
                  </div>
                </li>
              </ul>
            </div>
            
            <div v-if="form.errors.attachments" class="form-error mt-2">
              {{ form.errors.attachments }}
            </div>
          </div>
          
          <multi-select
            v-model="form.student_ids"
            :options="students"
            :error="form.errors.student_ids"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Студенти' : 'Students'"
            :placeholder="language === 'uk' ? 'Оберіть студентів' : 'Choose students'"
            track-by="id"
            label-prop="name"
          >
            <template #option="{ option }">
              <div class="flex items-center">
                <div class="text-sm">{{ option.name }}</div>
              </div>
            </template>
          </multi-select>
          <div v-if="isStudent" class="border border-amber-200 bg-amber-50 rounded-md p-4 pb-8 pr-6 w-full lg:w-1/2">
            <div class="text-sm font-medium text-amber-800 mb-1">{{ language === 'uk' ? 'Вчителі' : 'Teachers' }}</div>
            <div class="text-sm text-amber-700">
              {{ language === 'uk' ? 'Учні не можуть запрошувати вчителів на події' : 'Students cannot invite teachers to events' }}
            </div>
          </div>
          <multi-select
            v-else
            v-model="form.teacher_ids"
            :options="teachers"
            :error="form.errors.teacher_ids"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="language === 'uk' ? 'Вчителі' : 'Teachers'"
            :placeholder="language === 'uk' ? 'Оберіть вчителів' : 'Choose teachers'"
            track-by="id"
            label-prop="name"
          >
            <template #option="{ option }">
              <div class="flex items-center">
                <div class="text-sm">{{ option.name }}</div>
              </div>
            </template>
          </multi-select>
          <div v-if="isStudent" class="border border-amber-200 bg-amber-50 rounded-md p-4 pb-8 pr-6 w-full lg:w-full">
            <div class="text-sm font-medium text-amber-800 mb-1">{{ language === 'uk' ? 'Батьки' : 'Parents' }}</div>
            <div class="text-sm text-amber-700">
              {{ language === 'uk' ? 'Учні не можуть запрошувати батьків на події' : 'Students cannot invite parents to events' }}
            </div>
          </div>
          <multi-select
            v-else
            v-model="form.parent_ids"
            :options="parents"
            :error="form.errors.parent_ids"
            class="pb-8 pr-6 w-full lg:w-full"
            :label="language === 'uk' ? 'Батьки' : 'Parents'"
            :placeholder="language === 'uk' ? 'Оберіть батьків' : 'Choose parents'"
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
          <button
            v-if="isStudent && isOwnEvent"
            class="px-4 py-2 text-red-700 hover:text-red-500 transition-colors duration-200 ml-auto"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ language === 'uk' ? 'Видалити' : 'Delete' }}
          </button>
          <button
            v-else-if="!event.deleted_at"
            class="px-4 py-2 text-red-700 hover:text-red-500 transition-colors duration-200 ml-auto"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            {{ language === 'uk' ? 'Видалити' : 'Delete' }}
          </button>
          <loading-button
            class="ml-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded focus:outline-none"
            :loading="form.processing"
            type="submit"
          >
            {{ language === 'uk' ? 'Оновити' : 'Update' }}
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
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
    Icon,
    Link,
    LoadingButton,
    MultiSelect,
    SelectInput,
    TextArea,
    TextInput,
    TrashedMessage,
    CheckboxInput,
  },
  layout: Layout,
  props: {
    event: Object,
    types: Object,
    students: Array,
    teachers: Array,
    parents: Array,
    isStudent: Boolean,
    isOwnEvent: Boolean,
  },
  remember: 'form',
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: this.$inertia.form({
        // Remove _method: 'put' as it's not needed when using form.put()
        title: this.event.title,
        type: this.event.type,
        start_date: this.formatDateTimeForInput(this.event.start_date),
        duration: this.event.duration,
        content: this.event.content || '',
        tasks: this.event.tasks || '',
        location: this.event.location || '',
        online_link: this.event.online_link || '',
        is_content_hidden: this.event.is_content_hidden || false,
        student_ids: this.event.student_ids || [],
        teacher_ids: this.event.teacher_ids || [],
        parent_ids: this.event.parent_ids || [],
        attachments: [],
      }),
      dragover: false,
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
    
    
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
    updateLanguage(event) {
      this.language = event.detail.language;
    },
    formatDateTimeForInput(dateTimeString) {
      // Если входная дата - строка без "T" (только дата), добавляем текущее время
      if (typeof dateTimeString === 'string' && !dateTimeString.includes('T')) {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${dateTimeString}T${hours}:${minutes}`;
      }
      
      // Преобразуем входные данные в объект Date
      const d = new Date(dateTimeString);
      
      // Если дата недействительна, возвращаем пустую строку
      if (isNaN(d.getTime())) return '';
      
      // Форматируем дату в формате YYYY-MM-DDThh:mm
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      const hours = String(d.getHours()).padStart(2, '0');
      const minutes = String(d.getMinutes()).padStart(2, '0');
      
      return `${year}-${month}-${day}T${hours}:${minutes}`;
    },
    update() {
      // Clear teacher and parent fields for students
      if (this.isStudent) {
        this.form.teacher_ids = [];
        this.form.parent_ids = [];
      }
      
      // Use Inertia's put method directly
      this.form.put(`/events/${this.event.id}`, {
        onSuccess: () => {
          console.log('Event updated successfully');
          this.form.attachments = [];
          
          // No need to manually set success message as the server will provide it
        },
        onError: (errors) => {
          console.error('Error updating event:', errors);
        },
        preserveScroll: true,
      });
    },
    destroy() {
      if (confirm('Ви впевнені, що хочете видалити цю подію?')) {
        this.$inertia.delete(`/events/${this.event.id}`, {
          onSuccess: () => {
            this.$inertia.visit('/events');
          }
        });
      }
    },
    restore() {
      if (confirm('Ви впевнені, що хочете відновити цю подію?')) {
        this.$inertia.put(`/events/${this.event.id}/restore`)
      }
    },
    formatDateTimeForInput(date) {
      if (typeof date === 'string' && !date.includes('T')) {
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
    deleteAttachment(attachmentId) {
      if (confirm('Ви впевнені, що хочете видалити цей файл?')) {
        this.$inertia.delete(`/events/${this.event.id}/attachments/${attachmentId}`, {
          preserveScroll: true,
        });
      }
    },
  },
}
</script> 
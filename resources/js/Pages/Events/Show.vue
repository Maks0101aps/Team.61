<template>
  <div>
    <Head :title="event.title" />
    
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/events">Події</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ event.title }}
    </h1>

    <div class="bg-white rounded-md shadow overflow-hidden">
      <div class="p-8">
        <!-- Event Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <h3 class="text-lg font-semibold mb-2">Деталі події</h3>
            <div class="space-y-2">
              <p><span class="font-medium">Тип:</span> {{ event.type }}</p>
              <p><span class="font-medium">Дата початку:</span> {{ formatDate(event.start_date) }}</p>
              <p><span class="font-medium">Тривалість:</span> {{ event.duration }} хв.</p>
              <p><span class="font-medium">Місце:</span> {{ event.location }}</p>
              <p v-if="event.online_link">
                <span class="font-medium">Онлайн посилання:</span>
                <a :href="event.online_link" target="_blank" class="text-blue-600 hover:underline">{{ event.online_link }}</a>
              </p>
              <p><span class="font-medium">Створив:</span> {{ event.creator.name }}</p>
            </div>
          </div>

          <div>
            <h3 class="text-lg font-semibold mb-2">Ваша участь</h3>
            <div class="space-y-4">
              <p>
                <span class="font-medium">Статус:</span>
                <span :class="getEventStatusClasses(event.participation_status)">
                  {{ participationStatusText(event.participation_status) }}
                </span>
              </p>
              
              <div v-if="isParticipant" class="flex space-x-2">
                <button
                  @click="updateParticipation('accepted')"
                  class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none"
                  :disabled="event.participation_status === 'accepted'"
                >
                  Прийняти
                </button>
                <button
                  @click="updateParticipation('declined')"
                  class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none"
                  :disabled="event.participation_status === 'declined'"
                >
                  Відхилити
                </button>
              </div>
              <div v-else class="p-3 bg-yellow-50 border border-yellow-200 rounded-md text-yellow-700">
                <p>Ви не є учасником цієї події, тому не можете змінювати статус участі.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Event Content -->
        <div class="mb-6">
          <h3 class="text-lg font-semibold mb-2">Опис</h3>
          <div class="prose max-w-none" v-if="!event.is_content_hidden || canViewContent">
            <div v-html="event.content"></div>
          </div>
          <div v-else class="text-gray-500 italic">
            Контент буде доступний з початком події
          </div>
        </div>

        <!-- Tasks -->
        <div v-if="event.tasks" class="mb-6">
          <h3 class="text-lg font-semibold mb-2">Завдання</h3>
          <div class="prose max-w-none">
            <div v-html="event.tasks"></div>
          </div>
        </div>

        <!-- Attachments -->
        <div v-if="event.attachments && event.attachments.length > 0" class="mb-6">
          <h3 class="text-lg font-semibold mb-3">Файли</h3>
          <div class="space-y-2">
            <div v-for="attachment in event.attachments" :key="attachment.id" 
              class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                  Завантажити
                </a>
                <button 
                  v-if="attachment.can_delete"
                  @click="deleteAttachment(attachment.id)" 
                  class="px-3 py-1 text-xs font-medium bg-red-500 text-white rounded hover:bg-red-600 transition-colors duration-200"
                >
                  Видалити
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Participants -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <div v-if="event.students.length > 0">
            <h3 class="text-lg font-semibold mb-2">Студенти</h3>
            <ul class="space-y-1">
              <li v-for="student in event.students" :key="student.id" class="flex items-center">
                <span>{{ student.name }}</span>
                <span :class="getStatusClasses(student.participation_status)">
                  {{ participationStatusText(student.participation_status) }}
                </span>
              </li>
            </ul>
          </div>

          <div v-if="event.teachers.length > 0">
            <h3 class="text-lg font-semibold mb-2">Вчителі</h3>
            <ul class="space-y-1">
              <li v-for="teacher in event.teachers" :key="teacher.id" class="flex items-center">
                <span>{{ teacher.name }}</span>
                <span :class="getStatusClasses(teacher.participation_status)">
                  {{ participationStatusText(teacher.participation_status) }}
                </span>
              </li>
            </ul>
          </div>

          <div v-if="event.parents.length > 0">
            <h3 class="text-lg font-semibold mb-2">Батьки</h3>
            <ul class="space-y-1">
              <li v-for="parent in event.parents" :key="parent.id" class="flex items-center">
                <span>{{ parent.name }}</span>
                <span :class="getStatusClasses(parent.participation_status)">
                  {{ participationStatusText(parent.participation_status) }}
                </span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Comments -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Коментарі</h3>
          
          <!-- Comment Form -->
          <form @submit.prevent="addComment" class="mb-6">
            <textarea
              v-model="form.comment"
              class="w-full p-3 rounded border"
              rows="3"
              placeholder="Напишіть коментар..."
              required
            ></textarea>
            <div class="mt-2 flex justify-end">
              <button
                type="submit"
                class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 focus:outline-none"
                :disabled="form.processing"
              >
                Додати коментар
              </button>
            </div>
          </form>

          <!-- Comments List -->
          <div class="space-y-4">
            <div v-for="comment in event.comments" :key="comment.id" class="border-b pb-4">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <span class="font-medium">{{ comment.user.name }}</span>
                  <span class="text-gray-500 text-sm ml-2">{{ formatDate(comment.created_at) }}</span>
                </div>
                <button
                  v-if="comment.can_delete"
                  @click="deleteComment(comment.id)"
                  class="text-red-500 hover:text-red-700"
                >
                  Видалити
                </button>
              </div>
              <p class="text-gray-700">{{ comment.content }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  props: {
    event: Object,
    canViewContent: Boolean,
    isParticipant: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        comment: '',
      }),
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString('uk-UA')
    },
    participationStatusText(status) {
      const statusMap = {
        accepted: 'Прийнято',
        declined: 'Відхилено',
        pending: 'Очікує відповіді'
      }
      return statusMap[status] || status
    },
    getStatusClasses(status) {
      const baseClasses = 'ml-2 px-2 py-1 text-xs rounded';
      
      if (status === 'accepted') {
        return `${baseClasses} bg-green-100 text-green-800`;
      } else if (status === 'declined') {
        return `${baseClasses} bg-red-100 text-red-800`;
      } else if (status === 'pending') {
        return `${baseClasses} bg-yellow-100 text-yellow-800`;
      }
      
      return baseClasses;
    },
    getEventStatusClasses(status) {
      if (status === 'accepted') {
        return 'text-green-600';
      } else if (status === 'declined') {
        return 'text-red-600';
      } else if (status === 'pending') {
        return 'text-yellow-600';
      }
      
      return '';
    },
    updateParticipation(status) {
      this.$inertia.put(`/events/${this.event.id}/participation`, {
        status: status
      }, {
        preserveScroll: true,
        onSuccess: () => {
          this.event.participation_status = status;
        },
      });
    },
    addComment() {
      this.form.post(`/events/${this.event.id}/comments`, {
        preserveScroll: true,
        onSuccess: () => {
          this.form.comment = ''
        },
      })
    },
    deleteComment(commentId) {
      if (confirm('Ви впевнені, що хочете видалити цей коментар?')) {
        this.$inertia.delete(`/events/${this.event.id}/comments/${commentId}`, {
          preserveScroll: true,
        })
      }
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
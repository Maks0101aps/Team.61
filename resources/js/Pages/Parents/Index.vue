<template>
  <Head :title="language === 'uk' ? 'Батьки' : 'Parents'" />

  <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
      {{ language === 'uk' ? 'Батьки' : 'Parents' }}
    </h1>
    <div class="mt-4 md:mt-0 flex items-center">
      <Link 
        class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105"
        href="/parents/create"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span>{{ language === 'uk' ? 'Створити батьків' : 'Create Parent' }}</span>
      </Link>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow-lg p-6 mb-6 transition-all duration-300 hover:shadow-xl">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
      <div class="w-full md:w-1/2 mb-4 md:mb-0">
        <div class="relative">
          <input 
            v-model="form.search" 
            type="text" 
            :placeholder="language === 'uk' ? 'Пошук батьків...' : 'Search parents...'" 
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-300"
          >
          <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
      <div class="w-full md:w-1/3">
        <select 
          v-model="form.trashed" 
          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-300"
        >
          <option :value="null">{{ language === 'uk' ? 'Всі батьки' : 'All Parents' }}</option>
          <option value="with">{{ language === 'uk' ? 'З видаленими' : 'With Deleted' }}</option>
          <option value="only">{{ language === 'uk' ? 'Тільки видалені' : 'Only Deleted' }}</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Мобильный вид (карточки) -->
  <div class="md:hidden space-y-4">
    <div 
      v-for="parent in parents.data" 
      :key="parent.id" 
      class="bg-white rounded-xl shadow-md p-4 transition-all duration-300 hover:shadow-lg hover:scale-[1.01]"
      :class="{ 
        'border-l-4 border-red-400': parent.deleted_at,
        'border-l-4 border-green-400': $page.props.auth.user.role === 'parent' && parent.email === $page.props.auth.user.email
      }"
    >
      <div class="flex justify-between items-start">
        <div class="flex">
          <div class="mr-4 flex-shrink-0">
            <img 
              v-if="parent.photo" 
              :src="parent.photo" 
              class="w-12 h-12 rounded-full object-cover shadow-sm border border-gray-200" 
              :alt="parent.name"
            />
            <div v-else class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 shadow-sm border border-gray-200">
              <span>{{ parent.name.substring(0, 2) }}</span>
            </div>
          </div>
          <div>
            <Link 
              class="text-lg font-bold text-blue-800 hover:text-blue-600 transition-colors duration-200 flex items-center" 
              :href="`/parents/${parent.id}/edit`"
              :class="{ 'pointer-events-none opacity-60': ($page.props.auth.user.role === 'parent' && parent.email !== $page.props.auth.user.email) || 
                                                        $page.props.auth.user.role === 'student' }"
            >
              {{ parent.name }}
              <span v-if="parent.parent_type_name" class="ml-2 text-sm text-gray-600">({{ parent.parent_type_name }})</span>
              <span v-if="$page.props.auth.user.role === 'parent' && parent.email === $page.props.auth.user.email" class="ml-2 text-xs text-green-600">({{ language === 'uk' ? 'Ви' : 'You' }})</span>
            </Link>
            <div class="mt-2 space-y-1">
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                {{ parent.city || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                </svg>
                {{ parent.phone || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
              </div>
            </div>
          </div>
        </div>
        <div class="flex space-x-2">
          <Link 
            v-if="($page.props.auth.user.role !== 'parent' || parent.email === $page.props.auth.user.email) && 
                 $page.props.auth.user.role !== 'student'"
            :href="`/parents/${parent.id}/edit`" 
            class="p-2 text-blue-600 hover:text-blue-800 transition-colors duration-200"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
          </Link>
          <div 
            v-else
            class="p-2 text-gray-400 cursor-not-allowed"
            :title="$page.props.auth.user.role === 'parent' ? 
              (language === 'uk' ? 'Ви можете редагувати тільки свій профіль' : 'You can only edit your own profile') :
              (language === 'uk' ? 'Учні не можуть редагувати батьків' : 'Students cannot edit parents')"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
          </div>
          <button 
            v-if="($page.props.auth.user.role !== 'parent' || parent.email === $page.props.auth.user.email) && 
                 $page.props.auth.user.role !== 'student'"
            @click="destroy(parent)" 
            class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          </button>
          <div 
            v-else
            class="p-2 text-gray-400 cursor-not-allowed"
            :title="$page.props.auth.user.role === 'parent' ? 
              (language === 'uk' ? 'Ви можете видаляти тільки свій профіль' : 'You can only delete your own profile') :
              (language === 'uk' ? 'Учні не можуть видаляти батьків' : 'Students cannot delete parents')"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
    </div>
    <div v-if="parents.data.length === 0" class="bg-white rounded-xl shadow-md p-6 text-center text-gray-500">
      {{ language === 'uk' ? 'Записів не знайдено.' : 'No records found.' }}
    </div>
  </div>

  <!-- Десктопный вид (таблица) -->
  <div class="hidden md:block bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
    <table class="w-full">
      <thead>
        <tr class="bg-gradient-to-r from-blue-50 to-blue-100 text-left">
          <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Повне ім\'я' : 'Full Name' }}</th>
          <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Тип' : 'Type' }}</th>
          <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Місто' : 'City' }}</th>
          <th class="py-4 px-6 font-bold text-blue-900">{{ language === 'uk' ? 'Номер телефону' : 'Phone Number' }}</th>
          <th class="py-4 px-6 font-bold text-blue-900 text-center">{{ language === 'uk' ? 'Дії' : 'Actions' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="parent in parents.data" 
          :key="parent.id" 
          class="border-t border-gray-100 transition-colors duration-200 hover:bg-blue-50"
          :class="{ 
            'bg-red-50 hover:bg-red-100': parent.deleted_at,
            'bg-green-50 hover:bg-green-100': $page.props.auth.user.role === 'parent' && parent.email === $page.props.auth.user.email
          }"
        >
          <td class="py-4 px-6">
            <Link 
              class="font-medium text-blue-800 hover:text-blue-600 transition-colors duration-200 flex items-center" 
              :href="`/parents/${parent.id}/edit`"
              :class="{ 'pointer-events-none opacity-60': ($page.props.auth.user.role === 'parent' && parent.email !== $page.props.auth.user.email) || 
                                                        $page.props.auth.user.role === 'student' }"
            >
              <div class="mr-3 flex-shrink-0">
                <img 
                  v-if="parent.photo" 
                  :src="parent.photo" 
                  class="w-8 h-8 rounded-full object-cover shadow-sm border border-gray-200" 
                  :alt="parent.name"
                />
                <div v-else class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 shadow-sm border border-gray-200">
                  <span class="text-xs">{{ parent.name.substring(0, 2) }}</span>
                </div>
              </div>
              {{ parent.name }}
              <span v-if="$page.props.auth.user.role === 'parent' && parent.email === $page.props.auth.user.email" class="ml-2 text-xs text-green-600">({{ language === 'uk' ? 'Ви' : 'You' }})</span>
              <svg v-if="parent.deleted_at" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </Link>
          </td>
          <td class="py-4 px-6 text-gray-600">
            {{ parent.parent_type_name || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
          </td>
          <td class="py-4 px-6 text-gray-600">
            {{ parent.city || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
          </td>
          <td class="py-4 px-6 text-gray-600">
            {{ parent.phone || (language === 'uk' ? 'Не вказано' : 'Not specified') }}
          </td>
          <td class="py-4 px-6">
            <div class="flex justify-center space-x-3">
              <Link 
                v-if="($page.props.auth.user.role !== 'parent' || parent.email === $page.props.auth.user.email) && 
                     $page.props.auth.user.role !== 'student'"
                :href="`/parents/${parent.id}/edit`" 
                class="p-2 text-blue-600 hover:text-blue-800 transition-colors duration-200 rounded-full hover:bg-blue-100"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
              </Link>
              <div 
                v-else
                class="p-2 text-gray-400 cursor-not-allowed rounded-full"
                :title="$page.props.auth.user.role === 'parent' ? 
                  (language === 'uk' ? 'Ви можете редагувати тільки свій профіль' : 'You can only edit your own profile') :
                  (language === 'uk' ? 'Учні не можуть редагувати батьків' : 'Students cannot edit parents')"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
              </div>
              <button 
                v-if="($page.props.auth.user.role !== 'parent' || parent.email === $page.props.auth.user.email) && 
                     $page.props.auth.user.role !== 'student'"
                @click="destroy(parent)" 
                class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200 rounded-full hover:bg-red-100"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </button>
              <div 
                v-else
                class="p-2 text-gray-400 cursor-not-allowed rounded-full"
                :title="$page.props.auth.user.role === 'parent' ? 
                  (language === 'uk' ? 'Ви можете видаляти тільки свій профіль' : 'You can only delete your own profile') :
                  (language === 'uk' ? 'Учні не можуть видаляти батьків' : 'Students cannot delete parents')"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </td>
        </tr>
        <tr v-if="parents.data.length === 0">
          <td class="py-6 px-6 text-center text-gray-500" colspan="4">
            {{ language === 'uk' ? 'Записів не знайдено.' : 'No records found.' }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="mt-8">
    <pagination :links="parents.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import throttle from 'lodash/throttle'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
  },
  layout: Layout,
  props: {
    filters: Object,
    parents: Object,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/parents', this.form, { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = {
        search: null,
        trashed: null,
      }
    },
    destroy(parent) {
      if (confirm(this.language === 'uk' ? 'Ви впевнені, що хочете видалити цих батьків?' : 'Are you sure you want to delete this parent?')) {
        this.$inertia.delete(`/parents/${parent.id}`)
      }
    },
  },
  mounted() {
    
    if (this.$languageEventBus) {
      this.$languageEventBus.on('language-changed', (lang) => {
        this.language = lang
      })
    }
    
    
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.language = event.newValue
      }
    })
  }
}
</script>

<style scoped>

.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}


:deep(.pagination) {
  @apply flex justify-center;
}

:deep(.pagination-link) {
  @apply px-4 py-2 mx-1 rounded-lg transition-colors duration-200;
}

:deep(.pagination-link:not(.active)) {
  @apply bg-white text-blue-700 hover:bg-blue-50;
}

:deep(.pagination-link.active) {
  @apply bg-gradient-to-r from-blue-500 to-blue-600 text-white;
}
</style> 
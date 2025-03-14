<template>
  <div>
    <Head :title="language === 'uk' ? 'Головна Сторінка' : 'Home Page'" />
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden mb-8">
      <!-- Background with gradient overlay -->
      <div class="absolute inset-0 bg-gradient-to-r from-amber-600 to-amber-800 opacity-90"></div>
      
      <!-- Background pattern -->
      <div class="absolute inset-0 bg-pattern opacity-10"></div>
      
      <div class="relative max-w-5xl mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:py-32 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
          {{ language === 'uk' ? 'Шкільний Календар Подій' : 'School Event Calendar' }}
        </h1>
        <p class="mt-6 max-w-2xl mx-auto text-xl text-amber-100">
          {{ language === 'uk' ? 'Стеж за всіма важливими подіями у школі та не пропусти жодної дати!' : 'Keep track of all important school events and never miss a date!' }}
        </p>
        <div class="mt-10 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center">
          <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
            <a href="/events" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-amber-700 bg-white hover:bg-amber-50 transition-all">
              {{ language === 'uk' ? 'Переглянути події' : 'View Events' }}
            </a>
            <a href="/tasks" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-amber-800 hover:bg-amber-900 transition-all">
              {{ language === 'uk' ? 'Мої завдання' : 'My Tasks' }}
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Features Section -->
    <div class="py-12 bg-white">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-base font-semibold text-amber-600 tracking-wide uppercase">
            {{ language === 'uk' ? 'Можливості' : 'Features' }}
          </h2>
          <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
            {{ language === 'uk' ? 'Все, що потрібно для організації навчального процесу' : 'Everything you need to organize the educational process' }}
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            {{ language === 'uk' ? 'Наша платформа допомагає ефективно керувати шкільними подіями та завданнями.' : 'Our platform helps to effectively manage school events and tasks.' }}
          </p>
        </div>

        <div class="mt-12">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Feature 1 -->
            <div class="pt-6">
              <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                <div class="-mt-6">
                  <div>
                    <span class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-amber-500 to-amber-600 rounded-md shadow-lg">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </span>
                  </div>
                  <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">
                    {{ language === 'uk' ? 'Календар подій' : 'Event Calendar' }}
                  </h3>
                  <p class="mt-5 text-base text-gray-500">
                    {{ language === 'uk' ? 'Зручний календар для відстеження всіх шкільних заходів та важливих дат.' : 'Convenient calendar for tracking all school events and important dates.' }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Feature 2 -->
            <div class="pt-6">
              <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                <div class="-mt-6">
                  <div>
                    <span class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-amber-500 to-amber-600 rounded-md shadow-lg">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                      </svg>
                    </span>
                  </div>
                  <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">
                    {{ language === 'uk' ? 'Управління завданнями' : 'Task Management' }}
                  </h3>
                  <p class="mt-5 text-base text-gray-500">
                    {{ language === 'uk' ? 'Створюйте та відстежуйте завдання для учнів, вчителів та батьків.' : 'Create and track tasks for students, teachers, and parents.' }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Feature 3 -->
            <div class="pt-6">
              <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                <div class="-mt-6">
                  <div>
                    <span class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-amber-500 to-amber-600 rounded-md shadow-lg">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                    </span>
                  </div>
                  <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">
                    {{ language === 'uk' ? 'Управління контактами' : 'Contact Management' }}
                  </h3>
                  <p class="mt-5 text-base text-gray-500">
                    {{ language === 'uk' ? 'Зберігайте та керуйте контактами учнів, вчителів та батьків.' : 'Store and manage contacts of students, teachers, and parents.' }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  layout: Layout,
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
    }
  },
  mounted() {
    // Listen for language changes
    window.addEventListener('storage', (event) => {
      if (event.key === 'language') {
        this.language = event.newValue
      }
    })
  }
}
</script>

<style scoped>
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>

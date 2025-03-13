<template>
  <Head title="Login" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-gradient-to-br from-amber-50 to-gray-100">
    <div class="w-full max-w-xl">
      <div class="flex justify-end mb-4">
        <div class="inline-flex rounded-md shadow-sm" role="group">
          <button @click="setLanguage('uk')" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-amber-700 focus:z-10 focus:ring-2 focus:ring-amber-500" :class="{ 'bg-amber-100 text-amber-700': language === 'uk' }">
            Українська
          </button>
          <button @click="setLanguage('en')" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-100 hover:text-amber-700 focus:z-10 focus:ring-2 focus:ring-amber-500" :class="{ 'bg-amber-100 text-amber-700': language === 'en' }">
            English
          </button>
        </div>
      </div>
      
      <logo class="block mx-auto w-full max-w-md" height="100" />
      <form class="mt-6 bg-white rounded-lg shadow-xl overflow-hidden transform scale-110" @submit.prevent="login">
        <div class="px-12 py-14">
          <h1 class="text-center text-4xl font-bold text-gray-800">{{ language === 'uk' ? 'Ласкаво просимо!' : 'Welcome Back!' }}</h1>
          <div class="mt-6 mx-auto w-32 border-b-2 border-amber-300" />
          <text-input v-model="form.email" :error="form.errors.email" class="mt-12 text-lg" :label="language === 'uk' ? 'Електронна пошта' : 'Email'" type="email" autofocus autocapitalize="off" />
          <text-input v-model="form.password" :error="form.errors.password" class="mt-8 text-lg" :label="language === 'uk' ? 'Пароль' : 'Password'" type="password" />
          <div class="flex items-center justify-between mt-8">
            <label class="flex items-center select-none" for="remember">
              <input id="remember" v-model="form.remember" class="mr-1 h-5 w-5" type="checkbox" />
              <span class="text-base">{{ language === 'uk' ? "Запам'ятати мене" : 'Remember Me' }}</span>
            </label>
            <Link href="/register" class="text-amber-700 hover:text-amber-900 text-base">
              {{ language === 'uk' ? 'Потрібен обліковий запис? Зареєструватися' : 'Need an account? Register' }}
            </Link>
          </div>
        </div>
        <div class="flex px-12 py-5 bg-gradient-to-r from-amber-50 to-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-amber ml-auto" type="submit">{{ language === 'uk' ? 'Увійти' : 'Login' }}</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    Logo,
    TextInput,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk', // Default to Ukrainian, but check localStorage first
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: false,
      }),
    }
  },
  methods: {
    login() {
      this.form.post('/login')
    },
    setLanguage(lang) {
      this.language = lang
      localStorage.setItem('language', lang)
    }
  },
  mounted() {
    // Ensure we have a language set in localStorage
    if (!localStorage.getItem('language')) {
      localStorage.setItem('language', 'uk')
    }
  }
}
</script>

<style>
.btn-amber {
  padding: 0.75rem 2rem;
  border-radius: 0.375rem;
  background-image: linear-gradient(to right, var(--tw-gradient-stops));
  --tw-gradient-from: #f59e0b;
  --tw-gradient-to: #d97706;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
  color: white;
  font-size: 1rem;
  line-height: 1.25rem;
  font-weight: 700;
  white-space: nowrap;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.btn-amber:hover {
  --tw-gradient-from: #d97706;
  --tw-gradient-to: #b45309;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}

.btn-amber:focus {
  --tw-gradient-from: #d97706;
  --tw-gradient-to: #b45309;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}
</style>

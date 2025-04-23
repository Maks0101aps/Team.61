<template>
  <Head title="Login" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <div class="w-full max-w-xl">
      <div class="flex justify-end mb-4 space-x-3">
        <div class="inline-flex rounded-full shadow-md overflow-hidden">
          <button @click="setLanguage('uk')" 
                  type="button" 
                  class="relative px-5 py-2 text-sm font-medium transition-all duration-300 group overflow-hidden"
                  :class="language === 'uk' 
                    ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white' 
                    : 'bg-white text-gray-700 hover:bg-blue-50'">
            <span class="relative z-10">Українська</span>
            <span v-if="language === 'uk'" class="absolute inset-0 bg-blue-600 animate-pulse opacity-20"></span>
            <span class="absolute bottom-0 left-0 h-1 w-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                  :class="language === 'uk' ? 'bg-white' : 'bg-blue-500'"></span>
          </button>
          <button @click="setLanguage('en')" 
                  type="button" 
                  class="relative px-5 py-2 text-sm font-medium transition-all duration-300 group overflow-hidden"
                  :class="language === 'en' 
                    ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white' 
                    : 'bg-white text-gray-700 hover:bg-blue-50'">
            <span class="relative z-10">English</span>
            <span v-if="language === 'en'" class="absolute inset-0 bg-blue-600 animate-pulse opacity-20"></span>
            <span class="absolute bottom-0 left-0 h-1 w-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                  :class="language === 'en' ? 'bg-white' : 'bg-blue-500'"></span>
          </button>
        </div>
        <theme-toggle />
      </div>
      
      <logo class="block mx-auto w-full max-w-md" height="100" />
      <div class="mt-6 scale-110">
        <form class="bg-white rounded-lg shadow-xl overflow-hidden login-form" @submit.prevent="login">
          <div class="px-12 py-14">
            <h1 class="text-center text-4xl font-bold text-gray-800">{{ language === 'uk' ? 'Ласкаво просимо!' : 'Welcome Back!' }}</h1>
            <div class="mt-6 mx-auto w-32 border-b-2 border-blue-300" />
            <text-input v-model="form.email" :error="form.errors.email" class="mt-12 text-lg login-form-item" :style="{ animationDelay: '0.1s' }" :label="language === 'uk' ? 'Електронна пошта' : 'Email'" type="email" autofocus autocapitalize="off" />
            <text-input v-model="form.password" :error="form.errors.password" class="mt-8 text-lg login-form-item" :style="{ animationDelay: '0.2s' }" :label="language === 'uk' ? 'Пароль' : 'Password'" type="password" />
            <div class="flex items-center mt-8 login-form-item" :style="{ animationDelay: '0.3s' }">
              <label class="flex items-center select-none" for="remember">
                <input id="remember" v-model="form.remember" class="mr-1 h-5 w-5" type="checkbox" />
                <span class="text-base">{{ language === 'uk' ? "Запам'ятати мене" : 'Remember Me' }}</span>
              </label>
            </div>
            <div class="mt-6 py-2 login-form-item" :style="{ animationDelay: '0.4s' }">
              <Link href="/register" class="text-blue-700 hover:text-blue-900 text-sm font-medium register-link">
                {{ language === 'uk' ? 'Потрібен обліковий запис? Зареєструватися' : 'Need an account? Register' }}
              </Link>
            </div>
            <div class="mt-2 py-1 login-form-item" :style="{ animationDelay: '0.5s' }">
              <Link href="/forgot-password" class="text-blue-700 hover:text-blue-900 text-sm font-medium">
                {{ language === 'uk' ? 'Забули свій пароль?' : 'Forgot your password?' }}
              </Link>
            </div>
          </div>
          <div class="flex px-12 py-5 bg-gradient-to-r from-blue-50 to-gray-50 border-t border-gray-100 login-form-item" :style="{ animationDelay: '0.6s' }">
            <loading-button :loading="form.processing" class="btn-blue ml-auto" buttonType="submit">{{ language === 'uk' ? 'Увійти' : 'Login' }}</loading-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import ThemeToggle from '@/Components/ThemeToggle.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    Logo,
    TextInput,
    ThemeToggle,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: false,
      }),
    }
  },
  methods: {
    login() {
      this.form.post('/login', {
        onFinish: () => {
        },
      })
    },
    setLanguage(lang) {
      this.language = lang
      localStorage.setItem('language', lang)
      
      window.dispatchEvent(new CustomEvent('language-change', {
        detail: { language: lang }
      }))
    }
  },
  mounted() {
    if (!localStorage.getItem('language')) {
      localStorage.setItem('language', 'uk')
    }
  }
}
</script>

<style>
.btn-blue {
  padding: 0.75rem 2rem;
  border-radius: 0.375rem;
  background-image: linear-gradient(to right, var(--tw-gradient-stops));
  --tw-gradient-from: #6CB4EE;
  --tw-gradient-to: #4A90E2;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
  color: white;
  font-size: 1rem;
  line-height: 1.25rem;
  font-weight: 700;
  white-space: nowrap;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.btn-blue:hover {
  --tw-gradient-from: #4A90E2;
  --tw-gradient-to: #357ABD;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}

.btn-blue:focus {
  --tw-gradient-from: #4A90E2;
  --tw-gradient-to: #357ABD;
  --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}

.login-form {
  animation: fadeIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.login-form-item {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.register-link {
  position: relative;
  transition: all 0.3s ease;
}

.register-link:hover {
  transform: translateX(5px);
}

.register-link:after {
  content: "→";
  opacity: 0;
  margin-left: -5px;
  transition: all 0.3s ease;
}

.register-link:hover:after {
  opacity: 1;
  margin-left: 5px;
}
</style>

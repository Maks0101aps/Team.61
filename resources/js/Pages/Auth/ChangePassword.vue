<template>
  <Head :title="language === 'uk' ? 'Зміна пароля' : 'Change Password'" />

  <div class="flex items-center justify-center p-6 min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <div class="w-full max-w-xl">
      <logo class="block mx-auto w-full max-w-md" height="100" />
      <form class="mt-6 bg-white rounded-lg shadow-xl overflow-hidden transform" @submit.prevent="submit">
        <div class="px-12 py-14">
          <h1 class="text-center text-4xl font-bold text-gray-800">{{ language === 'uk' ? 'Зміна пароля' : 'Change Password' }}</h1>
          <div class="mt-6 mx-auto w-32 border-b-2 border-blue-300" />
          
          <div class="mt-8 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded-md">
            <div class="flex">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <div>
                <p class="font-semibold">{{ language === 'uk' ? 'Необхідно змінити пароль' : 'Password change required' }}</p>
                <p class="text-sm mt-1">{{ language === 'uk' ? 'Для безпеки вашого облікового запису, вам необхідно змінити тимчасовий пароль.' : 'For the security of your account, you need to change your temporary password.' }}</p>
              </div>
            </div>
          </div>
          
          <div class="mt-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">
              {{ language === 'uk' ? 'Електронна пошта' : 'Email' }}
            </label>
            <div class="bg-gray-100 px-4 py-3 rounded-lg text-gray-700">
              {{ user.email }}
            </div>
          </div>
          
          <text-input 
            v-model="form.current_password" 
            :error="form.errors.current_password" 
            class="mt-6" 
            type="password" 
            :label="language === 'uk' ? 'Поточний пароль' : 'Current Password'" 
          />
          
          <text-input 
            v-model="form.password" 
            :error="form.errors.password" 
            class="mt-6" 
            type="password" 
            :label="language === 'uk' ? 'Новий пароль' : 'New Password'" 
            :help-text="language === 'uk' ? 'Пароль повинен містити мінімум 8 символів' : 'Password must be at least 8 characters'"
          />
          
          <text-input 
            v-model="form.password_confirmation" 
            class="mt-6" 
            type="password" 
            :label="language === 'uk' ? 'Підтвердити новий пароль' : 'Confirm New Password'" 
          />
        </div>
        
        <div class="flex px-12 py-5 bg-gradient-to-r from-blue-50 to-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-blue ml-auto" buttonType="submit">
            {{ language === 'uk' ? 'Змінити пароль' : 'Change Password' }}
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Logo,
    TextInput,
    LoadingButton,
  },
  props: {
    user: Object,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: this.$inertia.form({
        current_password: '',
        password: '',
        password_confirmation: '',
      }),
    }
  },
  mounted() {
    window.addEventListener('language-changed', this.updateLanguage);
  },
  beforeUnmount() {
    window.removeEventListener('language-changed', this.updateLanguage);
  },
  methods: {
    submit() {
      this.form.post('/password/change')
    },
    updateLanguage(event) {
      this.language = event.detail.language;
    }
  },
}
</script> 
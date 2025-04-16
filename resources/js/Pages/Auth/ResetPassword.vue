<template>
  <Head title="Сброс пароля" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <div class="w-full max-w-xl">
      <Logo class="block mx-auto w-full max-w-md" height="100" />
      <form class="mt-6 bg-white rounded-lg shadow-xl overflow-hidden transform scale-110" @submit.prevent="submit">
        <div class="px-12 py-14">
          <h1 class="text-center text-4xl font-bold text-gray-800">{{ language === 'uk' ? 'Скидання пароля' : 'Reset Password' }}</h1>
          <div class="mt-6 mx-auto w-32 border-b-2 border-blue-300" />
          
          <text-input v-model="form.email" :error="form.errors.email" class="mt-8 text-lg" :label="language === 'uk' ? 'Електронна пошта' : 'Email'" type="email" disabled />
          
          <text-input v-model="form.password" :error="form.errors.password" class="mt-8 text-lg" :label="language === 'uk' ? 'Новий пароль' : 'New Password'" type="password" />
          
          <text-input v-model="form.password_confirmation" :error="form.errors.password_confirmation" class="mt-8 text-lg" :label="language === 'uk' ? 'Підтвердження пароля' : 'Confirm Password'" type="password" />
        </div>
        
        <div class="flex px-12 py-5 bg-gradient-to-r from-blue-50 to-gray-50 border-t border-gray-100">
          <Link href="/login" class="flex items-center text-blue-700 hover:text-blue-900 font-medium">
            {{ language === 'uk' ? 'Назад до входу' : 'Back to Login' }}
          </Link>
          <loading-button :loading="form.processing" class="btn-blue ml-auto" buttonType="submit">
            {{ language === 'uk' ? 'Скинути пароль' : 'Reset Password' }}
          </loading-button>
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
    Logo,
    TextInput,
    LoadingButton,
  },
  props: {
    token: String,
    email: String,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: this.$inertia.form({
        token: this.token,
        email: this.email,
        password: '',
        password_confirmation: '',
      }),
    }
  },
  methods: {
    submit() {
      this.form.post('/reset-password')
    },
  },
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
</style>

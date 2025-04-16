<template>
  <Head title="Подтверждение Email" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <div class="w-full max-w-xl">
      <logo class="block mx-auto w-full max-w-md" height="100" />
      <form class="mt-6 bg-white rounded-lg shadow-xl overflow-hidden transform scale-110" @submit.prevent="verifyEmail">
        <div class="px-12 py-14">
          <h1 class="text-center text-4xl font-bold text-gray-800">{{ language === 'uk' ? 'Підтвердження Email' : 'Email Verification' }}</h1>
          <div class="mt-6 mx-auto w-32 border-b-2 border-blue-300" />
          
          <div v-if="status" class="mt-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ status }}
          </div>
          
          <p class="mt-8 text-gray-600">
            {{ language === 'uk' ? 'Ми надіслали код підтвердження на вашу електронну пошту. Будь ласка, введіть його нижче для підтвердження вашого облікового запису.' : 'We have sent a verification code to your email. Please enter it below to verify your account.' }}
          </p>

          <!-- Parent banner message -->
          <div v-if="isParent" class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>
              {{ language === 'uk' ? 'Після підтвердження електронної пошти, ви зможете додати інформацію про своїх дітей у розділі "Учні".' : 'After verifying your email, you will be able to add information about your children in the "Students" section.' }}
            </span>
          </div>
          
          <text-input v-model="form.email" :error="form.errors.email" class="mt-8 text-lg" :label="language === 'uk' ? 'Електронна пошта' : 'Email'" type="email" readonly />
          
          <text-input v-model="form.code" :error="form.errors.code" class="mt-8 text-lg" :label="language === 'uk' ? 'Код підтвердження' : 'Verification Code'" type="text" autofocus />
          
          <div class="mt-6 text-sm text-gray-600">
            {{ language === 'uk' ? 'Не отримали код?' : 'Did not receive the code?' }}
            <button type="button" @click="resendCode" class="text-blue-700 hover:text-blue-900">
              {{ language === 'uk' ? 'Надіслати ще раз' : 'Resend code' }}
            </button>
          </div>
        </div>
        
        <div class="flex px-12 py-5 bg-gradient-to-r from-blue-50 to-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-blue ml-auto" buttonType="submit">
            {{ language === 'uk' ? 'Підтвердити' : 'Verify' }}
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
    email: String,
    status: String,
    userRole: String,
  },
  data() {
    return {
      language: localStorage.getItem('language') || 'uk',
      form: this.$inertia.form({
        email: this.email || '',
        code: '',
      }),
      resendForm: this.$inertia.form({
        email: this.email || '',
      }),
    }
  },
  computed: {
    isParent() {
      return this.userRole === 'parent';
    }
  },
  methods: {
    verifyEmail() {
      this.form.post('/email/verify')
    },
    resendCode() {
      this.resendForm.post('/email/resend', {
        preserveScroll: true,
        onSuccess: () => {
          // Можно добавить уведомление об успешной отправке
        },
      })
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

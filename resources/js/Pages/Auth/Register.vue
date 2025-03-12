<template>
  <Head title="Register" />
  <div class="flex items-center justify-center p-6 min-h-screen bg-indigo-800">
    <div class="w-full max-w-md">
      <logo class="block mx-auto w-full max-w-xs fill-white" height="50" />
      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="register">
        <div class="px-10 py-12">
          <h1 class="text-center text-3xl font-bold">Register</h1>
          <div class="mt-6 mx-auto w-24 border-b-2" />
          
          <div class="mt-10 grid grid-cols-2 gap-6">
            <text-input v-model="form.first_name" :error="form.errors.first_name" class="col-span-1" label="First Name" />
            <text-input v-model="form.last_name" :error="form.errors.last_name" class="col-span-1" label="Last Name" />
          </div>
          
          <text-input v-model="form.middle_name" :error="form.errors.middle_name" class="mt-6" label="Middle Name (Optional)" />
          <text-input v-model="form.email" :error="form.errors.email" class="mt-6" label="Email" type="email" autocapitalize="off" />
          <text-input v-model="form.password" :error="form.errors.password" class="mt-6" label="Password" type="password" />
          <text-input v-model="form.password_confirmation" :error="form.errors.password_confirmation" class="mt-6" label="Confirm Password" type="password" />
          
          <select-input v-model="form.role" :error="form.errors.role" class="mt-6" label="Role">
            <option value="" disabled>Select a role</option>
            <option v-for="(label, value) in roles" :key="value" :value="value">{{ label }}</option>
          </select-input>
          
          <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center">
              <Link href="/login" class="text-indigo-600 hover:text-indigo-800">
                Already have an account? Login
              </Link>
            </div>
          </div>
        </div>
        <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Register</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    Logo,
    TextInput,
    SelectInput,
  },
  props: {
    roles: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        middle_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
      }),
    }
  },
  methods: {
    register() {
      this.form.post('/register')
    },
  },
}
</script> 
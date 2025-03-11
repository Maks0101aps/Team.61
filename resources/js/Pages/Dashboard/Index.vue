<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center">
      <Head title="Панель керування" />
      <h1 class="mb-8 text-5xl font-extrabold text-gray-800">Календар подій</h1>
      <p class="mb-8 text-lg text-gray-600">
        Стеж за всіма важливими подіями у школі та не пропусти жодної дати!
      </p>
      <a
      href="#"
        class="px-6 py-3 text-lg font-medium text-white bg-indigo-600 rounded-lg hover:bg-orange-500 transition-all"
      >
        Детальніше
      </a>
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
}
</script>

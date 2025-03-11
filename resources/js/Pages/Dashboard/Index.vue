<template>
  <div>
    <Head title="Панель керування" />
    <h1 class="mb-8 text-3xl font-bold">Панель керування</h1>
    <p class="mb-8 leading-normal">Вітаємо! Ласкаво просимо до Ping CRM, демонстраційного додатку, розробленого для ілюстрації роботи <a class="text-indigo-500 hover:text-orange-600 underline" href="https://inertiajs.com">Inertia.js</a>.</p>
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

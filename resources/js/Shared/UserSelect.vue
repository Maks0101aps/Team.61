<template>
  <div>
    <multi-select
      v-model="selectedUsers"
      :options="users"
      :loading="loading"
      :label="label"
      :error="error"
      :placeholder="placeholder"
      label-prop="full_name"
      track-by="id"
      @search-change="searchUsers"
    >
      <template #option="{ option }">
        <div class="flex items-center">
          <div>
            <div class="text-sm font-medium">{{ option.full_name }}</div>
            <div class="text-xs text-gray-500">{{ option.email }}</div>
          </div>
        </div>
      </template>
      <template #tag="{ option, remove }">
        <span class="multiselect__tag">
          <span>{{ option.full_name }}</span>
          <span class="multiselect__tag-icon" @click="remove(option)" />
        </span>
      </template>
    </multi-select>
  </div>
</template>

<script>
import { ref, watch } from 'vue'
import { debounce } from 'lodash'
import { router } from '@inertiajs/vue3'
import MultiSelect from './MultiSelect.vue'

export default {
  components: {
    MultiSelect,
  },
  props: {
    modelValue: {
      type: Array,
      default: () => [],
    },
    label: {
      type: String,
      default: 'Користувачі',
    },
    error: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: 'Оберіть користувачів',
    },
    role: {
      type: String,
      required: true,
      validator: (value) => ['student', 'parent', 'teacher'].includes(value),
    },
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const users = ref([])
    const loading = ref(false)
    const selectedUsers = ref(props.modelValue)

    watch(selectedUsers, (newValue) => {
      emit('update:modelValue', newValue)
    })

    const searchUsers = debounce(async (query) => {
      if (!query) {
        users.value = []
        return
      }

      loading.value = true
      try {
        const response = await router.get(`/api/users/search`, {
          params: {
            query,
            role: props.role,
          },
          preserveState: true,
          preserveScroll: true,
        })
        users.value = response.data
      } catch (error) {
        console.error('Error searching users:', error)
        users.value = []
      } finally {
        loading.value = false
      }
    }, 300)

    return {
      users,
      loading,
      selectedUsers,
      searchUsers,
    }
  },
}
</script> 
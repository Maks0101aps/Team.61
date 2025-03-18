<template>
  <div>
    <label v-if="label" class="block text-sm font-medium text-gray-700">
      <span v-if="value.length">{{ label }}</span>
      <span v-else class="text-gray-500">{{ label }}</span>
    </label>
    <multiselect
      v-model="value"
      :options="options"
      :multiple="true"
      :close-on-select="false"
      :clear-on-select="false"
      :preserve-search="true"
      :placeholder="placeholder"
      :preselect-first="false"
      :loading="loading"
      :label="labelProp"
      :track-by="trackBy"
      class="mt-1"
      @update:modelValue="$emit('update:modelValue', $event)"
    >
      <template v-if="$slots['option']" #option="props">
        <slot name="option" v-bind="props" />
      </template>
      <template v-if="$slots['tag']" #tag="props">
        <slot name="tag" v-bind="props" />
      </template>
    </multiselect>
    <div v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'

export default {
  components: {
    Multiselect,
  },
  props: {
    modelValue: {
      type: [Array, Object],
      default: () => [],
    },
    options: {
      type: Array,
      default: () => [],
    },
    label: {
      type: String,
      default: '',
    },
    error: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: 'Оберіть опції',
    },
    labelProp: {
      type: String,
      default: 'name',
    },
    trackBy: {
      type: String,
      default: 'id',
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['update:modelValue'],
  computed: {
    value: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      },
    },
  },
}
</script>

<style>
.multiselect {
  @apply relative mx-auto w-full;
}

.multiselect__tags {
  @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm min-h-[38px] py-1 pl-3 pr-10;
}

.multiselect__placeholder {
  @apply text-gray-400 text-sm p-0 m-0;
}

.multiselect__input {
  @apply border-none p-0 m-0;
}

.multiselect__single {
  @apply text-gray-700 text-sm m-0;
}

.multiselect__select {
  @apply h-[38px] w-[30px];
}

.multiselect__content-wrapper {
  @apply border border-gray-300 rounded-md mt-1;
}

.multiselect__option {
  @apply text-sm text-gray-700 p-2;
}

.multiselect__option--highlight {
  @apply bg-blue-500 text-white;
}

.multiselect__option--selected {
  @apply bg-blue-100 text-blue-800 font-semibold;
}

.multiselect__option--selected.multiselect__option--highlight {
  @apply bg-red-500 text-white font-normal;
}

.multiselect__tag {
  @apply bg-blue-100 text-blue-800 text-sm rounded-md py-0.5 pl-2 pr-1 mr-1 mb-1;
}

.multiselect__tag-icon {
  @apply rounded-md ml-1 hover:bg-red-500;
}

.multiselect__tag-icon:after {
  @apply text-blue-800;
}

.multiselect__tag-icon:hover:after {
  @apply text-white;
}

.multiselect--disabled {
  @apply opacity-50 cursor-not-allowed;
}

.multiselect--disabled .multiselect__select {
  @apply bg-transparent;
}
</style> 
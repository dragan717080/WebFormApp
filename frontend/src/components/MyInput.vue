<template>
  <div >
    <label :for="id" class="block text-sm bold leading-6 text-gray-900">{{ label }}</label>
    <div class="mt-2">
      <input
        :id="id"
        ref="inputRef"
        :type="type"
        :autocomplete="id"
        class="form-input block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600 sm:text-sm sm:leading-6"
        :name="id"
        @input="$emit('update:modelValue', $event.target.value)"
        :class="inputClasses"
      >
      <div v-if="errors">
        <div 
          v-for="(error, index) in errors" 
          :key="index"
          class="error"
        >
          {{ error }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  modelValue: {
    type: String,
    required: true,
  },
  id: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    default: "text",
  },
  errors: {
    type: Array,
    required: true,
  },
  required: {
    type: Boolean,
    required: true,
  }
});

const inputClasses = [
    'form-input',
    'block',
    'w-full',
    'rounded-md',
    'border-0',
    'py-1.5',
    'text-gray-900',
    'shadow-sm',
    'ring-1',
    'ring-inset',
    'ring-gray-300',
    'placeholder:text-gray-400',
    'focus:ring-2',
    'focus:ring-inset',
    'focus:ring-sky-600',
    'sm:text-sm',
    'sm:leading-6',
    props.errors.length && 'focus:ring-rose-500',
    props.disabled && 'opacity-50 cursor-default',
]

defineEmits(['update:modelValue'])

</script>

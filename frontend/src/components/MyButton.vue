<template>
  <!-- To do: return 'disabled' prop -->
  <button
    @click="onClick"
    :type="type"
    :class="buttonClasses"
  >
    <slot></slot>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useIsLoadingStore } from '@/stores';
import { storeToRefs } from 'pinia';

const { isLoading } = storeToRefs(useIsLoadingStore())

const props = defineProps({
  type: {
    type: String,
    required: false
  },
  isFullWidth: {
    type: Boolean,
    required: false
  },
  children: {
    type: Array,
    required: false
  },
  onClick: {
    type: Function,
    required: false,
  },
  secondary: {
    type: Boolean,
    required: false,
  },
  disabled: {
    type: Boolean,
    required: false
  },
  danger: {
    type: Boolean,
    required: false,
  }
});

const buttonClasses = computed(() => [
  'row-h',
  'rounded-md',
  'px-3',
  'py-2',
  'text-sm',
  'semibold',
  'focus-visible:outline',
  'focus-visible:outline-2',
  'focus-visible:outline-offset-2',
  isLoading.value && 'opacity-50 cursor-default',
  props.isFullWidth && 'w-full',
  props.secondary ? 'text-gray-900' : 'text-white',
  props.danger ? 'bg-rose-500 hover:bg-rose-600 focus-visible:outline-rose-600' : 'bg-sky-500 hover:bg-sky-600 focus-visible:outline-sky-600'
]);

</script>

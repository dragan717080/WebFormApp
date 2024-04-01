<template>
  <Initializator />
  <FormWrapper :variant="variant" :toggleVariant="toggleVariant" />
</template>

<script setup lang='ts'>
import { ref } from 'vue'

const variant = ref<'DEAL' | 'ACCOUNT'>('DEAL')

const toggleVariant = () => {
  variant.value = variant.value === 'DEAL' ? 'ACCOUNT' : 'DEAL'
}

</script>

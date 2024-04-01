<template>
  <h2 class="!mt-0">{{ props.msg }}</h2>
  <p>
    Zoho Test App
  </p>
</template>

<script setup lang="ts">

const props = defineProps<{
  msg: string
  optionalProp?: number
}>()

</script>

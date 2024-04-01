<script setup lang="ts">
import { useHead } from '@vueuse/head'
import './styles/globals.css'
import './styles/fonts.css'

useHead({
  title: 'CRMOZ Test',
  meta: [
    { name: 'description', content: 'CRMOZ Test' },
  ],
})
</script>

<template>
  <router-view />
</template>

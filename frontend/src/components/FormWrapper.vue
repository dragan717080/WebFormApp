<template>
  <div class='flex-1 col-v py-12 sm:px-6 lg:px-8 bg-gray-100'>
    <div class='sm:mx-auto sm:w-full sm:max-w-md'>
      <div v-if="!isLoading">
        <img
          src='@/assets/images/logo.webp'
          href='./'
          class='w-40 xl:w-44 mx-auto'
          alt='App Logo'
        >
        <h2 class='mt-6 text-center text-3xl semibold tracking-tight text-gray-900'>
          {{ variant === 'DEAL' ? 'CREATE DEAL' : 'CREATE ACCOUNT' }}
        </h2>
      </div>
      <Loader v-else />
    </div>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white px-4 py-8 shadow sm:rounded-lg sm:px-10">
        <section v-if="variant === 'DEAL'">
          <DealForm />
        </section>
        <section v-else>
          <AccountForm />
        </section>
      </div>
      <div className="row gap-2 text-lg mt-6 px-2 text-gray-500">
        <span class="">Create</span>
        <span
          @click="toggleVariant"
          class="pointer text-sky-500"
        >
          {{ reverseVariant[0] + reverseVariant.slice(1).toLowerCase() }}
        </span>
        <span>instead</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useIsLoadingStore } from '@/stores'
import { storeToRefs } from 'pinia'

const { isLoading } = storeToRefs(useIsLoadingStore())

const props = defineProps<{
  variant: 'DEAL' | 'ACCOUNT'
  toggleVariant: () => void
}>()

const reverseVariant = computed(() => props.variant === 'DEAL' ? 'ACCOUNT' : 'DEAL') 
</script>

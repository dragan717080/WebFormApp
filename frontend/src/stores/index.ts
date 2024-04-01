import { acceptHMRUpdate, defineStore } from 'pinia';
import { ref } from 'vue';

export const useIsLoadingStore = defineStore('isLoading', () => {
  const isLoading = ref<boolean>(true)

  const setIsLoading = (newIsLoading: boolean) => {
    isLoading.value = newIsLoading
  }

  return {
    isLoading,
    setIsLoading
  }
})

/**
 * Since Account to Deal is one-to-many,
 * we only need to fetch all accounts (limited to first 15
 * on the backend) for performance.
 */
export const useAllAccountsStore = defineStore('allAccounts', () => {
  const allAccounts = ref([])

  const setAllAccounts = (newAllAccounts: boolean) => {
    allAccounts.value = newAllAccounts
  }

  return {
    allAccounts,
    setAllAccounts
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useIsLoadingStore, useAllAccountsStore, import.meta.hot));

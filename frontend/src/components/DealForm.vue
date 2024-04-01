<template>
  <form class='space-y-6' @submit.prevent='validate'>
    <label for="accountSelect" class="block text-sm bold leading-6 text-gray-900">Account</label>
    <select id="accountSelect" class="!mt-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600" v-model="selectedAccount">
      <option v-for="(option, index) in allAccounts" v-bind:value="option.id" :key="index">
        {{ option.Account_Name }}
      </option>
    </select>
    <div v-if="errors" class="!mt-0">
      <div 
        v-for="(error, index) in errors.selected" 
        :key="index"
        class="error"
      >
        {{ error }}
      </div>
    </div>
    <MyInput v-model='nameRef' ref='nameElementRef' label='Name' id='name' :errors='errors.name' required />
    <MyInput v-model='stageRef' ref='stageElementRef' label='Stage' id='stage' :errors='errors.stage' required />
    <MyButton type='submit' isFullWidth :disabled="isLoading">Create</MyButton>
  </form>
</template>

<script setup lang='ts'>
import { ref } from 'vue'
import MyInput from './MyInput.vue'
import MyButton from './MyButton.vue'
import { FormValidator, CreationHandler } from '@/utils'
import { useToast } from 'vue-toastification'
import { useIsLoadingStore, useAllAccountsStore } from '@/stores'
import { storeToRefs } from 'pinia'

const { setIsLoading } = useIsLoadingStore()
const { isLoading } = storeToRefs(useIsLoadingStore())
const { allAccounts } = storeToRefs(useAllAccountsStore())

const toast = useToast()

const nameRef = ref<string>('')
const stageRef = ref<string>('')
const selectedAccount = ref<string>('')
const nameElementRef = ref<HTMLDivElement|null>(null)
const stageElementRef = ref<HTMLDivElement|null>(null)

const errors = ref({
  name: [],
  stage: []
})

// On successful creation reset form fields
const resetInputFields = (): void => {
  // To do: uncomment
  // nameRef.value = ''
  nameElementRef.value.$refs.inputRef.value = ''
  // stageRef.value = ''
  stageElementRef.value.$refs.inputRef.value = ''
  selectedAccount.value = ''
}

const validate = (): void => {
  const formValues = {
    name: nameRef.value,
    stage: stageRef.value,
    selected: selectedAccount.value
  }

  const formValidator = new FormValidator(formValues)

  errors.value = formValidator.errors

  if (formValidator.allFieldsAreOk) {
    formValues.selected = {
      'id': selectedAccount.value,
      'name': allAccounts.value.find(account => account.id === selectedAccount.value).Account_Name,
    }
    createDeal(formValues)
  }
}

/**
 * Deal was created successfully.
 */
const handleCreatedDeal = (): void => {
  toast.success('Successfully created deal', { timeout: 2000 })
  setIsLoading(false)
  resetInputFields()
}

const createDeal = async (formValues) => {
  setIsLoading(true)
  // eslint-disable-next-line
  new CreationHandler('deal', handleCreatedDeal, formValues)
}

</script>

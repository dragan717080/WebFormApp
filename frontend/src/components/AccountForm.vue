<template>
  <form class="space-y-6" @submit.prevent="validate">
    <MyInput v-model="nameRef" ref="nameElementRef" label="Name" id="name" :errors="errors.name" required />
    <MyInput v-model="websiteRef" ref="websiteElementRef" label="Website" id="website" :errors="errors.website" required />
    <PhoneInput :key="phoneKey" ref="phoneElementRef" :renderWithErrors="renderWithErrors" :updateRefs="updatePhoneNumberRefs" />
    <MyButton type="submit" isFullWidth>Create</MyButton>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import MyInput from './MyInput.vue'
import MyButton from './MyButton.vue'
import { FormValidator, CreationHandler } from '@/utils'
import { useToast } from 'vue-toastification'
import { useIsLoadingStore, useAllAccountsStore } from '@/stores'
import { storeToRefs } from 'pinia'

const { setIsLoading } = useIsLoadingStore()
const { setAllAccounts } = useAllAccountsStore()
const { allAccounts } = storeToRefs(useAllAccountsStore())

const toast = useToast()

const nameRef = ref<string>('')
const phoneNumberRef = ref<string>('')
const isValidPhoneNumberRef = ref<boolean>(false)
const renderWithErrors = ref<boolean>(false)
const websiteRef = ref<string>('')
const phoneKey = ref<number>(0)

const nameElementRef = ref<HTMLDivElement|null>(null)
const websiteElementRef = ref<HTMLDivElement|null>(null)
const phoneElementRef = ref<HTMLDivElement|null>(null)

// Initialize 'errors' altho its empty to prevent errors at render
const errors = ref({
  name: [],
  website: [],
  phone: [],
  isValidPhoneNumber: []
})

// On successful creation reset form fields
const resetInputFields = (): void => {
  // To do: uncomment
  nameRef.value = ''
  nameElementRef.value.$refs.inputRef.value = ''
  // To do: uncomment
  websiteRef.value = ''
  websiteElementRef.value.$refs.inputRef.value = ''
  phoneKey.value += 1
}

const validate = () => {
  const formValues = {
    name: nameRef.value,
    website: websiteRef.value,
    phone: phoneNumberRef.value,
    isValidPhoneNumber: isValidPhoneNumberRef.value,
  }

  const formValidator = new FormValidator(formValues)
  errors.value = formValidator.errors

  if (!isValidPhoneNumberRef.value) {
    renderWithErrors.value = true
  }

  if (formValidator.allFieldsAreOk) {
    createAccount(formValues)
  }
}

/**
 * Account was created successfully.
 * To do: add it to Pinia.
 */
const handleCreatedAccount = (responseData): void => {
  toast.success('Successfully created account', { timeout: 2000 })
  setIsLoading(false)
  resetInputFields()
  setAllAccounts([...allAccounts.value, responseData])
}

const createAccount = async (formValues) => {
  setIsLoading(true)
  // eslint-disable-next-line no-new
  new CreationHandler('account', handleCreatedAccount, formValues)
}

const updatePhoneNumberRefs = (values: unknown) => {
  phoneNumberRef.value = values[0]
  isValidPhoneNumberRef.value = values[1]
  renderWithErrors.value = !isValidPhoneNumberRef.value
}

</script>

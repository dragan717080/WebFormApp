<template>
  <label for="phone" class="block text-sm bold leading-6 text-gray-900">Phone number</label>
  <div class="">
    <vue-tel-input
      ref="phoneElementRef"
      styleClasses="shadow-none focus:ring-2"
      :inputOptions="inputClasses"
      @validate="validatePhone"
      v-model="phoneNumberRef"
      mode="international"
    />
    <div v-if="hasPhoneError">
      <div class="error">Please enter valid phone number.</div>
    </div> 
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { VueTelInput } from 'vue3-tel-input'

import 'vue3-tel-input/dist/vue3-tel-input.css'

const props = defineProps({
  updateRefs: {
    type: Function,
    required: true,
  },
  renderWithErrors: {
    type: Boolean,
    required: true,
  }
})

const phoneElementRef = ref<HTMLDivElement|null>(null)
const phoneNumberRef = ref<string>('')
/**
 * 
 * Possible valid values:
 *  1. True
 *  2. False
 *  3. Undefined (Empty string)
 */
const isValidPhoneNumberRef = ref<boolean|undefined>(undefined)
const isInitialRef = ref<boolean>(true)

/**
 * 
 * Cover a very specific corner case: It had short input,
 * then was submitted and rendered with error, but in the meantime error was removed
 */
const toRenderWithErrors = ref<boolean>(props.renderWithErrors)

const hasPhoneError = computed(() => toRenderWithErrors.value || (phoneNumberRef.value?.length > 6 && !isValidPhoneNumberRef.value))

watch(() => props.renderWithErrors, (value: boolean) => {
  toRenderWithErrors.value = value
})

interface InputClasses {
  styleClasses: string
}

const inputClasses = ref<InputClasses>({
  styleClasses: "tel tel-valid rounded-md"
})

const validatePhone = ({ number, valid }) => {
  if (isInitialRef.value) {
    isInitialRef.value = false
    return
  }

  isValidPhoneNumberRef.value = valid;
  phoneNumberRef.value = number;
  if (phoneNumberRef.value?.length && isValidPhoneNumberRef.value) {
    toRenderWithErrors.value = false
  }
  props.updateRefs([phoneNumberRef.value, isValidPhoneNumberRef.value])
}

</script>

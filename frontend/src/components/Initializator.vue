<template>
  <div class='hidden'></div>
</template>

<script setup lang='ts'>
import AuthService from '@/auth'
import { useToast } from 'vue-toastification'
import { useRoute } from 'vue-router'
import { AxiosService, CookieUtils, TokenUtils } from '@/utils'
import { useIsLoadingStore, useAllAccountsStore } from '@/stores'

const { setIsLoading } = useIsLoadingStore()
const { setAllAccounts } = useAllAccountsStore()

const toast = useToast()

const route = useRoute()
/**
 * Better security and flexibility in managing access.
 * 
 * Laravel has its own route guard which needs bearer, and client needs both access token
 * for Laravel Passport and separate for Zoho API.
 * Depending of whether return type is 'object' (successful response) or 'string' (error)
 * 
 * 1. Store access token in a cookie and flash success message
 * 2. Flash error message with error string that was returned
 */
 const getClientAccessToken = async (): void => {
  const responseData = await AuthService.initializeApp()
  typeof(responseData) !== 'string' ? CookieUtils.storeAccessTokenCookieValue(responseData) : toast.error(responseData)
}

const getZohoAccessToken = async () => {
  setIsLoading(true)
  const baseUrl = `${import.meta.env.VITE_API_BASE_URL}/auth/access-token`
  const accessTokenData = await AxiosService.get(baseUrl, { code: route.query.code })

  if (typeof(accessTokenData) !== 'string') {
    setIsLoading(true)
    await TokenUtils.storeZohoAccessToken(accessTokenData.access_token, accessTokenData.refresh_token)
    // To do: fix
    setIsLoading(false)
  } else {
    toast.error("Couldn't get Zoho access token." + accessTokenData)
    setIsLoading(true)
  }
}

/**
 * Only once, get all records from Zoho CRM API.
 * 
 * On record creation all records will be updated via Pinia store
 */
const getAllRecords = async() => {
  setIsLoading(true)
  const dealsBaseUrl = `${import.meta.env.VITE_API_BASE_URL}/deals`
  const accessToken = CookieUtils.getValueForCookieName('tokenData').access_token
  const allDealsData = await AxiosService.get(dealsBaseUrl, {}, { Authorization: `Bearer ${accessToken}` })
  if (Object.keys(allDealsData).includes('0')) {
    getClientAccessToken()
    getAllRecords()
  }
  const accountsBaseUrl = `${import.meta.env.VITE_API_BASE_URL}/accounts`
  const allAccountsData = await AxiosService.get(accountsBaseUrl, {}, { Authorization: `Bearer ${accessToken}` })
  if (typeof(allAccountsData) !== 'string') {
    setAllAccounts(allAccountsData.data)
  }
}

// Didn't come here after Zoho auth redirect
const withoutRedirect = !Object.keys(route.query).length

if (withoutRedirect) {
  // To do: uncomment
  getClientAccessToken()
  setIsLoading(true)
  TokenUtils.getAuthorizationToken()
} else {
  getZohoAccessToken()
  getAllRecords()
}
</script>

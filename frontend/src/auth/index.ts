import axios, { AxiosError, AxiosPromise, AxiosResponse } from 'axios'
import AxiosService from '@/utils/AxiosService'
import CookieUtils from '@/utils/CookieUtils'

abstract class AuthService {

  /**
   * 
   * @returns {AxiosPromise} Promise that if resolved returns bearer token
   * for the frontend to use later to access Passport Laravel Routes (not access token
   * for the Zoho App).
   */
  static initializeApp(): AxiosPromise {
    const initClientUrl = `${import.meta.env.VITE_API_BASE_URL}/oauth/token`
    const clientCredentials = {
      'grant_type': 'client_credentials',
      'client_id': import.meta.env.VITE_CLIENT_ID,
      'client_secret': import.meta.env.VITE_CLIENT_SECRET
    }

    return AxiosService.create(
      initClientUrl,
      clientCredentials
    )
  }
}

export default AuthService

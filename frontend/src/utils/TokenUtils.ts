import AxiosService from "./AxiosService"
import { useToast } from "vue-toastification"

const toast = useToast()

abstract class TokenUtils {

  static getAuthorizationToken = async (): void => {
    const responseData = await AxiosService.get(`${import.meta.env.VITE_API_BASE_URL}/auth`)

    if (typeof (responseData) !== 'string') {
      window.location.href = responseData.message
    } else {
      toast.error("Couldn't get authorization token")
    }
  }

  /**
   * Stores Zoho access and refresh tokens to the backend.
   * 
   * Returns
   *  1. {boolean} True if succeeded in storing token,
   * then Pinia will update isLoading on the client
   *  2. {void} Failed to store token.
   */
  static storeZohoAccessToken = async (
    accessToken: string,
    refreshToken: string
  ): boolean | void => {
    const tokens = { accessToken, refreshToken, clientId: import.meta.env.VITE_CLIENT_ID }
    const storeZohoAccessTokenUrl = `${import.meta.env.VITE_API_BASE_URL}/auth/access-token`
    try {
      const responseData = await AxiosService.create(storeZohoAccessTokenUrl, tokens)

      /**
       * Only flash error messages since client doesn't care about app initialization
       */
      if (typeof (responseData) !== 'string') {
        toast.error('Creating Zoho Access Tokens failed')
      } else {
        return true
      }
    } catch (err) {
      console.error(err)
    }
  }
}

export default TokenUtils

// To do: d
import CookieUtils from './CookieUtils'
import AxiosService from './AxiosService'
import { useToast } from 'vue-toastification'
import AuthService from '@/auth'

const toast = useToast()

/**
 * Class to handle creating model by calling backend and displaying
 * toast/log notifications on success/error.
 */
class CreationHandler {
  /**
   * 
   * @param {string} name Name of model which is in lowercase.
   * @param {Function} handleCreated Callback function to call
   * on successful creation.
   * @param {FormValues} formValues
   */
  constructor(
    name: string,
    handleCreated: () => void,
    formValues: FormValues
  ) {
    this.name = name
    this.handleCreated = handleCreated
    this.formValues = formValues
    this.token = this.getAccessToken()
    this.create()
  }

  /**
   * @return {string} token Access Token to access backend
   * (not the access token which is stored in backend and used
   * to access the Zoho API)
   */
  getAccessToken = (): string => {
    const authTokenData = CookieUtils.getValueForCookieName('tokenData')
    return authTokenData.access_token
  }

  create = async () => {
    const responseData = await this.createRequest(this.formValues)
    this.handleResponse(responseData)
  }

  createRequest = async (): unknown => {
    const url = `${import.meta.env.VITE_API_BASE_URL}/${this.name}s`
    return await AxiosService.create(url, this.formValues, { Authorization: `Bearer ${this.token}` })
  }

  handleResponse = async (responseData: unknown) => {
    if (typeof (responseData) !== 'string') {
      /**
       * Client needs to refresh the access token
       * to access the backend (not access token for Zoho API) 
       */
      Object.keys(responseData).includes('0')
        ? await this.refreshTokens(responseData)
        : this.handleCreated(responseData)
    } else {
      toast.error(responseData)
    }
  }

  refreshTokens = async (responseData: unknown) => {
    const newAccessTokenData = await AuthService.initializeApp();
    if (typeof (newAccessTokenData) !== 'string') {
      if (
        Object.keys(responseData).includes('grant_type') ||
        Object.keys(responseData).includes('0')
      ) {
        CookieUtils.storeAccessTokenCookieValue(newAccessTokenData);
        this.token = newAccessTokenData.access_token
        // Reinitialize class with new access token
        return new CreationHandler(
          this.name,
          this.handleCreated,
          this.formValues
        );
      }
    } else {
      console.error(responseData);
      toast.error(responseData.message ?? responseData);
    }
  }
}

export default CreationHandler

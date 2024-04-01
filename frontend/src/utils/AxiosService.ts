import axios from 'axios'
import CookieUtils from './CookieUtils'

abstract class AxiosService {

  /**
   * Auth routes are the only ones not protected
   * by Laravel Passport since they are used to get client access token.
   */
  static getHeaders = (url: string, headers: unknown) => {
    if (!['auth', 'oauth'].includes(url.split('/')[3])) {
      headers.Authorization = `Bearer ${CookieUtils.getValueForCookieName('tokenData')}`
    }
    return headers
  }

  static get = (url, params?, headers?) =>
    axios.get(
      `${url}?${new URLSearchParams(params).toString()}`,
      { headers: this.getHeaders(url, headers) }
    ).then((response: AxiosResponse) => response.data)
      .catch((err: AxiosError) => {
        /**
         * If the server was unable to resolve URL it will return 'message' and will not get to 'data'
         */
        let errorMessage = "Couldn't get access token, invalid URL"
        if (err?.response?.data) {
          errorMessage = typeof (err.response.data) === 'string' ? err.response.data : err.response.data.message;
        }
        return errorMessage
      })

  static create = (url, body, headers?) =>
    axios.post(
      url,
      body,
      { headers }
    ).then((response: AxiosResponse) => response.data)
      .catch((err: AxiosError) => {
        let errorMessage = "Couldn't get access token, invalid URL"
        if (err?.response?.data) {
          errorMessage = typeof (err.response.data) === 'string' ? err.response.data : err.response.data.message;
        }
        return errorMessage
      })
}

export default AxiosService

abstract class CookieUtils {
  /**
   * Sets cookie value for access token for Laravel API (not Zoho API token).
   */
  static storeAccessTokenCookieValue = (accessToken): void => {
    const serializedTokenData = JSON.stringify(accessToken);
    const tokenData = encodeURIComponent(serializedTokenData)
    const expires = new Date(Date.now() + (accessToken.expires_in * 1000)).toUTCString()
    document.cookie = `tokenData=${tokenData}; expires=${expires}; path=/`
  }

  /**
   * Gets value for a specific cookie key.
   * @param {string} allCookies document.cookie (is URL decoded).
   * @param {string} cookieName key to search.
   * 
   * @returns {unknown} cookieValue for given key (unknown since it's generic function).
   */
  static getValueForCookieName = (
    cookieName: string
  ): unknown => JSON.parse(
    decodeURIComponent(document.cookie)
      .split("; ").find((row) => row.startsWith(cookieName))?.split("=")[1]
  )
}

export default CookieUtils

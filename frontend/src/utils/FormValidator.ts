class FormValidator {
  errors: { [key: string]: string[] };

  constructor(errors: array) {
    /**
     * Maps functions in relation to form variant
     */
    Object.keys(errors).includes('phone')
      ? this.validateAccountForm(errors)
      : this.validateDealForm(errors)
  }

  validateAccountForm = (errors: array): void => {
    const { name, website } = errors

    this.errors = Object.fromEntries(Object.keys(errors).map(error => [error, []]))

    this.validateStringField('name', name)
    this.validateWebsite(website)

    this.allFieldsAreOk = Object.values(this.errors).reduce((acc, x) => acc && !x.length, true)
  }

  validateDealForm = (errors: array): void => {
    const { name, stage, selected } = errors

    this.errors = Object.fromEntries(Object.keys(errors).map(error => [error, []]))

    this.validateStringField('name', name)
    this.validateStringField('stage', stage)
    this.validateSelected(selected)

    this.allFieldsAreOk = Object.values(this.errors).reduce((acc, x) => acc && !x.length, true)
  }

  /**
   * Validates length of string fields (name, username, website etc.)
   * 
   * @param {string} name
   * @param {string} value
   * 
   * @returns void
   */
  validateStringField = (name: string, value: string): void => {
    // Field name to be used in capitalized string e.g. 'Username'
    const fieldName = name.charAt(0).toUpperCase() + name.slice(1)
    if (value.length < 7) {
      this.errors[name].push(`${fieldName} is too short`)
    } else if (value.length > 20) {
      this.errors[name].push(`${fieldName} is too long`)
    }
  }

  validateWebsite = (websiteValue: string): void => {
    const websitePattern = /^https:\/\/.+$/
    if (!websitePattern.test(websiteValue)) {
      this.errors.website.push('Not a correct website')
    }
  }

  validateSelected = (selectedValue: string): void => {
    const relatedModule = Object.keys(this.errors).includes('phone')
      ? 'Deal'
      : 'Account'
    if (!selectedValue) {
      this.errors.selected.push(relatedModule + ' must not be empty')
    }
  }
}

export default FormValidator

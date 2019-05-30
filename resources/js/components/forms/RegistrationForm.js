export default {
  name: 'nzRegistrationForm',

  props: {
    initPasswordError: String,
  },

  data() {
    return {
      password: '',
      passwordWasOnceInvalid: !!this.initPasswordError,
      passwordIsInvalid: !!this.initPasswordError,
      passwordError: this.initPasswordError,
    }
  },

  computed: {
    shouldBeDisabled() {
      return this.passwordIsInvalid
    }
  },

  methods: {
    validatePassword() {
      return this.password.length < 8
    },

    checkPassword() {
      this.passwordIsInvalid = this.validatePassword()

      if (this.passwordIsInvalid) {
        this.passwordWasOnceInvalid = true
      }

      this.passwordError = this.passwordIsInvalid
        ? this.trans('validation.min.string', {
          attribute: this.trans('labels.register.password'),
          min: 8
        })
        : ''
    },

    checkIfFixedPassword() {
      if (this.passwordWasOnceInvalid) {
        this.checkPassword()
      }
    }
  }
}

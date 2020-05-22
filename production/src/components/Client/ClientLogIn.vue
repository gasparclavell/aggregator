<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Form for logging in as a client

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class="form-container">
    <p class="section-title">Client login</p>
    <form class="form-client" @submit.prevent="clientLogIn">
      <!-- Email textflied -->
      <p>Email</p>
      <div class="field-container">
        <div class="icon-container">
          <div
            v-show="
            $v.form.logInClientEmail.$error &&
              validationConditions('logInClientEmail')
          "
          >
            <i class="icon times fa fa-times"></i>
          </div>

          <input
            v-model.trim="$v.form.logInClientEmail.$model"
            :class="{
            'is-invalid':
              $v.form.logInClientEmail.$error &&
              validationConditions('logInClientEmail'),
            'is-valid': !$v.form.logInClientEmail.$invalid
          }"
            @blur="validationBlur('logInClientEmail')"
            @keypress="logInClientEmailKeypress = true"
            class="field"
            type="text"
            ref="email"
          />

          <span
            class="error-message"
            v-if="
            !$v.form.logInClientEmail.required &&
              validationConditions('logInClientEmail')
          "
          >Email is required.</span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInClientEmail.maxLength &&
              validationConditions('logInClientEmail')
          "
          >
            Email must have at most
            {{ $v.form.logInClientEmail.$params.maxLength.max }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInClientEmail.email &&
              validationConditions('logInClientEmail')
          "
          >Please, enter a valid email address.</span>
        </div>
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <!-- Password textfield -->
      <p>Password</p>
      <div class="field-container">
        <div class="icon-container">
          <div
            v-show="
            $v.form.logInClientPassword.$error &&
              validationConditions('logInClientPassword')
          "
          >
            <i class="icon times fa fa-times"></i>
          </div>
          <input
            v-model.trim="$v.form.logInClientPassword.$model"
            :class="{
            'is-invalid':
              $v.form.logInClientPassword.$error &&
              validationConditions('logInClientPassword'),
            'is-valid': !$v.form.logInClientPassword.$invalid
          }"
            @blur="validationBlur('logInClientPassword')"
            @keypress="logInClientPasswordKeypress = true"
            class="field"
            type="password"
            ref="password"
          />
          <span
            class="error-message"
            v-if="
            !$v.form.logInClientPassword.required &&
              validationConditions('logInClientPassword')
          "
          >Password is required.</span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInClientPassword.minLength &&
              validationConditions('logInClientPassword')
          "
          >
            Password must have at least
            {{ $v.form.logInClientPassword.$params.minLength.min }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInClientPassword.maxLength &&
              validationConditions('logInClientPassword')
          "
          >
            Password must have at most
            {{ $v.form.logInClientPassword.$params.maxLength.max }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInClientPassword.noSpace &&
              $v.form.logInClientPassword.required &&
              validationConditions('logInClientPassword')
          "
          >White spaces are not allowed.</span>
        </div>
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <!-- Button for sending the information of the form -->
      <div class="button-container">
        <button class="form-button">Log in</button>
      </div>
    </form>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
import {
  helpers,
  required,
  minLength,
  maxLength
} from "vuelidate/lib/validators";
import axios from "axios";

// Email validation
const email = helpers.regex(
  "email",
  /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
);

// No whitespace validation
const noSpace = helpers.withParams(
  { type: "noSpace" },
  value => value.indexOf(" ") < 0 && value != ""
);

export default {
  props: ["server"],
  data() {
    return {
      form: {
        logInClientEmail: "",
        logInClientPassword: ""
      },
      // Validation variables
      logInClientEmailFirstFocus: false,
      logInClientEmailKeypress: false,
      logInClientPasswordFirstFocus: false,
      logInClientPasswordKeypress: false
    };
  },
  validations: {
    form: {
      logInClientEmail: {
        required,
        maxLength: maxLength(60),
        email
      },
      logInClientPassword: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(20),
        noSpace
      }
    }
  },
  methods: {
    validationBlur(inputName) {
      if (this[inputName.concat("Keypress")]) {
        this[inputName.concat("FirstFocus")] = true;
      }
    },
    validationConditions(inputName) {
      if (
        this[inputName.concat("FirstFocus")] &&
        this[inputName.concat("Keypress")]
      ) {
        return true;
      } else {
        return false;
      }
    },
    clientLogIn() {
      (this.logInClientEmailFirstFocus = true),
        (this.logInClientEmailKeypress = true),
        (this.logInClientPasswordFirstFocus = true),
        (this.logInClientPasswordKeypress = true);
      // We check if the form is valid
      this.$v.form.$touch();
      // If it is not
      if (this.$v.$invalid) {
        this.focusInput();
      }
      // If it is
      else {
        let _this = this;
        var optionAxios = {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        };
        axios
          // We check if the user exists in the database
          .post(
            this.server +
              "check_user.php?e=" +
              this.form.logInClientEmail +
              "&k=" +
              this.form.logInClientPassword,
            optionAxios
          )
          .then(function(response) {
            console.log("Checking client data in the database...");
            // If the user exists
            if (response.data == "1") {
              _this.$emit("update-ui");
              _this.$emit(
                "update-connectedClient",
                _this.form.logInClientEmail
              );
            }
            // If the user does not exist
            else {
              alert("Access denied");
              _this.focusInput();
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    // When the left panel is folded/unfolded, the appropiate field is focused
    focusInput() {
      if (
        this.form.logInClientEmail == "" ||
        this.$v.form.logInClientEmail.$error
      ) {
        this.$refs.email.focus();
      } else if (
        this.form.logInClientPassword == "" ||
        this.$v.form.logInClientPassword.$error
      ) {
        this.$refs.password.focus();
      } else {
        this.$refs.email.focus();
      }
    }
  },
  mounted: function() {
    this.focusInput();
    this.$emit("update-connectedClient", "");
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style lang="scss" scoped>
@import "../../assets/styles/forms.scss";
</style>

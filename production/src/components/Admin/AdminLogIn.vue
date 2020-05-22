<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Form for logging in as an admin

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class="form-container">
    <p class="section-title">Admin login</p>
    <form class="form-admin" @submit.prevent="adminLogIn">
      <!-- Email textfield -->
      <p>Email</p>
      <div class="field-container">
        <div class="icon-container">
          <div
            v-show="
            $v.form.logInAdminEmail.$error &&
              validationConditions('logInAdminEmail')
          "
          >
            <i class="icon times fa fa-times"></i>
          </div>

          <input
            v-model.trim="$v.form.logInAdminEmail.$model"
            :class="{
            'is-invalid':
              $v.form.logInAdminEmail.$error &&
              validationConditions('logInAdminEmail'),
            'is-valid': !$v.form.logInAdminEmail.$invalid
          }"
            @blur="validationBlur('logInAdminEmail')"
            @keypress="logInAdminEmailKeypress = true"
            class="field"
            type="text"
            ref="email"
          />

          <span
            class="error-message"
            v-if="
            !$v.form.logInAdminEmail.required &&
              validationConditions('logInAdminEmail')
          "
          >Email is required.</span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInAdminEmail.maxLength &&
              validationConditions('logInAdminEmail')
          "
          >
            Email must have at most
            {{ $v.form.logInAdminEmail.$params.maxLength.max }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInAdminEmail.email &&
              validationConditions('logInAdminEmail')
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
            $v.form.logInAdminPassword.$error &&
              validationConditions('logInAdminPassword')
          "
          >
            <i class="icon times fa fa-times"></i>
          </div>
          <input
            v-model.trim="$v.form.logInAdminPassword.$model"
            :class="{
            'is-invalid':
              $v.form.logInAdminPassword.$error &&
              validationConditions('logInAdminPassword'),
            'is-valid': !$v.form.logInAdminPassword.$invalid
          }"
            @blur="validationBlur('logInAdminPassword')"
            @keypress="logInAdminPasswordKeypress = true"
            class="field"
            type="password"
            ref="password"
          />
          <span
            class="error-message"
            v-if="
            !$v.form.logInAdminPassword.required &&
              validationConditions('logInAdminPassword')
          "
          >Password is required.</span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInAdminPassword.minLength &&
              validationConditions('logInAdminPassword')
          "
          >
            Password must have at least
            {{ $v.form.logInAdminPassword.$params.minLength.min }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInAdminPassword.maxLength &&
              validationConditions('logInAdminPassword')
          "
          >
            Password must have at most
            {{ $v.form.logInAdminPassword.$params.maxLength.max }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.logInAdminPassword.noSpace &&
              $v.form.logInAdminPassword.required &&
              validationConditions('logInAdminPassword')
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

const email = helpers.regex(
  "email",
  /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
);

const noSpace = helpers.withParams(
  { type: "noSpace" },
  value => value.indexOf(" ") < 0 && value != ""
);

export default {
  props: ["server"],
  data() {
    return {
      form: {
        logInAdminEmail: "",
        logInAdminPassword: ""
      },
      // Validation variables
      logInAdminEmailFirstFocus: false,
      logInAdminEmailKeypress: false,
      logInAdminPasswordFirstFocus: false,
      logInAdminPasswordKeypress: false
    };
  },
  validations: {
    form: {
      logInAdminEmail: {
        required,
        maxLength: maxLength(60),
        email
      },
      logInAdminPassword: {
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
    adminLogIn() {
      (this.logInAdminEmailFirstFocus = true),
        (this.logInAdminEmailKeypress = true),
        (this.logInAdminPasswordFirstFocus = true),
        (this.logInAdminPasswordKeypress = true);
      // We check if the form is valid
      this.$v.form.$touch();
      // If it is not
      if (this.$v.$invalid) {
        // Not valid
        this.focusInput();
        // If it is
      } else {
        // Valid
        let _this = this;
        var optionAxios = {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        };
        axios
          // We check if the admin exists in the database
          .post(
            _this.server +
              "check_admin.php?e=" +
              this.form.logInAdminEmail +
              "&k=" +
              this.form.logInAdminPassword,
            optionAxios
          )
          .then(function(response) {
            console.log("Checking admin data in the database...");
            // If the admin exists
            if (response.data == "1") {
              _this.$emit("update-ui");
              _this.$emit("update-connectedAdmin", _this.form.logInAdminEmail);
            }
            // If the adnin does not exists
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
        this.form.logInAdminEmail == "" ||
        this.$v.form.logInAdminEmail.$error
      ) {
        this.$refs.email.focus();
      } else if (
        this.form.logInAdminPassword == "" ||
        this.$v.form.logInAdminPassword.$error
      ) {
        this.$refs.password.focus();
      } else {
        this.$refs.email.focus();
      }
    }
  },
  mounted: function() {
    this.focusInput();
    this.$emit("update-connectedAdmin", "");
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style lang="scss" scoped>
@import "../../assets/styles/forms.scss";
</style>

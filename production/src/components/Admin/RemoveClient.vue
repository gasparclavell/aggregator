<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Form for removing an existing client

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class="form-container">
    <p class="section-title">Remove client</p>

    <form class="form-removeclient" @submit.prevent="removeClient">
      <!-- Email textfield -->
      <p>Email</p>
      <div class="field-container">
        <div class="icon-container">
          <div
            id="times_email"
            v-show="
            $v.form.removeEmail.$error && validationConditions('removeEmail')
          "
          >
            <i class="icon times fa fa-times"></i>
          </div>

          <input
            v-model.trim="$v.form.removeEmail.$model"
            :class="{
            'is-invalid':
              $v.form.removeEmail.$error && validationConditions('removeEmail'),
            'is-valid': !$v.form.removeEmail.$invalid
          }"
            @blur="validationBlur('removeEmail')"
            @keypress="removeEmailKeypress = true"
            class="field"
            type="text"
            ref="email"
          />

          <span
            class="error-message"
            v-if="
            !$v.form.removeEmail.required && validationConditions('removeEmail')
          "
          >Email is required.</span>
          <span
            class="error-message"
            v-if="
            !$v.form.removeEmail.maxLength &&
              validationConditions('removeEmail')
          "
          >
            Email must have at most
            {{ $v.form.removeEmail.$params.maxLength.max }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.removeEmail.email && validationConditions('removeEmail')
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
            id="times_key"
            v-show="
            $v.form.removePassword.$error &&
              validationConditions('removePassword')
          "
          >
            <i class="icon times fa fa-times"></i>
          </div>
          <input
            v-model.trim="$v.form.removePassword.$model"
            :class="{
            'is-invalid':
              $v.form.removePassword.$error &&
              validationConditions('removePassword'),
            'is-valid': !$v.form.removePassword.$invalid
          }"
            @blur="validationBlur('removePassword')"
            @keypress="removePasswordKeypress = true"
            class="field"
            type="password"
            ref="password"
          />
          <span
            class="error-message"
            v-if="
            !$v.form.removePassword.required &&
              validationConditions('removePassword')
          "
          >Password is required.</span>
          <span
            class="error-message"
            v-if="
            !$v.form.removePassword.minLength &&
              validationConditions('removePassword')
          "
          >
            Password must have at least
            {{ $v.form.removePassword.$params.minLength.min }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.removePassword.maxLength &&
              validationConditions('removePassword')
          "
          >
            Password must have at most
            {{ $v.form.removePassword.$params.maxLength.max }} letters.
          </span>
          <span
            class="error-message"
            v-if="
            !$v.form.removePassword.noSpace &&
              $v.form.removePassword.required &&
              validationConditions('removePassword')
          "
          >White spaces are not allowed.</span>
        </div>
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <!-- Button for sending the information of the form -->
      <div class="button-container">
        <button class="form-button">Delete account</button>
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
  props: ["connectedClient", "server"],
  data() {
    return {
      form: {
        removeEmail: "",
        removePassword: ""
      },
      // Validation variables
      removeEmailFirstFocus: false,
      removeEmailKeypress: false,
      removePasswordFirstFocus: false,
      removePasswordKeypress: false
    };
  },
  validations: {
    form: {
      removeEmail: {
        required,
        maxLength: maxLength(60),
        email
      },
      removePassword: {
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
    removeClient() {
      (this.removeEmailFirstFocus = true),
        (this.removeEmailKeypress = true),
        (this.removePasswordFirstFocus = true),
        (this.removePasswordKeypress = true);
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
        // We check if the user exists in the database
        axios
          .post(
            _this.server +
              "check_user.php?e=" +
              this.form.removeEmail +
              "&k=" +
              this.form.removePassword,
            optionAxios
          )
          .then(function(response) {
            console.log("Checking client data in the database...");
            // The projects of the removed client will deleted, so we ask for confirmation
            if (response.data == "1") {
              var r = confirm(
                "All projects created with this account will be removed, do you want to continue?"
              );
              if (r == true) {
                // We remove the user from the database
                axios
                  .post(
                    _this.server +
                      "remove_user.php?e=" +
                      _this.form.removeEmail +
                      "&k=" +
                      _this.form.removePassword,
                    optionAxios
                  )
                  .then(function() {
                    console.log("A client has been removed");

                    // If the removed client is the same that is connected
                    if (_this.form.removeEmail == _this.connectedClient) {
                      _this.$emit("update-removeConnectedClient");
                    }
                    // We remove his projects from the database
                    axios
                      .post(
                        _this.server +
                          "remove_user_projects.php?e=" +
                          _this.form.removeEmail,
                        optionAxios
                      )
                      .then(function() {
                        console.log("His projects have been removed");
                      })
                      .catch(function(error) {
                        console.log(error);
                      });

                    _this.form.removeEmail = "";
                    _this.form.removePassword = "";
                    (_this.removeEmailFirstFocus = false),
                      (_this.removeEmailKeypress = false),
                      (_this.removePasswordFirstFocus = false),
                      (_this.removePasswordKeypress = false);
                    alert("The user was successfully removed");
                    _this.focusInput();
                  })
                  .catch(function(error) {
                    console.log(error);
                  });
              } else {
                _this.focusInput();
              }
            } else {
              alert("Invalid credentials");
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
      if (this.form.removeEmail == "" || this.$v.form.removeEmail.$error) {
        this.$refs.email.focus();
      } else if (
        this.form.removePassword == "" ||
        this.$v.form.removePassword.$error
      ) {
        this.$refs.password.focus();
      } else {
        this.$refs.email.focus();
      }
    }
  },
  mounted: function() {
    this.focusInput();
    document.body.classList.remove("stop-transitions");
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style lang="scss" scoped>
@import "../../assets/styles/forms.scss";
</style>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Form for adding a new client

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class="form-container">
    <p class="section-title">New client</p>

    <form class="form-newclient" @submit.prevent="addClient">
      <!-- Load client photo -->
      <p>Photo</p>
      <div class="photo-container">
        <div class="photo" id="photo_view"></div>
        <input
          @change="handleFileChange($event)"
          type="file"
          name="photo_newclient"
          id="photo_newclient"
        />
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <div class="two-fields-container">
        <!-- Name textfield -->
        <div class="field-container two-fields-subcontainer">
          <p>
            Name
            <a>*</a>
          </p>
          <div class="icon-container">
            <div v-show="$v.form.newName.$error && validationConditions('newName')">
              <i class="icon times fa fa-times"></i>
            </div>

            <input
              v-model.trim="$v.form.newName.$model"
              :class="{
              'is-invalid':
                $v.form.newName.$error && validationConditions('newName'),
              'is-valid': !$v.form.newName.$invalid
            }"
              @blur="validationBlur('newName')"
              @keypress="newNameKeypress = true"
              class="field"
              type="text"
              ref="newName"
            />

            <span
              class="error-message"
              v-if="!$v.form.newName.required && validationConditions('newName')"
            >Name is required.</span>
            <span
              class="error-message"
              v-if="
              !$v.form.newName.minLength &&
                $v.form.newName.name &&
                validationConditions('newName')
            "
            >
              Name must have at least
              {{ $v.form.newName.$params.minLength.min }} letters.
            </span>
            <span
              class="error-message"
              v-if="
              !$v.form.newName.maxLength &&
                $v.form.newName.name &&
                validationConditions('newName')
            "
            >
              Name must have at most
              {{ $v.form.newName.$params.maxLength.max }} letters.
            </span>
            <span
              class="error-message"
              v-if="!$v.form.newName.name && validationConditions('newName')"
            >Please, enter a valid name.</span>
          </div>
        </div>

        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

        <!-- Surname textfield -->
        <div class="field-container two-fields-subcontainer">
          <p>Surname</p>
          <div class="icon-container">
            <div
              v-show="
              $v.form.newSurname.$error && validationConditions('newSurname')
            "
            >
              <i class="icon times fa fa-times"></i>
            </div>

            <input
              v-model.trim="$v.form.newSurname.$model"
              :class="{
              'is-invalid':
                $v.form.newSurname.$error && validationConditions('newSurname'),
              'is-valid': !$v.form.newSurname.$invalid
            }"
              @blur="validationBlur('newSurname')"
              @keypress="newSurnameKeypress = true"
              class="field"
              type="text"
              ref="newSurname"
            />
            <span
              class="error-message"
              v-if="
              !$v.form.newSurname.minLength &&
                $v.form.newSurname.name &&
                validationConditions('newSurname')
            "
            >
              Surname must have at least
              {{ $v.form.newSurname.$params.minLength.min }} letters.
            </span>
            <span
              class="error-message"
              v-if="
              !$v.form.newSurname.maxLength &&
                $v.form.newSurname.name &&
                validationConditions('newSurname')
            "
            >
              Surname must have at most
              {{ $v.form.newSurname.$params.maxLength.max }} letters.
            </span>
            <span
              class="error-message"
              v-if="
              !$v.form.newSurname.name && validationConditions('newSurname')
            "
            >Please, enter a valid surname.</span>
          </div>
        </div>
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <div class="two-fields-container">
        <!-- ID textfield -->
        <div class="field-container two-fields-subcontainer">
          <p>
            ID
            <a>*</a>
          </p>
          <div class="icon-container">
            <div v-show="$v.form.newId.$error && validationConditions('newId')">
              <i class="icon times fa fa-times"></i>
            </div>

            <input
              v-model.trim="$v.form.newId.$model"
              :class="{
              'is-invalid':
                $v.form.newId.$error && validationConditions('newId'),
              'is-valid': !$v.form.newId.$invalid
            }"
              @blur="validationBlur('newId')"
              @keypress="newIdKeypress = true"
              class="field"
              type="text"
              ref="newId"
            />
            <span
              class="error-message"
              v-if="!$v.form.newId.required && validationConditions('newId')"
            >ID is required.</span>
            <span
              class="error-message"
              v-if="!$v.form.newId.id && validationConditions('newId')"
            >ID must be composed of nine numbers.</span>
          </div>
        </div>

        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

        <!-- Birthdate box -->
        <div class="field-container two-fields-subcontainer">
          <p>Birthdate</p>
          <div class="icon-container">
            <div
              v-show="
              $v.form.newBirthdate.$error &&
                validationConditions('newBirthdate')
            "
            >
              <i class="icon times fa fa-times"></i>
            </div>

            <input
              v-model.trim="$v.form.newBirthdate.$model"
              :class="{
              'is-invalid':
                $v.form.newBirthdate.$error &&
                validationConditions('newBirthdate'),
              'is-valid': !$v.form.newBirthdate.$invalid
            }"
              @blur="validationBlur('newBirthdate')"
              @keypress="newBirthdateKeypress = true"
              class="field"
              id="newBirthdate"
              type="date"
              ref="newBirthdate"
            />
            <span
              class="error-message"
              v-if="
              (!$v.form.newBirthdate.maxDate ||
                !$v.form.newBirthdate.minDate) &&
                this.form.newBirthdate != '' &&
                validationConditions('newBirthdate')
            "
            >Please, introduce a valid date.</span>
          </div>
        </div>
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <!-- Email textfield -->
      <p>
        Email
        <a>*</a>
      </p>
      <div class="field-container">
        <div class="icon-container">
          <div v-show="$v.form.newEmail.$error && validationConditions('newEmail')">
            <i class="icon times fa fa-times"></i>
          </div>

          <input
            v-model.trim="$v.form.newEmail.$model"
            :class="{
            'is-invalid':
              $v.form.newEmail.$error && validationConditions('newEmail'),
            'is-valid': !$v.form.newEmail.$invalid
          }"
            @blur="validationBlur('newEmail')"
            @keypress="newEmailKeypress = true"
            class="field"
            type="text"
            ref="newEmail"
          />
          <span
            class="error-message"
            v-if="!$v.form.newEmail.required && validationConditions('newEmail')"
          >Email is required.</span>
          <span
            class="error-message"
            v-if="!$v.form.newEmail.maxLength && validationConditions('newEmail')"
          >
            Email must have at most
            {{ $v.form.newEmail.$params.maxLength.max }} letters.
          </span>
          <span
            class="error-message"
            v-if="!$v.form.newEmail.email && validationConditions('newEmail')"
          >Please, enter a valid email address.</span>
        </div>
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <!-- Password textfield -->
      <div class="two-fields-container">
        <div class="field-container two-fields-subcontainer">
          <p>
            Password
            <a>*</a>
          </p>
          <div class="icon-container">
            <div
              v-show="
              $v.form.newPassword.$error && validationConditions('newPassword')
            "
            >
              <i class="icon times fa fa-times"></i>
            </div>
            <input
              v-model.trim="$v.form.newPassword.$model"
              :class="{
              'is-invalid':
                $v.form.newPassword.$error &&
                validationConditions('newPassword'),
              'is-valid': !$v.form.newPassword.$invalid
            }"
              @blur="validationBlur('newPassword')"
              @keypress="newPasswordKeypress = true"
              class="field"
              type="password"
              ref="newPassword"
            />
            <span
              class="error-message"
              v-if="
              !$v.form.newPassword.required &&
                validationConditions('newPassword')
            "
            >Password is required.</span>
            <span
              class="error-message"
              v-if="
              !$v.form.newPassword.minLength &&
                validationConditions('newPassword')
            "
            >
              Password must have at least
              {{ $v.form.newPassword.$params.minLength.min }} characters.
            </span>
            <span
              class="error-message"
              v-if="
              !$v.form.newPassword.maxLength &&
                validationConditions('newPassword')
            "
            >
              Password must have at most
              {{ $v.form.newPassword.$params.maxLength.max }} characters.
            </span>
            <span
              class="error-message"
              v-if="
              !$v.form.newPassword.noSpace &&
                $v.form.newPassword.required &&
                validationConditions('newPassword')
            "
            >White spaces are not allowed.</span>
          </div>
        </div>

        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

        <!-- Confirm password textfield -->
        <div class="field-container two-fields-subcontainer">
          <p>
            Confirm Password
            <a>*</a>
          </p>
          <div class="icon-container">
            <div
              v-show="
              $v.form.newConfirmPassword.$error &&
                validationConditions('newConfirmPassword')
            "
            >
              <i class="icon times fa fa-times"></i>
            </div>
            <input
              v-model.trim="$v.form.newConfirmPassword.$model"
              :class="{
              'is-invalid':
                $v.form.newConfirmPassword.$error &&
                validationConditions('newConfirmPassword'),
              'is-valid': !$v.form.newConfirmPassword.$invalid
            }"
              @blur="validationBlur('newConfirmPassword')"
              @keypress="newConfirmPasswordKeypress = true"
              class="field"
              type="password"
              ref="newConfirmPassword"
            />
            <span
              class="error-message"
              v-if="
              !$v.form.newConfirmPassword.required &&
                validationConditions('newConfirmPassword')
            "
            >Password is required.</span>
            <span
              class="error-message"
              v-if="
              !$v.form.newConfirmPassword.minLength &&
                validationConditions('newConfirmPassword')
            "
            >
              Password must have at least
              {{ $v.form.newConfirmPassword.$params.minLength.min }} characters.
            </span>
            <span
              class="error-message"
              v-if="
              !$v.form.newConfirmPassword.maxLength &&
                validationConditions('newConfirmPassword')
            "
            >
              Password must have at most
              {{ $v.form.newConfirmPassword.$params.maxLength.max }} characters.
            </span>
            <span
              class="error-message"
              v-if="
              !$v.form.newConfirmPassword.noSpace &&
                $v.form.newConfirmPassword.required &&
                validationConditions('newConfirmPassword')
            "
            >White spaces are not allowed.</span>
            <span
              class="error-message"
              v-if="
              !$v.form.newConfirmPassword.sameAs &&
                $v.form.newConfirmPassword.required &&
                $v.form.newConfirmPassword.minLength &&
                $v.form.newConfirmPassword.noSpace &&
                validationConditions('newConfirmPassword')
            "
            >Please, enter the same value again.</span>
          </div>
        </div>
      </div>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <!-- Button for sending the information of the form -->
      <div class="button-container">
        <button class="form-button">Sing in</button>
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
  maxLength,
  sameAs
} from "vuelidate/lib/validators";
import axios from "axios";
import FormData from "form-data";

const email = helpers.regex(
  "email",
  /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
);
const name = helpers.regex("name", /^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/);
const id = helpers.regex("id", /^[0-9]{9}$/);

const maxDate = helpers.withParams(
  { type: "maxDate" },
  value => value < maxDateJS || value == ""
);
const minDate = helpers.withParams(
  { type: "minDate" },
  value => value > minDateJS || value == ""
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
        newName: "",
        newSurname: "",
        newId: "",
        newBirthdate: "",
        newEmail: "",
        newPassword: "",
        newConfirmPassword: ""
      },
      // Validation variables
      maxDate: maxDateJS,
      minDate: minDateJS,
      newNameFirstFocus: false,
      newNameKeypress: false,
      newSurnameFirstFocus: false,
      newSurnameKeypress: false,
      newIdFirstFocus: false,
      newIdKeypress: false,
      newBirthdateFirstFocus: false,
      newBirthdateKeypress: false,
      newEmailFirstFocus: false,
      newEmailKeypress: false,
      newPasswordFirstFocus: false,
      newPasswordKeypress: false,
      newConfirmPasswordFirstFocus: false,
      newConfirmPasswordKeypress: false,
      data: new FormData()
    };
  },
  validations() {
    return {
      form: {
        newName: {
          required,
          minLength: minLength(3),
          maxLength: maxLength(60),
          name
        },
        newSurname: {
          minLength: minLength(3),
          maxLength: maxLength(60),
          name
        },
        newId: {
          required,
          id
        },
        newBirthdate: {
          maxDate,
          minDate
        },
        newEmail: {
          required,
          maxLength: maxLength(60),
          email
        },
        newPassword: {
          required,
          minLength: minLength(3),
          maxLength: maxLength(60),
          noSpace
        },
        newConfirmPassword: {
          required,
          minLength: minLength(3),
          maxLength: maxLength(60),
          noSpace,
          sameAs: sameAs("newPassword")
        }
      }
    };
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
    // When the left panel is folded/unfolded, the appropiate field is focused
    focusInput() {
      if (this.form.newName == "" || this.$v.form.newName.$error) {
        this.$refs.newName.focus();
      } else if (this.form.newId == "" || this.$v.form.newId.$error) {
        this.$refs.newId.focus();
      } else if (this.form.newEmail == "" || this.$v.form.newEmail.$error) {
        this.$refs.newEmail.focus();
      } else if (
        this.form.newPassword == "" ||
        this.$v.form.newPassword.$error
      ) {
        this.$refs.newPassword.focus();
      } else if (
        this.form.newConfirmPassword == "" ||
        this.$v.form.newConfirmPassword.$error
      ) {
        this.$refs.newConfirmPassword.focus();
      } else {
        this.$refs.newName.focus();
      }
    },
    // Method for adding new clients
    addClient() {
      (this.newNameFirstFocus = true),
        (this.newNameKeypress = true),
        (this.newSurnameFirstFocus = true),
        (this.newSurnameKeypress = true),
        (this.newIdFirstFocus = true),
        (this.newIdKeypress = true),
        (this.newBirthdateFirstFocus = true),
        (this.newBirthdateKeypress = true),
        (this.newEmailFirstFocus = true),
        (this.newEmailKeypress = true),
        (this.newPasswordFirstFocus = true),
        (this.newPasswordKeypress = true),
        (this.newConfirmPasswordFirstFocus = true),
        (this.newConfirmPasswordKeypress = true);

      // We check if all required fields are filled
      this.$v.form.$touch();

      // If they are not
      if (this.$v.$invalid) {
        this.focusInput();
      }
      // If they are
      else {
        let usersArray = [];
        var availableId = true;
        var availableEmail = true;

        let _this = this;

        // We get the user from the database
        var optionAxios = {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        };
        axios
          // We get the clients from the database
          .post(_this.server + "get_users.php", optionAxios)
          .then(function(response) {
            console.log("Clients have been loaded from the database");
            usersArray = response.data.map(x => x);

            for (
              var i = 0;
              i < usersArray.length && availableId && availableEmail;
              i++
            ) {
              // We check if the ID is available
              if (usersArray[i][2] == _this.form.newId.toLowerCase()) {
                availableId = false;
              }
              // We check if the email is available
              if (usersArray[i][4] == _this.form.newEmail.toLowerCase()) {
                availableEmail = false;
              }
            }

            if (!availableId) {
              alert("The ID is already registered");
              _this.$refs.newId.focus();
            } else if (!availableEmail) {
              alert("The email is already registered");
              _this.$refs.newEmail.focus();
            }
            // If they are available
            else {
              // We add the new client to the database
              axios
                .post(
                  _this.server +
                    "add_user.php?n=" +
                    _this.form.newName.toLowerCase() +
                    "&s=" +
                    _this.form.newSurname.toLowerCase() +
                    "&i=" +
                    _this.form.newId.toLowerCase() +
                    "&b=" +
                    _this.form.newBirthdate.toLowerCase() +
                    "&e=" +
                    _this.form.newEmail.toLowerCase() +
                    "&p=" +
                    _this.form.newPassword.toLowerCase(),
                  optionAxios
                )
                .then(function() {
                  console.log("A new client has been added to the database");
                  // We upload the profile picture
                  axios
                    .post(
                      _this.server + "upload_photo.php?i=" + _this.form.newId,
                      _this.data,
                      {
                        headers: {
                          accept: "application/json",
                          "Accept-Language": "en-US,en;q=0.8",
                          "Content-Type": `multipart/form-data; boundary=${_this.data._boundary}`
                        }
                      }
                    )
                    .then(function() {
                      // Handle success
                      console.log("The profile photo has been updated");
                      document.getElementById("photo_newclient").value = "";
                      document.getElementById(
                        "photo_view"
                      ).style.backgroundImage = "";
                    })
                    .catch(error => {
                      // Handle error
                      console.log(error);
                    });

                  alert("New client successfully registered");
                  (_this.newNameFirstFocus = false),
                    (_this.newNameKeypress = false),
                    (_this.newSurnameFirstFocus = false),
                    (_this.newSurnameKeypress = false),
                    (_this.newIdFirstFocus = false),
                    (_this.newIdKeypress = false),
                    (_this.newBirthdateFirstFocus = false),
                    (_this.newBirthdateKeypress = false),
                    (_this.newEmailFirstFocus = false),
                    (_this.newEmailKeypress = false),
                    (_this.newPasswordFirstFocus = false),
                    (_this.newPasswordKeypress = false),
                    (_this.newConfirmPasswordFirstFocus = false),
                    (_this.newConfirmPasswordKeypress = false);
                  _this.form.newName = "";
                  _this.form.newSurname = "";
                  _this.form.newId = "";
                  _this.form.newBirthdate = "";
                  _this.form.newEmail = "";
                  _this.form.newPassword = "";
                  _this.form.newConfirmPassword = "";
                  _this.focusInput();
                })
                .catch(function(error) {
                  console.log(error);
                });
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    handleFileChange: function(evt) {
      let _this = this;
      var file_data = evt.target.files[0];
      _this.data.append("file", file_data);

      axios
        // We upload the user's selected profile photo to the server
        .post(_this.server + "upload_temp_photo.php", _this.data, {
          headers: {
            accept: "application/json",
            "Accept-Language": "en-US,en;q=0.8",
            "Content-Type": `multipart/form-data; boundary=${_this.data._boundary}`
          }
        })
        .then(function() {
          // Handle success
          console.log("Temporary profile picture has been updated");
          // The profile photo of the form is updated
          document.getElementById("photo_view").style.backgroundImage =
            'url("' + _this.server + 'assets/images/temp/temp.jpg")';
          _this.focusInput();
        })
        .catch(error => {
          // Handle error
          console.log(error);
        });
    }
  },
  mounted: function() {
    this.focusInput();
    document.getElementById("newBirthdate").max = this.maxDate;
    document.getElementById("newBirthdate").min = this.minDate;
    document.body.classList.remove("stop-transitions");
  }
};

var dateObj = new Date();
var month = dateObj.getUTCMonth() + 1; // Months from 1-12
if (month < 10) {
  month = "0" + month;
}

var day = dateObj.getUTCDate();
if (day < 10) {
  day = "0" + day;
}

var year = dateObj.getUTCFullYear();

var maxDateJS = year - 14 + "-" + month + "-" + day;
var minDateJS = year - 100 + "-" + month + "-" + day;
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style lang="scss" scoped>
@import "../../assets/styles/forms.scss";

.photo-container {
  height: 100px;
  position: relative;
}

.photo-cover {
  position: absolute;
  width: 100px;
  height: 100px;
  background-color: rgb(228, 230, 231);
  z-index: 200;
  text-align: center;
  padding: 0 5px 0 5px;
  margin-top: 13px;
  color: rgba(0, 0, 0, 0.9);
  display: none;
  font-size: 14px;
  line-height: 20px;
}

.photo {
  position: absolute;
  width: 100px;
  height: 100px;
  background-image: url("../../assets/images/default.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  z-index: 90;
}

#photo_newclient {
  position: absolute;
  border-style: none;
  z-index: 0;
  bottom: 0;
  left: 110px;
}
.form-newclient .times {
  opacity: 0;
}

.two-fields-container {
  margin: auto;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: left;
  align-items: top;
  width: 100%;
}

.two-fields-subcontainer {
  width: 50%;
}

.three-fields-subcontainer {
  width: 25%;
}
</style>

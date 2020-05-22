<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Chart with clients' information

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div id="view-admin">
    <!-- Header for placing clients' column titles -->
    <li class="header" ref="upperScroll" :class="[foldedMenu ? 'header-fix' : '']">
      <div class="row-margin"></div>
      <div class="row-container" :class="[foldedMenu ? 'row-container-fix' : '']">
        <div class="element-icon-container-back hide"></div>

        <!-- Clients' name and surname column -->
        <div class="user-column" v-on:click.prevent.stop="sortClientUsers(0)" ref="clientValue">
          Client
          <span
            :class="[
              sortUsersByN == 0 ? 'sort_on' : 'sort_off',
              sortReverse ? 'sort_reverse' : ''
            ]"
          >
            <i class="sort-icon fas fa-chevron-up"></i>
          </span>
        </div>

        <!-- Clients' ID column -->
        <div class="user-column" v-on:click.prevent.stop="sortClientUsers(2)" ref="idValue">
          Id
          <span
            :class="[
              sortUsersByN == 2 ? 'sort_on' : 'sort_off',
              sortReverse ? 'sort_reverse' : ''
            ]"
          >
            <i class="sort-icon fas fa-chevron-up"></i>
          </span>
        </div>

        <!-- Clients' birthdate column -->
        <div class="user-column" v-on:click.prevent.stop="sortClientUsers(3)" ref="birthdateValue">
          Birthdate
          <span
            :class="[
              sortUsersByN == 3 ? 'sort_on' : 'sort_off',
              sortReverse ? 'sort_reverse' : ''
            ]"
          >
            <i class="sort-icon fas fa-chevron-up"></i>
          </span>
        </div>

        <!-- Clients' email column -->
        <div
          class="user-column plus-width"
          v-on:click.prevent.stop="sortClientUsers(4)"
          ref="emailValue"
        >
          Email
          <span
            :class="[
              sortUsersByN == 4 ? 'sort_on' : 'sort_off',
              sortReverse ? 'sort_reverse' : ''
            ]"
          >
            <i class="sort-icon fas fa-chevron-up"></i>
          </span>
        </div>
        <div class="user-column min-width"></div>
      </div>
      <div class="element-state-container hide"></div>
      <div class="row-margin"></div>
    </li>

    <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

    <div
      class="rows-container"
      @scroll="handleScroll"
      ref="mainScroll"
      :class="[foldedMenu ? 'rows-container-fix' : '']"
    >
      <!-- Loop for creating clients' rows -->
      <li
        v-for="user of users"
        v-bind:key="user.email"
        class="user-row"
        :class="[foldedMenu ? 'fix-rows' : '']"
      >
        <div class="row-margin"></div>
        <div class="row-container" :class="[foldedMenu ? 'row-container-fix' : '']">
          <!-- Default profile picture -->
          <div class="element-icon-container-back"></div>
          <!-- Personal profile picture -->
          <img class="element-icon-container" :src="getImgUrl(user.id)" alt />
          <!-- Client's name and surname -->
          <div class="user-column user-name-column">{{ user.name }} {{ user.surname }}</div>
          <!-- Client's ID -->
          <div class="user-column">{{ user.id }}</div>
          <!-- Client's birthdate -->
          <div v-if="user.birthdate !== '0000-00-00'" class="user-column">{{ user.birthdate }}</div>
          <div v-else-if="user.birthdate" class="user-column">N/A</div>
          <!-- Client's email -->
          <div class="user-column plus-width">{{ user.email }}</div>
          <div class="user-column min-width"></div>
        </div>
        <div
          class="element-state-container"
          :class="[connectedClient == user.email ? 'green' : 'gray']"
        ></div>
        <div class="row-margin"></div>
      </li>
    </div>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
import axios from "axios";

let loadedClients = [];

export default {
  props: ["connectedClient", "foldedMenu", "server"],
  data() {
    return {
      users: [],
      sortUsers: "",
      sortUsersByN: "0",
      sortReverse: false
    };
  },
  methods: {
    loadUsers() {
      let _this = this;

      var optionAxios = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      };

      // We get the clients from the database
      axios
        .post(_this.server + "get_users.php", optionAxios)
        .then(function(response) {
          console.log("Clients have been loaded from the database");
          _this.users = [];
          loadedClients = response.data.map(x => x);
          switch (_this.sortUsersByN) {
            case "0":
              _this.$refs.clientValue.click();
              break;
            case "2":
              _this.$refs.idValue.click();
              break;
            case "3":
              _this.$refs.birthdateValue.click();
              break;
            case "4":
              _this.$refs.emailValue.click();
              break;
            default:
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    sortClientUsers(number) {
      let _this = this;
      let usersArray = [];

      _this.users = [];
      usersArray = loadedClients;

      // Sort by name and surname
      if (number == 0) {
        usersArray.sort(function(a, b) {
          return a[number] > b[number] || a[number + 1] > b[number + 1]
            ? 1
            : -1;
        });
      }
      // Sort by ID
      else if (number == 3) {
        usersArray.sort(function(a, b) {
          return a[number] > b[number] ||
            a[number - 3] > b[number - 3] ||
            a[number - 2] > b[number - 2]
            ? 1
            : -1;
        });
      }
      // Sort by anything else
      else {
        usersArray.sort(function(a, b) {
          return a[number] > b[number] ? 1 : -1;
        });
      }

      // If the previously recorded users array is equal to the new one and it is not reversed
      if (
        usersArray.toString() + number == _this.sortUsers.toString() &&
        !_this.sortReverse
      ) {
        _this.sortReverse = true;

        // Sort by name and surname reversed
        if (number == 0) {
          usersArray.sort(function(a, b) {
            return a[number] > b[number] || a[number + 1] > b[number + 1]
              ? -1
              : 1;
          });
        }
        // Sort by ID reversed
        else if (number == 3) {
          usersArray.sort(function(a, b) {
            return a[number] > b[number] ||
              a[number - 3] > b[number - 3] ||
              a[number - 2] > b[number - 2]
              ? -1
              : 1;
          });
        }
        // Sort by anything else reversed
        else {
          usersArray.sort(function(a, b) {
            return a[number] > b[number] ? -1 : 1;
          });
        }
      } else {
        _this.sortReverse = false;
      }

      // The array of users is updated
      for (var i = 0; i < usersArray.length; i++) {
        _this.users.push({
          name: usersArray[i][0],
          surname: usersArray[i][1],
          id: usersArray[i][2],
          birthdate: usersArray[i][3],
          email: usersArray[i][4],
          password: usersArray[i][5]
        });
      }
      _this.sortUsers = usersArray + number;
      _this.sortUsersByN = number;

      localStorage.setItem("sortSeeClientsBy", number);
      localStorage.setItem("SeeClientsFieldReverse", _this.sortReverse);
    },
    getImgUrl: function(pic) {
      let _this = this;
      var url = _this.server + "assets/images/" + pic + ".jpg";
      return url;
    },
    handleScroll() {
      this.$refs.upperScroll.scrollLeft = this.$refs.mainScroll.scrollLeft;
    }
  },
  mounted: function() {
    if (localStorage.getItem("sortSeeClientsBy") != null) {
      this.sortUsersByN = localStorage.getItem("sortSeeClientsBy");
    }
    //console.log("1 - Mounted: " + this.sortUsersByN);
    this.loadUsers();

    document.body.classList.add("stop-transitions");
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style lang="scss" scoped>
@import "../../assets/styles/seeData.scss";
</style>

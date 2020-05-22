<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Main project file

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div id="app">
    <!-- Upper menu icon -->
    <div title="Hide panel" v-on:click.prevent.stop="menu" ref="menu">
      <div class="menu-icon" :class="[foldedMenu ? 'fix-icon' : '']">
        <div class="fa fa-bars"></div>
      </div>
    </div>

    <!-- Left panel -->
    <LeftPanel
      :class="[foldedMenu ? 'menu_shown' : '']"
      :selectedTab="selectedTab"
      :connectedAdmin="connectedAdmin"
      :dropdownActive="dropdownActive"
      @clickedTab="selectTab"
      @update-adminDropdown="newDropdownActive"
    />

    <!-- Client tab content -->
    <ClientTabContent
      v-if="selectedTab === '1'"
      :class="[foldedMenu ? 'fix-padding' : '']"
      :connectedClient="connectedClient"
      :foldedMenu="foldedMenu"
      :server="server"
      @update-connectedClient="newConnectedClient"
      ref="clientTabContent"
    />

    <!-- Admin tab content -->
    <AdminTabContent
      v-if="selectedTab !== '1'"
      :class="[foldedMenu ? 'fix-padding' : '']"
      :connectedClient="connectedClient"
      :connectedAdmin="connectedAdmin"
      :foldedMenu="foldedMenu"
      :server="server"
      :selectedTab="selectedTab"
      :dropdownActive="dropdownActive"
      @update-removeConnectedClient="removeConnectedClient"
      @update-connectedAdmin="newConnectedAdmin"
      @update-selectedTab="newSelectedTab"
      ref="adminTabContent"
    />
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
import LeftPanel from "./components/LeftPanel.vue";
import ClientTabContent from "./components/Client/ClientTabContent.vue";
import AdminTabContent from "./components/Admin/AdminTabContent.vue";
import axios from "axios";

// localStorage is cleaned
localStorage.clear();

// The title of the website is changed
document.title = "Aggregator";

export default {
  name: "App",
  data() {
    return {
      selectedTab: "1",
      connectedClient: "", // "name.one@solve-x.net"
      connectedAdmin: "", // "john.doe@solve-x.net"
      server: "http://testapplication.eu/api/",
      // "http://10.10.20.74:8080/api/" "http://localhost:8080/api" "https://aggregator2020.000webhostapp.com/api/" "http://testapplication.eu/api/"
      foldedMenu: false,
      dropdownActive: true
    };
  },
  components: {
    LeftPanel,
    ClientTabContent,
    AdminTabContent
  },
  methods: {
    selectTab(value) {
      this.selectedTab = value;
      console.log("Selected tab: " + this.selectedTab);
    },
    newConnectedClient(connectedClient) {
      this.connectedClient = connectedClient;
      console.log("New conected client: " + this.connectedClient);
    },
    newConnectedAdmin(connectedAdmin) {
      this.connectedAdmin = connectedAdmin;
      console.log("New conected admin: " + this.connectedAdmin);
    },
    newSelectedTab(selectedTab) {
      this.selectedTab = selectedTab;
    },
    removeConnectedClient() {
      console.log("Removed connected client: " + this.connectedClient);
      this.connectedClient = "";
    },
    menu() {
      // Transitions are activated
      document.body.classList.remove("stop-transitions");
      // We go from foldedMenu true to false and vice versa when we click on the menu icon
      this.foldedMenu = !this.foldedMenu;
      // We update the title of the menu icon
      if (this.foldedMenu) {
        this.$refs.menu.title = "Show panel";
      } else {
        this.$refs.menu.title = "Hide panel";
      }
      console.log("Folded menu: " + this.foldedMenu);
    },
    newDropdownActive() {
      this.dropdownActive = !this.dropdownActive;
      console.log("Admin dropdown active: " + this.dropdownActive);
    }
  },
  mounted: function() {
    let _this = this;
    // Everytime we run the app, the database is created
    var optionAxios = {
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      }
    };
    axios
      .post(_this.server + "create_database.php", optionAxios)
      .then(function() {
        console.log("The database has been created");
      })
      .catch(function(error) {
        console.log(error);
      });
  },
  watch: {
    selectedTab: function(val) {
      if (this.$refs.adminTabContent != null) {
        this.$refs.adminTabContent.showTab(val);
      }
    },
    // When the left panel is folded/unfolded, the appropiate field is focused
    foldedMenu: function() {
      if (this.selectedTab == 3) {
        this.$refs.adminTabContent.$refs.addClient.focusInput();
      } else if (this.selectedTab == 4) {
        this.$refs.adminTabContent.$refs.removeClient.focusInput();
      } else if (this.selectedTab == 2 && this.connectedAdmin == "") {
        this.$refs.adminTabContent.$refs.adminLogIn.focusInput();
      } else if (this.selectedTab == 1 && this.connectedClient == "") {
        this.$refs.clientTabContent.$refs.clientLogIn.focusInput();
      }
    }
  }
};

// When we resize the browser, the transitions stop
(function() {
  const classes = document.body.classList;
  let timer = 0;
  window.addEventListener("resize", function() {
    if (timer) {
      clearTimeout(timer);
      timer = null;
    } else classes.add("stop-transitions");

    timer = setTimeout(() => {
      classes.remove("stop-transitions");
      timer = null;
    }, 100);
  });
})();
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style>
/* Applied to everything */
* {
  margin: 0;
  padding: 0;
  overflow: hidden;
  font-family: Helvetica;
  box-sizing: border-box;
}

body.stop-transitions * {
  transition: none !important;
}

/* Texts */
p {
  margin-top: 20px;
  margin-bottom: 12px;
  user-select: none;
}
/* Required textfields */
a {
  color: rgba(95, 106, 251);
}

button,
span {
  user-select: none;
}

/* Move back and log out buttons */
.return_button {
  position: absolute;
  left: 38px;
  top: 8px;
  font-size: 22px;
  cursor: pointer;
  z-index: 100;
  transition: 0.24s;
}
#return_button_client:active,
#return_button_admin:active,
#export_projects:active,
#return_button:active,
#return_button_2:active,
#return_button_3:active,
#return_button_4:active,
#return_button_5:active {
  transform: scale(0.9);
}
/* Folded panel style */
.return_button-fix {
  left: 78px;
}

/* Log out buttons */
.right_button {
  right: 13px;
  left: unset;
}

/* Upper menu icon */
.menu-icon {
  position: fixed;
  color: white;
  opacity: 0.85;
  top: 9px;
  left: 235px;
  cursor: pointer;
  font-size: 20px;
  z-index: 200;
  transition: left 0.24s;
}
.menu-icon:hover {
  opacity: 1;
}
.menu-icon:active {
  transform: scale(0.95);
}

/* Folded panel style */
.menu_shown {
  transform: translateX(-270px);
}
.fix-icon {
  left: 38px;
  color: black;
}
.fix-padding {
  width: 100%;
  transform: translateX(0px);
}

/* Connected user email (upper right) */
.connected {
  position: absolute;
  font-size: 12px;
  right: 47px;
  top: 13px;
  opacity: 0.75;
  user-select: none;
}
</style>

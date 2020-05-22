<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Admin section container

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div>
    <!-- Upper bar of admin tab content -->
    <div
      class="bar"
      :class="[scrollIsAtTop ? '' : 'bar-shadow', foldedMenu ? 'bar-fix' : '']"
      v-if="!seeProjectsActive && !adminLogInActive || (adminLogInActive && foldedMenu)"
    >
      <!-- Log out button -->
      <div class="connected">{{ connectedAdmin }}</div>
      <span
        title="Log out"
        v-if="!adminLogInActive"
        v-on:click.prevent.stop="return_button_admin_menu"
      >
        <div class="return_button right_button" id="return_button_admin">
          <div class="fas fa-sign-out-alt"></div>
        </div>
      </span>

      <!-- Add Client move back button -->
      <span
        title="Move back"
        v-if="addClientActive && (!dropdownActive || foldedMenu)"
        v-on:click.prevent.stop="return_button_add_client"
      >
        <div
          class="return_button"
          :class="[foldedMenu ? 'return_button-fix' : '']"
          id="return_button_2"
        >
          <div class="fas fa-arrow-left"></div>
        </div>
      </span>

      <!-- Remove Client move back button -->
      <span
        title="Move back"
        v-if="removeClientActive && (!dropdownActive || foldedMenu)"
        v-on:click.prevent.stop="return_button_remove_client"
      >
        <div
          class="return_button"
          :class="[foldedMenu ? 'return_button-fix' : '']"
          id="return_button_3"
        >
          <div class="fas fa-arrow-left"></div>
        </div>
      </span>

      <!-- See Clients move back button -->
      <span
        title="Move back"
        v-if="seeClientsActive && (!dropdownActive || foldedMenu)"
        v-on:click.prevent.stop="return_button_see_clients"
      >
        <div
          class="return_button"
          :class="[foldedMenu ? 'return_button-fix' : '']"
          id="return_button_4"
        >
          <div class="fas fa-arrow-left"></div>
        </div>
      </span>
    </div>

    <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

    <!-- Admin tab content -->
    <div
      class="tabcontent"
      :class="[
        seeProjectsActive || seeClientsActive ? 'tabcontent-fix' : '',
        foldedMenu ? 'tabcontent-folded-fix' : '',
        addClientActive || removeClientActive || adminLogInActive ? 'darker-background' : ''
      ]"
      @scroll="handleScroll"
      ref="scroll"
    >
      <AdminLogIn
        v-if="adminLogInActive"
        :adminLogInActive="adminLogInActive"
        :adminMenuActive="adminMenuActive"
        :server="server"
        @update-ui="adminLogIn"
        @update-connectedAdmin="newConnectedAdmin"
        ref="adminLogIn"
      />
      <AdminMenu
        v-if="adminMenuActive"
        :adminMenuActive="adminMenuActive"
        @update-ui="adminMenu"
        @update-selectedTab="newSelectedTab"
      />
      <AddClient
        v-if="addClientActive"
        :addClientActive="addClientActive"
        :server="server"
        ref="addClient"
      />
      <RemoveClient
        v-if="removeClientActive"
        :removeClientActive="removeClientActive"
        :connectedClient="connectedClient"
        :server="server"
        @update-removeConnectedClient="removeConnectedClient"
        ref="removeClient"
      />
      <SeeClients
        v-if="seeClientsActive"
        :seeClientsActive="seeClientsActive"
        :connectedClient="connectedClient"
        :foldedMenu="foldedMenu"
        :server="server"
      />
      <SeeProjects
        v-if="seeProjectsActive"
        :seeProjectsActive="seeProjectsActive"
        :foldedMenu="foldedMenu"
        :server="server"
        @update-projects="updateProjects"
      />

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <!-- See Projects move back button -->
      <span
        v-if="seeProjectsActive && (!dropdownActive || foldedMenu)"
        title="Move back"
        v-on:click.prevent.stop="return_button_see_projects"
      >
        <div
          class="return_button"
          :class="[foldedMenu ? 'return_button-fix' : '']"
          id="return_button_5"
        >
          <div class="fas fa-arrow-left"></div>
        </div>
      </span>

      <div v-if="seeProjectsActive" class="header-padding-fix">
        <!-- Export to CSV button -->
        <span title="Export to CSV">
          <span v-on:click.prevent.stop="export_projects">
            <div class="return_button fas fa-file-excel" id="export_projects"></div>
          </span>
        </span>
        <!-- See Projects log out button -->
        <div class="connected">{{ connectedAdmin }}</div>
        <span
          title="Log out"
          v-if="!adminLogInActive"
          v-on:click.prevent.stop="return_button_admin_menu"
        >
          <div class="return_button right_button" id="return_button_admin">
            <div class="fas fa-sign-out-alt"></div>
          </div>
        </span>
      </div>
    </div>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
import AdminMenu from "./AdminMenu.vue";
import AdminLogIn from "./AdminLogIn.vue";
import AddClient from "./AddClient.vue";
import RemoveClient from "./RemoveClient.vue";
import SeeClients from "./SeeClients.vue";
import SeeProjects from "./SeeProjects.vue";

export default {
  props: [
    "connectedClient",
    "foldedMenu",
    "connectedAdmin",
    "server",
    "selectedTab",
    "dropdownActive"
  ],
  data() {
    return {
      adminLogInActive: false,
      adminMenuActive: true,
      addClientActive: false,
      removeClientActive: false,
      seeClientsActive: false,
      seeProjectsActive: false,
      projects: [],
      scrollIsAtTop: true
    };
  },
  components: {
    AdminMenu,
    AdminLogIn,
    AddClient,
    RemoveClient,
    SeeClients,
    SeeProjects
  },
  methods: {
    return_button_admin_menu() {
      let _this = this;
      let r = confirm("Log out?");

      if (r) {
        (this.adminMenuActive = false),
          (this.addClientActive = false),
          (this.removeClientActive = false),
          (this.seeClientsActive = false),
          (this.seeProjectsActive = false),
          (this.adminLogInActive = true);
        setTimeout(function() {
          _this.$emit("update-selectedTab", "2");
        }, 1);
      }
    },
    return_button_add_client() {
      this.adminMenuActive = true;
      this.addClientActive = false;
      this.$emit("update-selectedTab", "2");
    },
    return_button_remove_client() {
      this.adminMenuActive = true;
      this.removeClientActive = false;
      this.$emit("update-selectedTab", "2");
    },
    return_button_see_clients() {
      this.adminMenuActive = true;
      this.seeClientsActive = false;
      document.body.classList.add("stop-transitions");
      this.$emit("update-selectedTab", "2");
    },
    return_button_see_projects() {
      this.adminMenuActive = true;
      this.seeProjectsActive = false;
      document.body.classList.add("stop-transitions");
      this.$emit("update-selectedTab", "2");
    },
    adminLogIn() {
      this.adminLogInActive = false;
      this.adminMenuActive = true;
    },
    // Method for selecting the admin section that you want
    adminMenu(option) {
      this.adminMenuActive = false;
      if (option == "1") {
        this.addClientActive = true;
      } else if (option == "2") {
        this.removeClientActive = true;
      } else if (option == "3") {
        this.seeClientsActive = true;
      } else if (option == "4") {
        this.seeProjectsActive = true;
      }
    },
    // Method for removing a client who is connected
    removeConnectedClient() {
      this.$emit("update-removeConnectedClient");
    },
    export_projects: function() {
      let _this = this;

      var names = _this.projects.map(function(item) {
        return [item.name, item.owner, item.created, item.severity];
      });

      exportToCsv("projects.csv", names);
    },
    updateProjects(projects) {
      this.projects = projects;
    },
    newConnectedAdmin(connectedAdmin) {
      this.$emit("update-connectedAdmin", connectedAdmin);
    },
    newSelectedTab(selectedTab) {
      this.$emit("update-selectedTab", selectedTab);
    },
    handleScroll() {
      if (this.$refs.scroll.scrollTop != 0) {
        this.scrollIsAtTop = false;
      } else {
        this.scrollIsAtTop = true;
      }
    },
    showTab(val) {
      // We hide every section
      (this.adminMenuActive = false),
        (this.addClientActive = false),
        (this.removeClientActive = false),
        (this.seeClientsActive = false),
        (this.seeProjectsActive = false),
        (this.adminLogInActive = false);
      // And we show just the selected one
      if (val == 2) {
        if (this.connectedAdmin != "") {
          this.adminMenuActive = true;
        } else {
          this.adminLogInActive = true;
        }
      } else if (val == 3) {
        this.addClientActive = true;
      } else if (val == 4) {
        this.removeClientActive = true;
      } else if (val == 5) {
        this.seeClientsActive = true;
      } else if (val == 6) {
        this.seeProjectsActive = true;
      }
    }
  },
  mounted: function() {
    this.showTab(this.selectedTab);
  }
};

// Function for exporting a matrix to CSV
function exportToCsv(fName, rows) {
  var csv = "";
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    for (var j = 0; j < row.length; j++) {
      var val = row[j] === null ? "" : row[j].toString();
      val = val.replace(/\t/gi, " ");
      if (j > 0) csv += "\t";
      csv += val;
    }
    csv += "\n";
  }

  // For UTF-16
  var cCode,
    bArr = [];
  bArr.push(255, 254);
  for (var k = 0; k < csv.length; ++k) {
    cCode = csv.charCodeAt(k);
    bArr.push(cCode & 0xff);
    bArr.push((cCode / 256) >>> 0);
  }

  var blob = new Blob([new Uint8Array(bArr)], {
    type: "text/csv;charset=UTF-16LE;"
  });
  if (navigator.msSaveBlob) {
    navigator.msSaveBlob(blob, fName);
  } else {
    var link = document.createElement("a");
    if (link.download !== undefined) {
      var url = window.URL.createObjectURL(blob);
      link.setAttribute("href", url);
      link.setAttribute("download", fName);
      link.style.visibility = "hidden";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      window.URL.revokeObjectURL(url);
    }
  }
}
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style scoped>
@import "../../assets/styles/tabContent.scss";

.header-padding-fix {
  position: absolute;
  top: 0;
  right: 17px;
  width: 300px;
  height: 38px;
  transition: 0.24s;
  padding-right: 18px;
}

#export_projects {
  top: 8px;
  right: 197px;
  left: unset;
  opacity: 0.3;
}

#export_projects:hover {
  opacity: 1;
  color: rgba(95, 106, 251);
}
</style>

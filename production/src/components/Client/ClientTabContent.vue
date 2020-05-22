<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Client section container

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div>
    <!-- Upper bar of client tab content -->
    <div
      class="bar"
      :class="[scrollIsAtTop ? '' : 'bar-shadow', foldedMenu ? 'bar-fix' : '']"
      v-if="!clientLogInActive || (clientLogInActive && foldedMenu)"
    >
      <!-- Left div that adapts to folded/unfolded panel -->
      <div class="left-div" :class="[foldedMenu ? 'left-div-fix' : '']" v-if="clientProjectsActive">
        <!-- Select for specifying the sorting criterion -->
        <div class="order-text">Sort by:</div>
        <select
          @change="handleSelectProjectOrder($event)"
          id="project-order-select"
          v-model="selectedOrder"
        >
          <option selected="selected" value="0">Name</option>
          <option
            title="Severity levels:
      
    7 - Debug
    6 - Informational
    5 - Notice
    4 - Warning
    3 - Error
    2 - Critical
    1 - Alert
    0 - Emergency"
            value="1"
          >Severity level</option>
        </select>
      </div>

      <!-- Which client is connected -->
      <div class="connected">{{ connectedClient }}</div>

      <!-- Log out button -->
      <span
        title="Log out"
        v-if="clientProjectsActive"
        v-on:click.prevent.stop="return_button_client_projects"
      >
        <div class="return_button fas fa-sign-out-alt right_button" id="return_button_client"></div>
      </span>
    </div>

    <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

    <!-- Client tab content -->
    <div
      class="tabcontent"
      :class="[foldedMenu ? 'tabcontent-folded-fix' : '', clientLogInActive ? 'darker-background' : '']"
      @scroll="handleScroll"
      ref="scroll"
    >
      <!-- Client login -->
      <ClientLogIn
        v-if="clientLogInActive"
        :clientLogInActive="clientLogInActive"
        :server="server"
        @update-ui="clientLogIn"
        @update-connectedClient="newConnectedClient"
        ref="clientLogIn"
      />
      <!-- Client projects -->
      <ClientProjects
        v-if="clientProjectsActive"
        :connectedClient="connectedClient"
        :selectedOrder="selectedOrder"
        :clientProjectsActive="clientProjectsActive"
        :server="server"
        ref="clientProjects"
      />
    </div>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
import ClientLogIn from "./ClientLogIn.vue";
import ClientProjects from "./ClientProjects.vue";

export default {
  props: ["connectedClient", "foldedMenu", "server"],
  data() {
    return {
      clientLogInActive: false,
      clientProjectsActive: true,
      scrollIsAtTop: true,
      selectedOrder: 0
    };
  },
  methods: {
    clientLogIn() {
      this.clientLogInActive = false;
      this.clientProjectsActive = true;
    },
    return_button_client_projects() {
      let r = confirm("Log out?");
      if (r) {
        this.clientLogInActive = true;
        this.clientProjectsActive = false;
      }
    },
    newConnectedClient(connectedClient) {
      this.$emit("update-connectedClient", connectedClient);
    },
    handleScroll() {
      if (this.$refs.scroll.scrollTop != 0) {
        this.scrollIsAtTop = false;
      } else {
        this.scrollIsAtTop = true;
      }
    },
    handleSelectProjectOrder: function() {
      this.$refs.clientProjects.sortClientProjects(this.selectedOrder);
      localStorage.setItem("sortClientProjectsBy", this.selectedOrder);
    }
  },
  components: {
    ClientLogIn,
    ClientProjects
  },
  mounted: function() {
    if (this.connectedClient == "") {
      this.clientLogInActive = true;
      this.clientProjectsActive = false;
    }
    // We check if the client has specified a sorting criterion
    if (localStorage.getItem("sortClientProjectsBy") != null) {
      this.selectedOrder = localStorage.getItem("sortClientProjectsBy");
    }
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style scoped>
@import "../../assets/styles/tabContent.scss";

.left-div {
  left: 38px;
  position: relative;
  top: 10px;
  height: 20px;
  transition: 0.24s;
}
.order-text {
  font-size: 15px;
  top: 1px;
  position: relative;
  user-select: none;
}
#project-order-select {
  position: absolute;
  top: 0px;
  left: 60px;
}
.left-div-fix {
  left: 75px;
}
</style>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Left panel containing client and admin tabs

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class="tab">
    <p class="panel-title">Solve-X</p>

    <!-- First tab: client -->
    <button
      class="tablinks"
      v-on:click.prevent.stop="openTab1"
      :class="[selectedTab === '1' ? 'tab_selected' : '']"
    >
      <i class="icon-tab fa fa-user"></i>
      <span>Client</span>
    </button>

    <!-- Second tab: admin -->
    <button
      class="tablinks"
      v-on:click.prevent.stop="openTab2"
      v-on:dblclick="adminDropdown"
      :class="[selectedTab === '2' || (selectedTab !== '1' && !dropdownActive) ? 'tab_selected' : '']"
    >
      <i class="icon-tab fas fa-tools"></i>
      <span>Admin</span>
      <i
        class="dropdownCircle"
        v-if="connectedAdmin != ''"
        v-on:click.prevent.stop="adminDropdown"
        v-on:dblclick="adminDropdown"
        :class="[
              dropdownActive ? 'dropdownRotate' : ''
            ]"
      >
        <div class="dropdown fas fa-chevron-up"></div>
      </i>
    </button>

    <!-- Third tab: new client -->
    <button
      class="tablinks"
      v-if="connectedAdmin != '' && dropdownActive"
      v-on:click.prevent.stop="openTab2_1"
      :class="[selectedTab === '3' ? 'tab_selected' : '']"
    >
      <i class="icon-tab fas fa-plus sublink"></i>
      <span>New client</span>
    </button>

    <!-- Fourth tab: remove client-->
    <button
      class="tablinks"
      v-if="connectedAdmin != '' && dropdownActive"
      v-on:click.prevent.stop="openTab2_2"
      :class="[selectedTab === '4' ? 'tab_selected' : '']"
    >
      <i class="icon-tab fas fa-trash-alt sublink"></i>
      <span>Remove client</span>
    </button>

    <!-- Fifth tab: see clients -->
    <button
      class="tablinks"
      v-if="connectedAdmin != '' && dropdownActive"
      v-on:click.prevent.stop="openTab2_3"
      :class="[selectedTab === '5' ? 'tab_selected' : '']"
    >
      <i class="icon-tab fas fa-table sublink"></i>
      <span>See clients</span>
    </button>

    <!-- Sixth tab: see projects -->
    <button
      class="tablinks"
      v-if="connectedAdmin != '' && dropdownActive"
      v-on:click.prevent.stop="openTab2_4"
      :class="[selectedTab === '6' ? 'tab_selected' : '']"
    >
      <i class="icon-tab fas fa-table sublink"></i>
      <span>See projects</span>
    </button>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
export default {
  props: ["selectedTab", "foldedMenu", "connectedAdmin", "dropdownActive"],
  data() {
    return {};
  },
  methods: {
    openTab1: function() {
      this.$emit("clickedTab", "1");
    },
    openTab2: function() {
      this.$emit("clickedTab", "2");
      document.body.classList.add("stop-transitions");
    },
    openTab2_1: function() {
      this.$emit("clickedTab", "3");
      document.body.classList.add("stop-transitions");
    },
    openTab2_2: function() {
      this.$emit("clickedTab", "4");
      document.body.classList.add("stop-transitions");
    },
    openTab2_3: function() {
      this.$emit("clickedTab", "5");
    },
    openTab2_4: function() {
      this.$emit("clickedTab", "6");
    },
    adminDropdown() {
      this.$emit("update-adminDropdown", "");
    }
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style scoped>
.tab {
  position: absolute;
  float: left;
  overflow: hidden;
  background-color: rgba(26, 26, 26);
  width: 270px;
  min-width: 270px;
  height: 100%;
  z-index: 1;
  transition: 0.24s;
}

.tab span {
  margin-left: 10px;
}

.tab_selected {
  background-color: rgb(95, 106, 251) !important;
}

.tab_selected span,
.tab_selected .icon-tab,
.dropdown {
  color: white !important;
}

/* Admin dropdown icon */
.dropdown {
  opacity: 0.7;
  font-size: 12px;
  transform: translate(5.2px, -1px);
  color: rgba(255, 255, 255, 0.8);
}
.dropdownCircle {
  position: absolute;
  clip-path: circle(50% at 50% 50%);
  width: 21px;
  height: 21px;
  transform: translate(125px, 0px) rotate(180deg);
  cursor: pointer;
}
.dropdownCircle:hover {
  background-color: rgba(255, 255, 255, 0.075);
}
.dropdownRotate {
  transform: translate(125px, 0px);
}

/* Buttons inside every tab */
.tab button {
  width: 100%;
  background-color: rgba(26, 26, 26);
  float: left;
  border: none;
  outline: none;
  cursor: default;
  padding: 14px 16px;
  font-size: 17px;
  padding-left: 30px;
  padding-right: 30px;
  text-align: left;
}
.tab button:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Tab title and dropdown button */
.tab button span {
  color: rgba(255, 255, 255, 0.55);
  font-size: 16px;
}
.tab button:hover span,
.tab button:hover .icon-tab,
.tab button:hover .dropdown {
  color: white;
}

/* Tab icon */
.icon-tab {
  transform: translate(0, 0px);
  margin-right: 6px;
  color: rgba(255, 255, 255, 0.7);
}

/* Solve-X title */
.panel-title {
  color: white;
  font-size: 32px;
  margin-left: 30px;
  margin-bottom: 38px;
  margin-top: 42px;
  user-select: none;
}

.sublink {
  margin-left: 20px;
}
</style>

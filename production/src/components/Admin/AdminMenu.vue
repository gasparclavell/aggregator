<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Admin options

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class="options-admin">
    <!-- Add a new client -->
    <div class="option" v-on:click.prevent.stop="signInClient">
      <div class="icon_admin-option fas fa-plus"></div>
      <p class="text_admin-option">Create a new client account</p>
    </div>

    <!-- Remove an existing client -->
    <div class="option" v-on:click.prevent.stop="signOutClient">
      <div class="icon_admin-option far fa-trash-alt"></div>
      <p class="text_admin-option">Remove an existing client account</p>
    </div>

    <!-- See clients -->
    <div class="option" v-on:click.prevent.stop="seeClients">
      <div class="icon_admin-option fas fa-table"></div>
      <p class="text_admin-option">See clients</p>
    </div>

    <!-- See projects -->
    <div class="option" v-on:click.prevent.stop="seeProjects">
      <div class="icon_admin-option fas fa-table"></div>
      <p class="text_admin-option">See projects</p>
    </div>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
export default {
  props: ["adminMenuActive"],
  data() {
    return {};
  },
  methods: {
    signInClient() {
      this.$emit("update-ui", "1");
      this.$emit("update-selectedTab", "3");
    },
    signOutClient() {
      this.$emit("update-ui", "2");
      this.$emit("update-selectedTab", "4");
    },
    seeClients() {
      this.$emit("update-ui", "3");
      this.$emit("update-selectedTab", "5");
    },
    seeProjects() {
      this.$emit("update-ui", "4");
      this.$emit("update-selectedTab", "6");
    }
  },
  mounted: function() {
    setTimeout(function() {
      document.body.classList.remove("stop-transitions");
    }, 1);
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style scoped>
.options-admin {
  max-width: 600px;
  min-width: 400px;
  padding-right: 40px;
  width: 100%;
}
.option {
  width: 100%;
  height: 60px;
  border-radius: 4px;
  padding-left: 10px;
  padding-right: 10px;
  margin-top: 10px;
  background-color: rgba(0, 0, 0, 0.07);
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: left;
  align-items: flex-start;
  cursor: default;
  color: white;
}

.option:hover {
  background-color: rgba(95, 106, 251);
  color: white;
}

.option:hover .icon_admin-option,
.option:hover .text_admin-option {
  color: white !important;
}

.option:hover .icon_admin-option,
.option:hover .text_admin-option {
  color: white !important;
}

.icon_admin-option,
.icon_user-option {
  font-size: 32px;
  color: rgba(110, 110, 110);
}

.text_admin-option,
.text_user-option {
  color: rgba(30, 30, 30);
}

.icon_admin-option {
  transform: translate(13px, 13px);
}

.text_admin-option {
  margin-left: 35px;
}
</style>

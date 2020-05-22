<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Chart with information about clients' projects

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class>
    <!-- Select for choosing the creation time from which projects are going to be shown -->
    <select
      @change="handleSelectProjectTime($event)"
      id="project-time-select"
      v-model="selectedTime"
    >
      <option selected="selected" value="0">Anytime</option>
      <option value="1">Last 24 hours</option>
      <option value="2">Last hour</option>
    </select>

    <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

    <div id="view-admin">
      <!-- Header for placing the column titles of the projects -->
      <li class="header" ref="upperScroll" :class="[foldedMenu ? 'header-fix' : '']">
        <div class="row-margin"></div>
        <div class="row-container" :class="[foldedMenu ? 'row-container-fix' : '']">
          <div class="element-icon fas fa-file-code hide"></div>

          <!-- Column with the name of the project -->
          <div
            class="user-column project-column-fix"
            v-on:click.prevent.stop="sortProjects(0, false)"
          >
            Name
            <span
              :class="[
                sortProjectsByN == 0 ? 'sort_on' : 'sort_off',
                sortReverse ? 'sort_reverse' : ''
              ]"
            >
              <i class="sort-icon fas fa-chevron-up"></i>
            </span>
          </div>

          <!-- Column with the owner of the project -->
          <div class="user-column plus-width" v-on:click.prevent.stop="sortProjects(1, false)">
            Owner
            <span
              :class="[
                sortProjectsByN == 1 ? 'sort_on' : 'sort_off',
                sortReverse ? 'sort_reverse' : ''
              ]"
            >
              <i class="sort-icon fas fa-chevron-up"></i>
            </span>
          </div>

          <!-- Column with the creation date of the project -->
          <div class="user-column plus-width" v-on:click.prevent.stop="sortProjects(2, false)">
            Created
            <span
              :class="[
                sortProjectsByN == 2 ? 'sort_on' : 'sort_off',
                sortReverse ? 'sort_reverse' : ''
              ]"
            >
              <i class="sort-icon fas fa-chevron-up"></i>
            </span>
          </div>

          <!-- Column with the severity level of the project -->
          <div class="user-column" v-on:click.prevent.stop="sortProjects(3, false)">
            Severity
            <span
              :class="[
                sortProjectsByN == 3 ? 'sort_on' : 'sort_off',
                sortReverse ? 'sort_reverse' : ''
              ]"
            >
              <i class="sort-icon fas fa-chevron-up"></i>
            </span>
          </div>
        </div>

        <div class="row-margin extra-margin"></div>
      </li>

      <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

      <div
        class="rows-container"
        @scroll="handleScroll"
        ref="mainScroll"
        :class="[foldedMenu ? 'rows-container-fix' : '']"
      >
        <!-- Loop for creating the rows of the projects -->
        <li v-for="project of projects" v-bind:key="project.created" class="user-row">
          <div class="row-margin"></div>
          <div class="row-container" :class="[foldedMenu ? 'row-container-fix' : '']">
            <div class="element-icon element-icon_project fas fa-file-code"></div>
            <div class="user-column project-column-fix">{{ project.name }}</div>
            <div class="user-column plus-width">{{ project.owner }}</div>
            <div class="user-column plus-width">{{ project.created }}</div>
            <div class="user-column">{{ project.severity }} - {{ severityLevels[project.severity] }}</div>
          </div>
          <div class="row-margin"></div>
        </li>
      </div>
    </div>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
import axios from "axios";

let loadedProjects = [];

export default {
  props: ["seeProjectsActive", "foldedMenu", "server"],
  data() {
    return {
      projects: [],
      sortedProjects: "",
      sortProjectsByN: "1",
      newProjectName: "",
      severityLevels: [
        "emergency",
        "alert",
        "critical",
        "error",
        "warning",
        "notice",
        "informational",
        "debug"
      ],
      newProjectSeverity: "",
      sortReverse: false,
      selectedTime: "0",
      scrollx: 0
    };
  },
  methods: {
    loadProjects() {
      let _this = this;

      var optionAxios = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      };
      // We get the projects from the database
      axios
        .post(_this.server + "get_projects.php", optionAxios)
        .then(function(response) {
          console.log("Projects have been loaded from the database");
          // We store them in an array
          loadedProjects = response.data.map(x => x);
          _this.sortProjects(_this.sortProjectsByN, false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    sortProjects(number, dateChange) {
      let _this = this;
      this.projects = [];

      let todayTime = new Date();

      // Depending on the selected sorting time for the creation of the projects, we set a reference date
      if (this.selectedTime == "1") {
        todayTime.setDate(todayTime.getDate() - 1);
      } else if (this.selectedTime == "2") {
        todayTime.setHours(todayTime.getHours() - 1);
      } else {
        todayTime.setFullYear(todayTime.getFullYear() - 100);
      }
      let projectsArray = loadedProjects;

      // If the projects are sorted not due to a new selected creation time (anytime, last 24 hours, last hour)
      if (!dateChange) {
        // Sort by project name
        if (number == 0) {
          projectsArray.sort(function(a, b) {
            if (a[number] === b[number]) {
              // Name is only important when the other field is the same
              return a[2] > b[2] ? 1 : -1;
            }
            return a[number] > b[number] ? 1 : -1;
          });
        }
        // Sort by severity level
        else if (number == 3) {
          projectsArray.sort(function(a, b) {
            if (a[number] === b[number]) {
              // Owner is only important when the severity level is the same
              if (a[1] === b[1]) {
                // Name is only important when the owner is the same
                return a[0] > b[0] ? 1 : -1;
              }
              return a[1] > b[1] ? 1 : -1;
            }
            return a[number] > b[number] ? 1 : -1;
          });
        }
        // Sort by anything else
        else {
          projectsArray.sort(function(a, b) {
            if (a[number] === b[number]) {
              // Name is only important when the other field is the same
              return a[0] > b[0] ? 1 : -1;
            }
            return a[number] > b[number] ? 1 : -1;
          });
        }
      }

      // If the previously recorded projects array is equal to the new one and it is not reversed
      if (
        projectsArray.toString() + number == _this.sortedProjects.toString() &&
        !_this.sortReverse &&
        !dateChange
      ) {
        _this.sortReverse = true;

        // Sort by project name reversed
        if (number == 0) {
          projectsArray.sort(function(a, b) {
            if (a[number] === b[number]) {
              // Name is only important when the other field is the same
              return a[2] > b[2] ? 1 : -1;
            }
            return a[number] > b[number] ? -1 : 1;
          });
        }
        // Sort by severity level reversed
        else if (number == 3) {
          projectsArray.sort(function(a, b) {
            if (a[number] === b[number]) {
              // Owner is only important when the severity level is the same
              if (a[1] === b[1]) {
                // Name is only important when the owner is the same
                return a[0] > b[0] ? 1 : -1;
              }
              return a[1] > b[1] ? 1 : -1;
            }
            return a[number] > b[number] ? -1 : 1;
          });
        }
        // Sort by anything else reversed
        else {
          projectsArray.sort(function(a, b) {
            if (a[number] === b[number]) {
              // Name is only important when the other field is the same
              return a[0] > b[0] ? 1 : -1;
            }
            return a[number] > b[number] ? -1 : 1;
          });
        }
      } else {
        if (!dateChange) {
          _this.sortReverse = false;
        }
      }

      // The array of projects is updated
      for (var i = 0; i < projectsArray.length; i++) {
        var projectTime = new Date();
        projectTime = _this.sqlToJsDate(projectsArray[i][2]);

        if (projectTime > todayTime) {
          _this.projects.push({
            name: projectsArray[i][0],
            owner: projectsArray[i][1],
            created: projectsArray[i][2],
            severity: projectsArray[i][3]
          });
        }
      }

      _this.sortedProjects = projectsArray + number;
      _this.sortProjectsByN = number;

      localStorage.setItem("sortSeeProjectsBy", number);
      localStorage.setItem("seeProjectsFieldReverse", _this.sortReverse);
    },
    handleScroll() {
      this.$refs.upperScroll.scrollLeft = this.$refs.mainScroll.scrollLeft;
    },
    handleSelectProjectTime: function() {
      this.sortProjects(this.sortProjectsByN, true);
      localStorage.setItem("sortProjectCreationBy", this.selectedTime);
    },
    sqlToJsDate(sqlDate) {
      // sqlDate in SQL DATETIME format ("yyyy-mm-dd hh:mm:ss.ms")
      var sqlDateArr1 = sqlDate.split("-");
      // Format of sqlDateArr1[] = ['yyyy','mm','dd hh:mm:ms']
      var sYear = sqlDateArr1[0];
      var sMonth = (Number(sqlDateArr1[1]) - 1).toString();
      var sqlDateArr2 = sqlDateArr1[2].split(" ");
      // Format of sqlDateArr2[] = ['dd', 'hh:mm:ss.ms']
      var sDay = sqlDateArr2[0];
      var sqlDateArr3 = sqlDateArr2[1].split(":");
      // Format of sqlDateArr3[] = ['hh','mm','ss.ms']
      var sHour = sqlDateArr3[0];
      var sMinute = sqlDateArr3[1];
      var sqlDateArr4 = sqlDateArr3[2].split(".");
      // Format of sqlDateArr4[] = ['ss','ms']
      var sSecond = sqlDateArr4[0];
      var sMillisecond = "";

      return new Date(
        sYear,
        sMonth,
        sDay,
        sHour,
        sMinute,
        sSecond,
        sMillisecond
      );
    }
  },
  mounted: function() {
    if (localStorage.getItem("sortProjectCreationBy") != null) {
      this.selectedTime = localStorage.getItem("sortProjectCreationBy");
    }
    if (localStorage.getItem("sortSeeProjectsBy") != null) {
      this.sortProjectsByN = localStorage.getItem("sortSeeProjectsBy");
    }
    this.loadProjects();
    document.body.classList.add("stop-transitions");
  },
  watch: {
    projects: function() {
      this.$emit("update-projects", this.projects);
    }
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style lang="scss" scoped>
@import "../../assets/styles/seeData.scss";
</style>

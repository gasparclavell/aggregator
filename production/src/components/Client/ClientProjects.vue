<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Component where connected clients manage their projects.

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<template>
  <div class="projects" id="projects">
    <!-- Loop for loading client's projects -->
    <div v-for="project of projects" v-bind:key="project.name" class="project">
      <span
        title="Severity levels:
      
    7 - Debug
    6 - Informational
    5 - Notice
    4 - Warning
    3 - Error
    2 - Critical
    1 - Alert
    0 - Emergency"
      >
        <!-- Select for choosing the severity level of the project -->
        <select
          @change="handleSelectChange($event, project.owner, project.name)"
          v-model="project.severity"
          class="severity-level"
        >
          <option selected="selected" value="7" title="Debug">7</option>
          <option value="6" title="Informational">6</option>
          <option value="5" title="Notice">5</option>
          <option value="4" title="Warning">4</option>
          <option value="3" title="Error">3</option>
          <option value="2" title="Critical">2</option>
          <option value="1" title="Alert">1</option>
          <option value="0" title="Emergency">0</option>
        </select>
        <i class="select-icon fas fa-chevron-up"></i>
      </span>
      <div class="project_subcontainer">
        <!-- Color change depending on severity level -->
        <!-- :class="[project.severity == 0 ? 'severity_0' : '', project.severity == 6 ? 'severity_6' : '', project.severity == 3 ? 'severity_3' : '', project.severity == 2 ? 'severity_2' : '', project.severity == 4 ? 'severity_4' : '', project.severity == 5 ? 'severity_5' : '', project.severity == 1 ? 'severity_1' : '']" -->
        <div class="icon_user-option fas fa-file-code"></div>
        <p class="text_user-option">{{ project.name }}</p>
      </div>
      <!-- Button for removing the project -->
      <span title="Remove project">
        <div v-on:click.prevent.stop="removeProject(project.name)">
          <div class="remove-icon far fa-trash-alt"></div>
        </div>
      </span>
      <!-- Button for renaming the project -->
      <span title="Rename project">
        <div v-on:click.prevent.stop="renameProject(project)">
          <div class="icon-edit fas fa-edit"></div>
        </div>
      </span>

      <!-- Upper light of the projects -->
      <!-- <div
        class="project-light"
        :class="[project.severity == 0 ? 'severity_0' : '', project.severity == 6 ? 'severity_6' : '', project.severity == 3 ? 'severity_3' : '', project.severity == 2 ? 'severity_2' : '', project.severity == 4 ? 'severity_4' : '', project.severity == 5 ? 'severity_5' : '', project.severity == 1 ? 'severity_1' : '']"
        v-bind:title="
          'Severity level: ' + severityLevels[project.severity] + ''
        "
      ></div>-->
    </div>

    <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

    <!-- Button for adding new projects -->
    <div class="project" id="new" v-on:click.prevent.stop="addProject">
      <div class="project_subcontainer">
        <div class="icon_user-option fas fa-plus"></div>
        <p class="text_user-option">Add project</p>
      </div>
    </div>
  </div>
</template>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<script>
import axios from "axios";

let projectsArray = [];

export default {
  props: ["clientProjectsActive", "connectedClient", "selectedOrder", "server"],
  data() {
    return {
      projects: [],
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
      newProjectSeverity: ""
    };
  },
  methods: {
    sortClientProjects(selectedOrder) {
      this.projects = [];

      // If we sort the projects by their name
      if (selectedOrder == 0) {
        projectsArray.sort(function(a, b) {
          return a[0] > b[0] ? 1 : -1;
        });
      }
      // If we sort the projects by their severity level
      else {
        projectsArray.sort(function(a, b) {
          if (a[3] === b[3]) {
            // Name is only important when severity levels are the same
            return a[0] > b[0] ? 1 : -1;
          }
          return a[3] > b[3] ? 1 : -1;
        });
      }

      // The array of the projects is updated
      for (var i = 0; i < projectsArray.length; i++) {
        if (projectsArray[i][1] == this.connectedClient) {
          this.projects.push({
            name: projectsArray[i][0],
            owner: projectsArray[i][1],
            created: projectsArray[i][2],
            severity: projectsArray[i][3]
          });
        }
      }
    },
    loadClientProjects() {
      let _this = this;

      var optionAxios = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      };

      // We load the projects from the database
      axios
        .post(_this.server + "get_projects.php", optionAxios)
        .then(function(response) {
          console.log("Projects have been loaded from the database");

          //let dataArray = Array.from(response.data);
          //projectsArray = dataArray.map(x => x);
          projectsArray = response.data.map(x => x);
          _this.sortClientProjects(_this.selectedOrder);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    addProject() {
      let _this = this;
      let nextProjectNumber = 1;
      let validInput = false;
      let projectsArray = [];

      var optionAxios = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      };
      axios
        // We get the projects from the database
        .post(_this.server + "get_projects.php", optionAxios)
        .then(function(response) {
          console.log("Projects have been loaded from the database");
          projectsArray = response.data.map(x => x);

          var match = false;
          var numberFound = false;

          // We check an appropiate name (it must not match any of the projects from the database that are owned by the connected client)
          for (var i = 1; i < 1000 && !numberFound; i++) {
            match = false;

            for (var j = 0; j < projectsArray.length && !match; j++) {
              if (
                projectsArray[j][0] == "Project " + i &&
                projectsArray[j][1] == _this.connectedClient
              ) {
                match = true;
              }

              if (j == projectsArray.length - 1 && !match) {
                numberFound = true;
                nextProjectNumber = i;
              }
            }
          }

          // While there is not a valid input for the project name, we keep asking for one
          while (!validInput) {
            // We show the automatically generated name as well as we ask the user to introduce whatever name he wants for his new project
            var dialog = prompt(
              "Please, name your project",
              "Project " + nextProjectNumber
            );

            var existingName = false;

            // If the project name has at least one character
            if (dialog != null) {
              // We check if the name is available
              for (var k = 0; k < projectsArray.length && !existingName; k++) {
                if (
                  projectsArray[k][0].toLowerCase() == dialog.toLowerCase() &&
                  projectsArray[k][1] == _this.connectedClient
                ) {
                  existingName = true;
                }
              }

              // If it is
              if (!existingName) {
                // We check a few restrictions
                if (dialog.length > 20) {
                  alert("The name cannot exceed 20 characters");
                } else if (dialog.length < 3) {
                  alert("The name must have at least 3 characters");
                }
                // If everything is okay
                else {
                  validInput = true;

                  if (dialog) {
                    _this.newProjectName = dialog;

                    // Quick fix to get the proper time
                    var newDate = new Date();
                    newDate.setHours(newDate.getHours() + 2);

                    _this.currentDate = newDate
                      .toISOString()
                      .slice(0, 19)
                      .replace("T", " ");

                    // The project is added to the database
                    axios
                      .post(
                        _this.server +
                          "add_project.php?n=" +
                          _this.newProjectName +
                          "&e=" +
                          _this.connectedClient +
                          "&c=" +
                          _this.currentDate +
                          "&s=" +
                          _this.newProjectSeverity,
                        optionAxios
                      )
                      .then(function() {
                        console.log("A new project has been added");
                        _this.loadClientProjects();
                      })
                      .catch(function(error) {
                        console.log(error);
                      });
                  }
                }
              } else {
                alert("The name already exists");
              }
            } else {
              validInput = true;
            }
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    removeProject: function(name) {
      let _this = this;

      // We ask for confirmation
      var r = confirm('"' + name + '" will be removed, are you sure?');
      if (r == true) {
        var optionAxios = {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        };

        // The project is removed from the database
        axios
          .post(
            _this.server +
              "remove_project.php?n=" +
              name +
              "&e=" +
              _this.connectedClient,
            optionAxios
          )
          .then(function() {
            console.log("A project has been removed");
            _this.loadClientProjects();
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    renameProject: function(project) {
      let _this = this;
      let projectsArray = [];

      var optionAxios = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      };
      axios
        // We get the projects from the database
        .post(_this.server + "get_projects.php", optionAxios)
        .then(function(response) {
          console.log("Projects have been loaded from the database");
          projectsArray = response.data.map(x => x);

          var validInput = false;

          while (!validInput) {
            var dialog = prompt("Please, name your project", project.name);

            var existingName = false;

            // If the project name has at least one character
            if (dialog != null) {
              // We check if the name is available
              for (var j = 0; j < projectsArray.length && !existingName; j++) {
                if (
                  projectsArray[j][0].toLowerCase() == dialog.toLowerCase() &&
                  projectsArray[j][1] == _this.connectedClient
                ) {
                  existingName = true;
                }
              }

              // If it is
              if (!existingName) {
                // We check a few restrictions
                if (dialog.length > 20) {
                  alert("The name cannot exceed 20 characters");
                } else if (dialog.length < 3) {
                  alert("The name must have at least 3 characters");
                }
                // If everything is okay
                else {
                  validInput = true;

                  if (dialog == null) {
                    //
                  } else {
                    if (dialog != project.name) {
                      var optionAxios = {
                        headers: {
                          "Content-Type": "application/x-www-form-urlencoded"
                        }
                      };

                      // The project is renamed in the database
                      axios
                        .post(
                          _this.server +
                            "rename_project.php?nn=" +
                            dialog +
                            "&e=" +
                            _this.connectedClient +
                            "&n=" +
                            project.name,
                          optionAxios
                        )
                        .then(function() {
                          console.log("A project has been renamed");
                          _this.loadClientProjects();
                        })
                        .catch(function(error) {
                          console.log(error);
                        });
                    }
                  }
                }
              } else {
                if (dialog != project.name) {
                  alert("The name already exists");
                } else {
                  validInput = true;
                }
              }
            } else {
              validInput = true;
            }
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    handleSelectChange: function(evt, owner, name) {
      let _this = this;
      var optionAxios = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      };

      // The severity level is updated in the database
      axios
        .post(
          _this.server +
            "update_project.php?e=" +
            owner +
            "&s=" +
            evt.target.value +
            "&n=" +
            name,
          optionAxios
        )
        .then(function() {
          console.log("The severity level of a project has been updated");
          evt.target.blur();
          _this.loadClientProjects();
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  mounted: function() {
    this.loadClientProjects();
    this.newProjectSeverity = "7";
    document.body.classList.remove("stop-transitions");
  }
};
</script>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<style scoped>
.projects {
  margin: auto;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  align-items: center;
  width: 100%;
  min-width: 270px;
  user-select: none;
}

.project {
  position: relative;
  width: 200px;
  height: 200px;
  border-radius: 4px;
  padding-right: 10px;
  margin: 10px;
  margin-left: 0;
  margin-right: 20px;
  background-color: rgba(0, 0, 0, 0.07);
}

.project_subcontainer:active {
  transform: scale(0.98);
}

#new:active {
  transform: scale(0.98);
}

.project_subcontainer {
  position: absolute;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  cursor: default;
  color: white;
}

.remove-icon {
  position: absolute;
  right: 12px;
  top: 32px;
  display: none;
  opacity: 0.4;
  cursor: pointer;
  font-size: 14px;
}
.remove-icon:active,
.icon-edit:active {
  transform: scale(0.96);
}
.remove-icon:hover,
.icon-edit:hover {
  opacity: 0.9;
}

.icon-edit {
  position: absolute;
  right: 10px;
  top: 10px;
  display: none;
  opacity: 0.4;
  cursor: pointer;
  font-size: 12px;
}

.project-light {
  position: absolute;
  width: 80px;
  height: 6px;
  background-color: #cccccc59;
  /* opacity: 0.4; */
  top: 0px;
  left: 60px;
  border-radius: 0px 0px 5px 5px;
}

/* .severity_0 {
  background-color: rgba(255, 119, 119, 0.37);
}
.severity_1 {
  background-color: rgba(255, 157, 119, 0.37);
}
.severity_2 {
  background-color: rgba(255, 213, 119, 0.37);
}
.severity_3 {
  background-color: rgba(253, 255, 119, 0.37);
}
.severity_4 {
  background-color: rgba(203, 255, 119, 0.37);
}
.severity_5 {
  background-color: rgba(165, 255, 119, 0.37);
}
.severity_6 {
  background-color: rgba(140, 255, 120, 0.37);
} */

.severity_0 {
  background-color: rgba(255, 81, 81, 0.37);
}
.severity_1 {
  background-color: rgba(255, 167, 133, 0.5);
}
.severity_2 {
  background-color: rgba(255, 213, 119, 0.43);
}
.severity_3 {
  background-color: rgba(252, 255, 40, 0.37);
}
.severity_4 {
  background-color: rgba(167, 255, 25, 0.37);
}
.severity_5 {
  background-color: rgba(127, 255, 62, 0.37);
}
.severity_6 {
  background-color: rgba(55, 255, 20, 0.37);
}

.severity-level {
  color: black;
  -webkit-appearance: none;
  width: 27px;
  padding-left: 4px;
  font-size: 13px;
}
.severity-level option {
  color: black;
}

.select-icon {
  position: absolute;
  top: 14px;
  left: 26px;
  font-size: 8px;
  transform: rotate(180deg);
  opacity: 0;
}

.severity-level:hover + .select-icon {
  opacity: 0.9;
}

.project:hover {
  background-color: rgba(95, 106, 251);
  color: white;
}
.project:hover .icon_user-option,
.project:hover .text_user-option {
  color: white !important;
}
.project:hover .remove-icon,
.project:hover .icon-edit {
  display: block;
}
.project:hover .severity-level {
  color: white;
}
.project:hover .project-light {
  background-color: rgb(0, 0, 0, 0.1);
}
#new:hover .project_subcontainer {
  cursor: pointer;
}
/*.project:hover .severity-level {
	color: white;
}*/

.severity-level {
  position: absolute;
  background: transparent;
  outline-style: none;
  margin-top: 10px;
  margin-left: 10px;
  z-index: 5;
  border: none;
  opacity: 0.4;
}

.severity-level:hover,
.severity-level:focus {
  opacity: 0.9;
}

.severity-level:focus + .select-icon {
  opacity: 0.9;
}

.severity-level-text {
  position: absolute;
  margin-top: 10px;
  margin-left: 42px;
}

.option:hover {
  background-color: rgba(95, 106, 251);
  color: white;
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
  width: 80%;
  text-align: center;
}

.icon_admin-option {
  transform: translate(13px, 13px);
}

.text_admin-option {
  margin-left: 35px;
}

.icon_user-option {
  margin-top: 30px;
  font-size: 82px;
  color: rgba(0, 0, 0, 0.54);
}
</style>

<script>
import vSelect from 'vue-select';
import draggable from "vuedraggable";
import ProjectNewProjectBtn from "@components/project-lists/project-new-project-btn.vue";
import CuteMenu from "@components/global-kanban/cute-menu.vue";
import ProjectCard from "@components/global-kanban/project-card.vue";
import rootMixins from "@helpers/mixin/mixin.js";
import GKMixins from "@components/global-kanban/mixin";

export default {
  name: "kanban-column",
  props: {
    title: {
      type: String,
      required: true
    },
    allProjects: {
      type: Array,
      required: false,
      default: () => []
    },
    id: {
      type: Number,
      required: true
    }
  },
  mixins: [rootMixins, GKMixins],
  components: {
    ProjectCard,
    CuteMenu,
    draggable,
    'new-project-btn' : ProjectNewProjectBtn,
    'v-select' : vSelect
  },
  methods: {
    toggleDropdown(dropdownKey) {
      this.dropdowns[dropdownKey] = !this.dropdowns[dropdownKey];
      // Make sure only one is ever opened
      for (let key in this.dropdowns) {
        if (key !== dropdownKey) {
          this.dropdowns[key] = false;
        }
      }
    },
    addProject(project) {
      console.log("Board id: ", this.id);
      console.log("Project Added: ", project);
      this.addProjectBoardable(this.id, project);
      this.dropdowns.addProjMenu = false;
    },
    moveProject(evt) {
      // const project_id = parseInt(evt.item.id.split("_")[1]);
      // const to_board_id = parseInt(evt.to.parentElement.id.split("_")[1]);
      console.log("evt: ", evt);
      return true;
      // console.log("from_board_id: ", this.id);
      // console.log("to_board_id: ", to_board_id);

      // if (to_board_id !== this.id) {
      //   this.updateProjectBoardable(this.id, project_id, to_board_id);
      // }
    }
  },

  created() {
    this.getProjectBoardables(this.id);
  },
  data() {
    return {
      dropdowns: {
        addProjMenu: false,
        columnMenu: false
      },
      board_id: "board_" + this.id,
      editable: true
    };
  },
  computed: {
    colProjects() {
      return this.$store.state.globalKanban_boardables[this.id] || [];
    },
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: !this.editable,
        ghostClass: "ghost"
      }
    }
  }
}
</script>

<template>
  <div :data-section_id='id' class="kbc-th kbc-sortable-section kbc-section-order-by ui-sortable-handle">
    <div class="kbc-section-background">
<!---------------------------Header of the Column-------------------------->
      <div class="kbc-section-header-wrap" style="color: rgb(12, 26, 48); background: rgb(218, 233, 247);">
        <div class="kbc-section-header">
          <div :title='title' class="kbc-section-title">
            <span>{{ title }}</span>
          </div>
        </div>
        <div class="kbc-section-action kbc-non-sortable">
<!---------------------------Column Buttons----------------->
          <div class="kbc-action-icon-wrap">
            <div class="v-popover" fragment="15c5c64b77a">
              <div class="trigger" style="display: inline-block;">
                <!------------Add Project Button------>
                <button id="gk-add-project-btn" @click="toggleDropdown('addProjMenu')" class="pm-pro-kanboard-action-hrf">
                  <span style="color: rgb(52, 128, 235);">
                    <i aria-hidden="true" class="fa fa-plus"></i>
                  </span>
                </button>
                <button id="gk-col-options-btn" @click="toggleDropdown('columnMenu')" class="pm-pro-kanboard-action-hrf pm-pro-kanboard-del-btn">
                  <span style="color: rgb(52, 128, 235);">
                    <i aria-hidden="true" class="fa fa-ellipsis-v"></i>
                  </span>
                </button>
                <div v-show="dropdowns.addProjMenu" class="dropdown-content">
                  <cute-menu>
                    <v-select ref="select" :options="allProjects" label="title" @input="addProject"></v-select>
                  </cute-menu>
                </div>
                <!------------Options Dropdown------->
                <div v-show="dropdowns.columnMenu" class="dropdown-content">
                  <cute-menu>
                    <a href="#">Edit</a>
                    <a href="#">Delete</a>
                  </cute-menu>
                </div>
              </div>
            </div>
          </div>
          <div class="kbc-clearfix"></div>
        </div>
        <div class="kbc-clearfix"></div>
      </div>
<!---------------------------Projects in the Column-------------------------->
      <div class="kbc-tasks-wrap">
        <draggable :move="moveProject" class="gk-draggable-field" :id="board_id" v-bind="dragOptions">
          <transition-group class="gk-draggable-field">
            <project-card v-for="project in colProjects" :key="project.id" :project="project" :board_id="id"></project-card>
          </transition-group>
        </draggable>
      </div>
      <div class="kbc-section-footer"></div>
    </div>
  </div>
</template>

<style scoped lang="less">
.pm-kanboard {
 .kbc-kanboard {
   display: flex;
   .kbc-section-action {
     width: 50px;
     .kbc-action-icon-wrap {
       display: flex;
       justify-content: space-between;
     }
   }
 }
 .pm-kanboard-fullscreen {
   position: relative;
 }

 .pm-kanboard-fullscreen-active {
   background: #f1f1f1;
   padding: 22px 15px 15px 22px;
 }

 .fullscreen-view-btn {
   display: inline-flex;
   height: 30px;
   font-size: 12px;
   padding: 0 13px;
   border-radius: 3px;
   white-space: nowrap;
   align-items: center;
   background: #fff;
   border: 1px solid #E2E2E2;
   border-radius: 3px;
   border-top-right-radius: 0;
   border-bottom-right-radius: 0;
   color: #788383;
   border-right-color: #fff;
   white-space: nowrap;
   padding: 0 12px;
   margin-right: 10px;
   border-right: 1px solid #e2e2e2;
 }

  .gk-draggable-field {
    height: 100%;
    width: 100%;
  }

 .kanboard-menu-wrap {
   padding: 10px 0px;
   display: block;
   overflow: hidden;

   .task-filter {
     display: flex;
     align-items: center;
     height: 30px;
     color: #788383;
     padding: 0 13px;
     border-radius: 3px;
     font-size: 12px;
     border: 1px solid #e4e5e5;
     background: #fff;

     .icon-pm-filter {
       margin-right: 5px;
       color: #d4d6d6;
     }
   }

   .pm-list-header-menu {
     float: right;
     display: flex;
     align-items: center;
   }

   .fullscreen-view-btn {
     .icon-pm-fullscreen {
       &:before {
         vertical-align: middle;
       }
     }
     .icon-pm-fullscreen-text {
       margin-left: 8px;
       font-size: 12px;
       font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif !important;
       font-weight: 600;
     }

     &:hover {
       .icon-pm-fullscreen, .icon-pm-fullscreen-text {
         color: #1A9ED4 !important;
       }

       border-color: #1A9ED4;
     }
   }
 }

 .pm-project-module-page {
   .pm-project-module-content-overlay {
     width: 100%;
     margin-left: 0;
   }
 }
}

/* Kanban styles */
.kbc-clearfix {
  visibility: hidden;
  display: block;F
  font-size: 0;
  content: " ";
  clear: both;
  height: 0;
}
.kbc-kanboard .kbc-sortable-placeholder {
  border: 1px dashed #000;
  margin-left: 6%;
}
.kbc-kanboard .kbc-table-wrap {
  position: relative;
  overflow-x: auto;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap {
  width: 100%;
  white-space: nowrap;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder {
  display: inline-block;
  white-space: nowrap;
  vertical-align: top;
  position: relative;
  margin-right: 13px;
  border-style: solid;
  border-width: 1px;
  border-color: #e0e9ec;
  border-radius: 3px;
  background-color: #ffffff;
  box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.05);
  width: 254px;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-header-wrap,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-header-wrap {
  height: 50px;
  background: #fbfcfd;
  border-bottom: 1px solid #eff1f7;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-header-wrap .kbc-section-header,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-header-wrap .kbc-section-header {
  max-height: 52px;
  overflow-y: auto;
  width: 70%;
  display: inline-block;
  padding: 15px 0 15px 14px;
  float: left;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-header-wrap .kbc-section-action,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-header-wrap .kbc-section-action {
  vertical-align: top;
  text-align: right;
  position: relative;
  float: right;
  padding: 15px 10px 15px 0;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-header-wrap .kbc-section-action .kbck-section-task-count,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-header-wrap .kbc-section-action .kbck-section-task-count {
  margin-right: 18px;
  color: #dbdce0;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-header-wrap .kbc-section-action .kbc-action-icon-wrap,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-header-wrap .kbc-section-action .kbc-action-icon-wrap {
  position: relative;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-header-wrap .kbc-section-title,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-header-wrap .kbc-section-title {
  white-space: normal;
  text-align: justify;
  font-size: 16px;
  line-height: 1.25;
  text-align: left;
  -moz-transform: matrix(1.00074094, 0, 0, 1, 0, 0);
  -webkit-transform: matrix(1.00074094, 0, 0, 1, 0, 0);
  -ms-transform: matrix(1.00074094, 0, 0, 1, 0, 0);
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-after-inside-content .remove-from-board,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-header-wrap .kbc-spical-char,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-header-wrap .kbc-spical-char {
  color: #dbdce0;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-tasks-wrap,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-tasks-wrap {
  height: 54vh;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-kanboard-sortable-wrap,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-kanboard-sortable-wrap {
  height: 100%;
  overflow-y: auto;
  width: 100%;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-footer .cpm-add-more-task,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-footer .cpm-add-more-task {
  display: block;
  padding-left: 5px;
}
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-th .kbc-section-footer .kbc-section-new-task,
.kbc-kanboard .kbc-table-wrap .kbc-th-wrap .kbc-section-sortable-placeholder .kbc-section-footer .kbc-section-new-task {
  padding: 8px;
  width: 98%;
  border: none;
  font-size: 13px;
  height: 36px;
  border-style: solid;
  border-width: 1px;
  border-color: #eff1f7;
  border-radius: 3px;
  background-color: #fbfcfd;
}
/* Kanban styles end */
</style>

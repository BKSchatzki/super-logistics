<script>
import Draggable from "vuedraggable";
import ProjectNewProjectBtn from "@components/project-lists/project-new-project-btn.vue";
import CuteMenu from "@components/global-kanban/cute-menu.vue";
import ProjectTile from "@components/global-kanban/project-tile.vue";
import rootMixins from "@helpers/mixin/mixin.js";
import GKMixins from "@components/global-kanban/mixin";
import ColumnMenu from "@components/global-kanban/column-menu.vue";

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
    ColumnMenu,
    ProjectTile,
    CuteMenu,
    Draggable,
    'new-project-btn' : ProjectNewProjectBtn,
    'multiselect' : pm.Multiselect.Multiselect
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
    moveProject(evt) {
      let fromBoardId = evt.from.id.split("_")[1];
      let toBoardId = evt.to.id.split("_")[1];
      let projectId = evt.item.id.split("_")[1];
      console.log("fromBoardId: ", fromBoardId, " toBoardId: ", toBoardId, " projectId: ", projectId);
      this.updateProjectBoardable(fromBoardId, projectId, toBoardId);
    },
    addProject(project) {
      this.addProjectBoardable(this.id, project);
    },
    allowMove() {
      // this exclusively approves the move - doesn't even console.log besides that
      return true;
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
  <div :data-section_id='id' class="kbc-th">
<!---------------------------Header of the Column-------------------------->
    <div class="kbc-section-header-wrap column-drag-handle" style="color: rgb(12, 26, 48); background: rgb(218, 233, 247);">
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
              <!------------Options Button------>
              <button id="gk-col-options-btn" @click="toggleDropdown('columnMenu')" class="pm-pro-kanboard-action-hrf pm-pro-kanboard-del-btn">
                <span style="color: rgb(52, 128, 235);">
                  <i aria-hidden="true" class="fa fa-ellipsis-v"></i>
                </span>
              </button>
              <!------------Add Project Dropdown------->
              <div v-show="dropdowns.addProjMenu" class="dropdown-content">
                <multiselect ref="select" :options="allProjects" label="title" @input="addProject"></multiselect>
              </div>
              <!------------Options Dropdown------->
              <div v-show="dropdowns.columnMenu" class="dropdown-content">
                <column-menu :board_id="id"></column-menu>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<!---------------------------Projects in the Column-------------------------->
    <div class="kbc-tasks-wrap">
      <draggable :move="allowMove" class="gk-col-drag-area" :id="board_id" v-bind="dragOptions" @end="moveProject">
        <project-tile v-for="project in colProjects" :key="`project_${project.id}`" :project="project" :board_id="id"></project-tile>
      </draggable>
    </div>
    <div class="kbc-section-footer"></div>
  </div>
</template>

<style scoped lang="less">
.gk-col-drag-area {
  height: 100%;
  width: 100%;
}
.kbc-section-header-wrap {
  height: 50px;
  background: #fbfcfd;
  border-bottom: 1px solid #eff1f7;
}
.kbc-section-header {
  max-height: 52px;
  overflow-y: auto;
  width: 70%;
  display: inline-block;
  padding: 15px 0 15px 14px;
  float: left;
}
.kbc-section-action {
  vertical-align: top;
  text-align: right;
  position: relative;
  float: right;
  padding: 15px 10px 15px 0;
}
.kbc-action-icon-wrap {
  position: relative;
}
.kbc-tasks-wrap {
  height: 54vh;
}
/* Kanban styles end */
</style>

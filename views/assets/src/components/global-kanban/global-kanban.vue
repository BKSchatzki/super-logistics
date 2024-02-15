<template>
  <div class="pm-wrap pm pm-kanboard">
    <new-project-task-btns></new-project-task-btns>
    <div id="gk-main" class="pm-kanboard-fullscreen pm-project-module-page">
<!------------------------------------Header Start-------------------------->
      <div class="kanboard-menu-wrap">
        <!----------------------------Fullscreen Button-->
        <button id="gk-fullscreen-btn" class="fullscreen-view-btn list-action-group" @click="toggleFullScreen">
          <span class="icon-pm-fullscreen"></span>
          <span class="icon-pm-fullscreen-text">{{ __( 'Fullscreen', 'wedevs-project-manager' ) }}</span>
        </button>
        <div class="pm-list-header-menu">
          <a :title="__( 'Task Filter', 'wedevs-project-manager' )" href="#" @click.stop class="active-task-filter task-filter list-action-group task-filter-btn">
            <span class="icon-pm-filter"></span>
            <span>{{ __( 'Filter', 'wedevs-project-manager' ) }}</span>
          </a>
        </div>
        <br>
      </div>
<!------------------------------------The Board Itself------------------->
      <div class="kbc-kanboard">
        <div class="kbc-table-wrap">
          <div class="kbc-th-wrap kbc-section-order-wrap ui-sortable">
<!------------------------------------Kanban Column(s)--------------------->
            <kanban-column v-for="stage in stages" :title="stage.title" :stage_id="stage.id" ></kanban-column>
<!------------------------------------Add New Column----------------------->
            <div class="kbc-th kbc-th-new-section kbc-non-sortable ui-sortable-handle">
              <div class="kbc-section-header">
                <div>
                  <input type="text" :placeholder="__( 'Add new section', 'wedevs-project-manager' )" required="required" class="wpup-new-section-field">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="less" scoped>
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
    background: #ffffff; /* Set background to white */
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
  display: block;
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
  background: #ffffff;
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

<script>
import KanbanColumn from "@components/global-kanban/kanban-column.vue";
import ProjectNewProjectBtn from "@components/project-lists/project-new-project-btn.vue";
import ProjectMixins from "@components/project-lists/mixin";

export default {
  components: {
    "new-project-task-btns" : ProjectNewProjectBtn,
    "kanban-column": KanbanColumn
  },
  mixins: [ProjectMixins],
  computed: {
    stages () {
      // Just a nice little warning
      // $store.state.stages is set when something calls getStages() in the store
      // So one of the components on this page is doing it, but if it suddenly doesn't, they may not be there anymore
      return this.$root.$store.state.stages;
    }
  },
  methods: {
    toggleFullScreen() {
      if (!document.fullscreenElement) {
        const kanboard = document.getElementById('gk-main');
        kanboard.requestFullscreen().catch(err => {
          alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
        });
      } else {
        document.exitFullscreen();
      }
    },
    projectQuery () {
      const self = this;
      const args = {
        conditions: {
          status: 'incomplete',
          with: 'assignees',
          per_page: self.getSettings('project_per_page', 50),
          project_meta: 'all',
          orderby: 'id:desc',
        },

        callback (res) {
          self.projectFetchStatus(true);
          self.loading = false;
        }
      }

      this.loading = true;
      this.getProjects(args);
    }
  },

  created () {
    this.projectQuery();
  },
  data() {
    return {
    }
  },
  mounted() {

  }
}
</script>

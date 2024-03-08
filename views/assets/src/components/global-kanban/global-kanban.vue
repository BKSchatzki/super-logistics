<script>
import KanbanColumn from "@components/global-kanban/kanban-column.vue";
import ProjectNewProjectBtn from "@components/project-lists/project-new-project-btn.vue";
import KanbanMixins from "@components/global-kanban/mixin";
import AddNewColumn from "@components/global-kanban/add-new-column.vue";
import Draggable from "vuedraggable";

export default {
  components: {
    AddNewColumn,
    Draggable,
    KanbanColumn,
    "new-project-task-btns" : ProjectNewProjectBtn
  },
  computed: {
    gkColumns() {
      return [...this.$store.state.globalKanban_columns];
    },
    dragOptions() {
      return {
        animation: 0,
        group: "columns",
        sort: true,
        disabled: !this.editable,
        ghostClass: "ghost",
        handle: ".column-drag-handle"
      }
    }
  },
  mixins: [KanbanMixins],
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
    moveColumn(evt) {
      this.$store.commit('setGK_columns', this.gkColumns);
      this.updateBoardOrder(this.gkColumns);
    },
    allowMove() {
      return true;
    },
  },

  data() {
    return {
      editable: true
    }
  },

  created () {
    const pArgs = {
      status: 0,
      per_page: 0
    };

    this.getProjects(pArgs);
    this.getGlobalKanban();
  }
}
</script>

<template>
  <div class="pm-wrap pm pm-kanboard">
    <new-project-task-btns></new-project-task-btns>
    <div id="gk-main" class="pm-kanboard-fullscreen pm-project-module-page">
<!------------------------------------Header Start-------------------------->
      <div class="kanboard-menu-wrap">
        <!----------------------------Fullscreen Button----->
        <button class="fullscreen-view-btn" @click="toggleFullScreen">
          <span class="icon-pm-fullscreen"></span>
          <span class="icon-pm-fullscreen-text">{{ __( 'Fullscreen', 'wedevs-project-manager' ) }}</span>
        </button>
        <br>
      </div>
<!------------------------------------The Board Itself--------------->
      <div class="kbc-kanboard">
        <div class="kbc-table-wrap">
          <div class="kbc-th-wrap kbc-section-order-wrap">
            <!---------------------------Kanban Column(s)------------------->
            <draggable class="gkb-drag-area" :move="allowMove" :list="gkColumns" v-bind="dragOptions" @end="moveColumn">
              <kanban-column v-for="board in gkColumns" :title="board.title" :allProjects="$store.state.projects" :id="board.id" :key="board.id"></kanban-column>
            </draggable>
            <add-new-column></add-new-column>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="less" scoped>

.gkb-drag-area {
  display: inline;
  width: 100%;
  height: 100%;
}

.kbc-kanboard {
  margin-top: 10px;
  display: flex;
  .kbc-section-action {
    width: 50px;
    .kbc-action-icon-wrap {
      display: flex;
      justify-content: space-between;
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
}
</style>

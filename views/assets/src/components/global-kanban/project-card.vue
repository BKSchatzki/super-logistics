<script>
import GKMixins from "@components/global-kanban/mixin";
export default {
  name: "project-card.vue",
  props: {
    // the project object should really have everything you need
    // id, title, description, updated_at, created_at, etc.
    project: {
      type: Object,
      required: true
    },
    board_id: {
      type: Number,
      required: true
    }
  },
  mixins: [GKMixins],
  methods: {
    removeProj() {
      // removes the project from the board (meaning, the column)
      this.removeProjectBoardable(this.board_id, this.project.id);
    }
  },
  data () {
    return {
      id: "project_" + this.project.id,
    }

  }
}
</script>

<template>
  <div class="gk-project-card" :id="id">
    <h4>{{ project.title }}</h4>
    <router-link :to="{ name: 'task_lists', params: { project_id: project.id } }">See Project</router-link>
    <button @click="removeProj">
      <span>
        <i class="fa fa-trash"></i>
      </span>
    </button>
  </div>
</template>

<style scoped lang="less">
.gk-project-card {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin: 10px 10px 0px;
  width: 220px;
  height: 60px;
  overflow: hidden;

  h4 {
    margin: 0;
  }
}
</style>

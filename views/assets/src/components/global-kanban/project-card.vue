<script>
export default {
  name: "project-card.vue",
  props: {
    // the project object should really have everything you need
    // id, title, description, updated_at, created_at, etc.
    project: {
      type: Object,
      required: true
    }
  }
}
</script>

<template>
  <div class="gk-project-card">
    <h4>{{ project.title }}</h4>
    <a href="/projects/{project.id}">See Project</a>
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

<template>
  <div>
    <!-- "New Project" Button -->
    <button @click="showProjectModal = true">New Project</button>
    <!-- "New Customer" Button -->
    <button @click="showProjectModal = true">New Customer</button>
    <!-- "New Task" Button -->
    <button @click="showTaskModal = true">New Task</button>
    <!-- Customer/Project Modal -->
    <project-create-form v-if="showProjectModal" @update:is-active="val => showProjectModal = val" @disableProjectForm="showProjectModal=false"></project-create-form>
    <!-- Task Modal -->
    <new-task-form v-if='showTaskModal' @disableTaskForm="showTaskModal=false"></new-task-form>
  </div>
</template>

<script>
import ModalComponent from "@components/common/Modal.vue";
import ProjectCreateForm from "@components/project-lists/project-create-form.vue";
import newTaskForm from "@components/my-tasks/new-task.vue";

export default {
  components: { newTaskForm, ModalComponent, ProjectCreateForm },
  data() {
    return {
      showProjectModal: false,
      showTaskModal: false
    };
  }
};
</script>

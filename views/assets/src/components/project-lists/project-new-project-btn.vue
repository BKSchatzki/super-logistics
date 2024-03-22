<template>
  <div>
    <!-- "New Project" Button -->
    <button @click="showCustomerModal = true">New Project</button>
    <!-- "New Customer" Button -->
    <button @click="showCustomerModal = true">New Customer</button>
    <!-- "New Task" Button -->
    <button @click="showTaskModal = true">New Task</button>
    <!-- Project Modal -->
    <modal-component :is-active.sync="showCustomerModal">
      <project-create-form :is-active="showCustomerModal" @update:is-active="val => showCustomerModal = val"></project-create-form>
    </modal-component>
    <!-- Customer Modal -->
    <modal-component :is-active.sync="showCustomerModal">
      <project-create-form v-if="showCustomerModal" @update:is-active="val => showCustomerModal = val"></project-create-form>
    </modal-component>
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
      showCustomerModal: false,
      showTaskModal: false
    };
  }
};
</script>

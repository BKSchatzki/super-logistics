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
      <project-create-form :is-active="showCustomerModal" @update:is-active="val => showCustomerModal = val"></project-create-form>
    </modal-component>


    <!-- Task Modal -->
    <modal-component :is-active="showTaskModal" @close="showTaskModal = false">
      <globally-task-create-form></globally-task-create-form>
    </modal-component>
  </div>
</template>

<script>
import ModalComponent from "@components/common/Modal.vue";
import ProjectCreateForm from "@components/project-lists/project-create-form.vue";
import GloballyTaskCreateForm from "@components/common/globally-task-create-form.vue";

export default {
  components: {
    "globally-task-create-form": GloballyTaskCreateForm,
    "modal-component": ModalComponent,
    "project-create-form": ProjectCreateForm
  },
  data() {
    return {
      showProjectModal: false,
      showCustomerModal: false,
      showTaskModal: false
    };
  }
};
</script>

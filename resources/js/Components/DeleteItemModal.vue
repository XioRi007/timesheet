<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue"
import DangerButton from "@/Components/DangerButton.vue"
import Modal from "@/Components/Modal.vue"

defineProps({
  entityName: {
    type: String,
    required: true
  },
  close: {
    type: Function,
    required: true
  },
  item: {
    type: Number,
    required: false
  },
  deleteItem: {
    type: Function,
    required: true
  },
  error: {
    type: String,
    required: false
  }
})
</script>

<template>
  <Modal>
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 text-center">
        Are you sure you want to delete this {{ entityName }}?
      </h2>
      <p class="text-red-600 text-center py-2">{{ error }}</p>
      <div class="mt-6 flex justify-center">
        <SecondaryButton @click="close"> Cancel</SecondaryButton>
        <DangerButton
          class="ml-3"
          @click="deleteItem(item)"
        >
          Delete
        </DangerButton>
      </div>
    </div>
  </Modal>
</template>

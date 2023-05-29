<template>
  <div class="bg-white overflow-hidden flex justify-end">
    <button @click="back">
      <font-awesome-icon class="" icon="fa-solid fa-xmark" size="xl" title="Close"/>
    </button>
  </div>
</template>

<script setup>
import {router} from "@inertiajs/vue3"
const props = defineProps({
  link: {
    type: String,
    required: true
  }
})

const back = () => {
  router.visit(props.link)
}
</script>
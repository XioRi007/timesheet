<template>
  <div class="bg-white overflow-hidden flex justify-end">
    <button @click="back">
      <font-awesome-icon class="" icon="fa-solid fa-xmark" size="xl" title="Close"/>
    </button>
  </div>
</template>

<script setup>
const back = () => {
  window.history.back();
}
</script>
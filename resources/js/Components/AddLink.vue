<script setup>
import {Link} from "@inertiajs/vue3"

const props = defineProps({
  createLink: {
    type: String,
    required: true
  },
})
</script>

<template>
  <div class="bg-white overflow-hidden mb-6 flex justify-end">
    <Link :href="route(createLink)">
      <font-awesome-icon class="" icon="fa-solid fa-plus" size="xl" title="Add new"/>
    </Link>
  </div>
</template>
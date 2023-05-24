<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import DataTable from "@/Components/DataTable.vue"
import Title from "@/Components/Title.vue"
import {useToast} from "@/useToast.js"

const props = defineProps({
  message: {
    type: String
  }
});
useToast(props);
</script>

<template>
  <Head title="Developers"/>

  <AuthenticatedLayout>
    <template #header>
      <Title text="Developers"/>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
        <div class="bg-white overflow-hidden p-10">
          <div
            class="relative overflow-x-auto">
            <DataTable
              :data="$page.props.developers"
              :has-actions=true
              create-link="developers.create"
              delete-action-link="developers.destroy"
              edit-action-link="developers.edit"
              entity-name="developer"
              redirect-link="developers.index"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

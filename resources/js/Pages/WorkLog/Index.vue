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
  <Head title="Work Logs"/>

  <AuthenticatedLayout>
    <template #header>
      <Title text="Work Logs"/>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
        <div class="bg-white overflow-hidden p-10">
          <div
            class="relative overflow-x-auto">
            <DataTable
              :data="$page.props.worklogs"
              :has-actions=true
              :status-text="['Paid', 'Unpaid']"
              create-link="worklogs.create"
              delete-action-link="worklogs.destroy"
              edit-action-link="worklogs.edit"
              entity-name="work log"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import Title from "@/Components/Title.vue"
import WorkLogForm from "@/Pages/WorkLog/WorkLogForm.vue"


const props = defineProps({
  worklog: {
    type: Object,
  },
})
</script>

<template>
  <Head title="Edit worklog"/>
  <AuthenticatedLayout>
    <template #header>
      <Title text="Edit work log"/>
    </template>
    <div class="py-12 w-2/4 m-auto">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <WorkLogForm
            :developer="$page.props.developer"
            :project="$page.props.project"
            :rate="$page.props.rate"
            :submitRoute="route('worklogs.update', worklog.id)"
            :worklog="worklog"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

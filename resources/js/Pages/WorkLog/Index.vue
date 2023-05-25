<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import DataTable from "@/Components/DataTable.vue"
import Title from "@/Components/Title.vue"
import {useToast} from "@/useToast.js"
import AddLink from "@/Components/AddLink.vue"
import TableFilter from "@/Pages/WorkLog/TableFilter.vue"

useToast();
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
            <AddLink create-link="worklogs.create"/>
            <TableFilter
              :filter-params="$page.props.filterParams"
            />
            <DataTable
              :data="$page.props.worklogs"
              :has-actions=true
              :status-text="['Paid', 'Unpaid']"
              delete-action-link="worklogs.destroy"
              edit-action-link="worklogs.edit"
              entity-name="work log"
              redirect-link="worklogs.index"
              :column="$page.props.column"
              :ascending="$page.props.ascending"
              :_column-names="['date', 'developer', 'project', 'rate', 'hours', 'total', 'status']"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

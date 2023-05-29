<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import DataTable from "@/Components/DataTable.vue"
import Title from "@/Components/Title.vue"
import {useToast} from "@/useToast.js"
import AddLink from "@/Components/AddLink.vue"
import Pagination from "@/Components/Pagination.vue"

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
            class="relative">
            <AddLink create-link="worklogs.create"/>
            <DataTable
              :data="$page.props.worklogs.data"
              :has-actions=true
              :status-text="['Paid', 'Unpaid']"
              delete-action-link="worklogs.destroy"
              edit-action-link="worklogs.edit"
              entity-name="work log"
              redirect-link="worklogs.index"
              :column="$page.props.column"
              :ascending="$page.props.ascending"
              :filter-params="$page.props.filterParams"
              :filterFormat="[{
                  name: 'Date',
                  real: 'created_at',
                  type: 'date'
                }, {
                  name: 'Developer',
                  real: 'developer_id',
                  type: 'select',
                  data: $page.props.developers
                },{
                  name: 'Project',
                  real: 'project_id',
                  type: 'select',
                  data: $page.props.projects
                },{
                  name: 'Rate',
                  real: 'rate',
                  type: 'rate'
                },{
                  name: 'Hours',
                  real: 'hrs',
                  type: 'hrs'
                },{
                  name: 'Total',
                  real: 'total',
                  type: 'total'
                },{
                  name: 'Status',
                  real: 'status',
                  type: 'status'
                }
               ]"
            />
            <Pagination
              :links="$page.props.worklogs.links"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

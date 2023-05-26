<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import DataTable from "@/Components/DataTable.vue"
import Title from "@/Components/Title.vue"
import {useToast} from "@/useToast.js"
import TableFilter from "@/Pages/Project/TableFilter.vue"
import AddLink from "@/Components/AddLink.vue"
import Pagination from "@/Components/Pagination.vue"

useToast();
</script>

<template>
  <Head title="Projects"/>

  <AuthenticatedLayout>
    <template #header>
      <Title text="Projects"/>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
        <div class="bg-white overflow-hidden p-10">
          <div
            class="relative">
            <AddLink create-link="projects.create"/>
            <TableFilter
              :filter-params="$page.props.filterParams"
            />
            <DataTable
              :data="$page.props.projects.data"
              :has-actions=true
              delete-action-link="projects.destroy"
              edit-action-link="projects.edit"
              entity-name="project"
              redirect-link="projects.index"
              :column="$page.props.column"
              :ascending="$page.props.ascending"
              :_column-names="['name', 'client', 'rate', 'status']"
            />
            <Pagination
              :links="$page.props.projects.links"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

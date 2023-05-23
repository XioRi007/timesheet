<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import DataTable from "@/Components/DataTable.vue"
import Title from "@/Components/Title.vue"
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
            class="relative overflow-x-auto">
            <DataTable
              :data="$page.props.projects"
              :has-actions=true
              create-link="projects.create"
              delete-action-link="projects.destroy"
              edit-action-link="projects.edit"
              entity-name="project"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import DataTable from "@/Components/DataTable.vue"
import Title from "@/Components/Title.vue"
</script>

<template>
  <Head title="Clients"/>

  <AuthenticatedLayout>
    <template #header>
      <Title text="Clients"/>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
        <div class="bg-white overflow-hidden p-10">
          <div
            class="relative overflow-x-auto">
            <DataTable
              :data="$page.props.clients"
              :has-actions=true
              create-link="clients.create"
              delete-action-link="clients.destroy"
              edit-action-link="clients.edit"
              entity-name="client"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

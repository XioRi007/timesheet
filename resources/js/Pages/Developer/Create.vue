<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head} from '@inertiajs/vue3'
import Title from "@/Components/Title.vue"
import DeveloperForm from "@/Pages/Developer/DeveloperForm.vue"
import CloseBtn from "@/Components/CloseBtn.vue"
import ClientForm from "@/Pages/Client/ClientForm.vue"
</script>

<template>
  <Head title="Create Developer"/>
  <AuthenticatedLayout>
    <template #header>
      <Title text="Create Developer"/>
    </template>
    <div class="py-12 w-2/4 m-auto">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <div class="bg-white overflow-hidden mb-6 flex justify-end">
            <CloseBtn
              :link="$page.props.backLink"
            />
          </div>
          <DeveloperForm
            :submitRoute="route('developers.store')"
            :back-link="$page.props.backLink"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

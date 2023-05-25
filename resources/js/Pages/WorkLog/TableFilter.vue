<script setup>
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import InputLabel from "@/Components/InputLabel.vue"
import {router, useForm} from "@inertiajs/vue3"
import DatePicker from "@/Components/DatePicker.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import * as yup from "yup"
import {maxDecimalPlaces} from "@/validation.js"
import {showToast} from "@/useToast.js"

const defaultParams = {
  developer_id: null,
  project_id: null,
  rate: null,
  hrs: null,
  total: null,
  status: null,
  created_at: ''
}
const schema = yup.object({
  developer_id: yup.number().nullable(),
  project_id: yup.number().nullable(),
  rate: maxDecimalPlaces(2).min(0).max(999.99).nullable(),
  hrs: maxDecimalPlaces(2).min(0.1).max(999.99).nullable(),
  total: maxDecimalPlaces(2).min(0).max(99999999.99).nullable(),
  status: yup.boolean().nullable(),
})
const props = defineProps({
  filterParams: {
    type: Object
  }
})

const form = useForm(props.filterParams)
form.defaults(defaultParams)
const submit = async () => {
  try {
    form.clearErrors()
    await schema.validate(form, {abortEarly: false})
    router.get('', {
      filter: form.data()
    })
  } catch (err) {
    showToast('Fill the form correctly', 'error')
    err.inner.forEach((element) => {
      form.setError(element.path, element.message)
    })
  }

}

</script>

<template>
  <form novalidate class="flex grid-rows-1 mb-6 items-end" @submit.prevent="submit">

    <div class="mr-6">
      <InputLabel for="created_at" value="Date"/>
      <DatePicker
        id="created_at"
        v-model="form.created_at"
        class="mt-1 block w-full"
      />
    </div>
    <div class="w-44 mr-6">
      <InputLabel for="developer" value="Developer"/>
      <select id="developer"
              v-model="form.developer_id"
              class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
              name="developer"
      >
        <option :value=null selected></option>
        <option v-for="developer in $page.props.developers" :value="developer.id">{{ developer.name }}</option>
      </select>
    </div>

    <div class="w-44 mr-6">
      <InputLabel for="project" value="Project"/>
      <select id="project"
              v-model="form.project_id"
              class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
              name="project"
      >
        <option :value=null selected></option>
        <option v-for="project in $page.props.projects" :value="project.id">{{ project.name }}</option>
      </select>
    </div>

    <div class="w-20 mr-6">
      <InputLabel for="rate" value="Rate"/>
      <TextInput
        id="rate"
        v-model="form.rate"
        autocomplete="rate"
        class="mt-1 block w-full"
        step=".01"
        type="number"
      />
    </div>

    <div class="w-20 mr-6">
      <InputLabel for="rate" value="Hours"/>
      <TextInput
        id="hrs"
        v-model="form.hrs"
        autocomplete="rate"
        class="mt-1 block w-full"
        step=".01"
        type="number"
      />
    </div>

    <div class="w-24 mr-6">
      <InputLabel for="total" value="Total"/>
      <TextInput
        id="total"
        v-model="form.total"
        autocomplete="rate"
        class="mt-1 block w-full"
        step=".01"
        type="number"
      />
    </div>

    <div class="mr-6 w-40">
      <InputLabel for="status" value="Status"/>
      <select
        id="status"
        v-model="form.status"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
        name="status"
      >
        <option :value=null selected></option>
        <option :value="true">Paid</option>
        <option :value="false">Unpaid</option>
      </select>
      <InputError :message="form.errors.status" class="mt-2"/>
    </div>
    <PrimaryButton :disabled="form.processing" class="mr-2" type="submit">Filter</PrimaryButton>
    <SecondaryButton :disabled="form.processing" @click="form.reset();submit()">Reset</SecondaryButton>
  </form>
  <InputError class="mb-2" v-for="error in form.errors" :message="error" />

</template>
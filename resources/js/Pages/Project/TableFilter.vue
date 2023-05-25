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
  client_id: null,
  name: null,
  rate: null,
  status: null,
}
const schema = yup.object({
  name: yup.string().nullable(),
  client_id: yup.number().nullable(),
  rate: maxDecimalPlaces(2).min(0).max(999.99).nullable(),
  status: yup.boolean().nullable(),
})
const props = defineProps({
  filterParams: {
    type: Object
  }
})

const form = useForm(Object.keys(props.filterParams).length ? props.filterParams : defaultParams)
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
const reset = () => {
  form.reset()
  router.get('', {
    filter: form.data(),
    column: null,
    ascending: null
  })
}

</script>

<template>
  <form novalidate class="flex grid-rows-1 mb-6 items-end" @submit.prevent="submit">


    <div class="w-1/5 mr-4">
      <InputLabel for="name" value="Name"/>
      <TextInput
        id="name"
        v-model="form.name"
        autocomplete="name"
        autofocus
        class="mt-1 block w-full"
        type="text"
      />
    </div>

    <div class="w-1/5 mr-4">
      <InputLabel for="developer" value="Client"/>
      <select id="developer"
              v-model="form.client_id"
              class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
              name="developer"
      >
        <option :value=null selected></option>
        <option v-for="client in $page.props.clients" :value="client.id">{{ client.name }}</option>
      </select>
    </div>

    <div class="w-1/5 mr-4">
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

    <div class="w-1/5 w-40">
      <InputLabel for="status" value="Status"/>
      <select
        id="status"
        v-model="form.status"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
        name="status"
      >
        <option :value=null selected></option>
        <option :value="true">Active</option>
        <option :value="false">Inactive</option>
      </select>
      <InputError :message="form.errors.status" class="mt-2"/>
    </div>
    <div class="w-1/5 flex justify-end">
      <PrimaryButton :disabled="form.processing" class="mr-2 h-10 w-24" type="submit">Filter</PrimaryButton>
      <SecondaryButton :disabled="form.processing" class="h-10 w-24" @click="reset">Reset</SecondaryButton>
    </div>
  </form>
  <InputError class="mb-2" v-for="error in form.errors" :message="error" />

</template>
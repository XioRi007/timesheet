<script setup>
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import InputLabel from "@/Components/InputLabel.vue"
import {useForm} from "@inertiajs/vue3"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import * as yup from "yup"
import {maxDecimalPlaces} from "@/validation.js"
import {showToast} from "@/useToast.js"

const defaultParams = {
  first_name: null,
  last_name: null,
  rate: null,
  status: null,
}
const schema = yup.object({
  first_name: yup.string().nullable(),
  last_name: yup.string().nullable(),
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
    form.get('', {
      data: {
        filter: JSON.stringify(form.data())
      }
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

    <div class="w-64 mr-6">
      <InputLabel for="first_name" value="First Name"/>
      <TextInput
        id="first_name"
        v-model="form.first_name"
        autocomplete="first_name"
        autofocus
        class="mt-1 block w-full"
        type="text"
      />
    </div>

    <div class="w-56 mr-6">
      <InputLabel for="last_name" value="Last Name"/>
      <TextInput
        id="last_name"
        v-model="form.last_name"
        autocomplete="last_name"
        autofocus
        class="mt-1 block w-full"
        type="text"
      />
    </div>
    <div class="w-48 mr-6">
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

    <div class="mr-6 w-44">
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
    <PrimaryButton :disabled="form.processing" class="mr-2" type="submit">Filter</PrimaryButton>
    <SecondaryButton :disabled="form.processing" @click="form.reset();submit()">Reset</SecondaryButton>
  </form>
  <InputError class="mb-2" v-for="error in form.errors" :message="error"/>

</template>
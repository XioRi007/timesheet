<script setup>
import TextInput from "@/Components/TextInput.vue"
import InputLabel from "@/Components/InputLabel.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import InputError from "@/Components/InputError.vue"
import {router, useForm} from "@inertiajs/vue3"
import {showToast} from "@/useToast.js"
import * as yup from "yup"
import {maxDecimalPlaces} from "@/validation.js"
import DatePicker from "@/Components/DatePicker.vue"
import {computed} from "vue"

const props = defineProps({
  filterParams: {
    type: Object
  },
  statusText: {
    type: Array,
    default: ['Active', 'Inactive']
  },
  filterFormat: {
    type: Array,
    required: true
  },
})
const defaultParams = {}

props.filterFormat.forEach((column) => {
  defaultParams[column.real] = null
})
const form = useForm(Object.keys(props.filterParams).length ? props.filterParams : defaultParams)
form.defaults(defaultParams)

let schemaFields = {}
props.filterFormat.forEach(column => {
  switch (column.type) {
    case 'text':
      schemaFields[column.real] = yup.string().nullable()
      break
    case 'rate':
      schemaFields[column.real] = maxDecimalPlaces(2).min(0).max(999.99).nullable()
      break
    case 'status':
      schemaFields[column.real] = yup.boolean().nullable()
      break
    case 'select':
      schemaFields[column.real] = yup.number().nullable()
      break
    case 'hrs':
      schemaFields[column.real] = maxDecimalPlaces(2).min(0.1).max(999.99).nullable()
      break
    case 'total':
      schemaFields[column.real] = maxDecimalPlaces(2).min(0).max(99999999.99).nullable()
      break
    default:
      break
  }
})
const schema = yup.object(schemaFields)

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
  console.log(form.data())
  router.get('', {
    filter: form.data(),
    column: null,
    ascending: null
  })
}
const computedStyles = computed(()=>{
  const gridColumnCount = props.filterFormat.length + 1;
  return {
    'grid-template-columns': `repeat(${gridColumnCount}, minmax(0, 1fr))`
  };
})
</script>

<template>
  <form
    novalidate
    class="flex mb-6 items-end grid"
    :style="computedStyles"
    @submit.prevent="submit">
    <div v-for="column in filterFormat" class="col-span-1 pr-3 font-medium text-black">
      <InputLabel :for="column.real" :value="column.name"/>
      <TextInput
        v-if="column.type === 'rate' || column.type === 'hrs' || column.type === 'total'"
        :id="column.real"
        v-model="form[column.real]"
        :autocomplete="column.real"
        class="mt-1 block w-full"
        step=".01"
        type="number"
      />
      <select
        v-else-if="column.type === 'status'"
        :id="column.real"
        v-model="form[column.real]"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
      >
        <option :value=null selected></option>
        <option :value="true">{{ statusText[0] }}</option>
        <option :value="false">{{ statusText[1] }}</option>
      </select>

      <select
        :id="column.real"
        v-else-if="column.type === 'select'"
        v-model="form[column.real]"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
      >
        <option :value=null selected></option>
        <option v-for="item in column.data" :value="item.id">{{ item.name }}</option>
      </select>

      <DatePicker
        :id="column.real"
        v-else-if="column.type === 'date'"
        v-model="form[column.real]"
        class="mt-1 block w-full"
      />

      <TextInput
        v-else
        :id="column.real"
        v-model="form[column.real]"
        :autocomplete="column.real"
        class="mt-1 block w-full"
        type="text"
      />
    </div>
    <div class="flex justify-between">
      <PrimaryButton :disabled="form.processing" class=" h-10 w-2/4 mr-1" type="submit">Filter</PrimaryButton>
      <SecondaryButton :disabled="form.processing" class="h-10 w-2/4" @click="reset">Reset</SecondaryButton>
    </div>
  </form>
  <InputError class="mb-2" v-for="error in form.errors" :message="error"/>
</template>
<style>
.grid-cols-custom {
    grid-template-columns: repeat(props.filterFormat.length +1, minmax(0, 1fr));
}
</style>
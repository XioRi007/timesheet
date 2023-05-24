<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import {Link, useForm} from "@inertiajs/vue3"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import Toggle from "@/Components/Toggle.vue"
import * as yup from "yup"
import {maxDecimalPlaces} from "@/validation.js"
import {toast} from "vue3-toastify"

const props = defineProps({
  submitRoute: {
    type: String,
    required: true
  },
  project: {
    type: Object,
    required: false,
    default: {
      name: '',
      client_id: null,
      rate: 1.00,
      status: true,
    }
  },
})


const schema = yup.object({
  name: yup.string().required(),
  rate: maxDecimalPlaces(2).min(0).max(999.99).typeError('rate is required'),
  status: yup.string().required().oneOf(["true", "false"]),
  client_id: yup.number().required()
})

const form = useForm(props.project)
form.defaults()
const submit = async () => {
  try {
    form.clearErrors()
    await schema.validate(form, {abortEarly: false})
    form.status = form.status === true
    if (route().current('projects.edit')) {
      form.put(props.submitRoute)
    } else {
      form.post(props.submitRoute)
    }
  } catch (err) {
    toast("Fill the form correctly", {
      type: 'error',
      autoClose: 5000,
      position: toast.POSITION.BOTTOM_RIGHT,
    });
    err.inner.forEach((element) => {
      form.setError(element.path, element.message)
    })
  }
}
</script>

<template>
  <div class="bg-white overflow-hidden mb-6 flex justify-end">
    <Link :href="route('projects.index')">
      <font-awesome-icon class="" icon="fa-solid fa-xmark" size="xl" title="Close"/>
    </Link>
  </div>
  <form class="mt-6 space-y-6" novalidate @submit.prevent="submit">

    <div>
      <InputLabel for="project" value="Client"/>
      <select id="project"
              v-model="form.client_id"
              class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
        <option v-for="client in $page.props.clients" :value="client.id">{{ client.name }}</option>
      </select>
      <InputError :message="form.errors.client_id" class="mt-2"/>
    </div>

    <div>
      <InputLabel for="name" value="Name"/>
      <TextInput
        id="name"
        v-model="form.name"
        autocomplete="name"
        autofocus
        class="mt-1 block w-full"
        type="text"
      />
      <InputError :message="form.errors.name" class="mt-2"/>
    </div>

    <div>
      <InputLabel for="rate" value="Rate"/>
      <TextInput
        id="rate"
        v-model="form.rate"
        autocomplete="rate"
        class="mt-1 block w-full"
        step="0.01"
        type="number"
      />
      <InputError :message="form.errors.rate" class="mt-2"/>
    </div>

    <div v-show="route().current('projects.edit')">
      <Toggle
        v-model="form.status"
        active-text="Active"
        not-active-text="Inactive"
      />
    </div>

    <div class="flex items-center gap-4 justify-between">
      <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
      <SecondaryButton :disabled="form.processing" @click.prevent="form.reset()">Reset</SecondaryButton>
      <Transition class="transition ease-in-out" enter-from-class="opacity-0" leave-to-class="opacity-0">
        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
      </Transition>
    </div>
  </form>
</template>

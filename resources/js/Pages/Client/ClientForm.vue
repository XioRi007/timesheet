<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import {useForm} from "@inertiajs/vue3"
import Toggle from "@/Components/Toggle.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import * as yup from "yup"
import {maxDecimalPlaces} from "@/validation.js"

const props = defineProps({
  submitRoute: {
    type: String,
    required: true
  },
  client: {
    type: Object,
    required: false,
    default: {
      name: '',
      rate: 1.00,
      status: true
    }
  },
})


const schema = yup.object({
  name: yup.string().required(),
  rate: maxDecimalPlaces(2).min(0).max(999.99).typeError('rate is required'),
  status: yup.string().required().oneOf(['true', 'false']),
})
const form = useForm(props.client)
const submit = async () => {
  try {
    form.clearErrors()
    await schema.validate(form, {abortEarly: false})
    form.status = form.status === true
    if (route().current('clients.edit')) {
      form.put(props.submitRoute)
    } else {
      form.post(props.submitRoute)
    }
  } catch (err) {
    err.inner.forEach((element) => {
      form.setError(element.path, element.message)
    })
  }
}
</script>

<template>
  <form class="mt-6 space-y-6" novalidate @submit.prevent="submit">
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

    <div v-show="route().current('clients.edit')">
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

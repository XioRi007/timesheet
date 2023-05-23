<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import {useForm} from "@inertiajs/vue3"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import Toggle from "@/Components/Toggle.vue"
import * as yup from "yup"
import {maxDecimalPlaces} from "@/validation.js"

const props = defineProps({
  submitRoute: {
    type: String,
    required: true
  },
  developer: {
    type: Object,
    required: false,
    default: {
      first_name: '',
      last_name: '',
      rate: 1.00,
      rate_percent: 1.00,
      status: true,
    }
  },
})

const schema = yup.object({
  first_name: yup.string().required(),
  last_name: yup.string().required(),
  rate: maxDecimalPlaces(2).min(0).max(999.99).typeError('rate is required'),
  rate_percent: maxDecimalPlaces(2).min(0).max(9.99).typeError('rate percent is required'),
  status: yup.string().required().oneOf(['true', 'false']),
})
const form = useForm(props.developer)
form.defaults()
const submit = async () => {
  try {
    form.clearErrors()
    await schema.validate(form, {abortEarly: false})
    form.status = form.status === true
    if (route().current('developers.edit')) {
      form.put(props.submitRoute)
    } else {
      form.post(props.submitRoute)
    }
  } catch (err) {
    console.log(err)
    err.inner.forEach((element) => {
      form.setError(element.path, element.message)
    })
  }
}
</script>

<template>
  <form class="mt-6 space-y-6" novalidate @submit.prevent="submit">

    <div>
      <InputLabel for="name" value="First Name"/>
      <TextInput
        id="name"
        v-model="form.first_name"
        autocomplete="first_name"
        autofocus
        class="mt-1 block w-full"
        type="text"
      />
      <InputError :message="form.errors.first_name" class="mt-2"/>
    </div>

    <div>
      <InputLabel for="name" value="Last Name"/>
      <TextInput
        id="name"
        v-model="form.last_name"
        autocomplete="last_name"
        autofocus
        class="mt-1 block w-full"
        type="text"
      />
      <InputError :message="form.errors.last_name" class="mt-2"/>
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

    <div>
      <InputLabel for="rate" value="Rate percent"/>
      <TextInput
        id="rate"
        v-model="form.rate_percent"
        autocomplete="rate"
        class="mt-1 block w-full"
        step="0.01"
        type="number"
      />
      <InputError :message="form.errors.rate_percent" class="mt-2"/>
    </div>

    <div v-show="route().current('developers.edit')">
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

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import {Link, router, useForm} from "@inertiajs/vue3"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import Toggle from "@/Components/Toggle.vue"
import {onMounted} from "vue"
import * as yup from "yup"
import {maxDecimalPlaces} from "@/validation.js"
import {toast} from "vue3-toastify"

const props = defineProps({
  submitRoute: {
    type: String,
    required: true
  },
  worklog: {
    type: Object,
    required: false,
    default: {
      date: '',
      developer_id: null,
      project_id: null,
      rate: 0,
      hrs: 0,
      total: 0,
      status: false,
      created_at: Date()
    }
  },
  rate: {
    type: Number,
    required: false
  },
  developer: {
    type: Number,
    required: false
  },
  project: {
    type: Number,
    required: false
  }
})
const schema = yup.object({
  developer_id: yup.number().required(),
  project_id: yup.number().required(),
  rate: maxDecimalPlaces(2).min(0).max(999.99).typeError('rate is required'),
  hrs: maxDecimalPlaces(2).min(0.1).max(999.99).typeError('hours are required'),
  total: maxDecimalPlaces(2).min(0).max(99999999.99).typeError('total is required'),
  status: yup.string().required().oneOf(["true", "false"]),
})
const form = useForm(props.worklog)
onMounted(() => {
  form.defaults()
  update()
})
const submit = async () => {
  try {
    form.clearErrors()
    await schema.validate(form, {abortEarly: false})
    form.status = form.status === true
    if (route().current('worklogs.edit')) {
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
const updateTotal = () => {
  form.total = (form.rate * form.hrs)
}
const update = () => {
  if (props.developer) {
    form.developer_id = props.developer
  }
  if (props.project) {
    form.project_id = props.project
  }
  if (props.rate) {
    form.rate = props.rate
  }
  updateTotal()
}
const changeUrl = (e) => {
  router.visit(
    `?developer=${form.developer_id}&project=${form.project_id}`, {
      only: ['developer', 'project', 'rate'],
      preserveState: true,
      onSuccess: update
    })
}
</script>

<template>
  <div class="bg-white overflow-hidden mb-6 flex justify-end">
    <Link :href="route('worklogs.index')">
      <font-awesome-icon class="" icon="fa-solid fa-xmark" size="xl" title="Close"/>
    </Link>
  </div>
  <form class="mt-6 space-y-6" novalidate @submit.prevent="submit">
    <div>
      <InputLabel for="developer" value="Developer"/>
      <select id="developer"
              v-model="form.developer_id"
              class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
              name="developer"
              @change="changeUrl">
        <option v-for="developer in $page.props.developers" :value="developer.id">{{ developer.name }}</option>
      </select>
      <InputError :message="form.errors.developer_id" class="mt-2"/>
    </div>

    <div>
      <InputLabel for="project" value="Project"/>
      <select id="project"
              v-model="form.project_id"
              class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
              name="project"
              @change="changeUrl">
        <option v-for="project in $page.props.projects" :value="project.id">{{ project.name }}</option>
      </select>
      <InputError :message="form.errors.project_id" class="mt-2"/>
    </div>

    <div>
      <InputLabel for="rate" value="Rate"/>
      <TextInput
        id="rate"
        v-model="form.rate"
        autocomplete="rate"
        class="mt-1 block w-full"
        step=".01"
        type="number"
        @input="updateTotal"
      />
      <InputError :message="form.errors.rate" class="mt-2"/>
    </div>

    <div>
      <InputLabel for="rate" value="Hours"/>
      <TextInput
        id="hrs"
        v-model="form.hrs"
        autocomplete="rate"
        class="mt-1 block w-full"
        step=".01"
        type="number"
        @input="updateTotal"
      />
      <InputError :message="form.errors.hrs" class="mt-2"/>
    </div>

    <div>
      <InputLabel for="total" value="Total"/>
      <TextInput
        id="total"
        v-model="form.total"
        autocomplete="rate"
        class="mt-1 block w-full"
        step=".01"
        type="number"
      />
      <InputError :message="form.errors.total" class="mt-2"/>
    </div>

    <div v-show="route().current('worklogs.edit')" class="flex justify-between ">
      <Toggle
        v-model="form.status"
        active-text="Paid"
        not-active-text="Unpaid"
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

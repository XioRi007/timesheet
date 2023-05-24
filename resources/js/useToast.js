import {onMounted} from "vue"
import {toast} from "vue3-toastify"

export const useToast = () => {
  onMounted(() => {
    if (localStorage.getItem('message')) {
      let _toast = localStorage.getItem('message')
      _toast = JSON.parse(_toast)
      showToast(_toast.message, _toast.type)
      localStorage.removeItem('message')
    }
  })
}

export const createToast = (message, type='success') => {
  localStorage.setItem('message', JSON.stringify({message, type}))
}

export const showToast = (message, type='success') => {

  toast(message, {
    type: type,
    autoClose: 5000,
    position: toast.POSITION.BOTTOM_RIGHT,
  })
}

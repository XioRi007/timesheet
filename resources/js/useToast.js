import {onMounted} from "vue"
import {toast} from "vue3-toastify"

/**
 * Shows toast from localstorage
 */
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
/**
 * Saves toast on localstorage
 * @param  message
 * @param  type
 * @param  autoClose
 */
export const createToast = (message, type='success', autoClose=5000) => {
  localStorage.setItem('message', JSON.stringify({message, type}))
}

/**
 * Instantly shows toast
 * @param  message
 * @param  type
 * @param  autoClose
 */
export const showToast = (message, type='success', autoClose=5000) => {
  toast(message, {
    type: type,
    autoClose,
    position: toast.POSITION.TOP_RIGHT,
  })
}

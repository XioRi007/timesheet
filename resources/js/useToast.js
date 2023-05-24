import {onMounted} from "vue"
import {toast} from "vue3-toastify"

export function useToast(props) {
  onMounted(() => {
    if (props.message) {
      toast(props.message, {
        type: 'success',
        autoClose: 5000,
        position: toast.POSITION.BOTTOM_RIGHT,
      })
    }
  })
}
import './bootstrap'
import '../css/app.css'

import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/vue3'
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers'
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m'
import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {faBackward, faForward, faPencil, faPlus, faTrashCan} from '@fortawesome/free-solid-svg-icons'
import {defineRule} from 'vee-validate'
import AllRules from '@vee-validate/rules'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'
library.add(faBackward, faForward, faPencil, faTrashCan, faPlus)

Object.keys(AllRules).forEach(rule => {
  defineRule(rule, AllRules[rule])
})

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({el, App, props, plugin}) {
    return createApp({render: () => h(App, props)})
      .use(plugin)
      .component('font-awesome-icon', FontAwesomeIcon)
      .use(ZiggyVue, Ziggy)
      .mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})

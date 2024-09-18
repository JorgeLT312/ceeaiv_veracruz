import './bootstrap';
import '../css/app.css';
import $ from 'jquery';
import lodash from 'lodash'
window._ = lodash;

import './Plugins/HoldOn.min';
import customFilters from './Plugins/customFilters';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import AppLayout from './Layouts/AppLayout.vue'
import GuestLayout from './Layouts/GuestLayout.vue'

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import Modal from './Components/Modal.vue'
import Tabs from './Components/Tabs.vue'
import { Link } from '@inertiajs/vue3'
import Multiselect from '@vueform/multiselect'
import Notifications from '@kyvg/vue3-notification'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]

    if (name == 'Auth/Login' || name == 'Auth/Reset' || name == 'Auth/Recovery' )
    {
      page.default.layout = GuestLayout;
    } else {
      page.default.layout = page.default.layout || AppLayout;
    }

    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Notifications)
      .use(customFilters)
      .component('Modal', Modal)
      .component('Tabs', Tabs)
      .component('Link', Link)
      .component('Multiselect', Multiselect)
      .component('VueDatePicker', VueDatePicker)
      .mount(el)
  },
})

import { createApp } from 'vue'
import Main from './views/main.vue'

import './assets/main.pcss'

(() => {
  const app = createApp(Main)

  app.mount('#wifi-pass-content')
})()

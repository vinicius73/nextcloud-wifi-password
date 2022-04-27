<script lang="ts">
import { defineComponent } from 'vue'
import { useWifi } from '../state/wifi'
// import { useQRCode, QualityTypes } from '../state/qr-code'
// import { buildQRCodeData } from '../lib/qr-code'
// import IconWifi from './icons/Wifi.vue'
// import IconPrinter from './icons/Printer.vue'
// import IconShare from './icons/Share.vue'

export default defineComponent({
  name: 'WifiInfo',
  // components: { IconWifi, IconPrinter, IconShare },
  setup () {
    const { state } = useWifi()

    const print = () => {
      window.print()
    }

    return {
      print,
      state
    }
  }
})
</script>

<template>
  <section class="box">
    <header class="flex justify-center">
      <!-- <IconWifi /> -->
    </header>
    <slot />
    <hr>
    <div class="box-main">
      <slot name="main" />
    </div>
    <dl>
      <dt>Network</dt>
      <dd>{{ state.ssid }}</dd>

      <dt>Type</dt>
      <dd>{{ state.type }}</dd>

      <dt>Password</dt>
      <dd>{{ state.password }}</dd>
    </dl>
    <slot name="footer" />
  </section>
</template>

<style scoped>
dl dt {
  font-weight: normal;
  font-style: italic;
}
dl dd {
  font-weight: bold;
  font-size: 1.5em;
  font-family: monospace;
  margin-bottom: 0.8em;
  padding-left: 0.9em;
}
.action-buttons button {
  margin-right: 0.5em;
}
.action-buttons button:last-child {
  margin-right: 0;
}
.box-main {
  float: right;
}
</style>

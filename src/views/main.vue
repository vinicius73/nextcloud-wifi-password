<script lang="ts">
import { defineComponent } from 'vue'
import WifiForm from '../components/WifiForm.vue'
import WifiInfo from '../components/WifiInfo.vue'
import QRCode from '../components/QRCode.vue'

export default defineComponent({
  name: 'ViewMain',
  components: { WifiForm, WifiInfo, QRCode },
  setup () {
    return {

    }
  }
})
</script>

<template>
  <main
    id="page-main"
    class="container"
  >
    <div class="wifi-form">
      <WifiForm />
      <!-- <div class="version-text">
        <a
          href="https://github.com/vinicius73/vue-wifi-qrcode-generator"
          target="_blank"
        >v{{ version }}</a>
      </div> -->
    </div>
    <WifiInfo
      class="wifi-info"
    >
      <QRCode />
      <!-- <template #main>
        <CastButton />
      </template> -->
      <!-- <template #footer>
        <WifiInfoActions />
      </template> -->
    </WifiInfo>
  </main>
</template>

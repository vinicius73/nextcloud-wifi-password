<script lang="ts">
import { defineComponent } from 'vue'
import SSIDSelect from '../components/SSIDSelect.vue'
import WifiForm from '../components/WifiForm.vue'
import WifiInfo from '../components/WifiInfo.vue'
import QRCode from '../components/QRCode.vue'

export default defineComponent({
  name: 'ViewMain',
  components: { SSIDSelect, WifiInfo, QRCode, WifiForm },
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
    <SSIDSelect class="wifi-form">
      <hr>
      <WifiForm />
      <!-- <div class="version-text">
        <a
          href="https://github.com/vinicius73/vue-wifi-qrcode-generator"
          target="_blank"
        >v{{ version }}</a>
      </div> -->
    </SSIDSelect>
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

<style lang="pcss">
#content.app-wifi_password {
  width: 100%;
  padding-left: 1em;
  padding-right: 1em;
  @apply min-h-screen flex flex-col;
}
</style>

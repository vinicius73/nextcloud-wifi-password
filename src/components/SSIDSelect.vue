<script lang="ts">
import type { SSIDRecord } from '../domain/api'
import { isEmpty } from 'lodash-es'
import { defineComponent, reactive, onMounted, watchPostEffect } from 'vue'
import { loadList, loadWifiData } from '../domain/api'
import { useWifi } from '../state/wifi'

export default defineComponent({
  name: 'WifiForm',
  setup () {
    const state = reactive({
      ssid: '',
      list: [] as SSIDRecord[]
    })

    const { setState } = useWifi()

    const refresh = () => {
      loadList()
        .then(res => {
          state.list = res
        })
    }

    onMounted(refresh)

    watchPostEffect(() => {
      const { ssid } = state

      if (isEmpty(ssid)) {
        return
      }

      loadWifiData(ssid)
        .then(res => {
          setState(res)
        })
    })

    return {
      state,
      refresh
    }
  }
})
</script>

<template>
  <div id="wifi-form">
    <div class="ssid-select">
      <select
        v-model="state.ssid"
        name="ssid-select"
      >
        <option
          v-for="row in state.list"
          :key="row.ssid"
          :value="row.ssid"
        >
          {{ row.ssid }}
        </option>
      </select>
      <button>
        Novo
      </button>
    </div>

    <slot />
  </div>
</template>

<style scoped>
  .ssid-select {
    display: flex;
    align-items: center;
    gap: 1em;
  }
</style>

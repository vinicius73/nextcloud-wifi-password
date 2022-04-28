<script lang="ts">
import { readonly, watchEffect, defineComponent, computed } from 'vue'
import { ConnectionType } from '../lib/qr-code'
import { useWifi } from '../state/wifi'
import FormInput from './form/Input.vue'
import FormSelect from './form/Select.vue'

export default defineComponent({
  name: 'WifiInfo',
  components: { FormInput, FormSelect },
  setup () {
    const options = Object.values(ConnectionType)
    const { state, setState } = useWifi()

    const isNopass = computed(() => state.type === ConnectionType.nopass)

    const onChange = (ev: Event) => {
      const { name, value } = ev.target as HTMLInputElement

      setState({
        [name]: value
      })
    }

    watchEffect(() => {
      if (isNopass.value) {
        setState({
          password: ''
        })
      }
    })

    return {
      state: readonly(state),
      isNopass,
      options,
      onChange
    }
  }
})
</script>

<template>
  <form
    autocomplete="off"
    class="box"
    @submit.prevent
  >
    <FormInput
      :value="state.ssid"
      class="mb-4"
      name="ssid"
      label="Network name"
      placeholder="SSID"
      @input="onChange"
    />

    <FormInput
      v-if="!isNopass"
      :value="state.password"
      class="mb-4"
      name="password"
      label="Password"
      placeholder="Password"
      @input="onChange"
    />

    <FormSelect
      name="type"
      :value="state.type"
      :options="options"
      @change="onChange"
    />
  </form>
</template>

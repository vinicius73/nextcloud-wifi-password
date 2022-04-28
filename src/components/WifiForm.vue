<script lang="ts">
import { readonly, defineComponent, computed, reactive, watchEffect, ref } from 'vue'
import { ConnectionType, QRCodeData } from '../lib/qr-code'
import { useWifi } from '../state/wifi'
import FormInput from './form/Input.vue'
import FormSelect from './form/Select.vue'

export default defineComponent({
  name: 'WifiInfo',
  components: { FormInput, FormSelect },
  setup () {
    const options = Object.values(ConnectionType)
    const loading = ref(false)
    const state = reactive<QRCodeData>({
      password: '',
      ssid: '',
      type: ConnectionType.WEP
    })

    const { state: actual, saveState } = useWifi()

    const isNopass = computed(() => state.type === ConnectionType.nopass)

    const setState = (data: Partial<QRCodeData>) => {
      Object.assign(state, { ...data })
    }

    const onChange = (ev: Event) => {
      const { name, value } = ev.target as HTMLInputElement
      setState({ [name]: value })
    }

    const save = async () => {
      loading.value = true
      try {
        await saveState({ ...state })
      } catch (err) {
        console.warn(err)
      } finally {
        loading.value = false
      }
    }

    watchEffect(() => {
      setState({ ...actual })
    })

    watchEffect(() => {
      if (isNopass.value) {
        setState({ password: '' })
      }
    })

    return {
      state: readonly(state),
      loading: readonly(loading),
      isNopass,
      options,
      save,
      onChange
    }
  }
})
</script>

<template>
  <form
    autocomplete="off"
    class="box wifi-edit-form"
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

    <hr>

    <button
      class="full"
      type="button"
      :disabled="loading"
      @click="save"
    >
      Salvar
    </button>
  </form>
</template>

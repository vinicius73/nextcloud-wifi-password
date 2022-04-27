import { reactive, readonly } from 'vue'
import { QRCodeData } from '../lib/qr-code'

const state = reactive({
  ssid: '',
  password: '',
  type: 'WPA'
}) as QRCodeData

const setState = (newState: Partial<QRCodeData>) => {
  Object.assign(state, newState)
}

const useWifi = (def?: QRCodeData) => {
  if (def) {
    setState(def)
  }

  return { state: readonly(state), setState }
}

export { useWifi }

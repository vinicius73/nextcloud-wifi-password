import { reactive, readonly } from 'vue'
import { ConnectionType } from '../lib/qr-code'
import { SSIDData } from '../domain/wifi'
import { save } from '../domain/api'
import { onIdle } from '../lib/on-idle'

const state = reactive<SSIDData>({
  _key: '',
  ssid: '',
  password: '',
  type: ConnectionType.WEP
})

const setState = (newState: Partial<SSIDData>): void => {
  Object.assign(state, newState)
}

const saveState = async (newState?: Partial<SSIDData>): Promise<SSIDData> => {
  if (newState) {
    setState(newState)
  }
  return onIdle(() => save(state))
}

const useWifi = (def?: SSIDData) => {
  if (def) {
    setState(def)
  }

  return { state: readonly(state), setState, saveState }
}

export { useWifi }

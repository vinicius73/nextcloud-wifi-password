import { client } from './client'

export type SSIDRecord = {
  ssid: string
}

const loadList = () => client.get('/files')
  .then(res => res.data as SSIDRecord[])

const loadWifiData = (ssid: string) =>
  client.get(`/files/${ssid}`).then((res) => res.data as SSIDRecord)

const create = (ssid: string, password: string) =>
  client
    .post('/files', { password, ssid })
    .then((res) => res.data as SSIDRecord)

export { loadList, create, loadWifiData }

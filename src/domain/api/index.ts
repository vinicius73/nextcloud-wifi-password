import { isEmpty } from 'lodash-es'
import { client } from './client'
import { QRCodeData } from '../../lib/qr-code'
import { SSIDRecord, SSIDData } from '../wifi'

const loadList = () => client.get('/files')
  .then(res => res.data as SSIDRecord[])

const loadWifiData = (ssid: string): Promise<SSIDData> =>
  client
    .get(`/files/${ssid}`)
    .then<QRCodeData>((res) => res.data)
    .then((data) => {
      return { ...data, _key: data.ssid }
    })

const create = async ({ password, ssid, type }: SSIDData) => {
  const data: QRCodeData = {
    password,
    ssid,
    type
  }
  const res = await client
    .post('/files', data)

  return res.data as SSIDData
}

const update = async ({ password, ssid, type, _key }: SSIDData) => {
  const data: QRCodeData = {
    password,
    ssid,
    type
  }

  const res = await client.put(`/files/${_key}`, data)

  return res.data as SSIDData
}

const save = (record: SSIDData) => {
  if (isEmpty(record._key)) {
    return create(record)
  }

  return update(record)
}

export { loadList, create, loadWifiData, save, update }

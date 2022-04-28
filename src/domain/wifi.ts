import { QRCodeData } from '../lib/qr-code'

export type SSIDRecord = {
  ssid: string
}

export type SSIDData = QRCodeData & {
  _key: string;
}

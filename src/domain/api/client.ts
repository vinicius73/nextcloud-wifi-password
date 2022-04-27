import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

const baseURL = generateUrl('/apps/wifi_password/api')

axios.defaults.baseURL = baseURL

export { axios as client }

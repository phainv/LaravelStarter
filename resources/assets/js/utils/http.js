import axios from 'axios'
import interceptors from './interceptors'

export const http = axios.create({
  baseURL: process.env.MIX_API_URL || 'http://localhost'
})

export function setToken (token) {
  http.defaults.headers.common.Authorization = `Bearer ${token}`
}

export default function install (Vue) {
  interceptors(http)
  Object.defineProperty(Vue.prototype, '$http', {
    get () {
      return http
    }
  })
}

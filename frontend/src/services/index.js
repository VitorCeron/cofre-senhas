import axios from 'axios'
import { router } from '../router'
import UsersService from './users'
import UserPasswordsService from './user_passwords'

const API_ENVS = {
  production: '',
  development: '',
  local: 'http://localhost/api'
}

const httpClient = axios.create({
  baseURL: API_ENVS.local
})

httpClient.interceptors.request.use(config => {
  const token = window.localStorage.getItem('cofre_senhas_token')

  if (token) {
    config.headers.common.Authorization = `Bearer ${token}`
  }

  return config
})

// httpClient.interceptors.response.use((response) => {
//   setGlobalLoading(false)
//   return response
// }, (error) => {
//   const canThrowAnError = error.request.status === 0 || error.request.status === 500
//   if (canThrowAnError) {
//     setGlobalLoading(false)
//     throw new Error(error.message)
//   }
//   if (error.response.status === 401) {
//     router.push({ name: 'Home' })
//   }

//   setGlobalLoading(false)
//   return error
// })

export default {
  users: UsersService(httpClient),
  user_passwords: UserPasswordsService(httpClient),
}

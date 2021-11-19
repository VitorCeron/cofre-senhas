import axios from 'axios'
import { router } from '../router'
import UsersService from './users'
import UserPasswordsService from './user_passwords'
import LogsService from './logs'

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

httpClient.interceptors.response.use((response) => {
  return response
}, (error) => {
  if(error.response.status === 401) {
    window.localStorage.removeItem("cofre_senhas_token");
    window.localStorage.removeItem("cofre_senhas_user");
  }
  
  return error
})

export default {
  users: UsersService(httpClient),
  user_passwords: UserPasswordsService(httpClient),
  logs: LogsService(httpClient),
}

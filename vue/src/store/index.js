import {createStore} from 'vuex'
import axiosClient from '../axios'

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem('TOKEN')
    },
    categories: [],
    ads: [],
  },
  getters: {},
  actions: {
    register({commit}, user) {
      return axiosClient.post('/register', user)
        .then(({data}) => {
          commit('setUser', data.user)
          commit('setToken', data.token)
          return data
        })
    },
    login({commit}, user) {
      return axiosClient.post('/login', user)
        .then(({data}) => {
          commit('setUser', data.user);
          commit('setToken', data.token)
          return data;
        })
    },
    logout({commit}) {
      axiosClient.post('/logout')
        .then(response => {
          commit('logout')
          return response
        })
    },

    createAd({commit}, ad) {
      return axiosClient.post('/ads', ad)
        .then((res) => {
          return res
        })
    },

    getCategories({commit}) {
      axiosClient.get('/categories')
        .then((res) => {
          commit('setCategories', res.data)
        })
    },

    getAds({commit}) {
      return axiosClient.get('/ads')
        .then((res) => {
          commit('setAds', res.data)
          return res
        })
    },
    getAd({commit}, id) {
      return axiosClient.get(`/ads/${id}`)
        .then((res) => {
          return res.data
        })
    },
    getAuthUser({commit}) {
      return axiosClient.get('/user')
        .then((res) => {
          return res.data.data
        })
    },
    updateUser({commit}, data) {
      return axiosClient.post('/user/update', data)
        .then(res => {
          return res.data
        })
    },
  },
  mutations: {
    setUser: (state, user) => {
      state.user.data = user
    },
    setToken: (state, token) => {
      state.user.token = token
      sessionStorage.setItem('TOKEN', token)
    },
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      sessionStorage.removeItem("TOKEN");
    },
    setCategories: (state, categories) => {
      state.categories = categories
    },
    setAds: (state, ads) => {
      state.ads = ads
    },
  },
})

export default store
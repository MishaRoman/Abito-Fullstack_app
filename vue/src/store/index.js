import { createStore } from 'vuex'
import axiosClient from '../axios'

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem('TOKEN')
    },
    categories: [],
    ads: {
      meta: [],
      loading: false,
      data: [],
    },
    totalPages: null,
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

    getAds({commit}, {params}) {
      commit('setAdsLoading', true)
      return axiosClient.get('/ads', {params: {
        page: params.page,
        query: params.query,
        category: params.category
      }})
        .then((res) => {
          commit('setAdsLoading', false)
          commit('setAds', res.data)
        })
    },

    // get ads but through middleware auth, if you don't do it likes will not displaying correct
    getAuthAds({commit}, {params}) {
      commit('setAdsLoading', true)
      return axiosClient.get('/auth/ads', {params: {
        page: params.page,
        query: params.query,
        category: params.category
      }})
        .then((res) => {
          commit('setAdsLoading', false)
          commit('setAds', res.data)
        })
    },

    getMoreAds({commit}, {params}) {
      commit('setAdsLoading', true)
      return axiosClient.get('/ads', {params: {
        page: params.page,
        query: params.query,
        category: params.category
      }})
        .then((res) => {
          commit('setAdsLoading', false)
          commit('setMoreAds', res.data)
        })
    },
    // get ads but through middleware auth, if you don't do it likes will not displaying correct
    getMoreAuthAds({commit}, {params}) {
      commit('setAdsLoading', true)
      return axiosClient.get('/auth/ads', {params: {
        page: params.page,
        query: params.query,
        category: params.category
      }})
        .then((res) => {
          commit('setAdsLoading', false)
          commit('setMoreAds', res.data)
        })
    },

    getAd({commit}, id) {
      return axiosClient.get(`/ads/${id}`)
        .then((res) => {
          return res.data
        })
    },
    getAdsByAuthor({commit}, params) {
      return axiosClient.get(`/ads/author/${params.authorId}/${params.adId}`)
        .then(res => {
          return res.data
        })
    },
    getAuthUser({commit}) {
      return axiosClient.get('/user')
        .then((res) => {
          return res.data.data
        })
    },
    getUserAds({commit}, userId) {
      return axiosClient.get(`/user/${userId}`)
        .then((res) => {
          return res.data
        })
    },
    getAuthUserAds({commit}) {
      return axiosClient.get('/user/ads')
        .then((res) => {
          return res.data
        })
    },
    getFavorites({commit}) {
      return axiosClient.get('/favorites')
        .then(res => {
          return res.data
        })
    },

    updateUser({commit}, data) {
      return axiosClient.post('/user/update', data)
        .then(res => {
          return res.data
        })
    },
    addToFavorites({commit}, id) {
      return axiosClient.post(`/favorite/${id}`)
        .then(res => {
          return res
        })
    },
    removeFromFavorites({commit}, id) {
      return axiosClient.post(`/unfavorite/${id}`)
        .then(res => {
          return res
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
      state.ads.data = ads.data
      state.ads.meta = ads.meta
    },
    setMoreAds: (state, ads) => {
      state.ads.data = [...state.ads.data, ...ads.data]
    },
    setAdsLoading: (state, loading) => {
      state.ads.loading = loading;
    },
  },
})

export default store
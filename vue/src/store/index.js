import { createStore } from 'vuex'
import axiosClient from '../axios'

const store = createStore({
  state: {
    user: {
      data: {
        id: sessionStorage.getItem('userId')
      },
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
          commit('setUser', data)
          return data
        })
    },
    login({commit}, user) {
      return axiosClient.post('/login', user)
        .then(({data}) => {
          commit('setUser', data);
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

    editAd({commit}, data) {
      return axiosClient.post(`ads/${data.id}/edit`, data)
        .then(res => {
          return res
        })
    },

    deleteAd({commit}, id) {
      return axiosClient.delete(`ads/${id}/destroy`)
        .then(res => {
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

    getAd({commit}, id) {
      return axiosClient.get(`/ads/${id}`)
        .then((res) => {
          return res.data
        })
    },
    getComments({commit}, id) {
      return axiosClient.get(`/ads/${id}/comments`)
        .then((res) => {
          return res.data
        })
    },
    addComment({commit}, params) {
      return axiosClient.post(`/ads/${params.adId}/comments`, {body: params.body})
        .then(res => {
          return res
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

    follow({commit}, userId) {
      return axiosClient.post(`/follow/${userId}`)
        .then(res => {
          return res
        })
    },
    unfollow({commit}, userId) {
      return axiosClient.post(`/unfollow/${userId}`)
        .then(res => {
          return res
        })
    },
    
  },
  mutations: {
    setUser: (state, data) => {
      sessionStorage.setItem('userId', data.user.id)
      sessionStorage.setItem('TOKEN', data.token)
    },
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      sessionStorage.removeItem("TOKEN");
      sessionStorage.removeItem("userId");
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
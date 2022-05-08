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
    sortedAds: [],
    favorites: [],
  },
  getters: {
    ads(state) {
      if (state.sortedAds.length) {
        return state.sortedAds
      } else {
        return state.ads
      }
    }
  },
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
        })
    },
    getAd({commit}, id) {
      return axiosClient.get(`/ads/${id}`)
        .then((res) => {
          return res.data
        })
    },
    getAdsByAuthor({commit}, id) {
      return axiosClient.get(`/ads/author/${id}`)
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
    getUserAds({commit}) {
      return axiosClient.get('/user/ads')
        .then((res) => {
          return res.data
        })
    },
    updateUser({commit}, data) {
      return axiosClient.post('/user/update', data)
        .then(res => {
          return res.data
        })
    },
    getFavorites({commit}) {
      return axiosClient.get('/favorites')
        .then(res => {
          commit('setFavorites', res.data)
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
    sortByCategory({commit}, id) {
      commit('sortAdsByCategory', id)
    },
    sortBySearch({commit}, searchQuery) {
      commit('sortAdsBySearch', searchQuery)
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
    setFavorites: (state, favorites) => {
      state.favorites = favorites.data
    },
    setAds: (state, ads) => {
      state.ads = ads.data
    },
    sortAdsByCategory: (state, id) => {
      if(state.sortedAds.length) {
        state.sortedAds = state.sortedAds.filter(ad => ad.category_id == id)
      } else {
        state.sortedAds = state.ads.filter(ad => ad.category_id == id)
      }
    },
    sortAdsBySearch: (state, searchQuery) => {
      if(!searchQuery) {
        return
      }
      if(state.sortedAds.length) {
        state.sortedAds = state.sortedAds.filter(ad => ad.title.toLowerCase().includes(searchQuery.toLowerCase()))
      } else {
        state.sortedAds = state.ads.filter(ad => ad.title.toLowerCase().includes(searchQuery.toLowerCase()))
      }
    }
  },
})

export default store
import {createStore} from 'vuex'
import axios from 'axios'

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem('TOKEN')
    }
  },
  getters: {},
  actions: {
    register({commit}, user) {
      axios.post('http://127.0.0.1:8000/api/register', user)
        .then(({data}) => {
          commit('setUser', data.user)
          commit('setToken', data.token)
          return data
        })
    },
    login({commit}, user) {
      console.log(user);
      axios.post('http://127.0.0.1:8000/api/login', user)
        .then(({data}) => {
          commit('setUser', data.user)
          commit('setToken', data.token)
          return data
        })
    },
    logout({commit}) {
      commit('logout')
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
  },
})

export default store
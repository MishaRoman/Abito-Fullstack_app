<template>
  <div class="modal modal-overlay">
    <div class="login-form">
      <div class="options">
        <a
          id="login"
          class="option form-title form-link"
          :class="isLoginFormActive ? 'active' : ''"
          @click="openLoginForm"
          >Log in
        </a>
        <a
          id="signup"
          class="option form-title form-link"
          :class="!isLoginFormActive ? 'active' : ''"
          @click="openSignUpForm"
          >Sign up
        </a>
        <a class="close-modal" @click="closeAuthModal">
          <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px" height="24px" >
            <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/>
          </svg>
        </a>
      </div>

      <form v-if="isLoginFormActive" @submit="login">
        <span class="form-subtitle"
          >Login to account with your email and password
        </span>
        <input
          class="form-input"
          type="email"
          placeholder="email"
          name="email"
          required
          v-model="user.email"
        /><br />
        <input
          class="form-input"
          type="password"
          placeholder="password"
          name="password"
          required
          v-model="user.password"
        /><br />
        <button class="button btn-submit" role="button" type="submit" :disabled="loading">
          Log in
          <span class="loading" v-if="loading"></span>
        </button>

        <!-- Login errors block -->
        <div v-if="Object.keys(loginErrors).length">
          <ul v-for="(field, i) of Object.keys(loginErrors)" :key="i">
            <li v-for="(error, ind) of loginErrors[field] || []" :key="ind">{{error}}</li>
          </ul>
        </div>

      </form>

      <form v-else @submit="register">
        <span class="form-subtitle">Register for free</span>
        <input
          class="form-input"
          type="text"
          placeholder="fullname"
          name="name"
          required
          v-model="user.name"
        />
        <br />
        <input
          class="form-input"
          type="email"
          placeholder="email"
          name="email"
          required
          v-model="user.email"
        />
        <br />
        <input
          class="form-input"
          type="number"
          placeholder="Phone number"
          name="phone_number"
          required
          v-model.number="user.phone_number"
        />
        <br />
        <input
          class="form-input"
          type="password"
          placeholder="password"
          name="password"
          required
          v-model="user.password"
        /><br />
        <input
          class="form-input"
          type="password"
          placeholder="confirm password"
          name="password_confirmation"
          required
          v-model="user.password_confirmation"
        /><br />

        <button class="button btn-submit" role="button" type="submit" :disabled="loading">
          Register
          <span class="loading" v-if="loading"></span>
        </button>

        <!-- Registration errors block -->
        <div v-if="Object.keys(signupErrors).length">
          <ul v-for="(field, i) of Object.keys(signupErrors)" :key="i">
            <li v-for="(error, ind) of signupErrors[field] || []" :key="ind">{{error}}</li>
          </ul>
        </div>
        
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

const isLoginFormActive = ref(true)
const loading = ref(false)

let loginErrors = ref({})
let signupErrors = ref({})

const user = {
  name: '',
  email: '',
  phone_number: null,
  password: '',
  password_confirmation: ''
}

const emit = defineEmits(['closeAuthModal'])

const closeAuthModal = () => emit('closeAuthModal')
const openAuthModal = () => emit('openAuthModal')

const openLoginForm = () => isLoginFormActive.value = true
const openSignUpForm = () => isLoginFormActive.value = false

const register = (e) => {
  e.preventDefault()
  loading.value = true
  store.dispatch('register', user)
   .then(() => {
     loading.value = false
     closeAuthModal()
     location.reload();
   })
   .catch(err => {
    loading.value = false
    signupErrors.value = err.response.data.error.message
   })
}

const login = (e) => {
  e.preventDefault()
  loading.value = true
  store.dispatch('login', {'email': user.email, 'password': user.password})
    .then(() => {
      loading.value = false
      closeAuthModal()
      location.reload();
    })
    .catch(err => {
      loginErrors.value = err.response.data.error.message
      loading.value = false
   })
}

</script>

<style scoped>
li {
  color: red
}
.loading {
  display: inline-block;
  width: 7px;
  height: 7px;
  margin-left: 5px;
  border: 4px solid transparent;
  border-top-color: #ffffff;
  border-radius: 50%;
  animation: button-loading-spinner 1s ease infinite;
}
@keyframes button-loading-spinner {
  from {
    transform: rotate(0turn);
  }

  to {
    transform: rotate(1turn);
  }
}
button:disabled {
  opacity: 0.5;
}
</style>
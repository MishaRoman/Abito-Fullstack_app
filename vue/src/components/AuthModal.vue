<template>
  <div class="modal modal-overlay">
    <div class="login-form">
      <div class="options">
        <a
          @click="openLoginForm"
          class="option form-title form-link"
          :class="isLoginFormActive ? 'active' : ''"
          id="login">
          Log in
        </a>
        <a
          @click="openSignUpForm"
          class="option form-title form-link"
          :class="!isLoginFormActive ? 'active' : ''"
          id="signup">
          Sign up
        </a>
        <a class="close-modal" @click="closeAuthModal">
          <svg
            fill="#000000"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            width="24px"
            height="24px"
          >
            <path
              d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
            />
          </svg>
        </a>
      </div>

      <form v-if="isLoginFormActive" @submit="login">
        <span class="form-subtitle"
          >Login to account with your email and password</span
        >
        <input
         class="form-input"
         type="email"
         placeholder="email"
         name="email"
         v-model="user.email"
        /><br />
        <input
          class="form-input"
          type="password"
          placeholder="password"
          name="password"
          v-model="user.password"
        /><br />
        <button class="button btn-submit" role="button" type="submit">
          Log in
        </button>
      </form>

      <form v-else @submit="register">
        <span class="form-subtitle">Register for free</span>
        <input
         class="form-input"
         type="text"
         placeholder="fullname"
         name="name"
         v-model="user.name"
        />
        <br />
        <input
         class="form-input"
         type="email"
         placeholder="email"
         name="email"
         v-model="user.email"
        />
        <br />
        <input
          class="form-input"
          type="password"
          placeholder="password"
          name="password"
         v-model="user.password"
        /><br />
        <input
          class="form-input"
          type="password"
          placeholder="confirm password"
          name="password_confirmation"
         v-model="user.password_confirmation"
        /><br />
        <button class="button btn-submit" role="button" type="submit">
          Register
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import {ref} from 'vue'
import store from '../store'

const isLoginFormActive = ref(true)
const openLoginForm = () => isLoginFormActive.value = true
const openSignUpForm = () => isLoginFormActive.value = false
const emit = defineEmits(['closeAuthModal'])

function closeAuthModal() {
  emit('closeAuthModal')
}

const user = {
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
}

const register = (e) => {
  e.preventDefault()
  store.dispatch('register', user)
   .then(() => {
     closeAuthModal()
   })
}

const login = (e) => {
  e.preventDefault()
  store.dispatch('login', {'email': user.email, 'password': user.password})
   .then(() => {
     closeAuthModal()
   })
}

</script>
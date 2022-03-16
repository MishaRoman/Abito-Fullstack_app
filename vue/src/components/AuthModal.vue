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

      <form v-if="isLoginFormActive">
        <span class="form-subtitle"
          >Login to account with your email and password</span
        >
        <input class="form-input" type="email" placeholder="email" /><br />
        <input
          class="form-input"
          type="password"
          placeholder="password"
        /><br />
        <button class="button btn-submit" role="button" type="submit">
          Log in
        </button>
      </form>

      <form v-else>
        <span class="form-subtitle">New here?</span>
        <input class="form-input" type="text" placeholder="fullname" /><br />
        <input class="form-input" type="email" placeholder="email" /><br />
        <input
          class="form-input"
          type="password"
          placeholder="password"
        /><br />
        <input
          class="form-input"
          type="password"
          placeholder="confirm password"
        /><br />
        <button class="button btn-submit" role="button" type="submit">
          Sign up
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import {ref} from 'vue'

const isLoginFormActive = ref(true)

const openLoginForm = () => isLoginFormActive.value = true
const openSignUpForm = () => isLoginFormActive.value = false
const emit = defineEmits(['closeAuthModal'])

function closeAuthModal() {
  emit('closeAuthModal')
}

</script>
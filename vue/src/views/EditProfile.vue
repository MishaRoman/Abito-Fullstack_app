<template>
  <div class="container">
    <hr>
    <h2 class="main-title">Profile settings</h2>
    <div class="main-wrapper">
      <div class="img-wrapper">
        <img class="img" src="../assets/img/no_avatar.png">
        <div class="file-input">
          <label for="image" class="file-input-label">Edit Image</label>
          <input type="file" id="image"/>
        </div>
      </div>
      <div class="inputs-block">
        <div class="input-field">
          <label for="name" class="label">Name</label><br>
          <input
           type="text"
           id="name"
           name="name"
           class="input"
           v-model="user.name"
          >
          <label for="phone_number" class="label">Phone number</label>
          <br>
          <input type="tel"
           id="phone_number"
           name="phone_number" 
           class="input" 
           v-model.number="user.phone_number"
          >
          <div class="save">
            <button class="save-button" @click="updateUser">Save changes</button>
          </div>

          <div class="success-message" v-if="successMsg">
            <span>{{successMsg}}</span>
          </div>
          <p v-if="Object.keys(errors).length" class="errors-block">
            <ul v-for="(field, i) of Object.keys(errors)" :key="i">
              <li v-for="(error, ind) of errors[field] || []" :key="ind">{{error}}</li>
            </ul>
          </p>
        </div>
      </div>
    </div>
    
    <div class="logout">
      <button class="logout-button" @click.prevent="logout">Logout</button>
    </div>
  </div>
</template>

<script setup>
import store from '../store'
import {ref} from 'vue'
import {useRouter} from 'vue-router'

const router = useRouter()

const user = ref({
  name: '',
  phone_number: null,
})

const successMsg = ref('')
const errors = ref({})

store.dispatch('getAuthUser')
  .then(data => {
    user.value.name = data.name
    user.value.phone_number = data.phone_number
  })

const updateUser = () => {
  const data = {
    name: user.value.name,
    phone_number: user.value.phone_number
  }
  store.dispatch('updateUser', data)
    .then(res => {
      successMsg.value = res.message
    })
    .catch(err => {
      errors.value = err.response.data.error.message
    })
}

const logout = () => {
  if(confirm('Are you sure you want to logout?')) {
    store.dispatch('logout').then(router.push('/'))
  }
}
</script>

<style scoped>
.main-wrapper {
  display: flex;
  margin-top: 30px;
}
.img-wrapper {
  width: 250px;
  margin-right: 50px;
}
.main-title {
  text-align: center;
}
.img {
  border-radius: 99%;
  width: 250px;
  height: 250px;
}
.inputs-block {
  display: flex;
  flex: 0 0 ;
}
.errors-block ul {
  padding: 0;
  color: red;
}
.file-input {
  margin-top: 15px;
  text-align: center;
}
.input-field {
  text-align: center;
}
.file-input-label {
  background: #256EEB;
  border-radius: 5px;
  display: inline-block;
  color: #fff;
  padding: 10px 15px;
  cursor: pointer;
}
.label {
  font-size: 16px;
}
.input {
  display: block;
  font-size: 16px;
  padding: 8px 5px;
  color: rgb(15, 20, 25);
  border: 2px solid #256EEB;
  border-radius: 5px;
  width: 300px;
  margin-top: 5px;
  margin-bottom: 15px;
}
.logout {
  text-align: center;
  margin-top: 30px;
}
.logout-button {
  background: red;
  color: #fff;
  border-radius: 15px;
  padding: 12px 35px;
  font-size: 18px;
  cursor: pointer;
  text-decoration: none;
  border: none;
}
.save {
  text-align: center;
}
.save-button {
  background: #256EEB;
  color: #fff;
  border-radius: 15px;
  padding: 12px 20px;
  font-size: 16px;
  cursor: pointer;
  text-decoration: none;
  border: none;
}

.success-message {
  margin-top: 20px;
  color: green;
  font-weight: 700;
}

input[type="file"] {
    display: none;
}
@media (max-width: 768px) {
  .main-wrapper {
    display: block;
  }
  .img-wrapper {
    width: 100%;
    text-align: center;
  }
  .input-field {
    margin-top: 20px;
  }
  .input {
    display: inline;
  }
}
</style>
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
          <input type="text" id="name" name="name" class="input">
          <div class="save">
            <button class="save-button">Save changes</button>
          </div>
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
import {useRouter} from 'vue-router'

const router = useRouter()

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
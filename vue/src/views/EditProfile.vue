<template>
  <div class="container">
    <hr>
    <h2 class="main-title">Profile settings</h2>
    <div class="main-wrapper">
      <!-- Image input -->
      <div class="img-wrapper">
        <img class="img" :src="user.image_url">
        <div class="file-input">
          <label for="image" class="file-input-label">Edit Image</label>
          <input type="file" id="image" @change="onImageChoose"/>
        </div>
      </div>

      <div class="inputs-block">
        <div class="input-fields">
          <div class="input-field">
            <div class="edit">
              <label for="name" class="label">Name</label>
              <input
                type="text"
                id="name"
                name="name"
                class="input"
                required
                :disabled="isNameDisabled"
                :autofocus="isNameDisabled"
                v-model="user.name"
                @change="isSaveButtonDisabled = false"
              >
            </div>
            <button
              class="edit-button"
              @click="isNameDisabled = false"
              :disabled="!isNameDisabled"
              >Edit
            </button>
          </div>

          <div class="save">
            <button
              class="save-button"
              :disabled="isSaveButtonDisabled"
              @click="updateUser"
              >Save changes
            </button>
          </div>

          <div class="success-message" v-if="successMsg">
            <span>{{successMsg}}</span>
          </div>

          <!-- Errors block -->
          <Errors v-if="Object.keys(errors).length" :errors="errors"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Errors from '../components/Errors.vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { ref } from 'vue'

const router = useRouter()
const store = useStore()

const user = ref({
  name: '',
  image: '',
  image_url: '',
})

let isNameDisabled = ref(true)
let isSaveButtonDisabled = ref(true)

const successMsg = ref('')
const errors = ref({})

store.dispatch('getAuthUser')
  .then(data => {
    user.value.name = data.name
    user.value.image_url = data.image_url
  })

function onImageChoose(ev) {
  const file = ev.target.files[0]

  const reader = new FileReader()
  reader.onload = () => {
    user.value.image = reader.result

    user.value.image_url = reader.result

    ev.target.value = ''
  }
  reader.readAsDataURL(file)
  isSaveButtonDisabled.value = false
}

function updateUser () {
  const data = {
    name: user.value.name,
    image: user.value.image,
  }
  
  store.dispatch('updateUser', data)
    .then(res => {
      successMsg.value = res.message
    })
    .catch(err => {
      errors.value = err.response.data.error.message
    })
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

.file-input {
  margin-top: 15px;
  text-align: center;
}
.input-field {
  display: flex;
  text-align: center;
  align-items: flex-end;
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

.edit-button {
  background: #256EEB;
  border-radius: 5px;
  display: inline-block;
  color: #fff;
  padding: 10px 15px;
  cursor: pointer;
  height: 42px;
  margin-bottom: 14px;
  margin-left: 6px;
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
  .input-fields {
    margin-top: 20px;
  }
  .input {
    display: inline;
  }
}
</style>
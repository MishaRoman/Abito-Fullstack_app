<template>
  <div class="container">
    <hr>
    <div class="form-titles">
      <h2>Create ad</h2>
      <h4>Fill the fields below</h4>
    </div>

    <form class="create-form" @submit.prevent="createAd">
      <div class="inputs">
        <label for="title">Title</label>
        <input
          type="text"
          name="title"
          class="input"
          placeholder="title"
          required
          v-model="ad.title"
        >
        <label for="description">Description</label>
        <textarea
          type="text"
          name="desctiption"
          class="text-input"
          rows="7"
          required
          v-model="ad.description">
        </textarea>
        <label for="price">Price in $</label>
        <input
          type="number"
          name="price"
          class="input price-input"
          placeholder="Price"
          required
          v-model="ad.price"
        >

        <label for="category">Choose a category</label>
        <select
          name="category"
          class="create-form__select"
          value="Choose"
          required
          v-model="ad.category"
        >
          <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
            >{{category.title}}
          </option>
          
        </select>

        <label for="address">Address</label>
        <input
          type="text"
          name="address"
          class="input"
          placeholder="Address"
          required
          v-model="ad.address"
        >

        <p v-if="Object.keys(errors).length" class="errors-block">
          <ul v-for="(field, i) of Object.keys(errors)" :key="i">
            <li v-for="(error, ind) of errors[field] || []" :key="ind">{{error}}</li>
          </ul>
        </p>

        <button class="create-form__button" :disabled="loading">
          Create
          <span class="loading" v-if="loading"></span>
        </button>
      </div>
      <div class="images-input">
        <div class="input-label">
          <label for="image" class="file-input-label">Add Images <small>(maximum 4)</small></label>
          <input id="image" type="file" multiple @change="saveImages" accept="image/jpeg, image/png, image/jpg">
        </div>
        <div class="images-preview" v-if="imagesPreview">
          <div
            v-for="(img, idx) in imagesPreview"
            :key="idx"
            class="thumbnail-wrapper">
            <svg
              class="remove-image"
              @click="removeImg(idx)"
              fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px" height="24px">
              <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/>
            </svg>
            <img :src="img" class="thumbnail">
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { computed, ref } from 'vue'

const router = useRouter()
const store = useStore()

const categories = computed(() => store.state.categories)

const errors = ref({})
const imagesPreview = ref([])
const loading = ref(false)

const ad = {
  title: '',
  description: '',
  price: null,
  category: null,
  address: '',
  images: []
}

const saveImages = (e) => {
  const files = e.target.files

  for (let img of files) {
    if (!img.type.match("image")) continue; // ONLY PHOTOS (SKIP CURRENT ITERATION IF NOT A PHOTO)
    
    const reader = new FileReader()
    reader.onload = () => {
      if(!ad.images.includes(reader.result) && ad.images.length < 4) { // Maximum 4 images
        ad.images.push(reader.result)
        imagesPreview.value.push(reader.result)
      }

    }
    reader.readAsDataURL(img)
  }
}
const removeImg = (index) => {
  ad.images.splice(index)
  imagesPreview.value.splice(index, 1)
} 
const createAd = () => {
  loading.value = true
  store.dispatch('createAd', ad)
   .then(res => {
     router.push({name: 'single', params: {id: res.data.ad.id}})
   })
   .catch(err => {
     loading.value = false
     errors.value = err.response.data.error.message
   })
}


</script>

<style>
.inputs {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-size: 18px;
}
.create-form {
  display: flex;
}
.input {
  display: block;
  font-size: 16px;
  padding: 10px 5px;
  color: rgb(15, 20, 25);
  border: 2px solid #256EEB;
  border-radius: 5px;
  width: 300px;
  margin-bottom: 15px;
}
.text-input {
  border: 2px solid #256EEB;
  border-radius: 4px;
  outline: 0;
  transition: 0.2s;
  margin-bottom: 10px;
  width: 350px;
}
.price-input {
  width: 150px;
}

.create-form__select {
  width: 120px;
  margin-top: 5px;
  margin-bottom: 15px;
  height: 30px;
}

.images-input {
  align-self: flex-start;
  border-radius: 10px;
  margin-left: 100px;
  min-height: 300px;
  width: 400px;
  border-style: dashed;
  border-width: 3px;
  padding: 20px;
}
.input-label {
  text-align: center;
}
.images-preview {
  display: flex;
  flex-wrap: wrap;
}
.thumbnail-wrapper {
  margin: 0 25px;
}
.thumbnail {
  width: 362px;
}
.remove-image {
  cursor: pointer;
  position: relative;
  left: 334px;
  top: 32px;
  background-color: #fff;
  border: 2px solid #256EEB;
}
.file-input-label {
  background: #256EEB;
  border-radius: 5px;
  display: inline-block;
  color: #fff;
  padding: 10px 15px;
  cursor: pointer;
}

.create-form__button {
  display: block;
  background-color: #256EEB;
  margin-top: 10px;
  padding: 14px 35px;
  border-radius: 9999px;
  font-size: 16px;
  font-weight: 700;
  color: #ffffff;
  align-self: center;
  cursor: pointer;
}

.errors-block {
  margin-left: 15px;
}
.errors-block ul {
  padding: 0;
  color: red;
}


input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type="number"] {
  -moz-appearance: textfield;
}
input[type="number"]:hover,
input[type="number"]:focus {
  -moz-appearance: number-input;
}
input[type="file"] {
    display: none;
}

@media (max-width: 992px) {
  .create-form {
    flex-flow: column nowrap;
  }
  .images-input {
    margin-left: 0;
  }
  .inputs {
    margin-bottom: 20px;
  }
}
</style>
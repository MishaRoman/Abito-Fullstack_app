<template>
  <div class="container">
    <hr>
    <div class="form-titles">
      <h2>Create ad</h2>
      <h4>Fill the fields below</h4>
    </div>
    <form class="create-form" @submit.prevent="createAd">
      <label for="title">Title</label>
      <input type="text" name="title" class="input" placeholder="title" v-model="ad.title">

      <label for="description">Description</label>
      <textarea type="text"
       name="desctiption"
       class="text-input"
       rows="7"
       v-model="ad.description"></textarea>

      <label for="price">Price in $</label>
      <input type="number" name="price" class="input price-input" placeholder="Address" v-model="ad.price">

      <label for="category">Choose a category</label>
      <select name="category" class="create-form__select" value="Choose" v-model="ad.category">
        <option
         v-for="category in categories"
         :key="category.id"
         :value="category.id"
         >{{category.title}}</option>
        
      </select>

      <label for="address">Address</label>
      <input type="text" name="address" class="input" placeholder="Address" v-model="ad.address">
      
      <button class="button create-form__button">Create</button>
    </form>
  </div>
</template>

<script setup>
import {computed} from 'vue'
import store from '../store'

const categories = computed(() => store.state.categories)

const ad = {
  title: '',
  description: '',
  price: 0,
  category: null,
  address: '',
}

const createAd = () => store.dispatch('createAd', ad)

</script>

<style scoped>
label {
  margin-bottom: 5px;
  font-size: 18px;
}
.create-form {
  display: flex;
  flex-direction: column;
  max-width: 300px;
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
</style>
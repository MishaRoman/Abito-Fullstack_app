<template>
  <div class="container">
    <hr>
    <div class="form-titles">
      <h2>Edit ad</h2>
    </div>

    <form class="create-form" @submit.prevent="editAd">
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
          Edit
          <span class="loading" v-if="loading"></span>
        </button>
      </div>
      
    </form>
  </div>
</template>

<script setup>
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
import { ref } from 'vue'

const store = useStore()
const route = useRoute()
const router = useRouter()

const errors = ref({})
const loading = ref(true)

const ad = ref({
  id: null,
  title: '',
  description: '',
  price: null,
  address: '',
})

store.dispatch('getAd', route.params.id)
  .then(res => {
    // If user is not owner redirect to index
    if (res.data.author.id != store.state.user.data.id) router.push({name: 'index'})

    ad.value.id = res.data.id
    ad.value.title = res.data.title
    ad.value.description = res.data.description
    ad.value.price = res.data.price
    ad.value.address = res.data.address

    loading.value = false
  })

const editAd = () => {
  loading.value = true
  store.dispatch('editAd', ad.value)
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

</style>
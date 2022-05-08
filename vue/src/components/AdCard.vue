<template>
  <div class="card">
    <router-link class="card-link" :to="{name: 'single', params: {id: ad.id}}">
      <img :src="ad.preview" class="card-image" />
      
    </router-link>
    <div class="card-header">
      <router-link class="card-link" :to="{name: 'single', params: {id: ad.id}}">
        <h3 class="card-title">{{ad.title}}</h3>
      </router-link>
      <div class="favorite" v-if="store.state.user.token">
        <a v-if="!ad.favorited" class="favorite_btn" @click="addToFavorites(ad.id)">
          <svg :class="isFavorited ? 'favorited': ''" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402m5.726-20.583c-2.203 0-4.446 1.042-5.726 3.238-1.285-2.206-3.522-3.248-5.719-3.248-3.183 0-6.281 2.187-6.281 6.191 0 4.661 5.571 9.429 12 15.809 6.43-6.38 12-11.148 12-15.809 0-4.011-3.095-6.181-6.274-6.181"/></svg>
        </a>
        <a v-else class="favorite_btn" @click="removeFromFavorites(ad.id)">
          <svg :class="isFavorited ? 'favorited': ''" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402m5.726-20.583c-2.203 0-4.446 1.042-5.726 3.238-1.285-2.206-3.522-3.248-5.719-3.248-3.183 0-6.281 2.187-6.281 6.191 0 4.661 5.571 9.429 12 15.809 6.43-6.38 12-11.148 12-15.809 0-4.011-3.095-6.181-6.274-6.181"/></svg>
        </a>
      </div>
    </div>
    <strong class="card-price">{{ad.price}} $</strong>
    <p class="card-text">{{ad.address}}</p>
    <p class="card-text">{{ad.created_at}}</p>
    
  </div>
</template>

<script setup>
import store from '../store'
import {computed} from 'vue'

const { ad } = defineProps({
  ad: Object
})

const isFavorited = computed(() => ad.favorited)

function addToFavorites(id) {
  store.dispatch('addToFavorites', id)
  ad.favorited = true
}
function removeFromFavorites(id) {
  store.dispatch('removeFromFavorites', id)
  ad.favorited = false
}
</script>

<style scoped>
.favorite {
  margin-top: 15px;
  cursor: pointer;
}
.favorited {
  fill: red;
}
</style>
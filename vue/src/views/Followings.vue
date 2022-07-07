<template>
  <div class="container page-content">
    <hr>
    <h2 class="page-title">Followings</h2>
    <div class="cards">
      <AdCard 
        v-for="ad in followings"
        :key="ad.id"
        :ad="ad"
      />
    </div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import { useStore } from 'vuex'
import { ref } from 'vue'

const store = useStore()

const followings = ref([])

store.dispatch('getFollowings')
  .then(res => {
    followings.value = res.data
  })

</script>
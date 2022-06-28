<template>
  <div class="container page-content">
    <hr>
    <h2 class="page-title">Your Ads</h2>
    <div class="cards">
      <AdCard 
        v-for="ad in ads"
        :key="ad.id"
        :ad="ad"
        :canEdit="true"
      />
    </div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import { useStore } from 'vuex'
import { ref } from 'vue'

const store = useStore()

const ads = ref([])
store.dispatch('getAuthUserAds')
  .then(res => {
    ads.value = res.data
  })

</script>

<template>
  <div class="container page-content">
    <main class="main">
      <h2 class="page-title">Recommendations for you</h2>
      <div class="cards">
        <AdCard
          v-for="ad in ads.data"
          :key="ad.index"
          :ad="ad"
        />
      </div>
    </main>
  </div>
</template>

<script setup>
import AdCard from './AdCard.vue'
import store from '../store'
import { computed } from 'vue'

const ads = computed(() => store.state.ads)

store.dispatch('getAds')

</script>
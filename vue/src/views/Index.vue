<template>
  <SearchPanel />
  <div class="container page-content">
    <main class="main">
      <h2 class="page-title">Recommendations for you</h2>
      <div class="cards">
        <AdCard
          v-for="ad in ads"
          :key="ad.index"
          :ad="ad"
        />
      </div>
    </main>
  </div>
</template>

<script setup>
import SearchPanel from '../components/SearchPanel.vue'
import AdCard from '../components/AdCard.vue'
import store from '../store'
import { computed } from 'vue'

const ads = computed(() => store.getters.ads)

store.dispatch('getAds')

</script>
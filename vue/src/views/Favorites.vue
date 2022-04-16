<template>
  <SearchPanel />
  <div class="container page-content">
    <h2 class="page-title">Favorites</h2>
    <div class="cards">
      <AdCard 
        v-for="ad in favorites"
        :key="ad.id"
        :ad="ad"
      />
    </div>
  </div>
</template>

<script setup>
import SearchPanel from '../components/SearchPanel.vue'
import AdCard from '../components/AdCard.vue'

import store from '../store'
import {computed} from 'vue'

store.dispatch('getFavorites')
const favorites = computed(() => store.state.favorites)

</script>
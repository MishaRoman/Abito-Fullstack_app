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
import { useStore } from 'vuex'
import { ref } from 'vue'

const store = useStore()

const favorites = ref([])

store.dispatch('getFavorites')
  .then(data => {
    favorites.value = data.data
  })

</script>
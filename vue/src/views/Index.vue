<template>
  <SearchPanel
    @filterAds="filterAds"
  />
  <div class="container page-content">
    <main class="main">
      <h2 class="page-title">Recommendations for you</h2>
      <div class="spinner" v-if="loading && !ads.length"></div>
      <div class="cards" v-else>
        <AdCard
          v-for="ad in ads"
          :key="ad.index"
          :ad="ad"
        />
      </div>
    </main>
    <div v-intersection="loadMoreAds" class="observer" :class="loading && ads.length ? 'spinner': '' "></div>
  </div>
</template>

<script setup>
import SearchPanel from '../components/SearchPanel.vue'
import AdCard from '../components/AdCard.vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { computed, ref } from 'vue'

const router = useRouter()
const store = useStore()

const ads = computed(() => store.state.ads.data)
const loading = computed(() => store.state.ads.loading)
const totalPages = computed(() => store.state.ads.meta.last_page)

const page = ref(1)

const loadAds = () => {
  page.value = 1
  store.dispatch('getAds', {params: {page: 1}})
}

const loadMoreAds = () => {
  if (page.value >= totalPages.value) return

  page.value += 1
  store.dispatch('getMoreAds', {params: {
    page: page.value,
  }})
}

function filterAds(searchQuery) {
  router.push({name: 'filteredAds', params: {query: searchQuery}})
}

loadAds()


</script>

<style>

</style>
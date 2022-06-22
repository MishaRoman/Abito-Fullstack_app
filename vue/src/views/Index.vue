<template>
  <SearchPanel
    @filterAds="filterAds"
  />
  <div class="container page-content">
    <main class="main">
      <h2 class="page-title">Recommendations for you</h2>
      <div class="spinner" v-if="ads.loading && !ads.length"></div>
      <div class="cards" v-else>
        <AdCard
          v-for="ad in ads"
          :key="ad.index"
          :ad="ad"
        />
      </div>
    </main>
    <div class="observer" ref="obs" :class="ads.loading && ads.length ? 'spinner': '' "></div>
  </div>
</template>

<script setup>
import SearchPanel from '../components/SearchPanel.vue'
import AdCard from '../components/AdCard.vue'
import store from '../store'
import { computed, onMounted, ref } from 'vue'
import {useRouter} from 'vue-router'

const router = useRouter()

const ads = computed(() => store.state.ads.data)
const totalPages = computed(() => store.state.ads.meta.last_page)

const page = ref(1)
const obs = ref(null)

const loadAds = () => {
  page.value = 1
  if (store.state.user.token) {
    store.dispatch('getAuthAds', {params: {page: 1}})
  } else {
    store.dispatch('getAds', {params: {page: 1}})
  }
}

const loadMoreAds = (page) => {
  page.value += 1
  if (store.state.user.token) {
    store.dispatch('getMoreAuthAds', {params: {
      page: page.value,
    }})
  } else {
    store.dispatch('getMoreAds', {params: {
      page: page.value,
    }})
  }
}

function filterAds(searchQuery) {
  router.push({name: 'filteredAds', params: {query: searchQuery}})
}

onMounted(() => {
  loadAds()

  const options = {
    rootMargin: '0px',
    threshold: 1.0
  }
  const callback = (entries, observer) => {
    if (entries[0].isIntersecting && page.value <= totalPages.value) {
      loadMoreAds(page)
    }
  };
  const observer = new IntersectionObserver(callback, options);
  observer.observe(obs.value)
})


</script>

<style scoped>
.observer {
  height: 30px;
  margin-bottom: 3px;
  text-align: center;
}
.spinner {
  width: 2em;
  height: 2em;
  border-top: 0.8em solid #256EEB;
  border-right: 0.8em solid transparent;
  border-radius: 50%;
  margin: auto;
  animation: spinner-23543608 0.6s linear infinite;
}
@keyframes spinner {
  100% { transform: rotate(360deg) }
}
</style>
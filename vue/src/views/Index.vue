<template>
  <SearchPanel
    @getFilteredAds="loadAds"
  />
  <div class="container page-content">
    <main class="main">
      <h2 class="page-title">Recommendations for you</h2>
      <div class="spinner" v-if="ads.loading && !ads.data.length"></div>
      <div class="cards" v-else>
        <AdCard
          v-for="ad in ads.data"
          :key="ad.index"
          :ad="ad"
        />
      </div>
    </main>
    <div class="observer" ref="obs" :class="ads.loading && ads.data.length ? 'spinner': '' "></div>
  </div>
</template>

<script setup>
import SearchPanel from '../components/SearchPanel.vue'
import AdCard from '../components/AdCard.vue'
import store from '../store'
import { computed, onMounted, ref } from 'vue'

const ads = computed(() => store.getters.ads)
const totalPages = computed(() => store.state.ads.meta.last_page)

const page = ref(1)
const obs = ref(null)
const searchQuery = ref(null)


const loadAds = (query) => {
  searchQuery.value = query
  page.value = 1
  if (store.state.user.token) {
    store.dispatch('getAuthAds', query)
  } else {
    store.dispatch('getAds', query)
  }
}

function loadMoreAds(page, query) {
  page.value += 1
  if (store.state.user.token) {
    store.dispatch('getMoreAuthAds', {params: {
      page: page.value,
      query: query
    }})
  } else {
    store.dispatch('getMoreAds', {params: {
      page: page.value,
      query: query
    }})
  }
}

onMounted(() => {
  loadAds()

  const options = {
    rootMargin: '0px',
    threshold: 1.0
  }
  const callback = (entries, observer) => {
    if (entries[0].isIntersecting && page.value <= totalPages.value) {
      loadMoreAds(page, searchQuery.value)
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
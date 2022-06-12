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
    <div class="observer" ref="obs"></div>
  </div>
</template>

<script setup>
import SearchPanel from '../components/SearchPanel.vue'
import AdCard from '../components/AdCard.vue'
import store from '../store'
import { computed, onMounted, ref } from 'vue'

const ads = computed(() => store.getters.ads)

const page = ref(0)
const obs = ref(null)
const totalPages = computed(() => store.state.totalPages)

const loadAds = (page) => {
  page.value += 1
  if (store.state.user.token) {
    store.dispatch('getAuthAds', page.value)
  } else {
    store.dispatch('getAds', page.value)
  }
}

function loadMoreAds(page) {
  page.value += 1
  if (store.state.user.token) {
    store.dispatch('getAuthAds', page.value)
  } else {
    store.dispatch('getAds', page.value)
  }
}

onMounted(() => {
  loadAds(page)

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
}
</style>
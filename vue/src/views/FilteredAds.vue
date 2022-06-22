<template>
  <SearchPanel
    @filterAds="filterAds"
  />
  <div class="container page-content">
    <h2 class="page-title" ref="title" v-if="ads.length">Search results for "{{searchQuery ? searchQuery : category}}"</h2>

    <h2 class="page-title" ref="title" v-if="!loading && !ads.length">No results were found for query "{{searchQuery}}"</h2>
    <div class="cards" v-else>
      <AdCard
        v-for="ad in ads"
        :key="ad.id"
        :ad="ad"
      />
    </div>
    <div class="observer" ref="obs" :class="loading && ads.length ? 'spinner': '' "></div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import SearchPanel from '../components/SearchPanel.vue'
import {ref, onMounted, computed, watch} from 'vue'
import {useRoute} from 'vue-router'
import store from '../store'

const route = useRoute()
const ads = computed(() => store.state.ads.data)
const loading = computed(() => store.state.ads.loading)
const totalPages = computed(() => store.state.ads.meta.last_page)

const page = ref(1)
const searchQuery = ref(null)
const category = ref(null)

const obs = ref(null)

const loadAds = () => {
  page.value = 1
  if (store.state.user.token) {
    store.dispatch('getAuthAds', {params: {
      page: 1,
      query: searchQuery.value,
      category: category.value
    }})
  } else {
    store.dispatch('getAds', {params: {
      page: 1,
      query: searchQuery.value,
      category: category.value
    }})
  }
}

const loadMoreAds = () => {
  page.value += 1
  if (store.state.user.token) {
    store.dispatch('getMoreAuthAds', {params: {
      page: page.value,
      query: searchQuery.value,
      category: category.value
    }})
  } else {
    store.dispatch('getMoreAds', {params: {
      page: page.value,
      query: searchQuery.value,
      category: category.value
    }})
  }
}

const filterAds = (query) => {
  searchQuery.value = query
  loadAds(searchQuery.value)
}

onMounted(() => {
  searchQuery.value = route.params.query;
  category.value = route.params.category;
  
  loadAds()

  const options = {
    rootMargin: '0px',
    threshold: 1.0
  }
  const callback = (entries, observer) => {
    if (entries[0].isIntersecting && page.value <= totalPages.value) {
      loadMoreAds()
    }
  };
  const observer = new IntersectionObserver(callback, options);
  observer.observe(obs.value)
})

watch(route, (from, to) => {
  if (to.name == 'filteredAdsWithCategory') {
    category.value = route.params.category;
    loadAds()
  }
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
<template>
  <SearchPanel/>
  <div class="container page-content">
    <h2 class="page-title" ref="title"></h2>
    <div class="cards">
      <AdCard 
        v-for="ad in ads"
        :key="ad.id"
        :ad="ad"
      />
    </div>
    <div class="observer" ref="obs" :class="ads.loading && ads.data.length ? 'spinner': '' "></div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import SearchPanel from '../components/SearchPanel.vue'
import {ref, onMounted, computed} from 'vue'
import {useRoute} from 'vue-router'
import store from '../store'

const route = useRoute()
const ads = computed(() => store.state.ads.data)
const totalPages = computed(() => store.state.ads.meta.last_page)

const title = ref('')

const page = ref(1)
const obs = ref(null)
const searchQuery = ref(null)

const loadAds = (query) => {
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
  searchQuery.value = route.params.query;
  loadAds(searchQuery.value)
  
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
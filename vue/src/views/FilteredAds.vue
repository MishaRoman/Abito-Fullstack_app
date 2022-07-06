<template>
  <SearchPanel
    @filterAds="filterAds"
  />
  <div class="container page-content">
    <h2
      class="page-title"
      ref="title"
      v-if="ads.length"
      >Search results for "{{searchQuery ? searchQuery : category}}"
    </h2>

    <h2
      class="page-title"
      ref="title"
      v-if="!loading && !ads.length"
      >No results were found for query "{{searchQuery}}"
    </h2>
    <div class="cards" v-else>
      <AdCard
        v-for="ad in ads"
        :key="ad.id"
        :ad="ad"
      />
    </div>

    <div v-intersection="loadMoreAds" class="observer" :class="loading && ads.length ? 'spinner': '' "></div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import SearchPanel from '../components/SearchPanel.vue'
import { useRoute } from 'vue-router'
import { useStore } from 'vuex'
import { ref, computed, watch } from 'vue'

const route = useRoute()
const store = useStore()

const ads = computed(() => store.state.ads.data)
const loading = computed(() => store.state.ads.loading)
const totalPages = computed(() => store.state.ads.meta.last_page)

const page = ref(null)
const searchQuery = ref(null)
const category = ref(null)

const loadAds = () => {
  page.value = 1

  store.dispatch('getAds', {params: {
    page: 1,
    query: searchQuery.value,
    category: category.value
  }})
}

const loadMoreAds = () => {
  if (page.value >= totalPages.value) return

  page.value += 1
  store.dispatch('getMoreAds', {params: {
    page: page.value,
    query: searchQuery.value,
    category: category.value
  }})
}

const filterAds = (query) => {
  searchQuery.value = query
  loadAds(searchQuery.value)
}

searchQuery.value = route.params.query;
category.value = route.params.category;

loadAds()


watch(route, (from, to) => {
  if (to.name == 'filteredAdsWithCategory') {
    category.value = route.params.category;
    loadAds()
  }
})

</script>

<style scoped>

</style>
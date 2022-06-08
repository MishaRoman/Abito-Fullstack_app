<template>
  <div class="container">
    <hr>
  </div>
  <div class="container page-content page-content-single">
    <main class="main main-single">
      <div class="content">
        <h2 class="page-title">{{ad.title}}</h2>
        <swiper
          :modules="modules"
          :slides-per-view="1"
          :space-between="50"
          navigation
          :pagination="{ clickable: true }"
          :scrollbar="{ draggable: true }"
        >
          <swiper-slide v-for="img in ad.images" :key="img.id">
            <img :src="img.image_url" class="img">
          </swiper-slide>
        </swiper>

        <div class="content-text">
          <p>{{ad.description}}</p>
        </div>
        <div class="address">
          <span class="address-text">{{ad.address}}</span>
        </div>
      </div>
      <div class="author">
        <strong class="single-price">{{ad.price}} $</strong>
        <router-link class="card-link" :to="{name: 'userAds', params: {id: ad.author.id}}">
          <div class="author-wrapper">
            <div class="author-info">
              <span class="author-name">{{ad.author.name}}</span>
              <span class="author-status">Member since {{ad.author.member_since}}</span>
            </div>
            <img :src="ad.author.image_url" class="author-avatar" />
          </div>
        </router-link>

        <div class="button-group">
          <button class="button button-block button-primary" ref="phoneNumBtn">
            Show phone number
          </button>
          <button class="button button-block button-success">
            Write a message
          </button>
        </div>
      </div>
    </main>

    <div class="recommendations">
      <h2 class="page-title">Other ads by this author</h2>
      <div class="cards">
        <AdCard
          v-for="ad in otherAds"
          :key="ad.id"
          :ad="ad"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import {useRoute} from 'vue-router'
import {ref, onMounted, watch} from 'vue'
import store from '../store'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, Scrollbar, A11y } from 'swiper';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';

const modules = [Navigation, Pagination, Scrollbar, A11y]

const route = useRoute()

const ad = ref({
  id: null,
  title: '',
  description: '',
  address: '',
  price: '',
  author: {
    id: 0,
    name: '',
    phone_number: null,
    image_url: '',
    member_since: null,
  }
})

function getAd() {
  store.dispatch('getAd', route.params.id)
    .then(res => {
      ad.value = res.data

      store.dispatch('getAdsByAuthor', [ad.value.author.id, ad.value.id])
        .then(res => {
          otherAds.value = res.data
        })
    })
}
getAd()
const phoneNumBtn = ref(null)

onMounted(() => {
  phoneNumBtn.value.addEventListener('click', () => phoneNumBtn.value.innerText = ad.value.author.phone_number)
});

watch(route, (from, to) => {
  if (to.name == 'single') {
    getAd()
  }
})

const otherAds = ref()

</script>

<style scoped>
.address-text {
  font-size: 14px;
  line-height: 16px;
  color: #999999;
}
.recommendations {
  margin-top: 20px;
}
</style>
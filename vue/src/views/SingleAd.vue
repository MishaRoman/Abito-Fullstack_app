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

          <button class="button button-block button-success" :disabled="loading" @click="followDispatch">
            {{ ad.author.follows ? 'Unfollow': 'Follow'}}
            <span class="loading" v-if="loading"></span>
          </button>
        </div>
      </div>
    </main>

    <div class="comments-wrapper">
      <form @submit.prevent="addComment">
        <h2>Write a message...</h2>
        <textarea
          name="body"
          class="comments-textarea"
          rows="12"
          required
          v-model="commentBody"
          >
        </textarea>
        <button
          class="button button-primary"
          @click.prevent="addComment"
          >Send
        </button>
      </form>
    </div>

    <div class="comments-wrapper" v-if="ad.comments">
      <Comment 
        v-for="comment in ad.comments"
        :key="comment.id"
        :comment="comment"
      />

    </div>

    <div class="recommendations">
      <h2 class="page-title">Other ads by this author</h2>
      <div v-intersection="getAdsByAuthor" class="observer" :class="loading ? 'spinner': '' "></div>

      <p v-if="!otherAds.ads.length && !loading">There is no more ads</p>

      <div class="cards" v-else>
        <AdCard
          v-for="ad in otherAds.ads"
          :key="ad.id"
          :ad="ad"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import Comment from '../components/Comment.vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, Scrollbar, A11y } from 'swiper';
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import { ref, onMounted, watch } from 'vue'

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';

const modules = [Navigation, Pagination, Scrollbar, A11y]

const route = useRoute()
const store = useStore()

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
    follows: false,
    member_since: null,
  },
  comments: []
})

const loading = ref(true)
const commentBody = ref('')
const otherAds = ref({ads: [], isArrived: false})
const phoneNumBtn = ref(null)

function getAd() {
  store.dispatch('getAd', route.params.id)
    .then(res => {
      ad.value = res.data
      loading.value = false
    })
}

const getAdsByAuthor = () => {
  if (otherAds.value.isArrived) return
  loading.value = true
  store.dispatch('getAdsByAuthor',
    {
      authorId: ad.value.author.id,
      adId: ad.value.id
    })
    .then(res => {
      loading.value = false
      otherAds.value.ads = res.data
      otherAds.value.isArrived = true
    })
}

const addComment = () => {
  if (!store.state.user.token) return alert('You need to be logged in to add a comment')
  store.dispatch('addComment', {
    body: commentBody.value,
    adId: ad.value.id,
  })
  .then(() => {
    getComments()
    commentBody.value = ''
  })
}

const getComments = () => {
  store.dispatch('getComments', ad.value.id)
    .then (res => {
      ad.value.comments = res.data
    })
}

const followDispatch = () => {
  if (!store.state.user.token) return alert('You need to be logged in to follow a user')
  ad.value.author.follows ? unfollow(): follow()
}

const follow = () => {
  loading.value = true
  store.dispatch('follow', ad.value.author.id)
    .then (res => {
      ad.value.author.follows = true
      loading.value = false
    })
}
const unfollow = () => {
  loading.value = true
  store.dispatch('unfollow', ad.value.author.id)
    .then (res => {
      ad.value.author.follows = false
      loading.value = false
    })
}

getAd()

onMounted(() => {
  phoneNumBtn.value.addEventListener('click', () => phoneNumBtn.value.innerText = ad.value.author.phone_number)
});

watch(route, (from, to) => {
  if (to.name == 'single') {
    getAd()
  }
})

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

.comments-wrapper {
  width: 560px;
}
.comments-textarea {
  width: 552px;
  border: 2px solid #0A143A;
}
.button-primary {
  margin-top: 15px;
}
</style>
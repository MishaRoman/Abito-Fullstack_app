<template>
  <div class="container">
    <hr>
    <div class="main-wrapper">
      <div class="sidebar">
        <div class="img-wrapper">
          <img class="img" :src="user.image_url">
        </div>
        <div class="author-info">
          <span class="author-name">{{user.name}}</span>
          <span class="author-status">Member since {{user.member_since}}</span>
        </div>
        <div class="button-group">
          <button class="button button-block button-primary" ref="phoneNumBtn">
            Show phone number
          </button>
        </div>
      </div>

      <div class="cards-block">
        <h2 class="page-title">User's ads</h2>
        <div class="cards">
          <AdCard
            v-for="ad in ads"
            :key="ad.index"
            :ad="ad"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import AdCard from '../components/AdCard.vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import { ref, onMounted } from 'vue'

const route = useRoute()
const store = useStore()

const ads = ref([])
const phoneNumBtn = ref(null)
const user = ref({
  id: null,
  name: '',
  phone_number: null,
  image_url: '',
  member_since: null,
})

store.dispatch('getUserAds', route.params.id)
  .then(res => {
    ads.value = res.data.ads
    user.value = res.data.user
  })

onMounted(() => {
  phoneNumBtn.value.addEventListener('click', () => phoneNumBtn.value.innerText = user.value.phone_number)
});

</script>

<style scoped>
.sidebar {
  max-height: 400px;
}
.main-wrapper {
  display: flex;
  margin-top: 30px;
}
.img {
  border-radius: 99%;
  width: 225px;
  height: 225px;
}
.img-wrapper {
  width: 250px;
  margin-right: 44px;
}

.author-info {
  margin-top: 16px;
  margin-bottom: 16px;
}
.author-name {
  font-size: 24px;
  margin-bottom: 8px;
}
.author-status {
  font-size: 16px;
}
.button-block {
  width: 77%;
}

@media (max-width: 1200px) {
  .cards {
    word-break: break-all;
  }
}

@media (max-width: 992px) {
  .main-wrapper {
    flex-direction: column;
  }
  .sidebar {
    max-height: 100%;
    text-align: center;
  }
  .button-group {
    display: flex;
    align-items: center;
  }
  .img-wrapper {
    width: auto;
    margin: auto;
  }
}
</style>
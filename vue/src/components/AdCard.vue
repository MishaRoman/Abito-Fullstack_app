<template>
  <div class="card">
    <router-link class="card-link" :to="{name: 'single', params: {id: ad.id}}">
      <img src="../assets/img/card-image.jpg" class="card-image" />
    </router-link>
    <div class="card-header">
      <router-link class="card-link" :to="{name: 'single', params: {id: ad.id}}">
        <h3 class="card-title">{{ad.title}}</h3>
      </router-link>
    </div>
    <strong class="card-price">{{ad.price}} $</strong>
    <p class="card-text">{{ad.address}}</p>
    <p class="card-text">{{ad.created_at}}</p>
  </div>
</template>

<script setup>
const { ad } = defineProps({
  ad: Object
}) 
</script>
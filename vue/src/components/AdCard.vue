<template>
  <div class="card">
    <router-link class="card-link" :to="{name: 'single'}">
      <img src="../assets/img/card-image.jpg" class="card-image" />
    </router-link>
    <div class="card-header">
      <router-link class="card-link" :to="{name: 'single'}">
        <h3 class="card-title">Electric scooter</h3>
      </router-link>
    </div>
    <strong class="card-price">100 $</strong>
    <p class="card-text">Address</p>
    <p class="card-text">10.06 11:39</p>
  </div>
</template>

<script setup>

</script>
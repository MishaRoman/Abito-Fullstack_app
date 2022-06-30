<template>
  <div class="errors-block">
    <ul v-for="(field, i) of Object.keys(errors)" :key="i">
      <li v-for="(error, ind) of errors[field] || []" :key="ind">{{error}}</li>
    </ul>
  </div>
</template>

<script setup>
const { errors } = defineProps({
  errors: Object
})
</script>

<style>
.errors-block ul {
  padding: 0;
  color: red;
}
</style>
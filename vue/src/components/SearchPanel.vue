<template>
  <div class="container">
    <div class="search-panel">
      <input
       type="text"
       class="search-input"
       placeholder="Search by ads"
       v-model="searchQuery"
       @keyup.enter="filterAds"
      />
      <button class="search-button" @click="filterAds">
        <svg
          class="search-icon"
          width="15"
          height="15"
          viewBox="0 0 15 15"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g clip-path="url(#clip0)">
            <path
              d="M14.6506 14.0012L10.0862 9.43675C10.9465 8.43352 11.4706 7.13293 11.4706 5.71058C11.4706 2.54822 8.89765 -0.0247192 5.7353 -0.0247192C4.20353
                  -0.0247192 2.76265 0.571751 1.67824 1.6544C0.595589 2.73793 -0.000881376 4.17881 9.77541e-07 5.71058C9.77541e-07 8.87293 2.57294 11.4459 5.7353 11.4459C7.15765
                  11.4459 8.45912 10.9218 9.46235 10.0615L14.0268 14.6259L14.6506 14.0012ZM5.7353 10.5635C3.06 10.5635 0.882354 8.38675 0.882354 5.71058C0.881472 4.41352 1.38618
                  3.19499 2.30294 2.2791C3.21882 1.36234 4.43824 0.857634 5.7353 0.857634C8.41059 0.857634 10.5882 3.0344 10.5882 5.71058C10.5882 8.38587 8.41059 10.5635 5.7353
                  10.5635Z"
              fill="white"
            />
          </g>
          <defs>
            <clipPath id="clip0">
              <rect width="15" height="15" fill="white" />
            </clipPath>
          </defs>
        </svg>

        <span class="search-button-text">Search</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref }  from 'vue'

const searchQuery = ref(null)

const emit = defineEmits(['filterAds'])
const filterAds = () => emit('filterAds', searchQuery.value)

</script>

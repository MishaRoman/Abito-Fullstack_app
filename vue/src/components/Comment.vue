<template>
  <div class="comment">
    <router-link class="card-link" :to="{name: 'userAds', params: {id: comment.author.id}}">
      <div class="comment-author">
          <img :src="comment.author.image_url" class="author-avatar">
          <div>
            <span class="author-name">{{comment.author.name}}</span>
            <span class="author-status">{{comment.created_at}}</span>
          </div>
      </div>
    </router-link>
    <div class="comment-body">
      <p>{{comment.body}}</p>
    </div>
  </div>
</template>

<script setup>

const { comment } = defineProps({
  comment: Object,
})

</script>

<style scoped>
.comment {
  border-radius: 5px;
  margin: 15px auto;
  padding: 8px;
  background-color: rgb(216, 223, 224);
}
.comment-author {
  display: flex;
}
.comment-author .author-avatar {
  margin-right: 10px;
}
</style>
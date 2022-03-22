<template>
  <div class="container">
    <hr>
    <div class="form-titles">
      <h2>Create ad</h2>
      <h4>Fill the fields below</h4>
    </div>
    <form class="create-form">
      <label for="title">Title</label>
      <input type="text" name="title" class="input" placeholder="title">

      <label for="description">Description</label>
      <textarea type="text" name="desctiption" class="text-input" rows="7" placeholder="Description"></textarea>

      <label for="price">Price in $</label>
      <input type="number" value="0" name="price" class="input price-input" placeholder="Address">

      <label for="category">Choose a category</label>
      <select name="category" class="create-form__select" value="Choose">
        <option value="one">One</option>
        <option value="one">Two</option>
        <option value="one">Three</option>
      </select>

      <label for="address">Address</label>
      <input type="text" name="address" class="input">
      
      <button class="button create-form__button">Create</button>
    </form>
  </div>
</template>

<script setup>
</script>

<style scoped>
label {
  margin-bottom: 5px;
  font-size: 18px;
}
.create-form {
  display: flex;
  flex-direction: column;
  max-width: 300px;
}
.input {
  display: block;
  font-size: 16px;
  padding: 10px 5px;
  color: rgb(15, 20, 25);
  border: 2px solid #256EEB;
  border-radius: 5px;
  width: 300px;
  margin-bottom: 15px;
}
.text-input {
  border: 2px solid #256EEB;
  border-radius: 4px;
  outline: 0;
  transition: 0.2s;
  margin-bottom: 10px;
  width: 350px;
}
.price-input {
  width: 150px;
}

.create-form__select {
  width: 120px;
  margin-top: 5px;
  margin-bottom: 15px;
  height: 30px;
}

.create-form__button {
  display: block;
  background-color: #256EEB;
  margin-top: 10px;
  padding: 14px 35px;
  border-radius: 9999px;
  font-size: 16px;
  font-weight: 700;
  color: #ffffff;
  align-self: center;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type="number"] {
  -moz-appearance: textfield;
}
input[type="number"]:hover,
input[type="number"]:focus {
  -moz-appearance: number-input;
}
</style>
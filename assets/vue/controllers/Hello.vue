<template>
  <div>Hello {{ name }}
    <ul>
  <li v-for="item in  users ">
    {{ item.name }}
  </li>
    </ul>
  </div>
</template>

<script setup>
defineProps({
  name: String,
  users: Array

});
</script>

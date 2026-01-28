<!-- resources/js/Components/MyCard.vue -->
<template>
  <div class="p-4 bg-white shadow rounded">
    <h2>{{ title }}</h2>
    <p>{{ count }}</p>
  </div>
</template>

<script>
export default {
  props: ['title', 'count']
};
</script>

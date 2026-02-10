<template>
  <div class="flex min-h-screen bg-gray-100">
    
    <Sidebar />

    <main class="flex-1 p-10">
      <div class="w-40 h-40 bg-[#515050] shadow-lg rounded-lg">
        </div>
    </main>

  </div>
</template>

<script>
import MyCard from '../Components/MyCard.vue';
import Sidebar from '../Components/Sidebar.vue';

export default {
  components: {
    MyCard,
    Sidebar
  },
};
</script>

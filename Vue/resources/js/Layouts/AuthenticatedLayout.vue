<template>
  <div class="min-h-screen bg-gray-300">
    <Toast /> 
    
    <div class="flex flex-col md:flex-row md:h-screen w-full overflow-x-hidden">
      <Sidebar />
      <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import Toast from 'primevue/toast';
import Sidebar from '@/Components/Sidebar.vue';
import { watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const page = usePage();
const toast = useToast();

// Listen for flash messages sent from Laravel controllers
watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        toast.add({ severity: 'success', summary: 'Success', detail: flash.success, life: 3000 });
    }
    if (flash?.error) {
        toast.add({ severity: 'error', summary: 'Error', detail: flash.error, life: 3000 });
    }
}, { deep: true });
</script>
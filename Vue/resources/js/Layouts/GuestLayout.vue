<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >

    <div class="[
    'h-screen bg-[#515050] text-[#dcdcdc] transition-all duration-300 flex flex-col py-4',
    isCollapsed ? 'w-20' : 'w-64'
  ]">
        <div v-if="!isCollapsed" class="font-bold text-xl text-white truncate">
            <img 
                src="/logo.png" 
                alt="Logo" 
                class="h-8 w-auto object-contain transition-opacity duration-300"
            />
        </div>
    </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3'; // Import the Inertia Link
import { 
  LayoutDashboard, 
  FileText, // Added for RFP
  Wallet,   // Added for Petty Cash
  ClipboardList, // Added for Liquidation
  BarChart, // Added for Reports
  ChevronLeft, 
  ChevronRight,
  LogOut 
} from 'lucide-vue-next';

const isCollapsed = ref(false);

// Update navItems with your actual Laravel route names or URLs
const navItems = [
  { name: 'Dashboard', icon: LayoutDashboard, href: route('dashboard') },
  { name: 'RFP', icon: FileText, href: '/login' },
  { name: 'Petty Cash', icon: Wallet, href: '/petty-cash' },
  { name: 'Liquidation', icon: ClipboardList, href: '/liquidation' },
  { name: 'Reports', icon: BarChart, href: '/reports' },
];
</script>

<template>
  <aside 
  :class="[
    'h-screen bg-[#515050] text-[#dcdcdc] transition-all duration-300 flex flex-col py-4',
    isCollapsed ? 'w-20' : 'w-64'
  ]"
>
  <div class="flex items-center justify-between mb-8 px-4">
    <div v-if="!isCollapsed" class="font-bold text-xl text-white truncate">
      Elite<span class="text-[#87af49]"> Boogsh</span>
    </div>
    <button 
      @click="isCollapsed = !isCollapsed" 
      class="p-2 rounded-lg hover:bg-[#dcdcdc] hover:text-[#515050] transition-colors"
    >
      <ChevronLeft v-if="!isCollapsed" :size="20" />
      <ChevronRight v-else :size="20" />
    </button>
  </div>

  <nav class="flex-1 space-y-2">
    <Link 
      v-for="item in navItems" 
      :key="item.name" 
      :href="item.href"
      :class="[
        'flex items-center w-52 p-3 px-4 rounded-tr-xl rounded-br-xl transition-all group',
        $page.url === item.href 
          ? 'bg-[#dcdcdc] text-[#515050]' 
          : 'hover:bg-[#dcdcdc] hover:text-[#515050]'
      ]"
    >
      <component :is="item.icon" v-if="item.icon" :size="24" />
      
      <span 
        v-if="!isCollapsed" 
        class="ml-4 font-medium transition-opacity duration-200"
      >
        {{ item.name }}
      </span>
    </Link>
  </nav>

  <div class="pt-4 border-t border-slate-800 px-4">
    <Link 
      :href="route('logout')" 
      method="post" 
      as="button" 
      class="flex items-center w-full p-3 rounded-xl hover:bg-red-500/10 hover:text-red-500 transition-colors"
    >
      <LogOut :size="24" />
      <span v-if="!isCollapsed" class="ml-4 font-medium">Logout</span>
    </Link>
  </div>
</aside>
</template>
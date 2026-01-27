<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3'; 
import { 
  Home, 
  LayoutDashboard, 
  Settings, 
  Users, 
  ChevronLeft, 
  ChevronRight,
  LogOut,
  FileText,
  Wallet,   
  ClipboardList,  BarChart, 
} from 'lucide-vue-next';

const isCollapsed = ref(false);

const navItems = [
  { name: 'Dashboard', icon: LayoutDashboard, href: route('dashboard') },
  { name: 'RFP', icon: FileText, href: '/login' },
  { name: 'Petty Cash', icon: Wallet, href: '/petty-cash' },
  { name: 'Liquidation', icon: ClipboardList, href: '/liquidation' },
  { name: 'Reports', icon: BarChart, href: '/reports' },
  { name: 'Master List', icon:null, href:'#',
    children:[
      { name:'Users', icon:null, href:'#'},
      {name:'Status', icon:null, href:'#'},
      {name:'Companies', icon:null, href:'#'}
    ]
  },
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
  <div v-for="item in navItems" :key="item.name" class="group/menu">
    
    <a 
      :href="item.href"
      :class="[
        'flex items-center hover:bg-[#dcdcdc] hover:text-[#515050] p-3 transition-all',
        isCollapsed ? 'justify-center w-12 h-12 mx-auto rounded-lg' : 'w-52 px-4 rounded-tr-xl rounded-br-xl'
      ]"
    >
      <component :is="item.icon" v-if="item.icon" :size="24" />
      <span v-if="!isCollapsed" class="ml-4 font-medium transition-opacity duration-200">
        {{ item.name }}
      </span>
    </a>

    <div v-if="item.children && !isCollapsed" class="mt-1 ml-10 flex flex-col space-y-1">
      <a 
        v-for="child in item.children" 
        :key="child.name"
        :href="child.href"
        :class="[
        'flex items-center hover:bg-[#dcdcdc] hover:text-[#515050] p-3 transition-all',
        isCollapsed ? 'justify-center w-12 h-12 mx-auto rounded-lg' : 'w-52 px-4 rounded-tr-xl rounded-br-xl'
      ]"      >
        {{ child.name }}
      </a>
    </div>
    
  </div>
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

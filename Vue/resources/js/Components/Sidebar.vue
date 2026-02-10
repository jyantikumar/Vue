<script setup>
import { ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

import { 
  LayoutDashboard, FileText, Wallet, ClipboardList, 
  BarChart, ChevronLeft, ChevronRight, LogOut, 
  ChevronDown, Database, UserRound
} from 'lucide-vue-next';

const isCollapsed = ref(false);
const masterlistToggle = ref(false);
const page = usePage();

watch(isCollapsed, (newVal) => {
  if (newVal) masterlistToggle.value = false;
});

const isActive = (href) => page.url === href;

const navItems = [
  { name: 'Dashboard', icon: LayoutDashboard, href: '/dashboard' }, 
  { name: 'RFP', icon: FileText, href: '/login' },
  { name: 'Petty Cash', icon: Wallet, href: '/petty-cash' },
  { name: 'Liquidation', icon: ClipboardList, href: '/liquidation' },
  { name: 'Reports', icon: BarChart, href: '/reports' },
];

const masterlist = [
  { name: "Users", href: "/users" },
  { name: "Status", href: "/status" },
  { name: "Company", href: "/company" },
  { name: "Petty Cash", href: "/master-petty-cash" },
];
</script>

<template>
  <aside 
    :class="[
      'h-screen bg-[#515050] text-[#dcdcdc] transition-all duration-300 flex flex-col py-4 shadow-xl',
      isCollapsed ? 'w-20' : 'w-56'
    ]"
  >
    <div class="flex items-center justify-between mb-8 px-4 h-10">
      <div v-if="!isCollapsed" class="flex items-center overflow-hidden">
        <img 
          src="/logo.png" 
          alt="Logo" 
          class="h-8 w-auto object-contain transition-opacity duration-300"
        />
      </div>
      <button 
        @click="isCollapsed = !isCollapsed" 
        class="p-2 rounded-lg hover:bg-[#dcdcdc] hover:text-[#515050] transition-colors mx-auto"
      >
        <ChevronLeft v-if="!isCollapsed" :size="20" />
        <ChevronRight v-else :size="20" />
      </button>
    </div>

    <nav class="flex-1 space-y-2 overflow-y-auto overflow-x-hidden">
      <Link 
        v-for="item in navItems" 
        :key="item.name" 
        :href="item.href"
        :class="[
          'flex items-center p-3 px-4 transition-all duration-200 group relative',
          isCollapsed ? 'rounded-tr-xl rounded-br-xl w-16' : 'w-52 rounded-r-xl',
          isActive(item.href) 
            ? 'bg-[#87af49] text-white shadow-lg' 
            : 'text-[#dcdcdc] hover:bg-[#dcdcdc] hover:text-[#515050]'
        ]"
      >
        <component :is="item.icon" :size="24" />
        <span v-if="!isCollapsed" class="ml-4 font-medium">{{ item.name }}</span>
      </Link>

      <div class="relative">
        <button 
          @click="masterlistToggle = !masterlistToggle"
          :class="[
            'flex items-center p-3 px-4 transition-all duration-200 group',
            isCollapsed ? 'rounded-tr-xl rounded-br-xl w-16' : 'w-52 rounded-r-xl',
            masterlistToggle ? 'text-white bg-[#616060]' : 'hover:bg-[#dcdcdc] hover:text-[#515050]',
          ]"
        >
          <Database :size="24" />
          <span v-if="!isCollapsed" class="ml-4 font-medium flex-1 text-left">Masterlist</span>
          <ChevronDown 
            v-if="!isCollapsed" 
            :size="18" 
            :class="['transition-transform duration-300', { 'rotate-180': masterlistToggle }]" 
          />
        </button>

        <div 
          v-if="masterlistToggle && !isCollapsed" 
          class="mt-1 ml-4 space-y-1 border-l-2 border-[#87af49]"
        >
          <Link 
            v-for="subItem in masterlist" 
            :key="subItem.name" 
            :href="subItem.href"
            :class="[
              'block p-2 pl-6 text-sm transition-colors',
              isActive(subItem.href) 
                ? 'text-[#87af49] font-bold' 
                : 'hover:text-[#515050] hover:bg-[#dcdcdc]'
            ]"
          >
            {{ subItem.name }}
          </Link>
        </div>
      </div>
    </nav>

    <div class="pt-4 border-t border-[#616060] space-y-2">
      <Link 
        as="button" 
        :class="[
          'flex items-center p-3 transition-colors hover:bg-[#dcdcdc] hover:text-[#515050]',
          isCollapsed ? 'rounded-tr-xl rounded-br-xl w-16' : 'w-52 rounded-r-xl'
        ]"
      >
        <UserRound :size="24" />
        <span v-if="!isCollapsed" class="ml-4 font-medium">Profile</span>
      </Link>
      
      <Link 
        :href="route('logout')" 
        method="post" 
        as="button" 
        :class="[
          'flex items-center p-3 transition-colors hover:bg-red-900 hover:text-white',
          isCollapsed ? 'rounded-tr-xl rounded-br-xl w-16' : 'w-52 rounded-r-xl'
        ]">
        <LogOut :size="24" />
        <span v-if="!isCollapsed" class="ml-4 font-medium">Logout</span>
      </Link>
    </div>
  </aside>
</template>

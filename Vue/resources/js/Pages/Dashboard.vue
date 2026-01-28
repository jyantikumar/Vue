<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
  FileStack, FilePenLine, Clock, FileCheck, FileX
} from 'lucide-vue-next';
import { useToast } from "primevue/usetoast";

export default {
  layout: AuthenticatedLayout,
  components: {
    FileStack, 
    FilePenLine, 
    Clock, 
    FileCheck, 
    FileX
  },
  data() {
    return {
      rfpStats: [
        { 
          cardLabel: "Number of RFPs", 
          count: "30", 
          isLarge: true, 
          badge: "bg-[#515050] px-3 py-1 rounded-full text-white", 
          icon: "FileStack" 
        },
        { 
          cardLabel: "Draft RFP", 
          count: "8", 
          isLarge: false, 
          badge: "bg-[#515050] px-3 py-1 rounded-full text-white", 
          icon: "FilePenLine" 
        },
        { 
          cardLabel: "Pending RFP", 
          count: "8", 
          isLarge: false, 
          badge: "bg-[#D9A05B] px-3 py-1 rounded-full text-white", 
          icon: "Clock" 
        },
        { 
          cardLabel: "Paid RPF", 
          count: "7", 
          isLarge: false, 
          badge: "bg-[#87AF49] px-3 py-1 rounded-full text-white", 
          icon: "FileX" 
        },
        { 
          cardLabel: "Cancelled RPF", 
          count: "7", 
          isLarge: false, 
          badge: "bg-[#C25B56] px-3 py-1 rounded-full text-white", 
          icon: "FileX" 
        },
      ],
    }
  },
  mounted() {
    this.checkAndShowToast();
  },
  watch: {
    '$page.props.flash': {
      handler() {
        this.checkAndShowToast();
      },
      deep: true,
    },
  },
  methods: {
    checkAndShowToast() {
      const flash = this.$page.props.flash;
      
      if (flash?.success) {
        this.$toast.add({
          severity: 'success',
          summary: 'Success',
          detail: flash.success,
          life: 3000,
        });
      }

      if (flash?.error) {
        this.$toast.add({
          severity: 'error',
          summary: 'Error',
          detail: flash.error,
          life: 5000,
        });
      }
    },
  },
};
</script>


<template>
  <div class="flex flex-col md:flex-row md:h-screen w-full bg-gray-300 overflow-x-hidden">
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-4 flex-none">
        <div v-for="(card, index) in rfpStats" :key="index" :class="[
            'rounded-lg p-4 flex bg-white shadow-lg',
            card.isLarge ? 'sm:row-span-2 p-6 flex-col justify-between min-h-[160px]' : 'justify-between items-center sm:items-start gap-4']">
          <div :class="['flex items-center gap-2 w-fit', card.badge]">
            <component :is="card.icon" :size="card.isLarge ? 24 : 18" stroke-width="2.5" />
            
            <h3 :class="['font-semibold leading-tight', card.isLarge ? 'text-xl' : 'text-base md:text-lg']">
              {{ card.cardLabel }}
            </h3>
          </div>

          <span :class="['font-bold', card.isLarge ? 'text-5xl md:text-6xl self-end text-gray-900' : 'text-3xl md:text-5xl text-gray-800']">
            {{ card.count }}
          </span>

        </div>  
  </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 flex-1 gap-4 p-4 pt-0 min-h-0">
        <div class="lg:row-span-2 w-full rounded-lg shadow-md p-6 bg-white">
          <h2 class="text-xl font-bold text-gray-800">Recent RPF</h2>
          <p class="text-gray-600 mt-2"></p>
        </div>
        
       <div class="w-full min-h-[150px] rounded-lg shadow-md p-6 bg-white">
       <h2 class="text-xl font-bold text-gray-800 mb-4">Petty Cash</h2>

        </div>
        
        <div class="w-full min-h-[150px] rounded-lg shadow-md p-6 bg-white">
          <h2 class="text-xl font-bold text-gray-800">Account Breakdown</h2>
        </div>
      </div>
    </main>
  </div>
</template>


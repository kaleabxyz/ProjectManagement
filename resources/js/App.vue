<template>
    <body class="bg-custom-blue min-h-screen w-full   "
    :class="bodyClass">

        <Navbar v-if="!isDashboardRoute && userStore.user"/>
        <Sidebar v-if="!isDashboardRoute && userStore.user " @nav="showSide" />
    <router-view /> <!-- This will render the matched component -->
    </body>
  </template>
  
  <script setup>
  // You can handle global logic here if needed
  import Navbar from '@/Components/Navbar.vue'
import Sidebar from '@/Components/Sidebar.vue'
  import { ref,computed, onMounted } from "vue";
  import { useRoute } from 'vue-router';
  import { useUserStore } from '@/Stores/userStore.js';
  
  const userStore = useUserStore();

  const side = ref("active");
const sideDetail = ref(false);
const route = useRoute();
const isDashboardRoute = computed(() => {
  const currentPath = route.path;
  return currentPath === '/' || currentPath === '/register' || currentPath === '/login';
});
const showSide = (val) => {
    side.value = val;
};
const bodyClass = computed(() =>
  isDashboardRoute.value ? 'items-start ' : 'items-center flex'
);
// Load the user from local storage and fetch from API if needed
onMounted(() => {
  userStore.loadUserFromStorage(); // Optionally load user from storage
  userStore.fetchUser();

      
});
  
  </script>
  
  <style>
  /* Global styles can go here */
  </style>
  
<template>
    <body class="bg-custom-blue min-h-screen w-full flex items-center ">

        <Navbar/>
        <Sidebar @nav="showSide" />
    <router-view/> <!-- This will render the matched component -->
    </body>
  </template>
  
  <script setup>
  // You can handle global logic here if needed
  import Navbar from '@/Components/Navbar.vue'
import Sidebar from '@/Components/Sidebar.vue'
  import { ref } from "vue";

  const side = ref("active");
const sideDetail = ref(false);


const showSide = (val) => {
    side.value = val;
};
  </script>
  
  <style>
  /* Global styles can go here */
  </style>
  
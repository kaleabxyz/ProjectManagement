<template>
    <body class="bg-custom-blue min-h-screen w-full flex items-center ">

        <Navbar/>
        <Sidebar @nav="showSide" />
    <router-view></router-view> <!-- This will render the matched component -->
    </body>
  </template>
  
  <script setup>
  // You can handle global logic here if needed
  import Navbar from '@/Components/Navbar.vue'
  import Sidebar from '@/Components/Sidebar.vue'

  </script>
  
  <style>
  /* Global styles can go here */
  </style>
  
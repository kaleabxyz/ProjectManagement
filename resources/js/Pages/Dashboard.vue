<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";


</script>

<template>
    <Head title="Dashboard" />
    <head>
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />
    </head>
    
        <body class="w-full">
            <div class="nav px-52 py-6 flex justify-between">
                <div class="flex items-end">
                    <img class="ml-2 w-10" src="images/logo-s.png" alt="" />
                    <h1 class="text-3xl font-bold">Friday</h1>
                    <h2>.com</h2>
                </div>
                <div class="flex items-center">
                    <h1 class="text-gray-600 mx-2">Login</h1>
                    <router-link to="/register">
                    <div
                        class="group border flex text-white border-blue-300 mx-2 items-center px-4 py-2 rounded-3xl bg-blue-600 hover:bg-blue-700"
                    >
                        <h1>Get started</h1>
                        <svg
                            class="group-hover:pr-0 hover:pl-2 px-1"
                            width="28px"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g
                                id="SVGRepo_tracerCarrier"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            ></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M6 12H18M18 12L13 7M18 12L13 17"
                                    stroke="#ffffff"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </g>
                        </svg>
                    </div>
                </router-link>
                </div>
            </div>
        </body>

</template>

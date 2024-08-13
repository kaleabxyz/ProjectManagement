<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />
    </head>

    <nav
        class="fixed top-0 left-0 w-full flex bg-custom-blue width-full flex items-center justify-between"
    >
        <div class="flex items-center pl-4">
            <svg
                width="24px"
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
                    <g clip-path="url(#clip0_429_11069)">
                        <path
                            d="M4 4H6V6H4V4Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M4 18H6V20H4V18Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M18 4H20V6H18V4Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M18 11H20V13H18V11Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M11 11H13V13H11V11Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M4 11H6V13H4V11Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M11 4H13V6H11V4Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M11 18H13V20H11V18Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                        <path
                            d="M18 18H20V20H18V18Z"
                            stroke="#292929"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                    </g>
                    <defs>
                        <clipPath id="clip0_429_11069">
                            <rect width="24" height="24" fill="white"></rect>
                        </clipPath>
                    </defs>
                </g>
            </svg>

            <a href="/">
                <img
                    class="w-24"
                    src="images/image-removebg-preview.png"
                    alt=""
            /></a>
            <h1 class="font-sans">work management</h1>
        </div>
        <div class="flex items-center pr-4">
            <i class="far fa-bell fa-lg m-4"></i>
            <i class="fa fa-laptop m-4"></i>
            <i class="fa fa-user-plus m-4"></i>
            <i class="fa fa-puzzle-piece m-4"></i>
            <div
                class="border-solid m-4 border-r-2 w-1 h-6 border-gray-400"
            ></div>
            <i class="fa fa-search m-4"></i>
            <i class="fa fa-question m-4"></i>
            <div class="bg-custom-gray flex items-center rounded-r-full">
                <img class="ml-2 w-6" src="images/logo-s.png" alt="" />
                <i class="fa fa-user-circle ml-4 fa-2x"></i>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import TextInput from "@/Components/TextInput.vue";

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
    <aside
        class="fixed top-0 left-0 mt-14 bg-gradient-to-b from-white via-custom-blue to-custom-blue rounded-lg w-fit min-h-screen px-2.5 pt-2"
    >
        <div
            class="flex items-center bg-blue-100 py-1 rounded-md hover:bg-gray-200 cursor-pointer"
        >
            <svg
                width="20px"
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
                        d="M9 20H7C5.89543 20 5 19.1046 5 18V10.9199C5 10.336 5.25513 9.78132 5.69842 9.40136L10.6984 5.11564C11.4474 4.47366 12.5526 4.47366 13.3016 5.11564L18.3016 9.40136C18.7449 9.78132 19 10.336 19 10.9199V18C19 19.1046 18.1046 20 17 20H15M9 20V14C9 13.4477 9.44772 13 10 13H14C14.5523 13 15 13.4477 15 14V20M9 20H15"
                        stroke="#464455"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                </g>
            </svg>
            <h2 class="ml-2 font-thin text-sm">Home</h2>
        </div>
        <div
            class="flex items-center border-b-2 border-solid py-1 pb-4 rounded-md hover:bg-gray-200 cursor-pointer mb-2"
        >
            <i
                class="far fa-calendar-check ml-1 text-sm"
                aria-hidden="true"
            ></i>
            <h2 class="ml-3 font-thin text-sm">My work</h2>
        </div>
        <div class="flex flex-col w-fit">
            <div class="flex mt-2 w-fit">
                <div class="flex relative items-center mr-6">
                    <h3
                        class="bg-gray-500 w-fit text-white py-0.5 px-1 rounded-md text-xs"
                    >
                        M
                    </h3>
                    <svg
                        class="absolute left-2 top-4"
                        width="15px"
                        viewBox="0 0 24 24"
                        fill="#000000"
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
                                d="M9 20H7C5.89543 20 5 19.1046 5 18V10.9199C5 10.336 5.25513 9.78132 5.69842 9.40136L10.6984 5.11564C11.4474 4.47366 12.5526 4.47366 13.3016 5.11564L18.3016 9.40136C18.7449 9.78132 19 10.336 19 10.9199V18C19 19.1046 18.1046 20 17 20H15M9 20V14C9 13.4477 9.44772 13 10 13H14C14.5523 13 15 13.4477 15 14V20M9 20H15"
                                stroke="#ffffff"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            ></path>
                        </g>
                    </svg>
                    <div class="flex items-center p-2 pl-0 rounded-md">
                        <div
                            class="hover:bg-gray-200 cursor-pointer flex p-2 pl-1 flex items-center"
                        >
                            <h1 class="text-md font-bold">Main workspace</h1>
                            <i
                                class="fa fa-chevron-down text-xs ml-4"
                                aria-hidden="true"
                            ></i>
                        </div>

                        <i
                            class="fa fa-ellipsis-h text-xl ml-4 p-2 hover:bg-gray-200 cursor-pointer"
                            aria-hidden="true"
                        ></i>
                    </div>
                </div>
            </div>

            <div class="w-fit flex items-center">
                <div class="relative">
                    <TextInput
                        class="h-10 my-2 w-44 pl-6 text-sm font-thin"
                        placeholder="Search"
                    >
                    </TextInput>
                    <i
                        class="absolute left-2 top-6 fa fa-search text-xs text-gray-400"
                    ></i>
                </div>

                <i
                    class="fa text-white bg-blue-600 fa-plus text-2xl font-thin border border-white p-1 px-1 rounded-md ml-2"
                ></i>
            </div>
            <a href="project">
                <div
                    class="flex my-2 mx-1 items-center hover:bg-gray-200 cursor-pointer p-2 rounded-md"
                >
                    <svg
                        fill="#bfbbbb"
                        width="24px"
                        height="24px"
                        viewBox="0 0 56 56"
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
                                d="M 13.7851 49.5742 L 42.2382 49.5742 C 47.1366 49.5742 49.5743 47.1367 49.5743 42.3086 L 49.5743 13.6914 C 49.5743 8.8633 47.1366 6.4258 42.2382 6.4258 L 13.7851 6.4258 C 8.9101 6.4258 6.4257 8.8398 6.4257 13.6914 L 6.4257 42.3086 C 6.4257 47.1602 8.9101 49.5742 13.7851 49.5742 Z M 13.8554 45.8008 C 11.5117 45.8008 10.1992 44.5586 10.1992 42.1211 L 10.1992 13.8789 C 10.1992 11.4414 11.5117 10.1992 13.8554 10.1992 L 26.0429 10.1992 L 26.0429 45.8008 Z M 42.1679 10.1992 C 44.4882 10.1992 45.8007 11.4414 45.8007 13.8789 L 45.8007 42.1211 C 45.8007 44.5586 44.4882 45.8008 42.1679 45.8008 L 29.9804 45.8008 L 29.9804 10.1992 Z"
                            ></path>
                        </g>
                    </svg>
                    <h2 class="ml-2 text-xs">Project management</h2>
                </div>
            </a>
        </div>
    </aside>
</template>

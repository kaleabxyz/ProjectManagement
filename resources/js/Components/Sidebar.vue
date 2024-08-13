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
        class="fixed top-0 left-0 mt-14 bg-gradient-to-b from-white via-blue-50 to-white rounded-lg w-fit min-h-screen px-3 pt-2"
    >
        <div
            class="flex items-center bg-blue-100 py-2 rounded-lg hover:bg-gray-300"
        >
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
                    <path
                        d="M9 20H7C5.89543 20 5 19.1046 5 18V10.9199C5 10.336 5.25513 9.78132 5.69842 9.40136L10.6984 5.11564C11.4474 4.47366 12.5526 4.47366 13.3016 5.11564L18.3016 9.40136C18.7449 9.78132 19 10.336 19 10.9199V18C19 19.1046 18.1046 20 17 20H15M9 20V14C9 13.4477 9.44772 13 10 13H14C14.5523 13 15 13.4477 15 14V20M9 20H15"
                        stroke="#464455"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                </g>
            </svg>
            <h2 class="ml-2 font-thin">Home</h2>
        </div>
        <div
            class="flex items-center border-b-2 border-solid py-2 rounded-lg hover:bg-gray-300 mb-2"
        >
            <i class="far fa-calendar-check ml-1" aria-hidden="true"></i>
            <h2 class="ml-3 font-thin">My work</h2>
        </div>
        <div class="flex flex-col">
            <div class="flex mt-2">
                <div class="flex relative items-center mr-6">
                    <h3
                        class="bg-gray-500 w-fit text-white py-1 px-2 rounded-lg text-1xl"
                    >
                        M
                    </h3>
                    <svg
                        class="absolute left-4 top-4"
                        width="24px"
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
                    <div
                        class="flex items-center hover:bg-gray-200 p-2 rounded-md"
                    >
                        <h1 class="ml-2">Main workspace</h1>
                        <i
                            class="fa fa-chevron-down ttext-xs ml-6"
                            aria-hidden="true"
                        ></i>
                        <i class="fa fa-ellipsis-h ml-2" aria-hidden="true"></i>
                    </div>
                </div>
            </div>

            <div>
                <TextInput class="h-8 my-4" placeholder="Search"> </TextInput>
                <i
                    class="fa text-white bg-blue-600 fa-plus text-2xl border border-white p-1 px-1 rounded-md ml-2"
                ></i>
            </div>
            <a href="Project.vue">
                <div
                    class="flex my-2 mx-1 items-center hover:bg-gray-200 p-2 rounded-md"
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
                    <h2 class="ml-2 text-1xl">Project management</h2>
                </div>
            </a>
        </div>
    </aside>
</template>

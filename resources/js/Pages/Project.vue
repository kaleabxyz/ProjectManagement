<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Navbar from "@/Components/Navbar.vue";
import Sidebar from "@/Components/Sidebar.vue";

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="projects" />
    <body class="bg-custom-blue min-h-screen">
        <Navbar></Navbar>
        <div class="flex">
            <Sidebar></Sidebar>
        </div>

        <div
            class="h=full w-98 overflow-hidden pr-8 mt-14 bg-white rounded-lg ml-72 pl-10 pt-4"
        >
            <div class="flex justify-between">
                <div
                    class="flex items-center hover:bg-gray-200 cursor-pointer p-1 mb-2"
                >
                    <h1 class="text-2xl font-bold">Project management</h1>
                    <i
                        class="fa fa-chevron-down ml-2 text-xs"
                        aria-hidden="true"
                    ></i>
                </div>
                <div class="flex items-center">
                    <div
                        class="flex items-center hover:bg-gray-200 cursor-pointer"
                    >
                        <svg
                            width="20px"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            transform="rotate(-45)"
                            stroke="#676879"
                        >
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g
                                id="SVGRepo_tracerCarrier"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            ></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M17 9V12C17 14.7614 14.7614 17 12 17M7 9V12C7 14.7614 9.23858 17 12 17M12 17V21M8 3V6M16 3V6M5 9H19"
                                    stroke="#000000"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </g>
                        </svg>
                        <h3 class="text-sm ml-4">integrate</h3>
                    </div>
                    <i class="far ml-6 fa-comment" aria-hidden="true"></i>
                    <i class="fa fa-user-circle ml-6 fa-xl"></i>
                    <div
                        class="flex border-2 ml-6 p-1 text-sm border-gray-300 border-solid hover:bg-gray-200 cursor-pointer"
                    >
                        Invite/1
                    </div>
                    <i
                        class="fa fa-ellipsis-h text-xl ml-4 p-2 hover:bg-gray-200 cursor-pointer"
                        aria-hidden="true"
                    ></i>
                </div>
            </div>
            <div class="flex border-b border-gray-400">
                <div
                    class="w-fit flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-200 cursor-pointer border-b border-blue-400"
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
                    <h3>Main Tables</h3>
                </div>
                <div
                    class="w-fit flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-200 cursor-pointer"
                >
                    <h3>Kanaban</h3>
                </div>
                <div
                    class="w-fit flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-200 cursor-pointer"
                >
                    <h3>Chart</h3>
                </div>
                <div
                    class="w-fit flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-200 cursor-pointer"
                >
                    <h3>Gantt</h3>
                </div>
                <div
                    class="w-fit flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-200 cursor-pointer"
                >
                    <h3>Calander</h3>
                </div>
                <div
                    class="w-fit flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-200 cursor-pointer"
                >
                    <i class="fa fa-plus text-gray-600 text-xs"></i>
                </div>
            </div>
            <div class="my-4 flex">
                <div class="flex items-center bg-blue-600 w-fit rounded-md">
                    <div
                        class="p-2 py-1 text-white text-sm border-r border-gray-600 hover:bg-blue-700 rounded-md cursor-pointer"
                    >
                        New task
                    </div>
                    <i
                        class="fa fa-chevron-down p-2 py-1 text-xs text-white hover:bg-blue-700 rounded-md cursor-pointer"
                    ></i>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-200 cursor-pointer"
                >
                    <i class="fa fa-search text-gray-500 mr-2"></i>
                    <h3>Search</h3>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-200 cursor-pointer"
                >
                    <i class="far fa-user-circle text-gray-500 mr-2"></i>
                    <h3>Person</h3>
                </div>
                <div
                    class="group ml-2 p-2 pr-0 py-0 flex items-center hover:bg-gray-200 cursor-pointer"
                >
                    <div
                        class="flex py-1 items-center pr-2 group-hover:border-r-2 group-hover:border-white"
                    >
                        <i class="fa fa-filter text-gray-500"></i>
                        <h3>Filter</h3>
                    </div>

                    <i
                        class="fa fa-chevron-down p-1 py-2 text-xs text-gray-500 group-hover:bg-gray-300 rounded-md cursor-pointer"
                    ></i>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-200 cursor-pointer"
                >
                    <i class="far fa-user-circle text-gray-500 mr-2"></i>
                    <h3>Sort</h3>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-200 cursor-pointer"
                >
                    <i class="far fa-user-circle text-gray-500 mr-2"></i>
                    <h3>Hide</h3>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-200 cursor-pointer"
                >
                    <i class="far fa-user-circle text-gray-500 mr-2"></i>
                    <h3>Group by</h3>
                </div>
                <div
                    class="ml-2 p-2 py-1 flex items-center hover:bg-gray-200 cursor-pointer"
                >
                    <i
                        class="fa fa-ellipsis-h text-xl px-0 hover:bg-gray-200 cursor-pointer"
                        aria-hidden="true"
                    ></i>
                </div>
            </div>
            <div class="flex items-center text-blue-500">
                <i class="fa fa-chevron-down p-1 py-2 text-xs rounded-md"></i>
                <h2 class="text-xl ml-3">To-Do</h2>
            </div>
        </div>
    </body>
</template>

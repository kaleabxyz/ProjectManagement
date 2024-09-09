<script setup>

import SideDetail from "@/Components/SideDetail.vue";
import Navbar from "@/Components/Navbar.vue";
import TextInput from "@/Components/TextInput.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { ref } from "vue";

const side = ref("active");
const sideDetail = ref(false);
const showUpdateFeed = ref(true);
const showRecentVisited = ref(true);
const showMyWorkspaces = ref(true);

const showSide = (val) => {
    side.value = val;
};
</script>

<template>
    <Head title="home" />
    <body class="bg-custom-blue min-h-screen w-full flex flex-col">
        <Navbar></Navbar>
        <div class="flex h-full ">
            <Sidebar @nav="showSide" />
            <router-view />
        </div>
        <div class="bg-custome-blue h-full w-full"
        :class="{
                    'pl-[275px] ': side === 'active',
                    'pl-7': side === 'inactive',
                }">

        
            <div
                
                class="h-full flex-1 w-full overflow-hidden mt-14 bg-white rounded-lg pt-4"
            >
                <div class="border-b-2 border-solid pb-4 shadow-lg">
                    <h2 class="font-thin mx-16 text-sm">
                        Good afternoon, kaleab!
                    </h2>
                    <h2 class="font-light mx-16 text-md">
                        Quickly access your recent boards, inbox and workspaces
                    </h2>
                </div>
                <div
                    class="ml-16 mt-4 h-full  w-full pr-80 p-6 rounded-lg shadow-lg"
                >
                    <div class="flex items-center">
                        <i
                            @click="showRecentVisited = !showRecentVisited"
                            class="fa fa-chevron-down cursor-pointer mr-2 text-xs"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-lg">Recently visited</h2>
                    </div>
                    <div v-if="showRecentVisited" class="flex flex-wrap">
                        <router-link to="/project">
                            <div
                                class="m-2 border-blue-200 border border-solid rounded-lg p-2 hover:shadow-xl transition-shadow duration-300"
                            >
                                <img
                                    class="rounded-lg w-80"
                                    src="/images/doc.svg"
                                    alt="loading"
                                />

                                <div
                                    class="flex justify-between items-center my-1"
                                >
                                    <div class="flex my-2 items-center">
                                        <svg
                                            fill="#bfbbbb"
                                            width="24px"
                                            height="24px"
                                            viewBox="0 0 56 56"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <g
                                                id="SVGRepo_bgCarrier"
                                                stroke-width="0"
                                            ></g>
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
                                        <h2 class="font-bold ml-2 text-md">
                                            New Board
                                        </h2>
                                    </div>

                                    <div>
                                        <i
                                            class="far fa-star mr-2"
                                            aria-hidden="true"
                                        ></i>
                                    </div>
                                </div>
                                <div class="flex row items-center my-1">
                                    <img
                                        class="w-7 mr-2"
                                        src="images/logoC.png"
                                        alt=""
                                    />
                                    <h3
                                        class="font-light text-sm text-gray-500"
                                    >
                                        work management > Main workspace
                                    </h3>
                                </div>
                            </div>
                        </router-link>
                        <div
                            class="m-2 border-blue-200 border border-solid rounded-lg p-2 hover:shadow-xl transition-shadow duration-300"
                        >
                            <img
                                class="rounded-lg w-80"
                                src="/images/doc.svg"
                                alt="loading"
                            />

                            <div class="flex justify-between items-center my-1">
                                <div class="flex my-1 items-center">
                                    <svg
                                        fill="#bfbbbb"
                                        width="24px"
                                        height="24px"
                                        viewBox="0 0 56 56"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <g
                                            id="SVGRepo_bgCarrier"
                                            stroke-width="0"
                                        ></g>
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
                                    <h2 class="font-bold ml-2 text-md">
                                        New Board
                                    </h2>
                                </div>

                                <div>
                                    <i
                                        class="far fa-star mr-2"
                                        aria-hidden="true"
                                    ></i>
                                </div>
                            </div>
                            <div class="flex row items-center">
                                <img
                                    class="w-7 mr-2"
                                    src="images/logoC.png"
                                    alt=""
                                />
                                <h3 class="font-light text-sm text-gray-500">
                                    work management > Main workspace
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mt-6">
                        <i
                            @click="showUpdateFeed = !showUpdateFeed"
                            class="fa fa-chevron-down cursor-pointer mr-2 text-xs"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-lg">Update feed(inbox)</h2>
                        <h3 class="bg-blue-500 px-2 rounded-full text-white">
                            1
                        </h3>
                    </div>
                    <div
                        v-if="showUpdateFeed"
                        class="flex w-full flex-col p-8 border rounded-lg border-gray-300 mt-4"
                    >
                        <div
                            class="flex items-center w-full justify-between border-b border-gray-300 pb-4"
                        >
                            <div class="flex items-center mr-72">
                                <img
                                    class="rounded-full w-10 h-10 object-cover mr-4"
                                    src="/images/logoC.png"
                                    alt="loading"
                                />

                                <div>
                                    <h3 class="text-md">Roy Mann</h3>
                                    <h3 class="text-md">Hi @kaleab,</h3>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i
                                    class="far fa-clock text-xs"
                                    aria-hidden="true"
                                ></i>
                                <h3 class="text-sm ml-1">12 jun</h3>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mt-8">
                        <i
                            @click="showMyWorkspaces = !showMyWorkspaces"
                            class="fa fa-chevron-down cursor-pointer mr-2 text-xs"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-lg">My workspaces</h2>
                    </div>
                    <div
                        v-if="showMyWorkspaces"
                        class="flex w-full gap-8 items-center flex-wrap"
                    >
                        <div
                            class="flex p-8 w-fit border rounded-lg border-gray-300 mt-4"
                        >
                            <div class="relative mr-6">
                                <h3
                                    class="bg-gray-500 w-fit text-white py-2 px-4 rounded-lg text-2xl"
                                >
                                    M
                                </h3>
                                <svg
                                    class="absolute left-8 top-5"
                                    width="34px"
                                    viewBox="0 0 24 24"
                                    fill="#000000"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g
                                        id="SVGRepo_bgCarrier"
                                        stroke-width="0"
                                    ></g>
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
                            </div>
                            <div class="mr-56">
                                <h2 class="text-xl">Main workspace</h2>
                                <div class="flex">
                                    <img
                                        class="w-7 mr-2"
                                        src="images/logoC.png"
                                        alt=""
                                    />
                                    <h2>work managment</h2>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex p-8 w-fit border rounded-lg border-gray-300 mt-4"
                        >
                            <div class="relative mr-6">
                                <h3
                                    class="bg-gray-500 w-fit text-white py-2 px-4 rounded-lg text-2xl"
                                >
                                    N
                                </h3>
                            </div>
                            <div class="mr-56">
                                <h2 class="text-xl">New workspace</h2>
                                <div class="flex">
                                    <img
                                        class="w-7 mr-2"
                                        src="images/logoC.png"
                                        alt=""
                                    />
                                    <h2>work managment</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</template>

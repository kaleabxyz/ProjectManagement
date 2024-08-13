<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Navbar from "@/Components/Navbar.vue";
import Sidebar from "@/Components/Sidebar.vue";
</script>

<template>
    <Head title="home" />
    <body class="bg-custom-blue min-h-screen">
        <Navbar></Navbar>
        <div class="flex">
            <Sidebar></Sidebar>
            <div class="h=full w-full mt-14 bg-white rounded-lg ml-72 pt-4">
                <div class="border-b-2 border-solid pb-4 shadow-lg">
                    <h2 class="font-thin mx-16">Good afternoon, kaleab!</h2>
                    <h2 class="font-light mx-16">
                        Quickly access your recent boards, inbox and workspaces
                    </h2>
                </div>
                <div class="ml-16 mt-4 h-full w-fit p-6 rounded-lg shadow-lg">
                    <div class="flex items-center">
                        <i
                            class="fa fa-chevron-down mr-2"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-2xl">Recently visited</h2>
                    </div>
                    <div class="flex flex-wrap">
                        <div
                            class="m-4 border-blue-200 border border-solid rounded-lg p-2 hover:shadow-xl transition-shadow duration-300"
                        >
                            <img
                                class="rounded-lg w-60"
                                src="/images/doc.svg"
                                alt="loading"
                            />

                            <div class="flex justify-between items-center my-2">
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
                                    <h2 class="font-bold ml-2 text-1xl">
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
                                <h3 class="font-light text-sm text-gray-500">
                                    work management > Main workspace
                                </h3>
                            </div>
                        </div>
                        <div
                            class="m-4 border-blue-200 border border-solid rounded-lg p-2 hover:shadow-xl transition-shadow duration-300"
                        >
                            <img
                                class="rounded-lg w-60"
                                src="/images/doc.svg"
                                alt="loading"
                            />

                            <div class="flex justify-between items-center my-2">
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
                                    <h2 class="font-bold ml-2 text-1xl">
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
                                <h3 class="font-light text-sm text-gray-500">
                                    work management > Main workspace
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mt-6">
                        <i
                            class="fa fa-chevron-down mr-2"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-2xl">Update feed(inbox)</h2>
                        <h3 class="bg-blue-500 px-2 rounded-full text-white">
                            1
                        </h3>
                    </div>
                    <div
                        class="flex flex-col p-8 border rounded-lg border-gray-300 mt-4"
                    >
                        <div
                            class="flex items-center justify-between border-b border-gray-300 pb-4"
                        >
                            <div class="flex items-center mr-72">
                                <img
                                    class="rounded-full w-20 h-20 object-cover mr-4"
                                    src="/images/logoC.png"
                                    alt="loading"
                                />

                                <div>
                                    <h3>Roy Mann</h3>
                                    <h3>Hi @kaleab,</h3>
                                </div>
                            </div>
                            <div class="ml-96">
                                <i class="far fa-clock" aria-hidden="true"></i>
                                <h3>13d</h3>
                            </div>
                        </div>
                        <div
                            class="flex mt-4 bg-gray-200 p-3 rounded-lg justify-between"
                        >
                            <div class="relative flex items-center">
                                <i class="fa fa-user-circle text-5xl"></i>
                                <i
                                    class="fa fa-user-circle text-5xl left-8 border border-white p-0 rounded-full absolute"
                                ></i>
                                <i
                                    class="fa text-white bg-blue-600 fa-plus text-2xl left-16 border border-white p-3 rounded-full absolute"
                                ></i>
                                <h3 class="ml-24">
                                    Invite your teammates and start
                                    collaborating
                                </h3>
                            </div>
                            <div class="items-center">
                                <button
                                    class="text-black px-4 py-2 rounded-lg hover:bg-gray-300 mr-4"
                                >
                                    No thanks
                                </button>
                                <button
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                                >
                                    Invite
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mt-8">
                        <i
                            class="fa fa-chevron-down mr-2"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-2xl">My workspaces</h2>
                    </div>
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
                </div>
            </div>
        </div>
    </body>
</template>

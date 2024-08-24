<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Navbar from "@/Components/Navbar.vue";
import Sidebar from "@/Components/Sidebar.vue";
import SideDetail from "@/Components/SideDetail.vue";
import TextInput from "@/Components/TextInput.vue";

import { ref, watch, onMounted, computed, onUnmounted } from "vue";

const showTable = ref(true);
const showCard = ref(false);

const side = ref("active");
const subItem = ref(false);
const showGroup = ref(true);
const addTask = ref("+ AddTask");
const discussionActive = ref(false);
const ProjectDetail = ref(false);
const taskUpdate = ref(false);
const projectName = ref("Project managemnt");
// Reactive state
const isEditing = ref(false);
const content = ref("Task 1"); // Initial content of h1
const projectDiscription = ref(
    "Filter by Board Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, ratione. Molestias perferendis incidunt quisquam eveniet harum"
);

const showHide = ref(false);

const showTasks = ref(false);
const showAll = ref(false);
const showOwner = ref(false);
const showStatus = ref(true);
const showDueDate = ref(true);
const showPriority = ref(true);
const showNotes = ref(true);
const showBudget = ref(true);
const showFiles = ref(true);
const showTimeLine = ref(true);
const showLastUpdate = ref(false);

const showSubTasks = ref(false);
const showOwnerS = ref(true);
const showStatusS = ref(true);
const showDueDateS = ref(true);
const showPriorityS = ref(true);
const showNotesS = ref(true);
const showBudgetS = ref(true);
const showFilesS = ref(true);
const showTimeLineS = ref(true);
const showLastUpdateS = ref(true);

const tasks = ref([
    {
        avatar: "", // Leave empty for default avatar
        initial: "K",
        title: "Task 2",
        status: "Done",
        statusClass: "bg-green-500",
        priority: "High",
        priorityClass: "bg-purple-700",
        dueDate: "19 Aug",
        dueDateClass: "text-gray-500",
    },
    {
        avatar: "", // Leave empty for default avatar
        initial: "",
        title: "Task 3",
        status: "Stuck",
        statusClass: "bg-red-500",
        priority: "Medium",
        priorityClass: "bg-blue-600",
        dueDate: "20 Aug",
        dueDateClass: "text-red-600",
    },
    {
        avatar: "https://via.placeholder.com/40/FF0000/FFFFFF?text=K", // Sample avatar URL
        initial: "K",
        title: "Task 1",
        status: "Working on it",
        statusClass: "bg-orange-500",
        priority: "Low",
        priorityClass: "bg-blue-400",
        dueDate: "18 Aug",
        dueDateClass: "text-red-600",
    },
    {
        avatar: "", // Leave empty for default avatar
        initial: "",
        title: "Task 4",
        status: "Not Started",
        statusClass: "bg-gray-400",
        priority: "None",
        priorityClass: "bg-gray-400",
        dueDate: "",
        dueDateClass: "text-gray-500",
    },
]);

// Methods
const enableEditing = () => {
    isEditing.value = true;
};
const clearInput = () => {
    addTask.value = ""; // Clear the input field content
};

const disableEditing = () => {
    isEditing.value = false;
};

const showSide = (val) => {
    side.value = val;
};

watch(isEditing, (newValue) => {
    if (newValue) {
        onMounted(() => {
            const input = ref.value.editableInput;
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);
        });
    }
});
const updateContent = ref(
    "Filter by Board Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, ratione. Molestias perferendis incidunt quisquam eveniet harum aliquam, ab mollitia facere quas cupiditate libero doloribus enim accusantium earum et ducimus? Cum? Filter by Board Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, ratione. Molestias perferendis incidunt quisquam eveniet harum aliquam, ab mollitia facere quas cupiditate libero doloribus enim accusantium earum et ducimus? Cum?"
);

const showFullDescription = ref(false);

const toggleFullDescription = () => {
    showFullDescription.value = !showFullDescription.value;
};

const truncatedDescription = computed(() => {
    if (showFullDescription.value || updateContent.value.length <= 190) {
        return updateContent.value;
    } else {
        return updateContent.value.substring(0, 190) + "...";
    }
});
const hidesideDetail = () => {
    discussionActive.value = false;
    ProjectDetail.value = false;
    taskUpdate.value = false;
    showHide.value = false;
};
const handleClickOutside = (event) => {
    if (
        !event.target.closest(".invite-content") &&
        (discussionActive.value ||
            ProjectDetail.value ||
            taskUpdate.value ||
            showHide.value)
    ) {
        hidesideDetail();
    }
};
document.addEventListener("click", handleClickOutside);

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <Head title="projects" />
    <body class="bg-custom-blue min-h-screen flex flex-col">
        <Navbar />
        <div class="flex">
            <Sidebar @nav="showSide" />
        </div>
        <SideDetail v-if="discussionActive" @click.stop class="">
            <div class="flex justify-between items-center p-6">
                <div class="flex my-2 items-center rounded-md">
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
                    <h2 class="ml-2 text-sm text-gray-600">
                        Project management
                    </h2>
                </div>
                <i
                    @click="discussionActive = false"
                    class="fa fa-times fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-200 cursor-pointer"
                    aria-hidden="true"
                ></i>
            </div>
            <h1 class="text-2xl px-6 pb-6 border-b">Board Discussion</h1>
            <div class="px-6 mt-16">
                <TextInput
                    class="w-full border-indigo-500 hover:bg-gray-200"
                    placeholder="Write an update for all board members to see "
                ></TextInput>
            </div>
            <div class="flex w-full h-full mt-8 overflow-y-auto">
                <div class="w-full px-6 overflow-y-auto">
                    <div
                        class="w-full flex flex-col relative justify-between bg-white border rounded-lg p-0"
                    >
                        <div
                            class="w-10 h-10 bg-blue-600 ml-6 mt-4 absolute -right-20 rounded-md flex items-center hover:bg-blue-700 justify-center"
                        >
                            <i class="fa fa-check text-white text-2xl"></i>
                        </div>
                        <div>
                            <div class="flex p-4">
                                <div class="rounded-full group/detail relative">
                                    <h1
                                        class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                    >
                                        K
                                    </h1>
                                    <div
                                        class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                    >
                                        <div class="flex px-8">
                                            <h1
                                                class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                            >
                                                K
                                            </h1>
                                            <div>
                                                <div class="flex">
                                                    <h1
                                                        class="font-extralight hover:underline cursor-pointer"
                                                    >
                                                        Kaleab
                                                    </h1>
                                                    <span
                                                        class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                    ></span>
                                                </div>
                                                <h1
                                                    class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                >
                                                    Admin
                                                </h1>
                                            </div>
                                        </div>
                                        <div
                                            class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                        >
                                            <h1>Contact Details</h1>
                                            <i
                                                class="fa fa-chevron-down ml-2 text-xs"
                                                aria-hidden="true"
                                            ></i>
                                            <div
                                                class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-16 flex items-center left-0 h-16 absolute bg-white"
                                            >
                                                <i
                                                    class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                ></i>
                                                <h1 class="text-gray-500">
                                                    kaleab@email.com
                                                </h1>
                                                <h1
                                                    class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                >
                                                    Copy
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div class="flex items-center">
                                            <h1
                                                class="font-extralight hover:underline cursor-pointer"
                                            >
                                                Kaleab
                                            </h1>
                                            <span
                                                class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                            ></span>
                                        </div>
                                        <div
                                            class="flex items-center text-gray-600"
                                        >
                                            <i class="far fa-clock"> </i>
                                            <h1
                                                class="ml-1 hover:underline cursor-pointer mr-1"
                                            >
                                                3h
                                            </h1>
                                            <span
                                                @click="toggleSideDetail"
                                                @click.stop
                                                class="h-full hover:bg-gray-100"
                                            >
                                                <i
                                                    class="far fa-bell m-1 text-sm"
                                                ></i>
                                            </span>
                                            <span>
                                                <i
                                                    class="fa fa-ellipsis-h text-lg py-1 px-1 hover:bg-gray-100 cursor-pointer"
                                                    aria-hidden="true"
                                                ></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center text-gray-500 text-sm"
                                    >
                                        <a href="#" class="hover:text-blue-600">
                                            <h1>Project management ></h1>
                                        </a>
                                        <a href="#" class="hover:text-blue-600">
                                            <h1>To do ></h1>
                                        </a>
                                        <a href="#" class="hover:text-blue-600">
                                            <h1>Task1</h1>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 p-4 flex items-center flex-col">
                                <p>
                                    {{ truncatedDescription }}
                                </p>
                                <button
                                    @click="toggleFullDescription"
                                    class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                >
                                    {{ showFullDescription ? "Less" : "More" }}
                                </button>
                            </div>
                        </div>
                        <div class="w-full flex mt-8 border-t items-center">
                            <div
                                class="w-1/2 flex justify-center items-center p-3 border-r hover:bg-gray-100"
                            >
                                <i
                                    class="far fa-thumbs-up text-gray-400 mr-2"
                                    aria-hidden="true"
                                ></i>
                                Like
                            </div>
                            <div
                                class="w-1/2 flex justify-center items-center p-3 hover:bg-gray-100"
                            >
                                <i
                                    class="fa mr-2 text-gray-400 fa-reply"
                                    aria-hidden="true"
                                ></i>
                                Reply
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full flex flex-col justify-between relative bg-white border rounded-lg p-0"
                    >
                        <div
                            class="w-10 h-10 bg-blue-600 ml-6 mt-4 absolute -right-20 rounded-md flex items-center hover:bg-blue-700 justify-center"
                        >
                            <i class="fa fa-check text-white text-2xl"></i>
                        </div>
                        <div>
                            <div class="flex p-4">
                                <div class="rounded-full group/detail relative">
                                    <h1
                                        class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                    >
                                        K
                                    </h1>
                                    <div
                                        class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                    >
                                        <div class="flex px-8">
                                            <h1
                                                class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                            >
                                                K
                                            </h1>
                                            <div>
                                                <div class="flex">
                                                    <h1
                                                        class="font-extralight hover:underline cursor-pointer"
                                                    >
                                                        Kaleab
                                                    </h1>
                                                    <span
                                                        class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                    ></span>
                                                </div>
                                                <h1
                                                    class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                >
                                                    Admin
                                                </h1>
                                            </div>
                                        </div>
                                        <div
                                            class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                        >
                                            <h1>Contact Details</h1>
                                            <i
                                                class="fa fa-chevron-down ml-2 text-xs"
                                                aria-hidden="true"
                                            ></i>
                                            <div
                                                class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-16 flex items-center left-0 h-16 absolute bg-white"
                                            >
                                                <i
                                                    class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                ></i>
                                                <h1 class="text-gray-500">
                                                    kaleab@email.com
                                                </h1>
                                                <h1
                                                    class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                >
                                                    Copy
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full">
                                    <div class="">
                                        <div class="bg-gray-100 rounded-lg">
                                            <h1
                                                class="font-extralight text-blue-500 px-4 py-2 hover:underline cursor-pointer"
                                            >
                                                Kaleab
                                            </h1>
                                            <div
                                                class="mt-1 p-4 flex items-center flex-col"
                                            >
                                                <p>
                                                    {{ truncatedDescription }}
                                                </p>
                                                <button
                                                    @click="
                                                        toggleFullDescription
                                                    "
                                                    class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                                >
                                                    {{
                                                        showFullDescription
                                                            ? "Less"
                                                            : "More"
                                                    }}
                                                </button>
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-center text-gray-600"
                                        >
                                            <i class="far fa-clock"> </i>
                                            <h1
                                                class="ml-1 mt-2 hover:underline cursor-pointer mr-1"
                                            >
                                                3h
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full flex mt-2 px-4 py-2 items-center">
                            <div class="rounded-full group/detail relative">
                                <h1
                                    class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                >
                                    K
                                </h1>
                                <div
                                    class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                >
                                    <div class="flex px-8">
                                        <h1
                                            class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                        >
                                            K
                                        </h1>
                                        <div>
                                            <div class="flex">
                                                <h1
                                                    class="font-extralight hover:underline cursor-pointer"
                                                >
                                                    Kaleab
                                                </h1>
                                                <span
                                                    class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                ></span>
                                            </div>
                                            <h1
                                                class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                            >
                                                Admin
                                            </h1>
                                        </div>
                                    </div>
                                    <div
                                        class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                    >
                                        <h1>Contact Details</h1>
                                        <i
                                            class="fa fa-chevron-down ml-2 text-xs"
                                            aria-hidden="true"
                                        ></i>
                                        <div
                                            class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-16 flex items-center left-0 h-16 absolute bg-white"
                                        >
                                            <i
                                                class="far fa-envelope mr-2 font-extralight text-gray-500"
                                            ></i>
                                            <h1 class="text-gray-500">
                                                kaleab@email.com
                                            </h1>
                                            <h1
                                                class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                            >
                                                Copy
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <TextInput
                                class="w-full"
                                placeholder="Write a reply..."
                            ></TextInput>
                        </div>
                    </div>
                </div>
            </div>
        </SideDetail>

        <div
            :class="{
                'ml-72': side === 'active',
                'ml-8': side === 'inactive',
            }"
            class="h-full flex-1 w-98 overflow-hidden pr-8 mt-14 bg-white rounded-lg pl-10 pt-4"
        >
            <div class="flex justify-between relative">
                <div
                    @click="ProjectDetail = !ProjectDetail"
                    @click.stop
                    class="flex items-center hover:bg-gray-100 cursor-pointer p-1 mb-2"
                >
                    <h1 class="text-2xl font-bold">Project management</h1>
                    <i
                        class="fa fa-chevron-down ml-2 text-xs"
                        aria-hidden="true"
                    ></i>
                    <div
                        v-if="ProjectDetail"
                        @click.stop
                        class="absolute top-10 p-4 border-b z-10 bg-white shadow-lg w-96 rounded-lg"
                    >
                        <input
                            class="border-0 text-lg px-0"
                            type="text"
                            v-model="projectName"
                        />

                        <textarea
                            type="text"
                            rows="5"
                            v-model="projectDiscription"
                            ref="editableInput"
                            @blur="disableEditing"
                            @keyup.enter="disableEditing"
                            class="border-0 h-fit border-gray-300 text-sm rounded p-2 w-full"
                        />

                        <h1 class="font-bold border-t pt-4 border-gray-500">
                            Board info
                        </h1>
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="py-2">Owner</h1>
                                <h1 class="py-1 flex items-center">
                                    Created by
                                </h1>
                            </div>
                            <div>
                                <div
                                    class="flex items-center px-2 py-1 hover:bg-gray-100"
                                >
                                    <h1
                                        class="w-fit text-xl px-2 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                    >
                                        K
                                    </h1>
                                    <h1 class="mr-8">Kaleab</h1>
                                </div>
                                <div
                                    class="flex items-center px-2 py-1 hover:bg-gray-100"
                                >
                                    <h1
                                        class="w-fit text-xl px-2 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                    >
                                        K
                                    </h1>
                                    <h1>on</h1>
                                    <h1 class="ml-2">Aug 19, 2024</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div
                        class="flex items-center hover:bg-gray-100 cursor-pointer"
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
                    <span
                        @click="discussionActive = !discussionActive"
                        @click.stop
                    >
                        <i class="far ml-6 fa-comment" aria-hidden="true"></i>
                    </span>
                    <i class="fa fa-user-circle ml-6 fa-xl"></i>
                    <div
                        class="flex border-2 ml-6 p-1 text-sm border-gray-300 border-solid hover:bg-gray-100 cursor-pointer"
                    >
                        Invite/1
                    </div>
                    <i
                        class="fa fa-ellipsis-h text-xl ml-4 p-2 hover:bg-gray-100 cursor-pointer"
                        aria-hidden="true"
                    ></i>
                </div>
            </div>
            <div class="flex border-b border-gray-400">
                <div
                    @click="
                        showTable = true;
                        showCard = false;
                    "
                    :class="{
                        'border-b-2 rounded-b-none border-blue-400':
                            showTable === true,
                    }"
                    class="group relative w-fit group flex items-center pb-1 p-4 pt-1 rounded-md hover:pl-1 hover:pr-7 hover:bg-gray-100 cursor-pointer ease-in"
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
                    <span class="absolute hidden right-1 group-hover:block">
                        <i
                            class="fa fa-ellipsis-h text-sm ml-1 rounded-md p-1 hover:bg-blue-200 cursor-pointer"
                            aria-hidden="true"
                        ></i>
                    </span>
                </div>
                <div
                    @click="
                        showCard = true;
                        showTable = false;
                    "
                    :class="{
                        'border-b-2 rounded-b-none border-blue-400': showCard,
                    }"
                    class="w-fit group relative hover:pl-1 hover:pr-7 flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-100 cursor-pointer"
                >
                    <h3>Card</h3>
                    <span class="absolute hidden right-1 group-hover:block">
                        <i
                            class="fa fa-ellipsis-h text-sm ml-1 rounded-md p-1 hover:bg-blue-200 cursor-pointer"
                            aria-hidden="true"
                        ></i>
                    </span>
                </div>
                <div
                    class="w-fit group relative hover:pl-1 hover:pr-7 flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-100 cursor-pointer"
                >
                    <h3>Chart</h3>
                    <span class="absolute hidden right-1 group-hover:block">
                        <i
                            class="fa fa-ellipsis-h text-sm ml-1 rounded-md p-1 hover:bg-blue-200 cursor-pointer"
                            aria-hidden="true"
                        ></i>
                    </span>
                </div>
                <div
                    class="w-fit group relative hover:pl-1 hover:pr-7 flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-100 cursor-pointer"
                >
                    <h3>Gantt</h3>
                    <span class="absolute hidden right-1 group-hover:block">
                        <i
                            class="fa fa-ellipsis-h text-sm ml-1 rounded-md p-1 hover:bg-blue-200 cursor-pointer"
                            aria-hidden="true"
                        ></i>
                    </span>
                </div>
                <div
                    class="w-fit group relative hover:pl-1 hover:pr-7 flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-100 cursor-pointer"
                >
                    <h3>Calander</h3>
                    <span class="absolute hidden right-1 group-hover:block">
                        <i
                            class="fa fa-ellipsis-h text-sm ml-1 rounded-md p-1 hover:bg-blue-200 cursor-pointer"
                            aria-hidden="true"
                        ></i>
                    </span>
                </div>
                <div
                    class="w-fit group relative hover:pl-1 hover:pr-7 flex items-center pb-1 p-4 pt-1 rounded-md hover:bg-gray-100 cursor-pointer"
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
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <i class="fa fa-search text-gray-500 mr-2"></i>
                    <h3>Search</h3>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <i class="far fa-user-circle text-gray-500 mr-2"></i>
                    <h3>Person</h3>
                </div>
                <div
                    class="group ml-2 p-2 pr-0 py-0 flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <div
                        class="flex py-1 items-center pr-2 group-hover:border-solid group-hover:border-r-2 group-hover:border-white"
                    >
                        <i class="fa fa-filter text-gray-500"></i>
                        <h3>Filter</h3>
                    </div>

                    <i
                        class="fa fa-chevron-down group-hover:ml-0 p-1 py-2 text-xs text-gray-500 group-hover:bg-gray-300 rounded-md cursor-pointer"
                    ></i>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <i class="far fa-user-circle text-gray-500 mr-2"></i>
                    <h3>Sort</h3>
                </div>
                <div
                    @click="showHide = !showHide"
                    @click.stop
                    class="ml-2 p-4 py-1 relative flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <i class="far fa-eye-slash text-gray-500 mr-2"></i>
                    <h3>Hide</h3>
                    <div
                        v-if="showHide"
                        @click.stop
                        class="absolute rounded-lg p-6 w-72 overflow-y-auto h-44 top-10 bg-white shadow-md"
                    >
                        <h1 class="text-xl font-extralight">Display columns</h1>
                        <div class="flex items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showAll"
                            />
                            <h1>All columns</h1>
                        </div>
                        <div class="flex items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showTasks"
                            />
                            <h1>All Task items</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showOwner"
                            />
                            <h1>Owner</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showStatus"
                            />
                            <h1>Status</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showDueDate"
                            />
                            <h1>Due date</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showPriority"
                            />
                            <h1>Priority</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showNotes"
                            />
                            <h1>Notes</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showBudget"
                            />
                            <h1>Budget</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showFiles"
                            />
                            <h1>Files</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showTimeLine"
                            />
                            <h1>Timeline</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showLastUpdate"
                            />
                            <h1>Last Update</h1>
                        </div>
                        <div class="flex items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showSubTasks"
                            />
                            <h1>All subtask items</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showOwnerS"
                            />
                            <h1>Owner</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showStatusS"
                            />
                            <h1>Status</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showDueDateS"
                            />
                            <h1>Due date</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showPriorityS"
                            />
                            <h1>Priority</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showNotesS"
                            />
                            <h1>Notes</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showBudgetS"
                            />
                            <h1>Budget</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showFilesS"
                            />
                            <h1>Files</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showTimeLineS"
                            />
                            <h1>Timeline</h1>
                        </div>
                        <div class="flex ml-4 items-center mt-4">
                            <input
                                type="checkbox"
                                class="mr-2"
                                v-model="showLastUpdateS"
                            />
                            <h1>Last Update</h1>
                        </div>
                    </div>
                </div>
                <div
                    class="ml-2 p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <i class="far fa-user-circle text-gray-500 mr-2"></i>
                    <h3>Group by</h3>
                </div>
                <div
                    class="ml-2 p-2 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <i
                        class="fa fa-ellipsis-h text-xl px-0 hover:bg-gray-100 cursor-pointer"
                        aria-hidden="true"
                    ></i>
                </div>
            </div>
            <div
                v-if="showTable"
                class="flex group items-center group text-blue-500 w-fit"
            >
                <span
                    class="absolute -left-8 items-center hidden group-hover:block"
                >
                    <i
                        class="fa fa-ellipsis-h text-xl p-2 text-black hover:bg-gray-100 cursor-pointer"
                        aria-hidden="true"
                    ></i>
                </span>
                <span @click="showGroup = !showGroup" v-if="showGroup">
                    <i
                        class="fa fa-chevron-down p-1 py-2 text-xs rounded-md"
                    ></i>
                </span>
                <span @click="showGroup = !showGroup" v-if="!showGroup">
                    <i
                        class="fa fa-chevron-right p-2 py-1 text-xs rounded-md"
                    ></i>
                </span>

                <h2 class="text-xl ml-3">To-Do</h2>
            </div>

            <div v-if="showTable" class="overflow-x-auto rounded-md">
                <table
                    class="min-w-full text-left border border-b-0 border-l-0 border-gray-300 border-t-0 text-sm text-gray-500"
                >
                    <thead class="bg-white text-xs uppercase text-gray-700">
                        <tr class="flex h-7 group">
                            <th
                                class="pl-4 pr-4 w-fit flex items-center border-l-8 border-l-blue-300 border border-gray-100"
                            >
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600"
                                />
                            </th>
                            <th
                                class="px-6 py-2 border flex items-center justify-center w-56"
                            >
                                {{ showGroup ? "Task" : "3 Tasks/1 subtask" }}
                            </th>
                            <th
                                v-if="showOwner || showAll || showTasks"
                                class="px-6 py-1 flex items-center justify-center border"
                            >
                                Owner
                            </th>
                            <th
                                v-if="showStatus || showAll || showTasks"
                                class="px-6 py-1 w-36 flex items-center justify-center border"
                            >
                                Status
                            </th>
                            <th
                                v-if="showDueDate || showAll || showTasks"
                                class="px-6 py-1 flex items-center w-36 justify-center border"
                            >
                                Due date
                            </th>
                            <th
                                v-if="showPriority || showAll || showTasks"
                                class="px-6 py-1 flex items-center w-28 justify-center border"
                            >
                                Priority
                            </th>
                            <th
                                v-if="showNotes || showAll || showTasks"
                                class="px-6 py-1 w-36 flex items-center justify-center border"
                            >
                                Notes
                            </th>
                            <th
                                v-if="showBudget || showAll || showTasks"
                                class="px-6 py-1 flex items-center justify-center border"
                            >
                                Budget
                            </th>
                            <th
                                v-if="showFiles || showAll || showTasks"
                                class="px-6 py-1 w-36 flex items-center justify-center border"
                            >
                                Files
                            </th>
                            <th
                                v-if="showTimeLine || showAll || showTasks"
                                class="px-6 py-1 flex items-center justify-center border"
                            >
                                Timeline
                            </th>
                            <th
                                v-if="showLastUpdate || showAll || showTasks"
                                class="px-6 py-1 flex items-center justify-center border w-44"
                            >
                                Last updated
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Task 1 -->
                        <section v-if="showGroup">
                            <SideDetail
                                v-if="taskUpdate"
                                @click.stop
                                class="border-l-8 overflow-y-auto border-gray-300"
                            >
                                <div class="p-4">
                                    <i
                                        @click="hidesideDetail"
                                        class="fa fa-times fa-font-extralight text-gray-500 text-2xl p-1 hover:bg-gray-200 cursor-pointer"
                                        aria-hidden="true"
                                    ></i>

                                    <input
                                        type="text"
                                        v-model="content"
                                        ref="editableInput"
                                        class="border-0 px-0 border-gray-300 text-2xl rounded p-2 w-full"
                                    />
                                </div>
                                <div
                                    class="flex items-center border-b-2 mt-6 text-black pl-6 font-extralight text-sm"
                                >
                                    <div
                                        class="p-4 py-1 flex border-b-2 border-blue-400 items-center hover:bg-gray-100 cursor-pointer"
                                    >
                                        <h3>Updates</h3>
                                    </div>
                                    <span class="w-0.5 h-4 bg-gray-400"></span>
                                    <div
                                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                                    >
                                        <h3>Files</h3>
                                    </div>
                                    <span class="w-0.5 h-4 bg-gray-400"></span>
                                    <div
                                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                                    >
                                        <h3>Activity log</h3>
                                    </div>
                                </div>
                                <div class="px-6 mt-16">
                                    <TextInput
                                        class="w-full border-indigo-500 hover:bg-gray-200"
                                        placeholder="Write an update for all board members to see "
                                    ></TextInput>
                                </div>
                                <div
                                    class="flex w-full h-full mt-8 text-black overflow-y-auto"
                                >
                                    <div class="w-full px-6 overflow-y-auto">
                                        <div
                                            class="w-full flex flex-col relative justify-between bg-white border rounded-lg p-0"
                                        >
                                            <div>
                                                <div class="flex p-4">
                                                    <div
                                                        class="rounded-full group/detail relative"
                                                    >
                                                        <h1
                                                            class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                        >
                                                            K
                                                        </h1>
                                                        <div
                                                            class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                                        >
                                                            <div
                                                                class="flex px-8"
                                                            >
                                                                <h1
                                                                    class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                >
                                                                    K
                                                                </h1>
                                                                <div>
                                                                    <div
                                                                        class="flex"
                                                                    >
                                                                        <h1
                                                                            class="font-extralight hover:underline cursor-pointer"
                                                                        >
                                                                            Kaleab
                                                                        </h1>
                                                                        <span
                                                                            class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                        ></span>
                                                                    </div>
                                                                    <h1
                                                                        class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                    >
                                                                        Admin
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                                            >
                                                                <h1>
                                                                    Contact
                                                                    Details
                                                                </h1>
                                                                <i
                                                                    class="fa fa-chevron-down ml-2 text-xs"
                                                                    aria-hidden="true"
                                                                ></i>
                                                                <div
                                                                    class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-12 flex items-center left-0 h-16 absolute bg-white"
                                                                >
                                                                    <i
                                                                        class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                                    ></i>
                                                                    <h1
                                                                        class="text-gray-500"
                                                                    >
                                                                        kaleab@email.com
                                                                    </h1>
                                                                    <h1
                                                                        class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                                    >
                                                                        Copy
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="w-full">
                                                        <div
                                                            class="flex items-center justify-between"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <h1
                                                                    class="font-extralight hover:underline cursor-pointer"
                                                                >
                                                                    Kaleab
                                                                </h1>
                                                                <span
                                                                    class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                ></span>
                                                            </div>
                                                            <div
                                                                class="flex items-center text-gray-600"
                                                            >
                                                                <i
                                                                    class="far fa-clock"
                                                                >
                                                                </i>
                                                                <h1
                                                                    class="ml-1 hover:underline cursor-pointer mr-1"
                                                                >
                                                                    3h
                                                                </h1>
                                                                <span
                                                                    @click="
                                                                        toggleSideDetail
                                                                    "
                                                                    @click.stop
                                                                    class="h-full hover:bg-gray-100"
                                                                >
                                                                    <i
                                                                        class="far fa-bell m-1 text-sm"
                                                                    ></i>
                                                                </span>
                                                                <span>
                                                                    <i
                                                                        class="fa fa-ellipsis-h text-lg py-1 px-1 hover:bg-gray-100 cursor-pointer"
                                                                        aria-hidden="true"
                                                                    ></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="flex items-center text-gray-500 text-sm"
                                                        >
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>
                                                                    Project
                                                                    management >
                                                                </h1>
                                                            </a>
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>To do ></h1>
                                                            </a>
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>Task1</h1>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-4 p-4 flex items-center flex-col"
                                                >
                                                    <p>
                                                        {{
                                                            truncatedDescription
                                                        }}
                                                    </p>
                                                    <button
                                                        @click="
                                                            toggleFullDescription
                                                        "
                                                        class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                                    >
                                                        {{
                                                            showFullDescription
                                                                ? "Less"
                                                                : "More"
                                                        }}
                                                    </button>
                                                </div>
                                            </div>
                                            <div
                                                class="w-full flex mt-8 border-t items-center"
                                            >
                                                <div
                                                    class="w-1/2 flex justify-center items-center p-3 border-r hover:bg-gray-100"
                                                >
                                                    <i
                                                        class="far fa-thumbs-up text-gray-400 mr-2"
                                                        aria-hidden="true"
                                                    ></i>
                                                    Like
                                                </div>
                                                <div
                                                    class="w-1/2 flex justify-center items-center p-3 hover:bg-gray-100"
                                                >
                                                    <i
                                                        class="fa mr-2 text-gray-400 fa-reply"
                                                        aria-hidden="true"
                                                    ></i>
                                                    Reply
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="w-full flex flex-col justify-between relative bg-white border rounded-lg p-0"
                                        >
                                            <div>
                                                <div class="flex p-4">
                                                    <div
                                                        class="rounded-full group/detail relative"
                                                    >
                                                        <h1
                                                            class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                        >
                                                            K
                                                        </h1>
                                                        <div
                                                            class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                                        >
                                                            <div
                                                                class="flex px-8"
                                                            >
                                                                <h1
                                                                    class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                >
                                                                    K
                                                                </h1>
                                                                <div>
                                                                    <div
                                                                        class="flex"
                                                                    >
                                                                        <h1
                                                                            class="font-extralight hover:underline cursor-pointer"
                                                                        >
                                                                            Kaleab
                                                                        </h1>
                                                                        <span
                                                                            class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                        ></span>
                                                                    </div>
                                                                    <h1
                                                                        class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                    >
                                                                        Admin
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                                            >
                                                                <h1>
                                                                    Contact
                                                                    Details
                                                                </h1>
                                                                <i
                                                                    class="fa fa-chevron-down ml-2 text-xs"
                                                                    aria-hidden="true"
                                                                ></i>
                                                                <div
                                                                    class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-12 flex items-center left-0 h-16 absolute bg-white"
                                                                >
                                                                    <i
                                                                        class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                                    ></i>
                                                                    <h1
                                                                        class="text-gray-500"
                                                                    >
                                                                        kaleab@email.com
                                                                    </h1>
                                                                    <h1
                                                                        class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                                    >
                                                                        Copy
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="w-full">
                                                        <div class="">
                                                            <div
                                                                class="bg-gray-100 rounded-lg"
                                                            >
                                                                <h1
                                                                    class="font-extralight text-blue-500 px-4 py-2 hover:underline cursor-pointer"
                                                                >
                                                                    Kaleab
                                                                </h1>
                                                                <div
                                                                    class="mt-1 p-4 flex items-center flex-col"
                                                                >
                                                                    <p>
                                                                        {{
                                                                            truncatedDescription
                                                                        }}
                                                                    </p>
                                                                    <button
                                                                        @click="
                                                                            toggleFullDescription
                                                                        "
                                                                        class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                                                    >
                                                                        {{
                                                                            showFullDescription
                                                                                ? "Less"
                                                                                : "More"
                                                                        }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="flex items-center text-gray-600"
                                                            >
                                                                <i
                                                                    class="far fa-clock"
                                                                >
                                                                </i>
                                                                <h1
                                                                    class="ml-1 mt-2 hover:underline cursor-pointer mr-1"
                                                                >
                                                                    3h
                                                                </h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="w-full flex mt-2 px-4 py-2 items-center"
                                            >
                                                <div
                                                    class="rounded-full group/detail relative"
                                                >
                                                    <h1
                                                        class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                    >
                                                        K
                                                    </h1>
                                                    <div
                                                        class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                                    >
                                                        <div class="flex px-8">
                                                            <h1
                                                                class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                            >
                                                                K
                                                            </h1>
                                                            <div>
                                                                <div
                                                                    class="flex"
                                                                >
                                                                    <h1
                                                                        class="font-extralight hover:underline cursor-pointer"
                                                                    >
                                                                        Kaleab
                                                                    </h1>
                                                                    <span
                                                                        class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                    ></span>
                                                                </div>
                                                                <h1
                                                                    class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                >
                                                                    Admin
                                                                </h1>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                                        >
                                                            <h1>
                                                                Contact Details
                                                            </h1>
                                                            <i
                                                                class="fa fa-chevron-down ml-2 text-xs"
                                                                aria-hidden="true"
                                                            ></i>
                                                            <div
                                                                class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-12 flex items-center left-0 h-16 absolute bg-white"
                                                            >
                                                                <i
                                                                    class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                                ></i>
                                                                <h1
                                                                    class="text-gray-500"
                                                                >
                                                                    kaleab@email.com
                                                                </h1>
                                                                <h1
                                                                    class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                                >
                                                                    Copy
                                                                </h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <TextInput
                                                    class="w-full"
                                                    placeholder="Write a reply..."
                                                ></TextInput>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex w-full h-full mt-8 text-black overflow-y-auto"
                                >
                                    <div class="w-full px-6 overflow-y-auto">
                                        <div
                                            class="w-full flex flex-col relative justify-between bg-white border rounded-lg p-0"
                                        >
                                            <div>
                                                <div class="flex p-4">
                                                    <div
                                                        class="rounded-full group/detail relative"
                                                    >
                                                        <h1
                                                            class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                        >
                                                            K
                                                        </h1>
                                                        <div
                                                            class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                                        >
                                                            <div
                                                                class="flex px-8"
                                                            >
                                                                <h1
                                                                    class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                >
                                                                    K
                                                                </h1>
                                                                <div>
                                                                    <div
                                                                        class="flex"
                                                                    >
                                                                        <h1
                                                                            class="font-extralight hover:underline cursor-pointer"
                                                                        >
                                                                            Kaleab
                                                                        </h1>
                                                                        <span
                                                                            class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                        ></span>
                                                                    </div>
                                                                    <h1
                                                                        class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                    >
                                                                        Admin
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                                            >
                                                                <h1>
                                                                    Contact
                                                                    Details
                                                                </h1>
                                                                <i
                                                                    class="fa fa-chevron-down ml-2 text-xs"
                                                                    aria-hidden="true"
                                                                ></i>
                                                                <div
                                                                    class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-12 flex items-center left-0 h-16 absolute bg-white"
                                                                >
                                                                    <i
                                                                        class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                                    ></i>
                                                                    <h1
                                                                        class="text-gray-500"
                                                                    >
                                                                        kaleab@email.com
                                                                    </h1>
                                                                    <h1
                                                                        class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                                    >
                                                                        Copy
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="w-full">
                                                        <div
                                                            class="flex items-center justify-between"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <h1
                                                                    class="font-extralight hover:underline cursor-pointer"
                                                                >
                                                                    Kaleab
                                                                </h1>
                                                                <span
                                                                    class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                ></span>
                                                            </div>
                                                            <div
                                                                class="flex items-center text-gray-600"
                                                            >
                                                                <i
                                                                    class="far fa-clock"
                                                                >
                                                                </i>
                                                                <h1
                                                                    class="ml-1 hover:underline cursor-pointer mr-1"
                                                                >
                                                                    3h
                                                                </h1>
                                                                <span
                                                                    @click="
                                                                        toggleSideDetail
                                                                    "
                                                                    @click.stop
                                                                    class="h-full hover:bg-gray-100"
                                                                >
                                                                    <i
                                                                        class="far fa-bell m-1 text-sm"
                                                                    ></i>
                                                                </span>
                                                                <span>
                                                                    <i
                                                                        class="fa fa-ellipsis-h text-lg py-1 px-1 hover:bg-gray-100 cursor-pointer"
                                                                        aria-hidden="true"
                                                                    ></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="flex items-center text-gray-500 text-sm"
                                                        >
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>
                                                                    Project
                                                                    management >
                                                                </h1>
                                                            </a>
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>To do ></h1>
                                                            </a>
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>Task1</h1>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-4 p-4 flex items-center flex-col"
                                                >
                                                    <p>
                                                        {{
                                                            truncatedDescription
                                                        }}
                                                    </p>
                                                    <button
                                                        @click="
                                                            toggleFullDescription
                                                        "
                                                        class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                                    >
                                                        {{
                                                            showFullDescription
                                                                ? "Less"
                                                                : "More"
                                                        }}
                                                    </button>
                                                </div>
                                            </div>
                                            <div
                                                class="w-full flex mt-8 border-t items-center"
                                            >
                                                <div
                                                    class="w-1/2 flex justify-center items-center p-3 border-r hover:bg-gray-100"
                                                >
                                                    <i
                                                        class="far fa-thumbs-up text-gray-400 mr-2"
                                                        aria-hidden="true"
                                                    ></i>
                                                    Like
                                                </div>
                                                <div
                                                    class="w-1/2 flex justify-center items-center p-3 hover:bg-gray-100"
                                                >
                                                    <i
                                                        class="fa mr-2 text-gray-400 fa-reply"
                                                        aria-hidden="true"
                                                    ></i>
                                                    Reply
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="w-full flex flex-col justify-between relative bg-white border rounded-lg p-0"
                                        >
                                            <div>
                                                <div class="flex p-4">
                                                    <div
                                                        class="rounded-full group/detail relative"
                                                    >
                                                        <h1
                                                            class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                        >
                                                            K
                                                        </h1>
                                                        <div
                                                            class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                                        >
                                                            <div
                                                                class="flex px-8"
                                                            >
                                                                <h1
                                                                    class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                >
                                                                    K
                                                                </h1>
                                                                <div>
                                                                    <div
                                                                        class="flex"
                                                                    >
                                                                        <h1
                                                                            class="font-extralight hover:underline cursor-pointer"
                                                                        >
                                                                            Kaleab
                                                                        </h1>
                                                                        <span
                                                                            class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                        ></span>
                                                                    </div>
                                                                    <h1
                                                                        class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                    >
                                                                        Admin
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                                            >
                                                                <h1>
                                                                    Contact
                                                                    Details
                                                                </h1>
                                                                <i
                                                                    class="fa fa-chevron-down ml-2 text-xs"
                                                                    aria-hidden="true"
                                                                ></i>
                                                                <div
                                                                    class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-12 flex items-center left-0 h-16 absolute bg-white"
                                                                >
                                                                    <i
                                                                        class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                                    ></i>
                                                                    <h1
                                                                        class="text-gray-500"
                                                                    >
                                                                        kaleab@email.com
                                                                    </h1>
                                                                    <h1
                                                                        class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                                    >
                                                                        Copy
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="w-full">
                                                        <div class="">
                                                            <div
                                                                class="bg-gray-100 rounded-lg"
                                                            >
                                                                <h1
                                                                    class="font-extralight text-blue-500 px-4 py-2 hover:underline cursor-pointer"
                                                                >
                                                                    Kaleab
                                                                </h1>
                                                                <div
                                                                    class="mt-1 p-4 flex items-center flex-col"
                                                                >
                                                                    <p>
                                                                        {{
                                                                            truncatedDescription
                                                                        }}
                                                                    </p>
                                                                    <button
                                                                        @click="
                                                                            toggleFullDescription
                                                                        "
                                                                        class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                                                    >
                                                                        {{
                                                                            showFullDescription
                                                                                ? "Less"
                                                                                : "More"
                                                                        }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="flex items-center text-gray-600"
                                                            >
                                                                <i
                                                                    class="far fa-clock"
                                                                >
                                                                </i>
                                                                <h1
                                                                    class="ml-1 mt-2 hover:underline cursor-pointer mr-1"
                                                                >
                                                                    3h
                                                                </h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="w-full flex mt-2 px-4 py-2 items-center"
                                            >
                                                <div
                                                    class="rounded-full group/detail relative"
                                                >
                                                    <h1
                                                        class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                    >
                                                        K
                                                    </h1>
                                                    <div
                                                        class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                                    >
                                                        <div class="flex px-8">
                                                            <h1
                                                                class="py-3 w-fit text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                            >
                                                                K
                                                            </h1>
                                                            <div>
                                                                <div
                                                                    class="flex"
                                                                >
                                                                    <h1
                                                                        class="font-extralight hover:underline cursor-pointer"
                                                                    >
                                                                        Kaleab
                                                                    </h1>
                                                                    <span
                                                                        class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                    ></span>
                                                                </div>
                                                                <h1
                                                                    class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                >
                                                                    Admin
                                                                </h1>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="border-t group/contact w-full relative border-gray-500 hover:bg-gray-100 cursor-pointer flex items-center justify-center border-b-0 py-4"
                                                        >
                                                            <h1>
                                                                Contact Details
                                                            </h1>
                                                            <i
                                                                class="fa fa-chevron-down ml-2 text-xs"
                                                                aria-hidden="true"
                                                            ></i>
                                                            <div
                                                                class="w-64 gr transition-opacity duration-1000 ease-in-out opacity-0 group-hover/contact:opacity-100 rounded-lg px-4 shadow-lg py-2 top-12 flex items-center left-0 h-16 absolute bg-white"
                                                            >
                                                                <i
                                                                    class="far fa-envelope mr-2 font-extralight text-gray-500"
                                                                ></i>
                                                                <h1
                                                                    class="text-gray-500"
                                                                >
                                                                    kaleab@email.com
                                                                </h1>
                                                                <h1
                                                                    class="bg-blue-400 text-sm ml-4 rounded-md hover:bg-blue-500 p-1"
                                                                >
                                                                    Copy
                                                                </h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <TextInput
                                                    class="w-full"
                                                    placeholder="Write a reply..."
                                                ></TextInput>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </SideDetail>
                            <tr
                                class="border w-fit group flex bg-white hover:bg-gray-100"
                            >
                                <span
                                    class="absolute z-10 -left-72 items-center hidden group-hover:block"
                                >
                                    <i
                                        class="fa fa-ellipsis-h text-xl p-2 text-black hover:bg-gray-100 cursor-pointer"
                                        aria-hidden="true"
                                    ></i>
                                </span>
                                <td
                                    class="p-4 py-2 w-fit flex items-center justify-center border-l-8 border-l-blue-300 border border-gray-100"
                                >
                                    <input
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600"
                                    />
                                </td>
                                <td
                                    class="px-6 hover:px-2 py-1 group w-56 border flex items-center"
                                >
                                    <span
                                        v-if="!subItem"
                                        @click="subItem = !subItem"
                                        class="hidden group-hover:block"
                                    >
                                        <i
                                            class="fa fa-chevron-right mr-4 text-xs"
                                            aria-hidden="true"
                                        ></i>
                                    </span>
                                    <span
                                        v-if="subItem"
                                        @click="subItem = !subItem"
                                        class="hidden group-hover:block"
                                    >
                                        <i
                                            class="fa fa-chevron-down mr-4 text-xs"
                                            aria-hidden="true"
                                        ></i>
                                    </span>
                                    <span
                                        @click="taskUpdate = !taskUpdate"
                                        @click.stop
                                        class="w-full cursor-pointer justify-between h-full flex items-center"
                                    >
                                        Task 1
                                        <span class="flex items-end">
                                            <i
                                                class="far fa-comment text-xl text-blue-700"
                                            ></i>
                                            <span
                                                class="text-xs text-white px-1 rounded-full bg-blue-700"
                                                >3</span
                                            >
                                        </span>
                                    </span>
                                </td>
                                <td
                                    v-if="showOwner || showAll || showTasks"
                                    class="px-6 py-1 flex w-24 justify-center border items-center"
                                >
                                    <h1
                                        class="py-1 px-2.5 border-2 border-white text-white rounded-full bg-blue-300"
                                    >
                                        K
                                    </h1>
                                </td>
                                <td
                                    v-if="showStatus || showAll || showTasks"
                                    class="w-36 bg-orange-400 flex justify-center items-center"
                                >
                                    <h1 class="px-2 text-white">
                                        Working on it
                                    </h1>
                                </td>
                                <td
                                    v-if="showDueDate || showAll || showTasks"
                                    class="px-2 py-1 w-36 flex items-center justify-start border"
                                >
                                    <div>
                                        <i
                                            class="fa fa-exclamation mr-4 text-sm bg-red-500 py-1 px-2.5 rounded-full text-white"
                                            aria-hidden="true"
                                        ></i>
                                        18 Aug
                                    </div>
                                </td>
                                <td
                                    v-if="showPriority || showAll || showTasks"
                                    class="px-6 py-1 border w-28 flex items-center justify-center bg-purple-800 text-white"
                                >
                                    High
                                </td>
                                <td
                                    v-if="showNotes || showAll || showTasks"
                                    class="px-6 py-1 border w-36"
                                >
                                    Action items
                                </td>
                                <td
                                    v-if="showBudget || showAll || showTasks"
                                    class="px-6 py-1 border w-24 flex items-center justify-center"
                                >
                                    $100
                                </td>
                                <td
                                    v-if="showFiles || showAll || showTasks"
                                    class="px-6 py-1 flex items-center justify-center border w-36"
                                >
                                    <i
                                        class="fa fa-file text-blue-700"
                                        aria-hidden="true"
                                    ></i>
                                </td>
                                <td
                                    v-if="showTimeLine || showAll || showTasks"
                                    class="px-2 py-1 flex items-center justify-center border"
                                >
                                    <span
                                        class="inline-block rounded-xl bg-blue-400 px-2 text-white"
                                    >
                                        18 - 19 Aug
                                    </span>
                                </td>
                                <td
                                    v-if="
                                        showLastUpdate || showAll || showTasks
                                    "
                                    class="px-6 py-1 w-44 border flex items-center justify-center"
                                >
                                    <span class="text-gray-600"
                                        >7 minutes ago</span
                                    >
                                </td>
                            </tr>

                            <!-- Subitem -->
                            <tr
                                v-if="subItem"
                                class="bg-white border m-4 mb-0 rounded-md flex border-l-8 border-l-blue-300 z-5"
                            >
                                <th
                                    class="pl-4 pr-4 w-fit flex items-center border border-gray-100"
                                >
                                    <input
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600"
                                    />
                                </th>
                                <th
                                    class="px-6 py-2 border flex items-center justify-center w-52"
                                >
                                    Sub task
                                </th>
                                <th
                                    v-if="showOwnerS || showAll || showSubTasks"
                                    class="px-6 py-1 flex items-center justify-center border"
                                >
                                    Owner
                                </th>
                                <th
                                    v-if="
                                        showStatusS || showAll || showSubTasks
                                    "
                                    class="px-6 py-1 w-36 flex items-center justify-center border"
                                >
                                    Status
                                </th>
                                <th
                                    v-if="
                                        showDueDateS || showAll || showSubTasks
                                    "
                                    class="px-6 py-1 flex items-center w-36 justify-center border"
                                >
                                    Due date
                                </th>
                                <th
                                    v-if="
                                        showPriorityS || showAll || showSubTasks
                                    "
                                    class="px-6 py-1 flex items-center w-28 justify-center border"
                                >
                                    Priority
                                </th>
                                <th
                                    v-if="showNotesS || showAll || showSubTasks"
                                    class="px-6 py-1 w-36 flex items-center justify-center border"
                                >
                                    Notes
                                </th>
                                <th
                                    v-if="
                                        showBudgetS || showAll || showSubTasks
                                    "
                                    class="px-6 py-1 flex items-center justify-center border"
                                >
                                    Budget
                                </th>
                                <th
                                    v-if="showFilesS || showAll || showSubTasks"
                                    class="px-6 py-1 w-36 flex items-center justify-center border"
                                >
                                    Files
                                </th>
                                <th
                                    v-if="
                                        showTimeLineS || showAll || showSubTasks
                                    "
                                    class="px-6 py-1 flex items-center justify-center border"
                                >
                                    Timeline
                                </th>
                                <th
                                    v-if="
                                        showLastUpdateS ||
                                        showAll ||
                                        showSubTasks
                                    "
                                    class="px-6 py-1 flex items-center justify-center border w-44"
                                >
                                    Last updated
                                </th>
                                <th
                                    class="flex items-center justify-center p-3"
                                >
                                    <i
                                        class="fa fa-plus p-1 hover:bg-gray-100"
                                    ></i>
                                </th>
                            </tr>
                        </section>

                        <tr
                            v-if="subItem"
                            class="bg-white border w-fit hover:bg-gray-100 m-4 mt-0 rounded-md flex border-l-8 border-l-blue-300 z-5"
                        >
                            <td
                                class="p-4 py-2 w-fit flex items-center justify-center border border-gray-100"
                            >
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600"
                                />
                            </td>
                            <td
                                class="group px-6 py-1 w-52 border flex items-center"
                            >
                                Task 3
                            </td>
                            <td
                                v-if="showOwnerS || showAll || showSubTasks"
                                class="px-6 py-1 flex w-24 justify-center border items-center"
                            >
                                <h1
                                    class="py-1 px-2.5 border-2 border-white text-white rounded-full bg-blue-300"
                                >
                                    K
                                </h1>
                            </td>
                            <td
                                v-if="showStatusS || showAll || showSubTasks"
                                class="w-36 bg-green-400 flex justify-center items-center"
                            >
                                <h1 class="px-2 text-white">Done</h1>
                            </td>
                            <td
                                v-if="showDueDateS || showAll || showSubTasks"
                                class="px-2 py-1 w-36 flex items-center justify-start border"
                            >
                                <div>
                                    <i
                                        class="fa fa-check mr-4 text-sm bg-green-500 py-1 px-1 rounded-full text-white"
                                        aria-hidden="true"
                                    ></i>
                                    18 Aug
                                </div>
                            </td>
                            <td
                                v-if="showPriorityS || showAll || showSubTasks"
                                class="px-6 py-1 border w-28 flex items-center justify-center bg-purple-800 text-white"
                            >
                                High
                            </td>
                            <td
                                v-if="showNotesS || showAll || showSubTasks"
                                class="px-6 py-1 border w-36"
                            >
                                Action items
                            </td>
                            <td
                                v-if="showBudgetS || showAll || showSubTasks"
                                class="px-6 py-1 border w-24 flex items-center justify-center"
                            >
                                $100
                            </td>
                            <td
                                v-if="showFilesS || showAll || showSubTasks"
                                class="px-6 py-1 flex items-center justify-center border w-36"
                            >
                                <i
                                    class="fa fa-file text-blue-700"
                                    aria-hidden="true"
                                ></i>
                            </td>
                            <td
                                v-if="showTimeLineS || showAll || showSubTasks"
                                class="px-2 py-1 flex items-center justify-center border"
                            >
                                <span
                                    class="inline-block rounded-xl bg-blue-400 px-2 text-white"
                                >
                                    18 - 19 Aug
                                </span>
                            </td>
                            <td
                                v-if="
                                    showLastUpdateS || showAll || showSubTasks
                                "
                                class="px-6 py-1 w-44 border flex items-center justify-center"
                            >
                                <span class="text-gray-600">7 minutes ago</span>
                            </td>
                        </tr>

                        <tr
                            v-if="showGroup"
                            class="border- flex bg-white hover:bg-gray-100"
                        >
                            <td
                                class="p-4 py-2 w-fit flex items-center justify-center border-l-8 border-l-blue-300 border border-gray-100"
                            >
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600"
                                />
                            </td>
                            <td
                                class="px-6 group py-1 w-56 border flex items-center"
                            >
                                <input
                                    type="text"
                                    @focus="clearInput"
                                    v-model="addTask"
                                    :placeholder="addTask ? '' : '+ Add task'"
                                    ref="editableInput"
                                    class="border-0 px-0 focus: h-fit border-gray-300 text-sm rounded p-2 py-1 w-full"
                                />
                            </td>
                        </tr>
                        <tr
                            class="border- flex pl-72 bg-white hover:bg-gray-100"
                        >
                            <td
                                v-if="showOwner || showAll || showTasks"
                                class="px-6 py-1 flex w-24 justify-center border rounded-b-xl rounded-r-none items-center"
                            ></td>
                            <td
                                v-if="showStatus || showAll || showTasks"
                                :class="{
                                    '  ': showOwner === false,
                                }"
                                class="w-36 flex justify-center items-center"
                            >
                                <span class="w-1/3 h-full bg-orange-400"></span>
                                <span class="w-1/3 h-full bg-red-500"></span>
                                <span class="w-1/3 h-full bg-green-500"></span>
                            </td>
                            <td
                                v-if="showDueDate || showAll || showTasks"
                                class="px-2 py-1 w-36 flex items-center justify-center border"
                            >
                                <span
                                    class="inline-block rounded-xl bg-blue-400 px-2 text-white"
                                >
                                    18 - 19 Aug
                                </span>
                            </td>
                            <td
                                v-if="showPriority || showAll || showTasks"
                                class="border w-28 flex items-center justify-start text-white"
                            >
                                <span class="w-1/3 h-full bg-purple-800"></span>
                                <span class="w-1/3 h-full bg-blue-700"></span>
                                <span class="w-1/3 h-full bg-blue-300"></span>
                            </td>
                            <td
                                v-if="showNotes || showAll || showTasks"
                                class="px-6 py-1 border w-36"
                            ></td>
                            <td
                                v-if="showBudget || showAll || showTasks"
                                class="px-6 py-1 border w-24 flex items-center justify-center"
                            >
                                $300
                            </td>
                            <td
                                v-if="showFiles || showAll || showTasks"
                                class="px-6 py-1 flex items-center justify-center border w-36"
                            >
                                3 files
                            </td>
                            <td
                                v-if="showTimeLine || showAll || showTasks"
                                class="px-2 py-1 flex items-center justify-center border"
                            >
                                <span
                                    class="inline-block rounded-xl bg-blue-400 px-2 text-white"
                                >
                                    18 - 19 Aug
                                </span>
                            </td>
                            <td
                                v-if="showLastUpdate || showAll || showTasks"
                                class="px-6 py-1 w-44 border flex items-center justify-center"
                            ></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="showCard" class="rounded-md">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
                    <div
                        v-for="(task, index) in tasks"
                        :key="index"
                        class="bg-white rounded-lg shadow p-4"
                    >
                        <div
                            class="bg-gray-100 rounded-t-lg h-32 flex justify-center items-center"
                        >
                            <img
                                v-if="task.avatar"
                                :src="task.avatar"
                                alt="avatar"
                                class="w-16 h-16 rounded-full"
                            />
                            <div
                                v-else
                                class="w-16 h-16 bg-gray-300 rounded-full flex justify-center items-center text-2xl text-white font-semibold"
                            >
                                {{ task.initial }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">
                                {{ task.title }}
                            </h3>
                            <div class="flex items-center text-gray-600 mb-2">
                                <i class="fas fa-user mr-2"></i>Owner
                            </div>
                            <div class="mb-2 flex items-center justify-between">
                                <label class="text-gray-600">Status</label>
                                <div
                                    class="w-36 bg-green-400 flex justify-center items-center"
                                >
                                    <h1 class="px-2 text-white">Done</h1>
                                </div>
                            </div>
                            <div class="mb-2 flex items-center justify-between">
                                <label class="text-gray-600">Priority</label>
                                <div
                                    class="w-36 bg-blue-400 flex justify-center items-center"
                                >
                                    <h1 class="px-2 text-white">High</h1>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-600 mt-4">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span :class="task.dueDateClass">{{
                                    task.dueDate
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</template>

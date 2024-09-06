<script setup>
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref, watch, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";
function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}

const props = defineProps({
    modelValue: String, // this is the current value of showNav from the parent
});

// Emit the event
const emit = defineEmits(["nav"]);

const boards = ref([]);
const filteredFavorites = ref([]);
const route = useRoute();
const boardId = ref(route.params.boardName);
const option3 = ref(false);
const router = useRouter();
const workspace_name = ref('New Workspace');
const board_name = ref('New Board');

const showNav = ref("active");
const showTrash = ref(false);
const trash = ref(true);
const archive = ref(false);
const option1 = ref(false);
const option2 = ref(false);
const showFavorites = ref(false);
const manuallyActivated = ref(true);
const workSpaces = ref(false);
const addVisible = ref('');
const showBoardOptions = ref([]);


const user = JSON.parse(localStorage.getItem('user'));
console.log("🚀 ~ user:", user);
const selectedWorkspace = ref(user.workspaces[0]); // Initially select "Main workspace"
console.log("🚀 ~ selected workspace:", selectedWorkspace.value.id)
user.workspaces.forEach(workspace => {
  console.log("🚀 ~ workspace:", workspace);
  console.log("🚀 ~ boards:", workspace.boards.board);

  // Initialize 'false' for each board
  workspace.boards.forEach(() => {
    showBoardOptions.value.push(false);
  });
});



const toggleBoardOption = (index) => {
    showBoardOptions.value[index] = !showBoardOptions.value[index];
};


// Toggle dropdown visibility
const toggleWorkspaces = () => {
  workSpaces.value = !workSpaces.value;
};
const toggleAdd = (item) => {
    
    addVisible.value =item ;
    console.log("🚀 ~ workspace_name:", workspace_name.value[0])
};
const hideAddWorkspace = () => {
    addVisible.value = '';
};
const createWorkspace = async () => {
    try {
    const response = await axios.post('/api/workspaces', {
      workspace_name: workspace_name.value,
        is_trashed: false,
        is_archived: false,
        trashed_by: null,
        trashed_at: null,
        folder_id: null,
      is_favorite:false,
      created_by: user.id,
    });
        workspace_name.value = 'New Workspace';
        
    const newWorkspace = response.data;
    console.log("🚀 ~ createWorkspace ~ newWorkspace:", newWorkspace)
    user.workspaces.push(newWorkspace);
    console.log("🚀 ~ createWorkspace ~ user.workspace:", user.workspaces);

    alert('Workspace created successfully!');
      // Clear input after submission
    // Reset privacy to default
    hideAddWorkspace(); // Close the modal after creation
  } catch (error) {
    console.error('Error creating workspace:', error);
    alert('Failed to create workspace.');
  }
};
const createBoard = async () => {
    try {
    const response = await axios.post('/api/boards', {
        board_name: board_name.value,
        workspace_id: selectedWorkspace.value.id,
        owner:user.id,
        is_trashed: false,
        is_archived: false,
        trashed_by: null,
        folder_id: null,
        trashed_at: null,
      is_favorite:false,
      created_by: user.id,
    });
        board_name.value = 'New Board';
      
    
    const newBoard = response.data;
    console.log("🚀 ~ createWorkspace ~ newWorkspace:", newBoard)
    user.workspaces.boards.push(newBoard);
    console.log("🚀 ~ createWorkspace ~ user.workspace:", user.teams);

    alert('Workspace created successfully!');
      // Clear input after submission
    // Reset privacy to default
    hideAddWorkspace(); // Close the modal after creation
  } catch (error) {
    console.error('Error creating board:', error);
    alert('Failed to create board.');
  }
};
// Select a workspace
const selectWorkspace = (workspace) => {
  selectedWorkspace.value = workspace;
  workSpaces.value = false; // Close the dropdown after selection
};
const toggleOn = () => {
    showNav.value = "active";
    manuallyActivated.value = true;
    emit("nav", showNav.value);
};
const toggleOff = () => {
    showNav.value = "inactive";
    manuallyActivated.value = false;
    emit("nav", showNav.value);
};
const handleMouseOver = () => {
    if (!manuallyActivated.value) {
        showNav.value = "active";
    }
};

// Function to hide the navbar when the mouse leaves, if it's inactive
const handleMouseLeave = () => {
    if (!manuallyActivated.value) {
        showNav.value = "inactive";
    }
};
const fetchBoards = async () => {
    try {
        const response = await axios.get("/api/boards");
        boards.value = response.data;
        filterByFavorite(boards.value);
    } catch (error) {
        console.error("There was an error fetching boards!", error);
    }
};
const setArchive = async (id) => {

    
    
    selectedWorkspace.value.is_archived = true;
    try {
        // Update the task name in the database
        await axios.patch(`/api/workspaces/${selectedWorkspace.value.id}`, {
            is_archived: true,
        });
    } catch (error) {
        console.error("Error archiving:", error);
    }
    selectedWorkspace.value = user.workSpaces[0];
} 
const setTrash = async (id) => {

    
    
    selectedWorkspace.value.is_trashed = true;
    selectedWorkspace.value.trashed_by = user.id;
    selectedWorkspace.value.trashed_at = new Date().toISOString();



try {
    // Update the task name in the database
    await axios.patch(`/api/workspaces/${selectedWorkspace.value.id}`, {
        is_trashed: true,
        trashed_by: user.id,
        trashed_at: new Date().toISOString().slice(0,19).replace('T',' '),
    });
    selectedWorkspace.value = user.workspaces[0];
} catch (error) {
    console.error("Error trashing :", error);
}
} 
const filterByFavorite = (item) => {
    filteredFavorites.value = item.filter((item) => item.is_favorite === 1);
};
const openBoardInNewTab = (boardId) => {
    window.open(`/project/${boardId}`, "_blank");
};
watch(
    () => route.params.boardId,
    (newBoardId) => {
        boardId.value = newBoardId; // Update reactive reference
    }
);

onMounted(fetchBoards);
</script>

<template>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />
    </head>
    <div
        v-if="addVisible == 'workspace' || addVisible == 'board'"
        class="bg-black flex p-8 pb-0 fixed z-20 items-center justify-center inset-0 bg-opacity-40"
        @click.self="hideAddWorkspace"
    >
        <div class="bg-white  relative w-96   rounded-xl p-8">
            <i
                @click="hideAddWorkspace"
                class="fa fa-times absolute top-2 right-2 fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-100 cursor-pointer"
                aria-hidden="true"
            ></i>
            <h2 class="text-3xl font-semibold mb-4"> {{ addVisible === 'workspace' ? 'Add new workspace' : addVisible === 'board' ? 'Add new Board' : '' }}</h2>
      <div class="flex justify-center mb-4">
        <!-- Workspace Icon Placeholder -->
        <div class="flex items-center justify-center w-20 h-20 bg-pink-500 rounded-full text-white text-2xl font-bold">
          {{addVisible === 'workspace' ? workspace_name[0] : addVisible === 'board' ? board_name[0] : '' }}
        </div>
      </div>
      <form v-if ="addVisible === 'workspace'"
      @submit.prevent="createWorkspace">
        <div class="mb-4">
          <label
            for="workspaceName"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Workspace name</label
          >
          <input
            type="text"
            id="workspaceName"
            v-model="workspace_name"
            placeholder="Choose a name for your workspace"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
          />
        </div>
        
        <div class="flex justify-end space-x-4">
          <button
            @click="hideAddWorkspace"
            type="button"
            class="bg-transparent hover:bg-gray-100 text-gray-700 py-2 px-4 rounded-md"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Add workspace
          </button>
        </div>
      </form>
      <form v-if ="addVisible === 'board'"
       @submit.prevent="createBoard">
        <div class="mb-4">
          <label
            for="workspaceName"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Board name</label
          >
          <input
            type="text"
            id="workspaceName"
            v-model="board_name"
            placeholder="Choose a name for your workspace"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
          />
        </div>
        
        <div class="flex justify-end space-x-4">
          <button
            @click="hideAddWorkspace"
            type="button"
            class="bg-transparent hover:bg-gray-100 text-gray-700 py-2 px-4 rounded-md"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Add board
          </button>
        </div>
      </form>
            </div>
    </div>
    <div
        v-if="showTrash"
        class="bg-black flex p-8 pb-0 fixed z-20 items-center justify-center inset-0 bg-opacity-40"
        @click.self="showTrash = false"
    >
        <div class="bg-white relative w-full pt-8 h-full rounded-xl">
            <i
                @click="showTrash = false"
                class="fa fa-times absolute top-6 right-6 fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-100 cursor-pointer"
                aria-hidden="true"
            ></i>
            <div class="w-full flex items-center justify-center">
                <div
                    @click="
                        archive = false;
                        trash = true;
                    "
                    :class="{
                        'border-b border-blue-600': trash === true,
                    }"
                    class="flex items-center border-b cursor-pointer px-4 py-2 hover:bg-gray-100"
                >
                    <i class="fa text-gray-500 mr-2 fa-trash"></i>
                    <h1>Trash</h1>
                </div>
                <div
                    @click="
                        archive = true;
                        trash = false;
                    "
                    :class="{
                        'border-b border-blue-600': archive === true,
                    }"
                    class="flex items-center border-b px-4 cursor-pointer py-2 hover:bg-gray-100"
                >
                    <i class="fa text-gray-500 mr-2 fa-suitcase"></i>
                    <h1>Archive</h1>
                </div>
            </div>
        </div>
    </div>

    <aside
        @mouseover="handleMouseOver"
        @mouseleave="handleMouseLeave"
        class="group resize-x z-10 fixed top-0 left-0 mt-14 bg-gradient-to-b from-white via-custom-blue to-custom-blue rounded-lg h-full w-fit min-h-screen px-1 pt-1"
    >
        <span
            v-if="manuallyActivated || showNav === 'active'"
            @click="toggleOff"
            class="hidden group-hover:block"
        >
            <i
                class="absolute right-0 top-0 p-2 px-2 bg-gray-100 fa fa-chevron-left text-sm ml-4"
            ></i>
        </span>
        <span
            v-if="showNav === 'inactive' || !manuallyActivated"
            @click="toggleOn"
        >
            <i
                class="absolute right-0 top-0 p-2 px-2 bg-gray-100 fa fa-chevron-right text-sm ml-4"
            ></i>
        </span>
        <transition
            name="slide"
            @before-enter="beforeEnter"
            @enter="enter"
            @leave="leave"
        >
            <div v-if="showNav === 'active'" class="resize-x">
                <router-link to="/home">
                    <div
                        class="group hover:w-56 flex items-center bg-blue-100 py-1 rounded-md hover:bg-gray-200 cursor-pointer"
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
                </router-link>
                <div
                    class="flex items-center py-1 rounded-md hover:bg-gray-200 cursor-pointer mb-2"
                >
                    <i
                        class="far fa-calendar-check ml-1 text-sm"
                        aria-hidden="true"
                    ></i>
                    <h2 class="ml-3 font-thin text-sm">My work</h2>
                </div>
                <div class="border-y py-1 flex items-center">
                    <div
                        @click="showFavorites = !showFavorites"
                        class="flex items-center py-2 w-full pr-4 hover:bg-gray-200 cursor-pointer rounded-lg"
                    >
                        <div class="flex items-center">
                            <span v-if="!showFavorites">
                                <i class="far mr-2 fa-star"></i>
                            </span>
                            <span v-if="showFavorites">
                                <i class="fa text-orange-400 mr-2 fa-star"></i>
                            </span>

                            <h1 class="text-md">Favorites</h1>
                        </div>
                        <span v-if="!showFavorites">
                            <i class="fa text-sm ml-32 fa-chevron-down"></i>
                        </span>
                        <span v-if="showFavorites">
                            <i class="fa text-sm ml-32 fa-chevron-up"></i>
                        </span>
                    </div>
                </div>
                
                <div
                    v-if="showFavorites"
                    v-for="boards in user.teams"
                    :key="boards.id"
                    class="group/project relative"
                >
                
                    <router-link v-if="boards.board.is_favorite && boards.board.board_name"
                    :to="{
        name: 'project',
        params: { boardName: boards.board.name } // Ensure `boards.board.name` is defined
    }"
                    >
                        <div
                            :class="
                                board.id === +boardId
                                    ? 'bg-blue-100'
                                    : 'bg-gray-200'
                            "
                            class="flex my-2 mx-1 items-center group-hover/project:bg-blue-100 cursor-pointer p-2 rounded-md"
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
                            <h2 class="ml-2 text-sm">{{ boards.board_name }}</h2>

                            <div
                                @mouseleave="toggleBoardOption(index)"
                                v-if="showBoardOptions[index]"
                                class="absolute z-50 text-sm font-light shadow-xl top-0 w-72 left-64 bg-white p-1 py-4 rounded-lg"
                            >
                                <div
                                    class="flex w-full py-2 border-b hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                >
                                    <h1 class="w-40">
                                        Open board in a new tab
                                    </h1>
                                </div>
                                <div
                                    class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                >
                                    <i
                                        class="fa fa-pencil mr-2 text-gray-500"
                                    ></i>
                                    <h1 class="w-40">Rename board</h1>
                                </div>
                                <div
                                    class="flex w-full py-2 hover:bg-gray-100 justify-between cursor-pointer rounded-md px-4 items-center"
                                >
                                    <div class="flex">
                                        <i
                                            class="fa fa-arrow-right mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">Move to</h1>
                                    </div>
                                    <i class="fa fa-chevron-right"></i>
                                </div>
                                <div
                                    v-if="true"
                                    class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                >
                                    <i
                                        class="far fa-star mr-2 text-gray-500"
                                    ></i>
                                    <h1 class="w-40">Add to favorites</h1>
                                </div>
                                <div
                                   
                                    class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                >
                                    <i
                                        class="far fa-star mr-2 text-gray-500"
                                    ></i>
                                    <h1 class="w-40">Remove from favorites</h1>
                                </div>
                                <div
                                    class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                >
                                    <i
                                        class="far fa-file mr-2 text-gray-500"
                                    ></i>
                                    <h1 class="w-40">Duplicate</h1>
                                </div>
                                <div
                                    class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                >
                                    <i
                                        class="fa fa-trash mr-2 text-gray-500"
                                    ></i>
                                    <h1 class="w-40">Delete</h1>
                                </div>
                                <div
                                    class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                >
                                    <i
                                        class="fa fa-suitcase mr-2 text-gray-500"
                                    ></i>
                                    <h1 class="w-40">Archive</h1>
                                </div>
                            </div>
                        </div>
                    </router-link>
                    <span
                        class="group-hover/project:block hidden absolute top-3.5 right-5"
                    >
                        <i
                            @click="option2 = !option2"
                            class="fa fa-ellipsis-h text-xl ml-4 p-1 rounded-md hover:bg-blue-300 cursor-pointer"
                            aria-hidden="true"
                        ></i>
                    </span>
                </div>
                <div v-if="!showFavorites" class="flex flex-col w-fit">
                    <div class="flex mt-2 w-fit">
                        <div class="flex relative items-center mr-4">
  <div @click="toggleWorkspaces" class="flex  items-center  hover:bg-gray-200">
    <!-- Display Selected Workspace Name -->
    <h3 class="bg-gray-500 w-fit text-white py-0.5 px-1.5 rounded-md text-md">
      {{ selectedWorkspace.workspace_name.charAt(0) }}
    </h3>
    <svg v-if ="selectedWorkspace.id == user.workspaces[0].id"
      class="absolute left-3.5 top-7"
      width="20px"
      viewBox="0 0 24 24"
      fill="#000000"
      xmlns="http://www.w3.org/2000/svg"
    >
      <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
      <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
      <g id="SVGRepo_iconCarrier">
        <path
          d="M9 20H7C5.89543 20 5 19.1046 5 18V10.9199C5 10.336 5.25513 9.78132 5.69842 9.40136L10.6984 5.11564C11.4474 4.47366 12.5526 4.47366 13.3016 5.11564L18.3016 9.40136C18.7449 9.78132 19 10.336 19 10.9199V18C19 19.1046 18.1046 20 17 20H15M9 20V14C9 13.4477 9.44772 13 10 13H14C14.5523 13 15 13.4477 15 14V20M9 20H15"
          stroke="#ffffff"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
      </g>
    </svg>
    <div class="cursor-pointer flex p-2 pl-1 justify-between w-ful items-center">
      <h1 class="text-md  font-bold">{{ selectedWorkspace.workspace_name }}</h1>
      <i class="fa fa-chevron-down text-xs ml-4" aria-hidden="true"></i>
      <div
                            @mouseleave="option1 = false"
                            v-if="option1"
                            class="absolute z-50 text-md font-light shadow-xl top-1 w-72 left-64 bg-white p-1 py-4 rounded-lg"
                        >
                            <div
                                class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                            >
                                <svg
                                    class="mr-2"
                                    fill="#979595"
                                    width="14px"
                                    viewBox="0 0 16 16"
                                    xmlns="http://www.w3.org/2000/svg"
                                    stroke="#858585"
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
                                            d="M.63 11.08zm.21.41v-.1zm.23.38L1 11.68zM1 11.68l-.11-.19zm-.21-.29c-.06-.1-.11-.21-.16-.31.05.1.1.21.16.31zm.32.54v-.06z"
                                        ></path>
                                        <path
                                            d="m11.26 12.63 1.83 1.09a7.34 7.34 0 0 0 1-.94 7.48 7.48 0 0 0 1.56-2.86l-1.74-1A5.29 5.29 0 0 0 14 8a5.29 5.29 0 0 0-.08-.9l1.74-1a7.45 7.45 0 0 0-1.33-2.58 7.54 7.54 0 0 0-1.24-1.22l-1.83 1.04a6 6 0 0 0-1.11-.53v-2A8.55 8.55 0 0 0 7.94.53a8.39 8.39 0 0 0-2.26.3v2a7.23 7.23 0 0 0-1.12.54L2.78 2.28A7.46 7.46 0 0 0 .2 6.06l1.72 1a5.29 5.29 0 0 0-.08.9 5.29 5.29 0 0 0 .08.9l-1.73 1a8 8 0 0 0 .43 1.15c.05.1.1.21.16.31v.1l.11.19.12.19v.06a7.69 7.69 0 0 0 1.64 1.78l1.81-1.08a7.23 7.23 0 0 0 1.12.54v2a8.39 8.39 0 0 0 2.26.31 8.56 8.56 0 0 0 2.22-.3v-2a6 6 0 0 0 1.2-.48zm-2.39 1.52a7.57 7.57 0 0 1-.95.06 7.73 7.73 0 0 1-1-.06v-1.69a4.92 4.92 0 0 1-2.53-1.27l-1.54.92a6.22 6.22 0 0 1-1.08-1.61l1.56-.93a4.27 4.27 0 0 1 0-3.17l-1.56-.92a6.11 6.11 0 0 1 1.12-1.62l1.56.93A5 5 0 0 1 7 3.53V1.82a7.73 7.73 0 0 1 1-.06 7.57 7.57 0 0 1 .95.06v1.72a4.9 4.9 0 0 1 2.4 1.26l1.59-.94a6.31 6.31 0 0 1 1.11 1.62l-1.6.94a4.35 4.35 0 0 1 .3 1.58 4.44 4.44 0 0 1-.29 1.55l1.56.93a6.43 6.43 0 0 1-1.11 1.61l-1.58-.93a5 5 0 0 1-2.49 1.28z"
                                        ></path>
                                        <path
                                            d="M7.92 5.49A2.59 2.59 0 0 0 5.25 8a2.59 2.59 0 0 0 2.67 2.51A2.6 2.6 0 0 0 10.6 8a2.6 2.6 0 0 0-2.68-2.51zM8 9.2A1.35 1.35 0 0 1 6.55 8 1.35 1.35 0 0 1 8 6.7 1.35 1.35 0 0 1 9.39 8 1.35 1.35 0 0 1 8 9.2z"
                                        ></path>
                                    </g>
                                </svg>
                                <h1 class="w-40">Manage workspaces</h1>
                            </div>
                            <div
                                class="flex items-center hover:bg-gray-100 cursor-pointer rounded-md py-2 px-4 justify-between"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="mr-2"
                                        width="14px"
                                        viewBox="0 0 24 24"
                                        fill="none"
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
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M18.4324 4C18.2266 4 18.0227 4.04055 17.8325 4.11933C17.6423 4.19811 17.4695 4.31358 17.3239 4.45914L5.25659 16.5265L4.42524 19.5748L7.47353 18.7434L19.5409 6.67608C19.6864 6.53051 19.8019 6.3577 19.8807 6.16751C19.9595 5.97732 20 5.77348 20 5.56761C20 5.36175 19.9595 5.1579 19.8807 4.96771C19.8019 4.77752 19.6864 4.60471 19.5409 4.45914C19.3953 4.31358 19.2225 4.19811 19.0323 4.11933C18.8421 4.04055 18.6383 4 18.4324 4ZM17.0671 2.27157C17.5 2.09228 17.9639 2 18.4324 2C18.9009 2 19.3648 2.09228 19.7977 2.27157C20.2305 2.45086 20.6238 2.71365 20.9551 3.04493C21.2864 3.37621 21.5492 3.7695 21.7285 4.20235C21.9077 4.63519 22 5.09911 22 5.56761C22 6.03611 21.9077 6.50003 21.7285 6.93288C21.5492 7.36572 21.2864 7.75901 20.9551 8.09029L8.69996 20.3454C8.57691 20.4685 8.42387 20.5573 8.25597 20.6031L3.26314 21.9648C2.91693 22.0592 2.54667 21.9609 2.29292 21.7071C2.03917 21.4534 1.94084 21.0831 2.03526 20.7369L3.39694 15.7441C3.44273 15.5762 3.53154 15.4231 3.6546 15.3001L15.9097 3.04493C16.241 2.71365 16.6343 2.45086 17.0671 2.27157Z"
                                                fill="#858585"
                                            ></path>
                                        </g>
                                    </svg>
                                    <h1>Rename workspace</h1>
                                </div>
                                <i class="fa fa-chevron-right text-xs"></i>
                            </div>
                            <div
                                class="flex items-center py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4"
                            >
                                <svg
                                    class="mr-2"
                                    width="14px"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    stroke="#858585"
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
                                            d="M11.1924 5.65685C11.5829 5.26633 11.5829 4.63316 11.1924 4.24264L8.36397 1.41421C8.30576 1.356 8.24485 1.30212 8.18165 1.25259C7.50286 0.720577 6.55947 0.689024 5.84929 1.15793C5.73839 1.23115 5.63317 1.31658 5.53554 1.41421L2.70711 4.24264C2.31658 4.63316 2.31658 5.26633 2.70711 5.65685C3.09763 6.04738 3.7308 6.04738 4.12132 5.65685L6.00003 3.77814V18C6.00003 18.5523 6.44775 19 7.00003 19C7.55232 19 8.00003 18.5523 8.00003 18V3.8787L9.77818 5.65685C10.1687 6.04737 10.8019 6.04737 11.1924 5.65685Z"
                                            fill="#0F0F0F"
                                        ></path>
                                        <path
                                            d="M12.7071 18.3432C12.3166 18.7337 12.3166 19.3668 12.7071 19.7574L15.5355 22.5858C15.6332 22.6834 15.7384 22.7689 15.8493 22.8421C16.6256 23.3546 17.6805 23.2692 18.364 22.5858L21.1924 19.7574C21.5829 19.3668 21.5829 18.7337 21.1924 18.3432C20.8019 17.9526 20.1687 17.9526 19.7782 18.3432L18 20.1213L18 6C18 5.44771 17.5523 5 17 5C16.4477 5 16 5.44771 16 6L16 20.2218L14.1213 18.3432C13.7308 17.9526 13.0976 17.9526 12.7071 18.3432Z"
                                            fill="#0F0F0F"
                                        ></path>
                                    </g>
                                </svg>
                                <h1>Sort workspaces</h1>
                            </div>
                            <div  @click.stop = "setTrash(selectedWorkspace.id)"
                            v-if="selectedWorkspace.id != user.workspaces[0].id"
                                class="flex items-center border-b my-2 py-2 hover:bg-gray-100 cursor-pointer rounded-md border-gray-400 mt-2 pb-2 px-4"
                            >
                                <svg
                                    class="mr-2"
                                    width="14px"
                                    viewBox="0 0 24 24"
                                    fill="none"
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
                                            d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6"
                                            stroke="#000000"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </g>
                                </svg>
                                <h1>Delete workspace</h1>
                            </div>
                            <div @click.stop="toggleAdd('workspace')"
                                class="flex my-4 mb-2 w-full hover:bg-gray-100 cursor-pointer rounded-md px-4 pt-2 pb-2 mt-6 items-center"
                            >
                                <i class="fa fa-plus text-gray-500 mr-2"></i>
                                <h1>Add new workspace</h1>
                            </div>
                            <div
                                class="flex hover:bg-gray-100 cursor-pointer rounded-md px-4 pt-2 pb-2 items-center"
                            >
                                <i
                                    class="fa fa-th-large text-gray-500 mr-2"
                                    aria-hidden="true"
                                ></i>
                                <h1>Browse all workspaces</h1>
                            </div>
                            <div
                               
                                class="flex group/archive my-2 py-2 relative hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                            >
                                <i
                                    class="fa fa-trash text-gray-500 mr-2"
                                    aria-hidden="true"
                                ></i>
                                <h1>Show trash/archive</h1>
                                <div
                            
                            class="absolute hidden group-hover/archive:block z-50 text-md font-light shadow-xl top-1 left-[17.5rem] w-64 bg-white p-1 py-4 rounded-lg"
                        >
                        <div
                                @click.stop="
                                    showTrash = true;
                                    trash = true;
                                    archive = false;
                                "
                                class="flex my-2 py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                            >
                                <i
                                    class="fa fa-trash text-gray-500 mr-2"
                                    aria-hidden="true"
                                ></i>
                                <h1>Trash</h1>
                                </div>
                                <div
                                @click.stop="
                                    showTrash = true;
                                    trash = false;
                                    archive = true;
                                "
                                class="flex my-2 py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                            >
                                <i
                                    class="fa fa-suitcase text-gray-500 mr-2"
                                    aria-hidden="true"
                                ></i>
                                <h1>Archive</h1>
                                </div>
                    </div>
                            </div>

                            <div v-if = "selectedWorkspace.id != user.workspaces[0].id"
                                @click.stop="
                                    setArchive(selectedWorkspace.id)
                                "
                                class="flex my-2 py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                            >
                                <i
                                    class="fa fa-suitcase text-gray-500 mr-2"
                                    aria-hidden="true"
                                ></i>
                                <h1>Archive</h1>
                            </div>
                        </div>
    </div>
    

    <!-- Dropdown Menu -->
    <div
      v-if="workSpaces"
      @mouseleave="workSpaces = false"
      class="absolute bg-white z-10 left-0 top-14 p-4 w-66 rounded-lg shadow-lg h-fit"
    >
      <div class="relative">
        <TextInput class="h-8 w-38 pl-6" placeholder="Search for a workspace"></TextInput>
        <i class="absolute left-2 top-3 fa fa-search text-xs text-gray-400"></i>
      </div>
      <h1 class="text-gray-500 my-4">My workspaces</h1>
      

      <!-- Workspace List -->
      <div
        v-for="workspace in user.workspaces"
        :key="workspace.id"
        class="flex items-center relative overflow-y-auto max-h-10 hover:bg-gray-100"
        @click="selectWorkspace(workspace)"
      >
      <div v-if = "workspace.is_archived == false && workspace.is_trashed == false"
       class="flex items-center overflow-y-auto" >

      <svg v-if ="workspace.id == user.workspaces[0].id"
      class="absolute left-3.5 top-5"
      width="20px"
      viewBox="0 0 24 24"
      fill="#000000"
      xmlns="http://www.w3.org/2000/svg"
    >
      <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
      <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
      <g id="SVGRepo_iconCarrier">
        <path
          d="M9 20H7C5.89543 20 5 19.1046 5 18V10.9199C5 10.336 5.25513 9.78132 5.69842 9.40136L10.6984 5.11564C11.4474 4.47366 12.5526 4.47366 13.3016 5.11564L18.3016 9.40136C18.7449 9.78132 19 10.336 19 10.9199V18C19 19.1046 18.1046 20 17 20H15M9 20V14C9 13.4477 9.44772 13 10 13H14C14.5523 13 15 13.4477 15 14V20M9 20H15"
          stroke="#ffffff"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></path>
      </g>
    </svg>
        <h3 class="bg-gray-500 w-fit text-white py-0.5 px-1.5 rounded-md text-md">
          {{ workspace.workspace_name[0] }}
        </h3>
        <div class="cursor-pointer flex p-2 pl-1 items-center">
          <h1 class="text-md font-bold">{{ workspace.workspace_name }}</h1>
        </div>
      </div>

      </div>
    </div>
  </div>



                            <div class="flex items-center p-2 pl-0 rounded-md">
                                <i
                                    @click="option1 = !option1"
                                    class="fa fa-ellipsis-h text-xl ml-4 p-2 hover:bg-gray-200 cursor-pointer"
                                    aria-hidden="true"
                                ></i>
                            </div>
                        </div>
                        
                    </div>

                    <div class="w-fit flex relative items-center">
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
                            <span @click ="option3 = true" class = "cursor-pointer">

                            
                        <i 
                            class="fa text-white bg-blue-600 fa-plus text-2xl font-thin border border-white p-1 px-1 rounded-md ml-2"
                        ></i>
                    </span>
                        <div
                                    @mouseleave="option3=!option3"
                                    v-if="option3"
                                    class="absolute z-50 text-sm font-light shadow-xl top-0 w-72 left-64 bg-white p-1 py-4 rounded-lg"
                                >
                                    
                                    <div @click.stop="toggleAdd('board')"
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
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
                                        <h1 class="w-40">New Board</h1>
                                    </div>
                                    <div
                                        class="flex w-full py-2 hover:bg-gray-100 justify-between cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <div class="flex">
                                            <i
                                                class="far fa-folder ml-1 mr-2 text-gray-500 text-lg"
                                            ></i>
                                            <h1 class="w-40">New Folder</h1>
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                </div>
                    </div>
                    <div
                        v-for="(boards, index) in selectedWorkspace.boards"
                        :key="boards.id"
                        class="group/project relative"
                    >
                        <router-link v-if = "boards.workspace_id == selectedWorkspace.id"
                        :to="{
                                name: 'project',
                                params: { boardName: boards.board_name }, // Pass boardName
                             }"
                        >
                            <div
                                :class="
                                    boards.board_name === +boardId
                                        ? 'bg-blue-100'
                                        : 'bg-gray-200'
                                "
                                class="flex my-2 mx-1 items-center group-hover/project:bg-blue-100 cursor-pointer p-2 rounded-md"
                            >
                                {{ console.log(boardId) }}
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
                                <h2 class="ml-2 text-sm">
                                    {{ boards.board_name }}
                                </h2>

                                <div
                                    @mouseleave="toggleBoardOption(index)"
                                    v-if="showBoardOptions[index]"
                                    class="absolute z-50 text-sm font-light shadow-xl top-0 w-72 left-64 bg-white p-1 py-4 rounded-lg"
                                >
                                    <div
                                        class="flex w-full py-2 border-b hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <h1 class="w-40">
                                            Open board in a new tab
                                        </h1>
                                    </div>
                                    <div
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <i
                                            class="fa fa-pencil mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">Rename board</h1>
                                    </div>
                                    <div
                                        class="flex w-full py-2 hover:bg-gray-100 justify-between cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <div class="flex">
                                            <i
                                                class="fa fa-arrow-right mr-2 text-gray-500"
                                            ></i>
                                            <h1 class="w-40">Move to</h1>
                                        </div>
                                        <i class="fa fa-chevron-right"></i>
                                    </div>
                                    <div
                                        v-if="!boards.is_favorite"
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <i
                                            class="far fa-star mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">Add to favorites</h1>
                                    </div>
                                    <div
                                        v-if="boards.is_favorite"
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <i
                                            class="far fa-star mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">Remove from favorites</h1>
                                    </div>
                                    <div
                                        v-if="false"
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <i
                                            class="far fa-star mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">
                                            Remove from favorites
                                        </h1>
                                    </div>
                                    <div
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <i
                                            class="far fa-file mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">Duplicate</h1>
                                    </div>
                                    <div
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <i
                                            class="fa fa-trash mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">Delete</h1>
                                    </div>
                                    <div
                                        class="flex w-full py-2 hover:bg-gray-100 cursor-pointer rounded-md px-4 items-center"
                                    >
                                        <i
                                            class="fa fa-suitcase mr-2 text-gray-500"
                                        ></i>
                                        <h1 class="w-40">Archive</h1>
                                    </div>
                                </div>
                            </div>
                        </router-link>
                        <span
                            class="group-hover/project:block hidden absolute top-3.5 right-5"
                        >
                            <i
                                @click="toggleBoardOption(index)"
                                class="fa fa-ellipsis-h text-xl ml-4 p-1 rounded-md hover:bg-blue-300 cursor-pointer"
                                aria-hidden="true"
                            ></i>
                        </span>
                    </div>
                </div>
            </div>
        </transition>
    </aside>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.slide-enter, .slide-leave-to /* .slide-leave-active in <2.1.8 */ {
    transform: translateX(-100%);
    opacity: 0;
}

.slide-enter-to, .slide-leave /* .slide-enter-active in <2.1.8 */ {
    transform: translateX(0);
    opacity: 1;
}
#resizer {
    width: 5px; /* Resizer width */
    cursor: ew-resize; /* Cursor for resizing */
    background-color: #ccc;
    height: 100%;
}
</style>

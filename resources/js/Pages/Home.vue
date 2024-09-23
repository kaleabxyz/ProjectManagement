<script setup>
import SideDetail from "@/Components/SideDetail.vue";
import Navbar from "@/Components/Navbar.vue";
import TextInput from "@/Components/TextInput.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { ref, computed, onMounted } from "vue";
import { useUserStore } from "@/Stores/userStore";
import { useNotificationsStore } from "@/Stores/notificationsStore.js";
import Pusher from "pusher-js";
import axios from "axios";
const side = ref("active");
const sideDetail = ref(false);
const showUpdateFeed = ref(true);
const showRecentVisited = ref(true);
const showMyWorkspaces = ref(true);
const showSide = (val) => {
    side.value = val;
};

const initPusher = () => {
    const pusher = new Pusher("464dddcaaa1a6c17607c", {
        cluster: "eu",
        encrypted: true,
    });

    const invitesChannel = pusher.subscribe("invites-channel");

    // Listen for the 'new-invite' event
    invitesChannel.bind("new-invite", (data) => {
        

        const updatedInvitation = {
            ...data.invitation,
            inviter: data.inviter,
        };
        // Add the new notification to the notifications array
        notifications.value.push({
            ...data.notification,
            invitation: updatedInvitation,
        });
        

        // Optionally, trigger any additional logic, e.g., show a toast notification
    });
};
onMounted(() => {
    userStore.loadUserFromStorage(); // Optionally load user from storage
    userStore.fetchUser();
    // fetchNotifications(); // Fetch existing notifications on load
    initPusher();
});

const userStore = useUserStore();
const user = computed(() => userStore.user);
const unreadNotificationsCount = computed(() => {
    return notifications.value?.filter(notification => !notification.read).length;
});
const selectWorkspace = (workspace) => {
    userStore.selectWorkspace(workspace); // Use the Pinia store action
};
const notifications = computed(() => userStore.notifications);
const fetchNotifications = async () => {
    try {
        const response = await axios.get("/api/notifications");
        // notifications.value = response.data;
        userStore.notifications = notifications.value;
        {
            {
                console.log("notification in home", notifications.value);
            }
        }
    } catch (error) {
        console.error("Error fetching notifications", error);
    }
};
const handleAccept = async (notification) => {
    try {
        // Accept the invitation
        await axios.post(`/api/invitations/${notification.invitation.id}/accept`, {
            notification_id: notification.id,
        });
        const boardId = notification.invitation.board; 
        const { data: newBoard } = await axios.get(`/api/boards/${boardId}?workspace_id=${userStore.user.workspaces[0].id}`);
        const workspace = userStore.user.workspaces[0];
        if (workspace) {
                // Push the new board into the workspace's boards array
                workspace.boards.push(newBoard);
      
                // Update the user state with the modified workspace
                userStore.user = {
                  ...userStore.user,
                  workspaces: userStore.user.workspaces.map(w =>
                    w.id === workspace.id ? workspace : w
                  ),
                };
                console.log('new board from invite and user',newBoard,userStore.user)
                // Update local storage
                
              }
        // Mark the notification as read locally
        const index = notifications.value.findIndex(n => n.id === notification.id);
        if (index !== -1) {
            notifications.value[index].read = true;
            notifications.value[index].invitation.status = 'accepted';
        }
        userStore.notifications = notifications.value;
        localStorage.setItem('user', JSON.stringify(userStore.user));
        console.log("Invitation accepted and notification marked as read");
    } catch (error) {
        console.error("Error handling invitation acceptance:", error);
    }
};

const handleNoThanks = async (notification) => {
    try {
        // Decline the invitation
        await axios.post("/api/invitations/decline", {
            invitation_id: notification.invitation.id,
            notification_id: notification.id,
        });

        // Mark the notification as read locally
        const index = notifications.value.findIndex(n => n.id === notification.id);
        if (index !== -1) {
            notifications.value[index].read = true;
            notifications.value[index].invitation.status = 'declined';
        }
       
        console.log('userstore notifications', userStore.notifications);
        localStorage.setItem('user', JSON.stringify(userStore.user));
        console.log("Invitation declined and notification marked as read");
    } catch (error) {
        console.error("Error declining invitation and marking notification as read", error);
    }
};

function formatDateForDisplay(dateString) {
    const date = new Date(dateString);
    if (isNaN(date)) return "Invalid date"; // Handle invalid date case

    const currentYear = new Date().getFullYear();
    const taskYear = date.getFullYear();
    const month = date.toLocaleString("default", { month: "short" });
    const day = date.getDate();
    const year =
        taskYear !== currentYear ? `/${taskYear.toString().slice(-2)}` : "";

    return `${month} ${day}${year}`;
};
const getRecentlyUpdatedBoards = () => {
  // Flatten all boards from workspaces
  const allBoards = userStore.user.workspaces.flatMap((workspace) => workspace.boards);

  // Sort boards by updated_at in descending order
  const sortedBoards = allBoards.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));

  // Get the top 5 most recently updated boards
  return sortedBoards.slice(0, 5);
};

// Computed property to automatically get the 5 most recently updated boards
const recentlyUpdatedBoards = computed(() => getRecentlyUpdatedBoards());

const findWorkspaceByBoardId = (boardId) => {
  return userStore.user.workspaces.find(workspace => 
    workspace.boards.some(board => board.id === boardId)
  );
};
</script>

<template>
    <body v-if="user"
    class="bg-custom-blue min-h-screen w-full flex flex-col">
        <Navbar></Navbar>
        <div class="flex h-full">
            <Sidebar @nav="showSide" />
            <router-view />
        </div>
        <div
            class="bg-custome-blue h-full w-full"
            :class="{
                'pl-[275px] ': side === 'active',
                'pl-7': side === 'inactive',
            }"
        >
            <div
                class="min-h-screen flex-1 w-full overflow-hidden mt-14 bg-white rounded-lg pt-4"
            >
                <div class="border-b-2 border-solid pb-4 shadow-lg">
                    <h2 class="font-thin mx-16 text-sm">
                        Good afternoon, {{ user.user_name }}!
                    </h2>
                    <h2 class="font-light mx-16 text-md">
                        Quickly access your recent boards, inbox and workspaces
                    </h2>
                </div>
                <div
                    class="ml-16 mt-4 h-full w-full pr-80 p-6 rounded-lg shadow-lg"
                >
                    <div class="flex items-center">
                        <i
                            @click="showRecentVisited = !showRecentVisited"
                            class="fa fa-chevron-down cursor-pointer mr-2 text-xs"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-lg">Recently visited</h2>
                    </div>
                    <div v-if="showRecentVisited" class="flex flex-wrap"
                        v-for = "board in recentlyUpdatedBoards"
                        :key = "board.id"
                    >
                        <router-link :to="{
                                name: 'project',
    params: { boardName: board.board_name },
   query: {workSpace: board.pivot.workspace_id} // Pass boardName
                             }">
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
                                            {{board.board_name}}
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
                                        work management > {{findWorkspaceByBoardId(board.id).workspace_name}}
                                    </h3>
                                </div>
                            </div>
                        </router-link>
                        
                    </div>
                    <div class="flex items-center mt-6">
                        <i
                            @click="showUpdateFeed = !showUpdateFeed"
                            class="fa fa-chevron-down cursor-pointer mr-2 text-xs"
                            aria-hidden="true"
                        ></i>
                        <h2 class="font-bold text-lg">Notifications(inbox)</h2>
                        <h3 class="bg-blue-500 px-2 rounded-full text-white">
                            {{ unreadNotificationsCount }}
                        </h3>
                    </div>
                    <div
                        v-if="showUpdateFeed"
                        class="flex w-full flex-col p-8 border rounded-lg border-gray-300 mt-4"
                    >{{console.log('notifications ',userStore.notifications)}} 
                        <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            
                        >
                            <div  v-if = "notification && notification.read == false && notification.invitation"
                            class="flex items-center w-full justify-between border-b border-gray-300 pb-4" >
                                <div
                                    class="flex mt-4 bg-gray-200 p-3 rounded-lg justify-between"
                                >
                                    <div class="flex justify-between">
                                        <div class="relative flex items-center">
                                            
                                            <div
                                                class="flex flex-col items-center"
                                            >
                                                <h1
                                                    v-if="
                                                        !notification.invitation
                                                            .inviter
                                                            ?.profile_picture_url
                                                    "
                                                    class="py-1 w-fit px-2.5 border-2 border-white text-white rounded-full bg-blue-300"
                                                >
                                                    {{
                                                        notification.invitation
                                                            .inviter?.user_name
                                                            ? notification.invitation.inviter.user_name
                                                                  .charAt(0)
                                                                  .toUpperCase()
                                                            : "?"
                                                    }}
                                                </h1>
                                                <!-- Show user name -->
                                                <h1 class="ml-2">
                                                    {{
                                                        notification.invitation
                                                            .inviter?.user_name
                                                    }}
                                                </h1>
                                            </div>
                                            <div class="ml-4 text-base text-md">
                                                {{ notification.body }} as a
                                                {{
                                                    notification.invitation.role
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            v-if="
                                                notification.invitation
                                                    .status !== 'pending'
                                            "
                                            class="px-4 flex items-center"
                                        >
                                            <h1
                                                :class="{
                                                    'bg-red-600':
                                                        notification.invitation
                                                            .status ==
                                                        'declined',
                                                    'bg-green-600':
                                                        notification.invitation
                                                            .status ==
                                                        'accepted',
                                                }"
                                                class="text-white px-4 py-2 text-sm rounded-lg"
                                            >
                                                {{
                                                    notification.invitation
                                                        .status
                                                }}
                                            </h1>
                                        </div>
                                        <div
                                            v-if="
                                                notification.invitation
                                                    .status == 'pending'
                                            "
                                            class="items-center flex"
                                        >
                                            <button
                                                @click="
                                                    handleNoThanks(notification)
                                                "
                                                class="text-black px-4 py-2 rounded-lg hover:bg-gray-300 mr-4 text-sm"
                                            >
                                                No thanks
                                            </button>
                                            <button
                                                @click="
                                                    handleAccept(notification)
                                                "
                                                class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700"
                                            >
                                                Accept
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="far fa-clock text-xs"
                                        aria-hidden="true"
                                    ></i>
                                    <h3 class="text-sm ml-1">
                                        {{
                                            formatDateForDisplay(
                                                notification.created_at
                                            )
                                        }}
                                    </h3>
                                </div>
                            </div>
                            <div  v-if = "notification && notification.read == false && !notification.invitation"
                            class="flex items-center w-full justify-between border-b border-gray-300 pb-4" >
                               
                            <div
                                    class="flex mt-4 bg-gray-200 p-3 rounded-lg justify-between"
                                >
                                    <div class="flex justify-between">
                                        <div class="relative flex items-center">
                                            
                                           
                                            <div class="ml-4 text-base text-md">
                                                {{ notification.body }} 
                                                
                                            </div>
                                        </div>
                                       
                                        <div
                                            
                                            class="items-center flex"
                                        >
                                            <button
                                                @click="
                                                    
                                                "
                                                class="text-black px-4 py-2 rounded-lg hover:bg-gray-300 mr-4 text-sm"
                                            >
                                                Read
                                            </button>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="far fa-clock text-xs"
                                        aria-hidden="true"
                                    ></i>
                                    <h3 class="text-sm ml-1">
                                        {{
                                            formatDateForDisplay(
                                                notification.created_at
                                            )
                                        }}
                                    </h3>
                                </div>
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
                        v-for="workspace in user.workspaces"
                        v-if="showMyWorkspaces"
                        class="flex w-full gap-8 items-center flex-wrap"
                    >
                        <div
                            div
                            v-if="
                                workspace.is_archived == false &&
                                workspace.is_trashed == false
                            "
                            @click="selectWorkspace(workspace)"
                            class="flex p-8 w-fit border cursor-pointer hover:shadow-lg rounded-lg border-gray-300 mt-4"
                        >
                            <div class="relative mr-6">
                                <h3
                                    class="bg-gray-500 w-fit text-white py-2 px-4 rounded-lg text-2xl"
                                >
                                    {{ workspace.workspace_name[0] }}
                                </h3>
                                <svg
                                    v-if="workspace.id == user.workspaces[0].id"
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
                                <h2 class="text-xl">
                                    {{ workspace.workspace_name }}
                                </h2>
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

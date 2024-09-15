<script setup>
import { ref,reactive, toRaw, onUnmounted, watch, onMounted, computed } from "vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import SideDetail from "@/Components/SideDetail.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import axios from "axios";
import TextInput from "@/Components/TextInput.vue";
import { useRouter, useRoute } from "vue-router";
import state from "./../state";

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
const boards = ref([]);
const board = ref([]);
const userRole = ref("");
const showMembers = ref(false);
const boardName = ref(null);
const workSpace = ref(null);
const showReplyInput = ref([]);
const selectedBoard = ref([]);
const selectedUpdates = ref([]);
const showUpdate = ref("allUpdates");
const route = useRoute();
const showRead = ref(true);
const mentions = ref([]);
const replyContent = ref("");
const currentPassword = ref("");
const newPassword = ref("");
const confirmPassword = ref("");
const allUpdates = ref([]);
const sideDetail = ref(false);
const inviteVisible = ref(false);
const updateVisible = ref(false);
const email = ref('');
const searchResults = ref([]);
const selectedUser = ref(null);
const selectedRole = ref('');
const showProfile = ref(false);
const showTrash = ref(false);
const router = useRouter();
const showAdminstration = ref(false);
const profileActive = ref(false);
const profile = ref(true);
const trash = ref(true);
const archive = ref(false);
const workingStatus = ref(false);
const password = ref(false);
const jobTitle = ref("");
const phone = ref("");
const mobilePhone = ref("");
const location = ref("");
const skype = ref("");
const birthday = ref("");

const userName = ref("");
const selectedStatus = ref("In the office");
const selectedProfile = ref([]);
const formErrors = reactive({
  currentPassword: "",
  newPassword: "",
  confirmPassword: ""
});
axios.defaults.baseURL = "http://127.0.0.1:8000";

// Include credentials with requests
axios.defaults.withCredentials = true;

// Fetch CSRF token (Only needed once when your app loads)
const fetchCsrfToken = async () => {
    await axios.get("/sanctum/csrf-cookie");
};
const statuses = [
    { id: "in-office", label: "In the office", icon: "fas fa-building" },
    {
        id: "working-from-home",
        label: "Working from home",
        icon: "fas fa-home",
    },
    { id: "out-sick", label: "Out sick", icon: "fas fa-notes-medical" },
    {
        id: "out-of-office",
        label: "Out of office",
        icon: "fas fa-plane-departure",
    },
    { id: "working-outside", label: "Working outside", icon: "fas fa-tree" },
    { id: "family-time", label: "Family time", icon: "fas fa-users" },
    { id: "do-not-disturb", label: "Do not disturb", icon: "fas fa-ban" },
    { id: "on-break", label: "On break", icon: "fas fa-coffee" },
];
const users = ref([
    {
        avatar: "https://via.placeholder.com/40/FF0000/FFFFFF?text=K",
        name: "kaleab",
        email: "afeshakaleab@gmail.com",
        role: "Admin",
        productsIcon: "fas fa-circle",
        status: "Active",
        joined: "19 Aug, 2024",
        invitedBy: "",
        lastActive: "22 Aug, 2024",
    },
    {
        avatar: "https://via.placeholder.com/40/0000FF/FFFFFF?text=P",
        name: "Pauline from monday",
        email: "pauline@monday.com",
        role: "Member",
        productsIcon: "fas fa-times-circle",
        status: "Active",
        joined: "19 Aug, 2024",
        invitedBy: "",
        lastActive: "Never logged in",
    },
    {
        avatar: "https://via.placeholder.com/40/000000/FFFFFF?text=A",
        name: "akaleab.9363@gmail.com",
        email: "akaleab.9363@gmail.com",
        role: "Member",
        productsIcon: "fas fa-circle",
        status: "Pending",
        joined: "20 Aug, 2024",
        invitedBy: "",
        lastActive: "Never logged in",
    },
]);
const user = computed(() => state.state.user);

console.log("🚀 ~ userin navbar:", selectedProfile.value);
const fetchBoard = (workSpace, boardName) => {
    console.log("hellow is this working", workSpace.value, boardName.value);
    user.value.workspaces.forEach((workspace) => {
        if (workspace.id == workSpace.value) {
            workspace.boards.forEach((b) => {
                if (b.board_name == boardName.value) {
                    board.value = b;
                    console.log("discusssss", board.value.id);
                }
            });
        }
    });
};

const fetchBoards = () => {
    // Directly set the boards array by flattening all boards from all workspaces
    boards.value = user.value.workspaces.flatMap(
        (workspace) => workspace.boards
    );
    allUpdates.value = boards.value.flatMap((board) =>
        board.discussions && board.discussions.length > 0
            ? board.discussions
            : []
    );
    selectedUpdates.value = allUpdates.value;
    selectedUpdates.value.forEach((update) => {
        if (!update.reply) {
            showReplyInput.value.push(false);
        }
    });

    selectedBoard.value = null;

    console.log("showReplyInput", showReplyInput);
    console.log("All boards:", boards.value);
};
const fetchUsers = async () => {
      if (email.value.trim()) {
        try {
          const response = await axios.get('/api/search-users', {
            params: { email: email.value }
          });
            searchResults.value = response.data;
        console.log('search results',searchResults.value)  
        } catch (error) {
          console.error('Error fetching users:', error);
        }
      } else {
        searchResults.value = [];
      }
};
const selectUser = (user) => {
      selectedUser.value = user;
      console.log('selected user invite',selectedUser.value.id)
    };

    const sendInvitation = async () => {
        if (selectedUser.value && selectedRole.value) {
      console.log('send invite',board,board.value.team.id)  
        try {
          await axios.post('/api/invitations', {
            invited: selectedUser.value.id,
            board: board.value.id,
            team: board.value.team.id,
            role: selectedRole.value,
            email: selectedUser.value.email,
          });
          alert('Invitation sent!');
          hideInvite();
        } catch (error) {
          console.error('Error sending invitation:', error);
        }
      } else {
        alert('Please select a user and role.');
      }
    }; 
const filteredUpdates = computed(() => {
    if (showUpdate.value === "allUpdates") {
        // Show all updates
        if (showRead.value) {
            // Show all updates including read and unread
            return selectedUpdates.value;
        } else {
            // Show only unread updates
            return selectedUpdates.value.filter((update) => !update.is_read);
        }
    } else if (showUpdate.value === "mentioned") {
        // Show only updates where the user is mentioned, excluding replies
        if (showRead.value) {
            return mentions.value;
        } else {
            return mentions.value.filter((update) => !update.is_read);
        }
    } else if (showUpdate.value === "bookmarked") {
        // Show only bookmarked updates excluding replies
        if (showRead.value) {
            return selectedUpdates.value.filter((update) => update.bookmark);
        } else {
            return selectedUpdates.value.filter(
                (update) => !update.is_read && update.bookmark
            );
        }
    } else {
        // Default to showing all updates excluding replies
        return selectedUpdates.value.filter((update) => !update.reply);
    }
});
const userProfile = (user) => {
    console.log("selected user for profile", user);
    selectedProfile.value = user;
    user.workspaces.forEach((workspace) => {
        workspace.boards.forEach((b) => {
            if (b.id === board.value.id) {
                b.team.members.forEach((member) => {
                    if (member.id == user.id) {
                        userRole.value = member.pivot.role;
                        console.log("selected board for navbar", userRole);
                    }
                });
            }
        });
    });
    userName.value = user.user_name;
    jobTitle.value = user.job_title;
    phone.value = user.phone;
    mobilePhone.value = user.mobile_phone;
    location.value = user.location;
    skype.value = user.skype;
    birthday.value = user.birthday;
};
const updateUserField = async (fieldName, newValue) => {
    // Exit if the input is empty or hasn't changed
    if (!newValue.trim() || newValue === user.value[fieldName]) {
        return;
    }

    // Update the field in the frontend state
    user.value[fieldName] = newValue;

    try {
        // Update the user field in the database
        await axios.patch(`/api/users/${user.value.id}`, {
            [fieldName]: newValue,
        });

        // Save the updated user data to localStorage
        localStorage.setItem("user", JSON.stringify(user.value));

        console.log(`${fieldName} updated successfully in user data.`);
    } catch (error) {
        console.error(`Error updating ${fieldName}:`, error);
    }
};
const getDisableReason = () => {
  if (!currentPassword.value.trim()) {
    return "Current password is required.";
  }
  if (!newPassword.value.trim()) {
    return "New password is required.";
  }
  if (!confirmPassword.value.trim()) {
    return "Password confirmation is required.";
  }
  if (newPassword.value !== confirmPassword.value) {
    return "New password and confirmation do not match.";
  }
  if (newPassword.value.length < 8) {
    return "New password must be at least 8 characters long.";
  }
  return "";
};

// Computed property to enable/disable the save button based on form validity
const isFormValid = computed(() => {
    return (
        currentPassword.value.trim() !== "" &&
        newPassword.value.trim() !== "" &&
        confirmPassword.value.trim() !== "" &&
        newPassword.value === confirmPassword.value &&
        newPassword.value.length >= 8 // Check minimum length
    );
});

// Function to update the password
const updatePassword = async () => {
    // Clear previous error messages
    formErrors.currentPassword = "";
    formErrors.newPassword = "";
    formErrors.confirmPassword = "";

    try {
        // Send the request to update the password
        const token = localStorage.getItem("token");
        await axios.patch(
            "/api/password",
            {
                current_password: currentPassword.value,
                password: newPassword.value,
                password_confirmation: confirmPassword.value,
            },
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );

        alert("Password updated successfully.");
        currentPassword.value = "";
        newPassword.value = "";
        confirmPassword.value = "";
    } catch (error) {
        console.error("Error updating password:", error);
        // Handle server-side validation errors
        if (
            error.response &&
            error.response.data &&
            error.response.data.errors
        ) {
            const errors = error.response.data.errors;
            formErrors.currentPassword = errors.current_password
                ? errors.current_password[0]
                : "";
            formErrors.newPassword = errors.password ? errors.password[0] : "";
            formErrors.confirmPassword = errors.password_confirmation
                ? errors.password_confirmation[0]
                : "";
        } else {
            alert("Failed to update password. Please try again.");
        }
    }
};

const markAsRead = async (update) => {
    // Step 1: Update the local state
    update.is_read = true;

    // Step 2: Optionally, send the update to the server
    try {
        // Send the request to mark the update as read on the server
        await axios.post(`/api/updates/${update.id}/read`, {
            user_id: user.value.id,
        });

        // Step 3: Update the user data locally
        user.value.workspaces.forEach((workspace) => {
            workspace.boards.forEach((board) => {
                if (board.id === update.board_id) {
                    const discussionIndex = board.discussions.findIndex(
                        (discussion) => discussion.id === update.id
                    );
                    if (discussionIndex !== -1) {
                        board.discussions[discussionIndex] = {
                            ...board.discussions[discussionIndex],
                            is_read: true, // Update the discussion's read status
                        };
                    }
                }
            });
        });

        // Step 4: Save the updated user data in local storage
        localStorage.setItem("user", JSON.stringify(user.value));

        // Optionally, refresh the boards to ensure UI reflects the updated state
        fetchBoards();
        fetchBoard(workSpace, boardName);
        console.log("Update marked as read:", update.id);
    } catch (error) {
        console.error("Failed to mark update as read:", error);
    }
};
const markAllAsRead = async () => {
    try {
        // Step 1: Update the local state
        selectedUpdates.value.forEach((update) => {
            update.is_read = true;
        });

        // Step 2: Send requests to mark each update as read on the server
        await Promise.all(
            selectedUpdates.value.map((update) =>
                axios.post(`/api/updates/${update.id}/read`, {
                    user_id: user.value.id,
                })
            )
        );

        // Step 3: Update the user data locally
        user.value.workspaces.forEach((workspace) => {
            workspace.boards.forEach((board) => {
                board.discussions.forEach((discussion) => {
                    if (
                        selectedUpdates.value.some(
                            (update) => update.id === discussion.id
                        )
                    ) {
                        discussion.is_read = true;
                    }
                });
            });
        });

        // Step 4: Save the updated user data in local storage
        localStorage.setItem("user", JSON.stringify(user.value));

        // Optionally, refresh the boards to ensure UI reflects the updated state
        fetchBoards();
        fetchBoard(workSpace, boardName);
        console.log("All updates marked as read");
    } catch (error) {
        console.error("Failed to mark all updates as read:", error);
    }
};
const toggleBookmark = async (update) => {
    // Toggle the local bookmark status
    const newBookmarkStatus = !update.bookmark;
    update.bookmark = newBookmarkStatus;

    try {
        // Send the request to update the bookmark status on the server
        await axios.patch(`/api/updates/${update.id}`, {
            bookmark: newBookmarkStatus,
        });

        // Update the local user data
        user.value.workspaces.forEach((workspace) => {
            workspace.boards.forEach((board) => {
                board.discussions.forEach((discussion) => {
                    if (discussion.id === update.id) {
                        discussion.bookmark = newBookmarkStatus;
                    }
                });
            });
        });

        // Save the updated user data in local storage
        localStorage.setItem("user", JSON.stringify(user.value));

        console.log("Bookmark status updated:", update.id);
    } catch (error) {
        console.error("Failed to update bookmark status:", error);
    }
};

const submitReply = async (
    updateId,
    boardId,
    boardName,
    taskId,
    taskName,
    index
) => {
    if (replyContent.value.trim() === "") return; // Do not post if the input is empty
    {
        {
            console.log("update id", updateId);
        }
    }
    // Get user details from the current state
    const userId = user.value.id;
    const userObject = {
        id: userId,
        user_name: user.value.user_name, // Assuming your user object has 'user_name'
        profile_picture_url: user.value.profile_picture_url, // Assuming your user object has 'profile_picture_url'
    };

    // Prepare the data for the reply
    const replyData = {
        content: replyContent.value,
        user_id: userId,
        has_reply: false,
        board_id: boardId,
        task_id: taskId,
        parent_id: updateId, // Set the parent ID of the update where the reply button was clicked
        reply: 1, // Mark as reply
    };
    console.log("reply data", replyData);

    try {
        // Make an API call to create the reply
        const response = await axios.post("/api/updates", replyData);

        // Assuming the server returns the created reply
        const newReply = replyData;
        const selectedBoard = boards.value.find(
            (board) => board.id === boardId
        );

        // Find the task name from the selected board

        const formattedReply = {
            ...newReply,
            task: {
                id: taskId,
                task_name: taskName, // Get task name from the selected board
            },
            board: {
                id: boardId,
                board_name: boardName,
            },
            user: userObject, // Nest the user data under 'user'
        };

        // Push the new reply to the board's discussions array

        // Update the discussions in the user data
        user.value.workspaces.forEach((workspace) => {
            workspace.boards.forEach((b) => {
                if (b.id === boardId) {
                    b.discussions.push(formattedReply);
                }
            });
        });

        // Save the updated user data in local storage
        localStorage.setItem("user", JSON.stringify(user.value));
        fetchBoards();
        fetchBoard(workSpace, boardName);
        // Clear the input content and hide the reply input
        replyContent.value = "";
        showReplyInput.value[index] = false;

        // Optionally, you can call any method to update the task or update state if necessary
        updateTaskHasReply(updateId, boardId);
    } catch (error) {
        // Handle errors
        console.error("Error posting reply:", error);
    }
};
const toggleReplyInput = (index) => {
    showReplyInput.value[index] = !showReplyInput.value[index];
};

// Function to update the task to mark it as having a reply
const updateTaskHasReply = async (updateId, boardId) => {
    try {
        // Send a PATCH request to update the 'has_reply' status in the database
        await axios.patch(`/api/updates/${updateId}`, {
            has_reply: true,
        });

        // Find and update the discussion in board.discussions

        // Update the discussions in the user object
        user.value.workspaces.forEach((workspace) => {
            workspace.boards.forEach((b) => {
                if (b.id === boardId) {
                    const userDiscussionIndex = b.discussions.findIndex(
                        (discussion) => discussion.id === updateId
                    );
                    if (userDiscussionIndex !== -1) {
                        b.discussions[userDiscussionIndex] = {
                            ...b.discussions[userDiscussionIndex],
                            has_reply: true,
                        };
                    }
                }
            });
        });

        // Save the updated user data in local storage
        localStorage.setItem("user", JSON.stringify(user.value));
        fetchBoards();
        fetchBoard(workSpace, boardName);
    } catch (error) {
        console.error("Error updating task:", error);
    }
};
const truncatedDescription = (update, parent) => {
    const content = update.content; // Accessing content from the update
    const index = allUpdates.value.indexOf(update); // Find the index of the update

    // Regular expression to match mentions like @username
    const mentionPattern = /@(\w+)/g; // Matches any word starting with '@'
    let match;

    // Highlight mentions by wrapping them with a span
    let formattedContent = content.replace(
        mentionPattern,
        (match, username) => {
            return `<span style="color: blue;">${match}</span>`;
        }
    );

    // Check for mentions in the update content
    while ((match = mentionPattern.exec(content)) !== null) {
        const mentionedUser = match[1]; // Extract the username after '@'

        // If the mentioned user is the current user, add the update to mentions
        if (
            mentionedUser.toLowerCase() === user.value.user_name.toLowerCase()
        ) {
            if (!mentions.value.includes(update)) {
                if (update.reply && parent) {
                    if (!mentions.value.includes(parent)) {
                        // If it's a reply, add the parent update to the mentions array
                        mentions.value.push(parent);
                    }
                } else {
                    // If it's not a reply, add the current update to the mentions array
                    mentions.value.push(update);
                } // Add to mentions array
            }
        }
    }

    // Truncate the description
    if (showFullDescription.value[index] || content.length <= 100) {
        return formattedContent; // Return formatted content with highlights
    } else {
        return formattedContent.substring(0, 100) + "..."; // Truncate and return
    }
};

// Function to toggle the description between truncated and full for each update
const toggleFullDescription = (index) => {
    showFullDescription.value[index] = !showFullDescription.value[index];
};
const toggleSideDetail = () => {
    sideDetail.value = !sideDetail.value;
};
const hidesideDetail = () => {
    sideDetail.value = false;
    profileActive.value = false;
};

const toggleInvite = () => {
    inviteVisible.value = !inviteVisible.value;
};
const hideInvite = () => {
    inviteVisible.value = false;
};
const toggleUpdate = () => {
    updateVisible.value = !inviteVisible.value;
    fetchBoards();
};
const hideUpdate = () => {
    updateVisible.value = false;
};
const handleClickOutside = (event) => {
    if (
        !event.target.closest(".invite-content") &&
        (sideDetail.value || profileActive.value)
    ) {
        hidesideDetail();
    }
};
const logout = () => {
    // Clear the token and user data from local storage
    localStorage.removeItem("token");
    localStorage.removeItem("user");

    console.log("🚀 ~ logout ~ state.state.user:", state.state.user);
    state.state.user = null;

    // Optionally, redirect to the login page
    router.push("/");
};
const updateContent = ref(
    "Filter by Board Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, ratione. Molestias perferendis incidunt quisquam eveniet harum aliquam, ab mollitia facere quas cupiditate libero doloribus enim accusantium earum et ducimus? Cum? Filter by Board Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, ratione. Molestias perferendis incidunt quisquam eveniet harum aliquam, ab mollitia facere quas cupiditate libero doloribus enim accusantium earum et ducimus? Cum?"
);

const showFullDescription = ref(false);

document.addEventListener("click", handleClickOutside);

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});

watch(
    () => route.params.boardName,
    (newBoardId) => {
        if (newBoardId) {
             workSpace.value = route.query.workSpace;
            fetchBoard(workSpace, newBoardId);
        }
    }
);
onMounted(() => {
    workSpace.value = route.query.workSpace;
    boardName.value = route.params.boardName;
    console.log("board and workspace", workSpace.value);
    if (boardName) {
        fetchBoard(workSpace, boardName);
        fetchBoards();
    }
});
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
    <div
        v-if="showProfile"
        class="bg-black flex p-8 pb-0 fixed z-20 items-center justify-center inset-0 bg-opacity-40"
        @click.self="showProfile = false"
    >
        <div class="bg-white relative w-full h-full rounded-xl">
            <i
                @click="showProfile = false"
                class="fa fa-times absolute top-6 right-6 fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-100 cursor-pointer"
                aria-hidden="true"
            ></i>
            <div class="p-8 font-medium border-b">
                <h1 class="text-3xl">Profile</h1>
            </div>
            <div class="flex h-full">
                <div class="h-full border-r bg-gray-100 p-5">
                    <div class="text-sm font-extralight w-60">
                        <div
                            @click="
                                profile = true;
                                workingStatus = false;
                                password = false;

                                userProfile(user);
                            "
                            :class="{
                                'bg-blue-100': profile === true,
                                'hover:bg-gray-200': profile !== true,
                            }"
                            class="flex items-center px-3 py-2 cursor-pointer rounded-md"
                        >
                            <i
                                class="far fa-user mr-2 text-blue-600 text-md"
                            ></i>
                            <h1 class="font-extralight text-sm">
                                Personal info
                            </h1>
                        </div>
                        <div
                            @click="
                                workingStatus = true;
                                profile = false;
                                password = false;
                            "
                            :class="{
                                'bg-blue-100': workingStatus === true,
                                'hover:bg-gray-200': workingStatus !== true,
                            }"
                            class="flex items-center px-3 py-2 cursor-pointer rounded-md"
                        >
                            <i
                                class="fa fa-suitcase mr-2 text-blue-600 text-md"
                            ></i>
                            <h1 class="font-extralight text-sm">
                                Working status
                            </h1>
                        </div>
                        <div
                            @click="
                                password = true;
                                workingStatus = false;
                                profile = false;
                            "
                            :class="{
                                'bg-blue-100': password === true,
                                'hover:bg-gray-200': password !== true,
                            }"
                            class="flex items-center px-3 py-2 cursor-pointer rounded-md"
                        >
                            <i
                                class="fa fa-lock mr-2 text-blue-600 text-md"
                            ></i>
                            <h1 class="font-extralight text-sm">Password</h1>
                        </div>
                    </div>
                </div>
                <div v-if="profile" class="h-full w-full p-6">
                    <div class="w-full overflow-y-auto border rounded-lg">
                        <div class="flex p-6 justify-between">
                            <div class="flex h-fit">
                                <template
                                    v-if="selectedProfile?.profile_picture_url"
                                >
                                    <img
                                        :src="
                                            selectedProfile.profile_picture_url
                                        "
                                        alt="Profile Picture"
                                        class="w-20 h-20 object-cover rounded-full"
                                    />
                                </template>

                                <!-- Display h1 only if there is no profile_picture_url -->
                                <template v-else>
                                    <h1
                                        class="py-3 w-40 h-40 flex items-center justify-center z-15 text-8xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                    >
                                        {{
                                            selectedProfile?.user_name
                                                ? selectedProfile.user_name
                                                      .charAt(0)
                                                      .toUpperCase()
                                                : "?"
                                        }}
                                    </h1>
                                </template>
                                <div class="flex flex-col">
                                    <input
                                        type="text"
                                        class="px-0 border-0 hover:border-gray-300 hover:border text-4xl font-medium py-1"
                                        v-model="userName"
                                        @input="
                                            updateUserField(
                                                'user_name',
                                                userName
                                            )
                                        "
                                    />

                                    <input
                                        type="text"
                                        class="px-0 border-0 hover:border-gray-300 hover:border text-sm py-1 text-gray-500"
                                        v-model="jobTitle"
                                        @input="
                                            updateUserField(
                                                'job_title',
                                                jobTitle
                                            )
                                        "
                                        :placeholder="
                                            jobTitle ? '' : 'Add a job title'
                                        "
                                    />
                                    <h1
                                        class="mt-4 p-1 py-0 bg-blue-500 text-white flex items-center w-fit rounded-sm"
                                    >
                                        {{ userRole }}
                                    </h1>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div class="flex items-center">
                                        <i
                                            class="far fa-envelope mr-2 text-gray-500"
                                        >
                                        </i>
                                        <h1>Email</h1>
                                    </div>
                                    <h1 class="ml-4 text-sm text-gray-500">
                                        {{ selectedProfile.email }}
                                    </h1>
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <i
                                            class="fa fa-phone mr-2 text-gray-500"
                                        >
                                        </i>
                                        <h1>Phone</h1>
                                    </div>
                                    <input
                                        type="text"
                                        class="px-0 ml-4 border-0 hover:border-gray-300 hover:border text-sm py-1 text-gray-500"
                                        v-model="phone"
                                        @input="updateUserField('phone', phone)"
                                        :placeholder="phone ? '' : 'Add phone'"
                                    />
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <i
                                            class="fa fa-mobile mr-2 text-gray-500"
                                        >
                                        </i>
                                        <h1>Mobile phone</h1>
                                    </div>
                                    <input
                                        type="text"
                                        class="px-0 ml-4 border-0 hover:border-gray-300 hover:border text-sm py-1 text-gray-500"
                                        v-model="mobilePhone"
                                        @input="
                                            updateUserField(
                                                'mobile_phone',
                                                mobilePhone
                                            )
                                        "
                                        :placeholder="
                                            mobilePhone
                                                ? ''
                                                : 'Add mobile phone'
                                        "
                                    />
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <i
                                            class="fa fa-map-marker mr-2 text-gray-500"
                                        >
                                        </i>
                                        <h1>Location</h1>
                                    </div>
                                    <input
                                        type="text"
                                        class="px-0 ml-4 border-0 hover:border-gray-300 hover:border text-sm py-1 text-gray-500"
                                        v-model="location"
                                        @input="
                                            updateUserField(
                                                'location',
                                                location
                                            )
                                        "
                                        :placeholder="
                                            location ? '' : 'Add location'
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full h-full py-6 flex">
                        <div class="w-1/2 mr-6 border rounded-lg p-6">
                            <div>
                                <div class="flex items-center">
                                    <i
                                        class="far fa-comment mr-2 text-gray-500"
                                    >
                                    </i>
                                    <h1>Skype</h1>
                                </div>
                                <input
                                    type="text"
                                    class="px-0 ml-4 border-0 hover:border-gray-300 hover:border text-sm py-1 text-gray-500"
                                    v-model="skype"
                                    @input="updateUserField('skype', skype)"
                                    :placeholder="skype ? '' : 'Add a skype'"
                                />
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <i class="fa fa-gift mr-2 text-gray-500">
                                    </i>
                                    <h1>Birthday</h1>
                                </div>
                                <input
                                    type="text"
                                    class="px-0 ml-4 border-0 hover:border-gray-300 hover:border text-sm py-1 text-gray-500"
                                    v-model="birthday"
                                    @input="
                                        updateUserField('birthday', birthday)
                                    "
                                    :placeholder="
                                        birthday ? '' : 'Add birthday'
                                    "
                                />
                            </div>
                        </div>
                        <div class="w-1/2 border rounded-lg p-6">
                            <h1 class="text-5xl">Teams</h1>
                            <div v-for="teamBoard in boards">
                                <div>
                                    <div
                                        @click.stop="showMembers = !showMembers"
                                        class="m-4 w-3/4 p-2 bg-gray-100 cursor-pointer"
                                    >
                                        <div
                                            class="w-full flex text-xl font-bold justify-between"
                                        >
                                            {{ teamBoard.board_name }}
                                            <span>
                                                <i
                                                    :class="{
                                                        'fa-chevron-up':
                                                            showMembers,
                                                        'fa-chevron-down':
                                                            !showMembers,
                                                    }"
                                                    class="fa"
                                                ></i>
                                            </span>
                                        </div>
                                        <div v-if="showMembers" class="m-2">
                                            <h1>Members</h1>
                                            <div
                                                v-for="user in teamBoard.team
                                                    .members"
                                                class="bg-white p-2"
                                            >
                                                <div
                                                    class="flex relative items-center cursor-pointer w-44 mb-2 rounded-lg hover:bg-blue-100 px-3 py-1"
                                                >
                                                    <!-- Conditional rendering: show initials if no profile picture -->
                                                    <h1
                                                        v-if="
                                                            !user?.profile_picture_url
                                                        "
                                                        class="py-1 px-2.5 border-2 border-white text-white rounded-full bg-blue-300"
                                                    >
                                                        {{
                                                            user?.user_name
                                                                ? user.user_name
                                                                      .charAt(0)
                                                                      .toUpperCase()
                                                                : "?"
                                                        }}
                                                    </h1>
                                                    <!-- Show user name -->
                                                    <h1 class="ml-2">
                                                        {{ user?.user_name }}
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="workingStatus" class="h-full w-full p-6">
                    <div class=" ">
                        <h2 class="text-xl font-semibold mb-4">
                            Working status
                        </h2>
                        <p class="text-gray-500 mb-4">
                            Let everyone know your status
                        </p>
                        <div class="grid grid-cols-3 gap-4">
                            <div
                                v-for="(status, index) in statuses"
                                :key="index"
                                class="flex items-center"
                            >
                                <input
                                    type="radio"
                                    :id="status.id"
                                    name="status"
                                    :value="status.label"
                                    v-model="selectedStatus"
                                    @change="
                                        updateUserField(
                                            'working_status',
                                            status.label
                                        )
                                    "
                                    class="form-radio text-blue-600 h-4 w-4"
                                />
                                <label
                                    :for="status.id"
                                    class="ml-2 text-gray-700"
                                >
                                    <i :class="status.icon" class="mr-1"></i
                                    >{{ status.label }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="password" class="h-full w-1/4 p-6">
                    <div class="">
                        <h2 class="text-xl font-semibold mb-4">Password</h2>
                        <form @submit.prevent="updatePassword">
                            <div class="mb-4">
                                <label
                                    for="current-password"
                                    class="block text-gray-700"
                                    >Current password</label
                                >
                                <input
                                    id="current-password"
                                    type="password"
                                    v-model="currentPassword"
                                    class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                                    required
                                />
                                <span
                                    v-if="formErrors.currentPassword"
                                    class="text-red-500 text-sm"
                                    >{{ formErrors.currentPassword }}</span
                                >
                                <div class="mt-2">
                                    <a
                                        href="#"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Forgot your password? <br />
                                        Reset password via email
                                    </a>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label
                                    for="new-password"
                                    class="block text-gray-700"
                                    >New password</label
                                >
                                <input
                                    id="new-password"
                                    type="password"
                                    v-model="newPassword"
                                    class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                                    required
                                />
                                <span
                                    v-if="formErrors.newPassword"
                                    class="text-red-500 text-sm"
                                    >{{ formErrors.newPassword }}</span
                                >
                            </div>

                            <div class="mb-6">
                                <label
                                    for="confirm-password"
                                    class="block text-gray-700"
                                    >Confirm new password</label
                                >
                                <input
                                    id="confirm-password"
                                    type="password"
                                    v-model="confirmPassword"
                                    class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                                    required
                                />
                                <span
                                    v-if="formErrors.confirmPassword"
                                    class="text-red-500 text-sm"
                                    >{{ formErrors.confirmPassword }}</span
                                >
                            </div>

                            <button
                                type="submit"
                                :disabled="!isFormValid"
                                :title="!isFormValid ? getDisableReason() : ''"
                                :class="{
                                    'bg-gray-300 text-white cursor-not-allowed':
                                        !isFormValid,
                                    'bg-blue-400 text-white hover:bg-blue-500':
                                        isFormValid,
                                }"
                                class="w-full py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-50"
                            >
                                Save
                            </button>
                            <div
      v-if="!isFormValid"
      class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-1 text-white bg-gray-800 rounded-md text-sm"
    >
      {{ getDisableReason() }}
    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        v-if="showAdminstration"
        class="bg-black flex p-8 pb-0 fixed z-20 items-center justify-center inset-0 bg-opacity-40"
        @click.self="showAdminstration = false"
    >
        <div class="bg-white relative w-full h-full rounded-xl">
            <i
                @click="showAdminstration = false"
                class="fa fa-times absolute top-6 right-6 fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-100 cursor-pointer"
                aria-hidden="true"
            ></i>
            <div class="p-8 font-medium border-b">
                <h1 class="text-3xl">Adminstration</h1>
            </div>
            <div class="flex h-full">
                <div class="h-full border-r bg-gray-100 p-5">
                    <div class="text-sm font-extralight w-60">
                        <div
                            @click="
                                profile = true;
                                workingStatus = false;
                                password = false;
                            "
                            :class="{
                                'bg-blue-100': profile === true,
                                'hover:bg-gray-200': profile !== true,
                            }"
                            class="flex items-center px-3 py-2 cursor-pointer rounded-md"
                        >
                            <i
                                class="fa fa-users mr-2 text-blue-600 text-md"
                            ></i>
                            <h1 class="font-extralight text-sm">Users</h1>
                        </div>
                        <div
                            @click="
                                workingStatus = true;
                                profile = false;
                                password = false;
                            "
                            :class="{
                                'bg-blue-100': workingStatus === true,
                                'hover:bg-gray-200': workingStatus !== true,
                            }"
                            class="flex items-center px-3 py-2 cursor-pointer rounded-md"
                        >
                            <i
                                class="fa fa-suitcase mr-2 text-blue-600 text-md"
                            ></i>
                            <h1 class="font-extralight text-sm">Usage stats</h1>
                        </div>
                        <div
                            @click="
                                password = true;
                                workingStatus = false;
                                profile = false;
                            "
                            :class="{
                                'bg-blue-100': password === true,
                                'hover:bg-gray-200': password !== true,
                            }"
                            class="flex items-center px-3 py-2 cursor-pointer rounded-md"
                        >
                            <i
                                class="fa fa-lock mr-2 text-blue-600 text-md"
                            ></i>
                            <h1 class="font-extralight text-sm">
                                Pending invitations
                            </h1>
                        </div>
                    </div>
                </div>
                <div v-if="profile" class="h-full w-full p-6">
                    <h1 class="text-4xl mb-8">User management</h1>
                    <h1>user/email</h1>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <TextInput
                                placeholder="search"
                                class="w-72 mr-8"
                            ></TextInput>
                            <div
                                class="flex items-center p-2 hover:bg-gray-100 cursor-pointer text-gray-500"
                            >
                                <i class="fa fa-filter"> </i>
                                <h1>Filter</h1>
                            </div>
                        </div>
                        <h1 @click = "inviteVisible = true"
                            class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg cursor-pointer"
                        >
                            + Invite
                        </h1>
                    </div>
                    <div class="overflow-x-auto mt-8">
                        <table
                            class="min-w-full bg-white border border-gray-200"
                        >
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th
                                        class="px-6 py-3 text-left text-gray-600 font-medium"
                                    >
                                        Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-gray-600 font-medium"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-gray-600 font-medium"
                                    >
                                        User role
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-gray-600 font-medium"
                                    >
                                        Status
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-gray-600 font-medium"
                                    >
                                        Joined
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-gray-600 font-medium"
                                    >
                                        Invited by
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-gray-600 font-medium"
                                    >
                                        Last active
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr
                                    class="border-b border-gray-200"
                                    v-for="(user, index) in board.team
                                                    .members"
                                    :key="index"
                                >{{console.log('board singular',user.pivot.role)}}
                                    <td class="px-6 py-4">
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                        <template
                                                                v-if="
                                                                   user
                                                                        ?.profile_picture_url
                                                                "
                                                            >
                                                                <img
                                                                    :src="
                                                                        user
                                                                            .profile_picture_url
                                                                    "
                                                                    alt="Profile Picture"
                                                                    class="w-full h-full object-cover rounded-full"
                                                                />
                                                            </template>

                                                            <!-- Display h1 only if there is no profile_picture_url -->
                                                            <template v-else>
                                                                <h1
                                                                    class="py-1 w-12 h-12 flex items-center justify-center text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                >
                                                                    {{
                                                                       user
                                                                            ?.user_name
                                                                            ? user.user_name.charAt(
                                                                                  0
                                                                              ).toUpperCase()
                                                                            : "?"
                                                                    }}
                                                                </h1>
                                                            </template>

                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ user.name }}</span
                                            >
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <select
                                            v-model="user.pivot.role"
                                            class="border border-gray-300 w-24 rounded-md p-2 bg-gray-50"
                                        >
                                        
                                            <option value="owner" disabled>Owner</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Member">
                                                Member
                                            </option>
                                            <option value="Viewer">
                                                Viewer
                                            </option>
                                        </select>
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ user.status }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ user.joined }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ user.invitedBy || "---" }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ user.lastActive }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="workingStatus" class="h-full w-full p-6">
                    <div class=" ">
                        <h2 class="text-4xl mb-8">Usage stats</h2>
                    </div>
                </div>
                <div v-if="password" class="h-full w-1/4 p-6">
                    <div class="">
                        <h2 class="text-4xl mb-8">Pending invitations</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <SideDetail v-if="sideDetail" @click.stop class="p-6">
        <span>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Notifications</h1>
                <div class="flex items-center">
                    <i
                        class="fa fa-ellipsis-h text-lg mr-2 py-1 px-1 hover:bg-gray-200 cursor-pointer"
                        aria-hidden="true"
                    ></i>
                    <span class="w-0.5 h-8 bg-gray-400"></span>
                    <i
                        @click="hidesideDetail"
                        class="fa fa-times fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-200 cursor-pointer"
                        aria-hidden="true"
                    ></i>
                </div>
            </div>
            <div class="flex border-b-2 mt-6 font-extralight text-sm">
                <div
                    class="p-4 py-1 flex border-b-2 border-blue-400 items-center hover:bg-gray-100 cursor-pointer"
                >
                    <h3>All</h3>
                </div>
                <div
                    class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                >
                    <h3>Unread</h3>
                </div>
            </div>
            <div class="mt-4 relative flex">
                <TextInput
                    class="w-96 h-8"
                    placeholder="Search all notifications"
                ></TextInput>
                <i
                    class="absolute right-52 top-2 fa fa-search text-sm text-gray-400"
                ></i>
                <div
                    class="flex items-center ml-1 cursor-pointer hover:bg-gray-100 px-2"
                >
                    <i class="far fa-user-circle mr-2"></i>
                    <h1 class="text-sm">Fileter by person</h1>
                    <i
                        class="fa fa-chevron-down ml-2 text-xs"
                        aria-hidden="true"
                    ></i>
                </div>
            </div>
            <div class="flex mt-4 bg-gray-200 p-3 rounded-lg justify-between">
                <div class="relative flex items-center">
                    <i class="fa fa-user-circle text-4xl"></i>
                    <i
                        class="fa fa-user-circle text-4xl left-6 border border-white p-0 rounded-full absolute"
                    ></i>
                    <i
                        class="fa text-white bg-blue-600 fa-plus text-2xl left-12 border border-white p-2 rounded-full absolute"
                    ></i>
                    <div class="ml-24 text-base text-md">
                        Invite your teammates and start collaborating
                    </div>
                </div>
                <div class="items-center flex">
                    <button
                        class="text-black px-4 py-2 rounded-lg hover:bg-gray-300 mr-4 text-sm"
                    >
                        No thanks
                    </button>
                    <button
                        class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700"
                    >
                        Invite
                    </button>
                </div>
            </div>
        </span>
    </SideDetail>
    <div
        v-if="inviteVisible && boardName"
        class="bg-black flex p-4 fixed z-20 items-center justify-center inset-0 bg-opacity-40"
        @click.self="hideInvite"
    >
        <div class="bg-white w-1/3 h-fit rounded-xl p-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl">Invite to {{boardName}}</h1>
                <i
                    @click="hideInvite"
                    class="fa fa-times fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-100 cursor-pointer"
                    aria-hidden="true"
                ></i>
            </div>
            <div class="mt-6 font-extralight">
                <h1>Invite with email</h1>
            </div>
            <TextInput
        v-model="email"
        @input="fetchUsers"
        placeholder="Enter email address"
        class="w-full my-4"
      ></TextInput>
      <div v-if="searchResults.length"class="border-b py-2 h-20 overflow-y-auto bg-white">
        <div v-for="user2 in searchResults" :key="user.email"
        
         >
         <div v-if = "user2.id !== user.id"
         @click.stop="selectUser(user2)"
         class = "hover:bg-gray-100 cursor-pointer"
         :class = "{
            'bg-blue-200' : selectedUser?.id == user2.id
         }">

          <h2 class="font-semibold">{{ user2.user_name }}</h2>
          <p class="text-gray-500">{{ user2.email }}</p>
         </div>

        </div>
      </div>
            <div class="flex items-center">
                <div class="flex items-center mr-10">
                    <Checkbox  v-model="selectedRole"
                        class="rounded-xl mr-2 hover:border-black"
                    ></Checkbox>
                    <h1 class="font-extralight text-md">Member</h1>
                </div>
                <div class="flex items-center">
                    <Checkbox  v-model="selectedRole"
                        class="rounded-xl mr-2 hover:border-black"
                    ></Checkbox>
                    <h1 class="font-extralight text-md">Viewer(Read-only)</h1>
                </div>
            </div>
            <div v-if = "selectedUser && selectedRole"
            class= "w-full justify-end flex">

                <button @click="sendInvitation" class = "bg-blue-500 p-2 text-white rounded-lg">Invite</button>
            </div>
        </div>
    </div>
    <div
        v-if="updateVisible"
        class="bg-black flex p-8 pb-0 fixed z-20 items-center justify-center inset-0 bg-opacity-40"
        @click.self="hideUpdate"
    >
        <div class="bg-white w-full h-full relative flex rounded-xl p-8">
            <i
                @click="hideUpdate"
                class="fa fa-times absolute top-6 right-6 fa-font-extralight text-gray-500 text-2xl ml-2 p-1 hover:bg-gray-100 cursor-pointer"
                aria-hidden="true"
            ></i>
            <div class="h-full overflow-y-auto w-1/5">
                <h1 class="font-bold text-4xl">Update feed</h1>
                <div class="flex items-center justify-between mt-10">
                    <h1 class="font-bold text-lg">Filter by Board</h1>
                    <div class="flex hover:bg-gray-100 p-2 cursor-pointer">
                        <svg
                            class="mr-2"
                            fill="#979595"
                            width="20px"
                            viewBox="0 0 16 16"
                            xmlns="http://www.w3.org/2000/svg"
                            stroke="#858585"
                        >
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
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
                        <h1>Feed settings</h1>
                    </div>
                </div>
                <div class="pr-2">
                    <div
                        @click="
                            selectedBoard = null;
                            selectedUpdates = allUpdates;
                        "
                        :class="
                            selectedBoard == null
                                ? 'bg-blue-100'
                                : 'bg-gray-200'
                        "
                        class="flex cursor-pointer items-start justify-between rounded-md p-2 hover:bg-blue-200"
                    >
                        <h1 class="text-sm font-extralight">
                            All boards in my feed
                        </h1>
                        <h1 class="px-2 rounded-full bg-gray-400 text-black">
                            1
                        </h1>
                    </div>
                </div>
                <div v-for="(board, index) in boards">
                    <div class="pr-2">
                        <div
                            @click="
                                selectedBoard = board.id;
                                selectedUpdates = board.discussions;
                            "
                            :class="
                                selectedBoard == board.id
                                    ? 'bg-blue-100'
                                    : 'bg-gray-200'
                            "
                            class="flex my-2 cursor-pointer items-start justify-between rounded-md p-2 hover:bg-blue-200"
                        >
                            {{ console.log("selected board", board.id) }}
                            <h1 class="text-sm font-extralight">
                                {{ board.board_name }}
                            </h1>
                            <h1
                                class="px-2 rounded-full bg-gray-400 text-black"
                            >
                                1
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div
                    class="flex mt-6 ml-10 font-extralight text-gray-700 text-lg"
                >
                    <div
                        @click="showUpdate = 'allUpdates'"
                        :class="{
                            'border-b-2 border-blue-400':
                                showUpdate == 'allUpdates',
                        }"
                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                    >
                        <h3>All updates</h3>
                    </div>
                    <div
                        @click="showUpdate = 'mentioned'"
                        :class="{
                            'border-b-2 border-blue-400':
                                showUpdate == 'mentioned',
                        }"
                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                    >
                        <h3>@ I was mentioned</h3>
                    </div>
                    <div
                        @click="showUpdate = 'bookmarked'"
                        :class="{
                            'border-b-2 border-blue-400':
                                showUpdate == 'bookmarked',
                        }"
                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                    >
                        <i class="far fa-bookmark mr-2"></i>
                        <h3>Bookmarked</h3>
                    </div>
                </div>
                <div
                    class="w-full h-full py-6 px-10 bg-gray-50 overflow-y-auto"
                >
                    <div class="flex items-center w-fit text-sm">
                        <div
                            class="flex items-center px-2 rounded-lg py-2 hover:bg-gray-200 mr-96 cursor-pointer justify-between"
                        >
                            <h1 class="font-bold mr-4">Show</h1>
                            <div
                                @click="showRead = !showRead"
                                class="flex items-center"
                            >
                                <h1 v-if="!showRead">Unread updates</h1>
                                <h1 v-if="showRead">All updates</h1>
                            </div>
                        </div>
                        <div
                            class="flex items-center px-2 rounded-lg py-2 ml- hover:bg-gray-200 cursor-pointer justify-between"
                        >
                            <div
                                @click="markAllAsRead"
                                class="flex items-center"
                            >
                                <svg
                                    class="mr-2"
                                    width="16px"
                                    viewBox="0 0 16 16"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="#000000"
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
                                            d="M15.62 3.596L7.815 12.81l-.728-.033L4 8.382l.754-.53 2.744 3.907L14.917 3l.703.596z"
                                        ></path>
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M7.234 8.774l4.386-5.178L10.917 3l-4.23 4.994.547.78zm-1.55.403l.548.78-.547-.78zm-1.617 1.91l.547.78-.799.943-.728-.033L0 8.382l.754-.53 2.744 3.907.57-.672z"
                                        ></path>
                                    </g>
                                </svg>
                                <h1>Mark all as read</h1>
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-1/2">
                            {{
                                console.log(
                                    "selected Updates,filteredUpdates,  show Update, show Read",
                                    selectedUpdates,
                                    filteredUpdates,
                                    showUpdate,
                                    showRead
                                )
                            }}
                            <div
                                v-if="true"
                                v-for="(update, index) in filteredUpdates
                                    .slice()
                                    .reverse()"
                                :key="index"
                                class="relative"
                            >
                                <div
                                    @click="markAsRead(update)"
                                    v-if="!update.reply && !update.is_read"
                                    class="w-10 h-10 bg-blue-600 ml-6 mt-4 absolute -right-20 rounded-md flex items-center hover:bg-blue-700 justify-center"
                                >
                                    <i
                                        class="fa fa-check text-white text-2xl"
                                    ></i>
                                </div>

                                <div
                                    v-if="update.reply !== 1"
                                    class="flex w-full h-full mt-8 text-black overflow-y-auto"
                                >
                                    <div class="w-full px-6 overflow-y-auto">
                                        <div
                                            class="w-full flex flex-col relative justify-between bg-white border rounded-lg p-0"
                                        >
                                            <div>
                                                <div class="flex p-4">
                                                    <div
                                                        class="rounded-full w-12 h-12 flex items-center justify-center group/detail relative"
                                                    >
                                                        <template
                                                            v-if="
                                                                update.user
                                                                    ?.profile_picture_url
                                                            "
                                                        >
                                                            <img
                                                                :src="
                                                                    update.user
                                                                        .profile_picture_url
                                                                "
                                                                alt="Profile Picture"
                                                                class="w-full h-full object-cover rounded-full"
                                                            />
                                                        </template>

                                                        <!-- Display h1 only if there is no profile_picture_url -->
                                                        <template v-else>
                                                            <h1
                                                                class="py-1 w-12 h-12 flex items-center justify-center text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                            >
                                                                {{
                                                                    update.user
                                                                        ?.user_name
                                                                        ? update.user.user_name
                                                                              .charAt(
                                                                                  0
                                                                              )
                                                                              .toUpperCase()
                                                                        : "?"
                                                                }}
                                                            </h1>
                                                        </template>
                                                        <div
                                                            class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-1 flex-col justify-between left-12 z-10 h-44 absolute bg-white"
                                                        >
                                                            <div
                                                                class="flex px-8 w-20 h-20"
                                                            >
                                                                <template
                                                                    v-if="
                                                                        update
                                                                            .user
                                                                            ?.profile_picture_url
                                                                    "
                                                                >
                                                                    <img
                                                                        :src="
                                                                            update
                                                                                .user
                                                                                .profile_picture_url
                                                                        "
                                                                        alt="Profile Picture"
                                                                        class="w-20 h-20 object-cover rounded-full"
                                                                    />
                                                                </template>

                                                                <!-- Display h1 only if there is no profile_picture_url -->
                                                                <template
                                                                    v-else
                                                                >
                                                                    <h1
                                                                        class="py-3 w-20 h-20 flex items-center justify-center z-15 text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                    >
                                                                        {{
                                                                            update
                                                                                .user
                                                                                ?.user_name
                                                                                ? update.user.user_name
                                                                                      .charAt(
                                                                                          0
                                                                                      )
                                                                                      .toUpperCase()
                                                                                : "?"
                                                                        }}
                                                                    </h1>
                                                                </template>
                                                                <div>
                                                                    <div
                                                                        class="flex"
                                                                    >
                                                                        <h1
                                                                            class="font-extralight hover:underline cursor-pointer"
                                                                        >
                                                                            {{
                                                                                update
                                                                                    .user
                                                                                    .user_name
                                                                            }}
                                                                        </h1>
                                                                        <span
                                                                            class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                        ></span>
                                                                    </div>
                                                                    <h1
                                                                        class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                    >
                                                                        {{
                                                                            update.user_role
                                                                        }}
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
                                                                        {{
                                                                            console.log(
                                                                                "email",
                                                                                update.user
                                                                            )
                                                                        }}
                                                                        {{
                                                                            update
                                                                                .user
                                                                                .email
                                                                        }}
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
                                                                    {{
                                                                        update
                                                                            .user
                                                                            .user_name
                                                                    }}
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
                                                                    @click.stop="
                                                                        toggleBookmark(
                                                                            update
                                                                        )
                                                                    "
                                                                    class="h-full hover:bg-gray-100"
                                                                >
                                                                    <i
                                                                        :class="{
                                                                            fa: update.bookmark,
                                                                            far: !update.bookmark,
                                                                        }"
                                                                        class="fa-bookmark m-1 text-sm"
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
                                                                    {{
                                                                        update
                                                                            .board
                                                                            .board_name
                                                                    }}>
                                                                </h1>
                                                            </a>
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>To do</h1>
                                                            </a>
                                                            <a
                                                                href="#"
                                                                class="hover:text-blue-600"
                                                            >
                                                                <h1>
                                                                    >{{
                                                                        update
                                                                            .task
                                                                            .task_name
                                                                    }}
                                                                </h1>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-4 p-4 flex items-center flex-col"
                                                >
                                                    <p
                                                        v-html="
                                                            truncatedDescription(
                                                                update,
                                                                null
                                                            )
                                                        "
                                                    ></p>
                                                    <button
                                                        @click="
                                                            toggleFullDescription(
                                                                index
                                                            )
                                                        "
                                                        class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                                    >
                                                        {{
                                                            showFullDescription[
                                                                index
                                                            ]
                                                                ? "Less"
                                                                : "More"
                                                        }}
                                                    </button>
                                                </div>
                                            </div>
                                            <div
                                                v-if="update.has_reply == 0"
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
                                                    @click="
                                                        toggleReplyInput(index)
                                                    "
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
                                        <!-- Reply Input1 -->
                                        <div
                                            v-if="
                                                showReplyInput[index] &&
                                                update.has_reply == false
                                            "
                                            class="w-full flex flex-col justify-between relative bg-white border rounded-lg p-0"
                                        >
                                            <TextInput
                                                class="w-full"
                                                placeholder="Write a reply..."
                                                v-model="replyContent"
                                                @keyup.enter="
                                                    submitReply(
                                                        update.id,
                                                        update.board.id,
                                                        update.board.board_name,
                                                        update.task.task_id,
                                                        update.task.task_name,
                                                        index
                                                    )
                                                "
                                            ></TextInput>
                                        </div>

                                        <div>
                                            <div
                                                v-for="reply in selectedUpdates
                                                    .slice()
                                                    .reverse()"
                                            >
                                                <div
                                                    v-if="
                                                        reply.reply &&
                                                        reply.parent_id ===
                                                            update.id
                                                    "
                                                    class="w-full flex flex-col justify-between relative bg-white border border-b-0 rounded-lg p-0"
                                                >
                                                    <div>
                                                        <div class="flex p-4">
                                                            <div
                                                                class="rounded-full w-14 flex items-center justify-center h-14 group/detail relative"
                                                            >
                                                                <template
                                                                    v-if="
                                                                        update
                                                                            .user
                                                                            ?.profile_picture_url
                                                                    "
                                                                >
                                                                    <img
                                                                        :src="
                                                                            update
                                                                                .user
                                                                                .profile_picture_url
                                                                        "
                                                                        alt="Profile Picture"
                                                                        class="w-full h-full object-cover rounded-full"
                                                                    />
                                                                </template>

                                                                <!-- Display h1 only if there is no profile_picture_url -->
                                                                <template
                                                                    v-else
                                                                >
                                                                    <h1
                                                                        class="py-1 w-12 flex items-center justify-center h-12 text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                    >
                                                                        {{
                                                                            update
                                                                                .user
                                                                                ?.user_name
                                                                                ? update.user.user_name
                                                                                      .charAt(
                                                                                          0
                                                                                      )
                                                                                      .toUpperCase()
                                                                                : "?"
                                                                        }}
                                                                    </h1>
                                                                </template>
                                                                <div
                                                                    class="w-64 transition-opacity hidden group-hover/detail:flex duration-1000 ease-in-out opacity-0 group-hover/detail:opacity-100 rounded-lg shadow-lg px-0 py-6 pb-0 -top-20 flex-col justify-between left-14 h-44 absolute bg-white"
                                                                >
                                                                    <div
                                                                        class="flex px-8"
                                                                    >
                                                                        <h1
                                                                            class="py-3 w-20 h-20 flex items-center justify-center text-5xl px-6 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                                        >
                                                                            {{
                                                                                update
                                                                                    .user
                                                                                    ?.user_name
                                                                                    ? update.user.user_name
                                                                                          .charAt(
                                                                                              0
                                                                                          )
                                                                                          .toUpperCase()
                                                                                    : "?"
                                                                            }}
                                                                        </h1>
                                                                        <div>
                                                                            <div
                                                                                class="flex"
                                                                            >
                                                                                <h1
                                                                                    class="font-extralight hover:underline cursor-pointer"
                                                                                >
                                                                                    {{
                                                                                        update
                                                                                            .user
                                                                                            .user_name
                                                                                    }}
                                                                                </h1>
                                                                                <span
                                                                                    class="w-3 h-3 rounded-full bg-green-400 ml-2"
                                                                                ></span>
                                                                            </div>
                                                                            <h1
                                                                                class="mt-4 p-1 bg-blue-100 flex items-center justify-center rounded-lg"
                                                                            >
                                                                                {{
                                                                                    update.user_role
                                                                                }}
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
                                                                                {{
                                                                                    update
                                                                                        .user
                                                                                        .email
                                                                                }}
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
                                                                            {{
                                                                                update
                                                                                    .user
                                                                                    .user_name
                                                                            }}
                                                                        </h1>
                                                                        <div
                                                                            class="mt-1 p-4 flex items-center flex-col"
                                                                        >
                                                                            <p
                                                                                v-html="
                                                                                    truncatedDescription(
                                                                                        reply,
                                                                                        update
                                                                                    )
                                                                                "
                                                                            ></p>
                                                                            <button
                                                                                @click="
                                                                                    toggleFullDescription(
                                                                                        index
                                                                                    )
                                                                                "
                                                                                class="text-green-500 hover:text-green-600 hover:shadow-lg w-full bg-white"
                                                                            >
                                                                                {{
                                                                                    showFullDescription[
                                                                                        index
                                                                                    ]
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
                                                    ></div>
                                                    <!-- reply input 2 -->
                                                </div>
                                            </div>
                                            {{
                                                console.log(
                                                    "update has reply",
                                                    update.has_reply
                                                )
                                            }}
                                            <div
                                                v-if="update.has_reply == 1"
                                                class="flex flex-row"
                                            >
                                                <div>
                                                    <div
                                                        class="bg-white rounded-l-lg roundend-b-lg border border-t-0"
                                                    >
                                                        <template
                                                            v-if="
                                                                user?.profile_picture_url
                                                            "
                                                        >
                                                            <img
                                                                :src="
                                                                    user.profile_picture_url
                                                                "
                                                                alt="Profile Picture"
                                                                class="w-full h-full object-cover rounded-full"
                                                            />
                                                        </template>

                                                        <!-- Display h1 only if there is no profile_picture_url -->
                                                        <template v-else>
                                                            <h1
                                                                class="py-1 w-12 h-12 flex items-center justify-center text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                                            >
                                                                {{
                                                                    user?.user_name
                                                                        ? update.user.user_name
                                                                              .charAt(
                                                                                  0
                                                                              )
                                                                              .toUpperCase()
                                                                        : "?"
                                                                }}
                                                            </h1>
                                                        </template>
                                                    </div>
                                                </div>
                                                <TextInput
                                                    class="w-full"
                                                    placeholder="Write a reply..."
                                                    v-model="replyContent"
                                                    @keyup.enter="
                                                        submitReply(
                                                            update.id,
                                                            update.board.id,
                                                            update.board
                                                                .board_name,
                                                            update.task.task_id,
                                                            update.task
                                                                .task_name,
                                                            index
                                                        )
                                                    "
                                                ></TextInput>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav
        class="fixed top-0 left-0 w-full z-10 bg-custom-blue width-full flex items-center justify-between"
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

            <router-link to="/">
                <img
                    class="w-24"
                    src="../../../public/images/image-removebg-preview.png"
                    alt="F"
            /></router-link>
            <h1 class="font-sans">work management</h1>
        </div>
        <div class="flex items-center pr-4">
            <span
                @click="toggleSideDetail"
                @click.stop
                class="h-full hover:bg-gray-200"
            >
                <i class="far fa-bell m-2.5 text-sm"></i>
            </span>
            <span
                @click="toggleUpdate"
                @click.stop
                class="h-full hover:bg-gray-200"
            >
                <i class="fa fa-laptop m-2.5 text-sm"></i>
            </span>
            <span
                @click="toggleInvite"
                @click.stop
                class="h-full hover:bg-gray-200"
            >
                <i class="fa fa-user-plus m-2.5 text-sm"></i>
            </span>
            <span class="h-full hover:bg-gray-200">
                <i class="fa fa-puzzle-piece m-2.5"></i>
            </span>
            <div
                class="border-solid m-4 border-r-2 w-0.5 h-6 border-gray-400"
            ></div>
            <i class="fa fa-search m-2.5"></i>
            <i class="fa fa-question m-2.5"></i>
            <div
                @click="profileActive = !profileActive"
                @click.stop
                class="bg-custom-gray hover:bg-gray-200 cursor-pointer relative flex items-center rounded-r-full"
            >
                <img
                    class="ml-2 w-6"
                    src="../../../public/images/logo-s.png"
                    alt=""
                />
                <span>
                    <i class="fa fa-user-circle ml-4 fa-2x"></i>
                </span>
                <div
                    v-if="profileActive"
                    class="absolute top-10 p-4 -left-44 bg-white shadow-lg w-64 rounded-lg"
                >
                    <div class="flex">
                        <ApplicationLogo></ApplicationLogo>
                        <h1>Project management</h1>
                    </div>
                    <h1 class="text-gray-400">Account</h1>
                    <section class="text-sm">
                        <div
                            @click="
                                showProfile = true;
                                userProfile(user);
                            "
                            class="py-2 px-2 text-gray-600 flex items-center cursor-pointer hover:bg-gray-100"
                        >
                            <i class="far fa-user mr-2" aria-hidden="true"></i>
                            <h1>My profile</h1>
                        </div>
                        <div
                            @click="
                                showTrash = true;
                                trash = true;
                                archive = false;
                            "
                            class="py-2 px-2 text-gray-600 flex items-center cursor-pointer hover:bg-gray-100"
                        >
                            <i class="fa fa-trash mr-2" aria-hidden="true"></i>
                            <h1>Trash</h1>
                        </div>
                        <div
                            @click="
                                showTrash = true;
                                trash = false;
                                archive = true;
                            "
                            class="py-2 px-2 text-gray-600 flex items-center cursor-pointer hover:bg-gray-100"
                        >
                            <i
                                class="fa fa-suitcase mr-2"
                                aria-hidden="true"
                            ></i>
                            <h1>Archive</h1>
                        </div>
                        <div
                            @click="showAdminstration = true; "
                            class="py-2 px-2 text-gray-600 flex items-center cursor-pointer hover:bg-gray-100"
                        >
                            <i class="fa fa-cog mr-2" aria-hidden="true"></i>
                            <h1>Adminstration</h1>
                        </div>
                        <div
                            class="py-2 px-2 text-gray-600 flex items-center cursor-pointer hover:bg-gray-100"
                        >
                            <i class="fa fa-users mr-2" aria-hidden="true"></i>
                            <h1>Teams</h1>
                        </div>
                        <div
                            @click="logout"
                            class="py-2 px-2 text-gray-600 flex items-center cursor-pointer hover:bg-gray-100"
                        >
                            <i
                                class="fa fa-sign-out mr-2"
                                aria-hidden="true"
                            ></i>
                            <h1>Log-out</h1>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </nav>
</template>

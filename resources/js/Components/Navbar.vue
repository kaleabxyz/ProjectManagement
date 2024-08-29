<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onUnmounted, computed } from "vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import SideDetail from "@/Components/SideDetail.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
const sideDetail = ref(false);
const inviteVisible = ref(false);
const updateVisible = ref(false);
const showProfile = ref(false);
const showTrash = ref(false);

const showAdminstration = ref(false);
const profileActive = ref(false);
const profile = ref(true);
const trash = ref(true);
const archive = ref(false);
const workingStatus = ref(false);
const password = ref(false);
const jobTitle = ref("content");
const userName = ref("Kaleab");
const selectedStatus = ref("In the office");

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

document.addEventListener("click", handleClickOutside);

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
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
                                <h1
                                    class="text-8xl mr-6 font-bold text-white rounded-full bg-blue-300 py-10 px-14"
                                >
                                    K
                                </h1>
                                <div class="flex flex-col">
                                    <input
                                        type="text"
                                        class="px-0 border-0 hover:border-gray-300 hover:border text-4xl font-medium py-1"
                                        v-model="userName"
                                    />

                                    <input
                                        type="text"
                                        class="px-0 border-0 hover:border-gray-300 hover:border text-sm py-1 text-gray-500"
                                        v-model="jobTitle"
                                        :placeholder="
                                            jobTitle ? '' : 'Add a job title'
                                        "
                                    />
                                    <h1
                                        class="mt-4 p-1 py-0 bg-blue-500 text-white flex items-center w-fit rounded-sm"
                                    >
                                        Admin
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
                                        kaleab@email.com
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
                                    :placeholder="
                                        birthday ? '' : 'Add birthday'
                                    "
                                />
                            </div>
                        </div>
                        <div class="w-1/2 border rounded-lg p-6">
                            <h1 class="text-5xl">Teams</h1>
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
                                <div class="mt-2">
                                    <a
                                        href="#"
                                        class="text-sm text-blue-600 hover:underline"
                                        >Forgot your password? <br />
                                        Reset password via email</a
                                    >
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
                            </div>

                            <button
                                type="submit"
                                :disabled="!isFormValid"
                                class="bg-gray-200 text-gray-500 w-full py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-50"
                            >
                                Save
                            </button>
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
                        <h1
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
                                    v-for="(user, index) in users"
                                    :key="index"
                                >
                                    <td class="px-6 py-4">
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <img
                                                v-if="true"
                                                :src="user.avatar"
                                                alt="avatar"
                                                class="w-10 h-10 rounded-full"
                                            />
                                            <h1
                                                v-else
                                                class="py-1 w-fit text-3xl px-3.5 border-2 mr-2 border-white text-white rounded-full bg-blue-300"
                                            >
                                                K
                                            </h1>

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
                                            v-model="user.role"
                                            class="border border-gray-300 rounded-md p-2 bg-gray-50"
                                        >
                                            <option value="Admin">Admin</option>
                                            <option value="Member">
                                                Member
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
        v-if="inviteVisible"
        class="bg-black flex p-4 fixed z-20 items-center justify-center inset-0 bg-opacity-40"
        @click.self="hideInvite"
    >
        <div class="bg-white w-1/3 h-1/3 rounded-xl p-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl">Invite to work management</h1>
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
                placeholder="Enter email address"
                class="w-full my-4"
            ></TextInput>
            <div class="flex items-center">
                <div class="flex items-center mr-10">
                    <Checkbox
                        class="rounded-xl mr-2 hover:border-black"
                    ></Checkbox>
                    <h1 class="font-extralight text-md">Member</h1>
                </div>
                <div class="flex items-center">
                    <Checkbox
                        class="rounded-xl mr-2 hover:border-black"
                    ></Checkbox>
                    <h1 class="font-extralight text-md">Viewer(Read-only)</h1>
                </div>
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
            <div class="h-full w-1/5">
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
                        class="flex items-start justify-between bg-blue-200 rounded-md p-2 hover:bg-gray-100"
                    >
                        <h1 class="text-sm font-extralight">
                            All boards in my feed
                        </h1>
                        <h1 class="px-2 rounded-full bg-gray-400 text-black">
                            1
                        </h1>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div
                    class="flex mt-6 ml-10 font-extralight text-gray-700 text-lg"
                >
                    <div
                        class="p-4 py-1 flex border-b-2 border-blue-400 items-center hover:bg-gray-100 cursor-pointer"
                    >
                        <h3>All updates</h3>
                    </div>
                    <div
                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                    >
                        <h3>@ I was mentioned</h3>
                    </div>
                    <div
                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                    >
                        <i class="far fa-bookmark mr-2"></i>
                        <h3>Bookmarked</h3>
                    </div>
                    <div
                        class="p-4 py-1 flex items-center hover:bg-gray-100 cursor-pointer"
                    >
                        <h3>All account updates</h3>
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
                            <div class="flex items-center">
                                <h1>Unread updates</h1>
                                <i
                                    class="fa fa-chevron-down ml-2 text-xs"
                                    aria-hidden="true"
                                ></i>
                            </div>
                        </div>
                        <div
                            class="flex items-center px-2 rounded-lg py-2 ml- hover:bg-gray-200 cursor-pointer justify-between"
                        >
                            <div class="flex items-center">
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
                        <div>
                            <div
                                class="w-1/2 flex flex-col relative justify-between bg-white border rounded-lg p-0"
                            >
                                <div
                                    class="w-10 h-10 bg-blue-600 ml-6 mt-4 absolute -right-20 rounded-md flex items-center hover:bg-blue-700 justify-center"
                                >
                                    <i
                                        class="fa fa-check text-white text-2xl"
                                    ></i>
                                </div>
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
                                                    <i class="far fa-clock">
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
                                                        Project management >
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
                                            {{ truncatedDescription }}
                                        </p>
                                        <button
                                            @click="toggleFullDescription"
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
                                class="w-1/2 flex flex-col justify-between relative bg-white border rounded-lg p-0"
                            >
                                <div
                                    class="w-10 h-10 bg-blue-600 ml-6 mt-4 absolute -right-20 rounded-md flex items-center hover:bg-blue-700 justify-center"
                                >
                                    <i
                                        class="fa fa-check text-white text-2xl"
                                    ></i>
                                </div>
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
                                                    <i class="far fa-clock">
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
                <img class="ml-2 w-6" src="../../../public/images/logo-s.png" alt="" />
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
                            @click="showProfile = true"
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
                            @click="showAdminstration = true"
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

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import state from '../../state'

// Define props
const router = useRouter();
const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

// Form data
const form = ref({
    email: '',
    password: '',
    remember: false,
});


// Form errors
const formErrors = ref({});
const processing = ref(false);

const showErrorEmail = ref(false);
const showErrorPassword = ref(false);
const validateAndLogin = async () => {
showErrorEmail.value = !email.value;
showErrorPassword.value = !password.value;
if (showErrorEmail.value || showErrorPassword.value) {
return;
}
try {
const response = await axios.post('/api/login', {
email: email.value,
password: password.value
});
console.log(response.data.authorisation.token); // Access token from the 'authorisation' object
const token = response.data.authorisation.token; // Access token from the 'authorisation' object
const user = response.data.user; // User object
if(token){
localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(user));
    state.state.user = user;
router.push('/home')
} else {
console.error('Token not received');
}
} catch (error) {
console.error(error);
}
}

    
</script>


<template>
    <GuestLayout>
        
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="validateAndLogin">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="formErrors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="formErrors.password" />
            </div>

            <!-- <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div> -->

            <div class="flex items-center justify-end mt-4">
                <router-link
                    v-if="canResetPassword"
                    :to="route('password.request')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </router-link>
                <router-link
                    
                    to="/register"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Not Registered Yet?
                </router-link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': processing.value }" :disabled="processing.value">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>


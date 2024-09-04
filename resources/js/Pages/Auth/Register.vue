<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios'; // Import axios
import { ref } from 'vue';

// Form data
const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    processing: false
});

// Form errors
const formErrors = ref({});

// Function to handle form submission
const submit = async () => {
    formErrors.value = {}; // Clear previous errors
    form.value.processing = true; // Show loading state

    try {
        // Make a POST request to the backend API endpoint
        const response = await axios.post('/api/register', form.value);
        
        // Handle success (e.g., redirect, show message, etc.)
        console.log('User registered successfully:', response.data);

        // Optionally, reset the form after successful submission
        form.value.name = '';
        form.value.email = '';
        form.value.password = '';
        form.value.password_confirmation = '';
        
    } catch (error) {
        // Handle errors (e.g., display errors in the form)
        if (error.response && error.response.data.errors) {
            formErrors.value = error.response.data.errors;
        } else {
            console.error('Error during registration:', error);
        }
    } finally {
        form.value.processing = false; // Hide loading state
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="formErrors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="formErrors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="formErrors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/login"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </router-link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

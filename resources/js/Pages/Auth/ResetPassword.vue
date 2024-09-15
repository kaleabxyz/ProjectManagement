<template>
    <div>
      <h2>Reset Your Password</h2>
      <form @submit.prevent="resetPassword">
        <div>
          <label for="password">New Password:</label>
          <input
            id="password"
            v-model="password"
            type="password"
            required
          />
        </div>
        <div>
          <label for="password_confirmation">Confirm New Password:</label>
          <input
            id="password_confirmation"
            v-model="passwordConfirmation"
            type="password"
            required
          />
        </div>
        <button type="submit">Reset Password</button>
        <div v-if="statusMessage">{{ statusMessage }}</div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRoute, useRouter } from 'vue-router';
  
  const route = useRoute();
  const router = useRouter();
  
  const password = ref('');
  const passwordConfirmation = ref('');
  const statusMessage = ref('');
  
  const resetPassword = async () => {
    const { token, email } = route.query;
  
    if (password.value !== passwordConfirmation.value) {
      statusMessage.value = 'Passwords do not match.';
      return;
    }
  
    try {
      await axios.post('/api/password/reset', {
        email,
        token,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
      });
      statusMessage.value = 'Your password has been reset successfully.';
      router.push('/login');
    } catch (error) {
      statusMessage.value = 'Failed to reset password. Please try again.';
    }
  };
  </script>
  
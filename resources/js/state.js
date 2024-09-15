// src/state.js
import { reactive } from 'vue';
import axios from 'axios';

const state = reactive({
  user: null, // Store the authenticated user data here
});

// Function to load the user from local storage
const loadUserFromStorage = () => {
  const storedUser = localStorage.getItem('user');
  if (storedUser) {
    state.user = JSON.parse(storedUser); // Update the global state with user data
  }
};

// Function to set the user data directly
const setUser = (user) => {
  state.user = user; // Update the global state
  localStorage.setItem('user', JSON.stringify(user)); // Persist user data to local storage
};

// Function to fetch the user data from the server
const fetchUser = async () => {
  try {
    const response = await axios.get('/api/user'); // Adjust the API endpoint as needed
    setUser(response.data.user); // Use setUser to update state and local storage
  } catch (error) {
    console.error('Error fetching user:', error);
  }
};

export default {
  state,
  loadUserFromStorage,
  fetchUser,
  setUser, // Expose setUser to update user state directly
};

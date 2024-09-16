import { reactive } from 'vue';
import axios from 'axios';

const state = reactive({
  user: {}, // Initialize as an empty object to ensure reactivity
});

// Function to load the user from local storage
const loadUserFromStorage = () => {
  const storedUser = localStorage.getItem('user');
  if (storedUser) {
    Object.assign(state.user, JSON.parse(storedUser)); // Assign user data reactively
  }
};

// Function to set the user data directly
const setUser = (user) => {
  Object.assign(state.user, user); // Update state reactively using Object.assign
  localStorage.setItem('user', JSON.stringify(state.user)); // Persist user data to local storage
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

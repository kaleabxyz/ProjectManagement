// src/services/pusherService.js
import Pusher from 'pusher-js';

// Initialize Pusher with your credentials
const pusher = new Pusher('464dddcaaa1a6c17607c', {
  cluster: 'eu',
  encrypted: true,
});

export default pusher;

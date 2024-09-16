import { defineStore } from 'pinia';
import axios from 'axios';

export const useNotificationsStore = defineStore('notifications', {
  state: () => ({
    notifications: [], // Your notifications state
  }),
  actions: {
    async markNotificationAsRead(notificationId) {
      try {
        await axios.post(`/api/notifications/markAsRead/${notificationId}`);
        console.log('Notification marked as read');
        
        // Optionally, update the notifications list in the store
        this.notifications = this.notifications.map(notification =>
          notification.id === notificationId
            ? { ...notification, isRead: true }
            : notification
        );
      } catch (error) {
        console.error('Error marking notification as read', error);
      }
    },
    // Other actions for fetching and managing notifications
  },
});

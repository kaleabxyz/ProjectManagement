import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
    state: () => ({
      user: null,
        selectedWorkspace: null,
        notifications: [],
    }),
    actions: {
      async fetchUser() {
        try {
          const response = await axios.get('/api/user');
          this.user = response.data.user;
          if (this.user?.workspaces?.length) {
            this.selectedWorkspace = this.user.workspaces[0]; // Set default workspace
          }
          console.log("🚀 ~ selectedWorkspace in userStore:", this.selectedWorkspace);
        } catch (error) {
          console.error('Error fetching user:', error);
        }
      },
   
    async setUser(user) {
      this.user = user;
      localStorage.setItem('user', JSON.stringify(user));
    },
    loadUserFromStorage() {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        this.user = JSON.parse(storedUser);
      }
      },
      async createWorkspace(workspace_name) {
        try {
          const response = await axios.post('/api/workspaces', {
            workspace_name,
            is_trashed: false,
            is_archived: false,
            trashed_by: null,
            trashed_at: null,
            folder_id: null,
            is_favorite: false,
            created_by: this.user.id,
          });
  
          const newWorkspace = response.data;
          this.user.workspaces.push(newWorkspace);
          localStorage.setItem('user', JSON.stringify(this.user));
        } catch (error) {
          console.error('Error creating workspace:', error);
          alert('Failed to create workspace.');
        }
      },
      async createBoard(boardName) {
        if (!this.selectedWorkspace) {
          console.error('No selected workspace');
          return;
        }
        try {
          const boardData = {
            board_name: boardName,
            owner: this.user?.id,
            is_trashed: false,
            is_archived: false,
            trashed_by: null,
            folder_id: null,
            description: null,
            trashed_at: null,
            is_favorite: false,
            created_by: this.user?.id,
            workspace_id: this.selectedWorkspace.id,
          };
  
          const response = await axios.post('/api/boards', boardData);
          const createdBoardId = response.data.board.id;
  
          const { data: newBoard } = await axios.get(`/api/boards/${createdBoardId}`, {
            params: { with: ['owner', 'team', 'tasks'] },
          });
  
          const workspace = this.user.workspaces.find(w => w.id === this.selectedWorkspace.id);
          if (workspace) {
            workspace.boards.push(newBoard);
            localStorage.setItem('user', JSON.stringify(this.user));
          } else {
            console.error('Workspace not found');
          }
        } catch (error) {
          console.error('Error creating board:', error);
        }
      },
      async updateTaskField(taskId, field, value) {
        try {
          // Make the API request to update the specific task field
          await axios.patch(`/api/tasks/${taskId}`, { [field]: value });
      
          // Loop through workspaces and boards to find the correct task
          this.user.workspaces.forEach((workspace) => {
            workspace.boards.forEach((board) => {
              const taskIndex = board.tasks.findIndex((task) => task.id === taskId);
              if (taskIndex !== -1) {
                // Update the task field
                board.tasks[taskIndex] = { ...board.tasks[taskIndex], [field]: value };
              }
            });
          });
      
          // Save the updated user data to local storage
          localStorage.setItem('user', JSON.stringify(this.user));
        } catch (error) {
          console.error(`Error updating task ${field}:`, error);
        }
      },
      
          async acceptInvitation(notification) {
            try {
              const boardId = notification.invitation.board.id;
              if (!boardId) {
                throw new Error('Board ID not found in the invitation');
              }
      
              // Accept the invitation
              await axios.post(`/api/invitations/accept/${notification.invitation.id}`);
      
              // Fetch the new board data
              const { data: newBoard } = await axios.get(`/api/boards/${boardId}`, {
                params: { with: ['owner', 'team', 'tasks'] },
              });
      
              // Find the user's main workspace (first created workspace)
              const workspace = this.user.workspaces.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))[0];
      
              if (workspace) {
                // Push the new board into the workspace's boards array
                workspace.boards.push(newBoard);
      
                // Update the user state with the modified workspace
                this.user = {
                  ...this.user,
                  workspaces: this.user.workspaces.map(w =>
                    w.id === workspace.id ? workspace : w
                  ),
                };
      
                // Update local storage
                localStorage.setItem('user', JSON.stringify(this.user));
              }
      
              // Refresh the notifications list
              await this.fetchNotifications();
            } catch (error) {
              console.error('Error handling invitation acceptance:', error);
            }
      },
          
          async fetchNotifications() {
            try {
              const response = await axios.get('/api/notifications');
              this.notifications = response.data.notifications;
            } catch (error) {
              console.error('Error fetching notifications:', error);
            }
          },
  },
});

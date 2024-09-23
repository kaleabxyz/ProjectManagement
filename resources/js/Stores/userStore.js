import { defineStore } from "pinia";
import axios from "axios";
import Echo from "laravel-echo";
import router from "@/router";

export const useUserStore = defineStore("user", {
    state: () => ({
        user: null,
        selectedWorkspace: null,
        notifications: [],
        unreadCount: 0,
        update: false,
        initializedBoards: new Set(),
    }),
    actions: {
        resetUpdate() {
            this.update = false;
        },
        updateUser(userData) {
            this.user = { ...userData };
            localStorage.setItem("user", JSON.stringify(this.user)); // Update local storage as well
        },
        initEcho() {
            return new Echo({
                broadcaster: "pusher",
                key: "464dddcaaa1a6c17607c",
                cluster: "eu",
                encrypted: true,
            });
        }
        ,
        initTaskListeners() {
            const echo = this.initEcho();
        
            const boards = this.user.workspaces.flatMap(
                (workspace) => workspace.boards
            );
        
            boards.forEach((board) => {
                const boardChannel = echo.channel(`board.${board.id}`);
        
                boardChannel.listen("TaskUpdatedEvent", async (event) => {
                    console.log("Event received:", event);
        
                    if (event.event_type === "task_updated") {
                        console.log("Task updated:", event.task_id);
        
                        try {
                            // Fetch the updated task from the database
                            const { data: updatedTask } = await axios.get(
                                `/api/tasks/${event.task_id}`
                            );
                            console.log("Updated task from database:", updatedTask);
        
                            // Update the task in the board
                            this.updateTaskInBoard(board.id, updatedTask);
                        } catch (error) {
                            console.error("Error fetching the updated task:", error);
                        }
                    } else if (event.event_type === "new_update") {
                        console.log("New update posted:", event.update_id);
        
                        try {
                            // Fetch the newly created update
                            const { data: newUpdate } = await axios.get(
                                `/api/updates/${event.update_id}`
                            );
        
                            console.log("New update from database:", newUpdate);
        
                            // Add the new update to board discussions
                            this.addUpdateToBoard(board.id, newUpdate);
                        } catch (error) {
                            console.error("Error fetching the new update:", error);
                        }
                    }
                });
            });
        },

        // Method to replace the existing task with the updated one in the store
        async updateTaskInBoard(boardId, updatedTask) {
            let taskExists = false; // Flag to check if the task exists
        
            this.user.workspaces.forEach((workspace) => {
                const board = workspace.boards.find(
                    (board) => board.id === boardId
                );
        
                if (board) {
                    const taskIndex = board.tasks.findIndex(
                        (task) => task.id === updatedTask.id
                    );
        
                    if (taskIndex !== -1) {
                        // Task exists; update it
                        board.tasks[taskIndex] = {
                            ...updatedTask,
                            updated_at: new Date().toISOString(), // Update the timestamp here
                        };
                        taskExists = true; // Mark that the task exists
                    }
                }
            });
        
            // If the task does not exist, push the new task to the board
            if (!taskExists) {
                this.user.workspaces.forEach((workspace) => {
                    const board = workspace.boards.find(
                        (board) => board.id === boardId
                    );
        
                    if (board) {
                        // Add the new task to the board
                        board.tasks.push({
                            ...updatedTask,
                            updated_at: new Date().toISOString(), // Set the updated timestamp
                        });
                        
                        this.update = true;
                        localStorage.setItem("user", JSON.stringify(this.user)); // Persist user state
                    }
                });
            }
        },
        
    

        // Method to add a new task to the appropriate board in the store
        addTaskToBoard(boardId, newTask) {
            this.user.workspaces.forEach((workspace) => {
                const board = workspace.boards.find(
                    (board) => board.id === boardId
                );
                if (board) {
                    board.tasks.push(newTask);
                    localStorage.setItem("user", JSON.stringify(this.user)); // Persist user state
                }
            });
        },// Method to add a new update to the appropriate board discussions
        addUpdateToBoard(boardId, newUpdate) {
            this.user.workspaces.forEach((workspace) => {
                const board = workspace.boards.find(
                    (board) => board.id === boardId
                );
                if (board) {
                    // Check if the update already exists in the discussions
                    const discussionExists = board.discussions.find(
                        (discussion) => discussion.id === newUpdate.id
                    );
        
                    // If the update is new, add it to the discussions
                    if (!discussionExists) {
                        board.discussions.push(newUpdate);
        
                        // Check if the update is related to a task
                        if (newUpdate.task_id) {
                            // Find the task in the board's tasks array
                            const task = board.tasks.find(
                                (task) => task.id === newUpdate.task_id
                            );
        
                            // If the task is found, increment its updates_count
                            if (task) {
                                if (!task.updates_count) {
                                    task.updates_count = 0; // Initialize if it doesn't exist
                                }
                                task.updates_count += 1;
                            }
                        }
        
                        this.update = true;
                        localStorage.setItem("user", JSON.stringify(this.user)); // Persist user state
                    }
                }
            });
        },
        
        
        async fetchUser() {
            try {
                const response = await axios.get("/api/user");
                this.user = response.data.user;
                this.setUser(this.user);
                
                if (this.user?.workspaces?.length) {
                    this.selectedWorkspace = this.user.workspaces[0]; // Set default workspace
                }
            } catch (error) {
                console.error("Error fetching user:", error);
            }
        },

        selectWorkspace(workspace) {
            this.selectedWorkspace = workspace;
            console.log("🚀 ~ Workspace selected:", workspace);
            localStorage.setItem("user", JSON.stringify(this.user));
        },
        async setUser(user) {
            this.user = user;
            localStorage.setItem("user", JSON.stringify(user));
        },
        loadUserFromStorage() {
            const storedUser = localStorage.getItem("user");
            if (storedUser) {
                this.user = JSON.parse(storedUser);
            }
        },
        async createWorkspace(workspace_name) {
            try {
                const response = await axios.post("/api/workspaces", {
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
                localStorage.setItem("user", JSON.stringify(this.user));
            } catch (error) {
                console.error("Error creating workspace:", error);
                alert("Failed to create workspace.");
            }
        },
        async createBoard(boardName) {
            if (!this.selectedWorkspace) {
                console.error("No selected workspace");
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

                const response = await axios.post("/api/boards", boardData);
                const createdBoardId = response.data.board.id;

                const { data: newBoard } = await axios.get(
                    `/api/boards/${createdBoardId}?workspace_id=${this.selectedWorkspace.id}`
                );

                const workspace = this.user.workspaces.find(
                    (w) => w.id === this.selectedWorkspace.id
                );
                if (workspace) {
                    this.selectedWorkspace.boards.push(newBoard);

                    localStorage.setItem("user", JSON.stringify(this.user));

                    console.log(
                        "new board added to user workspace",
                        this.selectedWorkspace
                    );
                    localStorage.setItem("user", JSON.stringify(this.user));
                } else {
                    console.error("Workspace not found");
                }
            } catch (error) {
                console.error("Error creating board:", error);
            }
        },
        async createFolder(folder_name) {
            try {
                // Send POST request to create the folder
                const response = await axios.post("/api/folders", {
                    folder_name:folder_name,
                    workspace_id: this.selectedWorkspace.id,
                    is_trashed: false,
                    is_archived: false,
                    trashed_by: null,
                    trashed_at: null,
                    is_favorite: false,
                });
        
                // Get the new folder data from the response
                const newFolder = response.data;
        
                // Find the workspace to which the folder belongs and push the new folder
                const workspace = this.user.workspaces.find(
                    (ws) => ws.id === this.selectedWorkspace.id
                );
                if (workspace) {
                    workspace.folders.push(newFolder);
                } else {
                    console.error("Workspace not found for the newly created folder.");
                }
        
                // Optionally update local storage if you are persisting the user data
                localStorage.setItem("user", JSON.stringify(this.user));
                
            } catch (error) {
                console.error("Error creating folder:", error);
                alert("Failed to create folder.");
            }
        },
        
       updateBoardField(boardId, fieldName, fieldValue) {
    // Find the workspace that contains the board
    const currentWorkspace = this.user.workspaces.find(workspace => 
        workspace.boards.some(board => board.id === boardId)
    );

    if (currentWorkspace) {
        // Find the board and update the specified field
        const board = currentWorkspace.boards.find(board => board.id === boardId);
        
        if (board) {
            // Check if we're changing the workspace_id
            if (fieldName === 'workspace_id') {
                // Remove the board from the current workspace
                currentWorkspace.boards = currentWorkspace.boards.filter(b => b.id !== boardId);

                // Find the new workspace by ID
                const newWorkspace = this.user.workspaces.find(workspace => workspace.id === fieldValue);
                
                if (newWorkspace) {
                    // Add the board to the new workspace
                    
                    newWorkspace.boards.push({
                        ...board,
                        workspace_id: fieldValue // Update the workspace_id
                    });
                    
                    
                    console.log(`Board moved to new workspace: ${fieldValue}`);
                } else {
                    console.error('New workspace not found');
                }
            } else {
                // Update the specified field for the board
                board[fieldName] = fieldValue;
                console.log(`Local store updated: ${fieldName} set to ${fieldValue} for board ${boardId}`);
            }
        }
    }
    
    localStorage.setItem("user", JSON.stringify(this.user));
        },
        async  updateFolderField(folderId, fieldName, fieldValue) {
            // Find the current workspace that contains the folder
            const currentWorkspace = this.user.workspaces.find(workspace => 
                workspace.folders.some(folder => folder.id === folderId)
            );
        
            if (currentWorkspace) {
                // Find the folder and update the specified field
                const folder = currentWorkspace.folders.find(folder => folder.id === folderId);
                
                if (folder) {
                    // Check if we're changing the workspace_id
                    if (fieldName === 'workspace_id') {
                        // Remove the folder from the current workspace
                        currentWorkspace.folders = currentWorkspace.folders.filter(f => f.id !== folderId);
        
                        // Find the new workspace by ID
                        const newWorkspace = this.user.workspaces.find(workspace => workspace.id === fieldValue);
                        
                        if (newWorkspace) {
                            // Add the folder to the new workspace
                            newWorkspace.folders.push({
                                ...folder,
                                workspace_id: fieldValue // Update the workspace_id
                            });
        
                            console.log(`Folder moved to new workspace: ${fieldValue}`);
                        } else {
                            console.error('New workspace not found');
                        }
                    } else {
                        // Update the specified field for the folder
                        folder[fieldName] = fieldValue;
                        console.log(`Local store updated: ${fieldName} set to ${fieldValue} for folder ${folderId}`);
                    }
        
                    try {
                        // Send update request to backend
                        const response = await axios.patch(
                            `/api/folders/${folderId}`,
                            {
                                [fieldName]: fieldValue,
                            }
                        );
                        console.log("Database updated:", response.data);
                    } catch (error) {
                        console.error("Failed to update the database:", error);
                    }
                } else {
                    console.error("Folder not found");
                }
            } else {
                console.error("Current workspace not found");
            }
        
            // Save the updated user state to localStorage
            localStorage.setItem("user", JSON.stringify(this.user));
        },
               
        updateUserRole(userId, newRole, boardId, workSpaceId) {
            if (!this.user || !this.user.workspaces) {
                console.error('User or workspaces data is not available');
                return;
            }
            
            console.log('workspaces user role', this.user.workspaces);
        
            // Find the workspace by ID
            const workspace = this.user.workspaces.find(workspace => workspace.id == workSpaceId);
        
            if (!workspace) {
                console.error(`Workspace with ID ${workSpaceId} not found`);
                return;
            }
        
            // Find the board within the workspace by ID
            const board = workspace.boards.find(board => board.id === boardId);
        
            if (!board) {
                console.error(`Board with ID ${boardId} not found in workspace ${workSpaceId}`);
                return;
            }
        
            // Find the user within the team members of the board and update their role
            const user = board.team.members.find(member => member.id === userId);
        
            if (!user) {
                console.error(`User with ID ${userId} not found in board ${boardId}`);
                return;
            }
        
            // Update the user's role in the frontend store
            user.pivot.role = newRole;
            console.log(`User role updated to: ${newRole} for user ${userId} in board ${boardId}`);
            localStorage.setItem("user", JSON.stringify(this.user));
        },
        
        
        async updateTaskField(taskId, field, value) {
            try {
                // Make the API request to update the specific task field
                
                await axios.patch(`/api/tasks/${taskId}`, {
                    field: field,
                    value: value,
                });

                // Fetch the updated task from the database
                const response = await axios.get(`/api/tasks/${taskId}`);
              const updatedTask = response.data;
              console.log("task update is", updatedTask);

                // Log the activity in the backend

                // Update the task in the frontend
                this.user.workspaces.forEach((workspace) => {
                    workspace.boards.forEach((board) => {
                        const taskIndex = board.tasks.findIndex(
                            (task) => task.id === taskId
                        );
                        if (taskIndex !== -1) {
                            // Replace the old task with the updated task from the database
                            board.tasks[taskIndex] = {
                                ...updatedTask,
                                updated_at: new Date().toISOString(), // Update the timestamp here
                            };
                        }
                    });
                });
                this.update = true;
                // Save the updated user data to local storage
                localStorage.setItem("user", JSON.stringify(this.user));

                // Trigger TaskUpdated event (frontend)
            } catch (error) {
                console.error(`Error updating task ${field}:`, error);
            }
        },

        async acceptInvitation(notification) {
            try {
                console.log(
                    "board id in accept invitation",
                    notification.invitation
                );
                const boardId = notification.invitation.board.id;
                if (!boardId) {
                    throw new Error("Board ID not found in the invitation");
                }

                // Accept the invitation
                await axios.post(
                    `/api/invitations/accept/${notification.invitation.id}`
                );

                // Fetch the new board data
                const { data: newBoard } = await axios.get(
                    `/api/boards/${boardId}`,
                    {
                        params: { with: ["owner", "team", "tasks"] },
                    }
                );

                // Find the user's main workspace (first created workspace)
                const workspace = this.user.workspaces.sort(
                    (a, b) => new Date(a.created_at) - new Date(b.created_at)
                )[0];

                if (workspace) {
                    // Push the new board into the workspace's boards array
                    workspace.boards.push(newBoard);

                    // Update the user state with the modified workspace
                    this.user = {
                        ...this.user,
                        workspaces: this.user.workspaces.map((w) =>
                            w.id === workspace.id ? workspace : w
                        ),
                    };

                    // Update local storage
                    localStorage.setItem("user", JSON.stringify(this.user));
                }

                // Refresh the notifications list
                await this.fetchNotifications();
            } catch (error) {
                console.error("Error handling invitation acceptance:", error);
            }
        },

        async fetchNotifications() {
            try {
                const response = await axios.get("/api/notifications");
                this.notifications = response.data;
                console.log('notifications in userStore',this.notifications)
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        },
        async logout() {
            console.log("looog ouuut");
            this.user = null;
            localStorage.removeItem("token");
            localStorage.removeItem("user"); // Clear local storage
            router.push("/login"); // Redirect to login page
        },
    },
});

axios.interceptors.response.use(
    (response) => response, // Pass through successful responses
    (error) => {
        const userStore = useUserStore();
        if (error.response.status === 401 && userStore.user) {
            userStore.logout(); // Log out the user on token expiration
        }
        return Promise.reject(error);
    }
);

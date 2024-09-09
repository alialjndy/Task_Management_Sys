# Task Management System

## Overview

**The Task Management System is a web application that allows for the effective management of tasks among different roles: Admin, Manager, and User. The system is designed to provide varying access and permissions based on the user's role**

## Roles and Permissions

1. ### Admin

-   Full control over users and tasks.
-   Can perform CRUD (Create, Read, Update, Delete) operations on Users and Tasks.
-   Can assign roles (Admin, Manager, User) to users.

2. ### Manager

-   Can perform CRUD operations on Tasks.
-   Can assign tasks to Users.

3. ### User

-   Can change the status of tasks assigned to (completed or cancelled)
-   Can view all tasks assigned to them.

## Features

1. ### Admin

-   User Management:
    -   Create, update, delete, and view users.
    -   Assign roles to users (Admin, Manager, User).
    -   Filter User by (User or manager) role
-   Task Management:
    -   Create, update, delete, and view Tasks.

2. ### Manager

-   Task Management:
    -   Create, update, delete, and view tasks.
    -   Filter Task By pending , in_progress , cancelled , complete
    -   Assign tasks to users

3. ### User

-   Update Task Status to (complete or cancelled)
-   View all Task assigned to them and filtering it

## Requirments

-   PHP Version 8.3 or earlier
-   Laravel Version 11 or earlier
-   composer
-   XAMPP: Local development environment (or a similar solution)

## API Endpoints

### 1. Authentication

-   POST /api/login: Log in with email and password
-   POST /api/logout: Log out the current user
-   POST /api/me: display info currently user

### 2. AdminManagementUser

-   GET /api/UserManagement : View all users
-   POST /api/UserManagement : Create User
-   PUT /api/UserManagement/{id} : Update User info
-   DELETE /api/UserManagement/{id} : Soft Delete User
-   POST /api/UserManagement/RestoreUser/{id} : Restore User
-   POST /api/UserManagement/assign-role/{id} : Assign Role To User
-   POST /api/UserManagement/forceDelete/{id} : Force Delete User

### 3. TaskManagement

-   GET /api/Task : View all tasks
-   POST /api/Task : Create task
-   PUT /api/Task/{id} : Update Task info
-   DELETE /api/Task/{id} : Soft Delete Task
-   POST /api/Task/RestoreTask/{taskID} : Restore Task
-   POST /api/Task/{TaskId}/assign : Assign Task To User
-   POST /api/Task/forceDelete/{taskID} : Force Delete task

### 4. Ordinary User

-   GET /api/TaskAssigned : view all tasks assigned to them
-   POST /api/Task/{TaskId}/changeStatus : change status task to (completed or cancelled)

## Postman Collection:

You can access the Postman collection for this project by following this [link](https://lively-resonance-695697.postman.co/workspace/My-Workspace~f4d36390-4463-41a5-819e-d347e13c96b0/collection/37833857-9ccef591-20e2-4aef-833d-f31d15b00d22?action=share&creator=37833857). The collection includes all the necessary API requests for testing the application.

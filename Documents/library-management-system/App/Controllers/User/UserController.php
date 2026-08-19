<?php

namespace App\Controllers\User;

use App\Models\UserManagement;
use App\Core\CloudinaryUploader;
use Exception;

class UserController
{
    private UserManagement $userManagement;

    public function __construct()
    {
        $this->userManagement = new UserManagement();
    }

    
    public function readAll(): void
    {
        $users = $this->userManagement->getAllUsers();
        foreach ($users as &$user) {
            unset($user['Password']);
        }
        unset($user);

        http_response_code(200);
        echo json_encode(['users' => $users]);
    }

    
    public function read(array $params): void
    {
        $user = $this->userManagement->getUser((int) ($params['id'] ?? 0));

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found.']);
            return;
        }

        unset($user['Password']);
        http_response_code(200);
        echo json_encode(['user' => $user]);
    }

    
    public function create(): void
    {
        try {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role     = $_POST['role'] ?? 'user';

            if ($name === '' || $email === '' || $password === '') {
                throw new Exception('All fields are required.');
            }

            $imageUrl = null;
            if (!empty($_FILES['profile_image']['tmp_name'])) {
                $uploader = new CloudinaryUploader();
                $imageUrl = $uploader->upload($_FILES['profile_image']['tmp_name'], 'library/profile_pictures');
            }

            $registered = $this->userManagement->register($name, $email, $password, $role, $imageUrl);

            if (!$registered) {
                http_response_code(409);
                echo json_encode(['error' => 'Username or email already taken.']);
                return;
            }

            http_response_code(201);
            echo json_encode(['message' => 'Account created.', 'user' => ['name' => $name, 'email' => $email]]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update(array $params): void
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];

            $name     = trim($input['name'] ?? '');
            $email    = trim($input['email'] ?? '');
            $password = $input['password'] ?? '';
            $role     = $_SESSION['role'] ?? 'user';

            if ($name === '' || $email === '') {
                throw new Exception('Name and email are required.');
            }

            $message = $this->userManagement->updateProfile($name, $email, $password, $role);
            $succeeded = $message === 'Profile updated successfully.';

            if (!$succeeded) {
                http_response_code($message === 'User not found.' ? 404 : 500);
                echo json_encode(['error' => $message]);
                return;
            }

            http_response_code(200);
            echo json_encode(['message' => $message]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    
    public function delete(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);

        $deleted = $this->userManagement->deleteAccount($id);

        if (!$deleted) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete account.']);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Account deleted successfully.']);
    }
}
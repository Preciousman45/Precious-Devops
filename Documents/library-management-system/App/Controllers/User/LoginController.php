<?php

namespace App\Controllers\User;

use App\Models\UserManagement;
use App\Support\Assertion;
use Exception;

class LoginController
{
    private UserManagement $userManagement;

    public function __construct()
    {
        $this->userManagement = new UserManagement();
    }

  
    public function handleLogin(): void
    {
        try {
            $username = trim(htmlspecialchars($_POST['username'] ?? ''));
            $password = $_POST['password'] ?? '';

            Assertion::notEmpty($username, 'Username is required');
            Assertion::notEmpty($password, 'Password is required');

            $user = $this->userManagement->login($username, $password);

            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid username or password.']);
                return;
            }

            $_SESSION['username']  = $user['Name'] ?? '';
            $_SESSION['user_id']   = $user['id'] ?? null;
            $_SESSION['role']      = $user['Role'] ?? '';
            $_SESSION['email']     = $user['Email'] ?? '';
            $_SESSION['logged_in'] = true;

            unset($user['Password']);

            http_response_code(200);
            echo json_encode([
                'message' => 'Logged in successfully.',
                'user'    => $user,
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
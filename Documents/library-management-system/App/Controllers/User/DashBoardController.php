<?php

namespace App\Controllers\User;

use App\Models\BookManagement;
use App\Models\Borrowing;
use App\Models\Inventory;
use App\Models\UserManagement;

class DashboardController
{
    private BookManagement $bookManagement;
    private Borrowing $borrowing;
    private Inventory $inventory;
    private UserManagement $userManagement;

    public function __construct()
    {
        $this->bookManagement = new BookManagement();
        $this->borrowing = new Borrowing();
        $this->inventory = new Inventory();
        $this->userManagement = new UserManagement();
    }

   
    public function show(): void
    {
        if (empty($_SESSION['logged_in'])) {
            http_response_code(401);
            echo json_encode(['error' => 'You must be logged in to view the dashboard.']);
            return;
        }

        $role = $_SESSION['role'] ?? '';

        if ($role === 'Admin') {
            $allUsers = $this->userManagement->getAllUsers();
            foreach ($allUsers as &$user) {
                unset($user['Password']);
            }
            unset($user);

            $data = [
                'role'            => 'Admin',
                'availableBooks'  => $this->bookManagement->getAllBooks(),
                'borrowedBooks'   => $this->borrowing->getAllActiveBorrows(),
                'returnedBooks'   => $this->borrowing->getAllReturnedBorrows(),
                'fines'           => $this->borrowing->getAllFines(),
                'users'           => $allUsers,
            ];
        } else {
            $userId = $_SESSION['user_id'] ?? null;

            $data = [
                'role'            => $role,
                'availableBooks'  => $this->inventory->getAvailableBooks(),
                'borrowedBooks'   => $this->borrowing->getBorrowedBook($userId),
                'borrowHistory'   => $this->borrowing->getBorrowHistory($userId),
            ];
        }

        http_response_code(200);
        echo json_encode($data);
    }
}
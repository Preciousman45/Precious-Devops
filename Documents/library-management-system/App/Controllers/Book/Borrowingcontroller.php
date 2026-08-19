<?php

namespace App\Controllers\Book;

use App\Models\Borrowing;
use App\Support\Assertion;
use Exception;

class BorrowingController
{
    private Borrowing $borrowing;

    public function __construct()
    {
        $this->borrowing = new Borrowing();
    }

    
    public function readAll(): void
    {
        $allBorrowedBooks = $this->borrowing->getAllActiveBorrows();

        http_response_code(200);
        echo json_encode(['borrowedBooks' => $allBorrowedBooks]);
    }

    
    public function history(): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'You must be logged in to view borrow history.']);
            return;
        }

        $history = $this->borrowing->getBorrowHistory($userId) ?: [];

        http_response_code(200);
        echo json_encode(['borrowHistory' => $history]);
    }

    // GET /borrowings/returned  (replaces old ReturnedBookController::showForm — admin: all returned)
    public function returned(): void
    {
        $allReturnedBooks = $this->borrowing->getAllReturnedBorrows();

        http_response_code(200);
        echo json_encode(['returnedBooks' => $allReturnedBooks]);
    }

    
    public function fines(): void
    {
        $fines = $this->borrowing->getAllFines();

        http_response_code(200);
        echo json_encode(['fines' => $fines]);
    }

    
    public function create(): void
    {
        try {
            $userId = $_SESSION['user_id'] ?? null;
            $input  = json_decode(file_get_contents('php://input'), true) ?? [];
            $bookId = trim((string) ($input['bookId'] ?? ''));

            Assertion::notEmpty($bookId, 'Please select a book.');

            if (!$userId) {
                http_response_code(401);
                echo json_encode(['error' => 'You must be logged in to borrow a book.']);
                return;
            }

            $result = $this->borrowing->borrowBook($userId, $bookId);

            if (!$result['success']) {
                http_response_code(400);
                echo json_encode(['error' => $result['message']]);
                return;
            }

            http_response_code(201);
            echo json_encode(['message' => $result['message']]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    
    public function update(array $params): void
    {
        try {
            $userId = $_SESSION['user_id'] ?? null;
            $bookId = (int) ($params['bookId'] ?? 0);

            if (!$userId) {
                http_response_code(401);
                echo json_encode(['error' => 'You must be logged in to return a book.']);
                return;
            }
            if (!$bookId) {
                http_response_code(400);
                echo json_encode(['error' => 'Book ID is required to return a book.']);
                return;
            }

            $result = $this->borrowing->returnBook($userId, $bookId);

            if (!$result['success']) {
                http_response_code(400);
                echo json_encode(['error' => $result['message']]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                'message' => $result['message'],
                'fine'    => $result['fine'] ?? 0,
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
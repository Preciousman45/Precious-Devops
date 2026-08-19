<?php

namespace App\Controllers\Book;

use App\Models\BookManagement;
use App\Models\Inventory;
use App\Models\Borrowing;
use App\Support\Assertion;
use App\Core\CloudinaryUploader;
use Exception;

class BookController
{
    private BookManagement $bookManagement;
    private Inventory $inventory;
    private Borrowing $borrowing;

    public function __construct()
    {
        $this->bookManagement = new BookManagement();
        $this->inventory = new Inventory();
        $this->borrowing = new Borrowing();
    }

    
    public function readAll(): void
    {
        $title = trim($_GET['title'] ?? '');
        $role  = $_SESSION['role'] ?? '';

        $books = $title !== ''
            ? $this->bookManagement->searchBook($title)
            : $this->bookManagement->getAllBooks();

        if ($role === 'Admin') {
            foreach ($books as &$book) {
                $book['inventory']     = $this->inventory->trackInventory((int) $book['id']);
                $book['borrowRecords'] = $this->borrowing->getBookBorrowRecords($book['id']);
            }
            unset($book);
        }

        http_response_code(200);
        echo json_encode(['books' => $books]);
    }

    
    public function read(array $params): void
    {
        $bookId = $params['id'] ?? null;

        $book = $this->bookManagement->getBookById((int) $bookId);
        if ($book === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Book not found.']);
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $role   = $_SESSION['role'] ?? '';
        $activeBorrow = $this->borrowing->findActiveBorrow($bookId, $userId);
        $isBorrowedByUser = $activeBorrow !== null;
        $borrowId = $activeBorrow['borrowId'] ?? null;

        if ($role === 'Admin') {
            http_response_code(200);
            echo json_encode([
                'book'             => $book,
                'inventory'        => $this->inventory->trackInventory((int) $bookId),
                'borrowRecords'    => $this->borrowing->getBookBorrowRecords((int) $bookId),
                'isBorrowedByUser' => $isBorrowedByUser,
                'borrowId'         => $borrowId,
            ]);
            return;
        }

        $availableCopies = $this->inventory->trackInventory((int) $bookId)['availableCopies'] ?? 0;

        http_response_code(200);
        echo json_encode([
            'book' => [
                'id' => $book['id'], 'title' => $book['title'], 'author' => $book['author'],
                'ISBN' => $book['ISBN'], 'publicationDate' => $book['publicationDate'],
                'genre' => $book['genre'], 'description' => $book['description'],
                'image' => $book['image'], 'availableCopies' => $availableCopies,
            ],
            'isBorrowedByUser' => $isBorrowedByUser,
            'borrowId' => $borrowId,
        ]);
    }

    
    public function create(): void
    {
        try {
            $title           = $_POST['title'] ?? '';
            $author          = $_POST['author'] ?? '';
            $ISBN            = $_POST['ISBN'] ?? '';
            $publicationDate = $_POST['publicationDate'] ?? '';
            $genre           = $_POST['genre'] ?? '';
            $copies          = $_POST['copies'] ?? '';
            $description     = $_POST['description'] ?? '';

            Assertion::notEmpty($title, 'Title is required');
            Assertion::notEmpty($author, 'Author is required');
            Assertion::notEmpty($ISBN, 'ISBN is required');
            Assertion::notEmpty($publicationDate, 'Publication date is required');
            Assertion::notEmpty($genre, 'Genre is required');
            Assertion::notEmpty($copies, 'Number of copies is required');
            Assertion::notEmpty($description, 'Description is required');

            $imageUrl = null;
            if (!empty($_FILES['bookImage']['tmp_name'])) {
                $uploader = new CloudinaryUploader();
                $imageUrl = $uploader->upload($_FILES['bookImage']['tmp_name'], 'library/books');
            }

            $bookId = $this->bookManagement->addBook(
                $title, $author, $ISBN, $publicationDate, $genre, $copies, $description, $imageUrl
            );

            if (!$bookId) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to add book.']);
                return;
            }

            $this->inventory->addInventory($bookId, (int) $copies, (int) $copies);

            http_response_code(201);
            echo json_encode([
                'message' => 'Book added successfully.',
                'book' => ['id' => $bookId, 'title' => $title, 'author' => $author, 'ISBN' => $ISBN],
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    
    public function update(array $params): void
    {
        try {
            $id = $params['id'] ?? null;
            $input = json_decode(file_get_contents('php://input'), true) ?? [];

            $title           = $input['title'] ?? '';
            $author          = $input['author'] ?? '';
            $ISBN            = $input['ISBN'] ?? '';
            $publicationDate = $input['publicationDate'] ?? '';
            $genre           = $input['genre'] ?? '';
            $copies          = $input['copies'] ?? '';
            $description     = $input['description'] ?? '';

            if (!$id) {
                throw new Exception('Missing book ID for update.');
            }

            $isUpdated = $this->bookManagement->updateBook(
                $id, $title, $author, $ISBN, $publicationDate, $genre, $copies, $description
            );

            if (!$isUpdated) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update book.']);
                return;
            }

            http_response_code(200);
            echo json_encode(['message' => 'Book updated successfully.']);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    
    public function delete(array $params): void
    {
        $id = $params['id'] ?? null;

        $book = $this->bookManagement->getBookById((int) $id);
        if ($book === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Book not found.']);
            return;
        }

        $deleted = $this->bookManagement->deleteBook($book['title'], $book['author'], $book['ISBN']);

        if (!$deleted) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete book.']);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Book deleted successfully.']);
    }
}
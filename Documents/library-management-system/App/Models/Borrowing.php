<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Borrowing
{
    private PDO $pdo;

    
    private const FINE_PER_DAY = 50; 

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getBorrowedBook($userId): array
    {
        $sql = 'SELECT BorrowingTable.borrowId,
                       BorrowingTable.bookId,
                       BookTable.title,
                       BookTable.author,
                       BookTable.ISBN,
                       BookTable.image,
                       BorrowingTable.borrowDate,
                       BorrowingTable.dueDate
                FROM BookTable
                JOIN BorrowingTable ON BorrowingTable.bookId = BookTable.id
                WHERE BorrowingTable.userId = :userId
                  AND BorrowingTable.returnDate IS NULL';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':userId' => $userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBorrowHistory($userId): array
    {
        $sql = 'SELECT BorrowingTable.borrowId,
                       BookTable.id AS bookId,
                       BookTable.title,
                       BookTable.author,
                       BookTable.image,
                       BorrowingTable.borrowDate,
                       BorrowingTable.dueDate,
                       BorrowingTable.returnDate,
                       BorrowingTable.fine
                FROM BookTable
                JOIN BorrowingTable ON BorrowingTable.bookId = BookTable.id
                WHERE BorrowingTable.userId = :userId
                ORDER BY BorrowingTable.borrowDate DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':userId' => $userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function borrowBook($userId, $bookId): array
    {
        try {
            $this->pdo->beginTransaction();

            // Has THIS user already borrowed this book and not returned it?
            $statement = $this->pdo->prepare(
                'SELECT borrowId FROM BorrowingTable WHERE bookId = ? AND userId = ? AND returnDate IS NULL'
            );
            $statement->execute([$bookId, $userId]);
            $existingBorrow = $statement->fetch(PDO::FETCH_ASSOC);

            if ($existingBorrow) {
                $this->pdo->rollBack();
                return ['success' => false, 'message' => 'You have already borrowed this book.'];
            }

            $sql = 'SELECT availableCopies 
                    FROM InventoryTable
                    WHERE bookId = :bookId FOR UPDATE';
            $statement = $this->pdo->prepare($sql);
            $statement->execute(['bookId' => $bookId]);
            $inventory = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$inventory || $inventory['availableCopies'] <= 0) {
                $this->pdo->rollBack();
                return ['success' => false, 'message' => 'No copies available.'];
            }

            $borrowDate = date('Y-m-d');
            $dueDate    = date('Y-m-d', strtotime('+30 days'));

            $sql = 'INSERT INTO BorrowingTable
                        (bookId, userId, borrowDate, dueDate)
                    VALUES (:bookId, :userId, :borrowDate, :dueDate)';

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':bookId'     => $bookId,
                ':userId'     => $userId,
                ':borrowDate' => $borrowDate,
                ':dueDate'    => $dueDate,
            ]);

            $sql = 'UPDATE InventoryTable SET availableCopies = availableCopies - 1 
                    WHERE bookId = :bookId';
            $statement = $this->pdo->prepare($sql);
            $statement->execute([':bookId' => $bookId]);

            $this->pdo->commit();
            return ['success' => true, 'message' => 'Book borrowed successfully.'];

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function returnBook($userId, $bookId): array
    {
        try {
            $this->pdo->beginTransaction();

            $sql = 'SELECT borrowId FROM BorrowingTable 
                    WHERE userId = :userId AND bookId = :bookId AND returnDate IS NULL
                    FOR UPDATE';
            $statement = $this->pdo->prepare($sql);
            $statement->execute([':userId' => $userId, ':bookId' => $bookId]);
            $borrowRecord = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$borrowRecord) {
                $this->pdo->rollBack();
                return ['success' => false, 'message' => 'No active borrow record found for this book.'];
            }

            $returnDate = date('Y-m-d');
            $sql = 'UPDATE BorrowingTable 
                    SET returnDate = :returnDate 
                    WHERE borrowId = :borrowId';
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':returnDate' => $returnDate,
                ':borrowId'   => $borrowRecord['borrowId'],
            ]);

            $sql = 'UPDATE InventoryTable
                    SET availableCopies = availableCopies + 1  
                    WHERE bookId = :bookId';
            $statement = $this->pdo->prepare($sql);
            $statement->execute([':bookId' => $bookId]);

            $this->pdo->commit();

            // Now that returnDate is committed, calculate any overdue fine
            $fineResult = $this->calculateFine($borrowRecord['borrowId']);

            return [
                'success' => true,
                'message' => 'Book returned successfully. ' . ($fineResult['message'] ?? ''),
                'fine'    => $fineResult['fine'] ?? 0,
            ];

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    
    public function calculateFine($borrowId): array
    {
        $sql = 'SELECT borrowId, dueDate, returnDate 
                FROM BorrowingTable 
                WHERE borrowId = :borrowId';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':borrowId' => $borrowId]);
        $record = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            return ['success' => false, 'message' => 'Borrow record not found.', 'fine' => 0];
        }

        if (empty($record['returnDate'])) {
            return ['success' => false, 'message' => 'Book has not been returned yet.', 'fine' => 0];
        }

        $dueDate    = new \DateTime($record['dueDate']);
        $returnDate = new \DateTime($record['returnDate']);

        $overdueDays = 0;
        if ($returnDate > $dueDate) {
            $overdueDays = $dueDate->diff($returnDate)->days;
        }

        $fine = $overdueDays * self::FINE_PER_DAY;

        $sql = 'UPDATE BorrowingTable SET fine = :fine WHERE borrowId = :borrowId';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':fine' => $fine, ':borrowId' => $borrowId]);

        return [
            'success'     => true,
            'fine'        => $fine,
            'overdueDays' => $overdueDays,
            'message'     => $overdueDays > 0
                ? "This book was {$overdueDays} day(s) overdue. Fine: ₦{$fine}."
                : 'No fine — book was returned on time.',
        ];
    }

    public function getBookBorrowRecords($bookId): array
    {
        $sql = 'SELECT BorrowingTable.borrowId,
                       BorrowingTable.userId,
                       Users.Name AS borrowerName,
                       BorrowingTable.borrowDate,
                       BorrowingTable.dueDate,
                       BorrowingTable.returnDate,
                       BorrowingTable.fine
                FROM BorrowingTable
                JOIN Users ON Users.id = BorrowingTable.userId
                WHERE BorrowingTable.bookId = :bookId
                ORDER BY BorrowingTable.borrowDate DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':bookId' => $bookId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findActiveBorrow($bookId, $userId): ?array
    {
        $sql = 'SELECT * FROM BorrowingTable 
                WHERE bookId = :bookId AND userId = :userId AND returnDate IS NULL';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':bookId' => $bookId, ':userId' => $userId]);
        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Admin: every currently-borrowed book, across all users
    public function getAllActiveBorrows(): array
    {
        $sql = 'SELECT BorrowingTable.borrowId,
                       BorrowingTable.bookId,
                       BookTable.title,
                       BookTable.author,
                       BookTable.image,
                       Users.Name AS username,
                       BorrowingTable.borrowDate,
                       BorrowingTable.dueDate
                FROM BorrowingTable
                JOIN BookTable ON BookTable.id = BorrowingTable.bookId
                JOIN Users ON Users.id = BorrowingTable.userId
                WHERE BorrowingTable.returnDate IS NULL
                ORDER BY BorrowingTable.borrowDate DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: every book with an unpaid/outstanding fine, tied to the borrower
    public function getAllFines(): array
    {
        $sql = 'SELECT BorrowingTable.borrowId,
                       BorrowingTable.bookId,
                       BookTable.title,
                       BookTable.author,
                       BookTable.image,
                       Users.Name AS username,
                       Users.Email AS userEmail,
                       BorrowingTable.borrowDate,
                       BorrowingTable.dueDate,
                       BorrowingTable.returnDate,
                       BorrowingTable.fine
                FROM BorrowingTable
                JOIN BookTable ON BookTable.id = BorrowingTable.bookId
                JOIN Users ON Users.id = BorrowingTable.userId
                WHERE BorrowingTable.fine > 0
                ORDER BY BorrowingTable.fine DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getAllReturnedBorrows(): array
    {
        $sql = 'SELECT BorrowingTable.borrowId,
                       BorrowingTable.bookId,
                       BookTable.title,
                       BookTable.author,
                       BookTable.image,
                       Users.Name AS username,
                       BorrowingTable.borrowDate,
                       BorrowingTable.dueDate,
                       BorrowingTable.returnDate,
                       BorrowingTable.fine
                FROM BorrowingTable
                JOIN BookTable ON BookTable.id = BorrowingTable.bookId
                JOIN Users ON Users.id = BorrowingTable.userId
                WHERE BorrowingTable.returnDate IS NOT NULL
                ORDER BY BorrowingTable.returnDate DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
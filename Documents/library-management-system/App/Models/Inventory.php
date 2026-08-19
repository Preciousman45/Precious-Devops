<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Inventory
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function trackInventory(int $bookId): ?array
    {
        $sql = "SELECT
                    b.title,
                    i.bookId AS id,
                    i.totalCopies,
                    i.availableCopies,
                    borrow.userId AS borrowerId
                FROM InventoryTable i
                INNER JOIN BookTable b ON b.id = i.bookId
                LEFT JOIN BorrowingTable borrow 
                    ON b.id = borrow.bookId AND borrow.returnDate IS NULL
                WHERE i.bookId = :bookId";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $statement->execute();

         return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
        
    }

    public function getAvailableBooks(): array
{
    $sql = "SELECT
                BookTable.id,
                BookTable.title,
                BookTable.author,
                BookTable.image,
                InventoryTable.availableCopies
            FROM BookTable
            INNER JOIN InventoryTable ON BookTable.id = InventoryTable.bookId
            WHERE InventoryTable.availableCopies > 0";

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

    public function addInventory(int $bookId, int $totalCopies, int $availableCopies): bool
    {
        $sql = "INSERT INTO InventoryTable (bookId, totalCopies, availableCopies)
                VALUES (:bookId, :totalCopies, :availableCopies)";

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            ':bookId'          => $bookId,
            ':totalCopies'     => $totalCopies,
            ':availableCopies' => $availableCopies,
        ]);
    }

    public function updateInventory(int $bookId, int $totalCopies, int $availableCopies): bool
    {
        $sql = "UPDATE InventoryTable
                SET totalCopies = :totalCopies,
                    availableCopies = :availableCopies
                WHERE bookId = :bookId";

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            ':bookId'          => $bookId,
            ':totalCopies'     => $totalCopies,
            ':availableCopies' => $availableCopies,
        ]);
    }

    public function checkAvailability(int $bookId): bool
    {
        $sql = "SELECT availableCopies FROM InventoryTable WHERE bookId = :bookId";

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':bookId' => $bookId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result && $result['availableCopies'] > 0;
    }

    
}
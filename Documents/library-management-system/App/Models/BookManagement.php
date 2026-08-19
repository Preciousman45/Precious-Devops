<?php

namespace App\Models;

use App\Core\Database;

use PDO;
use PDOException;

class BookManagement
{
    private PDO $pdo;
    private Inventory $inventory;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        
    }

    public function addBook($title, $author, $ISBN, $publicationDate, $genre, $copies, $description, $image = null): int
{
    $sql = "INSERT INTO BookTable
            (title, author, ISBN, publicationDate, genre, copies, description, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $statement = $this->pdo->prepare($sql);
    $inserted = $statement->execute([
        $title, $author, $ISBN, $publicationDate, $genre, $copies, $description, $image
    ]);

    if (!$inserted) {
        return false;
    }

    $newBookId = $this->pdo->lastInsertId();
    return $newBookId;
     
}
    public function getBookById($id): ?array
    {
        $sql = "SELECT * FROM BookTable WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $book = $statement->fetch(PDO::FETCH_ASSOC);
        return $book ?: null;
    }

    public function updateBook($id, $title, $author, $ISBN, $publicationDate, $genre, $copies, $description, $image = null): bool
    {
        $sql = "UPDATE BookTable
                SET title = :title, author = :author, ISBN = :ISBN,
                    publicationDate = :publicationDate, genre = :genre,
                    copies = :copies, description = :description, image = :image
                WHERE id = :id";

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            ':id'              => $id,
            ':title'           => $title,
            ':author'          => $author,
            ':ISBN'            => $ISBN,
            ':publicationDate' => $publicationDate,
            ':genre'           => $genre,
            ':copies'          => $copies,
            ':description'     => $description,
            ':image'           => $image,
        ]);
    }

    public function deleteBook($title, $author, $ISBN): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "SELECT id FROM BookTable 
                    WHERE TRIM(title) = :title AND TRIM(author) = :author AND TRIM(ISBN) = :ISBN";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':title', $title, PDO::PARAM_STR);
            $statement->bindParam(':author', $author, PDO::PARAM_STR);
            $statement->bindParam(':ISBN', $ISBN, PDO::PARAM_STR);
            $statement->execute();

            $book = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$book) {
                $this->pdo->rollBack();
                return false;
            }

            $bookId = $book['id'];

            $this->pdo->prepare("DELETE FROM InventoryTable WHERE bookId = ?")->execute([$bookId]);
            $this->pdo->prepare("DELETE FROM BookTable WHERE id = ?")->execute([$bookId]);

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function searchBook($title): array
    {
        $sql = "SELECT * FROM BookTable WHERE TRIM(title) = :bookSearched";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':bookSearched', $title, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBooks(): array
    {
        $sql = "SELECT * FROM BookTable";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
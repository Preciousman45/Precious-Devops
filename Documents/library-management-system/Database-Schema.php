<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Database;
use PDOException;

$pdo = Database::getConnection();

$statements = [

    "CREATE TABLE IF NOT EXISTS Users (
        id INT AUTO_INCREMENT,
        Name VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL,
        Password VARCHAR(100) NOT NULL,
        Role VARCHAR(100) NOT NULL,
        PRIMARY KEY(id)
    )",

    "CREATE TABLE IF NOT EXISTS BookTable (
        id INT AUTO_INCREMENT,
        title VARCHAR(100) NOT NULL,
        author VARCHAR(100) NOT NULL,
        ISBN VARCHAR(20) NOT NULL,
        publicationDate DATE NOT NULL,
        genre VARCHAR(100) NOT NULL,
        image VARCHAR(255),
        description VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
    )",

    "CREATE TABLE IF NOT EXISTS BorrowingTable (
        borrowId INT AUTO_INCREMENT,
        bookId INT NOT NULL,
        userId INT NOT NULL,
        borrowDate DATE NOT NULL,
        dueDate DATE NOT NULL,
        returnDate DATE,
        fine DECIMAL(10,2) DEFAULT 0,
        PRIMARY KEY(borrowId),
        FOREIGN KEY (userId) REFERENCES Users(id),
        FOREIGN KEY (bookId) REFERENCES BookTable(id)
    )",

    "CREATE TABLE IF NOT EXISTS InventoryTable (
        bookId INT NOT NULL,
        totalCopies INT NOT NULL,
        availableCopies INT NOT NULL,
        PRIMARY KEY(bookId),
        FOREIGN KEY (bookId) REFERENCES BookTable(id)
    )"

];

foreach ($statements as $index => $statement) {
    try {
        $pdo->exec($statement);
        echo "Table " . ($index + 1) . " created successfully.<br>";
    } catch (PDOException $e) {
        echo "Error in statement " . ($index + 1) . ":<br>";
        echo "<pre>" . htmlspecialchars($statement) . "</pre>";
        die($e->getMessage());
    }
}
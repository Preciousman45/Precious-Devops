<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class UserManagement
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function login(string $username, string $password): array|false
    {
        try {
            $sql = "SELECT * FROM Users WHERE Name = :username";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->execute();

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['Password'])) {
                return $user;
            }

            return false;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function register(string $name, string $email, string $password, string $role, $image = null): bool
    {
        try {
            $sql = "SELECT * FROM Users WHERE Name = :username OR Email = :email";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':username', $name, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->execute();

            if ($statement->fetch(PDO::FETCH_ASSOC)) {
                return false; // username or email already taken
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO Users (Name, Email, Password, Role, image) VALUES (?, ?, ?, ?, ?)";
            $statement = $this->pdo->prepare($sql);

            return $statement->execute([$name, $email, $hashedPassword, $role, $image]);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateProfile(string $name, string $email, string $password, string $role, $image = null): string
    {
        try {
            $sql = "SELECT * FROM Users WHERE Name = :username";

            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':username', $name, PDO::PARAM_STR);
            $statement->execute();

            if (!$statement->fetch(PDO::FETCH_ASSOC)) {
                return "User not found.";
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE Users
                    SET Name = :name, Email = :email, Password = :password, Role = :role, image = :image
                    WHERE Name = :username";

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $hashedPassword,
                ':role'     => $role,
                ':username' => $name,
                ':image'    => $image,
            ]);

            return "Profile updated successfully.";

        } catch (PDOException $e) {
            return "Error updating profile: " . $e->getMessage();
        }
    }

    public function getUser(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM Users WHERE id = :id";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAllUsers(): array
    {
        try {
            $sql = "SELECT * FROM Users";

            $statement = $this->pdo->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function deleteAccount(int $id): bool
    {
        try {
            $sql = "DELETE FROM Users WHERE id = :id";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);

            return $statement->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getUserByUsername(string $username): ?array
    {
        try {
            $sql = "SELECT * FROM Users WHERE Name = :username";

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
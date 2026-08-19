<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books - Library</title>

    <link rel="stylesheet" href="/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Library</h2>

        <?php if ($_SESSION['role'] === "Admin"): ?>
            <a href="/dashboard">Dashboard</a>
            <a href="/searchBook">View Books</a>
            <a href="/addbook">Add Book</a>
            <a href="/updateBook">Update Book</a>
            <a href="/deleteBook">Delete Book</a>
            <a href="/inventoryTracking">Track Inventory</a>
            <a href="/inventoryUpdate">Update Inventory</a>
        <?php else: ?>
            <a href="/dashboard">Dashboard</a>
            <a href="/searchBook">View Books</a>
            <a href="/borrowBook">Borrow Book</a>
            <a href="/returnBook">Return Book</a>
            <a href="/borrowHistory">Borrow History</a>
            <a href="/updateProfile">My Profile</a>
        <?php endif; ?>

        <a href="/logout">Logout</a>
    </div>

    <div class="main">

        <div class="section-header">
            <h2>All Users</h2>
            <a href="/dashboard"><button type="button" class="btn-view-all">&larr; Back to Dashboard</button></a>
        </div>

        <div class="book-grid">
            <?php foreach ($allUsers as $user): ?>
                <a href="/viewUserDetails?id=<?= urlencode($user['id']) ?>" class="book-card user-card">
                    <div class="book-cover">
                        <?php if (!empty($user['image'])): ?>
                            <img src="<?= htmlspecialchars($user['image']) ?>" alt="<?= htmlspecialchars($user['Name']) ?>">
                        <?php else: ?>
                            <div class="no-cover">No Image</div>
                        <?php endif; ?>
                    </div>
                    <div class="book-info">
                        <h4><?= htmlspecialchars($user['Name']) ?></h4>
                        <p><?= htmlspecialchars($user['Role']) ?></p>
                        <p><?= htmlspecialchars($user['Email']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if (empty($allUsers)): ?>
                <p class="empty-msg">No users found.</p>
            <?php endif; ?>
        </div>

    </div>

</div>

</body>
</html>
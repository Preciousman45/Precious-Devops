<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?> - Library</title>

    <link rel="stylesheet" href="/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Library</h2>

        <?php if ($_SESSION['role'] === "Admin"): ?>
            <a href="/dashboard">Dashboard</a>
            
        <?php else: ?>
            
            
        <?php endif; ?>

        
    </div>

    <div class="main">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="status-msg error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="details-page">

            <div class="details-image">
                <?php if (!empty($book['image'])): ?>
                    <img src="<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                <?php else: ?>
                    <div class="no-cover-large">No Image Available</div>
                <?php endif; ?>
            </div>

            <div class="details-info">
                <h1><?= htmlspecialchars($book['title']) ?></h1>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p><strong>ISBN:</strong> <?= htmlspecialchars($book['ISBN'] ?? '-') ?></p>
                <p><strong>Genre:</strong> <?= htmlspecialchars($book['genre'] ?? '-') ?></p>
                <p><strong>Publication Date:</strong> <?= htmlspecialchars($book['publicationDate'] ?? '-') ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($book['description'] ?? 'No description available.') ?></p>
                <p><strong>Copies Available:</strong> <?= htmlspecialchars($book['availableCopies'] ?? $book['copies'] ?? '-') ?></p>

                <?php if ($_SESSION['role'] === 'Admin'): ?>

                    <div class="inventory-stats">
                        <div class="inventory-stat">
                            <span class="inventory-stat-label">Total Copies</span>
                            <span class="inventory-stat-value"><?= htmlspecialchars($inventoryDetails['totalCopies'] ?? '-') ?></span>
                        </div>
                        <div class="inventory-stat">
                            <span class="inventory-stat-label">Available</span>
                            <span class="inventory-stat-value"><?= htmlspecialchars($inventoryDetails['availableCopies'] ?? '-') ?></span>
                        </div>
                        <div class="inventory-stat">
                            <span class="inventory-stat-label">Borrowed</span>
                            <span class="inventory-stat-value">
                                <?= isset($inventoryDetails['totalCopies'], $inventoryDetails['availableCopies'])
                                        ? htmlspecialchars($inventoryDetails['totalCopies'] - $inventoryDetails['availableCopies'])
                                        : '-' ?>
                            </span>
                        </div>
                    </div>

                    <div class="details-actions">
                        <a href="/updateBook?id=<?= urlencode($book['id']) ?>">
                            <button type="button" class="btn-edit">Edit Book</button>
                        </a>
                        <a href="/deleteBook?id=<?= urlencode($book['id']) ?>" onclick="return confirm('Are you sure you want to delete this book?');">
                            <button type="button" class="btn-delete">Delete Book</button>
                        </a>
                    </div>

                <?php else: ?>

                    <?php if ($isBorrowedByUser): ?>
                        <form action="/returnBook" method="POST">
                            <input type="hidden" name="borrowId" value="<?= htmlspecialchars($borrowId) ?>">
                            <input type="hidden" name="bookId" value="<?= htmlspecialchars($book['id']) ?>">
                            <button type="submit" class="btn-return">Return Book</button>
                        </form>
                    <?php else: ?>
                        <form action="/borrowBook" method="POST">
                            <input type="hidden" name="bookId" value="<?= htmlspecialchars($book['id']) ?>">
                            <button type="submit" class="btn-borrow"
                                <?= ((int)($book['availableCopies'] ?? $book['copies'] ?? 0) < 1) ? 'disabled' : '' ?>>
                                Borrow Book
                            </button>
                        </form>
                    <?php endif; ?>

                <?php endif; ?>

                <a href="/dashboard" class="back-link">&larr; Back to Dashboard</a>
            </div>

        </div>

    </div>

</div>

</body>
</html>
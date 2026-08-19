<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returned Books - Library</title>

    <link rel="stylesheet" href="/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Library</h2>

       

        
    </div>

    <div class="main">

        <div class="section-header">
            <h2>All Returned Books</h2>
            <a href="/dashboard"><button type="button" class="btn-view-all">&larr; Back to Dashboard</button></a>
        </div>

        <div class="book-grid">
            <?php foreach ($allBorrowedBooks as $book): ?>
                <a href="/viewBookDetails?id=<?= urlencode($book['bookId']) ?>&borrowId=<?= urlencode($book['borrowId']) ?>" class="book-card">
                    <div class="book-cover">
                        <?php if (!empty($book['image'])): ?>
                            <img src="<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?> cover">
                        <?php else: ?>
                            <div class="no-cover">No Image</div>
                        <?php endif; ?>
                    </div>
                    <div class="book-info">
                        <h4><?= htmlspecialchars($book['title']) ?></h4>
                        <p><?= htmlspecialchars($book['author']) ?></p>

                        <div class="book-meta">
                            <p><strong>Returned by:</strong> <?= htmlspecialchars($book['username']) ?></p>
                            <p><strong>Borrowed:</strong> <?= htmlspecialchars($book['borrowDate']) ?></p>
                            <p><strong>Due:</strong> <?= htmlspecialchars($book['dueDate']) ?></p>
                            <p><strong>Returned:</strong> <?= htmlspecialchars($book['returnDate']) ?></p>
                            <p><strong>Fine:</strong> <?= htmlspecialchars($book['fine']) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if (empty($allBorrowedBooks)): ?>
                <p class="empty-msg">No returned books found.</p>
            <?php endif; ?>
        </div>

    </div>

</div>

</body>
</html>
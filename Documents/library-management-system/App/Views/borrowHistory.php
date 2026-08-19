<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow History</title>

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
            <h2>Borrow History</h2>
            <a href="/dashboard"><button type="button" class="btn-view-all">&larr; Back to Dashboard</button></a>
        </div>

        <div class="book-grid">
            <?php foreach ($history as $record): ?>
                <?php
                    $isReturned = !empty($record['returnDate']);
                    $isOverdue  = !$isReturned && strtotime($record['dueDate']) < time();
                    $status = $isReturned ? 'Returned' : ($isOverdue ? 'Overdue' : 'Borrowed');
                ?>
                <a href="/viewBookDetails?id=<?= urlencode($record['bookId']) ?>" class="book-card">
                    <div class="book-cover">
                        <?php if (!empty($record['image'])): ?>
                            <img src="<?= htmlspecialchars($record['image']) ?>" alt="<?= htmlspecialchars($record['title']) ?> cover">
                        <?php else: ?>
                            <div class="no-cover">No Image</div>
                        <?php endif; ?>
                    </div>
                    <div class="book-info">
                        <h4><?= htmlspecialchars($record['title']) ?></h4>
                        <p><?= htmlspecialchars($record['author']) ?></p>
                        <p>Due: <?= htmlspecialchars($record['dueDate']) ?></p>
                        <span class="status-badge <?= $isReturned ? 'status-returned' : ($isOverdue ? 'status-overdue' : 'status-active') ?>">
                            <?= $status ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if (empty($history)): ?>
                <p class="empty-msg">You haven't borrowed any books yet.</p>
            <?php endif; ?>
        </div>

    </div>

</div>

</body>
</html>
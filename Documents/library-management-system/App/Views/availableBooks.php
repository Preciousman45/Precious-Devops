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
           
            
        <?php else: ?>
            <a href="/dashboard">Dashboard</a>
            
        <?php endif; ?>

        
    </div>

    <div class="main">

        <div class="section-header">
            <h2>Available Books</h2>
            <a href="/dashboard"><button type="button" class="btn-view-all">&larr; Back to Dashboard</button></a>
        </div>

        <div class="book-grid">
            <?php foreach ($availableBooks as $book): ?>
                <a href="/viewBookDetails?id=<?= urlencode($book['id']) ?>" class="book-card">
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
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if (empty($availableBooks)): ?>
                <p class="empty-msg">No books currently available.</p>
            <?php endif; ?>
        </div>

    </div>

</div>

</body>
</html>
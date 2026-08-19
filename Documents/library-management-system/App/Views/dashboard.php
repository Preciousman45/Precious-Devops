<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>

    <link rel="stylesheet" href="/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Library</h2>

        <?php if ($_SESSION['role'] === "Admin"): ?>

            <a href="/dashboard">Dashboard</a>
            <a href="/Profile">My Profile</a>
            

        <?php else: ?>

            <a href="/dashboard">Dashboard</a>
            <!-- <a href="/searchBook">View Books</a>
            <a href="/borrowBook">Borrow Book</a>
            <a href="/returnBook">Return Book</a>
            <a href="/borrowHistory">Borrow History</a> -->
            <a href="/Profile">My Profile</a>

        <?php endif; ?>

        <a href="/logout">Logout</a>
    </div>

    <div class="main">

        <div class="top">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h1>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        </div>

        <?php if ($_SESSION['role'] === "Admin"): ?>

            <!-- ============================= -->
            <!-- ADMIN DASHBOARD -->
            <!-- ============================= -->

            <div class="cards">
                <div class="card"><h3>Total Books</h3><h2><?= count($availableBooks) ?></h2></div>
            </div>

            <!-- AVAILABLE BOOKS -->
            <div class="section-header">
                <h2>Available Books</h2>
                <div class="header-actions">
                    <a href="/availableBooks"><button type="button" class="btn-view-all">View All</button></a>
                    <a href="/addbook"><button type="button" class="btn-add">+ Add Book</button></a>
                </div>
            </div>

            <div class="book-grid">
                <?php foreach (array_slice($availableBooks, 0, 4) as $book): ?>
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
                    <p class="empty-msg">No books in the system yet.</p>
                <?php endif; ?>
            </div>

            <!-- BORROWED BOOKS (all users) -->
            <div class="section-header">
                <h2>Borrowed Books</h2>
                <a href="/allBorrowedBooks"><button type="button" class="btn-view-all">View All</button></a>
            </div>

            <div class="book-grid">
                <?php foreach (array_slice($borrowedBooksAdmin, 0, 4) as $record): ?>
                    <a href="/viewBookDetails?id=<?= urlencode($record['bookId']) ?>&borrowId=<?= urlencode($record['borrowId']) ?>" class="book-card">
                        <div class="book-cover">
                            <?php if (!empty($record['image'])): ?>
                                <img src="<?= htmlspecialchars($record['image']) ?>" alt="<?= htmlspecialchars($record['title']) ?> cover">
                            <?php else: ?>
                                <div class="no-cover">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h4><?= htmlspecialchars($record['title']) ?></h4>
                            <p>Borrowed by <?= htmlspecialchars($record['username']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php if (empty($borrowedBooksAdmin)): ?>
                    <p class="empty-msg">No books currently borrowed.</p>
                <?php endif; ?>
            </div>

            <!-- RETURNED BOOKS -->
            <div class="section-header">
                <h2>Returned Books</h2>
                <a href="/allReturnedBooks"><button type="button" class="btn-view-all">View All</button></a>
            </div>

            <div class="book-grid">
                <?php foreach (array_slice($returnedBooksAdmin, 0, 4) as $record): ?>
                    <a href="/viewBookDetails?id=<?= urlencode($record['bookId']) ?>&borrowId=<?= urlencode($record['borrowId']) ?>" class="book-card">
                        <div class="book-cover">
                            <?php if (!empty($record['image'])): ?>
                                <img src="<?= htmlspecialchars($record['image']) ?>" alt="<?= htmlspecialchars($record['title']) ?> cover">
                            <?php else: ?>
                                <div class="no-cover">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h4><?= htmlspecialchars($record['title']) ?></h4>
                            <p>Returned by <?= htmlspecialchars($record['username']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php if (empty($returnedBooksAdmin)): ?>
                    <p class="empty-msg">No return history yet.</p>
                <?php endif; ?>
            </div>

            <!-- FINES -->
            <div class="section-header">
                <h2>Fines</h2>
                <a href="/allFines"><button type="button" class="btn-view-all">View All</button></a>
            </div>

            <div class="book-grid">
                <?php foreach (array_slice($fines, 0, 4) as $record): ?>
                    <a href="/viewBookDetails?id=<?= urlencode($record['bookId']) ?>&borrowId=<?= urlencode($record['borrowId']) ?>" class="book-card">
                        <div class="book-cover">
                            <?php if (!empty($record['image'])): ?>
                                <img src="<?= htmlspecialchars($record['image']) ?>" alt="<?= htmlspecialchars($record['title']) ?> cover">
                            <?php else: ?>
                                <div class="no-cover">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h4><?= htmlspecialchars($record['title']) ?></h4>
                            <p><?= htmlspecialchars($record['username']) ?></p>
                            <span class="fine-amount">₦<?= htmlspecialchars($record['fine']) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php if (empty($fines)): ?>
                    <p class="empty-msg">No outstanding fines.</p>
                <?php endif; ?>
            </div>

            <!-- USERS -->
            

        <?php else: ?>

            <!-- ============================= -->
            <!-- USER DASHBOARD -->
            <!-- ============================= -->

            <form action="/searchBook" method="GET">
                <input class="search" type="text" name="title"
                       placeholder="Search books by title, author or ISBN">
            </form>

            <!-- AVAILABLE BOOKS PREVIEW -->
            <div class="section-header">
                <h2>Available Books</h2>
                <a href="/availableBooks"><button type="button" class="btn-view-all">View All</button></a>
            </div>

            <div class="book-grid">
                <?php foreach (array_slice($availableBooks, 0, 4) as $book): ?>
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

            <!-- BORROWED BOOKS PREVIEW -->
            <div class="section-header">
                <h2>My Borrowed Books</h2>
                <a href="/borrowHistory"><button type="button" class="btn-view-all">View All</button></a>
            </div>

            <div class="book-grid">
                <?php foreach (array_slice($borrowedBooks, 0, 4) as $book): ?>
                    <a href="/viewBookDetails?id=<?= urlencode($book['bookId']) ?>" class="book-card">
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

                <?php if (empty($borrowedBooks)): ?>
                    <p class="empty-msg">You haven't borrowed any books yet.</p>
                <?php endif; ?>
            </div>


            <div class="section-header">
                <h2>My Borrow History</h2>
                <a href="/borrowHistory"><button type="button" class="btn-view-all">View All</button></a>
            </div>

            <div class="book-grid">
                <?php foreach (array_slice($borrowHistory, 0, 4) as $book): ?>
                    <a href="/viewBookDetails?id=<?= urlencode($book['bookId']) ?>" class="book-card">
                        <div class="book-cover">
                            <?php if (!empty($book['image'])): ?>
                                <img src="<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?> cover">
                            <?php else: ?>
                                <div class="no-cover">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h4>book Title: <?= htmlspecialchars($book['title']) ?></h4>
                            <p>Author: <?= htmlspecialchars($book['author']) ?></p>
                            <p>Borrow Date: <?= htmlspecialchars($book['borrowDate']) ?></p>
                            <p>Due Date: <?= htmlspecialchars($book['dueDate']) ?></p>
                           
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php if (empty($borrowedBooks)): ?>
                    <p class="empty-msg">You haven't borrowed any books yet.</p>
                <?php endif; ?>
            </div>

        <?php endif; ?>

         


    </div>

</div>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
</script>

</body>
</html>
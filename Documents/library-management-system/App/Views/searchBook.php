<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Book</title>
    <link rel="stylesheet" href="/style.css">

    <style>
        .form-container {
            max-width: 1100px;
            width: 90%;
            margin: 40px auto;
        }

        .form-container form {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .form-container form input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #cfe3d4;
            border-radius: 6px;
            outline: none;
        }

        .form-container form input[type="text"]:focus {
            border-color: #2e7d4f;
        }

        .form-container form button {
            padding: 10px 20px;
            background: #2e7d4f;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .form-container form button:hover {
            background: #256a41;
        }

        .book-result {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .book-result h3 {
            margin-top: 0;
            color: #1e5631;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 8px;
        }

        .book-result h4 {
            margin-top: 20px;
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .book-result table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .book-result th,
        .book-result td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #eef2f0;
            font-size: 0.92rem;
        }

        .book-result > table th {
            width: 180px;
            color: #64748b;
            font-weight: 600;
            background: none;
        }

        .book-result table tr:first-child th {
            background-color: #f8fafc;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            width: auto;
        }

        .book-result table tr:hover {
            background-color: #f8fafc;
        }

        .admin-details {
            margin-top: 16px;
            padding: 16px;
            background: #f7fbf8;
            border: 1px dashed #cfe3d4;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="form-container">

    <h2>Search Book</h2>

    <form action="/searchBook" method="GET">
        <input type="text" name="title" placeholder="Search by title"
               value="<?= htmlspecialchars($title) ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($title !== '' && empty($books)): ?>
        <p>No books found matching "<?= htmlspecialchars($title) ?>".</p>
    <?php endif; ?>

    <?php foreach ($books as $book): ?>
        <div class="book-result">

            <h3><?= htmlspecialchars($book['title']) ?></h3>

            

            <table>
                <tr><th>Author</th><td><?= htmlspecialchars($book['author']) ?></td></tr>
                <tr><th>ISBN</th><td><?= htmlspecialchars($book['ISBN']) ?></td></tr>
                <tr><th>Genre</th><td><?= htmlspecialchars($book['genre']) ?></td></tr>
                <tr><th>Publication Date</th><td><?= htmlspecialchars($book['publicationDate']) ?></td></tr>
                <tr><th>Description</th><td><?= htmlspecialchars($book['description']) ?></td></tr>
                <tr><th>Copies</th><td><?= htmlspecialchars($book['Copies']) ?></td></tr>
            </table>

            <?php if (($_SESSION['role'] ?? '') === 'Admin'): ?>

                <div class="admin-details">

                    <h4>Inventory</h4>
                    <?php if ($book['inventory']): ?>
                        <table>
                            <tr><th>Total Copies</th><td><?= htmlspecialchars($book['inventory']['totalCopies']) ?></td></tr>
                            <tr><th>Available Copies</th><td><?= htmlspecialchars($book['inventory']['availableCopies']) ?></td></tr>
                            <tr><th>Currently Borrowed By (userId)</th><td><?= htmlspecialchars($book['inventory']['borrowerId'] ?? '—') ?></td></tr>
                        </table>
                    <?php else: ?>
                        <p>No inventory record found for this book.</p>
                    <?php endif; ?>

                    <h4>Borrow History (this book)</h4>
                    <?php if (!empty($book['borrowRecords'])): ?>
                        <table>
                            <tr>
                                <th>Borrower</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Fine</th>
                            </tr>
                            <?php foreach ($book['borrowRecords'] as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars($record['borrowerName']) ?></td>
                                    <td><?= htmlspecialchars($record['borrowDate']) ?></td>
                                    <td><?= htmlspecialchars($record['dueDate']) ?></td>
                                    <td><?= $record['returnDate'] ? htmlspecialchars($record['returnDate']) : 'Not returned yet' ?></td>
                                    <td>₦<?= $record['fine'] !== null ? htmlspecialchars($record['fine']) : '0' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>This book has never been borrowed.</p>
                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
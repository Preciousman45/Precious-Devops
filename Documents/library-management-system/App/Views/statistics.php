<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Statistics</title>

    <link rel="stylesheet" href="style.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="main">

    <div class="top">
        <h1>Library Statistics</h1>
        <p>Overview of the library system.</p>
    </div>

    <!-- Statistics Cards -->

    <div class="stats-grid">

        <div class="stats-card">
            <h4>Total Books</h4>
            <h2><?php echo $totalBooks; ?></h2>
        </div>

        <div class="stats-card">
            <h4>Total Users</h4>
            <h2><?php echo $totalUsers; ?></h2>
        </div>

        <div class="stats-card">
            <h4>Borrowed Books</h4>
            <h2><?php echo $borrowedBooks; ?></h2>
        </div>

        <div class="stats-card">
            <h4>Returned Books</h4>
            <h2><?php echo $returnedBooks; ?></h2>
        </div>

        <div class="stats-card">
            <h4>Available Copies</h4>
            <h2><?php echo $availableCopies; ?></h2>
        </div>

        <div class="stats-card">
            <h4>Overdue Books</h4>
            <h2><?php echo $overdueBooks; ?></h2>
        </div>

    </div>

    <!-- Recent Borrowing -->

    <h2 class="section-title">Recent Borrowing Activity</h2>

    <table>

        <tr>
            <th>Book</th>
            <th>User</th>
            <th>Borrow Date</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>

        <?php foreach($recentBorrowings as $borrow): ?>

        <tr>

            <td><?php echo $borrow['title']; ?></td>

            <td><?php echo $borrow['name']; ?></td>

            <td><?php echo $borrow['borrowDate']; ?></td>

            <td><?php echo $borrow['dueDate']; ?></td>

            <td><?php echo $borrow['status']; ?></td>

        </tr>

        <?php endforeach; ?>

    </table>

    <!-- Inventory Summary -->

    <h2 class="section-title">Inventory Summary</h2>

    <table>

        <tr>

            <th>Book</th>

            <th>Total Copies</th>

            <th>Available Copies</th>

        </tr>

        <?php foreach($inventory as $item): ?>

        <tr>

            <td><?php echo $item['title']; ?></td>

            <td><?php echo $item['totalCopies']; ?></td>

            <td><?php echo $item['availableCopies']; ?></td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
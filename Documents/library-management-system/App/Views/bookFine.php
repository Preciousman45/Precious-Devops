<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fine Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">

    <h2>Fine Details</h2>

    <p><strong>Book:</strong> <?php echo $book['title']; ?></p>

    <p><strong>Borrow Date:</strong> <?php echo $borrow['borrowDate']; ?></p>

    <p><strong>Due Date:</strong> <?php echo $borrow['dueDate']; ?></p>

    <p><strong>Return Date:</strong> <?php echo $borrow['returnDate']; ?></p>

    <h3>Fine:

        ₦<?php echo $borrow['fine']; ?>

    </h3>

    <a href="dashboard.php">

        <button>

            Back

        </button>

    </a>

</div>

</body>
</html>
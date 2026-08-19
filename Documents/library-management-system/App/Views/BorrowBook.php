<?php



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow Book</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="form-container">

    <h2>Borrow Book</h2>

    <form action="/borrowBook" method="POST">

        <div class="input-group">

            <label>Select Book</label>

            <select name="bookId" required>

                <option value="">Choose a Book</option>

                <?php foreach($availableBooks as $book): ?>

                    <option value="<?php echo $book['id']; ?>">

                        <?php echo $book['title']; ?>
                        <?php echo $book['availableCopies']; ?> Available

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <button type="submit">

            Borrow Book

        </button>

    </form>

</div>

</body>
</html>


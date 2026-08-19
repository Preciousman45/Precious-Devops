<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">

    <h2>Delete Book</h2>

    <form action="../Controllers/DeleteBookController.php" method="POST">


        <div class="input-group">

            <label>Book Title</label>

            <input
                type="text" name="title"
                value=""
                >

        </div>

        <div class="input-group">

            <label>Author</label>

            <input
                type="text" name="author"
                value="">

        </div>

        <div class="input-group">

            <label>ISBN</label>

            <input
                type="text" name="ISBN"
                value=" ">

        </div>

        <p style="color:red;font-weight:bold;text-align:center;">

            Are you sure you want to delete this book?

        </p>

       <button class="danger-btn" type="submit">
    Delete Book
</button>
    </form>

</div>

</body>
</html>
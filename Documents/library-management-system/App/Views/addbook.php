<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .form-container {
            max-width: 640px;
            margin: 40px auto;
            background: #ffffff;
            padding: 36px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .form-container h2 {
            margin-top: 0;
            margin-bottom: 24px;
            color: #1e5631;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 12px;
        }

        .add-book-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 20px;
        }

        .add-book-form .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .add-book-form .field.full-width {
            grid-column: 1 / -1;
        }

        .add-book-form label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
        }

        .add-book-form input[type="text"],
        .add-book-form input[type="date"],
        .add-book-form input[type="number"],
        .add-book-form textarea {
            padding: 10px 12px;
            font-size: 0.95rem;
            border: 1px solid #cfe3d4;
            border-radius: 8px;
            outline: none;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.15s;
        }

        .add-book-form input:focus,
        .add-book-form textarea:focus {
            border-color: #2e7d4f;
        }

        .add-book-form textarea {
            resize: vertical;
            min-height: 90px;
        }

        .add-book-form .file-field {
            border: 1px dashed #cfe3d4;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            background: #f7fbf8;
        }

        .add-book-form .file-field input[type="file"] {
            width: 100%;
            font-size: 0.85rem;
        }

        .add-book-form button {
            grid-column: 1 / -1;
            margin-top: 8px;
            padding: 12px;
            background: #2e7d4f;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }

        .add-book-form button:hover {
            background: #256a41;
        }

        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
        }

        .alert-error {
            background: #fdecea;
            color: #a33;
        }

        .alert-success {
            background: #e8f5e9;
            color: #256a41;
        }

        @media (max-width: 560px) {
            .add-book-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="form-container">

    <h2>Add New Book</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form class="add-book-form" action="/addbook" method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <label>Book Cover</label>
            <input type="file" name="bookImage" accept="image/*">
         </div>
        <div class="field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="e.g. Half of a Yellow Sun" required>
        </div>

        <div class="field">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" placeholder="e.g. Chimamanda Ngozi Adichie" required>
        </div>

        <div class="field">
            <label for="ISBN">ISBN</label>
            <input type="text" id="ISBN" name="ISBN" placeholder="e.g. 9781400095209" required>
        </div>

        <div class="field">
            <label for="publicationDate">Publication Date</label>
            <input type="date" id="publicationDate" name="publicationDate" required>
        </div>

        <div class="field">
            <label for="genre">Genre</label>
            <input type="text" id="genre" name="genre" placeholder="e.g. Historical Fiction" required>
        </div>

        <div class="field">
            <label for="copies">Copies</label>
            <input type="number" id="copies" name="copies" placeholder="e.g. 10" min="1" required>
        </div>

        <div class="field full-width">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Short summary of the book" required></textarea>
        </div>

        

        <button type="submit">Add Book</button>

    </form>

</div>

</body>
</html>
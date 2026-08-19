<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book</title>

    <link rel="stylesheet" href="style.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="form-container">

<h2>Update Book</h2>

<form action="/UpdateBook"
      method="POST" enctype="multipart/form-data">


<div class="input-group">
<label>Book ID</label>

<input
type="text"
name="id"
value=" "
required>

</div>




<div class="input-group">

<label>Book Title</label>

<input
type="text"
name="title"
value=" "
required>

</div>

<div class="input-group">

<label>Author</label>

<input
type="text"
name="author"
value=" "
required>

</div>

<div class="input-group">

<label>ISBN</label>

<input
type="text"
name="ISBN"
value=" "
required>

</div>

<div class="input-group">

<label>Publication Date</label>

<input
type="date"
name="publicationDate"
value="">

</div>

<div class="input-group">

<label>Category</label>

<input
type="text"
name="genre"
value="">

</div>

<div class="input-group">

<label>Copies</label>

<input
type="number"
name="copies"
value=" ">

</div>


<div class="input-group">

<label>Description</label>

<input
type="text"
name="description"
value="">

</div>


<div class="input-group">

<label>Replace Book Image</label>

<input
type="file"
name="bookImage">

</div>

<button type="submit">

Update Book

</button>

</form>

</div>

</body>
</html>
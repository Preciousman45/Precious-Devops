<table>

<tr>

<th>Image</th>

<th>Title</th>

<th>Author</th>

<th>Category</th>

<th>Action</th>

</tr>

<?php foreach($books as $book): ?>

<tr>

<td>

<img
src="../uploads/<?php echo $book['image'];?>"
width="60">

</td>

<td>

<?php echo $book['title']; ?>

</td>

<td>

<?php echo $book['author']; ?>

</td>

<td>

<?php echo $book['genre']; ?>

</td>

<td>

<a href="bookDetails.php?id=<?php echo $book['id']; ?>">

View

</a>

</td>

</tr>

<?php endforeach; ?>

</table>
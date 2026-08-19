<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Inventory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">

    <h2>Update Inventory</h2>

    <form action="/inventoryUpdate" method="POST">

        
         <div class="input-group">

            <label> Book ID</label>

            <input
                type="number"
                name="bookId"
                required>

        </div>

        <div class="input-group">

            <label>Total Copies</label>

            <input
                type="number"
                name="totalCopies"
                value=""
                required>

        </div>

        <div class="input-group">

            <label>Available Copies</label>

            <input
                type="number"
                name="availableCopies"
                value=""
                required>

        </div>

        <button type="submit">

            Update Inventory

        </button>

    </form>

</div>

</body>
</html>
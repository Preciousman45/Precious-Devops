<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Inventory</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="inventory.css">
</head>
<body>

<div class="form-container">
    <h2>Library Inventory</h2>

    <form method="GET" action="/inventoryTracking" class="search-form">
        <input type="text" name="search_id" placeholder="Enter Book ID..."
               value="<?= htmlspecialchars($searchId) ?>" required>
        <button type="submit">Search</button>
        <a href="/inventoryTracking"><button type="button" class="btn-clear">Clear</button></a>
    </form>

    <?php if ($searchId !== ''): ?>
        <?php if ($inventory): ?>
            <table>
                <tr>
                    <th>Book Title</th>
                    <th>Book ID</th>
                    <th>Total Copies</th>
                    <th>Available Copies</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($inventory['title'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($inventory['id'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($inventory['totalCopies'] ?? '0') ?></td>
                    <td><?= htmlspecialchars($inventory['availableCopies'] ?? '0') ?></td>
                    <td>
                        <a href="/inventoryUpdate?id=<?= urlencode($inventory['id'] ?? '') ?>" class="btn-edit">
                            Edit
                        </a>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <div class="status-msg error">
                No inventory record found for Book ID: <?= htmlspecialchars($searchId) ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="status-msg info">
            Please enter a Book ID above to look up inventory status.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
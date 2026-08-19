<?php 


?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Search Inventory</title> 
    <link rel="stylesheet" href="style.css"> 
</head> 
<body> 
<div class="form-container"> 
    <h2>Library Inventory</h2> 
    
    <!-- FIX: Leave action blank so the form reloads this view file, which triggers the handler at the top --> 
    <form method="GET" action="/" class="search-form"> 
        <div class="input-group"> 
            <label for="search_id">Inventory ID</label> 
            <input type="text" id="search_id" name="search_id" placeholder="Enter Inventory ID..." value="<?= htmlspecialchars($searchId) ?>" required> 
        </div> 
        <button type="submit">Search</button> 
        <a href="?" class="btn-clear">Clear</a> 
    </form> 

    <?php if (!empty($searchId)): ?> 
        <?php if ($item): ?> 
           <!-- Locate the table inside UI/searchInventory.php and update it to this: -->
                <table> 
                    <tr> 
                        <th>Book Title</th> 
                        <th>Inventory ID</th> 
                        <th>Total Copies</th> 
                        <th>Available Copies</th> 
                        <th>Borrower ID</th> <!-- Added Column Header -->
                        <th>Action</th> 
                    </tr> 
                    <tr> 
                        <td><?= htmlspecialchars($item['title'] ?? '-') ?></td> 
                        <td><?= htmlspecialchars($item['id'] ?? '-') ?></td> 
                        <td><?= htmlspecialchars($item['totalCopies'] ?? '0') ?></td> 
                        <td><?= htmlspecialchars($item['availableCopies'] ?? '0') ?></td> 
                        <td><?= htmlspecialchars($item['borrowerId'] ?? 'Not Borrowed') ?></td> <!-- Added Data Cell -->
                        <td> 
                            <a href="updateInventory.php?id=<?= urlencode($item['id'] ?? '') ?>" class="btn-edit"> 
                                Edit 
                            </a> 
                        </td> 
                    </tr> 
                </table> 
                
        <?php else: ?> 
            <div class="status-msg error"> 
                No inventory record found for Inventory ID: <?= htmlspecialchars($searchId) ?> 
            </div> 
        <?php endif; ?> 
    <?php else: ?> 
        <div class="status-msg info"> 
            Please enter an Inventory ID above to look up inventory status. 
        </div> 
    <?php endif; ?> 
</div> 
</body> 
</html>

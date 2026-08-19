<?php

$userId = $_SESSION['user_id'] ?? null;


use App\Models\UserManagement;
use App\Models\Borrowing;
$users = new UserManagement();
$details = $users->getUser($userId);

$borrowing = new Borrowing();
$borrowedBooks = $borrowing->getBorrowHistory($userId);

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

        <div class="dashboard">

            <!-- Sidebar -->
            

            <!-- Main content -->
            <div class="main">
            
                

                <div class="top">
                    <h1>My Profile</h1>
            </div>
            <div class="form-container">
            
                    <h2>Update Profile</h2>

                    <!-- User image section -->
                

                    <form action="/updateProfile" method="POST" enctype="multipart/form-data">
                        
                        <div class="input-group">
                            <label for="profile_image">Change Profile Image</label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/webp">
                        </div>

                        <div class="input-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name"
                                value="<?= htmlspecialchars($details['Name']) ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($details['Email']) ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password"
                                placeholder="Leave blank to keep current password">
                        </div>

                        <div class="input-group">
                            <label>Role</label>
                            <input type="text" value="<?= htmlspecialchars($details['Role']) ?>" disabled>
                        </div>

                        <button type="submit" name="update_profile">Update Profile</button>
                    
                    </form>
                
            </div>

                


            
        </div>

</body>
</html>
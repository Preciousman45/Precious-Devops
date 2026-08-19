<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Library Management System</title>

    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="login-container">

    <div class="login-box">

        <h1>Create Account</h1>

        <p>Register to access the library</p>

        <form action="/signUp" method="POST" enctype="multipart/form-data">

            <div class="input-group">
                <label>Full Name</label>
                <input type="text"  name="name" placeholder="Enter your full name" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label>Role</label>
                <input type="text" name="role" placeholder="Are you an Admin or a Normal User" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
            </div>

            <div class="input-group">
                <label>Profile Picture</label>
                <input type="file" name="profile_image" accept="image/*">

            <button type="submit">
                Sign Up
            </button>

        </form>

        <div class="bottom-text">

            Already have an account?

            <a href="/login">
                Login
            </a>

        </div>

    </div>

</div>

</body>
</html>
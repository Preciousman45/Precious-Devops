<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>

    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="login-container">
    
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']) ?>
        <?php endif ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']) ?>
        <?php endif ?>

    <div class="login-box">

        <h1>Library Management System</h1>

        <p>Login to continue</p>

        <form action="/login" method="POST" >

            <div class="input-group">

                <label>Users Name</label>
                <input type="name" name="username" placeholder="Enter username" required >

            </div>

            <div class="input-group">

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>

            </div>

            

            <button type="submit"> Login </button>

        </form>


        <div class="bottom-text">

            Don't have an account?

            <a href="/signUp"> Create one</a>

        </div>

    </div>

</div>

</body>
</html>
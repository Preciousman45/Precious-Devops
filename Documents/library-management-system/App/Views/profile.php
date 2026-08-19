<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

    <link rel="stylesheet" href="/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        .profile-page-wrap{
            display:flex;
            justify-content:center;
            padding-top:40px;
        }

        .profile-card{
            width:420px;
            max-width:100%;
            background:#fff;
            padding:45px 35px;
            border-radius:16px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            text-align:center;
        }

        .profile-card .profile-avatar img{
            width:130px;
            height:130px;
            object-fit:cover;
            border-radius:50%;
            border:4px solid #2e7d32;
            box-shadow:0 5px 15px rgba(0,0,0,.1);
            margin-bottom:20px;
        }

        .profile-card .user-avatar-large{
            width:130px;
            height:130px;
            font-size:48px;
            border:4px solid #2e7d32;
            margin:0 auto 20px;
        }

        .profile-card h1{
            color:#1b5e20;
            font-size:24px;
            margin-bottom:10px;
        }

        .role-badge{
            display:inline-block;
            padding:5px 16px;
            background:#e8f5e9;
            color:#2e7d32;
            border-radius:20px;
            font-size:13px;
            font-weight:600;
            margin-bottom:20px;
        }

        .profile-details{
            text-align:left;
            border-top:1px solid #eee;
            padding-top:20px;
            margin-bottom:25px;
        }

        .profile-details .detail-row{
            display:flex;
            justify-content:space-between;
            padding:10px 0;
            border-bottom:1px solid #f2f2f2;
        }

        .profile-details .detail-row:last-child{
            border-bottom:none;
        }

        .profile-details .detail-label{
            color:#888;
            font-size:14px;
        }

        .profile-details .detail-value{
            color:#1b1b1b;
            font-weight:600;
            font-size:14px;
        }

        .btn-edit-profile{
            width:100%;
            padding:13px;
            background:#2e7d32;
            color:#fff;
            border:none;
            border-radius:8px;
            font-weight:600;
            font-size:15px;
            cursor:pointer;
            transition:.3s;
        }

        .btn-edit-profile:hover{
            background:#1b5e20;
        }
    </style>
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Library</h2>
        <a href="/dashboard">Dashboard</a>
        <a href="/updateProfile">Update Profile</a>
        <a href="/logout">Logout</a>
    </div>

    <div class="main">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="profile-page-wrap">
            <div class="profile-card">

                <div class="profile-avatar">
                    <?php if (!empty($user['image'])): ?>
                        <img src="<?= htmlspecialchars($user['image']) ?>" alt="Profile photo">
                    <?php else: ?>
                        <div class="user-avatar-large"><?= strtoupper(substr($user['Name'], 0, 1)) ?></div>
                    <?php endif; ?>
                </div>

                <h1><?= htmlspecialchars($user['Name']) ?></h1>
                <span class="role-badge"><?= htmlspecialchars($user['Role']) ?></span>

                <div class="profile-details">
                    <div class="detail-row">
                        <span class="detail-label">Full Name</span>
                        <span class="detail-value"><?= htmlspecialchars($user['Name']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value"><?= htmlspecialchars($user['Email']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Role</span>
                        <span class="detail-value"><?= htmlspecialchars($user['Role']) ?></span>
                    </div>
                </div>

                <a href="/updateProfile">
                    <button type="button" class="btn-edit-profile">Edit Profile</button>
                </a>

            </div>
        </div>

    </div>

</div>

</body>
</html>
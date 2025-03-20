<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test1</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #222;
            color: white;
            display: flex;
            align-items: center;
            padding: 10px 20px;
        }
        .logo {
            font-size: 18px;
            font-weight: bold;
            margin-right: auto;
        }
        .nav {
            display: flex;
            gap: 20px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">Kiểm Tra Giữa Kì</div>
    
<nav>
    <a href="students.php">Trang Chủ</a>
    <a href="hocphan.php">Học Phần</a>

    <?php if (isset($_SESSION["MaSV"])): ?>
        <a href="dangkihocphan.php">Đăng Ký</a>
        <a href="logout.php">Đăng Xuất</a>
    <?php else: ?>
        <a href="register.php">Đăng Ký</a>
        <a href="login.php">Đăng Nhập</a>
    <?php endif; ?>
</nav>
</header>

</body>
</html>

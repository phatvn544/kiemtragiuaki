<?php
require_once __DIR__ . "/../config/database.php";
session_start();

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = $_POST["MaSV"];

    // Kiểm tra MSSV có tồn tại không
    $query = "SELECT MaSV FROM sinhvien WHERE MaSV = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$MaSV]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION["MaSV"] = $MaSV;
        $_SESSION["message"] = "Đăng nhập thành công!";
        header("Location: students.php");
        exit();
    } else {
        echo "<script>alert('MSSV không tồn tại!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 350px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Đăng Nhập</h2>
    <form method="post">
        <input type="text" name="MaSV" placeholder="Mã Sinh Viên" required>
        <button type="submit">Đăng Nhập</button>
    </form>
</div>

</body>
</html>

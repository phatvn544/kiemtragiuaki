<?php
require_once __DIR__ . "/../config/database.php";

$database = new Database();
$conn = $database->getConnection();

// Kiểm tra MaSV có tồn tại không
if (!isset($_GET['MaSV'])) {
    die("Thiếu mã sinh viên!");
}
$MaSV = $_GET['MaSV'];

// Lấy dữ liệu sinh viên từ DB
$query = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Sinh viên không tồn tại!");
}

// Xóa dữ liệu khi xác nhận
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($student['Hinh']) && file_exists("../" . $student['Hinh'])) {
        unlink("../" . $student['Hinh']);
    }

    $deleteQuery = "DELETE FROM SinhVien WHERE MaSV = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute([$MaSV]);
    
    header("Location: students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Thông Tin</title>
    <style>
        .container {
            width: 60%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        .info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .actions a, .actions button {
            margin: 5px;
            padding: 8px 12px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .delete-btn {
            background-color: red;
            color: white;
        }
        .cancel-btn {
            background-color: gray;
            color: white;
        }
    </style>
</head>
<body>

<?php include __DIR__ . "/../shares/header.php"; ?>

<div class="container">
    <h2>XÓA THÔNG TIN</h2>
    <p>Are you sure you want to delete this?</p>
    <div class="info">
        <div>
            <p><strong>Họ Tên:</strong> <?= htmlspecialchars($student['HoTen']) ?></p>
            <p><strong>Giới Tính:</strong> <?= htmlspecialchars($student['GioiTinh']) ?></p>
            <p><strong>Ngày Sinh:</strong> <?= htmlspecialchars($student['NgaySinh']) ?></p>
            <p><strong>Mã Ngành:</strong> <?= htmlspecialchars($student['MaNganh']) ?></p>
        </div>
        <img src="<?= htmlspecialchars('/' . ltrim($student['Hinh'], '/')) ?>" alt="Ảnh Sinh Viên">
    </div>
    
    <div class="actions">
        <form method="post">
            <button type="submit" class="delete-btn">Delete</button>
        </form>
        <a href="students.php" class="cancel-btn">Back to List</a>
    </div>
</div>

</body>
</html>
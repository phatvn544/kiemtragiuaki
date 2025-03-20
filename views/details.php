<?php
require_once __DIR__ . "/../config/database.php";

$database = new Database();
$conn = $database->getConnection();

if (!isset($_GET['MaSV'])) {
    die("Thiếu mã sinh viên!");
}
$MaSV = $_GET['MaSV'];

$query = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Sinh viên không tồn tại!");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chi tiết</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { max-width: 500px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; }
        .info { text-align: left; }
        img { width: 150px; height: 150px; object-fit: cover; border-radius: 10px; margin: 10px 0; }
        .actions a { text-decoration: none; color: #007bff; margin: 10px; }
        .actions a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <h2>Thông tin chi tiết</h2>
    <div class="info">
        <p><strong>Họ Tên:</strong> <?= htmlspecialchars($student['HoTen']) ?></p>
        <p><strong>Giới Tính:</strong> <?= htmlspecialchars($student['GioiTinh']) ?></p>
        <p><strong>Ngày Sinh:</strong> <?= htmlspecialchars($student['NgaySinh']) ?></p>
        <p><strong>Mã Ngành:</strong> <?= htmlspecialchars($student['MaNganh']) ?></p>
        <img src="<?= htmlspecialchars('/' . ltrim($student['Hinh'], '/')) ?>" alt="Ảnh Sinh Viên">
    </div>
    <div class="actions">
        <a href="edit.php?MaSV=<?= $student['MaSV'] ?>">✏ Edit</a> |
        <a href="students.php">🔙 Back to List</a>
    </div>
</div>
</body>
</html>

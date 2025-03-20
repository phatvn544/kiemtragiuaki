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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];

    if ($_FILES['Hinh']['name']) {
        $Hinh = 'uploads/' . basename($_FILES['Hinh']['name']);
        move_uploaded_file($_FILES['Hinh']['tmp_name'], "../" . $Hinh);
    } else {
        $Hinh = $student['Hinh'];
    }

    $updateQuery = "UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->execute([$HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $MaSV]);

    header("Location: students.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Sinh Viên</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { max-width: 500px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ddd; }
        button { background: #007bff; color: white; padding: 10px; border: none; margin-top: 15px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        img { width: 100px; height: 100px; object-fit: cover; margin-top: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Chỉnh sửa Sinh Viên</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Họ Tên:</label>
        <input type="text" name="HoTen" value="<?= $student['HoTen'] ?>" required>
        
        <label>Giới Tính:</label>
        <select name="GioiTinh">
            <option value="Nam" <?= $student['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
            <option value="Nữ" <?= $student['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
        </select>
        
        <label>Ngày Sinh:</label>
        <input type="date" name="NgaySinh" value="<?= $student['NgaySinh'] ?>" required>
        
        <label>Hình:</label>
        <input type="file" name="Hinh">
        <img src="../<?= $student['Hinh'] ?>" alt="Ảnh Sinh Viên">
        
        <label>Mã Ngành:</label>
        <input type="text" name="MaNganh" value="<?= $student['MaNganh'] ?>" required>
        
        <button type="submit">Lưu</button>
    </form>
</div>
</body>
</html>

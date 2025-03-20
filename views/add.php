<?php
require_once __DIR__ . "/../config/database.php";

// Kết nối database
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maSV = $_POST["MaSV"];
    $hoTen = $_POST["HoTen"];
    $gioiTinh = $_POST["GioiTinh"];
    $ngaySinh = $_POST["NgaySinh"];
    $maNganh = $_POST["MaNganh"];

    // Xử lý upload hình ảnh
    $hinh = "";
    if (!empty($_FILES["Hinh"]["name"])) {
        $targetDir = "uploads/"; // Thư mục lưu ảnh
        $targetFile = $targetDir . basename($_FILES["Hinh"]["name"]);
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFile);
        $hinh = $targetFile;
    }

    // Chèn dữ liệu vào database
    $query = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
              VALUES (:MaSV, :HoTen, :GioiTinh, :NgaySinh, :Hinh, :MaNganh)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":MaSV", $maSV);
    $stmt->bindParam(":HoTen", $hoTen);
    $stmt->bindParam(":GioiTinh", $gioiTinh);
    $stmt->bindParam(":NgaySinh", $ngaySinh);
    $stmt->bindParam(":Hinh", $hinh);
    $stmt->bindParam(":MaNganh", $maNganh);

    if ($stmt->execute()) {
        header("Location: students.php"); // Quay lại trang danh sách sinh viên
        exit();
    } else {
        echo "Lỗi khi thêm sinh viên!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
    <style>
        .container {
            width: 50%;
            margin: 20px auto;
        }
        input, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Thêm Sinh Viên</h2>
    <form action="add.php" method="post" enctype="multipart/form-data">
        <label>Mã SV:</label>
        <input type="text" name="MaSV" required>

        <label>Họ Tên:</label>
        <input type="text" name="HoTen" required>

        <label>Giới Tính:</label>
        <input type="text" name="GioiTinh" required>

        <label>Ngày Sinh:</label>
        <input type="date" name="NgaySinh" required>

        <label>Hình:</label>
        <input type="file" name="Hinh">

        <label>Ngành:</label>
        <input type="text" name="MaNganh" required>

        <button type="submit">Thêm</button>
    </form>

    <a href="students.php">Quay lại danh sách</a>
</div>

</body>
</html>

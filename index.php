<?php
// Gọi file kết nối database
require_once "config/database.php";

// Khởi tạo kết nối
$database = new Database();
$conn = $database->getConnection();

// Kiểm tra kết nối
if ($conn) {
    // Chuyển hướng đến students.php nếu kết nối thành công
    header("Location: views/students.php");
    exit();
} else {
    echo "Lỗi kết nối đến database!";
}
?>

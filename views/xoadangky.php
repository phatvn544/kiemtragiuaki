<?php
require_once __DIR__ . "/../config/database.php";
session_start();

if (!isset($_SESSION["MaSV"])) {
    die("Bạn chưa đăng nhập!");
}

$database = new Database();
$conn = $database->getConnection();
$MaSV = $_SESSION["MaSV"];

// Xóa tất cả học phần đăng ký của sinh viên
$query = "
    DELETE FROM ChiTietDangKy 
    WHERE MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = ?)";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);

header("Location: dangkihocphan.php");
exit();
?>

<?php
require_once __DIR__ . "/../config/database.php";
session_start();

if (!isset($_SESSION["MaSV"]) || !isset($_GET["MaHP"])) {
    die("Thiếu thông tin xóa!");
}

$database = new Database();
$conn = $database->getConnection();
$MaSV = $_SESSION["MaSV"];
$MaHP = $_GET["MaHP"];

// Xóa học phần khỏi bảng ChiTietDangKy
$query = "
    DELETE FROM ChiTietDangKy
    WHERE MaHP = ? AND MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = ?)";
$stmt = $conn->prepare($query);
$stmt->execute([$MaHP, $MaSV]);

header("Location: dangkihocphan.php");
exit();
?>

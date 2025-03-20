<?php
require_once __DIR__ . "/../config/database.php";
session_start();

// Chặn truy cập nếu chưa đăng nhập
if (!isset($_SESSION["MaSV"])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION["message"])) {
    echo "<script>alert('" . $_SESSION["message"] . "');</script>";
    unset($_SESSION["message"]); // Xóa sau khi hiển thị
}

// Kết nối database
$database = new Database();
$conn = $database->getConnection();

// Truy vấn danh sách sinh viên
$query = "SELECT * FROM SinhVien";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sinh Viên</title>
    <style>
        .container {
            width: 80%;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<?php include __DIR__ . "/../shares/header.php"; ?>

<div class="container">
    <h2>Danh Sách Sinh Viên</h2>
    <a href="add.php" >
        Thêm Sinh Viên
    </a>
    <table>
        <tr>
            <th>Mã SV</th>
            <th>Họ Tên</th>
            <th>Giới Tính</th>
            <th>Ngày Sinh</th>
            <th>Hình</th>
            <th>Ngành</th>
            <th>Hành động</th> <!-- Thêm cột mới -->
        </tr>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['MaSV']) ?></td>
            <td><?= htmlspecialchars($student['HoTen']) ?></td>
            <td><?= htmlspecialchars($student['GioiTinh']) ?></td>
            <td><?= htmlspecialchars($student['NgaySinh']) ?></td>
            <td>
                <img src="<?= htmlspecialchars('/' . ltrim($student['Hinh'], '/')) ?>" alt="Ảnh SV" width="100">
            </td>
            <td><?= htmlspecialchars($student['MaNganh']) ?></td>
            <td>
                <a href="edit.php?MaSV=<?= $student['MaSV'] ?>">Edit</a> |
                <a href="details.php?MaSV=<?= $student['MaSV'] ?>">Details</a> |
                <a href="delete.php?MaSV=<?= $student['MaSV'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>


</body>
</html>

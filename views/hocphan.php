<?php
require_once __DIR__ . "/../config/database.php";
session_start();

if (!isset($_SESSION["MaSV"])) {
    die("Bạn chưa đăng nhập!");
}

$database = new Database();
$conn = $database->getConnection();
$MaSV = $_SESSION["MaSV"];

// Xử lý đăng ký học phần
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["MaHP"])) {
    $MaHP = $_POST["MaHP"];

    // Kiểm tra xem sinh viên đã có đăng ký nào chưa
    $query = "SELECT MaDK FROM DangKy WHERE MaSV = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$MaSV]);
    $MaDK = $stmt->fetchColumn();

    // Nếu chưa có, tạo mới đăng ký
    if (!$MaDK) {
        $query = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$MaSV]);

        // Lấy MaDK mới tạo
        $MaDK = $conn->lastInsertId();
    }

    // Kiểm tra xem đã đăng ký học phần này chưa
    $query = "SELECT COUNT(*) FROM ChiTietDangKy WHERE MaDK = ? AND MaHP = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$MaDK, $MaHP]);
    $isRegistered = $stmt->fetchColumn();

    if (!$isRegistered) {
        // Thêm học phần vào đăng ký
        $query = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$MaDK, $MaHP]);

        // Chuyển hướng sang trang danh sách đăng ký
        header("Location: dangkihocphan.php");
        exit();
    }
}

// Lấy danh sách học phần
$query = "SELECT * FROM HocPhan";
$stmt = $conn->prepare($query);
$stmt->execute();
$hocphans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Học Phần</title>
    <style>
        .container {
            width: 70%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn-register {
            background-color: green;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>

<?php include __DIR__ . "/../shares/header.php"; ?>

<div class="container">
    <h2>DANH SÁCH HỌC PHẦN</h2>
    <table>
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Đăng Ký</th>
        </tr>
        <?php foreach ($hocphans as $hp): ?>
        <tr>
            <td><?= htmlspecialchars($hp['MaHP']) ?></td>
            <td><?= htmlspecialchars($hp['TenHP']) ?></td>
            <td><?= htmlspecialchars($hp['SoTinChi']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="MaHP" value="<?= htmlspecialchars($hp['MaHP']) ?>">
                    <button type="submit" class="btn-register">Đăng Ký</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>

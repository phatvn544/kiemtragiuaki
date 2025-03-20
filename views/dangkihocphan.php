<?php
require_once __DIR__ . "/../config/database.php";
session_start();

if (!isset($_SESSION["MaSV"])) {
    die("Bạn chưa đăng nhập!");
}

$database = new Database();
$conn = $database->getConnection();
$MaSV = $_SESSION["MaSV"];

// Lấy thông tin đăng ký của sinh viên
$query = "
    SELECT CTDK.MaHP, HP.TenHP, HP.SoTinChi 
    FROM ChiTietDangKy CTDK
    JOIN DangKy DK ON CTDK.MaDK = DK.MaDK
    JOIN HocPhan HP ON CTDK.MaHP = HP.MaHP
    WHERE DK.MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$MaSV]);
$dangkyhocphans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính tổng số học phần và tổng số tín chỉ
$tongHocPhan = count($dangkyhocphans);
$tongTinChi = array_sum(array_column($dangkyhocphans, 'SoTinChi'));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Học Phần</title>
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
        .btn-delete {
            background-color: red;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-clear {
            background-color: orange;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            border-radius: 5px;
        }
        .summary {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include __DIR__ . "/../shares/header.php"; ?>

<div class="container">
    <h2>Đăng Ký Học Phần</h2>
    <?php if (empty($dangkyhocphans)): ?>
        <p>Bạn chưa đăng ký học phần nào.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Mã Học Phần</th>
                <th>Tên Học Phần</th>
                <th>Số Tín Chỉ</th>
                <th>Xóa</th>
            </tr>
            <?php foreach ($dangkyhocphans as $hp): ?>
            <tr>
                <td><?= htmlspecialchars($hp['MaHP']) ?></td>
                <td><?= htmlspecialchars($hp['TenHP']) ?></td>
                <td><?= htmlspecialchars($hp['SoTinChi']) ?></td>
                <td>
                    <a href="xoahocphan.php?MaHP=<?= $hp['MaHP'] ?>" class="btn-delete">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Hiển thị tổng tín chỉ và tổng học phần -->
        <div class="summary">
            <p>Số học phần: <span style="color: red;"><?= $tongHocPhan ?></span></p>
            <p>Tổng số tín chỉ: <span style="color: red;"><?= $tongTinChi ?></span></p>
        </div>

        <a href="xoadangky.php" class="btn-clear">Xóa Đăng Ký</a>
    <?php endif; ?>
</div>

</body>
</html>

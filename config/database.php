<?php
class Database {
    private $host = "localhost";
    private $db_name = "test1";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Bật chế độ báo lỗi
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Lấy dữ liệu dạng mảng liên kết
                    PDO::ATTR_EMULATE_PREPARES => false // Ngăn SQL Injection
                ]
            );
        } catch (PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage()); // Ghi lỗi vào log
            die("Database connection failed."); // Hiển thị thông báo chung chung, tránh lộ thông tin DB
        }

        return $this->conn;
    }
}
?>

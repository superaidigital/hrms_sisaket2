<?php
// ==========================================
// ชื่อไฟล์: SystemMenu.php
// ที่อยู่ไฟล์: models/SystemMenu.php
// ==========================================

class SystemMenu {
    private $conn;
    private $table_name = "system_menus";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ดึงเมนูทั้งหมดเรียงตามลำดับ
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY sort_order ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // สลับสถานะ เปิด/ปิด
    public function toggleStatus($id) {
        // ดึงสถานะปัจจุบันมาก่อน
        $query = "SELECT is_active FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $new_status = ($row['is_active'] == '1') ? '0' : '1';
            $updateQuery = "UPDATE " . $this->table_name . " SET is_active = ? WHERE id = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(1, $new_status);
            $updateStmt->bindParam(2, $id);
            return $updateStmt->execute();
        }
        return false;
    }
}
?>
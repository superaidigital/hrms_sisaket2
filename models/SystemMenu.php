<?php
class SystemMenu {
    private $conn;
    private $table_name = "system_menus";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ดึงเมนูทั้งหมด เรียงตามลำดับ sort_order
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY sort_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ดึงเฉพาะเมนูที่เปิดใช้งาน (สำหรับแสดงที่ Sidebar)
    public function readActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = '1' ORDER BY sort_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // เพิ่มเมนูใหม่
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (menu_name, icon, action_name, is_active, sort_order) 
                  VALUES (:menu_name, :icon, :action_name, :is_active, :sort_order)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":menu_name", $data['menu_name']);
        $stmt->bindParam(":icon", $data['icon']);
        $stmt->bindParam(":action_name", $data['action_name']);
        $stmt->bindParam(":is_active", $data['is_active']);
        $stmt->bindParam(":sort_order", $data['sort_order']);
        
        return $stmt->execute();
    }

    // อัปเดตเมนู
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET menu_name = :menu_name, icon = :icon, action_name = :action_name, 
                      is_active = :is_active, sort_order = :sort_order 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":menu_name", $data['menu_name']);
        $stmt->bindParam(":icon", $data['icon']);
        $stmt->bindParam(":action_name", $data['action_name']);
        $stmt->bindParam(":is_active", $data['is_active']);
        $stmt->bindParam(":sort_order", $data['sort_order']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    // เปิด/ปิด สถานะเมนู
    public function toggleActive($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET is_active = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // ลบเมนู
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. ฟังก์ชันตรวจสอบการ Login (อันเดิม)
    public function login($username, $password) {
        $query = "SELECT id, username, password, full_name, role FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    // 2. ดึงรายชื่อผู้ใช้งานทั้งหมด
    public function readAll() {
        $query = "SELECT id, username, full_name, role, created_at FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 3. ดึงข้อมูลผู้ใช้งาน 1 คน (เพื่อนำไปแก้ไข)
    public function readOne($id) {
        $query = "SELECT id, username, full_name, role FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. เพิ่มผู้ใช้งานใหม่
    public function create($data) {
        // เข้ารหัสผ่านก่อนบันทึกลงฐานข้อมูล
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table_name . " (username, password, full_name, role) VALUES (:username, :password, :full_name, :role)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":role", $data['role']);

        try {
            if($stmt->execute()) return true;
        } catch(PDOException $e) {
            return false; // กรณี Username ซ้ำ
        }
        return false;
    }

    // 5. แก้ไขข้อมูลผู้ใช้งาน (อัปเดตรหัสผ่านเฉพาะกรณีที่กรอกมาใหม่)
    public function update($id, $data) {
        if(!empty($data['password'])) {
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = "UPDATE " . $this->table_name . " SET username = :username, password = :password, full_name = :full_name, role = :role WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":password", $hashed_password);
        } else {
            // ถ้าไม่เปลี่ยนรหัสผ่าน
            $query = "UPDATE " . $this->table_name . " SET username = :username, full_name = :full_name, role = :role WHERE id = :id";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":id", $id);

        try {
            if($stmt->execute()) return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    // 6. ลบผู้ใช้งาน
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()) return true;
        return false;
    }
}
?>
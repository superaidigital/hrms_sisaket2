<?php
require_once 'models/User.php';

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        
        // ตรวจสอบสิทธิ์ ต้องเป็น admin เท่านั้น
        if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['message'] = "คุณไม่มีสิทธิ์เข้าถึงหน้าจัดการผู้ใช้งาน!";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    public function index() {
        $userModel = new User($this->db);
        $stmt = $userModel->readAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/user/index.php';
    }

    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userModel = new User($this->db);
            $data = [
                'username' => trim($_POST['username']),
                'password' => $_POST['password'],
                'full_name' => trim($_POST['full_name']),
                'role' => $_POST['role']
            ];

            if($userModel->create($data)) {
                $_SESSION['message'] = "เพิ่มผู้ใช้งานใหม่เรียบร้อยแล้ว!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "ไม่สามารถเพิ่มผู้ใช้งานได้ (Username อาจซ้ำ)";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=users");
            exit();
        }
    }

    // --- เพิ่มฟังก์ชันแก้ไขข้อมูลผู้ใช้และเปลี่ยนรหัสผ่าน ---
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
            $userModel = new User($this->db);
            $id = $_POST['id'];
            
            $data = [
                'username' => trim($_POST['username']),
                'full_name' => trim($_POST['full_name']),
                'role' => $_POST['role'],
                'password' => !empty($_POST['password']) ? $_POST['password'] : null // ถ้ารหัสผ่านว่าง แสดงว่าไม่เปลี่ยนรหัส
            ];

            if($userModel->update($id, $data)) {
                $_SESSION['message'] = "อัปเดตข้อมูลบัญชีผู้ใช้งานสำเร็จ!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=users");
            exit();
        }
    }

    public function delete() {
        if(isset($_GET['id'])) {
            // ป้องกันไม่ให้ลบตัวเอง
            if($_GET['id'] == $_SESSION['user_id']) {
                $_SESSION['message'] = "คุณไม่สามารถลบบัญชีของตัวเองได้!";
                $_SESSION['message_type'] = "warning";
            } else {
                $userModel = new User($this->db);
                if($userModel->delete($_GET['id'])) {
                    $_SESSION['message'] = "ลบผู้ใช้งานเรียบร้อยแล้ว!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "เกิดข้อผิดพลาดในการลบผู้ใช้งาน";
                    $_SESSION['message_type'] = "danger";
                }
            }
            header("Location: index.php?action=users");
            exit();
        }
    }
}
?>
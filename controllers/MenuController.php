<?php
// ==========================================
// ชื่อไฟล์: MenuController.php
// ที่อยู่ไฟล์: controllers/MenuController.php
// ==========================================

require_once 'models/SystemMenu.php';

class MenuController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        
        // ตรวจสอบสิทธิ์ ต้องเป็น admin เท่านั้น
        if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['message'] = "คุณไม่มีสิทธิ์เข้าถึงหน้านี้!";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    // หน้าแสดงรายการเมนูทั้งหมด
    public function index() {
        $menuModel = new SystemMenu($this->db);
        $stmt = $menuModel->readAll();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/menu/index.php';
    }

    // เปิด/ปิด การแสดงผลเมนู
    public function toggle() {
        if(isset($_GET['id'])) {
            $menuModel = new SystemMenu($this->db);
            if($menuModel->toggleStatus($_GET['id'])) {
                $_SESSION['message'] = "อัปเดตสถานะการแสดงผลเมนูแล้ว!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการอัปเดตสถานะ";
                $_SESSION['message_type'] = "danger";
            }
        }
        header("Location: index.php?action=menus");
        exit();
    }
}
?>
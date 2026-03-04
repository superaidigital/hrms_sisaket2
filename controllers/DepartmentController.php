<?php
// ==========================================
// ชื่อไฟล์: DepartmentController.php
// ที่อยู่ไฟล์: controllers/DepartmentController.php
// ==========================================

require_once 'models/Department.php';

class DepartmentController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        
        // ตรวจสอบสิทธิ์ ต้องเป็น admin
        if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['message'] = "คุณไม่มีสิทธิ์เข้าถึงหน้าตั้งค่าระบบ!";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    public function index() {
        $deptModel = new Department($this->db);
        $stmt = $deptModel->readAll();
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/department/index.php';
    }

    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $deptModel = new Department($this->db);
            $data = [
                'name' => trim($_POST['name']),
                'type' => $_POST['type']
            ];

            if($deptModel->create($data)) {
                $_SESSION['message'] = "เพิ่มข้อมูลหน่วยงานเรียบร้อยแล้ว!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการเพิ่มข้อมูล";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=departments");
            exit();
        }
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
            $deptModel = new Department($this->db);
            $id = $_POST['id'];
            $data = [
                'name' => trim($_POST['name']),
                'type' => $_POST['type']
            ];

            if($deptModel->update($id, $data)) {
                $_SESSION['message'] = "อัปเดตข้อมูลหน่วยงานสำเร็จ!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการแก้ไขข้อมูล";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=departments");
            exit();
        }
    }

    public function delete() {
        if(isset($_GET['id'])) {
            $deptModel = new Department($this->db);
            if($deptModel->delete($_GET['id'])) {
                $_SESSION['message'] = "ลบข้อมูลหน่วยงานเรียบร้อยแล้ว!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการลบข้อมูล";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=departments");
            exit();
        }
    }
}
?>
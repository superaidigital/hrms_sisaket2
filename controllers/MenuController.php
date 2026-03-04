<?php
require_once 'models/SystemMenu.php';

class MenuController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $menuModel = new SystemMenu($this->db);
        $menus = $menuModel->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/menu/index.php';
    }

    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $menuModel = new SystemMenu($this->db);
            $data = [
                'menu_name' => trim($_POST['menu_name']),
                'icon' => trim($_POST['icon']),
                'action_name' => trim($_POST['action_name']),
                'sort_order' => (int)$_POST['sort_order'],
                'is_active' => isset($_POST['is_active']) ? '1' : '0'
            ];

            if ($menuModel->create($data)) {
                $_SESSION['message'] = "เพิ่มเมนูระบบสำเร็จ!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการเพิ่มเมนู!";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=menus");
            exit();
        }
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
            $menuModel = new SystemMenu($this->db);
            $id = $_POST['id'];
            $data = [
                'menu_name' => trim($_POST['menu_name']),
                'icon' => trim($_POST['icon']),
                'action_name' => trim($_POST['action_name']),
                'sort_order' => (int)$_POST['sort_order'],
                'is_active' => isset($_POST['is_active']) ? '1' : '0'
            ];

            if ($menuModel->update($id, $data)) {
                $_SESSION['message'] = "อัปเดตเมนูระบบสำเร็จ!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการอัปเดตเมนู!";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=menus");
            exit();
        }
    }

    public function toggleActive() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $menuModel = new SystemMenu($this->db);
            $id = $_GET['id'];
            $status = $_GET['status'] == '1' ? '1' : '0';

            if ($menuModel->toggleActive($id, $status)) {
                $_SESSION['message'] = "เปลี่ยนสถานะเมนูสำเร็จ!";
                $_SESSION['message_type পতিত'] = "success";
            }
            header("Location: index.php?action=menus");
            exit();
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $menuModel = new SystemMenu($this->db);
            if ($menuModel->delete($_GET['id'])) {
                $_SESSION['message'] = "ลบเมนูระบบสำเร็จ!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "เกิดข้อผิดพลาดในการลบเมนู!";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: index.php?action=menus");
            exit();
        }
    }
}
?>
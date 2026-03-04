<?php
require_once 'models/User.php';

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. แสดงหน้าฟอร์ม Login
    public function loginForm() {
        // ถ้าล็อกอินอยู่แล้ว ให้เด้งไปหน้า Dashboard เลย
        if(isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }
        // เรียกหน้า View Login
        require_once 'views/auth/login.php';
    }

    // 2. ตรวจสอบข้อมูลเมื่อกดปุ่มเข้าสู่ระบบ
    public function loginProcess() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userModel = new User($this->db);
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $user = $userModel->login($username, $password);

            if($user) {
                // ✅ ความปลอดภัย: สร้าง Session ID ใหม่เมื่อล็อกอินสำเร็จเพื่อป้องกัน Session Fixation
                session_regenerate_id(true);

                // ล็อกอินสำเร็จ เก็บข้อมูลลง Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                // สร้างข้อความต้อนรับ
                $_SESSION['message'] = "ยินดีต้อนรับคุณ " . $user['full_name'] . " เข้าสู่ระบบ";
                $_SESSION['message_type'] = "success";
                
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                // ล็อกอินไม่สำเร็จ
                $_SESSION['login_error'] = "ชื่อผู้ใช้งาน หรือ รหัสผ่านไม่ถูกต้อง!";
                header("Location: index.php?action=login");
                exit();
            }
        }
    }

    // 3. ออกจากระบบ (Logout)
    public function logout() {
        // ✅ ปรับปรุง: ล้างเฉพาะค่า Session ที่เกี่ยวกับการยืนยันตัวตน
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['full_name']);
        unset($_SESSION['role']);

        // ✅ ความปลอดภัย: สร้าง Session ID ใหม่ตอนออกจากระบบ
        session_regenerate_id(true);
        
        // สร้างข้อความแจ้งเตือนว่าออกจากระบบแล้ว
        $_SESSION['message'] = "คุณได้ออกจากระบบเรียบร้อยแล้ว";
        $_SESSION['message_type'] = "success";
        
        header("Location: index.php?action=login");
        exit();
    }
}
?>
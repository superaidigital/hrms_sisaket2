<?php
// 1. เปิดโชว์ Error (ช่วยป้องกันหน้าจอขาว จะได้รู้ว่าพังที่ไหน) **ลบออกได้เมื่อระบบเสร็จสมบูรณ์**
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. เริ่มต้น Session สำหรับเก็บข้อมูลผู้ใช้งานและข้อความแจ้งเตือนต่างๆ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. ดึงไฟล์ตั้งค่าฐานข้อมูล
require_once 'config/database.php';

// 4. สร้างออบเจกต์ฐานข้อมูล
$database = new Database();
$db = $database->getConnection();

// 5. รับค่า action จาก URL 
$action = isset($_GET['action']) ? $_GET['action'] : '';

// ==========================================
// ลำดับที่ 1: ตรวจสอบการล็อกอินก่อนเป็นอันดับแรก!
// ==========================================
// ถ้ายังไม่ได้ล็อกอิน และไม่ได้กำลังพยายามล็อกอิน ให้เด้งไปหน้า login ทันที
if ($action != 'login' && !isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

// ตั้งค่าหน้าเริ่มต้น หากล็อกอินแล้วแต่ไม่ได้ระบุ action
if ($action == '' && isset($_SESSION['user_id'])) {
    $action = 'dashboard';
}

// ==========================================
// ลำดับที่ 2: ตรวจสอบสถานะ Maintenance Mode (เฉพาะคนที่ล็อกอินแล้ว)
// ==========================================
if ($action != 'login' && $action != 'logout') {
    try {
        $stmtMode = $db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'maintenance_mode'");
        $stmtMode->execute();
        $modeResult = $stmtMode->fetch(PDO::FETCH_ASSOC);
        $maintenance_mode = $modeResult ? $modeResult['setting_value'] : 'off';

        // ถ้าเปิด Maintenance Mode และไม่ใช่ Admin ให้บล็อกการเข้าใช้งาน
        if ($maintenance_mode == 'on' && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
            die("<div style='text-align:center; margin-top:100px; font-family: sans-serif;'>
                    <h1 style='color:#e74c3c;'>ขออภัย ระบบกำลังปิดปรับปรุง</h1>
                    <p>เรากำลังทำการอัปเดตระบบ กรุณากลับมาใช้งานใหม่ในภายหลัง</p>
                    <a href='index.php?action=logout' style='padding:10px 20px; background:#3498db; color:#fff; text-decoration:none; border-radius:5px;'>ออกจากระบบ</a>
                 </div>");
        }
    } catch (PDOException $e) {
        // กรณีที่ตาราง system_settings ยังไม่ถูกสร้าง ให้ระบบข้ามไปก่อน (ป้องกันจอขาว)
        $maintenance_mode = 'off';
    }
}

// ==========================================
// ส่วนกำหนดเส้นทาง (Routing) ของระบบ
// ==========================================
switch ($action) {
    // ---- การยืนยันตัวตน (Authentication) ----
    case 'login':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($db);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $auth->loginProcess();
        } else {
            $auth->loginForm();
        }
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController($db);
        $auth->logout();
        break;

    // ---- หน้าหลัก (Dashboard) ----
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $dashboard = new DashboardController($db);
        $dashboard->index();
        break;

    // ---- จัดการบุคลากร (Employees) ----
    case 'employees':
        require_once 'controllers/EmployeeController.php';
        $emp = new EmployeeController($db);
        $emp->index();
        break;
        
    case 'create':
        require_once 'controllers/EmployeeController.php';
        $emp = new EmployeeController($db);
        $emp->create();
        break;

    case 'store':
        require_once 'controllers/EmployeeController.php';
        $emp = new EmployeeController($db);
        $emp->store();
        break;

    case 'show':
        require_once 'controllers/EmployeeController.php';
        $emp = new EmployeeController($db);
        $emp->show();
        break;

    case 'edit':
        require_once 'controllers/EmployeeController.php';
        $emp = new EmployeeController($db);
        $emp->edit();
        break;

    case 'update':
        require_once 'controllers/EmployeeController.php';
        $emp = new EmployeeController($db);
        $emp->update();
        break;

    case 'delete':
        require_once 'controllers/EmployeeController.php';
        $emp = new EmployeeController($db);
        $emp->delete();
        break;

    // ---- จัดการกรอบอัตรากำลัง (Manpower) ----
    case 'manpower':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->index();
        break;

    case 'manpower_detail':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->detail();
        break;

    case 'manpower_create':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->create();
        break;

    case 'manpower_store':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->store();
        break;

    case 'manpower_edit':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->edit();
        break;

    case 'manpower_update':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->update();
        break;

    case 'manpower_delete':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->delete();
        break;

    // 🌟 API สำหรับค้นหาบุคลากร
    case 'search_employee':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->searchEmployee();
        break;

    // 🌟 API สำหรับเพิ่มตำแหน่งด่วนจาก Popup
    case 'manpower_store_ajax':
        require_once 'controllers/ManpowerController.php';
        $manpower = new ManpowerController($db);
        $manpower->storeAjax();
        break;
        
    case 'manpower_summary':
        require_once 'controllers/ManpowerController.php';
        $controller = new ManpowerController($db); 
        $controller->summaryReport();
        break;

    // ---- ตั้งค่า: หน่วยงาน (Departments) ----
    case 'departments':
        require_once 'controllers/DepartmentController.php';
        $dept = new DepartmentController($db);
        $dept->index();
        break;

    case 'department_store':
        require_once 'controllers/DepartmentController.php';
        $dept = new DepartmentController($db);
        $dept->store();
        break;

    case 'department_update':
        require_once 'controllers/DepartmentController.php';
        $dept = new DepartmentController($db);
        $dept->update();
        break;

    case 'department_delete':
        require_once 'controllers/DepartmentController.php';
        $dept = new DepartmentController($db);
        $dept->delete();
        break;

    // ---- ตั้งค่า: ระดับตำแหน่ง (Position Levels) ----
    case 'position_levels':
        require_once 'controllers/PositionLevelController.php';
        $level = new PositionLevelController($db);
        $level->index();
        break;

    case 'position_level_store':
        require_once 'controllers/PositionLevelController.php';
        $level = new PositionLevelController($db);
        $level->store();
        break;

    case 'position_level_update':
        require_once 'controllers/PositionLevelController.php';
        $level = new PositionLevelController($db);
        $level->update();
        break;

    case 'position_level_delete':
        require_once 'controllers/PositionLevelController.php';
        $level = new PositionLevelController($db);
        $level->delete();
        break;

    // ---- จัดการผู้ใช้งาน (Users) ----
    case 'users':
        require_once 'controllers/UserController.php';
        $user = new UserController($db);
        $user->index();
        break;

    case 'user_store':
        require_once 'controllers/UserController.php';
        $user = new UserController($db);
        $user->store();
        break;

    case 'user_update':
        require_once 'controllers/UserController.php';
        $user = new UserController($db);
        $user->update();
        break;

    case 'user_delete':
        require_once 'controllers/UserController.php';
        $user = new UserController($db);
        $user->delete();
        break;
        
    case 'user_reset_pwd':
        require_once 'controllers/UserController.php';
        $user = new UserController($db);
        $user->resetPassword();
        break;

    case 'user_toggle':
        require_once 'controllers/UserController.php';
        $user = new UserController($db);
        $user->toggleActive();
        break;

    // ---- จัดการเมนู (Menus) ----
    case 'menus':
        require_once 'controllers/MenuController.php';
        $menu = new MenuController($db);
        $menu->index();
        break;

    case 'menu_toggle':
        require_once 'controllers/MenuController.php';
        $menu = new MenuController($db);
        $menu->toggleActive(); // แก้ไขเป็น toggleActive() แล้ว
        break;

    case 'menu_store':
        require_once 'controllers/MenuController.php';
        $menu = new MenuController($db);
        $menu->store();
        break;

    case 'menu_update':
        require_once 'controllers/MenuController.php';
        $menu = new MenuController($db);
        $menu->update();
        break;

    case 'menu_delete':
        require_once 'controllers/MenuController.php';
        $menu = new MenuController($db);
        $menu->delete();
        break;

    // ---- ตั้งค่าระบบหลัก (Settings) ----
    case 'settings':
        require_once 'controllers/SettingsController.php';
        $setting = new SettingsController($db);
        $setting->index();
        break;

    case 'setting_toggle_maintenance':
        require_once 'controllers/SettingsController.php';
        $setting = new SettingsController($db);
        $setting->toggleMaintenance();
        break;

    case 'setting_backup_db':
        require_once 'controllers/SettingsController.php';
        $setting = new SettingsController($db);
        $setting->backupDatabase();
        break;

    // ---- พิมพ์รายงาน (Reports) ----
    case 'print_kp7':
        require_once 'controllers/ReportController.php';
        $report = new ReportController($db);
        $report->printKP7();
        break;

    default:
        // หากไม่มี action ใดตรงกับข้างบนเลย
        require_once 'controllers/DashboardController.php';
        $dashboard = new DashboardController($db);
        $dashboard->index();
        break;
}
?>
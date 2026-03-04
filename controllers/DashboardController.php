<?php
// ==========================================
// ชื่อไฟล์: DashboardController.php
// ที่อยู่ไฟล์: controllers/DashboardController.php
// ==========================================

require_once 'models/Employee.php';
require_once 'models/Manpower.php';

class DashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // --- 1. ดึงข้อมูลบุคลากร ---
        $employeeModel = new Employee($this->db);
        $empStmt = $employeeModel->readAll();
        $employees = $empStmt->fetchAll(PDO::FETCH_ASSOC);

        $totalEmployees = count($employees);
        
        // ตัวแปรเก็บสถิติ
        $typeStats = ['ข้าราชการ อบจ.' => 0, 'ลูกจ้างประจำ' => 0, 'พนักงานจ้างตามภารกิจ' => 0, 'พนักงานจ้างทั่วไป' => 0];
        $genderStats = ['ชาย' => 0, 'หญิง' => 0, 'ไม่ระบุ' => 0];
        $ageStats = ['20-30 ปี' => 0, '31-40 ปี' => 0, '41-50 ปี' => 0, '51-60 ปี' => 0, 'มากกว่า 60 ปี' => 0];
        
        $currentYearCE = (int)date('Y'); // ปี ค.ศ. ปัจจุบัน

        foreach ($employees as $emp) {
            // สถิติประเภทบุคลากร
            $type = $emp['employee_type'];
            if (isset($typeStats[$type])) $typeStats[$type]++;
            else $typeStats[$type] = 1; 

            // สถิติเพศ
            $gender = !empty($emp['gender']) ? $emp['gender'] : 'ไม่ระบุ';
            if (isset($genderStats[$gender])) $genderStats[$gender]++;
            else $genderStats[$gender] = 1;

            // สถิติอายุ (คำนวณจาก วัน/เดือน/ปีเกิด พ.ศ.)
            if(!empty($emp['dob'])) {
                $dob_parts = explode('/', $emp['dob']);
                if(count($dob_parts) == 3) {
                    $yearBE = (int)$dob_parts[2]; // ปี พ.ศ. เกิด
                    if($yearBE > 2400) { // เช็คว่ากรอกปี พ.ศ. มาสมเหตุสมผลหรือไม่
                        $yearCE = $yearBE - 543; // แปลงเป็น ค.ศ.
                        $age = $currentYearCE - $yearCE;

                        if($age <= 30) $ageStats['20-30 ปี']++;
                        elseif($age <= 40) $ageStats['31-40 ปี']++;
                        elseif($age <= 50) $ageStats['41-50 ปี']++;
                        elseif($age <= 60) $ageStats['51-60 ปี']++;
                        else $ageStats['มากกว่า 60 ปี']++;
                    }
                }
            }
        }

        // ลบค่าที่เพศ "ไม่ระบุ" ออกถ้าเป็น 0 เพื่อความสวยงามของกราฟ
        if($genderStats['ไม่ระบุ'] == 0) unset($genderStats['ไม่ระบุ']);

        // --- 2. ดึงข้อมูลกรอบอัตรากำลัง ---
        $manpowerModel = new Manpower($this->db);
        $manStmt = $manpowerModel->readAll();
        $manpowers = $manStmt->fetchAll(PDO::FETCH_ASSOC);

        $totalManpower = count($manpowers);
        $totalOccupied = 0;
        $totalVacant = 0;
        $deptStats = [];

        foreach ($manpowers as $manpower) {
            if ($manpower['status'] == 'occupied') $totalOccupied++;
            else $totalVacant++;

            $dept = $manpower['department'];
            if (!isset($deptStats[$dept])) $deptStats[$dept] = ['total' => 0, 'occupied' => 0, 'vacant' => 0];
            
            $deptStats[$dept]['total']++;
            if ($manpower['status'] == 'occupied') $deptStats[$dept]['occupied']++;
            else $deptStats[$dept]['vacant']++;
        }

        $percentOccupied = ($totalManpower > 0) ? round(($totalOccupied / $totalManpower) * 100) : 0;
        $percentVacant = ($totalManpower > 0) ? round(($totalVacant / $totalManpower) * 100) : 0;

        require_once 'views/dashboard/index.php';
    }
}
?>
<?php
// ==========================================
// ชื่อไฟล์: Employee.php
// ที่อยู่ไฟล์: models/Employee.php
// ==========================================

class Employee {
    private $conn;
    private $table_name = "employees";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ✅ เพิ่มฟังก์ชันตรวจสอบ เลขประจำตัวประชาชน ซ้ำ
    public function isNationalIdExists($national_id, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE national_id = ?";
        if ($exclude_id) {
            $query .= " AND id != ?";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $national_id);
        if ($exclude_id) {
            $stmt->bindParam(2, $exclude_id);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function readAll() {
        $query = "SELECT e.*, w.position_name AS position, w.department, w.agency, w.level, m.employee_type 
                  FROM " . $this->table_name . " e
                  LEFT JOIN emp_work_history w ON w.id = (
                      SELECT id FROM emp_work_history WHERE employee_id = e.id ORDER BY id DESC LIMIT 1
                  )
                  LEFT JOIN manpower m ON m.position_number = e.emp_code
                  ORDER BY e.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readPaginated($from_record_num, $records_per_page, $search_term = "") {
        $query = "SELECT e.*, w.position_name AS position, w.department, w.agency, w.level, m.employee_type 
                  FROM " . $this->table_name . " e
                  LEFT JOIN emp_work_history w ON w.id = (
                      SELECT id FROM emp_work_history WHERE employee_id = e.id ORDER BY id DESC LIMIT 1
                  )
                  LEFT JOIN manpower m ON m.position_number = e.emp_code";
        
        if(!empty($search_term)) {
            $query .= " WHERE e.first_name LIKE ? OR e.last_name LIKE ? OR e.emp_code LIKE ? OR w.position_name LIKE ?";
        }
        $query .= " ORDER BY e.id DESC LIMIT ?, ?";
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($search_term)) {
            $search_param = "%{$search_term}%";
            $stmt->bindParam(1, $search_param);
            $stmt->bindParam(2, $search_param);
            $stmt->bindParam(3, $search_param);
            $stmt->bindParam(4, $search_param);
            $stmt->bindParam(5, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(6, $records_per_page, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt;
    }

    public function countAll($search_term = "") {
        $query = "SELECT e.id FROM " . $this->table_name . " e
                  LEFT JOIN emp_work_history w ON w.id = (
                      SELECT id FROM emp_work_history WHERE employee_id = e.id ORDER BY id DESC LIMIT 1
                  )";
                  
        if(!empty($search_term)) {
            $query .= " WHERE e.first_name LIKE ? OR e.last_name LIKE ? OR e.emp_code LIKE ? OR w.position_name LIKE ?";
        }
        $stmt = $this->conn->prepare($query);
        if(!empty($search_term)) {
            $search_param = "%{$search_term}%";
            $stmt->bindParam(1, $search_param);
            $stmt->bindParam(2, $search_param);
            $stmt->bindParam(3, $search_param);
            $stmt->bindParam(4, $search_param);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function readOne($id) {
        $query = "SELECT e.*, m.employee_type 
                  FROM " . $this->table_name . " e
                  LEFT JOIN manpower m ON m.position_number = e.emp_code
                  WHERE e.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $empData = $stmt->fetch(PDO::FETCH_ASSOC);

        if($empData) {
            $empData['family'] = $this->getChildData("emp_family", $id, true);
            $empData['education'] = $this->getChildData("emp_education", $id);
            $empData['training'] = $this->getChildData("emp_training", $id);
            $empData['work_history'] = $this->getChildData("emp_work_history", $id);
            $empData['leave'] = $this->getChildData("emp_leave", $id);
            $empData['acting'] = $this->getChildData("emp_acting", $id);
            $empData['evaluation'] = $this->getChildData("emp_evaluation", $id);
            $empData['decoration'] = $this->getChildData("emp_decoration", $id);
            $empData['disciplinary'] = $this->getChildData("emp_disciplinary", $id);
            $empData['license'] = $this->getChildData("emp_license", $id);
        }
        return $empData;
    }

    private function getChildData($table, $emp_id, $isSingle = false) {
        $query = "SELECT * FROM {$table} WHERE employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $emp_id);
        $stmt->execute();
        return $isSingle ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (emp_code, national_id, prefix, gender, dob, first_name, last_name, phone, email, avatar) 
                  VALUES 
                  (:emp_code, :national_id, :prefix, :gender, :dob, :first_name, :last_name, :phone, :email, :avatar)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":emp_code", $data['emp_code']);
        $stmt->bindParam(":national_id", $data['national_id']);
        $stmt->bindParam(":prefix", $data['prefix']);
        $stmt->bindParam(":gender", $data['gender']);
        $stmt->bindParam(":dob", $data['dob']);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":avatar", $data['avatar']);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $avatar_query = "";
        if(isset($data['avatar']) && $data['avatar'] != "") {
            $avatar_query = ", avatar = :avatar";
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET emp_code = :emp_code, national_id = :national_id, prefix = :prefix, gender = :gender, dob = :dob,
                      first_name = :first_name, last_name = :last_name, phone = :phone, email = :email " . $avatar_query . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":emp_code", $data['emp_code']);
        $stmt->bindParam(":national_id", $data['national_id']);
        $stmt->bindParam(":prefix", $data['prefix']);
        $stmt->bindParam(":gender", $data['gender']);
        $stmt->bindParam(":dob", $data['dob']);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":id", $id);
        
        if(isset($data['avatar']) && $data['avatar'] != "") {
            $stmt->bindParam(":avatar", $data['avatar']);
        }

        return $stmt->execute();
    }

    public function updateFamily($emp_id, $data) {
        $stmtDel = $this->conn->prepare("DELETE FROM emp_family WHERE employee_id = ?");
        $stmtDel->execute([$emp_id]);
        
        $query = "INSERT INTO emp_family (employee_id, father_name, mother_name, spouse_name, children_count) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$emp_id, $data['father_name'], $data['mother_name'], $data['spouse_name'], $data['children_count']]);
    }

    public function updateRelations($table, $emp_id, $dataArray, $columns) {
        $stmtDel = $this->conn->prepare("DELETE FROM {$table} WHERE employee_id = ?");
        $stmtDel->execute([$emp_id]);
        
        if(empty($dataArray) || !is_array($dataArray) || empty($dataArray[0])) return;

        $placeholders = implode(',', array_fill(0, count($columns) + 1, '?'));
        $colNames = implode(',', $columns);
        $query = "INSERT INTO {$table} (employee_id, {$colNames}) VALUES ({$placeholders})";
        $stmt = $this->conn->prepare($query);

        $rowCount = count($dataArray[0]);
        for($i = 0; $i < $rowCount; $i++) {
            $params = [$emp_id];
            $hasData = false;
            foreach($dataArray as $colIndex => $colValues) {
                $val = trim($colValues[$i] ?? '');
                if($val !== '') $hasData = true;
                $params[] = $val;
            }
            if($hasData) $stmt->execute($params);
        }
    }

    public function delete($id) {
        $related_tables = [
            'emp_family', 'emp_education', 'emp_training', 
            'emp_work_history', 'emp_leave', 'emp_acting', 
            'emp_evaluation', 'emp_decoration', 'emp_disciplinary', 'emp_license'
        ];
        foreach($related_tables as $table) {
            $stmtDel = $this->conn->prepare("DELETE FROM {$table} WHERE employee_id = ?");
            $stmtDel->execute([$id]);
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
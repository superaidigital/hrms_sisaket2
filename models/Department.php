<?php
class Department {
    private $conn;
    private $table_name = "departments";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        // เรียงลำดับตามประเภท และชื่อ
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY type ASC, name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (name, type) VALUES (:name, :type)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":type", $data['type']);

        if($stmt->execute()) return true;
        return false;
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET name = :name, type = :type WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":type", $data['type']);
        $stmt->bindParam(":id", $id);

        if($stmt->execute()) return true;
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if($stmt->execute()) return true;
        return false;
    }
}
?>
<?php
require_once "Database.php";

class Loan extends Database
{
    private $loan = "loans";

    public function totalLoans($user_id)
    {
        $stmt = $this->conn->prepare("SELECT SUM(amount) as total_loans FROM loans WHERE user_id=? AND status='approved'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $this->res = $stmt->get_result();
        $row = $this->res->fetch_assoc();
        $totalLoans = $row['total_loans'] ?? 0;
        $stmt->close();
        return $totalLoans;
    }

    public function select($row = "*", $where = NULL, $order = NULL)
    {
        try {
            if (!is_null($where)) {
                if (!is_null($order)) {
                    $cond = $types = "";
                    foreach ($where as $key => $value) {
                        $cond .= $key . " = ? AND ";
                        $types .= substr(gettype($value), 0, 1);
                    }
                    $cond = substr($cond, 0, -4);
                    $stmt = $this->conn->prepare("SELECT $row FROM $this->loan WHERE $cond $order");
                    $stmt->bind_param($types, ...array_values($where));
                } else {
                    $cond = $types = "";
                    foreach ($where as $key => $value) {
                        $cond .= $key . " = ? AND ";
                        $types .= substr(gettype($value), 0, 1);
                    }
                    $cond = substr($cond, 0, -4);
                    $stmt = $this->conn->prepare("SELECT $row FROM $this->loan WHERE $cond");
                    $stmt->bind_param($types, ...array_values($where));
                }
            } else {
                $stmt = $this->conn->prepare("SELECT $row FROM $this->loan");
            }
            $stmt->execute();
            $this->res = $stmt->get_result();
        } catch (Exception $e) {
            die("Error requesting data!. <br>" . $e);
        }
    }

    public function insert($data)
    {
        try {
            $table_column = implode(',', array_keys($data));
            $prep = $types = "";
            foreach ($data as $key => $value) {
                $prep .= '?,';
                $types .= substr(gettype($value), 0, 1);
            }
            $prep = substr($prep, 0, -1);
            $stmt = $this->conn->prepare("INSERT INTO $this->loan($table_column) VALUES ($prep)");
            $stmt->bind_param($types, ...array_values($data));
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die("Error while insertind data.<br>" . $e);
        }
    }

    public function update($data)
    {
        try {
            $set = $cond = $types = '';
            foreach ($data as $key => $value) {
                if ($key == 'id') {
                    $cond .= $key . " = ? AND ";
                } else {
                    $set .= "$key = ?,";
                }
                $types .= substr(gettype($value), 0, 1);
            }
            $set = substr($set, 0, -1);
            $cond = substr($cond, 0, -4);
            $stmt = $this->conn->prepare("UPDATE $this->loan SET $set WHERE $cond");
            $stmt->bind_param($types, ...array_values($data));
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die("Error updating data! <br>" . $e);
        }
    }
}

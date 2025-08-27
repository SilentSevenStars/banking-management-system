<?php
require_once "Database.php";

class Transaction extends Database
{
    private $table = 'transactions';

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
            $stmt = $this->conn->prepare("INSERT INTO $this->table($table_column) VALUES ($prep)");
            $stmt->bind_param($types, ...array_values($data));
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die("Error while insertind data.<br>" . $e);
        }
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
                    $stmt = $this->conn->prepare("SELECT $row FROM $this->table WHERE $cond $order");
                    $stmt->bind_param($types, ...array_values($where));
                } else {
                    $cond = $types = "";
                    foreach ($where as $key => $value) {
                        $cond .= $key . " = ? AND ";
                        $types .= substr(gettype($value), 0, 1);
                    }
                    $cond = substr($cond, 0, -4);
                    $stmt = $this->conn->prepare("SELECT $row FROM $this->table WHERE $cond");
                    $stmt->bind_param($types, ...array_values($where));
                }
            } else {
                $stmt = $this->conn->prepare("SELECT $row FROM $this->table");
            }
            $stmt->execute();
            $this->res = $stmt->get_result();
        } catch (Exception $e) {
            die("Error requesting data!. <br>" . $e);
        }
    }
    public function filter($row = "*", $where = NULL, $order = NULL)
    {
        try {
            if (!is_null($where)) {
                $cond = $types = "";
                foreach ($where as $key => $value) {
                    if (!empty($value)) {
                        if ($key === "from") {
                            $cond .= "DATE(created_at) >= ? AND ";
                            $types .= substr(gettype($value), 0, 1);
                        } elseif ($key === "to") {
                            $cond .= "DATE(created_at) <= ? AND ";
                            $types .= substr(gettype($value), 0, 1);
                        } else {
                            $cond .= $key . " = ? AND ";
                            $types .= substr(gettype($value), 0, 1);
                        }
                    }
                }
                $cond = substr($cond, 0, -4);
                if (!is_null($order)) {
                    $stmt = $this->conn->prepare("SELECT $row FROM $this->table WHERE $cond $order");
                    $stmt->bind_param($types, ...array_values($where));
                } else {
                    $stmt = $this->conn->prepare("SELECT $row FROM $this->table WHERE $cond");
                    $stmt->bind_param($types, ...array_values($where));
                }
            } else {
                $stmt = $this->conn->prepare("SELECT $row FROM $this->table");
            }
            $stmt->execute();
            $this->res = $stmt->get_result();
        } catch (Exception $e) {
            die("Error requesting data!. <br>" . $e);
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
            $stmt = $this->conn->prepare("UPDATE $this->table SET $set WHERE $cond");
            $stmt->bind_param($types, ...array_values($data));
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die("Error updating data! <br>" . $e);
        }
    }

    public function destroy($where)
    {
        try {
            $cond = $types = '';
            foreach ($where as $key => $value) {
                $cond .= $key . " = ? AND ";
                $types .= substr(gettype($value), 0, 1);
            }
            $cond = substr($cond, 0, -4);
            $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE $cond");
            $stmt->bind_param($types, ...array_values($where));
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die("Error while deleting data! . <br>" . $e);
        }
    }

    public function search($row, $where)
    {
        try {
            $cond = $types = "";
            foreach ($where as $key => $value) {
                $cond .= $key . " LIKE ? OR ";
                $types .= substr(gettype($value), 0, 1);
            }
            $cond = substr($cond, 0, -3);
            $stmt = $this->conn->prepare("SELECT $row FROM $this->table WHERE $cond");
            $stmt->bind_param($types, ...array_values($where));
            $stmt->execute();
            $this->res = $stmt->get_result();
        } catch (Exception $e) {
            die("Error requesting data!. <br>" . $e);
        }
    }

    public function exportCSV($datas)
    {
        try {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=transactions.csv');
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Type', 'Amount', 'Status', 'Date']);
            foreach ($datas as $data) {
                fputcsv($output, [
                    ucfirst($data['type']),
                    $data['amount'],
                    ucfirst($data['status']),
                    $data['date'],   // ✅ now using alias
                ]);
            }
            fclose($output);
        } catch (Exception $e) {
            die("Error exporting data!. <br>" . $e);
        }
    }

    public function getSummary($user_id)
    {
        $summary = ["deposit" => 0, "withdraw" => 0, "balance" => 0];
        $stmt = $this->conn->prepare("
            SELECT type, SUM(amount) as total 
            FROM $this->table 
            WHERE user_id=? AND status='success' 
            GROUP BY type
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $summary[$row['type']] = $row['total'];
        }
        $summary['balance'] = $summary['deposit'] - $summary['withdraw'];
        return $summary;
    }

    public function getChartData($user_id)
    {
        $chartData = [];
        $stmt = $this->conn->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
                   SUM(CASE WHEN type='deposit' THEN amount ELSE 0 END) as deposits,
                   SUM(CASE WHEN type='withdraw' THEN amount ELSE 0 END) as withdrawals
            FROM $this->table
            WHERE user_id=? AND status='success'
            GROUP BY month
            ORDER BY month ASC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $chartData[] = $row;
        }
        return $chartData;
    }
}

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config/class/Database.php';
$mydb = new Database;
$userId = $_SESSION['user_id'];

$type = $_GET['type'] ?? 'csv';



$stmt = $mydb->conn->prepare("
    SELECT id, type, amount, status, created_at 
    FROM transactions 
    WHERE user_id=? 
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

if ($type === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=transactions.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','Type','Amount','Status','Date']);
    foreach ($data as $row) {
        fputcsv($out, [$row['id'],$row['type'],$row['amount'],$row['status'],$row['created_at']]);
    }
    fclose($out);
    exit;
}

if ($type === 'xlsx') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename=transactions.xls');
    echo "ID\tType\tAmount\tStatus\tDate\n";
    foreach ($data as $row) {
        echo "{$row['id']}\t{$row['type']}\t{$row['amount']}\t{$row['status']}\t{$row['created_at']}\n";
    }
    exit;
}

if ($type === 'pdf') {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=transactions.pdf");
    ?>
    <html>
    <head><meta charset="UTF-8"><title>Transactions Report</title></head>
    <body>
        <h2>Transactions Report</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr><th>ID</th><th>Type</th><th>Amount</th><th>Status</th><th>Date</th></tr>
            <?php foreach($data as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= ucfirst($row['type']) ?></td>
                <td>₱<?= number_format($row['amount'],2) ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </body>
    </html>
    <?php
    exit;
}

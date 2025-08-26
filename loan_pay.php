<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loan_id'])) {
    $loanId = intval($_POST['loan_id']);
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT amount, status FROM loans WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $loanId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $loan = $result->fetch_assoc();
    $stmt->close();

    if ($loan && $loan['status'] == 'approved') {
        $loanAmount = $loan['amount'];

        $stmt = $conn->prepare("SELECT balance FROM users WHERE id=?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user['balance'] >= $loanAmount) {
            $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id=?");
            $stmt->bind_param("di", $loanAmount, $userId);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("UPDATE loans SET status='paid' WHERE id=?");
            $stmt->bind_param("i", $loanId);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, status, created_at) VALUES (?, 'loan repayment', ?, 'success', NOW())");
            $stmt->bind_param("id", $userId, $loanAmount);
            $stmt->execute();
            $stmt->close();

            $_SESSION['message'] = "Loan payment successful!";
        } else {
            $_SESSION['error'] = "Insufficient balance to pay this loan.";
        }
    }
}

header("Location: loan.php");
exit;

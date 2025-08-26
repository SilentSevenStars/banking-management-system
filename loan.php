<?php
session_start();

if (!isset($_SESSION['user_id']))
    header("Location: login.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan - PRT Bank</title>
    <script type="text/javascript" src="assets/js/tailwind.js"></script>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <?php include 'layout/sidebar.php'; ?>
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-6">Loan Services</h2>


            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Available Balance</h3>
                    <p class="text-2xl font-bold text-blue-600" id="availableBalance">
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Current Balance</h3>
                    <p class="text-2xl font-bold text-green-600" id="balanceText">
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4">Apply for a Loan</h3>
                <form action="loan_process.php" method="POST" class="space-y-4">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Loan Amount</label>
                        <input type="number" id="amount" name="amount" max="20000" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="term" class="block text-sm font-medium text-gray-700">Term (Months)</label>
                        <select id="term" name="term" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="3">3 Months</option>
                            <option value="9">9 Months</option>
                            <option value="12">12 Months</option>
                            <option value="24">24 Months</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                        Submit Application
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Loan History</h3>
                <?php if (count($loans) > 0): ?>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Term</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($loans as $loan): ?>
                                <tr class="border-t">
                                    <td class="px-4 py-2">₱<?= number_format($loan['amount'], 2) ?></td>
                                    <td class="px-4 py-2"><?= $loan['term'] ?> months</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded 
                    <?= $loan['status'] == 'approved' ? 'bg-green-100 text-green-600' : ($loan['status'] == 'paid' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-600') ?>">
                                            <?= $loan['status'] == 'paid' ? 'Loan Paid' : ucfirst($loan['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-2"><?= date("M d, Y", strtotime($loan['created_at'])) ?></td>
                                    <td class="px-4 py-2">
                                        <?php if ($loan['status'] == 'approved'): ?>
                                            <form action="loan_pay.php" method="POST" onsubmit="return confirm('Are you sure you want to pay this loan?');">
                                                <input type="hidden" name="loan_id" value="<?= $loan['id'] ?>">
                                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500">
                                                    Pay Now
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-gray-500">Loan Paid</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500">No loan history available.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function () {
            alert("try_alert works!");
        });
    </script>
</body>

</html>
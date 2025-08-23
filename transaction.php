<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - PRT Bank</title>
    <script type="text/javascript" src="assets/js/tailwind.js"></script>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <?php include 'layout/sidebar.php'; ?>

        <main class="flex-1 p-6 overflow-y-auto">
            <h2 class="text-2xl font-bold mb-6">Transaction History</h2>

            <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow flex flex-wrap gap-4">
                <div>
                    <label class="block text-gray-700">Type</label>
                    <select name="type" class="border rounded p-2">
                        <option value="">All</option>
                        <option value="deposit" <?= isset($_GET['type']) && $_GET['type'] == 'deposit' ? 'selected' : '' ?>>Deposit</option>
                        <option value="withdraw" <?= isset($_GET['type']) && $_GET['type'] == 'withdraw' ? 'selected' : '' ?>>Withdraw</option>
                        <option value="loan" <?= isset($_GET['type']) && $_GET['type'] == 'loan' ? 'selected' : '' ?>>Loan</option>
                        <option value="repayment" <?= isset($_GET['type']) && $_GET['type'] == 'repayment' ? 'selected' : '' ?>>Repayment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Status</label>
                    <select name="status" class="border rounded p-2">
                        <option value="">All</option>
                        <option value="success" <?= isset($_GET['status']) && $_GET['status'] == 'success' ? 'selected' : '' ?>>Success</option>
                        <option value="pending" <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="failed" <?= isset($_GET['status']) && $_GET['status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">From</label>
                    <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>" class="border rounded p-2">
                </div>
                <div>
                    <label class="block text-gray-700">To</label>
                    <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>" class="border rounded p-2">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
                    <a href="transaction.php" class="px-4 py-2 bg-gray-500 text-white rounded">Reset</a>
                </div>
                <div class="flex items-end ml-auto">
                    <a href="transaction.php?export=csv" class="px-4 py-2 bg-green-600 text-white rounded">Export CSV</a>
                </div>
            </form>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="table-auto w-full border-collapse">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left w-16">ID</th>
                            <th class="px-4 py-2 text-left w-32">Type</th>
                            <th class="px-4 py-2 text-left w-40">Amount</th>
                            <th class="px-4 py-2 text-left w-32">Status</th>
                            <th class="px-4 py-2 text-left w-48">Date</th>
                            <th class="px-4 py-2 text-left w-56">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= $row['id'] ?></td>
                                    <td class="px-4 py-2 capitalize"><?= $row['type'] ?></td>
                                    <td class="px-4 py-2">₱<?= number_format($row['amount'], 2) ?></td>
                                    <td class="px-4 py-2 capitalize"><?= $row['status'] ?></td>
                                    <td class="px-4 py-2"><?= $row['created_at'] ?></td>
                                    <td class="px-4 py-2">
                                        <div class="flex gap-2">
                                            <a href="transaction_receipt.php?id=<?= $row['id'] ?>&view=true"
                                                class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-500">View</a>
                                            <a href="transaction_receipt.php?id=<?= $row['id'] ?>&download=true"
                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-500">Download</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">No transactions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>
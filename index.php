<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PRT Bank</title>
    <script type="text/javascript" src="assets/js/tailwind.js"></script>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
     <script type="text/javascript" src="assets/js/chart.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">

        <?php include 'layout/sidebar.php'; ?>

        <main class="flex-1 p-6 overflow-y-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Welcome, <?= $_SESSION['fullname'] ?>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
                    <h3 class="text-gray-500">Balance</h3>
                    <p id="balanceText" class="text-2xl font-bold text-gray-800">
                        ₱<?= number_format($balance, 2) ?>
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
                    <h3 class="text-gray-500">Income</h3>
                    <p class="text-2xl font-bold text-green-500">₱0.00</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
                    <h3 class="text-gray-500">Expenses</h3>
                    <p class="text-2xl font-bold text-red-500">₱0.00</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
                    <h3 class="text-gray-500">Savings</h3>
                    <p class="text-2xl font-bold text-blue-500">₱0.00</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-lg p-6 col-span-2 border h-64">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Finances</h3>
                    <canvas id="financeChart"></canvas>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Transaction</h3>
                    <form id="quickTransactionForm" class="space-y-4">
                        <input type="number" id="amountInput" name="amount" placeholder="Enter Amount"
                            class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500" required>
                        <div class="flex space-x-2">
                            <button type="button" onclick="submitTransaction('deposit')"
                                class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-500">Deposit</button>
                            <button type="button" onclick="submitTransaction('withdraw')"
                                class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-500">Withdraw</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 mt-6 border">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Transaction History</h3>
                <div class="max-h-64 overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2 text-gray-500">Type</th>
                                <th class="pb-2 text-gray-500">Amount</th>
                                <th class="pb-2 text-gray-500">Status</th>
                                <th class="pb-2 text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $tx):
                                $typeDisplay = ucfirst($tx['type']);
                                $amount = floatval($tx['amount']);
                                if ($amount < 0) {
                                    $sign = '-';
                                    $amountDisplay = abs($amount);
                                    $amountColor = 'text-red-500';
                                } else {
                                    $sign = '+';
                                    $amountDisplay = $amount;
                                    $amountColor = 'text-green-500';
                                }
                                $statusColor = strtolower($tx['status']) === 'success' ? 'text-green-500' : 'text-red-500';
                            ?>
                                <tr class="border-b hover:bg-gray-100">
                                    <td><?= $typeDisplay ?></td>
                                    <td class="<?= $amountColor ?> font-semibold"><?= $sign ?>₱<?= number_format($amountDisplay, 2) ?></td>
                                    <td class="<?= $statusColor ?> font-semibold"><?= ucfirst($tx['status']) ?></td>
                                    <td><?= $tx['date'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        const ctx = document.getElementById('financeChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                datasets: [{
                        label: 'Income',
                        data: [200, 178, 400, 320, 500, 900],
                        borderColor: 'green',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Expenses',
                        data: [750, 623, 500, 450, 300, 400],
                        borderColor: 'red',
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        async function submitTransaction(type) {
            const amount = document.getElementById("amountInput").value;
            if (!amount || amount <= 0) {
                alert("Enter a valid amount.");
                return;
            }

            try {
                const response = await fetch("transaction_api.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "amount=" + encodeURIComponent(amount) + "&type=" + encodeURIComponent(type)
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById("balanceText").textContent = "₱" + parseFloat(data.newBalance).toFixed(2);

                    const tbody = document.querySelector("table tbody");
                    const row = document.createElement("tr");
                    row.className = "border-b hover:bg-gray-100";
                    row.innerHTML = `
        <td>${type.charAt(0).toUpperCase() + type.slice(1)}</td>
        <td class="${type === "withdraw" ? "text-red-500" : "text-green-500"} font-semibold">
          ${type === "withdraw" ? "-" : "+"}₱${parseFloat(amount).toFixed(2)}
        </td>
        <td class="text-green-500 font-semibold">Success</td>
        <td>${new Date().toLocaleString()}</td>
      `;
                    tbody.prepend(row);

                    document.getElementById("amountInput").value = "";
                } else {
                    alert(data.message || "Transaction failed.");
                }
            } catch (error) {
                console.error(error);
                alert("Error processing transaction.");
            }
        }
    </script>
</body>

</html>
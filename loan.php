<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Loan - PRT Bank</title>
    <script type="text/javascript" src="assets/js/tailwind.js"></script>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <?php include 'layout/sidebar.php'; ?>
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-6">Loan Services</h2>


            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Available Balance</h3>
                    <p class="text-2xl font-bold text-blue-600" id="availableBalance">

                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Current Balance</h3>
                    <p class="text-2xl font-bold text-green-600" id="balance">

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
                    <tbody id="tBodyLoan">

                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            loadBalance()
            loadLoanHistory()
        })

        function loadBalance() {
            $.ajax({
                url: "config/request.php",
                method: "POST",
                data: {
                    'get_balance': true,
                    'user_id': <?= $_SESSION['user_id'] ?>
                },
                success: function(result) {
                    console.log("Balance raw result:", result);

                    try {
                        let datas = JSON.parse(result);

                        if (datas && datas.availableBalance !== undefined && datas.balance !== undefined) {
                            $('#availableBalance').html(`₱ ${parseFloat(datas.availableBalance).toFixed(2)}`);
                            $('#balance').html(`₱ ${parseFloat(datas.balance).toFixed(2)}`);
                        } else {
                            $('#availableBalance').html(`₱ 0.00`);
                            $('#balance').html(`₱ 0.00`);
                        }
                    } catch (e) {
                        console.error("JSON parse error:", e, result);
                        $('#availableBalance').html(`₱ 0.00`);
                        $('#balance').html(`₱ 0.00`);
                    }
                },
                error: function() {
                    alert("Something went wrong");
                }
            })
        }

        function loadLoanHistory() {
            $.ajax({
                url: "config/request.php",
                method: "POST",
                data: {
                    'get_loan': true,
                    'user_id': <?= $_SESSION['user_id'] ?>,
                    'order': "ORDER BY created_at DESC",
                },
                success: function(result) {
                    console.log("Loan result:", result);

                    try {
                        let datas = JSON.parse(result);
                        let tBody = '';

                        if (datas.length > 0) {
                            datas.forEach(function(data) {
                                let statusClass =
                                    data.status === 'approved' ? 'bg-green-100 text-green-600' :
                                    (data.status === 'paid' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-600');

                                let statusLabel = data.status === 'paid' ? 'Loan Paid' : data.status.charAt(0).toUpperCase() + data.status.slice(1);

                                let actionHtml = '';
                                if (data.status === 'approved') {
                                    actionHtml = `
                                <form action="loan_pay.php" method="POST" onsubmit="return confirm('Are you sure you want to pay this loan?');">
                                    <input type="hidden" name="loan_id" value="${data.id}">
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500">
                                        Pay Now
                                    </button>
                                </form>
                            `;
                                } else {
                                    actionHtml = `<span class="text-gray-500">Loan Paid</span>`;
                                }

                                tBody += `
                            <tr class="border-t">
                                <td class="px-4 py-2">₱${parseFloat(data.amount).toFixed(2)}</td>
                                <td class="px-4 py-2">${data.term} months</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded ${statusClass}">
                                        ${statusLabel}
                                    </span>
                                </td>
                                <td class="px-4 py-2">${data.created_at}</td>
                                <td class="px-4 py-2">${actionHtml}</td>
                            </tr>
                        `;
                            });
                        } else {
                            tBody = `<tr><td colspan="5" class="text-center text-gray-500">No loans found</td></tr>`;
                        }

                        $('#tBodyLoan').html(tBody);
                    } catch (e) {
                        console.error("JSON parse error:", e, result);
                        $('#tBodyLoan').html(`<tr><td colspan="5" class="text-center text-gray-500">No loans found</td></tr>`);
                    }
                },
                error: function() {
                    alert("Something went wrong")
                }
            })
        }
    </script>
</body>

</html>
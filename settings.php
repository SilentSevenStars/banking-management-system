<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - PRT Bank</title>
    <script type="text/javascript" src="assets/js/tailwind.js"></script>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <?php include 'layout/sidebar.php'; ?>

        <div class="flex-1 p-6 overflow-y-auto bg-white text-gray-900">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Account Settings</h2>

            <?php if ($message): ?>
                <div class="mb-4 p-3 rounded text-white <?= strpos($message, 'successfully') !== false ? 'bg-green-600' : 'bg-red-600' ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-lg p-6 border max-w-lg">
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>"
                            class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
                            class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">New Password (leave blank to keep current)</label>
                        <input type="password" name="password"
                            class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
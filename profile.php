<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - PRT Bank</title>
    <script type="text/javascript" src="assets/js/tailwind.js"></script>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <?php include 'layout/sidebar.php'; ?>

        <main class="flex-1 p-6 overflow-y-auto">
            <h2 class="text-2xl font-bold mb-6">My Profile</h2>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">Profile updated successfully!</div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">Failed to update profile.</div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-lg p-6 border">
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-gray-700">Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Email (readonly)</label>
                        <input type="email" value="<?= htmlspecialchars($email) ?>" class="w-full p-2 border rounded bg-gray-100" disabled>
                    </div>
                    <div>
                        <label class="block text-gray-700">Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700">Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Current Balance:</label>
                        <p class="text-xl text-green-600 font-semibold">₱<?= number_format($balance, 2) ?></p>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Update Profile</button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
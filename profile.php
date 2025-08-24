<?php
    session_start();

    if(!isset($_SESSION['user_id']))
        header("Location: login.php");

    $id = $_SESSION['user_id'];

?>
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
                <form method="POST" id="updateProfileForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-700">Username</label>
                        <input type="text" name="username" id="username" class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Email (readonly)</label>
                        <input type="email" id="email" class="w-full p-2 border rounded bg-gray-100" disabled>
                    </div>
                    <div>
                        <label class="block text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700">Address</label>
                        <input type="text" name="address" id="address" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold">Current Balance:</label>
                        <p class="text-xl text-green-600 font-semibold" id="balance">₱0.00</p>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <input type="submit" value="Update Profile" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500" name="updateProfile">
                </form>
            </div>
        </main>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            loadProfile(<?= $id ?>)
        })

        function loadProfile(user_id){
            $.ajax({
                url: "config/request.php",
                method: "POST",
                data: {
                    "get_profile": true,
                    "userId": user_id,
                }, 
                success: function(result){
                    let datas = JSON.parse(result)
                    datas.forEach(function(data) {
                        $('#id').val(data['id'])
                        $('#username').val(data['username'])
                        $('#email').val(data['email'])
                        $('#phone').val(data['phone'])
                        $('#address').val(data['address'])
                        $('#balance').text("₱" + parseFloat(data['balance']).toLocaleString(undefined, {minimumFractionDigits: 2}))
                    })
                },
                error: function(e){
                    alert("Something went wrong")
                }
            })
        }

        $('#updateProfileForm').on('submit', function(e){
            e.preventDefault()
            var datas = $(this).serializeArray()
            var data_array = {}
            $.map(datas, function(data){
                data_array[data['name']] = data['value']
            })
            $.ajax({
                url: "config/request.php",
                method: "POST",
                data: {
                    'update_profile': true,
                    ...data_array
                },
                success: function(){
                    loadProfile()
                }, 
                error: function(){
                    alert("Something went wrong")
                }
            })
        })
    </script>
</body>

</html>

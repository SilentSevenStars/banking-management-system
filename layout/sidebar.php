<aside class="w-64 bg-gray-900 text-white flex flex-col justify-between p-5 min-h-screen">
  <div>
    <a href="index.php"><img src="assets/image/sidebarLogo.png" alt=""></a>
    <nav class="space-y-2">
      <a href="./profile.php" class="block px-4 py-2 rounded hover:bg-gray-700 
        <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'bg-gray-700' : '' ?>">
        Profile
      </a>
      <a href="./index.php" class="block px-4 py-2 rounded hover:bg-gray-700 
        <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-700' : '' ?>">
        Dashboard
      </a>
      <a href="./transaction.php" class="block px-4 py-2 rounded hover:bg-gray-700 
        <?= basename($_SERVER['PHP_SELF']) == 'transaction.php' ? 'bg-gray-700' : '' ?>">
        Transaction
      </a>
      <a href="payment.php" class="block px-4 py-2 rounded hover:bg-gray-700 
        <?= basename($_SERVER['PHP_SELF']) == 'payment.php' ? 'bg-gray-700' : '' ?>">
        Payment
      </a>
      <a href="./report_analysis.php" class="block px-4 py-2 rounded hover:bg-gray-700 
        <?= basename($_SERVER['PHP_SELF']) == 'report_analysis.php' ? 'bg-gray-700' : '' ?>">
        Report and Analysis
      </a>
      <a href="./loan.php" class="block px-4 py-2 rounded hover:bg-gray-700 
        <?= basename($_SERVER['PHP_SELF']) == 'loan.php' ? 'bg-gray-700' : '' ?>">
        Loan
      </a>
      <a href="./settings.php" class="block px-4 py-2 rounded hover:bg-gray-700 
        <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-gray-700' : '' ?>">
        Settings
      </a>
    </nav>
  </div>
  <a href="./logout.php" 
     class="block px-4 py-2 mt-4 bg-red-600 text-center rounded hover:bg-red-500">
    Logout
  </a>
</aside>

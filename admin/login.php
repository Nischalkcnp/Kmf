<?php
require_once dirname(__DIR__) . '/config/config.php';

if (isLoggedIn()) {
    redirect(BASE_URL . 'admin/index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($user && $pass) {
        $pdo = getDb();
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->execute([$user]);
        $row = $stmt->fetch();
        if ($row && password_verify($pass, $row['password_hash'])) {
            $_SESSION['admin_id'] = (int)$row['id'];
            $_SESSION['admin_username'] = $row['username'];
            redirect(BASE_URL . 'admin/index.php');
        }
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KMF Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { kmf: { blue: '#1e3a5f', orange: '#e85d04' } } } } }</script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-kmf-blue mb-6">KMF Admin Login</h1>
        <?php if ($error): ?>
            <p class="text-red-600 mb-4"><?php echo escape($error); ?></p>
        <?php endif; ?>
        <form method="post" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" required value="<?php echo escape($_POST['username'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="w-full bg-kmf-orange hover:bg-orange-600 text-white font-semibold py-2 rounded-lg">Login</button>
        </form>
        <p class="mt-4 text-sm text-gray-500">Default: admin / password — change in DB after first login.</p>
    </div>
</body>
</html>

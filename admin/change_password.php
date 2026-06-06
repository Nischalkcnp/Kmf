<?php
require_once dirname(__DIR__) . '/config/config.php';
requireLogin();

$adminTitle = 'Change Password';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if ($current_pass && $new_pass && $confirm_pass) {
        if ($new_pass !== $confirm_pass) {
            $error = 'New passwords do not match.';
        } elseif (strlen($new_pass) < 6) {
            $error = 'New password must be at least 6 characters long.';
        } else {
            $pdo = getDb();
            // Fetch current password hash
            $stmt = $pdo->prepare("SELECT password_hash FROM admin_users WHERE id = ? LIMIT 1");
            $stmt->execute([$_SESSION['admin_id']]);
            $row = $stmt->fetch();

            if ($row && password_verify($current_pass, $row['password_hash'])) {
                // Update password
                $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
                $update->execute([$new_hash, $_SESSION['admin_id']]);
                $success = 'Password changed successfully.';
            } else {
                $error = 'Current password is incorrect.';
            }
        }
    } else {
        $error = 'All fields are required.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Security Settings</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Change your login password</p>
    </div>
</div>

<?php if ($success): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100">
    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <p class="text-sm font-bold text-green-600"><?php echo escape($success); ?></p>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100 animate-headShake">
    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <p class="text-sm font-bold text-red-600"><?php echo escape($error); ?></p>
</div>
<?php endif; ?>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 max-w-xl">
    <form method="post" class="space-y-6">
        <?php echo csrfField(); ?>
        
        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Current Password</label>
            <input type="password" name="current_password" required
                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                placeholder="Enter current password">
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">New Password</label>
            <input type="password" name="new_password" required
                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                placeholder="At least 6 characters">
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Confirm New Password</label>
            <input type="password" name="confirm_password" required
                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                placeholder="Re-enter new password">
        </div>

        <div class="pt-4 flex gap-4">
            <button type="submit" class="flex-1 bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1">
                Change Password
            </button>
            <a href="index.php" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold py-4 rounded-2xl text-center transition-all flex items-center justify-center">
                Back to Dashboard
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

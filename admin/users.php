<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_iam');

$adminTitle = 'User Manager';
$pdo = getDb();

$error = '';
$success = '';

// Fetch all roles for the selection dropdown
$roles = $pdo->query("SELECT * FROM roles ORDER BY name ASC")->fetchAll();

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
        if (!$edit) {
            redirect(BASE_URL . 'admin/users.php');
        }
    } else {
        $edit = ['id' => 0, 'username' => '', 'email' => '', 'role_id' => null, 'status' => 'active'];
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role_id = !empty($_POST['role_id']) ? (int)$_POST['role_id'] : null;
    $status = trim($_POST['status'] ?? 'active');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validations
    if (!$username || !$email) {
        $error = 'Username and Email are required.';
    } elseif ($id === 0 && !$password) {
        $error = 'Password is required for new users.';
    } elseif ($password && $password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif ($password && strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check uniqueness of username
        $check = $pdo->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
        $check->execute([$username, $id]);
        if ($check->fetch()) {
            $error = 'Username is already taken.';
        } else {
            // Safety: prevent self lockouts or modifying the main admin
            if ($id === (int)$_SESSION['admin_id']) {
                $status = 'active'; // cannot deactivate self
                // Force own role to remain unchanged if self
                $stmt = $pdo->prepare("SELECT role_id FROM admin_users WHERE id = ?");
                $stmt->execute([$id]);
                $role_id = $stmt->fetchColumn();
            }

            // Fetch old username to protect the core admin
            if ($id > 0) {
                $stmtOld = $pdo->prepare("SELECT username FROM admin_users WHERE id = ?");
                $stmtOld->execute([$id]);
                $oldUsername = $stmtOld->fetchColumn();
                if ($oldUsername === 'admin') {
                    $username = 'admin'; // cannot rename system admin
                    $status = 'active';  // cannot deactivate system admin
                    // Role must be Super Admin (1)
                    $role_id = 1;
                }
            }

            if ($id > 0) {
                // Update
                if ($password) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, email = ?, role_id = ?, status = ?, password_hash = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $role_id, $status, $hash, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, email = ?, role_id = ?, status = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $role_id, $status, $id]);
                }
                redirect(BASE_URL . 'admin/users.php?updated=1');
            } else {
                // Insert
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, role_id, status, password_hash) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $role_id, $status, $hash]);
                redirect(BASE_URL . 'admin/users.php?created=1');
            }
        }
    }
}

// Handle Delete User
if (isset($_GET['delete'])) {
    if (isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token'] ?? '', $_GET['csrf'])) {
        $delId = (int)$_GET['delete'];
        
        // Prevent deleting oneself
        if ($delId === (int)$_SESSION['admin_id']) {
            $error = 'You cannot delete your own account.';
        } else {
            // Check if user is the main system admin
            $stmt = $pdo->prepare("SELECT username FROM admin_users WHERE id = ?");
            $stmt->execute([$delId]);
            $usernameToDelete = $stmt->fetchColumn();
            
            if ($usernameToDelete === 'admin') {
                $error = 'The primary system admin user cannot be deleted.';
            } else {
                $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
                $stmt->execute([$delId]);
                redirect(BASE_URL . 'admin/users.php?deleted=1');
            }
        }
    }
}

// Fetch all users
$users = $pdo->query("
    SELECT u.*, r.name as role_name 
    FROM admin_users u 
    LEFT JOIN roles r ON u.role_id = r.id 
    ORDER BY u.username ASC
")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight font-montserrat">User Management</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Add, edit, or deactivate CMS administrative accounts</p>
    </div>
    <?php if (!$edit): ?>
    <a href="?edit=0" class="inline-flex items-center gap-2 bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-6 py-3 rounded-2xl shadow-lg shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create New User
    </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['updated'])): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100">
    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <p class="text-sm font-bold text-green-600">User account updated successfully.</p>
</div>
<?php endif; ?>

<?php if (isset($_GET['created'])): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100">
    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <p class="text-sm font-bold text-green-600">User account created successfully.</p>
</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100">
    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    <p class="text-sm font-bold text-red-600">User account deleted.</p>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100 animate-headShake">
    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <p class="text-sm font-bold text-red-600"><?php echo escape($error); ?></p>
</div>
<?php endif; ?>

<?php if ($edit): ?>
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 mb-10 max-w-3xl">
    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-50">
        <div class="w-10 h-10 rounded-xl bg-kmf-orange/10 flex items-center justify-center text-kmf-orange">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-kmf-blue font-montserrat"><?php echo $edit['id'] ? 'Edit Administrative User' : 'Create Administrative User'; ?></h3>
    </div>

    <form method="post" class="space-y-6">
        <?php echo csrfField(); ?>
        <input type="hidden" name="id" value="<?php echo (int)($edit['id'] ?? 0); ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Username</label>
                <input type="text" name="username" value="<?php echo escape($edit['username']); ?>" required 
                    <?php echo ($edit['username'] === 'admin') ? 'readonly class="w-full px-6 py-4 bg-slate-100 border border-slate-200 rounded-2xl outline-none font-medium text-slate-400 cursor-not-allowed"' : 'class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"'; ?>
                    placeholder="e.g., john.doe">
            </div>
            
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Email Address</label>
                <input type="email" name="email" value="<?php echo escape($edit['email']); ?>" required
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                    placeholder="e.g., user@kmf.org.np">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Assign IAM Role</label>
                <?php if ($edit['username'] === 'admin'): ?>
                    <input type="hidden" name="role_id" value="1">
                    <input type="text" readonly value="Super Admin" class="w-full px-6 py-4 bg-slate-100 border border-slate-200 rounded-2xl outline-none font-medium text-slate-400 cursor-not-allowed">
                <?php elseif ($edit['id'] === (int)$_SESSION['admin_id']): ?>
                    <input type="hidden" name="role_id" value="<?php echo (int)$edit['role_id']; ?>">
                    <input type="text" readonly value="Super Admin (Cannot Demote Self)" class="w-full px-6 py-4 bg-slate-100 border border-slate-200 rounded-2xl outline-none font-medium text-slate-400 cursor-not-allowed">
                <?php else: ?>
                    <select name="role_id" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                        <option value="">No Role Assigned (Blocked)</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?php echo $r['id']; ?>" <?php echo ($edit['role_id'] == $r['id']) ? 'selected' : ''; ?>><?php echo escape($r['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Account Status</label>
                <?php if ($edit['username'] === 'admin' || $edit['id'] === (int)$_SESSION['admin_id']): ?>
                    <input type="hidden" name="status" value="active">
                    <input type="text" readonly value="Active (Cannot Deactivate Self/System Admin)" class="w-full px-6 py-4 bg-slate-100 border border-slate-200 rounded-2xl outline-none font-medium text-slate-400 cursor-not-allowed">
                <?php else: ?>
                    <select name="status" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                        <option value="active" <?php echo ($edit['status'] === 'active') ? 'selected' : ''; ?>>Active (Allowed Login)</option>
                        <option value="inactive" <?php echo ($edit['status'] === 'inactive') ? 'selected' : ''; ?>>Suspended (Blocked)</option>
                    </select>
                <?php endif; ?>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-6">
            <h4 class="text-sm font-bold text-kmf-blue mb-4 uppercase tracking-wider">
                <?php echo $edit['id'] ? 'Reset Password (Leave blank to keep current)' : 'Account Password'; ?>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" <?php echo $edit['id'] ? '' : 'required'; ?>
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                        placeholder="••••••••">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Confirm Password</label>
                    <input type="password" name="confirm_password" <?php echo $edit['id'] ? '' : 'required'; ?>
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                        placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="pt-6 flex flex-col md:flex-row gap-4">
            <button type="submit" class="flex-1 bg-kmf-blue hover:bg-kmf-blue-dark text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-kmf-blue/20 transition-all duration-300 transform hover:-translate-y-1">
                Save User Account
            </button>
            <a href="users.php" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold py-5 rounded-2xl text-center transition-all flex items-center justify-center">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3">
            <thead>
                <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">User Identity</th>
                    <th class="px-6 py-4">Role / Permissions</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr class="group hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-5 bg-slate-50/30 first:rounded-l-[2rem] border-y border-l border-slate-50">
                        <?php if (($u['status'] ?? 'active') === 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wider">
                                Active
                             </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 uppercase tracking-wider">
                                Suspended
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-5 bg-slate-50/30 border-y border-slate-50">
                        <div>
                            <p class="text-sm font-bold text-kmf-blue leading-none">
                                <?php echo escape($u['username']); ?>
                                <?php if ($u['id'] === (int)$_SESSION['admin_id']): ?>
                                    <span class="text-[9px] font-black text-kmf-orange ml-1 bg-kmf-orange/10 px-2 py-0.5 rounded-full uppercase tracking-wider">You</span>
                                <?php endif; ?>
                            </p>
                            <p class="text-[10px] font-medium text-slate-400 mt-1.5"><?php echo escape($u['email']); ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-5 bg-slate-50/30 border-y border-slate-50">
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-xl">
                            <?php echo escape($u['role_name'] ?: 'None (No Access)'); ?>
                        </span>
                    </td>
                    <td class="px-6 py-5 bg-slate-50/30 last:rounded-r-[2rem] border-y border-r border-slate-50 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="?edit=<?php echo $u['id']; ?>" class="p-2.5 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-kmf-orange hover:shadow-sm transition-all" title="Edit Account">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            
                            <?php if ($u['id'] !== (int)$_SESSION['admin_id'] && $u['username'] !== 'admin'): ?>
                                <a href="?delete=<?php echo $u['id']; ?>&csrf=<?php echo $_SESSION['csrf_token'] ?? ''; ?>" 
                                   onclick="return confirm('Are you sure you want to delete user <?php echo escape($u['username']); ?>? This action is irreversible.');"
                                   class="p-2.5 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-red-500 hover:shadow-sm transition-all" title="Delete User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

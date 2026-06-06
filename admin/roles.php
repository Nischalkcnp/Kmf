<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_iam');

$adminTitle = 'Roles & IAM';
$pdo = getDb();

$error = '';
$success = '';

// Fetch all permissions
$permissions = $pdo->query("SELECT * FROM permissions ORDER BY code_name ASC")->fetchAll();

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
        if (!$edit) {
            redirect(BASE_URL . 'admin/roles.php');
        }
        
        // Fetch current permission IDs for this role
        $stmt_perms = $pdo->prepare("SELECT permission_id FROM role_permissions WHERE role_id = ?");
        $stmt_perms->execute([$id]);
        $edit['permission_ids'] = $stmt_perms->fetchAll(PDO::FETCH_COLUMN) ?: [];
    } else {
        $edit = ['id' => 0, 'name' => '', 'description' => '', 'is_system' => 0, 'permission_ids' => []];
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $selected_permissions = $_POST['permissions'] ?? []; // Array of permission IDs

    if (!$name) {
        $error = 'Role name is required.';
    } else {
        // Check uniqueness of role name
        $check = $pdo->prepare("SELECT id FROM roles WHERE name = ? AND id != ?");
        $check->execute([$name, $id]);
        if ($check->fetch()) {
            $error = 'Role name is already in use.';
        } else {
            // Safety: Protect built-in roles
            $isSystem = 0;
            if ($id > 0) {
                $stmtSys = $pdo->prepare("SELECT name, is_system FROM roles WHERE id = ?");
                $stmtSys->execute([$id]);
                $sysRole = $stmtSys->fetch();
                if ($sysRole && $sysRole['is_system']) {
                    $name = $sysRole['name']; // cannot rename system role
                    $isSystem = 1;
                }
            }

            $pdo->beginTransaction();
            try {
                if ($id > 0) {
                    // Update role
                    $stmt = $pdo->prepare("UPDATE roles SET name = ?, description = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $id]);
                } else {
                    // Insert role
                    $stmt = $pdo->prepare("INSERT INTO roles (name, description, is_system) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $description, 0]);
                    $id = $pdo->lastInsertId();
                }

                // Update permissions mapping (unless it's Super Admin which always has all permissions)
                if ($name !== 'Super Admin') {
                    $del_perms = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = ?");
                    $del_perms->execute([$id]);

                    if (!empty($selected_permissions)) {
                        $ins_perm = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
                        foreach ($selected_permissions as $perm_id) {
                            $ins_perm->execute([$id, (int)$perm_id]);
                        }
                    }
                } else {
                    // Super Admin always gets all permissions
                    $del_perms = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = ?");
                    $del_perms->execute([$id]);
                    $ins_all = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) SELECT ?, id FROM permissions");
                    $ins_all->execute([$id]);
                }

                $pdo->commit();
                
                // Clear session cache for current user in case they updated their own role
                unset($_SESSION['admin_permissions']);
                unset($_SESSION['admin_role']);

                redirect(BASE_URL . 'admin/roles.php?updated=1');
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Failed to save role: ' . $e->getMessage();
            }
        }
    }
}

// Handle Delete Role
if (isset($_GET['delete'])) {
    if (isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token'] ?? '', $_GET['csrf'])) {
        $delId = (int)$_GET['delete'];
        
        // Verify role is not a system role
        $stmt = $pdo->prepare("SELECT is_system FROM roles WHERE id = ?");
        $stmt->execute([$delId]);
        $role = $stmt->fetch();
        
        if ($role && $role['is_system']) {
            $error = 'Built-in system roles cannot be deleted.';
        } else {
            // Delete role (users with this role will automatically get set to NULL due to ON DELETE SET NULL constraint)
            $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
            $stmt->execute([$delId]);
            redirect(BASE_URL . 'admin/roles.php?deleted=1');
        }
    }
}

// Fetch all roles with counts of users assigned
$roles_list = $pdo->query("
    SELECT r.*, COUNT(u.id) as user_count 
    FROM roles r 
    LEFT JOIN admin_users u ON r.id = u.role_id 
    GROUP BY r.id 
    ORDER BY r.name ASC
")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight font-montserrat">Roles & Access Control (IAM)</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Configure user roles and modify their access permissions</p>
    </div>
    <?php if (!$edit): ?>
    <a href="?edit=0" class="inline-flex items-center gap-2 bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-6 py-3 rounded-2xl shadow-lg shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create New Role
    </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['updated'])): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100">
    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <p class="text-sm font-bold text-green-600">Role configuration saved successfully.</p>
</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100">
    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    <p class="text-sm font-bold text-red-600">Role deleted successfully.</p>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100 animate-headShake">
    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <p class="text-sm font-bold text-red-600"><?php echo escape($error); ?></p>
</div>
<?php endif; ?>

<?php if ($edit): ?>
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 mb-10 max-w-4xl">
    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-50">
        <div class="w-10 h-10 rounded-xl bg-kmf-orange/10 flex items-center justify-center text-kmf-orange">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-kmf-blue font-montserrat"><?php echo $edit['id'] ? 'Edit Access Role' : 'Create Custom Role'; ?></h3>
    </div>

    <form method="post" class="space-y-6">
        <?php echo csrfField(); ?>
        <input type="hidden" name="id" value="<?php echo (int)($edit['id'] ?? 0); ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Role Name</label>
                <input type="text" name="name" value="<?php echo escape($edit['name']); ?>" required
                    <?php echo $edit['is_system'] ? 'readonly class="w-full px-6 py-4 bg-slate-100 border border-slate-200 rounded-2xl outline-none font-medium text-slate-400 cursor-not-allowed"' : 'class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"'; ?>
                    placeholder="e.g., Content Administrator">
            </div>
            
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Description</label>
                <input type="text" name="description" value="<?php echo escape($edit['description']); ?>" required
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                    placeholder="Brief description of this role's purpose">
            </div>
        </div>

        <div class="border-t border-slate-100 pt-6">
            <h4 class="text-sm font-bold text-kmf-blue mb-2 uppercase tracking-wider">Configure Access Permissions</h4>
            <p class="text-xs text-slate-400 font-medium mb-6">Select which operations users assigned to this role can execute.</p>
            
            <?php if ($edit['name'] === 'Super Admin'): ?>
                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl">
                    <p class="text-sm font-semibold text-kmf-blue flex items-center gap-2">
                        <svg class="w-5 h-5 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Super Admins have full access to all system options. Permissions cannot be restricted for this role.
                    </p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($permissions as $p): ?>
                        <label class="flex items-start gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100/50 hover:border-slate-200 transition-all select-none">
                            <input type="checkbox" name="permissions[]" value="<?php echo $p['id']; ?>" 
                                <?php echo in_array($p['id'], $edit['permission_ids']) ? 'checked' : ''; ?>
                                class="w-5 h-5 mt-0.5 rounded border-slate-300 text-kmf-orange focus:ring-kmf-orange/20">
                            <div>
                                <p class="text-sm font-bold text-kmf-blue leading-none"><?php echo escape($p['name']); ?></p>
                                <p class="text-[10px] font-medium text-slate-400 mt-1"><?php echo escape($p['description']); ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="pt-6 flex flex-col md:flex-row gap-4">
            <button type="submit" class="flex-1 bg-kmf-blue hover:bg-kmf-blue-dark text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-kmf-blue/20 transition-all duration-300 transform hover:-translate-y-1">
                Save Role Details
            </button>
            <a href="roles.php" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold py-5 rounded-2xl text-center transition-all flex items-center justify-center">
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
                    <th class="px-6 py-4">Role Name</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">Assigned Users</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles_list as $rl): ?>
                <tr class="group hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-5 bg-slate-50/30 first:rounded-l-[2rem] border-y border-l border-slate-50">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-kmf-blue"><?php echo escape($rl['name']); ?></span>
                            <?php if ($rl['is_system']): ?>
                                <span class="text-[9px] font-black text-slate-400 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-full uppercase tracking-wider">System</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-5 bg-slate-50/30 border-y border-slate-50">
                        <span class="text-xs text-slate-500 font-medium"><?php echo escape($rl['description']); ?></span>
                    </td>
                    <td class="px-6 py-5 bg-slate-50/30 border-y border-slate-50">
                        <span class="text-xs font-bold text-kmf-blue bg-kmf-blue/10 px-2.5 py-1 rounded-xl">
                            <?php echo (int)$rl['user_count']; ?> User(s)
                        </span>
                    </td>
                    <td class="px-6 py-5 bg-slate-50/30 last:rounded-r-[2rem] border-y border-r border-slate-50 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="?edit=<?php echo $rl['id']; ?>" class="p-2.5 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-kmf-orange hover:shadow-sm transition-all" title="Edit Role">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            
                            <?php if (!$rl['is_system']): ?>
                                <a href="?delete=<?php echo $rl['id']; ?>&csrf=<?php echo $_SESSION['csrf_token'] ?? ''; ?>" 
                                   onclick="return confirm('Are you sure you want to delete role <?php echo escape($rl['name']); ?>? Assigned users will be set to no role.');"
                                   class="p-2.5 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-red-500 hover:shadow-sm transition-all" title="Delete Role">
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

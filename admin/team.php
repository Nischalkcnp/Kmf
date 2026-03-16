<?php
require_once dirname(__DIR__) . '/config/config.php';
requireLogin();
$adminTitle = 'Team & Partners';

$pdo = getDb();
$team = $pdo->query("SELECT * FROM team ORDER BY type, sort_order")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners ORDER BY sort_order")->fetchAll();

$editTeam = null;
$editPartner = null;
if (isset($_GET['edit_team'])) {
    $id = (int)$_GET['edit_team'];
    if ($id === 0) $editTeam = ['id'=>0,'name'=>'','role'=>'','bio'=>'','image_url'=>'','type'=>'staff','sort_order'=>0,'is_active'=>1];
    else { $stmt = $pdo->prepare("SELECT * FROM team WHERE id = ?"); $stmt->execute([$id]); $editTeam = $stmt->fetch(); }
}
if (isset($_GET['edit_partner'])) {
    $id = (int)$_GET['edit_partner'];
    if ($id === 0) $editPartner = ['id'=>0,'name'=>'','logo_url'=>'','link_url'=>'','sort_order'=>0,'is_active'=>1];
    else { $stmt = $pdo->prepare("SELECT * FROM partners WHERE id = ?"); $stmt->execute([$id]); $editPartner = $stmt->fetch(); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'team') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        $type = in_array($_POST['type'] ?? '', ['board','staff']) ? $_POST['type'] : 'staff';
        $sort = (int)($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($id) {
            $stmt = $pdo->prepare("UPDATE team SET name=?, role=?, bio=?, image_url=?, type=?, sort_order=?, is_active=? WHERE id=?");
            $stmt->execute([$name, $role, $bio, $image_url, $type, $sort, $active, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO team (name, role, bio, image_url, type, sort_order, is_active) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$name, $role, $bio, $image_url, $type, $sort, $active]);
        }
    }
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'partner') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $logo_url = trim($_POST['logo_url'] ?? '');
        $link_url = trim($_POST['link_url'] ?? '');
        $sort = (int)($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($id) {
            $stmt = $pdo->prepare("UPDATE partners SET name=?, logo_url=?, link_url=?, sort_order=?, is_active=? WHERE id=?");
            $stmt->execute([$name, $logo_url, $link_url, $sort, $active, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO partners (name, logo_url, link_url, sort_order, is_active) VALUES (?,?,?,?,?)");
            $stmt->execute([$name, $logo_url, $link_url, $sort, $active]);
        }
    }
    redirect(BASE_URL . 'admin/team.php?updated=1');
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Team & Partners</h1>
<?php if (isset($_GET['updated'])): ?><p class="text-green-600 mb-4">Saved.</p><?php endif; ?>

<h2 class="text-lg font-semibold mt-8 mb-2">Team</h2>
<?php if ($editTeam !== null): ?>
<form method="post" class="bg-white rounded-lg shadow p-6 mb-6 max-w-2xl">
    <?php echo csrfField(); ?>
    <input type="hidden" name="form_type" value="team">
    <input type="hidden" name="id" value="<?php echo (int)$editTeam['id']; ?>">
    <div class="space-y-4">
        <div><label class="block text-sm font-medium mb-1">Name</label><input type="text" name="name" value="<?php echo escape($editTeam['name']); ?>" class="w-full px-4 py-2 border rounded-lg" required></div>
        <div><label class="block text-sm font-medium mb-1">Role</label><input type="text" name="role" value="<?php echo escape($editTeam['role'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Bio</label><textarea name="bio" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($editTeam['bio'] ?? ''); ?></textarea></div>
        <div><label class="block text-sm font-medium mb-1">Image URL</label><input type="text" name="image_url" value="<?php echo escape($editTeam['image_url'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Type</label><select name="type" class="w-full px-4 py-2 border rounded-lg"><option value="board" <?php echo ($editTeam['type']??'')==='board'?'selected':''; ?>>Board</option><option value="staff" <?php echo ($editTeam['type']??'')==='staff'?'selected':''; ?>>Staff</option></select></div>
        <div><label class="block text-sm font-medium mb-1">Sort</label><input type="number" name="sort_order" value="<?php echo (int)($editTeam['sort_order'] ?? 0); ?>" class="w-24 px-4 py-2 border rounded-lg"></div>
        <div><label><input type="checkbox" name="is_active" value="1" <?php echo ($editTeam['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label></div>
    </div>
    <div class="mt-4"><button type="submit" class="bg-kmf-orange text-white px-4 py-2 rounded-lg">Save</button> <a href="<?php echo BASE_URL; ?>admin/team.php" class="text-gray-600 ml-2">Cancel</a></div>
</form>
<?php else: ?>
<p class="mb-2"><a href="?edit_team=0" class="bg-kmf-orange text-white px-4 py-2 rounded-lg inline-block text-sm">Add Team Member</a></p>
<?php endif; ?>
<table class="bg-white rounded-lg shadow overflow-hidden w-full max-w-2xl">
    <thead class="bg-gray-100"><tr><th class="text-left p-3">Name</th><th class="text-left p-3">Role</th><th class="text-left p-3">Type</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
        <?php foreach ($team as $t): ?>
        <tr class="border-t"><td class="p-3"><?php echo escape($t['name']); ?></td><td class="p-3"><?php echo escape($t['role'] ?? '-'); ?></td><td class="p-3"><?php echo escape($t['type']); ?></td><td class="p-3"><a href="?edit_team=<?php echo $t['id']; ?>" class="text-kmf-orange hover:underline">Edit</a></td></tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2 class="text-lg font-semibold mt-12 mb-2">Partners</h2>
<?php if ($editPartner !== null): ?>
<form method="post" class="bg-white rounded-lg shadow p-6 mb-6 max-w-2xl">
    <?php echo csrfField(); ?>
    <input type="hidden" name="form_type" value="partner">
    <input type="hidden" name="id" value="<?php echo (int)$editPartner['id']; ?>">
    <div class="space-y-4">
        <div><label class="block text-sm font-medium mb-1">Name</label><input type="text" name="name" value="<?php echo escape($editPartner['name']); ?>" class="w-full px-4 py-2 border rounded-lg" required></div>
        <div><label class="block text-sm font-medium mb-1">Logo URL</label><input type="text" name="logo_url" value="<?php echo escape($editPartner['logo_url'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Link URL</label><input type="url" name="link_url" value="<?php echo escape($editPartner['link_url'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Sort</label><input type="number" name="sort_order" value="<?php echo (int)($editPartner['sort_order'] ?? 0); ?>" class="w-24 px-4 py-2 border rounded-lg"></div>
        <div><label><input type="checkbox" name="is_active" value="1" <?php echo ($editPartner['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label></div>
    </div>
    <div class="mt-4"><button type="submit" class="bg-kmf-orange text-white px-4 py-2 rounded-lg">Save</button> <a href="<?php echo BASE_URL; ?>admin/team.php" class="text-gray-600 ml-2">Cancel</a></div>
</form>
<?php else: ?>
<p class="mb-2"><a href="?edit_partner=0" class="bg-kmf-orange text-white px-4 py-2 rounded-lg inline-block text-sm">Add Partner</a></p>
<?php endif; ?>
<table class="bg-white rounded-lg shadow overflow-hidden w-full max-w-2xl">
    <thead class="bg-gray-100"><tr><th class="text-left p-3">Name</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
        <?php foreach ($partners as $p): ?>
        <tr class="border-t"><td class="p-3"><?php echo escape($p['name']); ?></td><td class="p-3"><a href="?edit_partner=<?php echo $p['id']; ?>" class="text-kmf-orange hover:underline">Edit</a></td></tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

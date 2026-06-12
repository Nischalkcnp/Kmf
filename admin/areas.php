<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_areas');
$adminTitle = 'Strategic Areas';

$pdo = getDb();

// Toggle Status Handler
if (isset($_GET['toggle_status'])) {
    $toggleId = (int)$_GET['toggle_status'];
    $stmt = $pdo->prepare("UPDATE strategic_areas SET is_active = 1 - is_active WHERE id = ?");
    $stmt->execute([$toggleId]);
    redirect(BASE_URL . 'admin/areas.php?updated=1');
}

function handleMultipleImageUploads(array $fileArrayItem, string $subfolder = 'areas'): ?string {
    if ($fileArrayItem['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $uploadDir = dirname(__DIR__) . '/assets/images/' . trim($subfolder, '/') . '/';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }

    $ext = strtolower(pathinfo($fileArrayItem['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (!in_array($ext, $allowed)) {
        return null;
    }

    $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($fileArrayItem['tmp_name'], $targetPath)) {
        return 'assets/images/' . trim($subfolder, '/') . '/' . $filename;
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $icon = trim($_POST['icon'] ?? '');
    $image_url = handleImageUpload('image_file', 'areas', $_POST['current_image_url'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($id) {
        $stmt = $pdo->prepare("UPDATE strategic_areas SET title=?, slug=?, excerpt=?, content=?, icon=?, image_url=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $content, $icon, $image_url, $sort, $active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO strategic_areas (title, slug, excerpt, content, icon, image_url, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $icon, $image_url, $sort, $active]);
        $id = (int)$pdo->lastInsertId();
    }

    // Process gallery photo deletions
    if (!empty($_POST['delete_photo_ids']) && is_array($_POST['delete_photo_ids'])) {
        $deletePhotoIds = array_map('intval', $_POST['delete_photo_ids']);
        $placeholders = implode(',', array_fill(0, count($deletePhotoIds), '?'));
        
        // Find URLs of files to delete
        $stmt = $pdo->prepare("SELECT image_url FROM strategic_area_photos WHERE id IN ($placeholders)");
        $stmt->execute($deletePhotoIds);
        $photosToDelete = $stmt->fetchAll();
        foreach ($photosToDelete as $pToDelete) {
            $filePath = dirname(__DIR__) . '/' . $pToDelete['image_url'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        
        // Delete records
        $stmt = $pdo->prepare("DELETE FROM strategic_area_photos WHERE id IN ($placeholders)");
        $stmt->execute($deletePhotoIds);
    }

    // Process gallery uploads
    if (!empty($_FILES['gallery_files']['name'][0])) {
        $files = $_FILES['gallery_files'];
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileItem = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];
            $uploadedUrl = handleMultipleImageUploads($fileItem, 'areas');
            if ($uploadedUrl) {
                $stmt = $pdo->prepare("INSERT INTO strategic_area_photos (area_id, image_url) VALUES (?, ?)");
                $stmt->execute([$id, $uploadedUrl]);
            }
        }
    }
    
    redirect(BASE_URL . 'admin/areas.php?updated=1');
}

$areas = $pdo->query("SELECT * FROM strategic_areas ORDER BY sort_order, id")->fetchAll();
$edit = null;
$galleryPhotos = [];

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM strategic_areas WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    
    if ($edit) {
        $stmt = $pdo->prepare("SELECT * FROM strategic_area_photos WHERE area_id = ? ORDER BY sort_order, id");
        $stmt->execute([$edit['id']]);
        $galleryPhotos = $stmt->fetchAll();
    }
} elseif (isset($_GET['add'])) {
    $edit = [
        'id' => 0,
        'title' => '',
        'slug' => '',
        'excerpt' => '',
        'content' => '',
        'icon' => '',
        'image_url' => '',
        'sort_order' => 0,
        'is_active' => 1
    ];
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Strategic Areas</h1>
<?php if (isset($_GET['updated'])): ?><p class="text-green-600 mb-4">Saved.</p><?php endif; ?>

<?php if ($edit): ?>
<form method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 mb-6 max-w-3xl border border-gray-200">
    <?php echo csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
    <input type="hidden" name="current_image_url" value="<?php echo escape($edit['image_url'] ?? ''); ?>">
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-kmf-blue border-b pb-2"><?php echo $edit['id'] ? 'Edit Strategic Area' : 'Add Strategic Area'; ?></h2>
        
        <div><label class="block text-sm font-medium mb-1">Title</label><input type="text" name="title" value="<?php echo escape($edit['title']); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-kmf-blue/20 outline-none" required></div>
        <div><label class="block text-sm font-medium mb-1">Slug</label><input type="text" name="slug" value="<?php echo escape($edit['slug']); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-kmf-blue/20 outline-none" placeholder="e.g. education (leave blank to auto-generate)"></div>
        <div><label class="block text-sm font-medium mb-1">Excerpt</label><textarea name="excerpt" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-kmf-blue/20 outline-none"><?php echo escape($edit['excerpt']); ?></textarea></div>
        <div><label class="block text-sm font-medium mb-1">Content (HTML)</label><textarea name="content" rows="6" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-kmf-blue/20 outline-none"><?php echo escape($edit['content']); ?></textarea></div>
        <div class="flex gap-4">
            <div class="flex-1"><label class="block text-sm font-medium mb-1">Icon (education|people|health|home|environment)</label><input type="text" name="icon" value="<?php echo escape($edit['icon']); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-kmf-blue/20 outline-none"></div>
            <div class="w-32"><label class="block text-sm font-medium mb-1">Sort Order</label><input type="number" name="sort_order" value="<?php echo (int)$edit['sort_order']; ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-kmf-blue/20 outline-none"></div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Main Cover Image (Upload file)</label>
            <?php if (!empty($edit['image_url'])): ?>
                <div class="mt-2 text-sm text-gray-500">Current Preview:</div>
                <img src="<?php echo BASE_URL . $edit['image_url']; ?>" class="mt-1 h-32 w-auto rounded border" alt="Preview">
            <?php endif; ?>
            <input type="file" name="image_file" class="w-full text-sm mt-2">
        </div>

        <!-- Gallery Photos Section -->
        <div class="border-t pt-4">
            <h3 class="text-md font-bold text-kmf-blue mb-3">Area Photo Section (Gallery)</h3>
            
            <?php if (!empty($galleryPhotos)): ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                    <?php foreach ($galleryPhotos as $photo): ?>
                        <div class="relative group border rounded-lg p-2 bg-gray-50 text-center">
                            <img src="<?php echo BASE_URL . $photo['image_url']; ?>" class="h-24 w-full object-cover rounded mb-2" alt="Gallery photo">
                            <label class="inline-flex items-center text-xs text-red-600 cursor-pointer font-semibold hover:text-red-800">
                                <input type="checkbox" name="delete_photo_ids[]" value="<?php echo $photo['id']; ?>" class="mr-1"> Remove
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm text-gray-500 italic mb-4">No gallery photos uploaded yet. Upload at least 4 photos for this area.</p>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium mb-1">Upload New Gallery Photos (Multiple files allowed)</label>
                <input type="file" name="gallery_files[]" class="w-full text-sm mt-2" multiple>
                <p class="text-xs text-gray-400 mt-1">Select one or more images to show in the frontend lightbox gallery.</p>
            </div>
        </div>

        <div>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?php echo $edit['is_active'] ? 'checked' : ''; ?> class="rounded text-kmf-orange focus:ring-kmf-orange border-gray-300">
                <span class="ml-2 text-sm font-medium text-gray-700">Active (Visible on Website)</span>
            </label>
        </div>
    </div>
    <div class="mt-6 border-t pt-4 flex items-center justify-end gap-2">
        <a href="<?php echo BASE_URL; ?>admin/areas.php" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-semibold transition-colors">Cancel</a>
        <button type="submit" class="bg-kmf-orange hover:bg-kmf-orange-light text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors shadow-md">Save Changes</button>
    </div>
</form>
<?php endif; ?>

<div class="flex justify-between items-center mb-4 max-w-3xl">
    <h2 class="text-lg font-semibold text-kmf-blue">All Strategic Areas</h2>
    <a href="?add=1" class="bg-kmf-orange hover:bg-kmf-orange-light text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors shadow-sm">+ Add Strategic Area</a>
</div>

<table class="bg-white rounded-lg shadow overflow-hidden w-full max-w-3xl border">
    <thead class="bg-gray-100">
        <tr>
            <th class="text-left p-3 text-sm font-bold text-gray-600">Title</th>
            <th class="text-left p-3 text-sm font-bold text-gray-600">Slug</th>
            <th class="p-3 text-center text-sm font-bold text-gray-600 w-24">Status</th>
            <th class="p-3 text-center text-sm font-bold text-gray-600 w-20">Sort</th>
            <th class="p-3 text-center text-sm font-bold text-gray-600 w-24">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($areas as $a): ?>
        <tr class="border-t hover:bg-gray-50 transition-colors">
            <td class="p-3 text-sm font-semibold text-gray-700">
                <div class="flex items-center gap-3">
                    <?php if ($a['image_url']): ?>
                        <img src="<?php echo BASE_URL . $a['image_url']; ?>" class="w-10 h-10 object-cover rounded shadow-sm" alt="">
                    <?php else: ?>
                        <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    <?php endif; ?>
                    <?php echo escape($a['title']); ?>
                </div>
            </td>
            <td class="p-3 text-sm text-gray-500"><?php echo escape($a['slug']); ?></td>
            <td class="p-3 text-center text-sm">
                <?php if ($a['is_active']): ?>
                    <a href="?toggle_status=<?php echo $a['id']; ?>" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 hover:bg-green-200 transition-colors" title="Click to disable">
                        Active
                    </a>
                <?php else: ?>
                    <a href="?toggle_status=<?php echo $a['id']; ?>" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 hover:bg-red-200 transition-colors" title="Click to enable">
                        Inactive
                    </a>
                <?php endif; ?>
            </td>
            <td class="p-3 text-center text-sm text-gray-700"><?php echo (int)$a['sort_order']; ?></td>
            <td class="p-3 text-center text-sm">
                <a href="?edit=<?php echo $a['id']; ?>" class="text-kmf-orange hover:text-kmf-orange-light font-bold transition-colors">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

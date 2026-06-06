<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_news');
$adminTitle = 'Media Gallery';

$pdo = getDb();
$error = '';
$success = '';

// Helper to extract YouTube ID
function getYouTubeId($url) {
    $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
    if (preg_match($pattern, $url, $match)) {
        return $match[1];
    }
    return null;
}

// Fetch all active programs for the selection dropdown
$programs = $pdo->query("SELECT id, title FROM programs WHERE is_active = 1 ORDER BY title ASC")->fetchAll();

// Handle Delete Media
if (isset($_GET['delete'])) {
    if (isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token'] ?? '', $_GET['csrf'])) {
        $delId = (int)$_GET['delete'];
        
        // Fetch item first to delete local uploaded image if exists
        $stmt = $pdo->prepare("SELECT image_url FROM gallery WHERE id = ?");
        $stmt->execute([$delId]);
        $img = $stmt->fetchColumn();
        
        if ($img && strpos($img, 'assets/images/gallery/') === 0) {
            $fullPath = dirname(__DIR__) . '/' . $img;
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
        
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$delId]);
        redirect(BASE_URL . 'admin/gallery.php?deleted=1');
    } else {
        $error = 'Invalid security token.';
    }
}

// Initialize edit form variables
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
        if (!$edit) {
            redirect(BASE_URL . 'admin/gallery.php');
        }
    } else {
        $edit = [
            'id' => 0,
            'title' => '',
            'program_id' => '',
            'image_url' => '',
            'category' => 'photo',
            'video_url' => '',
            'sort_order' => 0,
            'is_active' => 1
        ];
    }
}

// Handle Add/Edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $category = in_array($_POST['category'] ?? '', ['photo', 'video']) ? $_POST['category'] : 'photo';
    $program_id = !empty($_POST['program_id']) ? (int)$_POST['program_id'] : null;
    $video_url = trim($_POST['video_url'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;
    
    $current_image = $_POST['current_image_url'] ?? '';
    
    // Upload image
    $uploaded_image = handleImageUpload('image_file', 'gallery', $current_image);
    
    // Auto-resolve video thumbnail from YouTube if no custom image is uploaded
    if ($category === 'video' && !empty($video_url) && empty($uploaded_image)) {
        $ytId = getYouTubeId($video_url);
        if ($ytId) {
            $uploaded_image = "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg";
        }
    }
    
    // Validations
    if ($category === 'photo' && empty($uploaded_image)) {
        $error = 'Please upload an image file for the photo.';
    } elseif ($category === 'video' && empty($video_url)) {
        $error = 'Please enter a video URL.';
    } elseif ($category === 'video' && !getYouTubeId($video_url)) {
        $error = 'Please enter a valid YouTube URL.';
    } else {
        if ($id > 0) {
            // Update
            $stmt = $pdo->prepare("UPDATE gallery SET title = ?, program_id = ?, image_url = ?, category = ?, video_url = ?, sort_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $program_id, $uploaded_image, $category, $video_url, $sort, $active, $id]);
            redirect(BASE_URL . 'admin/gallery.php?updated=1');
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO gallery (title, program_id, image_url, category, video_url, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $program_id, $uploaded_image, $category, $video_url, $sort, $active]);
            redirect(BASE_URL . 'admin/gallery.php?created=1');
        }
    }
}

// Fetch all gallery items
$items = $pdo->query("
    SELECT g.*, p.title as program_title 
    FROM gallery g 
    LEFT JOIN programs p ON g.program_id = p.id 
    ORDER BY g.sort_order ASC, g.id DESC
")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Media Gallery</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Manage project-wise photos and videos displayed on the website</p>
    </div>
    <?php if (!$edit): ?>
    <a href="?edit=0" class="inline-flex items-center gap-2 bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-6 py-3 rounded-2xl shadow-lg shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Media Item
    </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['updated'])): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100 animate-fadeIn">
    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <p class="text-sm font-bold text-green-600">Gallery item updated successfully.</p>
</div>
<?php endif; ?>

<?php if (isset($_GET['created'])): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100 animate-fadeIn">
    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <p class="text-sm font-bold text-green-600">Gallery item added successfully.</p>
</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100 animate-fadeIn">
    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    <p class="text-sm font-bold text-red-600">Gallery item deleted successfully.</p>
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
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-kmf-blue font-montserrat"><?php echo $edit['id'] ? 'Edit Media Item' : 'Add Media Item'; ?></h3>
    </div>

    <form method="post" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrfField(); ?>
        <input type="hidden" name="id" value="<?php echo (int)($edit['id'] ?? 0); ?>">
        <input type="hidden" name="current_image_url" value="<?php echo escape($edit['image_url'] ?? ''); ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Media Title</label>
                <input type="text" name="title" value="<?php echo escape($edit['title']); ?>"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                    placeholder="e.g., School Building Construction Progress" required>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Project / Program wise</label>
                <select name="program_id" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue" required>
                    <option value="">-- Select Project --</option>
                    <?php foreach ($programs as $prog): ?>
                        <option value="<?php echo $prog['id']; ?>" <?php echo ($edit['program_id'] == $prog['id']) ? 'selected' : ''; ?>><?php echo escape($prog['title']); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-[10px] text-slate-400 font-bold ml-1 uppercase">Select the project this media belongs to.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Category</label>
                <select name="category" id="category_select" onchange="toggleCategoryFields(this.value)"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                    <option value="photo" <?php echo ($edit['category'] === 'photo') ? 'selected' : ''; ?>>Photo</option>
                    <option value="video" <?php echo ($edit['category'] === 'video') ? 'selected' : ''; ?>>Video</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo (int)($edit['sort_order'] ?? 0); ?>"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                    placeholder="0">
            </div>
        </div>

        <!-- Video fields -->
        <div id="video_url_field" class="space-y-2 <?php echo ($edit['category'] === 'video') ? '' : 'hidden'; ?>">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">YouTube Video URL</label>
            <input type="url" name="video_url" id="video_url_input" value="<?php echo escape($edit['video_url'] ?? ''); ?>"
                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ">
            <p class="text-[10px] text-slate-400 font-bold ml-1 uppercase">Supports standard YouTube or Shorts URLs.</p>
        </div>

        <!-- Upload Image field -->
        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1" id="image_label">
                <?php echo ($edit['category'] === 'video') ? 'Custom Video Thumbnail (Optional)' : 'Photo Upload'; ?>
            </label>
            <?php if (!empty($edit['image_url'])): ?>
                <div class="mt-2 flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <img src="<?php echo (strpos($edit['image_url'], 'http') === 0) ? $edit['image_url'] : BASE_URL . $edit['image_url']; ?>" 
                         class="h-24 w-36 object-cover rounded-xl shadow-sm border border-slate-200" alt="Current Image">
                    <div>
                        <p class="text-xs font-bold text-kmf-blue">Current Image File</p>
                        <p class="text-[10px] text-slate-400 font-mono overflow-hidden truncate max-w-[250px]"><?php echo basename($edit['image_url']); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <div class="mt-2">
                <input type="file" name="image_file" class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-kmf-blue/5 file:text-kmf-blue hover:file:bg-kmf-blue/10 transition-colors">
            </div>
            <p class="text-[10px] text-slate-400 font-bold ml-1 uppercase" id="image_help_text">
                <?php echo ($edit['category'] === 'video') ? 'Leave blank to auto-fetch the YouTube high-resolution thumbnail.' : 'Supported formats: JPG, JPEG, PNG, WEBP, GIF.'; ?>
            </p>
        </div>

        <div class="space-y-2">
            <label class="inline-flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?php echo ($edit['is_active'] ?? 1) ? 'checked' : ''; ?>
                    class="rounded text-kmf-orange focus:ring-kmf-orange border-slate-300 w-5 h-5">
                <span class="text-sm font-bold text-slate-600">Active (Visible on public website)</span>
            </label>
        </div>

        <div class="pt-6 flex flex-col md:flex-row gap-4">
            <button type="submit" class="flex-1 bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1">
                Save Gallery Item
            </button>
            <a href="gallery.php" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold py-5 rounded-2xl text-center transition-all flex items-center justify-center">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function toggleCategoryFields(cat) {
    const videoUrlField = document.getElementById('video_url_field');
    const videoUrlInput = document.getElementById('video_url_input');
    const imageLabel = document.getElementById('image_label');
    const imageHelp = document.getElementById('image_help_text');
    
    if (cat === 'video') {
        videoUrlField.classList.remove('hidden');
        videoUrlInput.setAttribute('required', 'required');
        imageLabel.textContent = 'Custom Video Thumbnail (Optional)';
        imageHelp.textContent = 'Leave blank to auto-fetch the YouTube high-resolution thumbnail.';
    } else {
        videoUrlField.classList.add('hidden');
        videoUrlInput.removeAttribute('required');
        imageLabel.textContent = 'Photo Upload';
        imageHelp.textContent = 'Supported formats: JPG, JPEG, PNG, WEBP, GIF.';
    }
}
</script>
<?php endif; ?>

<!-- Gallery Items Grid -->
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
    <h3 class="text-xl font-extrabold text-kmf-blue font-montserrat mb-6">Gallery Media Items</h3>
    
    <?php if (empty($items)): ?>
        <div class="bg-slate-50 rounded-2xl p-12 text-center border border-dashed border-slate-200">
            <p class="text-slate-400 font-medium italic">No media items added yet. Click "Add Media Item" to begin.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($items as $item): 
                $thumbnail = $item['image_url'];
                if (empty($thumbnail)) {
                    $thumbnail = 'https://placehold.co/600x400/1e3a5f/white?text=No+Image';
                }
            ?>
            <div class="bg-slate-50 rounded-[2rem] border border-slate-100 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300 relative group">
                <!-- Status Badge -->
                <div class="absolute top-4 left-4 z-10 flex gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider <?php echo $item['category'] === 'video' ? 'bg-red-500 text-white shadow-sm' : 'bg-kmf-blue text-white shadow-sm'; ?>">
                        <?php echo escape($item['category']); ?>
                    </span>
                    <?php if (!$item['is_active']): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black bg-slate-400 text-white uppercase tracking-wider shadow-sm">
                            Inactive
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Sort Order Badge -->
                <div class="absolute top-4 right-4 z-10">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/90 backdrop-blur-sm border border-slate-100 text-xs font-black text-kmf-blue shadow-sm">
                        #<?php echo (int)$item['sort_order']; ?>
                    </span>
                </div>
                
                <!-- Media Preview -->
                <div class="relative h-44 overflow-hidden bg-slate-900 flex items-center justify-center">
                    <img src="<?php echo (strpos($thumbnail, 'http') === 0) ? $thumbnail : BASE_URL . $thumbnail; ?>" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="<?php echo escape($item['title']); ?>">
                    <?php if ($item['category'] === 'video'): ?>
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-red-600/90 flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Card Body -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-extrabold text-kmf-blue leading-tight mb-2 min-h-[40px] line-clamp-2">
                            <?php echo escape($item['title'] ?: 'Untitled Media'); ?>
                        </h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4">
                            Project: <span class="text-kmf-orange"><?php echo escape($item['program_title'] ?: 'General / Unassigned'); ?></span>
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
                        <a href="?edit=<?php echo $item['id']; ?>" class="flex-1 text-center py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                            Edit
                        </a>
                        <a href="?delete=<?php echo $item['id']; ?>&csrf=<?php echo $_SESSION['csrf_token'] ?? ''; ?>" 
                           onclick="return confirm('Are you sure you want to delete this media item? This action will permanently remove it from the system.');"
                           class="p-2.5 rounded-xl border border-slate-200 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

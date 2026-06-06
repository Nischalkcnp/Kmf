<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_messages');
$adminTitle = 'Contact Messages';

$pdo = getDb();

// Handle deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete']) && validateCsrf()) {
    $stmt = $pdo->prepare("DELETE FROM contact_submissions WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    redirect(BASE_URL . 'admin/messages.php?deleted=1');
}

// Mark all unread messages as read upon viewing this page
$pdo->exec("UPDATE contact_submissions SET is_read = 1 WHERE is_read = 0");

$stmt = $pdo->query("SELECT * FROM contact_submissions ORDER BY created_at DESC");
$messages = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Contact Messages</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">View messages submitted through the website contact form</p>
    </div>
</div>

<?php if (isset($_GET['deleted'])): ?>
<div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 font-medium border border-red-100 flex items-center justify-between">
    Message deleted successfully.
<a href="<?php echo BASE_URL; ?>admin/messages.php" class="text-red-800 font-bold hover:underline">Dismiss</a>
</div>
<?php endif; ?>

<div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
    <?php if (empty($messages)): ?>
        <div class="text-center py-12">
            <h3 class="text-xl font-bold text-slate-400 mb-2">No Messages Found</h3>
            <p class="text-slate-500">You haven't received any contact messages yet.</p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($messages as $msg): ?>
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm relative group overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-kmf-blue opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-3">
                                <h3 class="text-lg font-bold text-kmf-blue"><?php echo escape($msg['name']); ?></h3>
                                <div class="px-3 py-1 bg-kmf-orange/10 text-kmf-orange text-xs font-bold rounded-full border border-kmf-orange/20">
                                    <a href="mailto:<?php echo escape($msg['email']); ?>" class="hover:underline"><?php echo escape($msg['email']); ?></a>
                                </div>
                                <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <?php echo formatDate($msg['created_at'], 'd M, Y g:i A'); ?>
                                </span>
                            </div>
                            
                            <?php if ($msg['subject']): ?>
                                <p class="text-sm font-extrabold text-slate-700 mb-3 border-l-2 border-kmf-orange pl-3"><?php echo escape($msg['subject']); ?></p>
                            <?php endif; ?>
                            
                            <div class="text-slate-600 outline-none leading-relaxed text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <?php echo nl2br(escape($msg['message'])); ?>
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex md:flex-col gap-3 justify-start items-end">
                            <a href="mailto:<?php echo escape($msg['email']); ?>?subject=Re: <?php echo escape($msg['subject'] ?: 'Your Contact Request'); ?>" 
                               class="flex items-center gap-2 px-5 py-2.5 bg-kmf-blue text-white text-xs font-bold rounded-xl hover:bg-kmf-blue-dark transition-colors shadow-lg shadow-kmf-blue/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Reply
                            </a>
                            <form action="" method="get" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                <input type="hidden" name="delete" value="<?php echo $msg['id']; ?>">
                                <?php echo csrfField(); ?>
                                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-colors border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

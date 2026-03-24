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
    <title>Admin Login | KMF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kmf: {
                            blue: '#1e3a5f',
                            'blue-dark': '#132841',
                            orange: '#e85d04',
                            'orange-light': '#ff8c00',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        montserrat: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-kmf-blue-dark min-h-screen flex items-center justify-center p-6 relative overflow-hidden font-sans">
    <!-- Abstract Background Shapers -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
        <div class="absolute -top-1/4 -right-1/4 w-1/2 h-1/2 bg-kmf-orange rounded-full blur-[150px]"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-1/2 h-1/2 bg-kmf-blue rounded-full blur-[150px]"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-white font-montserrat tracking-tight mb-2">KMF <span class="text-kmf-orange">CMS</span></h1>
            <p class="text-gray-400 text-sm font-medium">Kanchhi Maya Tamang Foundation</p>
        </div>

        <div class="glass rounded-[2rem] shadow-2xl p-8 md:p-10 border border-white/10">
            <h2 class="text-2xl font-bold text-kmf-blue mb-8">Login to Dashboard</h2>
            
            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 animate-headShake">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-red-600 text-sm font-semibold"><?php echo escape($error); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div>
                    <label for="username" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input type="text" id="username" name="username" required value="<?php echo escape($_POST['username'] ?? ''); ?>" 
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                            placeholder="your.username">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" id="password" name="password" required 
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
                        Sign In &rarr;
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium">© <?php echo date('Y'); ?> Kanchhi Maya Tamang Foundation</p>
            </div>
        </div>
    </div>
</body>
</html>

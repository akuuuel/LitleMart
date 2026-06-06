<?php
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard = (strpos($currentUri, '/vendor') !== false || strpos($currentUri, '/admin') !== false);
$searchAction = $isDashboard ? url('/vendor/search') : url('/products');
$searchPlaceholder = $isDashboard ? "Cari pesanan, produk..." : "Cari produk, merek, penjual...";
?>
<!-- ======================== NAVBAR ======================== -->
<nav class="bg-[#056526] border-b border-green-700/30 fixed top-0 left-0 right-0 z-[100] shadow-md" x-data="{ mobileOpen: false, searchOpen: false }">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 md:h-16">
            
            <!-- Left: Hamburger (mobile) + Logo + Nav Links -->
            <div class="flex items-center gap-3 md:gap-6">
                <!-- Logo -->
                <a href="<?= url('/') ?>" class="flex-shrink-0 flex items-center gap-2">
                    <div class="hidden md:flex w-7 h-7 md:w-8 md:h-8 bg-primary rounded-lg items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                    </div>
                    <span class="text-base md:text-xl font-bold text-white tracking-tight">LitleMart</span>
                </a>
                
                <!-- Desktop Nav Links -->
                <?php if (!$isDashboard): ?>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="<?= url('/categories') ?>" class="text-sm font-semibold text-white/90 hover:text-white transition-colors">Kategori</a>
                    <?php
                        $sellLink = url('/login');
                        if (isset($_SESSION['user_id'])) {
                            if (isset($_SESSION['roles']) && in_array('vendor', $_SESSION['roles'])) {
                                $sellLink = url('/vendor/dashboard');
                            } else {
                                $sellLink = url('/vendor/onboarding');
                            }
                        }
                    ?>
                    <a href="<?= $sellLink ?>" class="text-sm font-bold text-white/80 hover:text-white transition-colors">Jual di LitleMart</a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Center: Search Bar (hidden on mobile, full on desktop) -->
            <div class="hidden md:flex flex-1 max-w-2xl px-6">
                <form action="<?= $searchAction ?>" method="GET" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="search" name="q" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" class="w-full pl-11 pr-4 py-2.5 border border-white/10 rounded-xl bg-white/10 text-white placeholder-white/50 text-sm focus:outline-none focus:ring-2 focus:ring-white/20 focus:bg-white/20 transition-all font-medium" placeholder="<?= $searchPlaceholder ?>">
                </form>
            </div>

            <!-- Right: Action Icons -->
            <div class="flex items-center gap-1 md:gap-4">
                <!-- Mobile Search Toggle -->
                <button @click="searchOpen = !searchOpen" class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/10 text-white transition-colors" aria-label="Cari">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <?php if (!$isDashboard): ?>
                <!-- Cart -->
                <?php
                $cartCount = 0;
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $qty) {
                        $cartCount += $qty;
                    }
                }
                ?>
                <a href="<?= url('/cart') ?>" id="navbar-cart-icon" class="relative w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span id="cart-badge" class="<?= $cartCount > 0 ? '' : 'hidden' ?> absolute -top-0.5 -right-0.5 h-3.5 min-w-[14px] px-1 bg-white text-[#056526] text-[8px] font-black flex items-center justify-center rounded-full border border-[#056526] transition-all transform scale-100"><?= $cartCount ?></span>
                </a>
                <?php endif; ?>

                <!-- Messages -->
                <a href="<?= url('/messages') ?>" class="relative w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span id="chat-badge" class="hidden absolute -top-0.5 -right-0.5 h-3.5 w-3.5 bg-white text-[#056526] text-[8px] font-bold flex items-center justify-center rounded-full border border-[#056526]"></span>
                </a>

                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <?php
                    $unreadCount = 0;
                    $latestNotifs = [];
                    $vendorIncompleteOrders = 0;
                    if (isset($_SESSION['user_id'])) {
                        $db = \App\Core\Database::getInstance()->getConnection();
                        $notifStmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
                        $notifStmt->execute([$_SESSION['user_id']]);
                        $unreadCount = $notifStmt->fetchColumn();
                        $notifStmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                        $notifStmt->execute([$_SESSION['user_id']]);
                        $latestNotifs = $notifStmt->fetchAll();

                        // Vendor incomplete orders check
                        if (isset($_SESSION['roles']) && in_array('vendor', $_SESSION['roles'])) {
                            $vStmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
                            $vStmt->execute([$_SESSION['user_id']]);
                            $vId = $vStmt->fetchColumn();
                            if ($vId) {
                                $vendorIncompleteOrders = (int)$db->query("
                                    SELECT COUNT(DISTINCT o.id) 
                                    FROM orders o 
                                    JOIN order_items oi ON oi.order_id = o.id 
                                    WHERE oi.vendor_id = '$vId' 
                                    AND o.status NOT IN ('completed', 'cancelled')
                                ")->fetchColumn();
                            }
                        }
                    }
                    ?>
                    <?php $notifUrl = $isDashboard ? url('/vendor/notifications') : url('/notifications'); ?>
                    <button @click="window.innerWidth < 768 ? window.location.href = '<?= $notifUrl ?>' : (open = !open)" 
                            class="relative w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span id="notification-badge" class="<?= $unreadCount > 0 ? '' : 'hidden' ?> absolute -top-0.5 -right-0.5 h-3.5 w-3.5 bg-white text-[#056526] text-[8px] font-bold flex items-center justify-center rounded-full border border-[#056526] transition-all transform scale-100"><?= $unreadCount ?></span>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-3 w-72 md:w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-4 z-50 overflow-hidden"
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        <div class="px-4 pb-2 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 text-sm">Notifikasi</h3>
                            <?php if ($unreadCount > 0): ?>
                                <a href="<?= url($isDashboard ? '/vendor/notifications' : '/notifications/mark-all-read') ?>" @click="if(typeof clearSystemNotifications === 'function') clearSystemNotifications('<?= $_SESSION['user_id'] ?>')" class="text-[10px] text-primary font-bold uppercase transition-all hover:opacity-75">Tandai dibaca</a>
                            <?php endif; ?>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <?php if (empty($latestNotifs)): ?>
                                <div class="p-8 text-center text-gray-400">
                                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg></div>
                                    <p class="text-xs">Tidak ada notifikasi</p>
                                </div>
                            <?php else: ?>
                                <div class="divide-y divide-gray-50">
                                    <?php foreach ($latestNotifs as $n): ?>
                                        <div class="px-4 py-3 hover:bg-gray-50 transition-colors <?= !$n['is_read'] ? 'bg-green-50/20' : '' ?>">
                                            <p class="text-[11px] font-bold text-gray-900 truncate mb-0.5"><?= htmlspecialchars($n['title']) ?></p>
                                            <p class="text-[10px] text-gray-500 line-clamp-1 mb-1"><?= htmlspecialchars($n['message']) ?></p>
                                            <p class="text-[9px] text-gray-400"><?= date('d M Y, H:i', strtotime($n['created_at'])) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a href="<?= $isDashboard ? url('/vendor/notifications') : url('/notifications') ?>" class="block text-center py-2 bg-gray-50 text-[10px] font-bold text-gray-500 hover:text-primary transition-colors">LIHAT SEMUA</a>
                    </div>
                </div>

                <!-- User Avatar / Login Buttons (Hidden on mobile, replaced by merged hamburger menu) -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="hidden md:block relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-1.5 focus:outline-none">
                            <div class="h-8 w-8 md:h-9 md:w-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs ring-2 ring-white shadow-sm overflow-hidden border border-gray-200">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 top-full mt-2 w-48 rounded-xl shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 text-xs text-slate-400">Halo, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></div>
                            <a href="<?= url('/') ?>" class="block px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-slate-50 transition-colors">Beranda</a>
                            <a href="<?= url('/user/' . $_SESSION['user_id']) ?>" class="block px-4 py-3 text-sm font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors">Profil Saya</a>
                            <a href="<?= url('/orders') ?>" class="block px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-slate-50 transition-colors">Pesanan Saya</a>
                            <?php if (in_array('admin', $_SESSION['roles'] ?? [])): ?>
                                <a href="<?= url('/admin/dashboard') ?>" class="block px-4 py-3 text-sm font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors">Dashboard Admin</a>
                            <?php endif; ?>
                            <?php if (in_array('vendor', $_SESSION['roles'] ?? [])): ?>
                                <a href="<?= url('/vendor/dashboard') ?>" class="block px-4 py-3 text-sm font-semibold text-primary hover:bg-slate-50 transition-colors">Dashboard Penjual</a>
                            <?php endif; ?>
                            <form action="<?= url('/logout') ?>" method="POST" class="border-t border-gray-50">
                                <button type="submit" class="w-full text-left block px-4 py-3 text-sm text-red-600 font-bold hover:bg-red-50 transition-colors">Keluar</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="hidden md:flex items-center gap-3">
                        <a href="<?= url('/login') ?>" class="text-sm font-semibold text-white hover:text-white/80 transition-colors">Masuk</a>
                        <a href="<?= url('/register') ?>" class="text-sm font-semibold text-[#056526] bg-white hover:bg-gray-100 px-4 py-2 rounded-full transition-colors shadow-sm">Daftar</a>
                    </div>
                <?php endif; ?>

                <!-- Hamburger Menu (Universal Mobile Drawer) -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white/10 text-white transition-colors flex-shrink-0 relative" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <?php if ($vendorIncompleteOrders > 0): ?>
                        <span class="absolute -top-0.5 -right-0.5 h-3.5 min-w-[14px] px-1 bg-red-500 text-white text-[8px] font-black flex items-center justify-center rounded-full border border-[#056526] animate-pulse"><?= $vendorIncompleteOrders ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>

        <!-- Mobile Search Bar (toggle) -->
        <div x-show="searchOpen" x-cloak class="md:hidden pb-3 pt-1" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <form action="<?= $searchAction ?>" method="GET" class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="search" name="q" autofocus value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" class="w-full pl-11 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary transition-all" placeholder="<?= $searchPlaceholder ?>">
            </form>
        </div>
    </div>

    <!-- Universal Mobile Drawer Menu -->
    <div x-show="mobileOpen" x-cloak class="fixed inset-0 z-[110] md:hidden" style="pointer-events: none;">
        <!-- Backdrop -->
        <div @click="mobileOpen = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm" style="pointer-events: all;"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
        
        <!-- Drawer -->
        <div class="absolute right-0 top-0 h-full w-[280px] bg-white shadow-2xl flex flex-col" style="pointer-events: all;"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
             
            <!-- Header -->
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-emerald-50 text-emerald-900">
                <div class="font-black text-lg tracking-tight">Menu Utama</div>
                <button @click="mobileOpen = false" class="w-8 h-8 flex items-center justify-center bg-white/50 rounded-lg text-emerald-800 hover:bg-white/80 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Content -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <div class="px-2 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Akses Belanja</div>
                
                <?php
                $mobileNavLinks = [
                    ['url' => url('/'), 'label' => 'Beranda', 'exact' => true, 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'],
                    ['url' => url('/categories'), 'label' => 'Kategori', 'exact' => false, 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>'],
                    ['url' => url('/products'), 'label' => 'Semua Produk', 'exact' => false, 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'],
                    ['url' => url('/orders'), 'label' => 'Pesanan Saya', 'exact' => false, 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>']
                ];

                foreach ($mobileNavLinks as $link):
                    $isActive = $link['exact'] 
                        ? ($currentUri === parse_url($link['url'], PHP_URL_PATH) || $currentUri === parse_url($link['url'], PHP_URL_PATH) . '/')
                        : (strpos($currentUri, parse_url($link['url'], PHP_URL_PATH)) !== false);
                    
                    if ($link['url'] === url('/') && $currentUri !== '/' && $currentUri !== url('/')) $isActive = false;

                    $baseClass = $isActive 
                        ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold bg-[#056526] text-white transition-colors shadow-md shadow-green-100'
                        : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors';
                    $iconClass = $isActive ? 'text-white' : 'text-gray-400';
                    $iconHtml = str_replace('ICON_COLOR', $iconClass, $link['icon']);
                ?>
                    <a href="<?= $link['url'] ?>" class="<?= $baseClass ?>">
                        <?= $iconHtml ?>
                        <?= $link['label'] ?>
                    </a>
                <?php endforeach; ?>

                <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= url('/login') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors mt-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Jual di LitleMart
                </a>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['roles']) && in_array('vendor', $_SESSION['roles'])): ?>
                <div class="mt-4 px-2 pt-4 border-t border-gray-100 pb-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">Akses Penjual / Vendor</div>
                
                <?php
                $vendorNavLinks = [
                    ['url' => url('/vendor/dashboard'), 'label' => 'Dashboard Penjual', 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>'],
                    ['url' => url('/vendor/products'), 'label' => 'Kelola Produk', 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'],
                    ['url' => url('/vendor/orders'), 'label' => 'Pesanan Masuk', 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>'],
                    ['url' => url('/vendor/analytics'), 'label' => 'Statistik & Analitik', 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'],
                    ['url' => url('/vendor/settings'), 'label' => 'Pengaturan Toko', 'icon' => '<svg class="w-5 h-5 ICON_COLOR" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>']
                ];
                
                foreach ($vendorNavLinks as $link):
                    $isActive = (strpos($currentUri, parse_url($link['url'], PHP_URL_PATH)) !== false);
                    $baseClass = $isActive 
                        ? 'flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-bold bg-[#056526] text-white transition-colors shadow-md shadow-green-100'
                        : 'flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-bold text-gray-700 hover:bg-emerald-50 transition-colors';
                    $iconClass = $isActive ? 'text-white' : 'text-emerald-500';
                    $iconHtml = str_replace('ICON_COLOR', $iconClass, $link['icon']);
                    
                    $badgeCount = 0;
                    if (strpos($link['url'], '/vendor/orders') !== false) {
                        $badgeCount = $vendorIncompleteOrders;
                    }
                ?>
                    <a href="<?= $link['url'] ?>" class="<?= $baseClass ?>">
                        <div class="flex items-center gap-3">
                            <?= $iconHtml ?>
                            <?= $link['label'] ?>
                        </div>
                        <?php if ($badgeCount > 0): ?>
                            <span class="h-4 min-w-[1rem] px-1.5 flex items-center justify-center rounded-full text-[9px] font-black <?= $isActive ? 'bg-white text-[#056526]' : 'bg-red-500 text-white' ?>"><?= $badgeCount ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
                
                <?php endif; ?>

                <?php if (isset($_SESSION['roles']) && in_array('admin', $_SESSION['roles'])): ?>
                <div class="mt-4 px-2 pt-4 border-t border-gray-100 pb-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">Akses Administrator</div>
                <a href="<?= url('/admin/dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold text-gray-700 hover:bg-emerald-50 transition-colors">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard Admin
                </a>
                <?php endif; ?>

            </nav>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm ring-2 ring-white shadow-sm overflow-hidden border border-gray-200 flex-shrink-0">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-bold text-xs text-gray-900 truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></div>
                        <a href="<?= url('/user/' . $_SESSION['user_id']) ?>" class="text-[10px] text-emerald-600 font-bold hover:underline">Lihat Profil</a>
                    </div>
                </div>
                <form action="<?= url('/logout') ?>" method="POST">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
            <?php endif; ?>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="p-4 border-t border-gray-100 flex gap-3 bg-gray-50">
                <a href="<?= url('/login') ?>" class="flex-1 text-center py-2.5 bg-white border border-gray-200 text-sm font-bold text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">Masuk</a>
                <a href="<?= url('/register') ?>" class="flex-1 text-center py-2.5 bg-primary text-sm font-bold text-white rounded-lg hover:bg-green-800 transition-colors shadow-sm">Daftar</a>
            </div>
            <?php endif; ?>
        </div>
        <!-- Lightsaber Effect Overlay -->
        <canvas id="lightsaber-canvas" class="fixed inset-0 pointer-events-none z-[9999]"></canvas>

        <style>
            #lightsaber-canvas { width: 100vw; height: 100vh; }
            @keyframes badgePop {
                0%   { transform: scale(1); }
                35%  { transform: scale(1.8); }
                65%  { transform: scale(0.85); }
                100% { transform: scale(1); }
            }
            .badge-pop { animation: badgePop 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) both; }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('lightsaber-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            function resizeCanvas() {
                canvas.width  = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            window.shootLightsaber = function(x1, y1, x2, y2, onDone) {
                const DURATION = 480;
                let start = null;
                function ease(t) { return t < 0.5 ? 2*t*t : -1+(4-2*t)*t; }

                function draw(ts) {
                    if (!start) start = ts;
                    const t = Math.min(ease((ts - start) / DURATION), 1);
                    const tailT = Math.max(0, (t - 0.4) / 0.6);
                    const tipX  = x1 + (x2 - x1) * t;
                    const tipY  = y1 + (y2 - y1) * t;
                    const talX  = x1 + (x2 - x1) * tailT;
                    const talY  = y1 + (y2 - y1) * tailT;

                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    const grad = ctx.createLinearGradient(talX, talY, tipX, tipY);
                    grad.addColorStop(0,   'rgba(0,255,100,0)');
                    grad.addColorStop(0.5, 'rgba(0,255,100,0.65)');
                    grad.addColorStop(0.9, 'rgba(180,255,200,0.95)');
                    grad.addColorStop(1,   'rgba(255,255,255,1)');

                    ctx.beginPath();
                    ctx.moveTo(talX, talY); ctx.lineTo(tipX, tipY);
                    ctx.strokeStyle = 'rgba(0,255,100,0.15)';
                    ctx.lineWidth = 16; ctx.lineCap = 'round';
                    ctx.shadowBlur = 28; ctx.shadowColor = '#00ff64';
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.moveTo(talX, talY); ctx.lineTo(tipX, tipY);
                    ctx.strokeStyle = 'rgba(60,255,130,0.55)';
                    ctx.lineWidth = 6; ctx.shadowBlur = 14; ctx.shadowColor = '#00ff64';
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.moveTo(talX, talY); ctx.lineTo(tipX, tipY);
                    ctx.strokeStyle = grad;
                    ctx.lineWidth = 2.5; ctx.shadowBlur = 6; ctx.shadowColor = '#aaffcc';
                    ctx.stroke();

                    const alpha = t < 0.92 ? 1 : (1-t)/0.08;
                    ctx.beginPath(); ctx.arc(tipX, tipY, 5, 0, Math.PI*2);
                    ctx.fillStyle = `rgba(220,255,230,${alpha})`;
                    ctx.shadowBlur = 20; ctx.shadowColor = '#00ff64';
                    ctx.fill();
                    ctx.shadowBlur = 0;

                    if (t < 1) requestAnimationFrame(draw);
                    else {
                        let fo = null;
                        function fade(ts2) {
                            if (!fo) fo = ts2;
                            const f = Math.min((ts2 - fo) / 130, 1);
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            ctx.globalAlpha = 1 - f;
                            ctx.beginPath(); ctx.moveTo(x1,y1); ctx.lineTo(x2,y2);
                            ctx.strokeStyle='rgba(0,255,100,0.35)'; ctx.lineWidth=4; ctx.stroke();
                            ctx.globalAlpha = 1;
                            if (f < 1) requestAnimationFrame(fade);
                            else { ctx.clearRect(0,0,canvas.width,canvas.height); if(onDone) onDone(); }
                        }
                        requestAnimationFrame(fade);
                    }
                }
                requestAnimationFrame(draw);
            };
        });
        </script>
    </div>
</nav>
<?php if (!$isDashboard): ?>
<script>
    // Sync cart badge with server on every page load to catch stale items
    (function syncCartBadge() {
        var badge = document.getElementById('cart-badge');
        if (!badge) return;
        fetch('<?= url('/api/cart/count') ?>', { credentials: 'same-origin' })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var count = data.count || 0;
                badge.textContent = count;
                if (count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(function() { /* silent fail */ });
    })();
</script>
<?php endif; ?>

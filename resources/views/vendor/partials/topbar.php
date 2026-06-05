<?php
// Get unread notification count for bell badge
$_notifDb    = \App\Core\Database::getInstance()->getConnection();
$_userId     = $_SESSION['user_id'] ?? null;
$_notifCount = 0;
$_incompleteOrders = 0;
try {
    if ($_userId) {
        $_notifCount = (int)$_notifDb->query(
            "SELECT COUNT(*) FROM notifications WHERE user_id = '$_userId' AND is_read = 0"
        )->fetchColumn();

        // Check incomplete orders for vendor badge
        $_vStmt = $_notifDb->prepare("SELECT id FROM vendors WHERE user_id = ?");
        $_vStmt->execute([$_userId]);
        $_vId = $_vStmt->fetchColumn();
        if ($_vId) {
            $_incompleteOrders = (int)$_notifDb->query("
                SELECT COUNT(DISTINCT o.id) 
                FROM orders o 
                JOIN order_items oi ON oi.order_id = o.id 
                WHERE oi.vendor_id = '$_vId' 
                AND o.status NOT IN ('completed', 'cancelled')
            ")->fetchColumn();
        }
    }
} catch (\Exception $_e) {
    $_notifCount = 0;
}
$_pageTitle = $pageTitle ?? 'Dashboard';
?>
<header class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 sticky top-0 z-20">
    <h1 class="text-sm font-bold text-gray-900"><?= htmlspecialchars($_pageTitle) ?></h1>
    
    <div class="flex items-center gap-3">
        <!-- Back to Store -->
        <a href="<?= url('/') ?>" title="Back to Store" class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-[#056526] transition-colors px-3 py-1.5 rounded-lg hover:bg-green-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Beranda
        </a>

        <!-- Orders Reminder -->
        <?php if ($_incompleteOrders > 0): ?>
        <a href="<?= url('/vendor/orders') ?>" class="flex items-center gap-2 px-3 py-1.5 bg-red-50 border border-red-100 rounded-lg text-red-600 hover:bg-red-100 transition-all animate-pulse shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span class="text-[10px] font-black uppercase tracking-tight"><?= $_incompleteOrders ?> Pesanan</span>
        </a>
        <?php endif; ?>

        <!-- Notifications Bell -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <?php if ($_notifCount > 0): ?>
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center"><?= min($_notifCount, 9) ?><?= $_notifCount > 9 ? '+' : '' ?></span>
                <?php endif; ?>
            </button>

            <!-- Dropdown Panel -->
            <div x-show="open" @click.outside="open = false" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 top-full mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-xl z-50">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Notifikasi</h3>
                    <?php if ($_notifCount > 0): ?>
                        <a href="/vendor/notifications/read-all" class="text-xs font-semibold text-[#056526] hover:underline">Tandai semua dibaca</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                    <?php
                    $_notifs = [];
                    try {
                        $_notifStmt = $_notifDb->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 8");
                        $_notifStmt->execute([$_userId]);
                        $_notifs = $_notifStmt->fetchAll();
                    } catch (\Exception $_e) { $_notifs = []; }

                    if (empty($_notifs)): ?>
                        <div class="p-6 text-center text-sm text-gray-400">Tidak ada notifikasi.</div>
                    <?php else:
                        foreach ($_notifs as $_n): ?>
                        <div class="px-4 py-3 hover:bg-gray-50 transition-colors <?= !$_n['is_read'] ? 'bg-green-50/50' : '' ?>">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 <?= !$_n['is_read'] ? 'bg-[#056526]' : 'bg-gray-300' ?>"></div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900"><?= htmlspecialchars($_n['title']) ?></p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed"><?= htmlspecialchars($_n['message']) ?></p>
                                    <p class="text-[10px] text-gray-400 mt-1"><?= date('d M Y, H:i', strtotime($_n['created_at'])) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <form action="<?= url('/logout') ?>" method="POST">
            <button type="submit" title="Logout" class="flex items-center gap-1.5 text-xs font-semibold text-red-500 hover:text-white hover:bg-red-500 transition-all px-3 py-1.5 rounded-lg border border-red-200 hover:border-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</header>

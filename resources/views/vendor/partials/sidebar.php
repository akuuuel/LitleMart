<?php 
$currentPath = $_SERVER['REQUEST_URI'] ?? '';

// Fetch incomplete orders count for sidebar badge
$db = \App\Core\Database::getInstance()->getConnection();
$vendorStmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
$vendorStmt->execute([$_SESSION['user_id']]);
$vId = $vendorStmt->fetchColumn();

$incompleteCount = 0;
if ($vId) {
    $incompleteCount = (int)$db->query("
        SELECT COUNT(DISTINCT o.id) 
        FROM orders o 
        JOIN order_items oi ON oi.order_id = o.id 
        WHERE oi.vendor_id = '$vId' 
        AND o.status NOT IN ('completed', 'cancelled')
    ")->fetchColumn();
}

function sidebarLink($href, $label, $icon, $currentPath, $badge = 0) {
    $isActive = strpos($currentPath, $href) !== false;
    $base = $isActive
        ? 'flex items-center justify-between px-4 py-3 bg-[#056526] text-white rounded-lg text-sm font-medium transition-all shadow-md shadow-green-100'
        : 'flex items-center justify-between px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors';
    $iconColor = $isActive ? 'text-white' : 'text-gray-400';
    
    echo "<a href=\"{$href}\" class=\"{$base}\">
            <div class=\"flex items-center gap-3\">
                <span class=\"{$iconColor}\">{$icon}</span>
                <span>{$label}</span>
            </div>";
    if ($badge > 0) {
        $badgeClass = $isActive ? 'bg-white text-[#056526]' : 'bg-red-500 text-white';
        echo "<span class=\"h-4 min-w-[1rem] px-1 flex items-center justify-center rounded-full text-[9px] font-black {$badgeClass}\">{$badge}</span>";
    }
    echo "</a>";
}

$icons = [
    'dashboard' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
    'orders'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>',
    'products'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
    'customers' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
    'analytics' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
    'settings'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
    'help'      => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    'store'     => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>',
    'profile'   => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
];
?>


<!-- Desktop Sidebar (hidden on mobile) -->
<aside class="!hidden md:!flex w-[200px] flex-shrink-0 flex-col bg-white border-r border-gray-200 min-h-screen sticky top-0">
    <div class="p-6 pb-5 border-b border-gray-100 flex items-center gap-3">
        <div class="w-9 h-9 bg-[#056526] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
        </div>
        <div>
            <div class="text-[11px] font-black text-[#056526] leading-none uppercase tracking-tighter">LitleMart</div>
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Vendor Pro</div>
        </div>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        <?php sidebarLink(url('/vendor/dashboard'), 'Dashboard', $icons['dashboard'], $currentPath); ?>
        <?php sidebarLink(url('/vendor/orders'),    'Pesanan',    $icons['orders'],    $currentPath, $incompleteCount); ?>
        <?php sidebarLink(url('/vendor/products'),  'Produk',     $icons['products'],  $currentPath); ?>
        <?php sidebarLink(url('/vendor/customers'), 'Pelanggan',  $icons['customers'], $currentPath); ?>
        <?php sidebarLink(url('/vendor/analytics'), 'Analitik',   $icons['analytics'], $currentPath); ?>
        <?php sidebarLink(url('/vendor/settings'),  'Pengaturan', $icons['settings'],  $currentPath); ?>
        <div class="pt-6 pb-2 px-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Navigasi Publik</div>
        <?php 
            if ($vId) sidebarLink(url('/store/' . $vId), 'Toko Saya', $icons['store'], $currentPath);
            sidebarLink(url('/user/' . $_SESSION['user_id']), 'Profil Saya', $icons['profile'], $currentPath);
        ?>
    </nav>

    <div class="px-3 pb-6 space-y-1 border-t border-gray-100 pt-4">
        <?php sidebarLink(url('/vendor/help'), 'Bantuan', $icons['help'], $currentPath); ?>
    </div>
</aside>

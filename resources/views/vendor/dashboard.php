<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <!-- Main Area -->
    <div class="flex-1 w-full flex flex-col min-w-0 overflow-hidden">

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-10 pt-4 md:pt-6">
            <!-- Welcome Banner -->
            <div class="mb-5 md:mb-8 bg-gradient-to-r from-[#056526] to-green-400 rounded-2xl md:rounded-[2rem] p-5 md:p-8 flex items-center justify-between text-white shadow-lg overflow-hidden relative">
                <div class="relative z-10">
                    <p class="text-green-100 text-[10px] md:text-xs font-bold mb-1.5 uppercase tracking-[0.2em] opacity-80">Selamat datang kembali,</p>
                    <h2 class="text-xl md:text-3xl font-black tracking-tight leading-tight"><?= htmlspecialchars($vendor['store_name'] ?? 'Toko Anda') ?></h2>
                    <p class="hidden sm:block text-green-100 text-sm mt-2 font-medium">Berikut adalah ringkasan performa toko Anda hari ini.</p>
                </div>
                <a href="<?= url('/vendor/products/create') ?>" class="relative z-10 hidden md:flex items-center gap-2 bg-white text-[#056526] text-[10px] md:text-sm font-black px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl hover:bg-green-50 transition-all shadow-xl shadow-green-900/10 active:scale-95 flex-shrink-0 ml-4">
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    <span>PRODUK BARU</span>
                </a>
                <!-- Decorative background elements -->
                <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-4 -top-4 w-32 h-32 bg-green-900/10 rounded-full blur-2xl"></div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <?php
                $cards = [
                    [
                        'label'      => 'Total Produk',
                        'value'      => number_format($totalProducts),
                        'link'       => url('/vendor/products'),
                        'iconBg'     => 'bg-emerald-100',
                        'iconColor'  => 'text-emerald-600',
                        'icon'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>',
                        'badge'      => 'Listing Aktif',
                        'badgeColor' => 'text-emerald-700 bg-emerald-100',
                    ],
                    [
                        'label'      => 'Total Pesanan',
                        'value'      => number_format($totalOrders),
                        'link'       => url('/vendor/orders'),
                        'iconBg'     => 'bg-blue-100',
                        'iconColor'  => 'text-blue-600',
                        'icon'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>',
                        'badge'      => 'Semua Waktu',
                        'badgeColor' => 'text-blue-700 bg-blue-100',
                    ],
                    [
                        'label'      => 'Total Pendapatan',
                        'value'      => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                        'link'       => url('/vendor/analytics'),
                        'iconBg'     => 'bg-purple-100',
                        'iconColor'  => 'text-purple-600',
                        'icon'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        'badge'      => 'Setelah Selesai',
                        'badgeColor' => 'text-purple-700 bg-purple-100',
                    ],
                    [
                        'label'      => 'Pesanan Pending',
                        'value'      => number_format($pendingOrders),
                        'link'       => url('/vendor/orders'),
                        'iconBg'     => 'bg-orange-100',
                        'iconColor'  => 'text-orange-600',
                        'icon'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        'badge'      => $pendingOrders > 0 ? 'Perlu Tindakan' : 'Semua Beres',
                        'badgeColor' => $pendingOrders > 0 ? 'text-orange-700 bg-orange-100' : 'text-gray-500 bg-gray-100',
                    ],
                ];
                foreach ($cards as $card): ?>
                <a href="<?= $card['link'] ?>" class="bg-white rounded-2xl p-4 md:p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:border-emerald-100 transition-all group scale-100 active:scale-95 duration-300">
                    <div class="flex justify-between items-start mb-3 md:mb-5">
                        <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl <?= $card['iconBg'] ?> flex items-center justify-center transition-transform group-hover:scale-110 duration-500">
                            <svg class="w-5 h-5 md:w-7 md:h-7 <?= $card['iconColor'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $card['icon'] ?></svg>
                        </div>
                        <span class="hidden xs:block px-2 py-0.5 md:px-3 md:py-1 text-[8px] md:text-[10px] font-black rounded-full uppercase tracking-widest <?= $card['badgeColor'] ?>"><?= $card['badge'] ?></span>
                    </div>
                    <div class="text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-wider mb-1"><?= $card['label'] ?></div>
                    <div <?= isset($card['id']) ? "id=\"{$card['id']}\"" : '' ?> class="text-base md:text-2xl font-black text-gray-900 truncate leading-none"><?= $card['value'] ?></div>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Chart + Recent Orders Row -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
                <!-- Revenue Chart (last 6 months) -->
                <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Pendapatan 6 Bulan Terakhir</h3>
                            <p class="text-xs text-gray-400">Hanya pesanan selesai</p>
                        </div>
                    </div>
                    <?php
                    $maxVal = max(array_column($chartData, 'value') ?: [1]);
                    ?>
                    <div class="flex items-end gap-2 h-40">
                        <?php foreach ($chartData as $bar):
                            $pct = $maxVal > 0 ? round(($bar['value'] / $maxVal) * 100) : 5;
                            $pct = max($pct, 5);
                        ?>
                        <div class="flex-1 flex flex-col items-center gap-1 group">
                            <div class="w-full rounded-t-lg transition-all duration-500 <?= $bar['value'] == $maxVal ? 'bg-[#056526]' : 'bg-green-100 group-hover:bg-green-200' ?>" 
                                 style="height: <?= $pct ?>%" 
                                 title="Rp <?= number_format($bar['value'], 0, ',', '.') ?>"></div>
                            <span class="text-[9px] text-gray-400 font-semibold"><?= $bar['label'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($totalRevenue == 0): ?>
                        <p class="text-center text-xs text-gray-400 mt-4">Belum ada penjualan yang diselesaikan. Grafik akan terisi otomatis.</p>
                    <?php endif; ?>
                </div>

                <!-- Wallet Card -->
                <div class="lg:col-span-2 bg-gradient-to-br from-[#056526] to-green-400 rounded-xl p-6 text-white shadow-md flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span class="text-green-100 text-xs font-bold uppercase tracking-widest">Saldo Dompet</span>
                        </div>
                        <div class="text-3xl font-black mb-1">Rp <?= number_format($walletBalance, 0, ',', '.') ?></div>
                        <p class="text-green-200 text-xs">Akumulasi dari pesanan selesai</p>
                    </div>
                    <div class="mt-6 flex gap-2">
                        <a href="<?= url('/vendor/settings?tab=Pembayaran') ?>" class="flex-1 text-center py-2 bg-white/20 hover:bg-white/30 text-white text-xs font-bold rounded-lg transition-colors">
                            Pengaturan Pencairan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900">Pesanan Terbaru</h3>
                    <a href="<?= url('/vendor/orders') ?>" class="text-xs font-bold text-[#056526] hover:underline">Lihat Semua →</a>
                </div>
                <?php if (empty($recentOrders)): ?>
                    <div class="py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-sm font-medium">Belum ada pesanan masuk.</p>
                        <p class="text-xs mt-1">Pesanan yang masuk akan muncul di sini.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 md:px-6 py-4 text-[9px] md:text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Pesanan</th>
                                    <th class="px-4 md:px-6 py-4 text-[9px] md:text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Pelanggan</th>
                                    <th class="hidden sm:table-cell px-6 py-4 text-[9px] md:text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Item</th>
                                    <th class="px-4 md:px-6 py-4 text-[9px] md:text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Jumlah</th>
                                    <th class="px-4 md:px-6 py-4 text-[9px] md:text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                                    <th class="hidden lg:table-cell px-6 py-4 text-[9px] md:text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php foreach ($recentOrders as $order):
                                    $statusColors = [
                                        'pending'    => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'paid'       => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'processing' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'shipped'    => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'delivered'  => 'bg-green-50 text-green-700 border-green-200',
                                        'completed'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'cancelled'  => 'bg-red-50 text-red-700 border-red-200',
                                    ];
                                    $sc = $statusColors[$order['status'] ?? ''] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                    
                                    $translatedStatus = [
                                        'pending'    => 'Menunggu',
                                        'paid'       => 'Dibayar',
                                        'processing' => 'Diproses',
                                        'shipped'    => 'Dikirim',
                                        'delivered'  => 'Sampai',
                                        'completed'  => 'Selesai',
                                        'cancelled'  => 'Dibatalkan',
                                    ];
                                    $stLabel = $translatedStatus[$order['status']] ?? ucfirst($order['status']);
                                ?>
                                    <tr onclick="window.location.href='<?= url('/vendor/orders?id=' . $order['id']) ?>'" class="hover:bg-emerald-50/30 transition-all cursor-pointer group border-b border-gray-50 last:border-0">
                                        <td class="px-4 md:px-6 py-4 text-[11px] md:text-sm font-black text-primary uppercase">#<?= substr($order['id'], 0, 8) ?></td>
                                        <td class="px-4 md:px-6 py-4">
                                            <div class="flex items-center gap-2 md:gap-3">
                                                <div class="hidden xs:flex w-6 h-6 md:w-8 md:h-8 rounded-lg bg-emerald-100 text-emerald-700 items-center justify-center text-[10px] font-black flex-shrink-0">
                                                    <?= strtoupper(substr($order['customer_name'], 0, 1)) ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-[11px] md:text-sm font-bold text-gray-900 truncate max-w-[80px] xs:max-w-none"><?= htmlspecialchars($order['customer_name']) ?></div>
                                                    <div class="hidden md:block text-[10px] text-gray-400"><?= date('d M Y', strtotime($order['created_at'])) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 text-xs text-gray-500"><?= $order['item_count'] ?> item</td>
                                        <td class="px-4 md:px-6 py-4 text-[11px] md:text-sm font-black text-gray-900">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td>
                                        <td class="px-4 md:px-6 py-4">
                                            <span class="px-2 py-0.5 text-[8px] md:text-[10px] font-black rounded-full uppercase tracking-wider <?= $sc ?> border">
                                                <?= $stLabel ?>
                                            </span>
                                        </td>
                                        <td class="hidden lg:table-cell px-6 py-4 text-xs text-gray-400 italic"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

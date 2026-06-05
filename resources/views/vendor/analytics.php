<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 w-full flex flex-col min-w-0 overflow-hidden">

        <main class="flex-1 overflow-y-auto p-4 md:p-8 pt-4 md:pt-8 w-full">
            <div class="mb-6 text-center md:text-left">
                <h2 class="text-xl font-black text-gray-900">Analitik Performa</h2>
                <p class="text-xs text-gray-500 mt-0.5">Ringkasan performa untuk <b><?= htmlspecialchars($vendor['store_name']) ?></b></p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <?php
                $cards = [
                    ['label' => 'Total Pendapatan',    'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),   'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-purple-600 bg-purple-50'],
                    ['label' => 'Total Pesanan',     'value' => number_format($totalOrders),                         'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',                                                                                                                                                      'color' => 'text-blue-600 bg-blue-50'],
                    ['label' => 'Total Produk',   'value' => number_format($totalProducts),                       'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',                                                                                                                              'color' => 'text-green-600 bg-green-50'],
                    ['label' => 'Total Pelanggan',  'value' => number_format($totalCustomers),                      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'text-orange-600 bg-orange-50'],
                ];
                foreach ($cards as $c): ?>
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <div class="w-10 h-10 rounded-lg <?= $c['color'] ?> flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $c['icon'] ?>"></path>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-400 font-medium mb-1"><?= $c['label'] ?></div>
                    <div class="text-xl font-black text-gray-900"><?= $c['value'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Monthly Revenue Chart -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-1">Pendapatan Bulanan</h3>
                    <p class="text-xs text-gray-400 mb-5">6 bulan terakhir (pesanan selesai)</p>
                    <?php $maxRev = max(array_column($chartData, 'revenue') ?: [1]); ?>
                    <div class="space-y-3">
                        <?php foreach ($chartData as $row):
                            $pct = $maxRev > 0 ? round(($row['revenue'] / $maxRev) * 100) : 0;
                        ?>
                        <div class="flex items-center gap-3">
                            <div class="w-14 text-[10px] font-bold text-gray-400 text-right flex-shrink-0"><?= $row['label'] ?></div>
                            <div class="flex-1 h-6 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#056526] rounded-full transition-all" style="width: <?= max($pct, $row['revenue'] > 0 ? 3 : 0) ?>%"></div>
                            </div>
                            <div class="w-24 text-[10px] font-bold text-gray-700 flex-shrink-0">Rp <?= number_format($row['revenue'], 0, ',', '.') ?></div>
                            <div class="w-12 text-[10px] text-gray-400 flex-shrink-0"><?= $row['orders'] ?> pesanan</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($totalRevenue == 0): ?>
                        <p class="text-center text-xs text-gray-400 mt-6">Belum ada data penjualan. Grafik akan terisi saat ada pesanan selesai.</p>
                    <?php endif; ?>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-1">Produk Terlaris</h3>
                    <p class="text-xs text-gray-400 mb-5">Peringkat berdasarkan pendapatan</p>
                    <?php if (empty($topProducts)): ?>
                        <div class="py-8 text-center text-xs text-gray-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Belum ada data penjualan produk.
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($topProducts as $i => $p): ?>
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-[10px] font-black flex items-center justify-center flex-shrink-0"><?= $i + 1 ?></div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-gray-800 truncate"><?= htmlspecialchars($p['name']) ?></div>
                                    <div class="text-[10px] text-gray-400"><?= number_format($p['total_sold']) ?> terjual</div>
                                </div>
                                <div class="text-xs font-black text-[#056526]">Rp <?= number_format($p['total_revenue'], 0, ',', '.') ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

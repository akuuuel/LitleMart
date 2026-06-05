<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 w-full flex flex-col min-w-0 overflow-hidden">

        <main class="flex-1 overflow-y-auto p-4 md:p-8 pt-4 md:pt-8" x-transition>
            <div class="mb-6">
                <h2 class="text-xl font-black text-gray-900">Daftar Pelanggan</h2>
                <p class="text-xs text-gray-500 mt-0.5">Semua pembeli yang telah berbelanja di toko Anda.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ 
                selectedCustomer: null, 
                showDetail: false,
                openDetail(c) {
                    this.selectedCustomer = c;
                    this.showDetail = true;
                }
            }">
                <?php if (empty($customers)): ?>
                    <div class="py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <p class="text-sm font-medium">Belum ada pelanggan.</p>
                        <p class="text-xs mt-1">Data pembeli akan muncul di sini setelah ada pesanan masuk.</p>
                    </div>
                <?php else: ?>
                    <!-- Mobile List -->
                    <div class="md:hidden divide-y divide-gray-50">
                        <?php foreach ($customers as $c): ?>
                            <div @click="openDetail(<?= htmlspecialchars(json_encode($c)) ?>)" class="p-4 flex items-center justify-between active:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-black">
                                        <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900"><?= htmlspecialchars($c['name']) ?></div>
                                        <div class="text-[10px] text-gray-400 font-medium"><?= $c['order_count'] ?> Pesanan</div>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-5 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">#</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Total Belanja</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Pesanan</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Pesanan Terakhir</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Anggota Sejak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php foreach ($customers as $i => $c): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-4 text-xs text-gray-400"><?= $i+1 ?></td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-black">
                                                <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold text-gray-900"><?= htmlspecialchars($c['name']) ?></div>
                                                <div class="text-[10px] text-gray-400"><?= htmlspecialchars($c['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-xs font-black text-[#056526]">Rp <?= number_format($c['total_spent'], 0, ',', '.') ?></td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-full border border-blue-100">
                                            <?= $c['order_count'] ?> pesanan
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-gray-400"><?= date('d M Y', strtotime($c['last_order'])) ?></td>
                                    <td class="px-5 py-4 text-xs text-gray-400"><?= date('M Y', strtotime($c['member_since'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Customer Detail Modal (Mobile) -->
                <div x-show="showDetail" class="fixed inset-0 z-[100] flex items-end md:hidden" x-cloak>
                    <div x-show="showDetail" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDetail = false"></div>
                    <div x-show="showDetail" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" class="relative w-full bg-white rounded-t-[2.5rem] p-8 shadow-2xl">
                        <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8"></div>
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-black">
                                <span x-text="selectedCustomer?.name.charAt(0).toUpperCase()"></span>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900" x-text="selectedCustomer?.name"></h3>
                                <p class="text-xs text-gray-400 font-medium" x-text="selectedCustomer?.email"></p>
                            </div>
                        </div>
                        <div class="space-y-4 mb-8">
                            <div class="bg-gray-50 rounded-2xl p-4 flex justify-between items-center text-center">
                                <div class="flex-1">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Belanja</p>
                                    <p class="text-sm font-black text-emerald-600" x-text="'Rp ' + (parseInt(selectedCustomer?.total_spent) || 0).toLocaleString('id-ID')"></p>
                                </div>
                                <div class="w-px h-8 bg-gray-200"></div>
                                <div class="flex-1">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pesanan</p>
                                    <p class="text-sm font-black text-blue-600" x-text="selectedCustomer?.order_count + ' Pesanan'"></p>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pesanan Terakhir</span>
                                    <span class="text-[10px] font-black text-gray-700" x-text="selectedCustomer ? new Date(selectedCustomer.last_order).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : ''"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bergabung</span>
                                    <span class="text-[10px] font-black text-gray-700" x-text="selectedCustomer ? new Date(selectedCustomer.member_since).toLocaleDateString('id-ID', {month: 'long', year: 'numeric'}) : ''"></span>
                                </div>
                            </div>
                        </div>
                        <button @click="showDetail = false" class="w-full py-4 bg-gray-100 text-gray-600 font-black rounded-2xl text-xs uppercase tracking-widest active:scale-95 transition-all">Tutup Rincian</button>
                    </div>
                </div>
            </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

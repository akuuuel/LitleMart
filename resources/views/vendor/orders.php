<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans" x-data="{
    showShipModal: false,
    shipOrderId: '',
    shipCourier: '',
    shipTracking: '',
    showCancelModal: false,
    cancelOrderId: '',
    selectedOrder: null,
    showDetailModal: false,
    openDetail(order) {
        this.selectedOrder = order;
        this.showDetailModal = true;
    },
    closeDetail() {
        this.showDetailModal = false;
    },
    openShipModal(orderId) {
        this.shipOrderId = orderId;
        this.showShipModal = true;
    },
    openCancelModal(orderId) {
        this.cancelOrderId = orderId;
        this.showCancelModal = true;
    }
}">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 w-full flex flex-col min-w-0 overflow-hidden">

        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-10 pt-4 md:pt-6">
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-black text-gray-900">Manajemen Pesanan</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Lacak dan kelola semua pesanan untuk toko Anda.</p>
                </div>
            </div>

            <!-- Status Filter Tabs (Scrollable on Mobile) -->
            <div class="overflow-hidden -mx-4 px-4 md:mx-0 md:px-0 mb-4 md:mb-6">
                <div id="status-tabs" class="flex gap-2 overflow-x-auto pb-4 no-scrollbar md:flex-wrap">
                    <?php
                    $tabs = [
                        '' => 'Semua', 
                        'pending' => 'Menunggu', 
                        'paid' => 'Dibayar', 
                        'processing' => 'Diproses', 
                        'shipped' => 'Dikirim', 
                        'delivered' => 'Sampai', 
                        'completed' => 'Selesai', 
                        'cancelled' => 'Batal'
                    ];
                    $total = array_sum($statusCounts);
                    foreach ($tabs as $val => $label):
                        $cnt = $val === '' ? $total : ($statusCounts[$val] ?? 0);
                        $isActive = $statusFilter === $val;
                    ?>
                    <a href="?status=<?= $val ?>" 
                       id="tab-<?= $val ?: 'all' ?>"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[10px] md:text-[11px] font-black uppercase tracking-wider whitespace-nowrap <?= $isActive ? 'bg-[#056526] text-white shadow-lg shadow-green-100 active-tab' : 'bg-white text-gray-400 border border-gray-100 hover:border-emerald-200 hover:text-emerald-600' ?>">
                        <span><?= $label ?></span>
                        <?php if($cnt > 0): ?>
                        <span class="flex items-center justify-center min-w-[1.25rem] md:min-w-[1.5rem] h-4 md:h-5 px-1.5 rounded-lg text-[9px] md:text-[10px] font-black <?= $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500' ?>">
                            <?= $cnt ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const activeTab = document.querySelector('.active-tab');
                    if (activeTab) {
                        activeTab.scrollIntoView({ behavior: 'auto', inline: 'center', block: 'nearest' });
                    }
                });
            </script>

            <!-- Orders Table -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <?php if (empty($orders)): ?>
                    <div class="py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-sm font-medium">Tidak ada pesanan<?= $statusFilter ? ' dengan status ' . ($tabs[$statusFilter] ?? $statusFilter) : '' ?>.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-3 md:px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Pesanan</th>
                                    <th class="px-3 md:px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Pelanggan</th>
                                    <th class="hidden sm:table-cell px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Item</th>
                                    <th class="px-3 md:px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Total</th>
                                    <th class="px-3 md:px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">Status</th>
                                    <th class="hidden md:table-cell px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <?php if ($statusFilter !== ''): ?>
                                        <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php foreach ($orders as $order):
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

                                    $nextStatuses = [
                                        'pending'    => ['processing' => 'Proses'],
                                        'paid'       => ['processing' => 'Proses'],
                                        'processing' => ['shipped' => 'Kirim Sekarang'],
                                        'shipped'    => ['delivered' => 'Tandai Sampai'],
                                    ];
                                    $actions = $nextStatuses[$order['status']] ?? [];
                                ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer group border-b border-gray-100" @click="openDetail(<?= htmlspecialchars(json_encode($order)) ?>)">
                                        <td class="px-3 md:px-5 py-4 font-bold text-primary text-xs group-hover:underline">#<?= strtoupper(substr($order['id'], 0, 8)) ?></td>
                                        <td class="px-3 md:px-5 py-4" @click.stop>
                                            <a href="<?= url('/user/' . $order['user_id']) ?>" class="group/user flex items-center gap-2">
                                                <div class="hidden xs:flex w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 items-center justify-center text-[10px] font-black flex-shrink-0">
                                                    <?= strtoupper(substr($order['customer_name'], 0, 1)) ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-semibold text-gray-900 text-xs truncate max-w-[80px] xs:max-w-none group-hover/user:text-primary transition-colors"><?= htmlspecialchars($order['customer_name']) ?></div>
                                                    <div class="hidden sm:block text-[10px] text-gray-400"><?= htmlspecialchars($order['customer_email']) ?></div>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="hidden sm:table-cell px-5 py-4 text-xs text-gray-500"><?= $order['item_count'] ?> item</td>
                                        <td class="px-3 md:px-5 py-4 font-black text-gray-900 text-[11px] md:text-xs">Rp <?= number_format($order['vendor_subtotal'], 0, ',', '.') ?></td>
                                        <td class="px-3 md:px-5 py-4 text-center">
                                            <span class="px-2 py-0.5 text-[9px] md:text-[10px] font-bold border rounded-full <?= $sc ?> whitespace-nowrap"><?= $stLabel ?></span>
                                        </td>
                                        <td class="hidden md:table-cell px-5 py-4 text-xs text-gray-400"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                        
                                        <?php if ($statusFilter !== ''): ?>
                                        <td class="px-3 md:px-5 py-4 text-right" @click.stop>
                                            <?php if ($statusFilter === 'pending' && $order['status'] === 'pending'): ?>
                                                <button @click="openCancelModal('<?= $order['id'] ?>')" 
                                                        class="px-2 md:px-3 py-1.5 md:py-1 bg-red-500 text-white text-[9px] md:text-[10px] font-bold rounded-lg hover:bg-red-700 transition-all">
                                                    Batal
                                                </button>
                                                <form id="cancel-form-<?= $order['id'] ?>" action="<?= url('/vendor/orders/delete') ?>" method="POST" class="hidden">
                                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                </form>
                                            <?php elseif (!empty($actions)): ?>
                                                <?php foreach ($actions as $newStatus => $label): ?>
                                                    <?php if ($newStatus === 'shipped'): ?>
                                                        <button @click="openShipModal('<?= $order['id'] ?>')" class="px-3 py-1 bg-[#056526] text-white text-[10px] font-bold rounded-lg hover:bg-green-800 transition-colors">
                                                            <?= $label ?>
                                                        </button>
                                                    <?php else: ?>
                                                        <form action="<?= url('/vendor/orders/status') ?>" method="POST" class="inline">
                                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                            <input type="hidden" name="status" value="<?= $newStatus ?>">
                                                            <button type="submit" class="px-3 py-1 bg-[#056526] text-white text-[10px] font-bold rounded-lg hover:bg-green-800 transition-colors">
                                                                <?= $label ?>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-[10px] text-gray-300">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Ship Now Modal -->
    <div x-show="showShipModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showShipModal = false"></div>
            
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full relative shadow-2xl">
                <h3 class="text-xl font-black text-gray-900 mb-6">Informasi Pengiriman</h3>
                
                <form action="<?= url('/vendor/orders/status') ?>" method="POST" class="space-y-4">
                    <input type="hidden" name="order_id" :value="shipOrderId">
                    <input type="hidden" name="status" value="shipped">
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Nama Kurir</label>
                        <input type="text" name="courier" required placeholder="Contoh: JNE, J&T, Sicepat" 
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-primary/20 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Nomor Resi</label>
                        <input type="text" name="tracking_number" required placeholder="Masukkan nomor resi fisik" 
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-primary/20 outline-none">
                    </div>
                    
                    <div class="pt-2 grid grid-cols-2 gap-3">
                        <button type="button" @click="showShipModal = false" 
                                class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg text-xs hover:bg-gray-200 transition-colors">Batal</button>
                        <button type="submit" 
                                class="px-4 py-2 bg-[#056526] text-white font-bold rounded-lg text-xs hover:bg-green-800 transition-colors shadow-lg shadow-green-100">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div x-show="showDetailModal" class="fixed inset-0 z-[100] overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0" @click="closeDetail()"><div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                
                <div class="px-5 md:px-8 py-4 md:py-6 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10 rounded-t-[2rem]">
                    <div class="min-w-0">
                        <h3 class="text-lg md:text-xl font-black text-gray-900 uppercase tracking-tight truncate">Rincian Pesanan</h3>
                        <p class="text-[10px] font-bold text-emerald-600 tracking-widest uppercase" x-text="'#' + selectedOrder?.id.substring(0, 13)"></p>
                    </div>
                    <button @click="closeDetail()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-5 md:px-8 py-5 md:py-6 space-y-6 md:space-y-8 max-h-[75vh] overflow-y-auto">
                    <!-- Products -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Produk Pesanan</h4>
                        <div class="space-y-3">
                            <template x-for="item in selectedOrder?.items" :key="item.id">
                                <div class="flex items-center gap-4 bg-gray-50/50 p-3 rounded-2xl border border-gray-100">
                                    <div class="w-14 h-14 bg-white rounded-xl flex-shrink-0 border border-gray-100 overflow-hidden">
                                        <img :src="'<?= url('') ?>' + item.product_image" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=Prod&background=056526&color=fff'">
                                    </div>
                                    <div class="flex-grow">
                                        <h5 class="text-sm font-bold text-gray-900 leading-tight" x-text="item.product_name"></h5>
                                        <p class="text-[10px] text-gray-400 mt-1" x-text="item.quantity + 'x Rp ' + parseInt(item.price).toLocaleString('id-ID')"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-gray-900" x-text="'Rp ' + parseInt(item.total).toLocaleString('id-ID')"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Pelanggan & Alamat</h4>
                            <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 space-y-4">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Nama</p>
                                    <a :href="'<?= url('/user/') ?>' + selectedOrder?.user_id" class="text-sm text-primary font-bold hover:underline" x-text="selectedOrder?.customer_name"></a>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Alamat Pengiriman</p>
                                    <p class="text-xs text-gray-700 leading-relaxed font-medium italic" x-text="selectedOrder?.shipping_address"></p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Status & Pembayaran</h4>
                            <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 space-y-4">
                                <div class="flex justify-between items-center">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Status</p>
                                    <span class="px-2 py-0.5 text-[9px] font-black rounded-full uppercase" 
                                          :class="{
                                              'bg-yellow-50 text-yellow-700 border border-yellow-100': selectedOrder?.status === 'pending',
                                              'bg-blue-50 text-blue-700 border border-blue-100': selectedOrder?.status === 'paid',
                                              'bg-purple-50 text-purple-700 border border-purple-100': selectedOrder?.status === 'processing',
                                              'bg-indigo-50 text-indigo-700 border border-indigo-100': selectedOrder?.status === 'shipped',
                                              'bg-green-50 text-green-700 border border-green-100': selectedOrder?.status === 'delivered',
                                              'bg-emerald-50 text-emerald-700 border border-emerald-100': selectedOrder?.status === 'completed',
                                              'bg-red-50 text-red-700 border border-red-100': selectedOrder?.status === 'cancelled'
                                          }" x-text="selectedOrder?.status"></span>
                                </div>
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <p class="text-[10px] font-black text-gray-400 uppercase">Subtotal Toko</p>
                                        <p class="text-lg font-black text-primary" x-text="'Rp ' + parseInt(selectedOrder?.vendor_subtotal).toLocaleString('id-ID')"></p>
                                    </div>
                                    <p class="text-[9px] text-gray-400 mt-1 italic">* Belum termasuk ongkos kirim global</p>
                                </div>
                                
                                <template x-if="selectedOrder?.shipment">
                                    <div class="pt-3 border-t border-gray-200">
                                        <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Info Pengiriman</p>
                                        <div class="flex justify-between text-[10px]">
                                            <span class="text-gray-500 font-bold uppercase" x-text="selectedOrder.shipment.courier"></span>
                                            <span class="text-gray-900 font-black font-mono tracking-tighter" x-text="selectedOrder.shipment.tracking_number"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button @click="closeDetail()" class="px-8 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 transition-all text-sm shadow-sm">
                        Tutup
                    </button>
                    
                    <template x-if="selectedOrder?.status === 'paid' || selectedOrder?.status === 'pending'">
                         <form action="<?= url('/vendor/orders/status') ?>" method="POST">
                            <input type="hidden" name="order_id" :value="selectedOrder.id">
                            <input type="hidden" name="status" value="processing">
                            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-green-800 transition-all text-sm shadow-lg shadow-green-100">
                                Proses Pesanan
                            </button>
                         </form>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <!-- Cancel Confirmation Modal -->
    <div x-show="showCancelModal" 
         class="fixed inset-0 z-[110] overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showCancelModal = false"></div>
            
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full relative shadow-2xl text-center">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Batalkan Pesanan?</h3>
                <p class="text-xs text-gray-500 mb-8 font-medium italic">Tindakan ini akan memindahkan pesanan ke tab dibatalkan. Pembeli akan melihat status pesanan sebagai Dibatalkan.</p>
                
                <div class="grid grid-cols-2 gap-3">
                    <button @click="showCancelModal = false" 
                            class="px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl text-xs hover:bg-gray-200 transition-all font-sans">
                        Tutup
                    </button>
                    <button @click="document.getElementById('cancel-form-' + cancelOrderId).submit()" 
                            class="px-4 py-3 bg-red-600 text-white font-bold rounded-xl text-xs hover:bg-red-700 transition-all shadow-lg shadow-red-100 font-sans">
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

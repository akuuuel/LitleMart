<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="min-h-screen bg-gray-50/50" x-data="{ 
    currentView: 'list',
    selectedOrder: null, 
    showConfirmModal: false,
    showReceiptModal: false,
    processing: false,
    confirmTitle: '',
    confirmAction: null,
    
    confirmDelete(orderId, status) {
        this.confirmTitle = (status === 'pending' ? 'Batalkan Pesanan?' : 'Hapus Riwayat Pesanan?');
        this.confirmAction = () => {
            document.getElementById('delete-form-' + orderId).submit();
        };
        this.showConfirmModal = true;
    },

    confirmReceipt(orderId) {
        this.confirmTitle = 'Konfirmasi Penerimaan?';
        this.confirmAction = () => {
            document.getElementById('receipt-form-' + orderId).submit();
        };
        this.showReceiptModal = true;
    },

    async payAgain(orderId) {
        this.processing = true;
        openPaymentModal();
        
        try {
            const res = await fetch('<?= url('/orders/get-snap-token') ?>?id=' + orderId);
            const result = await res.json();
            
            if (result.snap_token) {
                document.getElementById('modal-grand-total').innerText = 'Rp ' + parseInt(this.selectedOrder.grand_total).toLocaleString('id-ID');
                document.getElementById('modal-order-id').innerText = '#' + orderId;
                document.getElementById('modal-order-info').classList.remove('hidden');
                document.getElementById('modal-order-info').classList.add('flex');

                document.getElementById('payment-loading').classList.add('hidden');
                document.getElementById('snap-container-wrapper').classList.remove('hidden');

                window.snap.embed(result.snap_token, {
                    embedId: 'snap-container',
                    onSuccess: async function(midtransResult) { 
                        await fetch('<?= url('/api/payments/finish') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                order_id: orderId,
                                status: 'paid'
                            })
                        });
                        window.location.href = '<?= url('/orders/success') ?>?id=' + orderId;
                    },
                    onPending: function(midtransResult) { 
                         window.location.href = '<?= url('/orders/pending') ?>?id=' + orderId;
                    },
                    onError: function(midtransResult) { 
                        alert('Pembayaran gagal! Silakan coba lagi.'); 
                        closePaymentModal();
                    },
                    onClose: function() {
                        closePaymentModal();
                    }
                });
            } else {
                alert(result.error || 'Gagal membuat token pembayaran');
                closePaymentModal();
            }
        } catch (e) {
            alert('Terjadi kesalahan: ' + e.message);
            closePaymentModal();
        } finally {
            this.processing = false;
        }
    },

    openDetail(order) {
        this.selectedOrder = order;
        // On mobile, switch to detail view. On desktop, we can use modal or the same view.
        // The user specifically wants 'detail pesanan' bukan pop up on mobile.
        if (window.innerWidth < 768) {
            this.currentView = 'detail';
            window.scrollTo(0, 0);
        } else {
            // For desktop, we could still use a nice layout, maybe similar but non-fullscreen.
            // Let's use the same 'view' system for consistency.
            this.currentView = 'detail';
            window.scrollTo(0, 0);
        }
    },
    goBack() {
        this.currentView = 'list';
        this.selectedOrder = null;
    }
}" @popstate.window="if(currentView === 'detail') { currentView = 'list'; }">

    <!-- LIST VIEW -->
    <div x-show="currentView === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="container mx-auto px-4 py-8 md:py-12">
        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 md:mb-12">
                <div>
                    <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">
                        <a href="<?= url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-gray-900">Pesanan Saya</span>
                    </nav>
                    <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tighter mb-2">Riwayat Pesanan</h1>
                    <p class="text-sm md:text-base font-medium text-gray-500 tracking-tight">Pantau semua transaksi Anda di satu tempat.</p>
                </div>
                <a href="<?= url('/products') ?>" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white border-2 border-gray-100 rounded-2xl font-black text-gray-700 hover:border-emerald-500 hover:text-emerald-600 transition-all shadow-sm group text-sm">
                    <span>Belanja Lagi</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <?php if (empty($orders)): ?>
                <div class="bg-white rounded-[2.5rem] p-16 text-center border-2 border-dashed border-gray-200 shadow-xl shadow-gray-200/50">
                    <div class="w-24 h-24 bg-gray-50 text-gray-200 rounded-[2rem] flex items-center justify-center mx-auto mb-8">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 mb-2 tracking-tighter">Belum ada pesanan</h2>
                    <p class="text-gray-400 mb-8 font-medium">Ayo mulai belanja dan temukan produk impianmu!</p>
                    <a href="<?= url('/products') ?>" class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-600 text-white font-black rounded-xl hover:bg-emerald-700 transition-all shadow-lg active:scale-95">
                        Mulai Belanja
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-6 md:gap-8">
                    <?php foreach ($orders as $order): ?>
                        <form id="delete-form-<?= $order['id'] ?>" action="<?= url('/orders/delete') ?>" method="POST" class="hidden">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        </form>
                        <form id="receipt-form-<?= $order['id'] ?>" action="<?= url('/orders/confirm-receipt') ?>" method="POST" class="hidden">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        </form>

                        <?php
                            $statusMap = [
                                'pending'    => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-amber-100 text-amber-700', 'dot' => 'bg-amber-500'],
                                'paid'       => ['label' => 'Sudah Dibayar',       'color' => 'bg-blue-100 text-blue-700',   'dot' => 'bg-blue-500'],
                                'processing' => ['label' => 'Diproses',           'color' => 'bg-purple-100 text-purple-700', 'dot' => 'bg-purple-500'],
                                'shipped'    => ['label' => 'Dikirim',            'color' => 'bg-indigo-100 text-indigo-700', 'dot' => 'bg-indigo-500'],
                                'delivered'  => ['label' => 'Sampai',              'color' => 'bg-teal-100 text-teal-700',     'dot' => 'bg-teal-500'],
                                'completed'  => ['label' => 'Selesai',             'color' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
                                'cancelled'  => ['label' => 'Dibatalkan',           'color' => 'bg-red-100 text-red-700',       'dot' => 'bg-red-500'],
                            ];
                            $st = $statusMap[$order['status']] ?? ['label' => strtoupper($order['status']), 'color' => 'bg-gray-100 text-gray-700', 'dot' => 'bg-gray-400'];
                        ?>

                        <div @click="openDetail(<?= htmlspecialchars(json_encode($order)) ?>)" 
                             class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-emerald-100 transition-all duration-300 overflow-hidden group cursor-pointer">
                            
                            <div class="p-6 md:p-10 flex flex-col md:flex-row md:items-center gap-6">
                                <!-- Order Header (Mobile Friendly) -->
                                <div class="flex items-center justify-between md:hidden mb-2">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest"><?= date('d M Y', strtotime($order['created_at'])) ?></span>
                                    <span class="px-3 py-1 <?= $st['color'] ?> text-[9px] font-black tracking-widest rounded-full uppercase"><?= $st['label'] ?></span>
                                </div>

                                <!-- Product Preview -->
                                <div class="flex items-center gap-4 md:gap-8 flex-grow">
                                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 rounded-2xl overflow-hidden shadow-inner flex-shrink-0">
                                        <img src="<?= !empty($order['items']) ? ($order['items'][0]['product_image'] ?? 'https://picsum.photos/seed/prod/200/200') : 'https://picsum.photos/seed/prod/200/200' ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-base md:text-xl font-black text-gray-900 truncate tracking-tight mb-1">
                                            <?= !empty($order['items']) ? $order['items'][0]['product_name'] : 'Pesanan Tanpa Nama' ?>
                                        </h3>
                                        <p class="text-xs md:text-sm font-bold text-gray-400 flex items-center gap-2">
                                            <span><?= count($order['items']) ?> Produk</span>
                                            <?php if (count($order['items']) > 1): ?>
                                            <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-500">+<?= count($order['items'])-1 ?> lainnya</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Price and Status (Desktop) -->
                                <div class="flex items-center justify-between md:flex-col md:items-end gap-2 border-t md:border-t-0 border-gray-50 pt-4 md:pt-0">
                                    <div class="text-left md:text-right">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Belanja</p>
                                        <p class="text-lg md:text-2xl font-black text-emerald-600 tracking-tighter">Rp <?= number_format($order['grand_total'], 0, ',', '.') ?></p>
                                    </div>
                                    <div class="hidden md:flex items-center gap-2">
                                        <span class="px-4 py-1.5 <?= $st['color'] ?> text-[10px] font-black tracking-widest rounded-full uppercase"><?= $st['label'] ?></span>
                                    </div>
                                    <div class="md:hidden">
                                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-emerald-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- DETAIL VIEW (FULL PAGE MOBILE STYLE) -->
    <div x-show="currentView === 'detail'" x-cloak 
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="fixed inset-0 z-[100] bg-gray-50 flex flex-col h-screen">
        
        <!-- Mobile Detail Header -->
        <div class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-4 shrink-0 shadow-sm relative z-20">
            <button @click="goBack()" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-600 active:scale-90 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <div class="min-w-0">
                <h3 class="text-lg font-black text-gray-900 tracking-tight truncate" x-text="'Pesanan #' + (selectedOrder?.id?.substring(0, 8) || '')"></h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="selectedOrder ? new Date(selectedOrder.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : ''"></p>
            </div>
            <div class="ml-auto">
                 <span :class="{
                    'bg-amber-100 text-amber-700':   selectedOrder?.status === 'pending',
                    'bg-blue-100 text-blue-700':     selectedOrder?.status === 'paid',
                    'bg-purple-100 text-purple-700': selectedOrder?.status === 'processing',
                    'bg-indigo-100 text-indigo-700': selectedOrder?.status === 'shipped',
                    'bg-teal-100 text-teal-700':     selectedOrder?.status === 'delivered',
                    'bg-emerald-100 text-emerald-700': selectedOrder?.status === 'completed',
                    'bg-red-100 text-red-700':       selectedOrder?.status === 'cancelled'
                }" class="px-3 py-1 text-[9px] font-black rounded-full uppercase tracking-widest" 
                x-text="{
                    pending: 'Tagihan',
                    paid: 'Dibayar',
                    processing: 'Diproses',
                    shipped: 'Dikirim',
                    delivered: 'Sampai',
                    completed: 'Selesai',
                    cancelled: 'Batal'
                }[selectedOrder?.status] || selectedOrder?.status"></span>
            </div>
        </div>

        <!-- Detail Scrollable Context -->
        <div class="flex-1 overflow-y-auto pb-32">
            <div class="p-6 space-y-6">
                <!-- Status Banner -->
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg x-show="selectedOrder?.status === 'shipped'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <svg x-show="selectedOrder?.status === 'delivered' || selectedOrder?.status === 'completed'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <i x-show="['pending','paid','processing'].includes(selectedOrder?.status)" class="fa-solid fa-clock-rotate-left text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-gray-900 mb-0.5" x-text="{
                            pending: 'Menunggu Pembayaran',
                            paid: 'Sedang Disiapkan',
                            processing: 'Sedang Dipacking',
                            shipped: 'Pesanan Dikirim',
                            delivered: 'Pesanan Sampai',
                            completed: 'Pesanan Selesai',
                            cancelled: 'Dibatalkan'
                        }[selectedOrder?.status]"></h4>
                        <p class="text-[11px] font-bold text-gray-400" x-text="selectedOrder?.status === 'shipped' ? 'Kurir sedang membawa paketmu.' : (selectedOrder?.status === 'delivered' ? 'Silakan cek paketmu sekarang.' : 'Pantau terus progresnya!')"></p>
                    </div>
                </div>

                <!-- Shipping Tracking (If shipped/delivered) -->
                <template x-if="selectedOrder?.shipment">
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 space-y-4">
                         <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Informasi Pengiriman</span>
                            <span class="text-[10px] font-black text-emerald-600" x-text="selectedOrder.shipment.courier"></span>
                         </div>
                         <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                             <div>
                                 <p class="text-[9px] font-bold text-gray-400 uppercase mb-1">Nomor Resi</p>
                                 <p class="text-sm font-black text-gray-900 font-mono tracking-wider" x-text="selectedOrder.shipment.tracking_number"></p>
                             </div>
                             <button @click="navigator.clipboard.writeText(selectedOrder.shipment.tracking_number); alert('Resi disalin!')" class="text-emerald-600 text-xs font-black uppercase">Salin</button>
                         </div>
                    </div>
                </template>

                <!-- Product Items -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Daftar Produk</h4>
                    <template x-for="item in selectedOrder?.items" :key="item.id">
                        <div class="bg-white rounded-[2rem] p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl overflow-hidden border border-gray-50 flex-shrink-0">
                                <img :src="item.product_image || 'https://picsum.photos/seed/prod/200/200'" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow min-w-0">
                                <p class="text-[9px] font-black text-emerald-600 uppercase mb-0.5 truncate" x-text="item.store_name"></p>
                                <h5 class="text-sm font-black text-gray-900 truncate mb-1" x-text="item.product_name"></h5>
                                <p class="text-[10px] font-bold text-gray-400" x-text="item.quantity + ' x Rp ' + parseInt(item.price).toLocaleString('id-ID')"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-gray-900" x-text="'Rp ' + parseInt(item.total).toLocaleString('id-ID')"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Billing Summary -->
                <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white space-y-4 shadow-xl">
                    <div class="flex justify-between text-xs opacity-60 font-bold uppercase tracking-widest">
                        <span>Subtotal</span>
                        <span x-text="'Rp ' + parseInt(selectedOrder?.total_amount).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between text-xs opacity-60 font-bold uppercase tracking-widest">
                        <span>Ongkos Kirim</span>
                        <span x-text="'Rp ' + parseInt(selectedOrder?.shipping_cost).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="pt-6 border-t border-white/10 flex justify-between items-end">
                        <div>
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Total Pembayaran</p>
                            <p class="text-2xl font-black tracking-tighter" x-text="'Rp ' + parseInt(selectedOrder?.grand_total).toLocaleString('id-ID')"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-white/40 uppercase mb-1">Metode</p>
                            <p class="text-[10px] font-black" x-text="selectedOrder?.payment?.payment_type || 'Online'"></p>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Tujuan Pengiriman</p>
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <p class="text-sm font-bold text-gray-700 leading-relaxed" x-text="selectedOrder?.shipping_address"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar (Sticky Mobile) -->
        <div class="bg-white/80 backdrop-blur-xl border-t border-gray-100 px-6 py-6 flex flex-col gap-3 fixed bottom-0 left-0 right-0 z-30 shadow-[0_-10px_30px_rgba(0,0,0,0.03)]">
             <!-- Confirm Receipt -->
             <template x-if="selectedOrder?.status === 'delivered'">
                <button @click="confirmReceipt(selectedOrder.id)" 
                        class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-700 transition-all text-sm shadow-xl shadow-emerald-200 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <span>Terima Barang Sekarang</span>
                </button>
            </template>

            <!-- Pay Again -->
            <template x-if="selectedOrder?.status === 'pending'">
                <button @click="payAgain(selectedOrder.id)" :disabled="processing"
                        class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-700 transition-all text-sm shadow-xl shadow-emerald-200 disabled:opacity-50 active:scale-95 flex items-center justify-center gap-3">
                    <span x-show="processing" class="animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                    <span x-text="processing ? 'Menghubungkan Midtrans...' : 'Bayar Sekarang'"></span>
                </button>
            </template>

            <div class="flex gap-3">
                 <!-- Delete / Cancel -->
                <template x-if="['pending', 'completed', 'cancelled', 'delivered'].includes(selectedOrder?.status)">
                    <button @click="confirmDelete(selectedOrder.id, selectedOrder.status)" 
                            class="flex-1 py-4 bg-white border-2 border-red-50 text-red-600 font-black rounded-2xl hover:bg-red-50 transition-all text-sm flex items-center justify-center gap-2">
                         <span x-text="selectedOrder?.status === 'pending' ? 'Batalkan' : 'Hapus History'"></span>
                    </button>
                </template>
                <button @click="goBack()" class="flex-1 py-4 bg-gray-50 text-gray-500 font-black rounded-2xl active:scale-95 transition-all text-sm">Kembali</button>
            </div>
        </div>
    </div>

    <!-- Confirm Modals (Stylized) -->
    <div x-show="showConfirmModal || showReceiptModal" class="fixed inset-0 z-[150] flex items-center justify-center p-6" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showConfirmModal = false; showReceiptModal = false"></div>
        <div class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full relative shadow-2xl text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-90 opacity-0" x-transition:enter-end="scale-100 opacity-100">
            <div x-show="showConfirmModal" class="w-20 h-20 bg-red-50 text-red-600 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <div x-show="showReceiptModal" class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2 leading-tight" x-text="confirmTitle"></h3>
            <p class="text-gray-400 mb-10 font-bold text-sm leading-relaxed" x-text="showConfirmModal ? 'Tindakan ini tidak bisa dibatalkan.' : 'Pastikan barang sudah diterima dengan baik.'"></p>
            <div class="flex flex-col gap-3">
                <button @click="confirmAction(); showConfirmModal = false; showReceiptModal = false" class="w-full py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-emerald-600 transition-all shadow-xl">Konfirmasi</button>
                <button @click="showConfirmModal = false; showReceiptModal = false" class="w-full py-4 text-gray-400 font-bold">Batalkan</button>
            </div>
        </div>
    </div>

    <!-- Midtrans Payment Modal -->
    <div id="payment-modal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closePaymentModal()"></div>
        <div class="relative bg-white w-full max-w-[440px] h-[600px] rounded-[3rem] overflow-hidden flex flex-col shadow-2xl scale-95 opacity-0 transition-all duration-300" id="payment-modal-container">
             <div class="px-8 py-6 bg-white border-b border-gray-50 flex items-center justify-between">
                <h4 class="font-black text-gray-900 text-sm tracking-widest uppercase italic">Secure Checkout</h4>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <div id="modal-order-info" class="hidden px-8 py-4 bg-gray-900 items-center justify-between">
                <p id="modal-grand-total" class="text-xl font-black text-white">Rp 0</p>
                <p id="modal-order-id" class="text-[10px] font-mono font-bold text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded">#ORD</p>
            </div>
            <div class="flex-1 overflow-hidden flex flex-col">
                <div id="payment-loading" class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-white">
                    <div class="w-16 h-16 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mb-6"></div>
                    <p class="text-gray-900 font-black">Menghubungkan...</p>
                </div>
                <div id="snap-container-wrapper" class="hidden flex-1 overflow-y-auto">
                    <div id="snap-container" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    [x-cloak] { display: none !important; }
    #snap-container, #snap-container iframe { 
        width: 100% !important; 
        min-height: 500px !important;
        border: none !important; 
    }
</style>

<script>
function openPaymentModal() {
    const modal = document.getElementById('payment-modal');
    const container = document.getElementById('payment-modal-container');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closePaymentModal() {
    const modal = document.getElementById('payment-modal');
    const container = document.getElementById('payment-modal-container');
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('payment-loading').classList.remove('hidden');
        document.getElementById('snap-container-wrapper').classList.add('hidden');
        document.getElementById('snap-container').innerHTML = '';
        document.getElementById('modal-order-info').classList.add('hidden');
    }, 400);
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php include __DIR__ . '/../../layouts/header.php'; ?>
<div class="flex min-h-screen bg-[#F4F9F4] font-sans" x-data="{ showDeleteModal: false, deleteAction: '' }">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <main class="flex-1 overflow-y-auto p-4 md:p-8 pt-4 md:pt-8" x-data="{ 
            selectedProduct: null, 
            showDetail: false,
            openDetail(p) {
                this.selectedProduct = p;
                this.showDetail = true;
            }
        }">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">Inventori Produk</h2>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">Kelola dan pantau daftar produk toko Anda.</p>
                </div>
                <a href="<?= url('/vendor/products/create') ?>" class="w-full md:w-auto bg-[#056526] hover:bg-green-800 text-white px-6 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Produk
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-3 md:grid-cols-3 gap-3 md:gap-6 mb-8">
                <div class="bg-white p-3 md:p-6 rounded-xl md:rounded-2xl border border-gray-200 shadow-sm text-center md:text-left">
                    <p class="text-[8px] md:text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total</p>
                    <h3 class="text-sm md:text-2xl font-bold text-gray-900"><?php echo count($products ?? []); ?></h3>
                </div>
                <div class="bg-white p-3 md:p-6 rounded-xl md:rounded-2xl border border-gray-200 shadow-sm text-center md:text-left">
                    <p class="text-[8px] md:text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Aktif</p>
                    <h3 class="text-sm md:text-2xl font-bold text-[#056526]"><?php echo count(array_filter($products ?? [], fn($p) => ($p['status'] ?? '') === 'published')); ?></h3>
                </div>
                <div class="bg-white p-3 md:p-6 rounded-xl md:rounded-2xl border border-gray-200 shadow-sm text-center md:text-left">
                    <p class="text-[8px] md:text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Stok Tipis</p>
                    <h3 class="text-sm md:text-2xl font-bold text-orange-500"><?php echo count(array_filter($products ?? [], fn($p) => ($p['stock'] ?? 0) <= 5)); ?></h3>
                </div>
            </div>

            <!-- Product List -->
            <div class="bg-white md:rounded-2xl md:border border-gray-200 shadow-sm overflow-hidden -mx-4 md:mx-0">
                <!-- Mobile Grid -->
                <div class="grid grid-cols-2 gap-px bg-gray-100 md:hidden">
                    <?php if (empty($products)): ?>
                        <div class="col-span-2 bg-white py-12 text-center text-gray-500 text-xs">Tidak ada produk ditemukan.</div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div @click="openDetail(<?= htmlspecialchars(json_encode($product)) ?>)" class="bg-white p-4 space-y-3 active:bg-gray-50 transition-colors">
                                <div class="aspect-square rounded-xl bg-gray-50 overflow-hidden border border-gray-100">
                                    <img src="<?= url($product['primary_image'] ?? '') ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($product['name']) ?>&background=random';">
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-900 text-[11px] truncate"><?= htmlspecialchars($product['name']) ?></div>
                                    <div class="text-[10px] font-black text-[#056526] mt-0.5">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <span class="px-1.5 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-tighter bg-gray-100 text-gray-500 border border-gray-200">Stok: <?= $product['stock'] ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Produk</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Kategori</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Harga</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Stok</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-xs">Tidak ada produk ditemukan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-lg bg-gray-50 overflow-hidden border border-gray-100">
                                                    <img src="<?= url($product['primary_image'] ?? '') ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($product['name']) ?>&background=random';">
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($product['name']) ?></div>
                                                    <div class="text-[10px] text-gray-400 font-bold uppercase">ID: <?= substr($product['id'], 0, 8) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100"><?= htmlspecialchars($product['category_name'] ?? 'Umum') ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-sm">Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= $product['stock'] ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-green-50 text-green-700 border border-green-100"><?= ucfirst($product['status'] ?? 'published') ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end gap-3">
                                                <a href="<?= url('/vendor/products/edit/' . $product['id']) ?>" class="text-gray-400 hover:text-[#056526] transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <button @click="showDeleteModal = true; deleteAction = '<?= url('/vendor/products/delete/' . $product['id']) ?>'" class="text-gray-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Product Detail Modal (Mobile) -->
            <div x-show="showDetail" class="fixed inset-0 z-[100] flex items-end md:hidden" x-cloak>
                <div x-show="showDetail" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDetail = false"></div>
                <div x-show="showDetail" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" class="relative w-full bg-white rounded-t-[2.5rem] p-8 shadow-2xl">
                    <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8"></div>
                    <div class="flex gap-5 mb-8">
                        <div class="w-24 h-24 rounded-2xl bg-gray-50 overflow-hidden border border-gray-100 flex-shrink-0">
                            <img :src="'<?= url('') ?>' + (selectedProduct?.primary_image || '')" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-black text-gray-900 leading-tight mb-1" x-text="selectedProduct?.name"></h3>
                            <p class="text-xl font-black text-[#056526]" x-text="'Rp ' + (parseInt(selectedProduct?.price) || 0).toLocaleString('id-ID')"></p>
                            <div class="mt-3 flex gap-2">
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase bg-blue-50 text-blue-600 border border-blue-100" x-text="selectedProduct?.category_name || 'Umum'"></span>
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase bg-orange-50 text-orange-600 border border-orange-100" x-text="'Stok: ' + selectedProduct?.stock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a :href="'<?= url('/vendor/products/edit/') ?>' + selectedProduct?.id" class="flex items-center justify-center gap-2 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition-all text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Produk
                        </a>
                        <button @click="showDeleteModal = true; deleteAction = '<?= url('/vendor/products/delete/') ?>' + selectedProduct?.id; showDetail = false" class="py-3.5 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-100 transition-all text-xs">Hapus</button>
                    </div>
                    <button @click="showDetail = false" class="w-full mt-3 py-3.5 text-gray-400 font-black text-[10px] uppercase tracking-widest">Tutup</button>
                </div>
            </div>
        </main>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-transition>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl border border-gray-100" @click.away="showDeleteModal = false">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 text-center mb-2">Hapus Produk?</h3>
            <p class="text-sm text-gray-500 text-center mb-8">Tindakan ini tidak dapat dibatalkan. Listing produk akan dihapus secara permanen.</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 py-3 bg-gray-50 text-gray-600 font-bold rounded-2xl hover:bg-gray-100 transition-colors">Batal</button>
                <form :action="deleteAction" method="POST" class="flex-1">
                    <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-colors shadow-lg">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>

<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-[#F4F9F4] min-h-screen">
    <!-- Vendor Header: Cover Style -->
    <!-- Vendor Header: Cover Style -->
    <div class="h-40 md:h-72 relative">
        <?php if ($vendor['store_banner']): ?>
            <img src="<?= url($vendor['store_banner']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
            <div class="w-full h-full bg-gradient-to-r from-emerald-900 to-primary"></div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    <!-- Store Profile Info Section -->
    <div class="max-w-[90rem] mx-auto px-4 md:px-8 relative">
        <div class="flex flex-col md:flex-row items-center md:items-end gap-4 md:gap-8 -mt-16 md:-mt-20">
            <!-- Store Logo -->
            <div class="w-32 h-32 md:w-44 md:h-44 rounded-3xl md:rounded-[3rem] bg-white p-1.5 md:p-2 shadow-2xl relative z-10 overflow-hidden border-4 border-white/10">
                <?php if ($vendor['store_logo']): ?>
                    <img src="<?= url($vendor['store_logo']) ?>" class="w-full h-full object-cover rounded-2xl md:rounded-[2.8rem]">
                <?php else: ?>
                    <div class="w-full h-full bg-emerald-100 text-primary flex items-center justify-center text-4xl md:text-6xl font-black rounded-2xl md:rounded-[2.8rem]">
                        <?= strtoupper(substr($vendor['store_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Store Description & Name -->
            <div class="flex-1 text-center md:text-left mb-4 md:mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-center md:justify-start gap-3 mb-3">
                    <h1 class="text-2xl md:text-5xl font-black text-gray-900 md:text-white drop-shadow-none md:drop-shadow-[0_4px_8px_rgba(0,0,0,0.4)] tracking-tight"><?= htmlspecialchars($vendor['store_name']) ?></h1>
                    <span class="inline-block mx-auto md:mx-0 w-fit px-3 py-1 bg-primary md:bg-white/20 backdrop-blur-md border border-white/30 text-white text-[9px] font-black rounded-full uppercase tracking-widest">Verified Partner</span>
                </div>
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-2 md:gap-3">
                    <div class="flex items-center gap-2 text-[10px] md:text-[11px] font-black text-gray-500 md:text-white bg-white md:bg-black/20 backdrop-blur-sm px-3 py-1.5 md:py-1 rounded-full border border-gray-100 md:border-white/10 uppercase tracking-tighter shadow-sm md:shadow-none">
                        <svg class="w-3 h-3 text-emerald-500 md:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <?= htmlspecialchars($vendor['location'] ?? 'Jakarta Selatan, ID') ?>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] md:text-[11px] font-black text-gray-500 md:text-white bg-white md:bg-black/20 backdrop-blur-sm px-3 py-1.5 md:py-1 rounded-full border border-gray-100 md:border-white/10 uppercase tracking-tighter shadow-sm md:shadow-none">
                        <svg class="w-3 h-3 text-emerald-500 md:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Bergabung <?= date('M Y', strtotime($vendor['created_at'])) ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pb-6 flex items-center justify-center gap-3 w-full md:w-auto">
                <?php if (\App\Core\Session::get('user_id') == $vendor['user_id']): ?>
                    <a href="<?= url('/vendor/settings') ?>" class="flex-1 md:flex-none px-6 py-3 bg-white text-primary font-black rounded-2xl hover:scale-105 transition-all shadow-xl shadow-green-900/10 border border-gray-100 text-center text-sm">
                        <i class="fa-solid fa-gear mr-2"></i> Edit Toko
                    </a>
                <?php else: ?>
                    <button class="flex-1 md:flex-none px-6 py-3 bg-white text-primary font-black rounded-2xl hover:scale-105 transition-all shadow-xl shadow-green-900/10 border border-gray-100 text-sm">
                        Ikuti
                    </button>
                    <button class="flex-1 md:flex-none px-6 py-3 bg-primary text-white font-black rounded-2xl hover:scale-105 transition-all shadow-xl shadow-green-900/10 text-sm uppercase tracking-widest">
                        Pesan
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mt-8 md:mt-16 mb-12">
            <div class="bg-white rounded-3xl p-4 md:p-6 shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4 group hover:border-emerald-200 transition-colors">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-emerald-50 rounded-xl md:rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <div class="text-lg md:text-xl font-black text-gray-900 leading-none mb-1"><?= count($products) ?></div>
                    <div class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest leading-tight">Produk Aktif</div>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-4 md:p-6 shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4 group hover:border-amber-200 transition-colors">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-amber-50 rounded-xl md:rounded-2xl flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div>
                    <div class="text-lg md:text-xl font-black text-gray-900 leading-none mb-1">4.9</div>
                    <div class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest leading-tight">Rating Toko</div>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-4 md:p-6 shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4 group hover:border-blue-200 transition-colors">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <div>
                    <div class="text-lg md:text-xl font-black text-gray-900 leading-none mb-1">99%</div>
                    <div class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest leading-tight">Balas Chat</div>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-4 md:p-6 shadow-sm border border-gray-100 flex items-center gap-3 md:gap-4 group hover:border-emerald-200 transition-colors">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-50 rounded-xl md:rounded-2xl flex items-center justify-center text-gray-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <div class="text-lg md:text-xl font-black text-gray-900 leading-none mb-1">10k+</div>
                    <div class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest leading-tight">Pengikut</div>
                </div>
            </div>
        </div>

        <!-- Inventory Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 md:gap-0 border-b border-gray-200/50 pb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="w-8 h-1.5 bg-primary rounded-full"></span>
                    Koleksi Produk
                </h2>
                <p class="text-xs md:text-sm text-gray-400 font-bold uppercase tracking-widest mt-1">Produk pilihan dari <?= htmlspecialchars($vendor['store_name']) ?></p>
            </div>
            <div class="flex items-center gap-4">
                <select class="w-full md:w-auto bg-white border border-gray-100 rounded-xl px-4 py-2.5 text-[10px] font-black text-gray-600 focus:outline-none focus:ring-4 focus:ring-primary/5 shadow-sm uppercase tracking-widest">
                    <option>Urutkan: Terbaru</option>
                    <option>Harga: Rendah ke Tinggi</option>
                    <option>Harga: Tinggi ke Rendah</option>
                </select>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="bg-white rounded-[2rem] p-12 md:p-24 text-center border border-gray-100 shadow-xl shadow-green-900/5">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">📦</div>
                <h3 class="text-xl font-black text-gray-900 mb-2 whitespace-nowrap">Belum ada produk</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest italic">Toko ini masih mempersiapkan stok terbaik.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-8">
                <?php foreach($products as $product): ?>
                    <a href="<?= url('/products/' . $product['id']) ?>" class="group bg-white rounded-3xl md:rounded-[2.5rem] border border-gray-50 overflow-hidden hover:shadow-2xl hover:-translate-y-2 md:hover:-translate-y-3 transition-all duration-500">
                        <div class="aspect-square relative overflow-hidden bg-slate-50">
                            <?php if(isset($product['primary_image']) && $product['primary_image']): ?>
                                <img src="<?= url($product['primary_image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <img src="https://picsum.photos/seed/p-<?= $product['id'] ?>/400/400" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php endif; ?>
                            
                            <div class="absolute top-2 right-2 md:top-4 md:right-4 md:translate-y-4 md:opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-lg text-primary">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 md:p-8">
                            <span class="text-[8px] md:text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1 md:mb-2 block line-clamp-1"><?= htmlspecialchars($product['category_name'] ?? 'General') ?></span>
                            <h3 class="font-black text-gray-900 mb-1 group-hover:text-primary transition-colors line-clamp-1 text-xs md:text-lg italic tracking-tight uppercase"><?= htmlspecialchars($product['name']) ?></h3>
                            <div class="flex items-center gap-1 mb-3 md:mb-4">
                                <div class="flex text-amber-400">
                                    <svg class="w-2.5 h-2.5 md:w-3 md:h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <span class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase">4.9 (12)</span>
                            </div>
                            <div class="flex items-center justify-between pt-3 md:pt-6 border-t border-gray-50">
                                <div class="text-primary font-black text-sm md:text-xl">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                <div class="w-7 h-7 md:w-10 md:h-10 rounded-lg md:rounded-2xl bg-emerald-50 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

    </main>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

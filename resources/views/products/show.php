<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="w-full bg-[#F4F9F4] min-h-screen font-sans text-[#1A1A1A]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumbs -->
        <div class="overflow-hidden -mx-4 px-4 md:mx-0 md:px-0">
            <nav class="flex text-[10px] md:text-[11px] text-gray-500 mb-6 md:mb-8 items-center gap-1.5 md:gap-2 font-medium tracking-wide overflow-x-auto whitespace-nowrap no-scrollbar">
                <a href="<?= url('/') ?>" class="hover:text-black shrink-0">Beranda</a>
                <span class="text-gray-300 shrink-0">›</span>
                <a href="<?= url('/products') ?>" class="hover:text-black shrink-0">Pasar</a>
                <span class="text-gray-300 shrink-0">›</span>
                <a href="<?= url('/products?category=' . ($product['category_id'] ?? '')) ?>" class="hover:text-black shrink-0 text-ellipsis overflow-hidden max-w-[100px]"><?php echo htmlspecialchars($product['category_name'] ?? 'Kategori'); ?></a>
                <span class="text-gray-300 shrink-0">›</span>
                <span class="text-gray-900 font-bold shrink-0 text-ellipsis overflow-hidden max-w-[120px] md:max-w-none"><?php echo htmlspecialchars($product['name']); ?></span>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start mb-16">
            <!-- Left Column: Media -->
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Thumbnails -->
                <div class="overflow-hidden -mx-4 px-4 md:mx-0 md:px-0 order-2 md:order-1">
                    <div class="flex md:flex-col gap-2 md:gap-3 overflow-x-auto md:overflow-y-auto no-scrollbar pb-2 md:pb-0">
                        <?php if (empty($images)): ?>
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-300 text-[9px] font-bold uppercase shrink-0">No Img</div>
                        <?php else: ?>
                            <?php foreach ($images as $index => $img): ?>
                                <button onclick="document.getElementById('main-image').src = '<?= url($img['image_path']) ?>'; document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('border-black')); this.classList.add('border-black')" 
                                        class="thumb-btn w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden border-2 border-transparent bg-white flex-shrink-0 transition-all hover:border-black/20 shadow-sm shrink-0">
                                    <img src="<?= url($img['image_path']) ?>" class="w-full h-full object-cover">
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Main Preview -->
                <div class="order-1 md:order-2 flex-1 relative aspect-square rounded-[2rem] overflow-hidden bg-white border border-gray-100 flex items-center justify-center p-4 shadow-sm">
                    <img id="main-image" src="<?= url($images[0]['image_path'] ?? 'assets/img/placeholder.jpg') ?>" 
                         class="w-full h-full object-contain" 
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($product['name']) ?>&background=056526&color=fff';">
                    
                    <!-- Floating Controls -->
                    <div class="absolute top-6 right-6 flex flex-col gap-3">
                        <button class="w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-md hover:scale-110 transition-transform text-gray-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                        <button class="w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-md hover:scale-110 transition-transform text-gray-400 hover:text-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Info & Buy -->
            <div class="space-y-8">
                <div>
                    <span class="inline-block px-3 py-1 bg-[#1E6B3E] text-white text-[9px] font-black uppercase tracking-widest rounded-md mb-3 md:mb-4">
                        PILIHAN TERBAIK
                    </span>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold tracking-tight mb-3 md:mb-4 leading-tight"><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="flex flex-wrap items-center gap-3 md:gap-4 text-xs md:text-sm">
                        <div class="flex text-amber-500">
                            <?php for($i=0; $i<5; $i++): ?>
                                <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-gray-400 font-medium">(128 ulasan)</span>
                        <span class="hidden xs:block w-1 h-1 bg-gray-300 rounded-full"></span>
                        <span class="text-[#1E6B3E] font-bold">Stok Tersedia</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-3 md:gap-4">
                    <span class="text-3xl md:text-4xl font-bold text-[#1E6B3E] tracking-tight">Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                    <span class="text-base md:text-lg text-gray-400 line-through">Rp<?php echo number_format($product['price'] * 1.25, 0, ',', '.'); ?></span>
                    <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-black rounded">-20% OFF</span>
                </div>

                <!-- Actions -->
                <div class="grid grid-cols-1 md:space-y-4 gap-3 md:block">
                <!-- Add to Cart: AJAX with fly animation -->
                <div class="flex gap-3 md:gap-4">
                    <div class="flex items-center bg-white border border-gray-200 rounded-xl h-12 md:h-14 w-28 md:w-32 shadow-sm shrink-0">
                        <button type="button" onclick="const input = document.getElementById('qty-input'); if(input.value > 1) { input.value--; document.querySelectorAll('.qty-sync').forEach(i => i.value = input.value); }" class="flex-1 h-full font-bold text-gray-400">-</button>
                        <input type="number" id="qty-input" name="quantity" value="1" min="1" max="<?php echo $product['stock'] ?? 99; ?>" class="w-8 md:w-10 text-center font-bold border-none focus:ring-0 bg-transparent p-0 qty-sync text-sm">
                        <button type="button" onclick="const input = document.getElementById('qty-input'); if(input.value < <?php echo $product['stock'] ?? 99; ?>) { input.value++; document.querySelectorAll('.qty-sync').forEach(i => i.value = input.value); }" class="flex-1 h-full font-bold text-gray-400">+</button>
                    </div>
                    <button type="button" id="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>" class="flex-1 h-12 md:h-14 bg-[#1E6B3E] text-white font-bold rounded-xl hover:opacity-90 transition-all shadow-md shadow-green-900/10 text-sm md:text-base relative overflow-hidden">
                        <span id="btn-text">Masuk Keranjang</span>
                        <span id="btn-loading" class="hidden absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        </span>
                    </button>
                </div>
                    
                    <form action="<?= url('/checkout/buynow') ?>" method="POST" class="w-full">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1" class="qty-sync">
                        <button type="submit" class="w-full h-12 md:h-14 bg-[#2D2D2D] text-white font-bold rounded-xl hover:bg-black transition-all shadow-md shadow-black/10 text-sm md:text-base">
                            Beli Langsung
                        </button>
                    </form>
                </div>

                <!-- Value Props -->
                <div class="grid grid-cols-3 gap-4 pt-8 border-t border-gray-100">
                    <div class="text-center group">
                        <div class="w-10 h-10 mx-auto bg-[#1E6B3E]/5 text-[#1E6B3E] rounded-full flex items-center justify-center mb-2 group-hover:bg-[#1E6B3E] group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest whitespace-nowrap">Pengiriman Nasional</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-10 h-10 mx-auto bg-[#1E6B3E]/5 text-[#1E6B3E] rounded-full flex items-center justify-center mb-2 group-hover:bg-[#1E6B3E] group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-7.618 3.015L4.5 9.281a11.947 11.947 0 001.618 6.518 11.948 11.948 0 005.882 5.256l11.764-10.512a11.95 11.95 0 001.618-6.518z"></path></svg>
                        </div>
                        <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest whitespace-nowrap">Garansi 2 Tahun</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-10 h-10 mx-auto bg-[#1E6B3E]/5 text-[#1E6B3E] rounded-full flex items-center justify-center mb-2 group-hover:bg-[#1E6B3E] group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                        </div>
                        <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest whitespace-nowrap">Retur 30 Hari</p>
                    </div>
                </div>

                <!-- Seller Card -->
                <div class="p-4 md:p-5 bg-white rounded-2xl border border-gray-100 flex flex-col sm:flex-row items-center sm:justify-between shadow-sm gap-4">
                    <div class="flex items-center gap-4 w-full sm:w-auto">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center font-bold text-lg text-gray-500 shrink-0">
                            <?php echo strtoupper(substr($product['store_name'] ?? 'L', 0, 2)); ?>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm leading-none mb-1 truncate"><?php echo htmlspecialchars($product['store_name'] ?? 'LitleMart Electronics'); ?></p>
                            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                <span class="text-amber-500">★</span> 4.9 Rating Penjual
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <?php if (\App\Core\Session::get('user_id') != ($product['user_id'] ?? null)): ?>
                            <a href="<?= url('/messages?vendor_id=' . ($product['vendor_id'] ?? '')) ?>" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-emerald-50 text-primary text-[10px] md:text-[11px] font-black rounded-lg hover:bg-emerald-100 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-comments"></i> Chat
                            </a>
                        <?php endif; ?>
                        <a href="<?= url('/store/' . ($product['vendor_id'] ?? '')) ?>" class="flex-1 sm:flex-none justify-center px-4 py-2.5 border border-black/10 text-[10px] md:text-[11px] font-bold rounded-lg hover:bg-gray-50 uppercase tracking-widest text-center whitespace-nowrap">Toko</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs -->
        <div class="mb-10 md:mb-20" x-data="{ activeTab: 'description' }">
            <div class="overflow-hidden -mx-4 px-4 md:mx-0 md:px-0">
                <div class="flex gap-6 md:gap-10 border-b border-gray-200 mb-6 md:mb-10 text-sm font-bold text-gray-400 overflow-x-auto no-scrollbar whitespace-nowrap">
                    <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-[#1E6B3E] text-[#1E6B3E]' : 'border-transparent hover:text-gray-900'" class="pb-4 border-b-2 transition-all">Deskripsi</button>
                    <button @click="activeTab = 'specifications'" :class="activeTab === 'specifications' ? 'border-[#1E6B3E] text-[#1E6B3E]' : 'border-transparent hover:text-gray-900'" class="pb-4 border-b-2 transition-all">Spesifikasi</button>
                    <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-[#1E6B3E] text-[#1E6B3E]' : 'border-transparent hover:text-gray-900'" class="pb-4 border-b-2 transition-all">Ulasan (0)</button>
                    <button @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'border-[#1E6B3E] text-[#1E6B3E]' : 'border-transparent hover:text-gray-900'" class="pb-4 border-b-2 transition-all text-balance">Pengiriman & Retur</button>
                </div>
            </div>
            
            <div class="max-w-4xl">
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" x-cloak class="space-y-8">
                    <div class="text-gray-500 font-medium leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 pt-10 border-t border-gray-100">
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-[#1E6B3E]/10 text-[#1E6B3E] flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-1">Produk Asli</p>
                                <p class="text-xs text-gray-400 font-bold uppercase leading-tight">100% terjamin keasliannya oleh LitleMart.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-[#1E6B3E]/10 text-[#1E6B3E] flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-1">Dikurasi Ahli</p>
                                <p class="text-xs text-gray-400 font-bold uppercase leading-tight">Dipilih khusus untuk koleksi premium kami.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div x-show="activeTab === 'specifications'" x-cloak class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Berat Bersih</p>
                            <p class="text-sm font-bold text-gray-900"><?php echo number_format($product['weight'], 0, ',', '.'); ?> gram</p>
                        </div>
                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Kondisi</p>
                            <p class="text-sm font-bold text-gray-900 uppercase"><?php echo htmlspecialchars($product['condition'] ?? 'Baru'); ?></p>
                        </div>
                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Produsen</p>
                            <p class="text-sm font-bold text-gray-900 uppercase"><?php echo htmlspecialchars($product['brand'] ?? 'Universal'); ?></p>
                        </div>
                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Kategori</p>
                            <p class="text-sm font-bold text-gray-900 uppercase"><?php echo htmlspecialchars($product['category_name']); ?></p>
                        </div>
                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">SKU Token</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">LM-<?php echo strtoupper(substr($product['id'], 0, 12)); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" x-cloak>
                    <div class="py-12 text-center bg-white border border-gray-100 rounded-3xl">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <p class="text-gray-900 font-bold mb-1">Belum ada ulasan</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Jadilah yang pertama berbagi pengalaman Anda dengan produk ini.</p>
                    </div>
                </div>

                <!-- Shipping Tab -->
                <div x-show="activeTab === 'shipping'" x-cloak class="space-y-6">
                    <div class="bg-white border border-gray-100 rounded-3xl p-8 space-y-6">
                        <div>
                            <h5 class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em] mb-4">Pengiriman Cepat ke Seluruh Indonesia</h5>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">
                                Kami bekerja sama dengan berbagai kurir terpercaya (JNE, J&T, SiCepat, dll). Sebagian besar pesanan diproses dalam 24 jam. Estimasi pengiriman 1–7 hari kerja tergantung lokasi Anda.
                            </p>
                        </div>
                        <div class="h-px bg-gray-50"></div>
                        <div>
                            <h5 class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em] mb-4">Pengembalian Mudah</h5>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">
                                Tidak puas? Tidak masalah. Pengembalian diterima dalam 30 hari setelah pengiriman. Produk harus dalam kondisi asli dan belum digunakan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-10 md:pt-20 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6 md:mb-12">
                <h2 class="text-xl md:text-2xl font-bold tracking-tight">Produk Serupa</h2>
                <a href="<?= url('/products') ?>" class="text-xs md:text-sm font-bold flex items-center gap-2 hover:translate-x-1 transition-transform text-slate-500">Lihat Semua <span class="text-lg">→</span></a>
            </div>

            <div class="flex flex-nowrap md:grid md:grid-cols-4 gap-4 md:gap-8 overflow-x-auto no-scrollbar -mx-4 px-4 md:mx-0 md:px-0 scroll-smooth">
                <?php foreach($relatedProducts as $rp): ?>
                    <a href="<?= url('/products/' . $rp['id']) ?>" class="group flex-shrink-0 w-[160px] md:w-auto flex flex-col bg-white rounded-2xl md:rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl transition-all aspect-[2/3] md:aspect-auto">
                        <div class="h-[60%] md:aspect-square relative overflow-hidden bg-white p-2 md:p-6">
                            <?php if(!empty($rp['primary_image'])): ?>
                                <img src="<?= url($rp['primary_image']) ?>" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <img src="https://picsum.photos/seed/prod-<?= $rp['id'] ?>/400/400" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                            <?php endif; ?>
                        </div>
                        <div class="p-3 md:p-6 flex flex-col flex-1 min-w-0">
                            <span class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1 truncate"><?= htmlspecialchars($rp['category_name'] ?? 'Category') ?></span>
                            <h3 class="font-bold text-gray-900 text-[10px] md:text-sm line-clamp-1 mb-1 group-hover:text-[#1E6B3E] transition-colors"><?= htmlspecialchars($rp['name']) ?></h3>
                            <div class="mt-auto flex items-center justify-between pt-1">
                                <p class="text-[10px] md:text-base font-black text-[#1E6B3E]">Rp<?= number_format($rp['price'], 0, ',', '.') ?></p>
                                <div class="flex items-center gap-1 text-[8px] md:text-[10px] font-bold text-gray-400">
                                    <span class="text-amber-500">★</span> 4.9
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                
                <?php if(empty($relatedProducts)): ?>
                    <p class="text-gray-400 text-sm italic col-span-4">Tidak ada produk terkait ditemukan.</p>
                <?php endif; ?>
            </div>        </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addBtn     = document.getElementById('add-to-cart-btn');
    const qtyInput   = document.getElementById('qty-input');
    if (!addBtn) return;

    /* ── Click handler ────────────────────────────────────────── */
    addBtn.addEventListener('click', function (e) {
        if (addBtn.disabled) return;
        addBtn.disabled = true;

        const productId = addBtn.dataset.productId;
        const qty       = parseInt(qtyInput ? qtyInput.value : 1) || 1;
        const cartBadge = document.getElementById('cart-badge');

        /* source = centre of button */
        const br   = addBtn.getBoundingClientRect();
        const ox   = br.left + br.width  / 2;
        const oy   = br.top  + br.height / 2;

        /* dest = centre of cart icon */
        const cartIcon = document.getElementById('navbar-cart-icon');
        let dx = window.innerWidth - 20, dy = 20;
        if (cartIcon) {
            const cr = cartIcon.getBoundingClientRect();
            dx = cr.left + cr.width  / 2;
            dy = cr.top  + cr.height / 2;
        }

        /* fire beam via global function in navbar.php */
        if (window.shootLightsaber) {
            window.shootLightsaber(ox, oy, dx, dy, function () {
                if (cartBadge) {
                    cartBadge.classList.remove('badge-pop');
                    void cartBadge.offsetWidth;
                    cartBadge.classList.add('badge-pop');
                }
            });
        }

        /* AJAX */
        fetch('<?= url('/cart/add') ?>', {
            method : 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded',
                       'X-Requested-With': 'XMLHttpRequest' },
            body   : 'product_id=' + encodeURIComponent(productId) + '&quantity=' + qty
        })
        .then(r => r.json())
        .then(data => {
            if (cartBadge && data.cart_count !== undefined) {
                cartBadge.textContent = data.cart_count;
                cartBadge.classList.remove('hidden');
            }
            addBtn.disabled = false;
        })
        .catch(() => { addBtn.disabled = false; });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
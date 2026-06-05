<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12">
    <div class="flex flex-col lg:flex-row gap-6 md:gap-8" x-data="{ filterOpen: false }">
        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden flex items-center gap-3 mb-2">
            <button @click="filterOpen = !filterOpen" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:border-[#056526] hover:text-[#056526] transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter & Sort
            </button>
            <span class="text-xs font-medium text-gray-400">Menampilkan <span class="text-[#056526] font-bold"><?= count($products) ?></span> produk</span>
        </div>
        <!-- Sidebar Filters -->
        <div class="w-full lg:w-72 flex-shrink-0" x-show="filterOpen || window.innerWidth >= 1024" :class="filterOpen ? '' : 'hidden lg:block'" x-cloak>
            <form action="<?= url('/products') ?>" method="GET" id="filterForm" class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm lg:sticky lg:top-24">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-black text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#056526]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </h3>
                    <a href="<?= url('/products') ?>" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-red-500">Hapus</a>
                </div>
                <!-- Preserve search query across filter submits -->
                <?php if(!empty($filters['search'])): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($filters['search']) ?>">
                <?php endif; ?>
                
                <div class="space-y-10">
                    <!-- Categories -->
                    <div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-5 border-l-4 border-[#056526] pl-3">Kategori</h4>
                        <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            <?php foreach($categories as $category): ?>
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="category" value="<?= $category['id'] ?>" 
                                    <?= ($filters['category'] ?? '') == $category['id'] ? 'checked' : '' ?>
                                    onchange="this.form.submit()"
                                    class="w-5 h-5 border-slate-200 text-[#056526] focus:ring-[#056526] rounded-full">
                                <span class="ml-3 text-sm font-bold text-slate-600 group-hover:text-[#056526] transition-colors"><?= htmlspecialchars($category['name']) ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-5 border-l-4 border-[#056526] pl-3">Rentang Harga</h4>
                        <div class="space-y-3">
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400">MIN</span>
                                <input type="number" name="min_price" value="<?= $filters['min_price'] ?? '' ?>" placeholder="0" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#056526] outline-none">
                            </div>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400">MAX</span>
                                <input type="number" name="max_price" value="<?= $filters['max_price'] ?? '' ?>" placeholder="Tanpa batas" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#056526] outline-none">
                            </div>
                            <button type="submit" class="w-full py-3 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-[#056526] transition-all shadow-lg">Terapkan Harga</button>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-5 border-l-4 border-[#056526] pl-3">Rating</h4>
                        <div class="space-y-3">
                            <?php for($i=4; $i>=3; $i--): ?>
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="rating" value="<?= $i ?>"
                                    <?= ($filters['rating'] ?? '') == $i ? 'checked' : '' ?>
                                    onchange="this.form.submit()"
                                    class="w-5 h-5 border-slate-200 text-[#056526] focus:ring-[#056526]">
                                <span class="ml-3 text-sm font-bold text-slate-600 flex items-center gap-1">
                                    <?= $i ?>+ <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </span>
                            </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
            <div class="hidden lg:flex flex-col sm:flex-row justify-between items-center mb-10 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Temukan Produk</h2>
                    <p class="text-sm text-slate-400 font-medium tracking-tight">Menampilkan <span class="text-[#056526] font-bold"><?= count($products) ?></span> produk ditemukan</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <select class="w-full sm:w-auto bg-white border border-slate-100 rounded-2xl px-6 py-3 text-xs font-black text-slate-900 outline-none focus:ring-2 focus:ring-[#056526] shadow-sm">
                        <option>Terbaru</option>
                        <option>Harga: Terendah ke Tertinggi</option>
                        <option>Harga: Tertinggi ke Terendah</option>
                        <option>Rating: Tertinggi ke Terendah</option>
                    </select>
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="bg-white rounded-[3rem] p-20 text-center border border-slate-100 shadow-sm">
                    <div class="text-6xl mb-6">🔍</div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Produk tidak ditemukan</h3>
                    <p class="text-slate-400 max-w-xs mx-auto mb-8">Coba sesuaikan filter atau kata kunci pencarian untuk menemukan yang Anda cari.</p>
                    <a href="<?= url('/products') ?>" class="inline-block px-8 py-3 bg-slate-900 text-white font-black rounded-2xl hover:bg-[#056526] transition-all">Hapus Semua Filter</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-8">
                    <?php foreach($products as $product): ?>
                        <a href="<?= url('/products/' . $product['id']) ?>" class="group flex flex-col bg-white rounded-2xl md:rounded-[2.5rem] border border-gray-100 shadow-lg shadow-slate-200/60 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 aspect-[2/3] md:aspect-auto">
                            <div class="h-[65%] md:aspect-square relative overflow-hidden bg-slate-50">
                                <?php if(!empty($product['primary_image'])): ?>
                                    <img src="<?= url($product['primary_image']) ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <?php else: ?>
                                    <img src="https://picsum.photos/seed/prod-<?= $product['id'] ?>/400/400" alt="Product" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <?php endif; ?>
                                <div class="absolute top-4 left-4 z-10 px-2.5 py-1 bg-white/90 backdrop-blur-md rounded-full text-[8px] font-black uppercase tracking-widest text-slate-900 border border-white/20">
                                    <?= htmlspecialchars($product['category_name']) ?>
                                </div>
                            </div>
                            <div class="p-3 md:p-8 flex flex-col flex-1 min-w-0">
                                <div class="mb-1 md:mb-4">
                                    <div class="text-[8px] md:text-[10px] font-black text-[#056526] uppercase tracking-widest mb-1 flex justify-between items-center">
                                        <span class="truncate pr-1"><?= htmlspecialchars($product['store_name'] ?? 'Official Store') ?></span>
                                        <div class="flex items-center gap-0.5 text-yellow-400 scale-75 origin-right">
                                            <i class="fa-solid fa-star"></i>
                                            <span class="text-slate-900 font-bold">4.9</span>
                                        </div>
                                    </div>
                                    <h3 class="font-black text-slate-900 text-[11px] md:text-sm line-clamp-2 min-h-[32px] md:min-h-[48px] leading-tight group-hover:text-[#056526] transition-colors mb-1 md:mb-3"><?= htmlspecialchars($product['name']) ?></h3>
                                    <div class="text-sm md:text-2xl font-black text-[#056526] tracking-tight">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                </div>
                                <div class="mt-auto pt-2 md:pt-6 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-1 text-slate-400">
                                        <i class="fa-solid fa-location-dot text-[9px] md:text-xs"></i>
                                        <span class="text-[7px] md:text-[9px] font-black uppercase tracking-widest truncate">JKT</span>
                                    </div>
                                    <button onclick="event.preventDefault(); addToCart('<?= $product['id'] ?>', event)" class="w-7 h-7 md:w-10 md:h-10 rounded-lg md:rounded-2xl bg-slate-900 text-white flex items-center justify-center hover:bg-[#056526] transition-all shadow-xl relative z-20">
                                        <i class="fa-solid fa-plus text-[10px] md:text-base pointer-events-none"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <script>
                function addToCart(productId, event) {
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('quantity', 1);

                    if (window.shootLightsaber && event) {
                        const rect = event.currentTarget.getBoundingClientRect();
                        const ox = rect.left + rect.width / 2;
                        const oy = rect.top + rect.height / 2;
                        const cartIcon = document.getElementById('navbar-cart-icon');
                        let dx = window.innerWidth - 20, dy = 20;
                        if (cartIcon) {
                            const cr = cartIcon.getBoundingClientRect();
                            dx = cr.left + cr.width / 2;
                            dy = cr.top + cr.height / 2;
                        }
                        window.shootLightsaber(ox, oy, dx, dy);
                    }

                    fetch('<?= url('/cart/add') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const badge = document.getElementById('cart-badge');
                        if (badge && data.cart_count !== undefined) {
                            badge.textContent = data.cart_count;
                            badge.classList.remove('hidden');
                            badge.classList.remove('badge-pop');
                            void badge.offsetWidth;
                            badge.classList.add('badge-pop');
                        }
                    });
                }
                </script>

                <!-- Pagination -->
                <div class="mt-20 flex justify-center">
                    <nav class="inline-flex gap-2 p-2 bg-white border border-slate-100 rounded-3xl shadow-sm">
                        <a href="#" class="w-12 h-12 flex items-center justify-center rounded-2xl text-slate-400 hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <a href="#" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-900 text-white font-black">1</a>
                        <a href="#" class="w-12 h-12 flex items-center justify-center rounded-2xl text-slate-600 hover:bg-slate-50 font-black transition-colors">2</a>
                        <a href="#" class="w-12 h-12 flex items-center justify-center rounded-2xl text-slate-600 hover:bg-slate-50 font-black transition-colors">3</a>
                        <a href="#" class="w-12 h-12 flex items-center justify-center rounded-2xl text-slate-400 hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

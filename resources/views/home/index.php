<?php 
    $isHomePage = true;
    include __DIR__ . '/../layouts/header.php'; 
?>
<?php 
    $initial_pool = $featured_products;
    if(!empty($initial_pool)) {
        shuffle($initial_pool);
    }
    $products_json = json_encode(array_map(function($p) {
        return [
            'id'              => $p['id'],
            'name'            => $p['name'],
            'formatted_price' => 'Rp ' . number_format($p['price'], 0, ',', '.'),
            'stock'           => (int)$p['stock'],
            'store_name'      => $p['store_name'] ?? 'Toko Resmi',
            'image_url'       => $p['primary_image'] ? url($p['primary_image']) : 'https://picsum.photos/seed/prod-'.$p['id'].'/400/400',
            'detail_url'      => url('/products/' . $p['id'])
        ];
    }, $initial_pool), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    $api_url = url('/api/products/latest');
?>
<script>
    window.__initialProducts = <?= $products_json ?>;
    window.__apiProductsUrl  = "<?= htmlspecialchars($api_url, ENT_QUOTES) ?>";
</script>

<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8"
     x-data="{
        pulling: false,
        pullDistance: 0,
        refreshing: false,
        startY: 0,
        products: [],
        loadingMore: false,
        allLoaded: false,
        offset: 0,

        get displayedProducts() {
            const isDesktop = window.innerWidth >= 1024;
            return isDesktop ? this.products : this.products.slice(0, 6);
        },

        init() {
            this.products = window.__initialProducts || [];
            this.offset   = this.products.length;

            if (window.innerWidth >= 1024) {
                window.addEventListener('scroll', () => {
                    if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 600) {
                        this.loadMore();
                    }
                });
            }
        },

        async loadMore() {
            if (this.loadingMore || this.allLoaded || window.innerWidth < 1024) return;
            this.loadingMore = true;
            try {
                const res  = await fetch(window.__apiProductsUrl + '?limit=8&offset=' + this.offset);
                const data = await res.json();
                if (!Array.isArray(data) || data.length < 8) this.allLoaded = true;
                if (Array.isArray(data)) {
                    this.products = [...this.products, ...data];
                    this.offset  += data.length;
                }
            } catch (e) {
                console.error('Infinite scroll error:', e);
            } finally {
                this.loadingMore = false;
            }
        },

        handleStart(e) {
            if (window.scrollY === 0) {
                this.startY = e.touches[0].pageY;
            }
        },
        handleMove(e) {
            if (window.scrollY === 0 && e.touches[0].pageY > this.startY) {
                const distance = e.touches[0].pageY - this.startY;
                if (distance > 0) {
                    this.pullDistance = Math.min(distance / 2.5, 80);
                    this.pulling = true;
                }
            }
        },
        handleEnd() {
            if (this.pullDistance > 60) {
                this.refreshing = true;
                window.location.reload();
            } else {
                this.pulling = false;
                this.pullDistance = 0;
            }
        }
     }"
     @touchstart="handleStart($event)"
     @touchmove="handleMove($event)"
     @touchend="handleEnd()">

    <!-- Pull to Refresh Indicator -->
    <div x-show="pulling || refreshing" 
         class="fixed top-0 left-0 right-0 z-[110] flex justify-center pointer-events-none transition-all duration-200"
         :style="`transform: translateY(${pullDistance}px); opacity: ${pullDistance / 60}`">
        <div class="bg-white rounded-full p-2 shadow-xl border border-gray-100">
            <svg class="w-6 h-6 text-primary animate-spin" :class="refreshing ? '' : 'animate-none'" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </div>
    </div>
    <!-- Hero Section -->
    <div class="relative rounded-2xl md:rounded-[2rem] overflow-hidden bg-[#bedabe] p-5 md:p-12 mb-6 md:mb-16 shadow-2xl shadow-green-900/20 flex flex-col md:flex-row items-center gap-4 md:gap-8 border border-green-700/10"
         x-data="{ 
            activeSlide: 0, 
            slides: [
                'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&q=80&w=800'
            ],
            next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
            init() {
                setInterval(() => this.next(), 5000)
            }
         }">
        <div class="relative z-10 w-full md:w-1/2">
            <span class="inline-block px-2 py-0.5 bg-white/60 text-green-800 text-[9px] md:text-xs font-bold rounded-full mb-2 md:mb-4 uppercase tracking-wider backdrop-blur-md">Marketplace Premium</span>
            <h1 class="text-2xl md:text-6xl font-black text-[#056526] tracking-tight mb-2 md:mb-4 leading-tight">
                Belanja Produk Terbaik<br class="hidden md:block"/> 
                dari Penjual Terpercaya
            </h1>
            <p class="text-[11px] md:text-lg text-green-900/70 mb-4 md:mb-8 max-w-lg leading-relaxed line-clamp-2 md:line-clamp-none">
                LitleMart menghadirkan produk terbaik dari kurator dan produsen terpercaya ke depan pintu Anda.
            </p>
            <div class="flex flex-wrap items-center gap-3">
                <a href="<?= url('/products') ?>" class="px-4 py-2.5 md:px-6 md:py-3 bg-[#056526] text-white text-xs md:text-base font-bold rounded-xl hover:bg-green-800 transition-all shadow-sm">
                    Mulai Belanja
                </a>
                <a href="<?= url('/about') ?>" class="px-4 py-2.5 md:px-6 md:py-3 bg-white text-[#056526] text-xs md:text-base font-bold rounded-xl hover:bg-green-50 transition-all shadow-sm border border-green-700/10">
                    Cara Kerja
                </a>
            </div>
        </div>
        
        <div class="relative w-full md:w-1/2 h-[180px] md:h-[400px] flex items-center justify-center">
            <div class="absolute inset-0 bg-[#056526] rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl skew-y-1 md:skew-y-3 transform origin-bottom-right opacity-90"></div>
            
            <div class="relative w-full h-full rounded-2xl md:rounded-3xl overflow-hidden z-10 shadow-xl cursor-pointer"
                 @click="next()"
                 @touchstart="touchStartX = $event.touches[0].clientX"
                 @touchend="if (touchStartX - $event.changedTouches[0].clientX > 50) next(); if ($event.changedTouches[0].clientX - touchStartX > 50) prev();"
                 x-data="{ touchStartX: 0 }">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-x-8"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-8"
                         class="absolute inset-0 w-full h-full">
                        <img :src="slide" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                </template>
                
                <!-- Indicators -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-20">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click.stop="activeSlide = index"
                                class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full transition-all duration-300"
                                :class="activeSlide === index ? 'bg-white w-4 md:w-6' : 'bg-white/40'"></button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Badges -->
    <div class="grid grid-cols-3 sm:grid-cols-3 gap-2 md:gap-6 mb-6 md:mb-16">
        <div class="bg-white border border-gray-100 rounded-xl md:rounded-2xl p-3 md:p-6 flex flex-col items-center justify-center text-center aspect-square md:aspect-auto md:flex-row md:items-start md:gap-4 md:text-left transition-all hover:bg-white shadow-lg shadow-slate-200/60 hover:shadow-2xl">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 flex items-center justify-center text-green-700 rounded-lg md:rounded-xl flex-shrink-0 text-base md:text-lg mb-2 md:mb-0">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div>
                <h3 class="font-black md:font-bold text-gray-900 text-[10px] md:text-base leading-none">Free Ongkir</h3>
                <p class="hidden md:block text-xs text-gray-500 mt-1">Gratis ongkir pesanan di atas Rp 50.000.</p>
            </div>
        </div>
        <div class="bg-white border border-gray-100 rounded-xl md:rounded-2xl p-3 md:p-6 flex flex-col items-center justify-center text-center aspect-square md:aspect-auto md:flex-row md:items-start md:gap-4 md:text-left transition-all hover:bg-white shadow-lg shadow-slate-200/60 hover:shadow-2xl">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 flex items-center justify-center text-blue-700 rounded-lg md:rounded-xl flex-shrink-0 text-base md:text-lg mb-2 md:mb-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h3 class="font-black md:font-bold text-gray-900 text-[10px] md:text-base leading-none">100% Aman</h3>
                <p class="hidden md:block text-xs text-gray-500 mt-1">Protokol enkripsi data finansial berlapis.</p>
            </div>
        </div>
        <div class="bg-white border border-gray-100 rounded-xl md:rounded-2xl p-3 md:p-6 flex flex-col items-center justify-center text-center aspect-square md:aspect-auto md:flex-row md:items-start md:gap-4 md:text-left transition-all hover:bg-white shadow-lg shadow-slate-200/60 hover:shadow-2xl">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-red-100 flex items-center justify-center text-red-700 rounded-lg md:rounded-xl flex-shrink-0 text-base md:text-lg mb-2 md:mb-0">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div>
                <h3 class="font-black md:font-bold text-gray-900 text-[10px] md:text-base leading-none">Bantuan</h3>
                <p class="hidden md:block text-xs text-gray-500 mt-1">Dukungan ahli siap membantu Anda.</p>
            </div>
        </div>
    </div>



    <!-- Trending Now (Products) -->
    <div class="mb-8 md:mb-16">
        <div class="hidden md:block text-center mb-10">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Sedang Trending</h2>
            <p class="text-sm text-slate-500 mt-1">Produk paling banyak dicari saat ini</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-6">
            <template x-for="product in displayedProducts" :key="product.id">
                <div class="group flex flex-col bg-white rounded-2xl md:rounded-3xl border border-gray-100 shadow-lg shadow-slate-200/60 overflow-hidden hover:shadow-2xl transition-all duration-500 aspect-[2/3] md:aspect-auto relative">
                    <a :href="product.detail_url" class="h-[65%] md:aspect-square relative overflow-hidden bg-slate-50 block">
                        <img :src="product.image_url" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </a>
                    <div class="p-3 md:p-6 flex flex-col flex-1">
                        <div class="mb-1 md:mb-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest truncate max-w-[60%]" x-text="product.store_name"></span>
                                <div class="flex text-orange-400 scale-[0.6] md:scale-75 origin-right gap-0.5">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <a :href="product.detail_url" class="block">
                                <h3 class="font-black text-slate-900 text-[11px] md:text-sm line-clamp-2 leading-tight group-hover:text-primary transition-colors mb-1 md:mb-2" x-text="product.name"></h3>
                            </a>
                            <div class="text-sm md:text-xl font-black text-[#056526] tracking-tight" x-text="product.formatted_price"></div>
                        </div>
                        
                        <div class="mt-auto pt-2 md:pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[7px] md:text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Stok</span>
                                <span class="text-[9px] md:text-[11px] font-bold" :class="product.stock <= 2 ? 'text-orange-500' : 'text-gray-900'" x-text="product.stock + ' Unit'"></span>
                            </div>
                            <button @click="addToCart(product.id, $event)" 
                                    :disabled="product.stock <= 0"
                                    class="w-7 h-7 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm"
                                    :class="product.stock <= 0 ? 'opacity-20 cursor-not-allowed' : ''">
                                <i class="fa-solid fa-plus text-[10px] md:text-base"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Desktop Load More Indicator -->
        <div x-show="loadingMore" class="hidden md:flex justify-center py-10">
            <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    <!-- AJAX Add to Cart Script -->
    <script>
        function addToCart(productId, event) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            // Trigger Lightsaber effect if available
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
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const badge = document.getElementById('cart-badge');
                    if (badge && data.cart_count !== undefined) {
                        badge.textContent = data.cart_count;
                        badge.classList.remove('hidden');
                        badge.classList.remove('badge-pop');
                        void badge.offsetWidth;
                        badge.classList.add('badge-pop');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
    
    <!-- CTA Section -->
    <div class="bg-gradient-to-br from-[#056526] to-[#044a1d] rounded-2xl md:rounded-[2rem] p-7 md:p-14 overflow-hidden relative shadow-2xl mb-6 md:mb-12">
        <div class="relative z-10 max-w-xl">
            <h2 class="text-xl md:text-3xl font-bold text-white mb-3 md:mb-4">Jadikan Passion Anda Bisnis Global</h2>
            <p class="text-green-100 opacity-70 mb-8 leading-relaxed">Bergabunglah dengan 2.000+ penjual terbaik yang sudah sukses di LitleMart. Akses analitik canggih, logistik terintegrasi, dan basis pelanggan yang luas.</p>
            
            <div class="grid grid-cols-2 gap-3 mb-5 md:mb-8">
                <div class="flex items-center gap-2 text-white">
                    <div class="text-emerald-400">
                        <i class="fa-solid fa-circle-check"></i>
                    </div> <span class="text-sm">Tanpa Biaya Listing</span>
                </div>
                <div class="flex items-center gap-2 text-white">
                    <div class="text-emerald-400">
                        <i class="fa-solid fa-circle-check"></i>
                    </div> <span class="text-sm">Pencairan Cepat</span>
                </div>
            </div>

            <a href="<?= url('/vendor/register') ?>" class="inline-block px-8 py-3 bg-white text-[#056526] font-bold rounded-xl transition-transform hover:scale-105">
                Daftar Jadi Penjual Sekarang
            </a>
        </div>
        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800" class="absolute right-0 bottom-0 opacity-10 w-1/2 h-full object-cover">
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

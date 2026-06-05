<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="min-h-screen bg-white py-12 md:py-20">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="border-b border-gray-100 pb-8 md:pb-12 mb-8 md:mb-16 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-2xl">
                <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-full mb-3 md:mb-4 uppercase tracking-widest border border-primary/10">Katalog LitleMart</span>
                <h1 class="text-3xl md:text-7xl font-black text-slate-900 tracking-tighter leading-none mb-4 md:mb-6">Jelajahi<br class="hidden md:block"/>Kategori</h1>
                <p class="text-sm md:text-lg text-slate-500 font-medium leading-relaxed">Temukan produk impian Anda melalui kurasi kategori terbaik kami.</p>
            </div>
            <div class="flex items-center gap-4 text-xs md:text-sm font-black text-slate-400">
                <span class="text-slate-900 bg-slate-100 px-2 py-1 rounded-lg"><?= count($categories) ?></span> Kategori Tersedia
            </div>
        </div>

        <!-- Grid 3 Mobile, Grid 4 Desktop -->
        <div class="grid grid-cols-3 md:grid-cols-4 gap-2 md:gap-8">
            <?php foreach($categories as $index => $category): 
                $gradients = [
                    'bg-[#056526]',
                    'bg-slate-900',
                    'bg-rose-700',
                    'bg-blue-700',
                    'bg-amber-600',
                    'bg-purple-700'
                ];
                $bgClass = $gradients[$index % count($gradients)];
                $hasImage = !empty($category['image']);
            ?>
                <a href="<?= url('/products?category=' . $category['id']) ?>" 
                   class="group flex flex-col bg-white rounded-xl md:rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden hover:shadow-2xl hover:-translate-y-1 md:hover:-translate-y-2 transition-all duration-500 aspect-[2/3] md:aspect-auto relative">
                    
                    <!-- Top Part (Image or Initial) -->
                    <div class="h-[65%] md:aspect-square relative overflow-hidden <?= $bgClass ?>">
                        <?php if($hasImage): ?>
                            <img src="<?= url($category['image']) ?>" 
                                 alt="<?= $category['name'] ?>" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                        <?php else: ?>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl md:text-6xl font-black text-white/20 uppercase"><?= substr($category['name'], 0, 1) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Overlay for text clarity on mobile if image exists -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent md:hidden"></div>
                    </div>

                    <!-- Bottom Part (Content) -->
                    <div class="p-2 md:p-8 flex flex-col flex-1 justify-center md:justify-start items-center md:items-start text-center md:text-left">
                        <div class="mb-1 md:mb-4">
                            <h3 class="font-black text-slate-900 text-[10px] md:text-2xl line-clamp-1 md:line-clamp-2 leading-tight group-hover:text-primary transition-colors"><?= htmlspecialchars($category['name']) ?></h3>
                        </div>
                        
                        <div class="hidden md:block mt-auto pt-6 border-t border-slate-50 w-full">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lihat Produk</span>
                                <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center group-hover:bg-primary transition-all">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Counter Badge (Desktop Only) -->
                    <div class="hidden md:flex absolute top-6 right-6 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md border border-white/30 items-center justify-center text-white text-[10px] font-black z-10">
                        <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-[#F4F9F4] min-h-screen">
    <!-- Cover Photo Area -->
    <div class="relative">
        <div class="h-48 md:h-64 relative">
            <?php if ($user['banner']): ?>
                <img src="<?= url($user['banner']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <div class="w-full h-full bg-gradient-to-r from-primary to-emerald-900"></div>
            <?php endif; ?>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <!-- Overlay Card -->
        <div class="max-w-6xl mx-auto px-4 md:px-8 -mt-16 md:-mt-20 relative z-10">
            <div class="bg-white rounded-3xl shadow-xl shadow-green-900/5 p-5 md:p-8 flex flex-col md:flex-row items-center md:items-end gap-6 border border-gray-100">
                <!-- Avatar -->
                <div class="relative -mt-20 md:-mt-24">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-[2.5rem] bg-white p-2 shadow-2xl overflow-hidden">
                        <?php if ($user['avatar']): ?>
                            <img src="<?= url($user['avatar']) ?>" class="w-full h-full object-cover rounded-[2.2rem]">
                        <?php else: ?>
                            <div class="w-full h-full bg-emerald-100 text-primary flex items-center justify-center text-5xl font-black rounded-[2.2rem]">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Info Summary -->
                <div class="flex-1 text-center md:text-left mb-2 md:mb-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-center md:justify-start gap-3 mb-2">
                        <h1 class="text-2xl md:text-4xl font-black text-gray-900 drop-shadow-sm leading-tight"><?= htmlspecialchars($user['name']) ?></h1>
                        <?php if ($user['is_verified']): ?>
                            <span class="inline-flex items-center justify-center bg-blue-500 text-white w-5 h-5 rounded-full shadow-sm" title="Verified User">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="text-[11px] md:text-xs font-black text-emerald-600 uppercase tracking-widest px-3 py-1 bg-emerald-50 w-fit mx-auto md:mx-0 rounded-lg">
                        Anggota sejak <?= date('M Y', strtotime($user['created_at'])) ?>
                    </div>
                </div>

                <!-- Action Button -->
                <?php if (\App\Core\Session::get('user_id') == $user['id']): ?>
                    <div class="w-full md:w-auto mt-4 md:mt-0">
                        <a href="<?= url('/settings') ?>" class="block md:inline-block px-6 py-3.5 bg-[#056526] text-white text-[11px] font-black rounded-2xl text-center hover:bg-green-800 transition-all shadow-xl shadow-green-900/10 active:scale-95 uppercase tracking-[0.2em] w-full">Edit Profil</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <main class="max-w-6xl mx-auto px-4 md:px-8 py-8 md:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Stats Card -->
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Statistik Profil</h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xl font-black text-gray-900"><?= $stats['completed_orders'] ?></div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Transaksi Selesai</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08-.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xl font-black text-gray-900">Rp <?= number_format($stats['total_spending'], 0, ',', '.') ?></div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Belanja</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Promo Card if user has store -->
                <?php if ($vendor): ?>
                    <a href="<?= url('/store/' . $vendor['id']) ?>" class="block group">
                        <div class="bg-gradient-to-br from-primary to-emerald-800 rounded-[2rem] p-6 md:p-8 text-white shadow-xl shadow-green-900/10 overflow-hidden relative">
                            <div class="relative z-10">
                                <div class="text-[10px] font-black text-emerald-300 uppercase tracking-widest mb-2">Toko Anda</div>
                                <h4 class="text-xl font-black mb-1"><?= htmlspecialchars($vendor['store_name']) ?></h4>
                                <p class="text-xs text-emerald-100 opacity-80 line-clamp-2 italic"><?= htmlspecialchars($vendor['store_description'] ?? 'Toko Anda yang luar biasa.') ?></p>
                                <div class="mt-6 flex items-center gap-2 text-[11px] font-black uppercase tracking-widest bg-white/10 w-fit px-4 py-2 rounded-xl backdrop-blur group-hover:bg-white group-hover:text-primary transition-all">
                                    Dashboard Toko <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </div>
                            </div>
                            <div class="absolute -right-4 -bottom-4 text-emerald-700/30 opacity-50 group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                
                <!-- Contact Info -->
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="text-sm font-bold text-gray-700 truncate"><?= htmlspecialchars($user['email']) ?></div>
                        </div>
                        <?php if ($user['phone']): ?>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div class="text-sm font-bold text-gray-700"><?= htmlspecialchars($user['phone']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Tabs Controls -->
                <div x-data="{ tab: 'activity' }">
                    <div class="flex gap-4 md:gap-8 border-b border-gray-100 mb-6 md:mb-8 overflow-x-auto no-scrollbar">
                        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'border-primary text-primary' : 'border-transparent text-gray-400'" class="pb-3 md:pb-4 border-b-2 font-black text-xs md:text-sm uppercase tracking-widest whitespace-nowrap">Riwayat Pembelian</button>
                        <button @click="tab = 'about'" :class="tab === 'about' ? 'border-primary text-primary' : 'border-transparent text-gray-400'" class="pb-3 md:pb-4 border-b-2 font-black text-xs md:text-sm uppercase tracking-widest whitespace-nowrap">Detail Akun</button>
                    </div>

                    <!-- Recent Activity Tab -->
                    <div x-show="tab === 'activity'" x-transition class="space-y-4">
                        <?php if (empty($recentOrders)): ?>
                            <div class="bg-white rounded-[2rem] p-10 md:p-16 text-center border border-gray-100">
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-emerald-50 text-emerald-300 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🛍️</div>
                                <h3 class="text-gray-900 font-black text-lg">Belum ada transaksi</h3>
                                <p class="text-gray-400 text-xs md:text-sm italic mt-1">Produk eksklusif LitleMart menanti untuk Anda miliki.</p>
                                <a href="<?= url('/products') ?>" class="mt-6 inline-block px-8 py-3 bg-[#056526] text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-green-800">Mulai Belanja</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $ro): ?>
                            <div class="bg-white rounded-[1.5rem] p-5 md:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between group gap-4">
                                <div class="flex items-center gap-4 md:gap-6">
                                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-primary group-hover:text-white transition-all flex-shrink-0">
                                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h5 class="font-black text-gray-900 text-sm truncate">#<?= substr($ro['id'], 0, 8) ?></h5>
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[8px] font-black uppercase rounded-lg border border-gray-200"><?= $ro['status'] ?></span>
                                        </div>
                                        <p class="text-[10px] md:text-xs text-gray-500 font-medium truncate"><?= htmlspecialchars($ro['sample_product']) ?><?= count($recentOrders) > 1 ? ', dll...' : '' ?></p>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1"><?= date('d M Y', strtotime($ro['created_at'])) ?></p>
                                    </div>
                                </div>
                                <div class="sm:text-right flex items-center sm:block justify-between border-t border-gray-50 sm:border-0 pt-3 sm:pt-0">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest sm:hidden">Total Belanja</span>
                                    <div class="font-black text-primary text-sm md:text-base">Rp <?= number_format($ro['grand_total'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <div class="pt-6 text-center">
                                <a href="<?= url('/orders') ?>" class="inline-flex items-center gap-2 text-[10px] font-black text-primary hover:text-green-800 uppercase tracking-[0.2em] transition-colors">
                                    Lihat Semua Riwayat Pesanan <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- About Tab -->
                    <div x-show="tab === 'about'" x-transition class="bg-white rounded-[2rem] p-6 md:p-8 border border-gray-100 shadow-sm space-y-8">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                            <div>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 border-b border-gray-100 pb-2">Biografi Pengguna</h4>
                                <p class="text-xs md:text-sm text-gray-600 leading-relaxed italic mt-4"><?= $user['bio'] ? nl2br(htmlspecialchars($user['bio'])) : '"Pelanggan premium LitleMart yang selalu mengutamakan kualitas. Senang berburu produk-produk eksklusif berperingkat tinggi dan berkontribusi membangun ekosistem e-commerce yang sehat."' ?></p>
                            </div>
                            <?php if (\App\Core\Session::get('user_id') == $user['id']): ?>
                                <a href="<?= url('/settings') ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-xl hover:bg-emerald-100 transition-all border border-emerald-100 whitespace-nowrap">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Biografi
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <div>
                                <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Akun</h4>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
                                    <span class="text-sm font-black text-gray-900 uppercase">Aktif Tersertifikasi</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Peringkat Kepercayaan</h4>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-emerald-600 font-black text-base">A+</span>
                                    <div class="flex text-amber-400">
                                        <svg class="w-4 h-4 shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <svg class="w-4 h-4 shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <svg class="w-4 h-4 shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

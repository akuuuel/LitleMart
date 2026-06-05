<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <main class="flex-1 overflow-y-auto p-0">
            <!-- Cover Photo & Profile Header -->
            <div class="relative">
                <div class="h-48 md:h-64 overflow-hidden">
                    <img src="https://picsum.photos/seed/cover/1200/400" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/30 backdrop-blur-[2px]"></div>
                </div>

                <!-- Overlay content -->
                <div class="max-w-[90rem] mx-auto px-4 md:px-8 -mt-16 md:-mt-20 relative z-10">
                    <div class="bg-white rounded-3xl shadow-xl shadow-green-900/5 p-5 md:p-8 flex flex-col md:flex-row items-center md:items-end gap-6 border border-gray-100/50">
                        <!-- Avatar -->
                        <div class="relative -mt-20 md:-mt-24">
                            <div class="w-32 h-32 md:w-40 md:h-40 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-2xl">
                                <img src="https://picsum.photos/seed/<?= $vendor['id'] ?? 'user' ?>/200/200" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute bottom-2 right-2 w-8 h-8 bg-[#056526] rounded-full flex items-center justify-center border-4 border-white shadow-lg text-white">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>

                        <!-- Name and Quick Stats -->
                        <div class="flex-1 text-center md:text-left mb-2 md:mb-4">
                            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mb-2">
                                <h1 class="text-2xl md:text-4xl font-black text-gray-900 leading-tight"><?= htmlspecialchars($vendor['name'] ?? 'Alexandro Rivera') ?></h1>
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">Verified Vendor</span>
                            </div>
                            <div class="flex flex-wrap justify-center md:justify-start gap-4 text-gray-500 font-medium text-xs md:text-sm italic">
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.244a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> <?= htmlspecialchars($vendor['location'] ?? 'Jakarta, ID') ?></span>
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"></path></svg> Gabung <?= date('M Y', strtotime($vendor['created_at'] ?? 'now')) ?></span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 w-full md:w-auto">
                            <button class="flex-1 md:flex-none px-6 py-3.5 bg-[#056526] text-white text-[11px] font-black rounded-2xl hover:bg-green-800 transition-all shadow-xl shadow-green-900/10 active:scale-95 uppercase tracking-[0.2em]">
                                Edit Profil
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="max-w-[90rem] mx-auto px-4 md:px-8 py-8 md:py-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left: Details -->
                    <div class="lg:col-span-2 space-y-8">
                        <section class="bg-white rounded-[2rem] p-6 md:p-10 border border-gray-100 shadow-sm relative overflow-hidden">
                            <div class="flex items-center gap-4 mb-10">
                                <div class="w-10 h-10 bg-emerald-50 text-primary rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Data Personal</h2>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Informasi dasar akun vendor Anda</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
                                <div class="space-y-1">
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Nama Lengkap</div>
                                    <div class="text-sm font-black text-slate-800 border-b border-gray-100 pb-2"><?= htmlspecialchars($vendor['name'] ?? 'Alexandro Rivera') ?></div>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Identitas Publik</div>
                                    <div class="text-sm font-black text-slate-800 border-b border-gray-100 pb-2">@<?= strtolower(str_replace(' ', '', $vendor['name'] ?? 'alexrivera')) ?></div>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Alamat Surel</div>
                                    <div class="text-sm font-black text-slate-800 border-b border-gray-100 pb-2"><?= htmlspecialchars($vendor['email'] ?? 'alex.rivera@litlemart.com') ?></div>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Kontak Aktif</div>
                                    <div class="text-sm font-black text-slate-800 border-b border-gray-100 pb-2"><?= htmlspecialchars($vendor['phone'] ?? '+62 812-3456-7890') ?></div>
                                </div>
                            </div>
                        </section>

                        <!-- Security -->
                        <section class="bg-white rounded-[2rem] p-6 md:p-10 border border-gray-100 shadow-sm">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Privasi & Keamanan</h2>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Lindungi integritas akun Anda</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100 group hover:border-primary/20 transition-all">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-primary transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <button class="text-[9px] font-black text-primary uppercase tracking-widest hover:underline">Ganti</button>
                                    </div>
                                    <div class="text-xs font-black text-gray-800 uppercase mb-1">Kata Sandi</div>
                                    <div class="text-[10px] text-gray-400 font-medium italic">Kuat & Terlindungi</div>
                                </div>
                                <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100 group hover:border-primary/20 transition-all">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-primary transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">On</span>
                                        </div>
                                    </div>
                                    <div class="text-xs font-black text-gray-800 uppercase mb-1">Dua-Faktor (2FA)</div>
                                    <div class="text-[10px] text-gray-400 font-medium italic">Verifikasi SMS Aktif</div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Right Sidebar: Activity -->
                    <div class="space-y-8">
                        <section class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-8 h-8 bg-amber-50 text-amber-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h2 class="text-lg font-black text-gray-900 tracking-tight uppercase italic">Ledger Aktivitas</h2>
                            </div>
                            
                            <div class="space-y-8 relative before:absolute before:left-1.5 before:top-2 before:bottom-2 before:w-[2px] before:bg-gray-50">
                                <?php
                                $activities = [
                                    ['icon' => 'bg-emerald-500', 'title' => 'Login Berhasil', 'desc' => 'Chrome (Win11) &bull; Jakarta', 'time' => '10 menit yang lalu'],
                                    ['icon' => 'bg-slate-300', 'title' => 'Stok Update', 'desc' => "Batch 'Smartphone Pro' diperbarui", 'time' => '2 jam lalu'],
                                    ['icon' => 'bg-slate-300', 'title' => 'Profil Diubah', 'desc' => 'Foto sampul diperbarui', 'time' => 'Kemarin, 14:00'],
                                ];
                                foreach ($activities as $a):
                                ?>
                                <div class="relative pl-8">
                                    <div class="absolute left-0 top-1.5 w-3 h-3 rounded-full <?= $a['icon'] ?> border-2 border-white shadow-sm z-10"></div>
                                    <div class="text-[11px] font-black text-gray-900 uppercase mb-0.5"><?= $a['title'] ?></div>
                                    <div class="text-[10px] text-gray-400 font-medium italic mb-1 lowercase"><?= $a['desc'] ?></div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?= $a['time'] ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <a href="#" class="block w-full mt-10 py-3 text-center border-t border-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-primary transition-colors">Selengkapnya &rarr;</a>
                        </section>

                        <!-- Banner Promo (Visual Fluff) -->
                        <div class="rounded-[2rem] bg-gradient-to-br from-primary to-emerald-900 p-8 text-white relative overflow-hidden group">
                            <div class="relative z-10">
                                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-300 mb-2">Vendor Pro</div>
                                <h3 class="text-xl font-black mb-4 leading-tight italic">Tingkatkan Performa Jualan Anda</h3>
                                <button class="px-5 py-2.5 bg-white text-primary text-[10px] font-black uppercase rounded-xl hover:scale-105 transition-transform">Pelajari &rarr;</button>
                            </div>
                            <svg class="absolute -right-8 -bottom-8 w-40 h-40 text-white/5 transform group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

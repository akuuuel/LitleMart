<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans" x-data="{ activeTab: '<?= $_GET['tab'] ?? 'Umum' ?>', showPaymentModal: false, showWithdrawModal: false, withdrawAmount: 0 }">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <main class="flex-1 flex flex-col md:flex-row min-h-0 pt-0">
            <!-- Settings Sub-nav (Sidebar on Desktop, Horizontal Scroll on Mobile) -->
            <aside class="w-full md:w-56 bg-white border-b md:border-b-0 md:border-r border-gray-200 flex-shrink-0 py-2 md:py-6 px-4 overflow-x-auto no-scrollbar">
                <div class="flex md:flex-col gap-2 min-w-max md:min-w-0">
                    <?php
                    $tabs = [
                        ['id' => 'General', 'label' => 'Umum',      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>'],
                        ['id' => 'Payments', 'label' => 'Pembayaran',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>'],
                        ['id' => 'Notifications', 'label' => 'Notifikasi','icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>'],
                        ['id' => 'Security', 'label' => 'Keamanan',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>'],
                    ];
                    foreach ($tabs as $t):
                    ?>
                        <button @click="activeTab = '<?= $t['label'] ?>'" 
                           :class="activeTab === '<?= $t['label'] ?>' ? 'flex items-center gap-3 px-4 py-2.5 bg-[#056526] text-white rounded-xl text-sm font-bold shadow-sm whitespace-nowrap' : 'flex items-center gap-3 px-4 py-2.5 text-gray-500 hover:bg-gray-50 rounded-xl text-sm font-medium transition-colors whitespace-nowrap'">
                            <svg class="w-4 h-4" :class="activeTab === '<?= $t['label'] ?>' ? 'text-white' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $t['icon'] ?></svg>
                            <?= $t['label'] ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8">
                
                <!-- General Settings Tab -->
                <div x-show="activeTab === 'Umum'" x-transition>
                    <form action="<?= url('/vendor/settings') ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="general">
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 md:p-8 mb-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-1">Pengaturan Umum</h2>
                            <p class="text-sm text-gray-500 mb-6">Perbarui informasi utama toko dan pengaturan lokasi Anda.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Toko</label>
                                    <input name="store_name" type="text" required class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]" value="<?= htmlspecialchars($vendor['store_name'] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Kontak</label>
                                    <input name="contact_email" type="email" required class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]" value="<?= htmlspecialchars($vendor['email'] ?? '') ?>">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Tampilan</label>
                                    <input name="location" type="text" placeholder="misal: Jakarta Selatan, Indonesia" class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]" value="<?= htmlspecialchars($vendor['location'] ?? '') ?>">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Toko</label>
                                    <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] resize-none uppercase tracking-tight"><?= htmlspecialchars($vendor['store_description'] ?? '') ?></textarea>
                                </div>

                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50 mt-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Logo Toko</label>
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden relative group">
                                                <img id="logo_preview" src="<?= $vendor['store_logo'] ? url($vendor['store_logo']) : '' ?>" class="w-full h-full object-cover <?= !$vendor['store_logo'] ? 'hidden' : '' ?>">
                                                <div id="logo_placeholder" class="w-full h-full flex items-center justify-center text-primary font-bold <?= $vendor['store_logo'] ? 'hidden' : '' ?>">L</div>
                                                <input type="file" name="store_logo" id="logo_input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                                <input type="hidden" name="current_logo" value="<?= $vendor['store_logo'] ?>">
                                            </div>
                                            <p class="text-[10px] text-gray-400 font-medium leading-tight">Klik untuk unggah logo merek.<br>Ukuran kotak disarankan.</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Banner Toko</label>
                                        <div class="h-16 w-full rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden relative group">
                                            <img id="v_banner_preview" src="<?= $vendor['store_banner'] ? url($vendor['store_banner']) : '' ?>" class="w-full h-full object-cover <?= !$vendor['store_banner'] ? 'hidden' : '' ?>">
                                            <div id="v_banner_placeholder" class="w-full h-full flex items-center justify-center text-gray-300 text-[10px] uppercase font-black tracking-widest <?= $vendor['store_banner'] ? 'hidden' : '' ?>">Pilih Banner</div>
                                            <input type="file" name="store_banner" id="v_banner_input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                            <input type="hidden" name="current_banner" value="<?= $vendor['store_banner'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6" x-data="{ 
                                    provinces: [], 
                                    cities: [], 
                                    selectedProv: '<?= $vendor['province_id'] ?? '' ?>', 
                                    selectedCity: '<?= $vendor['city_id'] ?? '' ?>', 
                                    searchCity: '',
                                    showCity: false,
                                    loadingCities: false,
                                    async fetchCities() {
                                        if(!this.selectedProv) return;
                                        this.loadingCities = true;
                                        const res = await fetch('<?= url('/api/shipping/cities?province=') ?>' + this.selectedProv);
                                        this.cities = await res.json();
                                        this.loadingCities = false;
                                        
                                        if(this.selectedCity && !this.searchCity) {
                                            const city = this.cities.find(c => c.city_id == this.selectedCity);
                                            if(city) this.searchCity = city.type + ' ' + city.city_name;
                                        }
                                    }
                                }" x-init="provinces = await (await fetch('<?= url('/api/shipping/provinces') ?>')).json(); if(selectedProv) fetchCities()">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                        <select name="province_id" required x-model="selectedProv" @change="fetchCities(); selectedCity = ''; searchCity = ''" class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]">
                                            <option value="">Pilih Provinsi</option>
                                            <template x-for="p in provinces" :key="p.province_id">
                                                <option :value="p.province_id" x-text="p.province" :selected="p.province_id == selectedProv"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="relative">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                                        <input type="text" x-model="searchCity" 
                                               @input="showCity = true"
                                               @click="showCity = true" 
                                               @click.away="showCity = false"
                                               :disabled="!selectedProv || loadingCities"
                                               :placeholder="loadingCities ? 'Memuat...' : (selectedProv ? 'Cari kota...' : 'Pilih provinsi dahulu')"
                                               class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]">
                                        
                                        <input type="hidden" name="city_id" :value="selectedCity">

                                        <div x-show="showCity && selectedProv && searchCity.length >= 2" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto" x-cloak>
                                            <template x-for="c in cities.filter(c => c.city_name.toLowerCase().includes(searchCity.toLowerCase()))" :key="c.city_id">
                                                <div @click="selectedCity = c.city_id; searchCity = c.type + ' ' + c.city_name; showCity = false" class="px-4 py-3 hover:bg-green-50 cursor-pointer text-sm transition-colors border-b border-gray-100 last:border-0">
                                                    <span x-text="c.type + ' ' + c.city_name"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end items-center">
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-[#056526] text-white text-sm font-bold rounded-xl hover:bg-green-800 transition-colors shadow-lg shadow-green-900/10 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payments Tab -->
                <div x-show="activeTab === 'Pembayaran'" x-transition x-cloak>
                    <!-- Balance Card -->
                    <div class="bg-gradient-to-br from-[#056526] to-green-500 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-green-900/10 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="text-center md:text-left">
                            <h3 class="text-green-100 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Saldo</h3>
                            <div class="text-3xl md:text-5xl font-black tracking-tighter">Rp <?= number_format($walletBalance ?? 0, 0, ',', '.') ?></div>
                            <p class="text-green-200 text-[9px] font-bold mt-2 uppercase tracking-widest opacity-80 italic">Dana aman dan siap ditarik</p>
                        </div>
                        <button @click="showWithdrawModal = true" class="w-full md:w-auto px-8 py-3.5 bg-white text-[#056526] font-black rounded-2xl hover:bg-green-50 transition-all shadow-xl shadow-green-900/20 flex items-center justify-center gap-3">
                            Tarik Saldo
                        </button>
                    </div>

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 mb-1">Metode Pembayaran</h2>
                                <p class="text-sm text-gray-500">Rekening tujuan untuk pengiriman dana.</p>
                            </div>
                            <button @click="showPaymentModal = true" type="button" class="w-full md:w-auto px-4 py-2.5 bg-gray-50 text-gray-600 text-[10px] font-black uppercase rounded-xl hover:bg-gray-100 transition-all border border-gray-100">
                                + Bank Baru
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php if(empty($payments)): ?>
                                <div class="col-span-2 p-12 text-center text-gray-300 border-2 border-dashed border-gray-100 rounded-3xl">
                                    Belum ada bank terdaftar
                                </div>
                            <?php else: ?>
                                <?php foreach($payments as $p): ?>
                                <div class="group relative flex items-center justify-between p-5 border border-gray-100 rounded-2xl hover:border-green-200 hover:bg-green-50/30 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v20M12 14v20M16 14v20M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-[11px] font-black text-gray-900 uppercase tracking-tight"><?= htmlspecialchars($p['bank_name']) ?></div>
                                            <div class="text-[10px] text-gray-400 font-medium"><?= htmlspecialchars($p['account_number']) ?></div>
                                        </div>
                                    </div>
                                    <?php if($p['is_primary']): ?>
                                        <span class="px-1.5 py-0.5 bg-green-500 text-white text-[7px] font-black uppercase rounded shadow-sm shadow-green-200 tracking-widest">Utama</span>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'Keamanan'" x-transition x-cloak>
                    <form action="<?= url('/vendor/settings') ?>" method="POST">
                        <input type="hidden" name="action" value="update_password">
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 mb-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-1">Keamanan Akun</h2>
                            <p class="text-sm text-gray-500 mb-6">Perbarui kata sandi secara berkala untuk menjaga keamanan.</p>
                            
                            <div class="space-y-5">
                                <?php if (!empty($vendor['password'])): ?>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Lama</label>
                                        <input type="password" name="current_password" required class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20">
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 bg-blue-50 text-blue-700 rounded-xl text-[11px] font-bold uppercase tracking-tight">Login melalui Google</div>
                                <?php endif; ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sandi Baru</label>
                                        <input type="password" name="new_password" required class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ulangi Sandi</label>
                                        <input type="password" name="confirm_password" required class="w-full px-4 py-2.5 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-gray-900 text-white text-sm font-black rounded-xl hover:bg-black transition-all shadow-lg active:scale-95 uppercase tracking-widest">Update Sandi</button>
                        </div>
                    </form>
                </div>
                
                <!-- Notifications Tab -->
                <div x-show="activeTab === 'Notifikasi'" x-transition x-cloak>
                    <form action="<?= url('/vendor/settings') ?>" method="POST">
                        <input type="hidden" name="action" value="update_notifications">
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 mb-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-1">Preferensi</h2>
                            <p class="text-sm text-gray-500 mb-6">Kelola bagaimana Anda menerima informasi dari sistem.</p>
                            
                            <div class="space-y-3">
                                <?php 
                                $notifItems = ['new_order_email' => 'Email Order Baru', 'new_order_push' => 'Push Notifikasi', 'weekly_report' => 'Laporan Mingguan', 'security_alerts' => 'Keamanan Akun'];
                                foreach($notifItems as $key => $label): ?>
                                <label class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-2xl cursor-pointer hover:bg-white hover:shadow-sm transition-all group">
                                    <span class="text-xs font-black text-gray-700 uppercase tracking-tight"><?= $label ?></span>
                                    <input type="checkbox" name="<?= $key ?>" value="1" <?= ($notifSettings[$key] ?? 0) ? 'checked' : '' ?> class="w-5 h-5 accent-[#056526] rounded-lg">
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-[#056526] text-white text-sm font-black rounded-xl hover:bg-green-800 transition-all shadow-lg uppercase tracking-widest active:scale-95">Simpan Notif</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Payment Method Modal -->
    <div x-show="showPaymentModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl" @click.away="showPaymentModal = false">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Tambah Rekening</h3>
                <button @click="showPaymentModal = false" class="p-2 hover:bg-gray-50 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="<?= url('/vendor/settings') ?>" method="POST" class="p-8 space-y-5">
                <input type="hidden" name="action" value="add_payment">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Bank</label>
                    <input type="text" name="bank_name" placeholder="E.g. BCA, Mandiri, BRI" required
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-[#056526]/5 focus:bg-white focus:border-[#056526] transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nomor Rekening</label>
                    <input type="text" name="account_number" placeholder="Masukkan nomor tanpa spasi" required
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-[#056526]/5 focus:bg-white focus:border-[#056526] transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Pemilik</label>
                    <input type="text" name="account_holder" placeholder="Nama sesuai di buku tabungan" required
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-[#056526]/5 focus:bg-white focus:border-[#056526] transition-all">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-[#056526] text-white font-black rounded-2xl hover:bg-green-800 transition-all shadow-xl shadow-green-900/20">
                        Simpan Rekening
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawal Modal -->
    <div x-show="showWithdrawModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl" @click.away="showWithdrawModal = false">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Tarik Saldo</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">LitleMart Wallet System</p>
                </div>
                <button @click="showWithdrawModal = false" class="p-2 hover:bg-white rounded-xl transition-colors shadow-sm">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <?php if(empty($payments)): ?>
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h4 class="text-lg font-black text-gray-900 mb-2">Rekening Belum Ada</h4>
                    <p class="text-xs text-gray-500 font-medium mb-8">Anda harus menambahkan minimal satu rekening bank sebelum bisa menarik saldo.</p>
                    <button @click="showWithdrawModal = false; showPaymentModal = true" class="px-8 py-3 bg-[#056526] text-white font-black rounded-xl hover:scale-105 transition-all">Tambah Rekening Sekarang</button>
                </div>
            <?php elseif($walletBalance <= 0): ?>
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-black text-gray-900 mb-2">Saldo Kosong</h4>
                    <p class="text-xs text-gray-500 font-medium mb-8">Saat ini saldo Anda Rp 0. Tunggu pesanan diselesaikan untuk mendapatkan saldo.</p>
                    <button @click="showWithdrawModal = false" class="px-8 py-3 bg-gray-900 text-white font-black rounded-xl">Kembali</button>
                </div>
            <?php else: ?>
                <form action="<?= url('/vendor/settings') ?>" method="POST" class="p-8 space-y-6">
                    <input type="hidden" name="action" value="request_withdrawal">
                    <div class="p-4 bg-green-50 rounded-2xl border border-green-100 flex items-center justify-between">
                        <span class="text-[10px] font-black text-green-800 uppercase tracking-widest">Saldo Bisa Ditarik</span>
                        <span class="text-lg font-black text-[#056526]">Rp <?= number_format($walletBalance, 0, ',', '.') ?></span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nominal Penarikan</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-gray-900 text-lg">Rp</span>
                            <input type="number" name="amount" x-model="withdrawAmount" max="<?= $walletBalance ?>" min="10000" required
                                class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-lg font-black focus:outline-none focus:ring-4 focus:ring-[#056526]/5 focus:bg-white focus:border-[#056526] transition-all">
                        </div>
                        <p class="text-[9px] text-gray-400 font-bold mt-2 uppercase tracking-widest">Minimal penarikan Rp 10.000</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Rekening Tujuan</label>
                        <select name="payment_method_id" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-[#056526]/5 focus:bg-white focus:border-[#056526] appearance-none transition-all">
                            <?php foreach($payments as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['bank_name'] ?> — <?= $p['account_number'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password" required placeholder="Masukkan kata sandi akun Anda"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-[#056526]/5 focus:bg-white focus:border-[#056526] transition-all">
                    </div>

                    <div class="pt-4 border-t border-gray-50">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-xs font-bold text-gray-500">Kirim Ke Admin</span>
                            <span class="text-xs font-black text-gray-900 italic">Proses 1-3 Hari Kerja</span>
                        </div>
                        <button type="submit" :disabled="withdrawAmount > <?= $walletBalance ?>" class="w-full py-4 bg-[#056526] disabled:bg-gray-300 text-white font-black rounded-2xl hover:bg-green-800 transition-all shadow-xl shadow-green-900/20">
                            Konfirmasi Penarikan
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
function setupPreview(inputId, previewId, placeholderId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);

    if (input && preview) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }
}

setupPreview('logo_input', 'logo_preview', 'logo_placeholder');
setupPreview('v_banner_input', 'v_banner_preview', 'v_banner_placeholder');
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

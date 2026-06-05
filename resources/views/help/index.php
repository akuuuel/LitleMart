<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white min-h-screen">
    <!-- Hero Section -->
    <div class="bg-[#056526] py-20 px-4 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-6">Pusat Bantuan LitleMart</h1>
            <p class="text-green-100 text-lg mb-8 max-w-2xl mx-auto">Kami di sini untuk membantu Anda dengan segala pertanyaan seputar belanja dan berjualan di LitleMart.</p>
            
            <div class="max-w-2xl mx-auto relative">
                <input type="text" placeholder="Cari bantuan..." class="w-full px-6 py-4 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/30 backdrop-blur-md">
                <button class="absolute right-3 top-3 px-6 py-2 bg-white text-[#056526] font-bold rounded-xl hover:bg-green-50 transition-all">Cari</button>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 hover:shadow-xl transition-all group">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Panduan Pembeli</h3>
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">Cara berbelanja, melacak pesanan, dan mengelola akun pembeli Anda.</p>
                <a href="#" class="text-emerald-600 font-bold text-xs uppercase tracking-widest hover:gap-2 flex items-center gap-1 transition-all">Lihat Selengkapnya <span>→</span></a>
            </div>
            
            <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 hover:shadow-xl transition-all group">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Panduan Penjual</h3>
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">Membuka toko, mengunggah produk, dan tips meningkatkan penjualan Anda.</p>
                <a href="#" class="text-emerald-600 font-bold text-xs uppercase tracking-widest hover:gap-2 flex items-center gap-1 transition-all">Lihat Selengkapnya <span>→</span></a>
            </div>
            
            <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 hover:shadow-xl transition-all group">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pembayaran & Keamanan</h3>
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">Keamanan transaksi, metode pembayaran, dan kebijakan pengembalian dana.</p>
                <a href="#" class="text-emerald-600 font-bold text-xs uppercase tracking-widest hover:gap-2 flex items-center gap-1 transition-all">Lihat Selengkapnya <span>→</span></a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-black text-gray-900 mb-10 text-center">Pertanyaan yang Sering Diajukan</h2>
            <div class="space-y-4" x-data="{ open: null }">
                <?php
                $faqs = [
                    ['q' => 'Bagaimana cara mendaftar akun di LitleMart?', 'a' => 'Klik tombol Daftar di pojok kanan atas, isi detail Anda, dan verifikasi email Anda untuk mulai berbelanja.'],
                    ['q' => 'Apakah aman berbelanja di sini?', 'a' => 'Sangat aman. Kami menggunakan Midtrans sebagai pengolah pembayaran dan menjaga data Anda tetap terenkripsi.'],
                    ['q' => 'Berapa biaya pengiriman produk?', 'a' => 'Biaya pengiriman dihitung secara otomatis berdasarkan lokasi Anda dan penjual menggunakan data real-time dari RajaOngkir.'],
                    ['q' => 'Bagaimana cara menjadi penjual?', 'a' => 'Anda bisa klik "Jual di LitleMart" di navigasi dan ikuti proses onboarding yang mudah dan cepat.'],
                ];
                foreach ($faqs as $i => $faq): ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ isOpen: false }">
                    <button @click="isOpen = !isOpen" class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-gray-50 transition-colors">
                        <span class="font-bold text-gray-900 pr-4"><?= $faq['q'] ?></span>
                        <span class="flex-shrink-0 w-6 h-6 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 transition-transform" :class="isOpen ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="isOpen" x-transition class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                        <?= $faq['a'] ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Support CTA -->
    <div class="max-w-5xl mx-auto px-4 pb-20">
        <div class="bg-gradient-to-br from-gray-900 to-black rounded-[2.5rem] p-12 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-8 left-8 text-6xl text-white">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl font-black mb-4">Masih butuh bantuan?</h2>
                <p class="text-gray-400 mb-8 max-w-lg mx-auto">Tim dukungan kami siap membantu Anda 24/7. Hubungi kami melalui tombol di bawah ini.</p>
                <a href="<?= url('/contact') ?>" class="inline-flex items-center gap-3 px-10 py-4 bg-[#056526] text-white font-black rounded-2xl hover:bg-emerald-800 transition-all shadow-xl shadow-green-900/20 hover:scale-105 text-sm uppercase tracking-widest">
                    Hubungi Kami Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
}
.animate-fadeInUp { animation: fadeInUp 0.7s ease both; }
.animate-float { animation: float 4s ease-in-out infinite; }
.step-card { animation: fadeInUp 0.6s ease both; }
.step-card:nth-child(1) { animation-delay: 0.1s; }
.step-card:nth-child(2) { animation-delay: 0.25s; }
.step-card:nth-child(3) { animation-delay: 0.4s; }
.step-card:nth-child(4) { animation-delay: 0.55s; }
.gradient-text {
    background: linear-gradient(135deg, #056526, #10b981);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<div class="bg-[#F4F9F4] min-h-screen">

    <!-- HERO -->
    <div class="relative overflow-hidden bg-gradient-to-br from-[#056526] via-[#067a2e] to-[#044a1d] py-24 px-4">
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4 blur-3xl"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="inline-block px-4 py-1.5 bg-white/15 backdrop-blur text-white text-xs font-bold rounded-full uppercase tracking-widest mb-6 border border-white/20 animate-fadeInUp">
                Pengalaman LitleMart
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight animate-fadeInUp" style="animation-delay:0.1s">
                Bagaimana Kami<br><span class="text-emerald-300">Bekerja</span>
            </h1>
            <p class="text-xl text-white/70 max-w-2xl mx-auto leading-relaxed animate-fadeInUp" style="animation-delay:0.2s">
                Dari penemuan hingga ke pintu rumah Anda — LitleMart membuat jual beli menjadi simpel, aman, dan lancar.
            </p>
        </div>
    </div>

    <!-- LIVE STATS BAR -->
    <div class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <?php
            $statsDisplay = [
                ['value' => number_format($stats['users']),    'label' => 'Pembeli Terdaftar', 'icon' => 'fa-solid fa-user'],
                ['value' => number_format($stats['vendors']),  'label' => 'Penjual Terverifikasi', 'icon' => 'fa-solid fa-store'],
                ['value' => number_format($stats['products']), 'label' => 'Produk Aktif',     'icon' => 'fa-solid fa-box'],
                ['value' => number_format($stats['orders']),   'label' => 'Pesanan Selesai',    'icon' => 'fa-solid fa-circle-check'],
            ];
            foreach ($statsDisplay as $s): ?>
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl text-emerald-600 mb-2">
                    <i class="<?= $s['icon'] ?>"></i>
                </span>
                <div class="text-3xl font-black text-[#056526]"><?= $s['value'] ?></div>
                <div class="text-xs text-gray-400 font-semibold uppercase tracking-widest"><?= $s['label'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-20">

        <!-- FOR BUYERS SECTION -->
        <div class="mb-20">
            <div class="text-center mb-14">
                <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-black rounded-full uppercase tracking-widest mb-4">Untuk Pembeli</span>
                <h2 class="text-4xl font-black text-gray-900">Mulai Belanja dalam 4 Langkah Mudah</h2>
                <p class="text-gray-500 mt-2 max-w-xl mx-auto">Semua yang Anda butuhkan untuk menemukan produk favorit dan dikirim dengan aman.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                <!-- Connector line -->
                <div class="hidden md:block absolute top-12 left-[12.5%] right-[12.5%] h-0.5 bg-emerald-100 z-0"></div>

                <?php
                $buyerSteps = [
                    ['num' => '01', 'icon' => 'fa-solid fa-magnifying-glass', 'title' => 'Temukan Produk', 'desc' => 'Jelajahi ribuan produk pilihan dari penjual terverifikasi. Gunakan filter, kategori, atau pencarian untuk menemukan apa yang Anda butuhkan.', 'cta' => 'Jelajahi Sekarang', 'href' => '/products'],
                    ['num' => '02', 'icon' => 'fa-solid fa-cart-shopping', 'title' => 'Tambah ke Keranjang', 'desc' => 'Pilih item Anda, tentukan varian (ukuran, warna), dan tambahkan ke keranjang. Bandingkan harga dari berbagai penjual dengan mudah.', 'cta' => 'Lihat Keranjang', 'href' => '/cart'],
                    ['num' => '03', 'icon' => 'fa-solid fa-credit-card', 'title' => 'Pembayaran Aman', 'desc' => 'Pilih alamat dan metode pengiriman dengan kalkulasi biaya waktu-nyata. Bayar dengan aman melalui Midtrans dengan berbagai pilihan pembayaran.', 'cta' => 'Pembayaran', 'href' => '/checkout'],
                    ['num' => '04', 'icon' => 'fa-solid fa-truck-fast', 'title' => 'Lacak & Terima', 'desc' => 'Pantau status pesanan Anda secara waktu-nyata. Dapatkan nomor resi kurir dari penjual. Konfirmasi penerimaan saat paket tiba dengan aman.', 'cta' => 'Pesanan Saya', 'href' => '/orders'],
                ];
                foreach ($buyerSteps as $step): ?>
                <div class="step-card relative z-10 group">
                    <div class="bg-white rounded-[2rem] p-7 border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 h-full flex flex-col">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-2xl text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="<?= $step['icon'] ?>"></i>
                            </div>
                            <span class="text-4xl font-black text-gray-100 group-hover:text-emerald-100 transition-colors"><?= $step['num'] ?></span>
                        </div>
                        <h3 class="font-black text-gray-900 text-lg mb-2"><?= $step['title'] ?></h3>
                        <p class="text-sm text-gray-500 leading-relaxed flex-1"><?= $step['desc'] ?></p>
                        <a href="<?= url($step['href']) ?>" class="mt-5 inline-flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:gap-3 transition-all">
                            <?= $step['cta'] ?> <span>→</span>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- FOR VENDORS SECTION -->
        <div class="mb-20">
            <div class="text-center mb-14">
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-black rounded-full uppercase tracking-widest mb-4">Untuk Penjual</span>
                <h2 class="text-4xl font-black text-gray-900">Jual Produk Anda dalam Hitungan Menit</h2>
                <p class="text-gray-500 mt-2 max-w-xl mx-auto">Buka toko Anda, daftarkan produk, dan mulai hasilkan uang — tidak perlu keahlian teknis.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $vendorSteps = [
                    ['icon' => 'fa-solid fa-store', 'step' => '1', 'title' => 'Buka Toko Anda', 'desc' => 'Daftar sebagai penjual dengan nama toko, logo, banner, and deskripsi. Proses onboarding kami kurang dari 5 menit. Tanpa biaya awal.', 'color' => 'from-[#056526] to-[#044a1d]'],
                    ['icon' => 'fa-solid fa-box-open', 'step' => '2', 'title' => 'Daftarkan Produk', 'desc' => 'Unggah foto produk, atur harga, tambahkan deskripsi, dan kelola stok. Atur ke dalam kategori untuk kemudahan penemuan.', 'color' => 'from-blue-600 to-blue-800'],
                    ['icon' => 'fa-solid fa-money-bill-trend-up', 'step' => '3', 'title' => 'Kelola & Dapatkan', 'desc' => 'Terima pesanan melalui notifikasi, proses pengiriman, input nomor resi, dan dapatkan pembayaran langsung ke rekening bank terdaftar Anda.', 'color' => 'from-amber-500 to-orange-600'],
                ];
                foreach ($vendorSteps as $s): ?>
                <div class="group relative overflow-hidden rounded-[2rem] shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br <?= $s['color'] ?> opacity-95"></div>
                    <div class="absolute top-4 right-4 text-8xl font-black text-white/10 leading-none"><?= $s['step'] ?></div>
                    <div class="relative z-10 p-8">
                        <div class="text-4xl text-white mb-5 animate-float" style="animation-delay: <?= ($s['step']-1)*0.5 ?>s">
                            <i class="<?= $s['icon'] ?>"></i>
                        </div>
                        <h3 class="text-xl font-black text-white mb-3"><?= $s['title'] ?></h3>
                        <p class="text-sm text-white/70 leading-relaxed"><?= $s['desc'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-10">
                <a href="<?= url('/vendor/onboarding') ?>" class="inline-flex items-center gap-3 px-10 py-4 bg-[#056526] text-white font-black rounded-2xl hover:bg-emerald-800 transition-all shadow-xl shadow-green-900/20 hover:scale-105 text-sm uppercase tracking-widest">
                    <i class="fa-solid fa-rocket animate-pulse"></i> Mulai Berjualan Hari Ini — Gratis!
                </a>
            </div>
        </div>

        <!-- PAYMENT & SECURITY -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4 blur-2xl"></div>
                <div class="text-4xl text-emerald-400 mb-5">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-2xl font-black mb-3">Pembayaran Aman</h3>
                <p class="text-white/60 text-sm mb-5 leading-relaxed">Semua transaksi didukung oleh <strong class="text-white">Midtrans</strong>, gateway pembayaran terkemuka di Indonesia. Data finansial Anda terenkripsi sepenuhnya.</p>
                <ul class="space-y-2 text-sm">
                    <?php foreach(['Transfer Bank (VA)', 'Kartu Kredit & Debit', 'E-Wallet (GoPay, OVO, dll)', 'Pembayaran Instan QRIS', 'Pilihan Cicilan'] as $m): ?>
                    <li class="flex items-center gap-3 text-white/70">
                        <span class="w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center text-white text-[10px] flex-shrink-0">✓</span>
                        <?= $m ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-xl">
                <div class="text-4xl text-blue-600 mb-5">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-3">Pengiriman & Pelacakan</h3>
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">Kami terintegrasi dengan <strong class="text-gray-800">RajaOngkir</strong> untuk mendapatkan biaya pengiriman waktu-nyata dari kurir utama Indonesia. Lacak pesanan Anda dari pengiriman hingga tiba.</p>
                <ul class="space-y-2 text-sm">
                    <?php foreach(['JNE — Reguler, YES, OKE', 'J&T Express', 'SiCepat — HALU, GOKIL', 'TIKI', 'POS Indonesia'] as $courier): ?>
                    <li class="flex items-center gap-3 text-gray-600">
                        <span class="w-5 h-5 bg-blue-50 border border-blue-100 rounded-full flex items-center justify-center text-blue-500 text-[10px] flex-shrink-0">→</span>
                        <?= $courier ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- FAQ -->
        <div class="mb-20" x-data="{ open: null }">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-black text-gray-900">Pertanyaan Umum (FAQ)</h2>
                <p class="text-gray-400 mt-2">Semua yang perlu Anda ketahui tentang LitleMart.</p>
            </div>
            <div class="space-y-4 max-w-3xl mx-auto">
                <?php
                $faqs = [
                    ['q' => 'Apakah LitleMart gratis untuk pembeli?', 'a' => 'Ya! Menjelajah, menambah ke keranjang, dan berbelanja di LitleMart sepenuhnya gratis untuk pembeli. Anda hanya membayar harga produk dan biaya pengiriman.'],
                    ['q' => 'Bagaimana cara menjadi penjual?', 'a' => 'Klik "Mulai Berjualan" di navigasi, lalu lengkapi formulir onboarding cepat kami. Atur nama toko, logo, dan lokasi — Anda langsung siap mendaftarkan produk!'],
                    ['q' => 'Bagaimana pembayaran diproses?', 'a' => 'Pembayaran melalui Midtrans, gateway bersertifikasi PCI-DSS. Dana ditahan dengan aman dan diteruskan ke penjual setelah pembeli mengonfirmasi penerimaan (atau setelah 3 hari otomatis).'],
                    ['q' => 'Bisakah saya melacak pesanan saya?', 'a' => 'Ya! Setelah penjual mengirimkan pesanan, mereka akan memasukkan nama kurir dan nomor resi. Anda akan melihat ini di Riwayat Pesanan dan bisa melacaknya langsung.'],
                    ['q' => 'Apa yang terjadi jika saya tidak menerima pesanan?', 'a' => 'Jika Anda mengalami masalah pengiriman, hubungi penjual melalui sistem pesan kami. Status pesanan akan tetap "Dikirim" sampai Anda konfirmasi. Jika tidak terselesaikan, hubungi tim bantuan 24/7 kami.'],
                    ['q' => 'Bagaimana penjual mendapatkan bayaran?', 'a' => 'Penjual mendaftarkan rekening bank di Pengaturan Penjual → Tab Pembayaran. Setelah pembeli mengonfirmasi penerimaan (atau lewat 3 hari), pembayaran akan dilepas ke rekening mereka.'],
                ];
                foreach ($faqs as $i => $faq): ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-gray-50 transition-colors">
                        <span class="font-bold text-gray-900 pr-4 text-sm"><?= $faq['q'] ?></span>
                        <span class="flex-shrink-0 w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 transition-transform" :class="open ? 'rotate-45 bg-primary border-primary text-white' : ''">+</span>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-5 text-sm text-gray-500 leading-relaxed border-t border-gray-50 pt-4">
                        <?= $faq['a'] ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CTA BOTTOM -->
        <div class="bg-gradient-to-br from-[#056526] to-[#044a1d] rounded-[2.5rem] p-12 md:p-16 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-8 left-8 text-7xl text-white">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="absolute bottom-8 right-12 text-6xl text-white">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>
            <div class="relative z-10">
                <h2 class="text-4xl font-black text-white mb-4">Siap untuk Memulai?</h2>
                <p class="text-white/60 max-w-lg mx-auto mb-8 leading-relaxed">Bergabunglah dengan ribuan pembeli senang dan penjual sukses di LitleMart hari ini.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="<?= url('/products') ?>" class="px-8 py-4 bg-white text-[#056526] font-black rounded-2xl hover:scale-105 transition-all shadow-xl text-sm uppercase tracking-wider">
                        Belanja Sekarang <i class="fa-solid fa-bag-shopping ml-2"></i>
                    </a>
                    <a href="<?= url('/vendor/onboarding') ?>" class="px-8 py-4 bg-white/10 border border-white/20 text-white font-black rounded-2xl hover:bg-white/20 transition-all text-sm uppercase tracking-wider backdrop-blur">
                        Jadi Penjual <i class="fa-solid fa-rocket ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>

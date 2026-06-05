<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-20 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-[3rem] p-12 shadow-sm border border-gray-100">
        <h1 class="text-4xl font-black text-gray-900 mb-8">Syarat & Ketentuan</h1>
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-10 border-b border-gray-100 pb-6">Terakhir diperbarui: 5 Juni 2026</div>
        
        <div class="prose prose-emerald max-w-none space-y-10 text-gray-600 leading-relaxed">
            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">1. Penerimaan Ketentuan</h2>
                <p>Dengan mengakses dan menggunakan platform LitleMart, Anda setuju untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak setuju dengan bagian mana pun dari ketentuan ini, Anda tidak diperbolehkan untuk menggunakan layanan kami.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">2. Akun Pengguna</h2>
                <p>Anda bertanggung jawab untuk menjaga kerahasiaan akun dan kata sandi Anda. Anda harus berusia minimal 18 tahun atau memiliki izin orang tua untuk menggunakan platform ini.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">3. Transaksi Penjualan</h2>
                <p>Semua transaksi diproses melalui metode pembayaran yang disediakan. LitleMart bertindak sebagai perantara yang aman. Dana akan dilepaskan ke penjual setelah pembeli mengonfirmasi penerimaan barang atau setelah periode otomatis berakhir.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">4. Kebijakan Pengembalian</h2>
                <p>Pengembalian barang dan dana diatur berdasarkan kesepakatan antara penjual dan pembeli. LitleMart dapat memediasi jika terjadi sengketa yang tidak dapat diselesaikan sendiri.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-gray-900 mb-4">5. Perubahan Ketentuan</h2>
                <p>Kami berhak untuk mengubah Syarat dan Ketentuan ini kapan saja. Perubahan akan berlaku segera setelah dipublikasikan di halaman ini.</p>
            </section>
        </div>

        <div class="mt-20 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm text-gray-400">Punya pertanyaan tentang ketentuan kami?</p>
            <a href="<?= url('/contact') ?>" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all text-sm">Hubungi Tim Legal</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

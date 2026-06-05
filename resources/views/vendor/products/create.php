<?php include __DIR__ . '/../../layouts/header.php'; ?>
<div class="flex min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto">
                <div class="mb-6">
                    <a href="<?= url('/vendor/products') ?>" class="text-xs font-semibold text-gray-500 hover:text-[#056526] flex items-center gap-1.5 mb-3 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Produk
                    </a>
                    <h2 class="text-xl font-black text-gray-900">Tambah Produk Baru</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Isi detail di bawah untuk mendaftarkan produk Anda di marketplace.</p>
                </div>

                <form action="<?= url('/vendor/products') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <!-- Basic Info -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-[#056526] text-white text-xs flex items-center justify-center font-black">1</span>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="misal: Kursi Baca Kayu"
                                    class="w-full px-4 py-3 border border-gray-300 bg-white rounded-xl text-base font-bold text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                                <select name="category_id" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white">
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Merek <span class="text-slate-400">(Opsional)</span></label>
                                <input type="text" name="brand" placeholder="misal: Sony, IKEA"
                                    class="w-full px-4 py-2.5 border border-gray-200 bg-white rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kondisi <span class="text-red-500">*</span></label>
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="condition" value="new" checked class="hidden peer">
                                        <div class="py-2.5 text-center rounded-xl border border-gray-200 peer-checked:border-[#056526] peer-checked:bg-green-50 peer-checked:text-[#056526] text-sm font-bold text-gray-500 group-hover:bg-gray-50 transition-all">Baru</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="condition" value="used" class="hidden peer">
                                        <div class="py-2.5 text-center rounded-xl border border-gray-200 peer-checked:border-[#056526] peer-checked:bg-green-50 peer-checked:text-[#056526] text-sm font-bold text-gray-500 group-hover:bg-gray-50 transition-all">Bekas</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="4" required placeholder="Jelaskan bahan, fitur, dan dimensi produk..."
                                class="w-full px-4 py-2.5 border border-gray-200 bg-white rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Pricing & Inventory -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-[#056526] text-white text-xs flex items-center justify-center font-black">2</span>
                            Harga & Inventori
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">Rp</span>
                                    <input type="number" name="price" required min="0" placeholder="0"
                                        class="w-full pl-9 pr-4 py-2.5 border border-gray-200 bg-white rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                                <input type="number" name="stock" required min="0" placeholder="0"
                                    class="w-full px-4 py-2.5 border border-gray-200 bg-white rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Berat (gram) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="weight" required min="0" placeholder="0"
                                        class="w-full pl-4 pr-10 py-2.5 border border-gray-200 bg-white rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526]">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">gr</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-[#056526] text-white text-xs flex items-center justify-center font-black">3</span>
                            Foto Produk
                        </h3>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-[#056526] transition-colors cursor-pointer" onclick="document.getElementById('images').click()">
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm font-semibold text-gray-600">Klik untuk unggah foto</p>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG — maks 5 foto</p>
                        </div>
                        <div id="preview" class="grid grid-cols-4 gap-3 mt-2"></div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pb-6">
                        <a href="<?= url('/vendor/products') ?>" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors border border-gray-200">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-[#056526] text-white hover:bg-green-800 transition-colors shadow-sm">
                            Terbitkan Produk
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script>
function previewImages(input) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    Array.from(input.files).slice(0, 5).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML += `<div class="aspect-square rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                <img src="${e.target.result}" class="w-full h-full object-cover">
            </div>`;
        };
        reader.readAsDataURL(file);
    });
}
</script>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>

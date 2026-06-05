<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Prepare data for Alpine.js safely
$selectedItems = array_map('strval', array_column($items, 'id'));
$alpineItems = array_map(function($i) {
    return [
        'id' => strval($i['id']),
        'subtotal' => (float)$i['subtotal']
    ];
}, $items);

$alpineData = [
    'selected' => $selectedItems,
    'items' => $alpineItems
];
?>

<main class="container mx-auto px-4 py-8 md:py-12 pb-32 md:pb-12" 
      x-data='{ 
        selected: <?= json_encode($selectedItems) ?>,
        items: <?= json_encode($alpineItems) ?>,
        getTotal() {
            let sum = 0;
            this.items.forEach(i => {
                if (this.selected.includes(i.id)) {
                    sum += i.subtotal;
                }
            });
            return sum;
        },
        toggleAll(checked) {
            this.selected = checked ? this.items.map(i => i.id) : [];
        },
        formatRupiah(num) {
            return "Rp " + new Intl.NumberFormat("id-ID").format(num);
        }
      }'>
      
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8 md:mb-12">
        <div class="flex items-center gap-3">
            <a href="<?= url('/products') ?>" class="md:hidden w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-500">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-5xl font-black text-gray-900 tracking-tighter">Keranjang</h1>
                <p class="hidden md:block text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Daftar produk pilihan terbaik Anda</p>
            </div>
        </div>
        <div class="flex flex-col items-end">
            <p class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-widest border border-emerald-100 mb-1">LitleMart Secure</p>
            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest md:hidden">
                <span x-text="selected.length"></span> Item Terpilih
            </p>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <div class="bg-white p-12 md:p-20 rounded-[3rem] border border-gray-100 shadow-sm text-center">
            <div class="w-24 h-24 bg-gray-50 text-gray-200 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                <i class="fa-solid fa-basket-shopping text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2 tracking-tight">Keranjang Anda kosong</h2>
            <p class="text-gray-400 mb-10 font-medium">Temukan produk impianmu dan masukkan ke sini!</p>
            <a href="<?= url('/products') ?>" class="inline-flex items-center gap-3 bg-emerald-600 text-white px-10 py-4 rounded-2xl font-black hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-200 active:scale-95">
                <span>Mulai Belanja</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    <?php else: ?>
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-start">
            
            <!-- ITEMS LIST -->
            <div class="w-full lg:w-[65%] space-y-4 md:space-y-6 order-1">
                <!-- Select All Control -->
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between mb-2">
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" 
                                @change="toggleAll($event.target.checked)" 
                                :checked="selected.length === items.length && items.length > 0" 
                                class="peer sr-only">
                            <div class="w-6 h-6 border-2 border-gray-200 rounded-lg bg-white peer-checked:bg-emerald-600 peer-checked:border-emerald-600 transition-all flex items-center justify-center">
                                <i class="fa-solid fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-all"></i>
                            </div>
                        </div>
                        <span class="text-xs font-black text-gray-900 uppercase tracking-widest group-hover:text-emerald-600 transition-colors">Pilih Semua (<span x-text="items.length"></span>)</span>
                    </label>
                </div>

                <?php foreach ($items as $item): ?>
                    <!-- PRODUCT CARD -->
                    <div class="bg-white p-3 md:p-6 rounded-[1.5rem] md:rounded-[3rem] border border-gray-100 shadow-sm flex items-center gap-3 md:gap-8 relative overflow-hidden">
                        <!-- Checkbox -->
                        <label class="flex-shrink-0 cursor-pointer">
                            <input type="checkbox" value="<?= $item['id'] ?>" x-model="selected" class="peer sr-only">
                            <div class="w-5 h-5 md:w-6 md:h-6 border-2 border-gray-200 rounded-lg bg-white peer-checked:bg-emerald-600 peer-checked:border-emerald-600 transition-all flex items-center justify-center">
                                <i class="fa-solid fa-check text-white text-[8px] md:text-[10px] opacity-0 peer-checked:opacity-100 transition-all"></i>
                            </div>
                        </label>

                        <!-- Image -->
                        <div class="w-20 h-20 md:w-32 md:h-32 rounded-2xl md:rounded-3xl bg-gray-50 flex-shrink-0 overflow-hidden border border-gray-100">
                             <img src="<?= !empty($item['main_image']) ? url($item['main_image']) : 'https://picsum.photos/seed/'.$item['id'].'/400/400' ?>" class="w-full h-full object-cover">
                        </div>

                        <!-- Info -->
                        <div class="flex-grow min-w-0">
                            <h3 class="text-xs md:text-xl font-black text-gray-900 truncate mb-0.5"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-[9px] md:text-xs text-emerald-600 font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                                <i class="fa-solid fa-shop text-[8px]"></i>
                                <?= htmlspecialchars($item['store_name']) ?>
                            </p>
                            
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-gray-900 font-black text-xs md:text-2xl tracking-tighter">
                                    Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                </div>

                                <!-- Qty -->
                                <div class="flex items-center bg-gray-50 rounded-xl border border-gray-100 p-0.5">
                                    <button onclick="updateQty('<?= $item['id'] ?>', <?= $item['quantity'] - 1 ?>, <?= $item['stock'] ?>)" 
                                            class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center bg-white rounded-lg text-gray-400 hover:text-emerald-600 transition-all font-black">-</button>
                                    <span class="w-7 md:w-10 text-center font-black text-gray-900 text-[10px] md:text-sm"><?= $item['quantity'] ?></span>
                                    <button onclick="updateQty('<?= $item['id'] ?>', <?= $item['quantity'] + 1 ?>, <?= $item['stock'] ?>)" 
                                            class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center bg-white rounded-lg text-gray-400 hover:text-emerald-600 transition-all font-black">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Delete -->
                        <div class="absolute top-2 right-2">
                            <a href="<?= url('/cart/remove/' . $item['id']) ?>" class="text-red-300 hover:text-red-500 p-1 transition-colors">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- SUMMARY -->
            <div class="w-full lg:w-[35%] order-2">
                <form action="<?= url('/checkout/prepare') ?>" method="POST" 
                      class="fixed bottom-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-xl border-t border-gray-100 p-6 md:p-10 md:static md:bg-gray-900 md:text-white md:rounded-[3rem] md:shadow-2xl">
                    
                    <h2 class="hidden md:block text-xl font-black uppercase tracking-widest mb-8 text-emerald-400 italic">Billing Summary</h2>
                    
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="selected_items[]" :value="id">
                    </template>

                    <div class="hidden md:flex flex-col gap-4 mb-8">
                        <div class="flex justify-between items-center opacity-60 text-xs font-black uppercase tracking-widest">
                            <span>Subtotal</span>
                            <span x-text="formatRupiah(getTotal())"></span>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                            <span class="text-sm font-black uppercase tracking-widest">Grand Total</span>
                            <span class="text-3xl font-black tracking-tighter text-emerald-400" x-text="formatRupiah(getTotal())"></span>
                        </div>
                    </div>

                    <!-- Mobile Summary -->
                    <div class="md:hidden flex items-center justify-between mb-4">
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Bill</p>
                            <p class="text-xl font-black text-emerald-600 tracking-tighter" x-text="formatRupiah(getTotal())"></p>
                        </div>
                    </div>

                    <button type="submit" 
                            :disabled="selected.length === 0"
                            :class="selected.length === 0 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-emerald-600 md:bg-white md:text-emerald-900 shadow-xl shadow-emerald-200 md:shadow-none'"
                            class="block w-full text-center py-4 md:py-5 rounded-2xl font-black text-sm md:text-base transition-all active:scale-95">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>

        </div>
    <?php endif; ?>
</main>

<script>
async function updateQty(id, qty, stock) {
    if (qty < 1) {
        if (confirm('Hapus dari keranjang?')) window.location.href = '<?= url('/cart/remove/') ?>' + id;
        return;
    }
    if (qty > stock) return alert('Stok tidak cukup');
    
    const res = await fetch('<?= url('/cart/update') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${id}&quantity=${qty}`
    });
    if (res.ok) window.location.reload();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php include __DIR__ . '/../layouts/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="container mx-auto px-4 py-6 md:py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-5 md:mb-8">Pembayaran</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-12">
            <!-- Shipping & Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Address Section -->
                <div class="bg-white p-5 md:p-8 rounded-2xl md:rounded-3xl border border-gray-100 shadow-sm space-y-5 md:space-y-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Detail Penerima
                    </h2>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Nama Penerima</label>
                        <input type="text" id="receiver_name" value="<?php echo htmlspecialchars($userName ?? ''); ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 outline-none bg-gray-50" placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" 
                         x-data="{ 
                        provinces: <?php echo htmlspecialchars(json_encode($provinces)); ?>,
                        cities: [],
                        searchProv: '',
                        searchCity: '',
                        showProv: false,
                        showCity: false,
                        selectedProv: null,
                        selectedCity: null,
                        loadingProvinces: false,
                        loadingCities: false,

                        async init() {
                            // No need to fetch provinces, they are passed from PHP
                        },

                        async selectProv(p) {
                            this.selectedProv = p;
                            this.searchProv = p.province;
                            this.showProv = false;
                            this.loadingCities = true;
                            this.selectedCity = null;
                            this.searchCity = '';
                            this.cities = [];
                            
                            try {
                                const res = await fetch('<?= url('/api/shipping/cities') ?>?province=' + p.province_id);
                                this.cities = await res.json();
                            } catch (e) {
                                console.error('Failed to load cities');
                            } finally {
                                this.loadingCities = false;
                            }
                        },

                        selectCity(c) {
                            this.selectedCity = c;
                            this.searchCity = c.type + ' ' + c.city_name;
                            this.showCity = false;
                            window.dispatchEvent(new CustomEvent('city-selected', { detail: c.city_id }));
                        }
                    }">
                        <!-- Province Autocomplete -->
                        <div class="relative space-y-2">
                            <label class="text-sm font-medium text-gray-700">Provinsi</label>
                            <div class="relative">
                                <input type="text" 
                                       x-model="searchProv" 
                                       @input="showProv = true"
                                       @click="showProv = true"
                                       @click.away="showProv = false"
                                       :placeholder="loadingProvinces ? 'Memuat provinsi...' : 'Ketik min. 3 huruf...'"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 outline-none bg-gray-50 transition-all">
                                <div x-show="loadingProvinces" class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <div class="animate-spin w-4 h-4 border-2 border-emerald-600 border-t-transparent rounded-full"></div>
                                </div>
                            </div>
                            
                            <div x-show="showProv && searchProv.length >= 3" class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl max-h-60 overflow-y-auto" x-cloak>
                                <template x-for="p in provinces.filter(p => p.province.toLowerCase().includes(searchProv.toLowerCase()))" :key="p.province_id">
                                    <div @click="selectProv(p)" class="px-4 py-3 hover:bg-emerald-100 cursor-pointer text-sm transition-colors border-b border-gray-50 last:border-0">
                                        <span class="font-medium text-gray-900" x-text="p.province"></span>
                                    </div>
                                </template>
                                <div x-show="provinces.filter(p => p.province.toLowerCase().includes(searchProv.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-gray-500 italic">
                                    Provinsi tidak ditemukan
                                </div>
                            </div>
                        </div>

                        <!-- City Autocomplete -->
                        <div class="relative space-y-2">
                            <label class="text-sm font-medium text-gray-700">Kota / Kabupaten</label>
                            <div class="relative">
                                <input type="text" 
                                       x-model="searchCity" 
                                       @input="showCity = true"
                                       @click="showCity = true"
                                       @click.away="showCity = false"
                                       :disabled="!selectedProv || loadingCities"
                                       :placeholder="loadingCities ? 'Memuat kota...' : (selectedProv ? 'Ketik min. 3 huruf...' : 'Pilih provinsi dahulu')"
                                       :class="(!selectedProv || loadingCities) ? 'bg-gray-100 cursor-not-allowed opacity-60' : 'bg-gray-50'"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 outline-none transition-all">
                                
                                <div x-show="loadingCities" class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <div class="animate-spin w-4 h-4 border-2 border-emerald-600 border-t-transparent rounded-full"></div>
                                </div>
                            </div>
                            
                            <div x-show="showCity && selectedProv && searchCity.length >= 3" class="absolute z-50 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl max-h-60 overflow-y-auto" x-cloak>
                                <template x-for="c in cities.filter(c => c.city_name.toLowerCase().includes(searchCity.toLowerCase()) || (c.type + ' ' + c.city_name).toLowerCase().includes(searchCity.toLowerCase()))" :key="c.city_id">
                                    <div @click="selectCity(c)" class="px-4 py-3 hover:bg-emerald-100 cursor-pointer text-sm transition-colors border-b border-gray-50 last:border-0">
                                        <span class="font-medium text-gray-900" x-text="c.type + ' ' + c.city_name"></span>
                                    </div>
                                </template>
                                <div x-show="cities.filter(c => c.city_name.toLowerCase().includes(searchCity.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-gray-500 italic">
                                    Kota tidak ditemukan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea id="address" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 outline-none bg-gray-50" placeholder="Nama Jalan, Nomor Gedung, dll."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" id="phone" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 outline-none bg-gray-50" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Kode Pos</label>
                            <input type="text" id="postal_code" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 outline-none bg-gray-50" placeholder="12345">
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Metode Pengiriman
                    </h2>
                    <div id="shipping-options" class="grid grid-cols-1 gap-4">
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 text-gray-400 text-center italic">
                            Pilih provinsi dan kota untuk melihat ongkos kirim
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Metode Pembayaran
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center gap-4 p-4 border-2 border-emerald-600 rounded-2xl bg-emerald-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="online" checked class="w-4 h-4 text-emerald-600">
                            <div>
                                <p class="font-bold text-gray-900">Pembayaran Online Aman</p>
                                <p class="text-xs text-emerald-600">E-Wallet, Virtual Account, Qris</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-4 p-4 border border-gray-100 rounded-2xl bg-white grayscale opacity-50 cursor-not-allowed">
                            <input type="radio" name="payment_method" disabled class="w-4 h-4 text-gray-300">
                            <div>
                                <p class="font-bold text-gray-400">Bayar di Tempat</p>
                                <p class="text-xs text-gray-400">Segera hadir</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Summary & Payment -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Rincian Pesanan</h2>
                    <div class="space-y-4 mb-6">
                        <?php foreach ($items as $item): ?>
                            <div class="flex justify-between gap-4">
                                <div class="flex-grow">
                                    <h4 class="text-sm font-semibold text-gray-900 line-clamp-1"><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <span class="text-xs text-gray-500"><?php echo $item['quantity']; ?>x Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></span>
                                </div>
                                <span class="text-sm font-bold text-gray-900 whitespace-nowrap">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-gray-100 mb-8">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal</span>
                            <span id="subtotal">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Ongkos Kirim</span>
                            <span id="shipping-cost">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-900 pt-3">
                            <span class="font-bold">Total Bayar</span>
                            <span id="grand-total" class="text-2xl font-bold text-emerald-600">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <button id="pay-button" class="w-full bg-emerald-600 text-white py-4 rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ===================== PREMIUM PAYMENT MODAL (LitleMart Payment Suite) ===================== -->
<div id="payment-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 py-10">
    <!-- Darker Backdrop for more focus -->
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm transition-opacity duration-500"></div>
    
    <!-- Modal Container: Fixed precise width to match Snap -->
    <div class="relative bg-white w-full max-w-[440px] rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden transform transition-all duration-500 scale-95 opacity-0 flex flex-col h-fit max-h-full" id="payment-modal-container">
        
        <!-- Premium Modal Header -->
        <div class="px-6 py-5 flex items-center justify-between bg-white border-b border-gray-50 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-shield-check text-lg"></i>
                </div>
                <div>
                    <h4 class="font-black text-gray-900 text-xs italic leading-tight tracking-wider uppercase">LitleMart Secure Payment</h4>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-widest leading-none">Security Verified</p>
                    </div>
                </div>
            </div>
            <button onclick="closePaymentModal()" class="w-8 h-8 bg-gray-50 text-gray-400 rounded-lg flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Order Summary Bar (Inside Modal) -->
        <div id="modal-order-info" class="hidden px-6 py-4 bg-gray-50/80 border-b border-gray-100 items-center justify-between shrink-0">
            <div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1.5">Total Bayar</p>
                <p id="modal-grand-total" class="text-xl font-black text-emerald-700 leading-none">Rp 0</p>
            </div>
            <div class="text-right">
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1.5 text-right">Order Ref</p>
                <p id="modal-order-id" class="text-[10px] font-mono font-bold text-gray-800 bg-white px-2 py-1 rounded-md border border-gray-200">#ORD-XXXX</p>
            </div>
        </div>
        
        <!-- Content Area: NO PADDING for Snap integration -->
        <div class="flex-1 overflow-hidden flex flex-col min-h-[480px]">
            <!-- Loading State -->
            <div id="payment-loading" class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-white">
                <div class="relative w-20 h-20 mb-6">
                    <div class="absolute inset-0 bg-emerald-100 rounded-full animate-ping opacity-25"></div>
                    <div class="relative bg-white w-20 h-20 rounded-full shadow-[0_8px_20px_rgba(5,101,38,0.1)] flex items-center justify-center border border-emerald-50">
                        <i class="fa-solid fa-lock text-2xl text-emerald-600"></i>
                    </div>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-2">Sesi Aman Dimulai</h3>
                <p class="text-gray-400 text-xs font-medium leading-relaxed px-4">Gunakan metode pembayaran pilihan Anda di bawah ini.</p>
            </div>

            <!-- Snap Container Wrapper: No margins, no padding -->
            <div id="snap-container-wrapper" class="hidden flex-1 overflow-y-auto">
                <div id="snap-container" class="w-full">
                    <!-- Midtrans Snap will load here and fill 100% -->
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="py-3 bg-gray-50 text-center shrink-0 border-t border-gray-100">
            <p class="text-[8px] text-gray-400 font-bold uppercase tracking-[0.3em]">Direct Connection &bull; Midtrans Official Gateway</p>
        </div>
    </div>
</div>

<style>
    #snap-container, #snap-container iframe { 
        width: 100% !important; 
        min-height: 480px !important;
        border: none !important; 
        margin: 0 !important;
        padding: 0 !important;
    }
    /* Hide Midtrans's default padding if possible through iframe centering */
    #snap-container {
        display: flex;
        justify-content: center;
    }
</style>


<script>

const shippingOptions = document.getElementById('shipping-options');
const midtransClientKey = "<?php echo $midtransClientKey; ?>";
const payButton = document.getElementById('pay-button');
console.log('Midtrans Client Key:', midtransClientKey);

if (!midtransClientKey) {
    alert('Warning: Midtrans Client Key is empty. Check your .env file!');
}

let selectedShippingCost = 0;
const baseTotal = <?php echo $total; ?>;

window.addEventListener('city-selected', async (e) => {
    const cityId = e.detail;
    shippingOptions.innerHTML = '<div class="col-span-full py-8 text-center"><div class="animate-spin w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full mx-auto mb-2"></div><p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Calculating shipping rates...</p></div>';
    
    // Call shipping calculation API
    const res = await fetch('<?= url('/api/shipping/calculate') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            origin: <?= $items[0]['vendor_city_id'] ?? 152 ?>,
            destination: cityId,
            weight: <?php 
                $totalWeight = 0;
                foreach($items as $item) {
                    $totalWeight += ($item['weight'] ?? 1000) * $item['quantity'];
                }
                echo $totalWeight;
            ?>
        })
    });
    const couriers = await res.json();
    
    shippingOptions.innerHTML = '';
    if (couriers.length === 0) {
        shippingOptions.innerHTML = '<div class="col-span-full p-4 border border-red-100 rounded-xl bg-red-50 text-red-500 text-center text-xs font-bold uppercase">No shipping options available for this destination.</div>';
        return;
    }

    couriers.forEach(cGroup => {
        cGroup.costs.forEach(service => {
            service.cost.forEach(c => {
                shippingOptions.innerHTML += `
                    <label class="flex justify-between items-center p-4 border border-gray-100 rounded-xl hover:border-emerald-300 cursor-pointer transition-all bg-white group">
                        <div class="flex items-center gap-4">
                            <input type="radio" name="shipping" value="${c.value}" onclick="updateTotal(${c.value})" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                            <div>
                                <p class="font-black text-gray-900 text-sm">${cGroup.courier} <span class="text-xs text-gray-400 font-medium ml-2">${service.service}</span></p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.1em]">Estimasi: ${c.etd || '-'} Hari</p>
                            </div>
                        </div>
                        <p class="font-black text-emerald-600">Rp ${c.value.toLocaleString('id-ID')}</p>
                    </label>
                `;
            });
        });
    });
});

function updateTotal(cost) {
    selectedShippingCost = cost;
    document.getElementById('shipping-cost').innerText = `Rp ${cost.toLocaleString('id-ID')}`;
    const grandTotal = baseTotal + cost;
    document.getElementById('grand-total').innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
}

function openPaymentModal() {
    const modal = document.getElementById('payment-modal');
    const container = document.getElementById('payment-modal-container');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closePaymentModal() {
    const modal = document.getElementById('payment-modal');
    const container = document.getElementById('payment-modal-container');
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        payButton.disabled = false;
        payButton.innerHTML = 'Bayar Sekarang';
    }, 500);
}

payButton.addEventListener('click', async () => {
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;
    const postalCode = document.getElementById('postal_code').value;
    const receiverName = document.getElementById('receiver_name').value;
    const provinceName = document.querySelector('[x-model="searchProv"]')?.value;
    const cityName = document.querySelector('[x-model="searchCity"]')?.value;
    const isBuyNow = <?= $isBuyNow ? 'true' : 'false' ?>;

    if (!receiverName || !phone || !address || !postalCode || !cityName || selectedShippingCost === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Mohon isi data penerima, alamat, dan kurir terlebih dahulu.',
            confirmButtonColor: '#056526'
        });
        return;
    }

    // Modern Feedback
    payButton.disabled = true;
    payButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengamankan Sesi...';
    openPaymentModal();

    try {
        const res = await fetch('<?= url('/checkout/process') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                is_buy_now: isBuyNow,
                receiver_name: receiverName,
                phone: phone,
                address: address,
                postal_code: postalCode,
                province_name: provinceName,
                city_name: cityName,
                shipping_cost: selectedShippingCost
            })
        });

        const result = await res.json();
        if (result.error) throw new Error(result.error);

        // Fill Modal Info
        const grandTotalText = document.getElementById('grand-total').innerText;
        document.getElementById('modal-grand-total').innerText = grandTotalText;
        document.getElementById('modal-order-id').innerText = '#' + result.order_id;
        document.getElementById('modal-order-info').classList.remove('hidden');
        document.getElementById('modal-order-info').classList.add('flex');

        // Switch from loading to Snap Container
        document.getElementById('payment-loading').classList.add('hidden');
        document.getElementById('snap-container-wrapper').classList.remove('hidden');

        window.snap.embed(result.snap_token, {
            embedId: 'snap-container',
            onSuccess: async function(midtransResult) { 
                await fetch('<?= url('/api/payments/finish') ?>', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ order_id: result.order_id, status: 'paid' })
                });
                window.location.href = '<?= url('/orders/success') ?>?id=' + result.order_id; 
            },
            onPending: function(midtransResult) { 
                window.location.href = '<?= url('/orders/pending') ?>?id=' + result.order_id; 
            },
            onError: function(midtransResult) { 
                Swal.fire('Gagal!', 'Pembayaran gagal diproses.', 'error');
                closePaymentModal();
            },
            onClose: function() {
                closePaymentModal();
            }
        });
    } catch (e) {
        Swal.fire('Kesalahan', e.message, 'error');
        closePaymentModal();
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siapkan Toko Anda - LitleMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#056526' }, fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; background-color: #EBF5EB; }
        input, textarea, select { color: #000000 !important; background-color: #ffffff !important; }
        
        /* Fix for Autofill visibility */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        textarea:-webkit-autofill:active,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus,
        select:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #000000 !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col bg-[#EBF5EB]">
    <!-- Minimal Navbar -->
    <header class="bg-[#EBF5EB] border-b border-gray-200 h-14 flex items-center justify-between px-8">
        <div class="flex items-center gap-3">
            <a href="<?= url('/') ?>" class="font-bold text-[#056526] text-lg hover:opacity-80 transition-opacity">LitleMart</a>
            <div class="h-5 w-px bg-gray-300"></div>
            <span class="text-xs font-bold text-gray-500 tracking-widest uppercase">Pendaftaran Vendor</span>
        </div>
        <div class="flex items-center gap-6">
            <a href="<?= url('/') ?>" class="text-sm font-semibold text-gray-600 hover:text-[#056526] transition-colors">Beranda</a>
            <a href="<?= url('/') ?>" class="text-sm font-bold text-[#056526] bg-white px-4 py-1.5 rounded-full border border-green-100 shadow-sm hover:bg-green-50 transition-all">Simpan & Keluar</a>
            <button class="w-7 h-7 rounded-full border border-gray-300 text-gray-500 flex items-center justify-center text-xs font-bold hover:bg-white">?</button>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 flex items-start justify-center px-4 py-16" x-data="{ step: 1 }">
        <div class="flex flex-col lg:flex-row gap-6 w-full max-w-4xl">

            <!-- Left Steps Panel -->
            <div class="w-full lg:w-72 flex-shrink-0 space-y-4">
                <!-- Steps -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-5">Langkah Pendaftaran</h2>
                    <div class="space-y-4">
                        <!-- Step 1 -->
                        <div class="flex items-center gap-3 p-3 rounded-xl transition-colors" :class="step === 1 ? 'bg-[#056526] text-white' : (step > 1 ? 'text-[#056526]' : 'text-gray-400')">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" :class="step === 1 ? 'bg-white/20' : (step > 1 ? 'bg-green-100' : 'bg-gray-100')">
                                <svg x-show="step <= 1" class="w-4 h-4" :class="step === 1 ? 'text-white' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <svg x-show="step > 1" class="w-4 h-4 text-[#056526]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs transition-opacity" :class="step === 1 ? 'opacity-80 font-bold' : ''">Langkah 1</div>
                                <div class="text-sm font-semibold">Informasi Toko</div>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-center gap-3 p-3 rounded-xl transition-colors" :class="step === 2 ? 'bg-[#056526] text-white' : (step > 2 ? 'text-[#056526]' : 'text-gray-400')">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" :class="step === 2 ? 'bg-white/20' : (step > 2 ? 'bg-green-100' : 'bg-gray-100')">
                                <svg x-show="step <= 2" class="w-4 h-4" :class="step === 2 ? 'text-white' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <svg x-show="step > 2" class="w-4 h-4 text-[#056526]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs transition-opacity" :class="step === 2 ? 'opacity-80 font-bold' : ''">Langkah 2</div>
                                <div class="text-sm font-semibold">Detail Bisnis</div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-center gap-3 p-3 rounded-xl transition-colors" :class="step === 3 ? 'bg-[#056526] text-white' : 'text-gray-400'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" :class="step === 3 ? 'bg-white/20' : 'bg-gray-100'">
                                <svg class="w-4 h-4" :class="step === 3 ? 'text-white' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs transition-opacity" :class="step === 3 ? 'opacity-80 font-bold' : ''">Langkah 3</div>
                                <div class="text-sm font-semibold">Verifikasi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-[#056526] text-white rounded-xl p-5">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-sm leading-relaxed opacity-90">
                        Menyiapkan akun LitleMart Pro Anda membutuhkan waktu kurang dari 5 menit. Pastikan semua dokumen valid dan sesuai dengan identitas hukum Anda untuk menghindari penundaan.
                    </p>
                </div>

                <!-- Security note -->
                <div class="flex items-center gap-2 text-xs text-gray-500 px-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Enkripsi AES-256 Aman
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="flex-1 bg-white rounded-xl border border-gray-200 shadow-sm p-8 lg:p-10 relative overflow-hidden">
                <form action="<?= url('/vendor/onboarding') ?>" method="POST" id="onboardingForm" class="space-y-6">
                    
                    <!-- STEP 1: Store Information -->
                    <div id="step1" x-show="step === 1" x-transition.opacity.duration.300ms>
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">Ceritakan tentang toko Anda</h2>
                        <p class="text-sm text-gray-500 mb-8">Bagaimana pelanggan akan melihat merek Anda di LitleMart.</p>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Toko</label>
                                <input name="store_name" type="text" required placeholder="misal: Dekorasi Rumah Modern" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                <select name="category" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white">
                                    <option value="" class="text-gray-400">Pilih kategori</option>
                                    <option>Elektronik</option>
                                    <option>Fashion & Pakaian</option>
                                    <option>Rumah & Gaya Hidup</option>
                                    <option>Kecantikan & Kesehatan</option>
                                    <option>Olahraga & Luar Ruangan</option>
                                    <option>Makanan & Minuman</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Toko (Opsional)</label>
                                <textarea name="description" rows="4" placeholder="Jelaskan secara singkat apa yang Anda jual..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white resize-none"></textarea>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="button" @click="
                                let inputs = document.getElementById('step1').querySelectorAll('input[required], select[required], textarea[required]');
                                let valid = true;
                                for(let i of inputs) { if(!i.reportValidity()) { valid = false; break; } }
                                if(valid) step = 2;
                            " class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-[#056526] text-white text-sm font-bold rounded-xl hover:bg-green-800 transition-colors shadow-sm">
                                Lanjutkan ke Detail Bisnis
                                <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Business Details -->
                    <div id="step2" x-show="step === 2" x-cloak x-transition.opacity.duration.300ms>
                        <button type="button" @click="step = 1" class="text-sm font-bold text-gray-500 hover:text-gray-900 mb-4 inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </button>

                        <h2 class="text-2xl font-bold text-gray-900 mb-1">Detail Bisnis</h2>
                        <p class="text-sm text-gray-500 mb-8">Dari mana Anda beroperasi?</p>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Bisnis</label>
                                <input name="phone" type="tel" required placeholder="+62 812..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ 
                                provinces: [], 
                                cities: [], 
                                selectedProv: '', 
                                selectedCity: '', 
                                searchCity: '',
                                showCity: false,
                                loadingCities: false,
                                async fetchCities() {
                                    if(!this.selectedProv) return;
                                    this.loadingCities = true;
                                    const res = await fetch('<?= url('/api/shipping/cities?province=') ?>' + this.selectedProv);
                                    this.cities = await res.json();
                                    this.loadingCities = false;
                                }
                            }" x-init="provinces = await (await fetch('<?= url('/api/shipping/provinces') ?>')).json()">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <select name="province_id" required x-model="selectedProv" @change="fetchCities(); selectedCity = ''; searchCity = ''" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white">
                                        <option value="">Pilih Provinsi</option>
                                        <template x-for="p in provinces" :key="p.province_id">
                                            <option :value="p.province_id" x-text="p.province"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                    <input type="text" x-model="searchCity" 
                                           @input="showCity = true"
                                           @click="showCity = true" 
                                           @click.away="showCity = false"
                                           :disabled="!selectedProv || loadingCities"
                                           :placeholder="loadingCities ? 'Memuat...' : (selectedProv ? 'Cari kota...' : 'Pilih provinsi dahulu')"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white">
                                    
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

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea name="address" required rows="4" placeholder="Jalan Sudirman No. 123..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#056526]/20 focus:border-[#056526] bg-white resize-none"></textarea>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="button" @click="
                                let inputs = document.getElementById('step2').querySelectorAll('input[required], select[required], textarea[required]');
                                let valid = true;
                                for(let i of inputs) { if(!i.reportValidity()) { valid = false; break; } }
                                if(valid) step = 3;
                            " class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-[#056526] text-white text-sm font-bold rounded-xl hover:bg-green-800 transition-colors shadow-sm">
                                Lanjutkan ke Verifikasi
                                <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Verification -->
                    <div id="step3" x-show="step === 3" x-cloak x-transition.opacity.duration.300ms>
                        <button type="button" @click="step = 2" class="text-sm font-bold text-gray-500 hover:text-gray-900 mb-4 inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </button>

                        <h2 class="text-2xl font-bold text-gray-900 mb-1">Verifikasi Akhir</h2>
                        <p class="text-sm text-gray-500 mb-8">Setujui syarat dan ketentuan kami untuk mengaktifkan akun Anda secara instan.</p>

                        <div class="p-5 border border-green-200 bg-green-50 rounded-xl mb-6 flex items-start gap-4">
                            <div class="pt-1">
                                <input type="checkbox" required class="w-5 h-5 text-[#056526] border-gray-300 rounded focus:ring-[#056526] accent-[#056526]">
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-green-900 mb-1">Saya menerima Syarat dan Kebijakan</h4>
                                <p class="text-xs text-green-700/80 leading-relaxed">
                                    Dengan melanjutkan, saya menyatakan bahwa semua informasi yang diberikan adalah benar dan akurat. Saya setuju untuk mematuhi Kebijakan Vendor LitleMart, ketentuan pembayaran, dan memahami bahwa akun saya dapat diaudit atau ditangguhkan jika terbukti menjual barang palsu.
                                </p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-[#056526] text-white text-sm font-bold rounded-xl hover:bg-green-800 transition-colors shadow-lg shadow-green-900/30">
                                Kirim & Buka Toko Sekarang
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#EBF5EB] border-t border-gray-200 py-6">
        <div class="max-w-5xl mx-auto px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <div class="font-bold text-[#056526] text-base mb-0.5">LitleMart</div>
                <div class="text-xs text-gray-500">© 2024 LitleMart Marketplace. Hak cipta dilindungi undang-undang.</div>
            </div>
            <div class="flex items-center gap-6 text-sm font-medium text-gray-500">
                <a href="<?= url('/help') ?>" class="hover:text-gray-900">Bantuan</a>
                <a href="<?= url('/terms') ?>" class="hover:text-gray-900">Ketentuan Layanan</a>
                <a href="<?= url('/privacy') ?>" class="hover:text-gray-900">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>
</body>
</html>

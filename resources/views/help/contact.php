<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-20 flex flex-col md:flex-row gap-20">
        <!-- Left Side: Info -->
        <div class="flex-1">
            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-black rounded-full uppercase tracking-widest mb-4">Hubungi Kami</span>
            <h1 class="text-5xl font-black text-gray-900 mb-6 leading-tight">Kami Siap Mendengar <span class="text-[#056526]">Suara Anda</span></h1>
            <p class="text-gray-500 text-lg mb-12 leading-relaxed">Punya pertanyaan, feedback, atau ingin berkolaborasi? Jangan ragu untuk menghubungi kami melalui formulir atau kontak di bawah.</p>
            
            <div class="space-y-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-xl shadow-sm text-emerald-600">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Kantor Pusat</h3>
                        <p class="text-sm text-gray-500">Jl. Sultan Alauddin 2 No. 3, Makassar, Sulawesi Selatan, Indonesia</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-xl shadow-sm text-emerald-600">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Email Dukungan</h3>
                        <p class="text-sm text-gray-500">imranzmart023@gmail.com</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-xl shadow-sm text-emerald-600">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">WhatsApp Business</h3>
                        <p class="text-sm text-gray-500">+62 853-4386-9700</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="flex-1">
            <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-2xl relative z-10">
                <form action="#" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" placeholder="John Doe" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/10 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Alamat Email</label>
                            <input type="email" placeholder="john@example.com" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/10 focus:bg-white transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Subjek</label>
                        <select class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/10 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option>Pertanyaan Umum</option>
                            <option>Dukungan Teknis</option>
                            <option>Kemitraan</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pesan Anda</label>
                        <textarea rows="5" placeholder="Tuliskan pesan Anda di sini..." class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/10 focus:bg-white transition-all resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-5 bg-[#056526] text-white font-black rounded-2xl hover:bg-emerald-800 transition-all shadow-xl shadow-green-900/20 text-sm uppercase tracking-widest">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Map Placeholder -->
    <div class="max-w-7xl mx-auto px-4 pb-20">
        <div class="h-96 bg-gray-100 rounded-[3rem] overflow-hidden border border-gray-100 relative">
            <div class="absolute inset-0 flex items-center justify-center text-gray-400 font-bold">
                <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.5744927483547!2d119.42031!3d-5.17428!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee00000000001%3A0x0!2sJl.+Sultan+Alauddin+2%2C+Makassar%2C+Sulawesi+Selatan!5e0!3m2!1sid!2sid!4v1717000000000" 
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade" class="w-full h-full">
            </iframe>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

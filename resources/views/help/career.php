<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php if (isset($_GET['sent'])): ?>
<div id="toast-career" class="fixed top-6 right-6 z-50 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 animate-bounce">
    <i class="fa-solid fa-circle-check text-xl"></i>
    <span class="font-bold text-sm">
        <?= $_GET['sent'] === 'cv' ? 'CV berhasil dikirim! WhatsApp akan terbuka.' : 'Pertanyaan berhasil dikirim! WhatsApp akan terbuka.' ?>
    </span>
</div>
<script>setTimeout(() => document.getElementById('toast-career')?.remove(), 4000);</script>
<?php endif; ?>

<div class="bg-white min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-[#056526] via-[#067a2e] to-[#044a1d] py-32 px-4 relative overflow-hidden text-center">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 text-9xl text-white">
                <i class="fa-solid fa-rocket"></i>
            </div>
            <div class="absolute bottom-10 left-10 text-8xl text-white">
                <i class="fa-solid fa-bullseye"></i>
            </div>
        </div>
        <div class="max-w-4xl mx-auto relative z-10">
            <span class="inline-block px-4 py-1.5 bg-white/15 backdrop-blur text-white text-xs font-bold rounded-full uppercase tracking-widest mb-6 border border-white/20">Mari Tumbuh Bersama</span>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
                Bangun Masa Depan<br><span class="text-emerald-300">E-Commerce</span>
            </h1>
            <p class="text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Bergabunglah dengan tim inovatif kami di LitleMart dan bantu kami mendisrupsi cara orang berbelanja dan berjualan.
            </p>
        </div>
    </div>

    <!-- Values -->
    <div class="max-w-7xl mx-auto px-4 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-black text-gray-900 mb-4">Kenapa Bergabung dengan Kami?</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Kami menghargai kreativitas, kolaborasi, dan kemandirian dalam bekerja.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <?php
            $values = [
                ['icon' => 'fa-solid fa-lightbulb', 'title' => 'Inovasi Tanpa Batas', 'desc' => 'Kami selalu mencari cara baru untuk menyelesaikan masalah kompleks di dunia digital.'],
                ['icon' => 'fa-solid fa-users-gear', 'title' => 'Budaya Kolaboratif', 'desc' => 'Bekerja dengan tim yang suportif dan saling membantu untuk mencapai tujuan bersama.'],
                ['icon' => 'fa-solid fa-scale-balanced', 'title' => 'Keseimbangan Hidup', 'desc' => 'Kami mengutamakan kesejahteraan tim dengan jam kerja yang fleksibel dan lingkungan sehat.'],
            ];
            foreach ($values as $v): ?>
            <div class="p-10 bg-gray-50 rounded-[3rem] border border-gray-100 hover:shadow-xl transition-all text-center">
                <div class="text-5xl mb-6 text-emerald-600">
                    <i class="<?= $v['icon'] ?>"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-4"><?= $v['title'] ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?= $v['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Talent Pool / Join Us -->
    <div class="bg-gray-50 py-32 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-12 md:p-16 rounded-[4rem] border border-gray-100 shadow-xl text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-[2rem] flex items-center justify-center text-3xl mx-auto mb-8 shadow-inner">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <h2 class="text-4xl font-black text-gray-900 mb-6">Bergabung dengan Talent Pool Kami</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-10">
                        Kami selalu mencari individu berbakat yang memiliki semangat untuk mengubah masa depan perdagangan. 
                        Walaupun saat ini kami tidak memiliki daftar posisi spesifik, kami selalu terbuka untuk berdiskusi dengan orang-orang hebat.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <!-- Trigger Modal CV -->
                        <button onclick="document.getElementById('modal-cv').classList.remove('hidden')"
                            class="px-10 py-4 bg-[#056526] text-white font-black rounded-2xl hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-900/20 text-sm uppercase tracking-widest">
                            <i class="fa-brands fa-whatsapp mr-2"></i> Kirim CV &amp; Portofolio
                        </button>
                        <!-- Trigger Modal Budaya -->
                        <button onclick="document.getElementById('modal-budaya').classList.remove('hidden')"
                            class="px-10 py-4 bg-gray-50 text-gray-900 font-black rounded-2xl border border-gray-200 hover:bg-gray-100 transition-all text-sm uppercase tracking-widest">
                            <i class="fa-brands fa-whatsapp mr-2 text-emerald-600"></i> Tanya Budaya Kerja
                        </button>
                    </div>
                    
                    <p class="mt-12 text-sm text-gray-400 font-medium">
                        Atau ikuti kami di media sosial untuk berita terbaru tentang perkembangan tim kami.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL: Kirim CV ===================== -->
<div id="modal-cv" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('modal-cv').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-[3rem] p-10 max-w-lg w-full shadow-2xl z-10">
        <button onclick="document.getElementById('modal-cv').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-[1.5rem] flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-file-arrow-up"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900">Kirim CV &amp; Portofolio</h3>
            <p class="text-gray-400 text-sm mt-2">Isi form ini, lalu Anda akan diarahkan ke WhatsApp kami.</p>
        </div>
        <form action="<?= url('/career/send-cv') ?>" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(session_id()) ?>">
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Nama Anda" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 transition-all">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Posisi yang Diminati *</label>
                <input type="text" name="position" required placeholder="Contoh: Backend Developer" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 transition-all">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link CV / Portfolio *</label>
                <input type="url" name="cv_link" required placeholder="https://drive.google.com/..." class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 transition-all">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Perkenalan Singkat *</label>
                <textarea name="intro" required rows="3" placeholder="Ceritakan pengalaman dan keahlian Anda..." class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 transition-all resize-none"></textarea>
            </div>
            <button type="submit" class="w-full py-4 bg-[#056526] text-white font-black rounded-2xl hover:bg-emerald-800 transition-all shadow-lg shadow-green-900/20 text-sm uppercase tracking-widest">
                <i class="fa-brands fa-whatsapp mr-2"></i> Kirim via WhatsApp
            </button>
        </form>
    </div>
</div>

<!-- ===================== MODAL: Tanya Budaya Kerja ===================== -->
<div id="modal-budaya" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('modal-budaya').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-[3rem] p-10 max-w-lg w-full shadow-2xl z-10">
        <button onclick="document.getElementById('modal-budaya').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-[1.5rem] flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900">Tanya Budaya Kerja</h3>
            <p class="text-gray-400 text-sm mt-2">Tanyakan apa saja tentang budaya kerja kami.</p>
        </div>
        <form action="<?= url('/career/ask-culture') ?>" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(session_id()) ?>">
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Anda *</label>
                <input type="text" name="name" required placeholder="Nama Anda" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 transition-all">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pertanyaan Anda *</label>
                <textarea name="question" required rows="4" placeholder="Contoh: Bagaimana ritme kerja sehari-hari di LitleMart?" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#056526]/20 transition-all resize-none"></textarea>
            </div>
            <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-800 transition-all shadow-lg shadow-green-900/20 text-sm uppercase tracking-widest">
                <i class="fa-brands fa-whatsapp mr-2"></i> Tanya via WhatsApp
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-[#056526] via-[#067a2e] to-[#044a1d] py-32 px-4 relative overflow-hidden text-center">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 text-9xl text-white">
                <i class="fa-solid fa-rocket"></i>
            </div>
            <div class="absolute bottom-10 left-10 text-8xl text-white">
                <i class="fa-solid fa-bullseye"></i>
            </div>
        </div>
        <div class="max-w-4xl mx-auto relative z-10">
            <span class="inline-block px-4 py-1.5 bg-white/15 backdrop-blur text-white text-xs font-bold rounded-full uppercase tracking-widest mb-6 border border-white/20">Mari Tumbuh Bersama</span>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
                Bangun Masa Depan<br><span class="text-emerald-300">E-Commerce</span>
            </h1>
            <p class="text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Bergabunglah dengan tim inovatif kami di LitleMart dan bantu kami mendisrupsi cara orang berbelanja dan berjualan.
            </p>
        </div>
    </div>

    <!-- Values -->
    <div class="max-w-7xl mx-auto px-4 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-black text-gray-900 mb-4">Kenapa Bergabung dengan Kami?</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Kami menghargai kreativitas, kolaborasi, dan kemandirian dalam bekerja.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <?php
            $values = [
                ['icon' => 'fa-solid fa-lightbulb', 'title' => 'Inovasi Tanpa Batas', 'desc' => 'Kami selalu mencari cara baru untuk menyelesaikan masalah kompleks di dunia digital.'],
                ['icon' => 'fa-solid fa-users-gear', 'title' => 'Budaya Kolaboratif', 'desc' => 'Bekerja dengan tim yang suportif dan saling membantu untuk mencapai tujuan bersama.'],
                ['icon' => 'fa-solid fa-scale-balanced', 'title' => 'Keseimbangan Hidup', 'desc' => 'Kami mengutamakan kesejahteraan tim dengan jam kerja yang fleksibel dan lingkungan sehat.'],
            ];
            foreach ($values as $v): ?>
            <div class="p-10 bg-gray-50 rounded-[3rem] border border-gray-100 hover:shadow-xl transition-all text-center">
                <div class="text-5xl mb-6 text-emerald-600">
                    <i class="<?= $v['icon'] ?>"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-4"><?= $v['title'] ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?= $v['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Talent Pool / Join Us -->
    <div class="bg-gray-50 py-32 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-12 md:p-16 rounded-[4rem] border border-gray-100 shadow-xl text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-[2rem] flex items-center justify-center text-3xl mx-auto mb-8 shadow-inner">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <h2 class="text-4xl font-black text-gray-900 mb-6">Bergabung dengan Talent Pool Kami</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-10">
                        Kami selalu mencari individu berbakat yang memiliki semangat untuk mengubah masa depan perdagangan. 
                        Walaupun saat ini kami tidak memiliki daftar posisi spesifik, kami selalu terbuka untuk berdiskusi dengan orang-orang hebat.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="mailto:career@litlemart.com" class="px-10 py-4 bg-[#056526] text-white font-black rounded-2xl hover:bg-emerald-800 transition-all shadow-lg shadow-emerald-900/20 text-sm uppercase tracking-widest">
                            Kirim CV & Portofolio
                        </a>
                        <a href="<?= url('/contact') ?>" class="px-10 py-4 bg-gray-50 text-gray-900 font-black rounded-2xl border border-gray-200 hover:bg-gray-100 transition-all text-sm uppercase tracking-widest">
                            Tanya Budaya Kerja
                        </a>
                    </div>
                    
                    <p class="mt-12 text-sm text-gray-400 font-medium">
                        Atau ikuti kami di LinkedIn untuk berita terbaru tentang perkembangan tim kami.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

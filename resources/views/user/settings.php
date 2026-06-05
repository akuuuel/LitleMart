<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-[#F4F9F4] min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Pengaturan Profil</h1>
            <p class="text-gray-500 font-medium italic">Kelola informasi publik dan tampilan profil Anda.</p>
        </div>

        <form action="<?= url('/settings') ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <!-- Appearance Section -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-green-900/5 border border-gray-100">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tampilan Profil
                </h3>
                
                <div class="space-y-10">
                    <!-- Banner Upload -->
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Foto Sampul (Banner)</label>
                        <div class="relative h-40 w-full bg-emerald-50 rounded-3xl overflow-hidden border-2 border-dashed border-emerald-100 group">
                            <img id="banner_preview" src="<?= $user['banner'] ? url($user['banner']) : '' ?>" class="w-full h-full object-cover <?= !$user['banner'] ? 'hidden' : '' ?>">
                            <div id="banner_placeholder" class="absolute inset-0 flex items-center justify-center <?= $user['banner'] ? 'hidden' : '' ?>">
                                <span class="text-emerald-300 font-bold">Belum ada sampul</span>
                            </div>
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                <span class="bg-white/90 text-primary px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg cursor-pointer">Ganti Sampul</span>
                            </div>
                            <input type="file" name="banner" id="banner_input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                            <input type="hidden" name="current_banner" value="<?= $user['banner'] ?>">
                        </div>
                        <p class="mt-2 text-[10px] text-gray-400 font-medium italic">Rekomendasi ukuran: 1200x400px</p>
                    </div>

                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-8">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-[2rem] bg-emerald-100 overflow-hidden shadow-lg border-4 border-white flex items-center justify-center">
                                <img id="avatar_preview" src="<?= $user['avatar'] ? url($user['avatar']) : '' ?>" class="w-full h-full object-cover <?= !$user['avatar'] ? 'hidden' : '' ?>">
                                <div id="avatar_placeholder" class="w-full h-full flex items-center justify-center text-3xl font-black text-primary <?= $user['avatar'] ? 'hidden' : '' ?>">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-black/20 rounded-[2rem] opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center cursor-pointer">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <input type="file" name="avatar" id="avatar_input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                            <input type="hidden" name="current_avatar" value="<?= $user['avatar'] ?>">
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-gray-900 mb-1">Foto Profil</h4>
                            <p class="text-xs text-gray-500 font-medium">Gunakan foto asli agar penjual dan pembeli lain mengenali Anda.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-green-900/5 border border-gray-100">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-gray-900 font-bold focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="e.g. 0812..." class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-gray-900 font-bold focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Email (Tidak Dapat Diubah)</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled class="w-full px-6 py-4 bg-gray-100 border-none rounded-2xl text-gray-400 font-bold cursor-not-allowed">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Bio / Deskripsi Singkat</label>
                        <textarea name="bio" rows="4" placeholder="Ceritakan tentang diri Anda..." class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-gray-900 font-bold focus:ring-2 focus:ring-primary/20 transition-all"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-4">
                <a href="<?= url('/user/' . $_SESSION['user_id']) ?>" class="px-8 py-4 text-sm font-black text-gray-400 hover:text-gray-600 transition-all uppercase tracking-widest">Batal</a>
                <button type="submit" class="px-10 py-4 bg-primary text-white text-sm font-black rounded-3xl hover:bg-emerald-800 transition-all shadow-xl shadow-green-900/10 uppercase tracking-[0.2em]">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function setupPreview(inputId, previewId, placeholderId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);

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

setupPreview('avatar_input', 'avatar_preview', 'avatar_placeholder');
setupPreview('banner_input', 'banner_preview', 'banner_placeholder');
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

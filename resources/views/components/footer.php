<!-- Global Footer: Only shown on Mobile if it's the Home Page -->
<footer class="bg-white border-t border-slate-200 py-8 md:py-12 mt-10 md:mt-20 <?= (!($isHomePage ?? false)) ? 'hidden md:block' : '' ?>">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <div class="col-span-2 md:col-span-2">
                <a href="<?= url('/') ?>" class="flex items-center gap-2 mb-3 md:mb-4">
                    <div class="w-7 h-7 md:w-8 md:h-8 bg-primary rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xs md:text-base">L</span>
                    </div>
                    <span class="text-lg md:text-xl font-bold text-slate-900 tracking-tight">LitleMart</span>
                </a>
                <p class="text-slate-500 text-xs md:text-sm max-w-xs leading-relaxed">
                    Platform marketplace multi-vendor yang dibangun dengan PHP Native dan teknologi UI modern.
                </p>
            </div>
            <div>
                <h3 class="text-[10px] md:text-sm font-bold text-slate-900 uppercase tracking-wider mb-2 md:mb-4">Perusahaan</h3>
                <ul class="space-y-1 md:space-y-2">
                    <li><a href="<?= url('/about') ?>" class="text-[11px] md:text-sm text-slate-500 hover:text-primary transition-colors">Tentang Kami</a></li>
                    <li><a href="<?= url('/career') ?>" class="text-[11px] md:text-sm text-slate-500 hover:text-primary transition-colors">Karir</a></li>
                    <li><a href="<?= url('/privacy') ?>" class="text-[11px] md:text-sm text-slate-500 hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-[10px] md:text-sm font-bold text-slate-900 uppercase tracking-wider mb-2 md:mb-4">Dukungan</h3>
                <ul class="space-y-1 md:space-y-2">
                    <li><a href="<?= url('/help') ?>" class="text-[11px] md:text-sm text-slate-500 hover:text-primary transition-colors">Pusat Bantuan</a></li>
                    <li><a href="<?= url('/terms') ?>" class="text-[11px] md:text-sm text-slate-500 hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="<?= url('/contact') ?>" class="text-[11px] md:text-sm text-slate-500 hover:text-primary transition-colors">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-100 mt-8 md:mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-slate-400">&copy; <?= date('Y') ?> LitleMart. Semua hak dilindungi undang-undang.</p>
            <div class="flex gap-4">
                <!-- Social Links -->
            </div>
        </div>
    </div>
</footer>

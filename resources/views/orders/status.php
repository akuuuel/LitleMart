<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="w-full bg-white md:bg-gray-50 min-h-screen flex items-center justify-center p-4 md:p-6 relative overflow-hidden">
    <!-- Abstract Background Decorations (Desktop Only) -->
    <div class="hidden md:block absolute top-0 left-0 w-96 h-96 bg-emerald-100/50 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2"></div>
    <div class="hidden md:block absolute bottom-0 right-0 w-96 h-96 bg-blue-100/30 rounded-full blur-[120px] translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-md w-full bg-white md:rounded-[3rem] p-4 md:p-12 text-center md:shadow-[0_32px_64px_-12px_rgba(0,0,0,0.1)] relative z-10">
        <!-- Status Icon (Shaper & Faster on mobile) -->
        <div class="relative w-20 h-20 md:w-28 md:h-28 mx-auto mb-6 md:mb-10 group">
            <div class="<?= $status === 'success' ? 'bg-emerald-500' : 'bg-amber-500' ?> absolute inset-0 rounded-3xl md:rounded-[2.5rem] rotate-6 group-hover:rotate-0 transition-transform duration-500 opacity-20"></div>
            <div class="<?= $status === 'success' ? 'bg-emerald-600' : 'bg-amber-600' ?> relative w-20 h-20 md:w-28 md:h-28 rounded-3xl md:rounded-[2.5rem] flex items-center justify-center text-white shadow-2xl transition-transform hover:-translate-y-1">
                <?php if($status === 'success'): ?>
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                <?php else: ?>
                    <svg class="w-8 h-8 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php endif; ?>
            </div>
        </div>
        
        <h1 class="text-2xl md:text-3xl font-black text-gray-900 mb-2 md:mb-3 tracking-tighter italic uppercase"><?= $title ?></h1>
        <p class="text-gray-500 mb-6 md:mb-10 font-medium text-sm md:text-lg leading-relaxed px-4 md:px-0"><?= $message ?></p>
        
        <!-- Transaction Detail (Compact) -->
        <?php if($orderId): ?>
            <div class="bg-gray-50 border border-gray-100 rounded-2xl md:rounded-[1.5rem] p-3 md:p-6 mb-6 md:mb-10 flex flex-col items-center">
                <span class="text-[8px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 md:mb-2 text-center w-full">ID Transaksi</span>
                <span class="text-gray-900 font-black text-[10px] md:text-sm tracking-tight bg-white px-3 py-1.5 md:px-4 md:py-2 rounded-lg border border-gray-100 shadow-sm">
                    #<?= $orderId ?>
                </span>
            </div>
        <?php endif; ?>
        
        <!-- Action Buttons (Closer together) -->
        <div class="space-y-3 md:space-y-4">
            <a href="<?= url('/orders') ?>" class="block w-full py-4 md:py-5 bg-gray-900 text-white font-black rounded-xl md:rounded-2xl hover:bg-emerald-600 transition-all shadow-xl active:scale-95 text-xs md:text-base">
                Cek Status Pesanan
            </a>
            <a href="<?= url('/') ?>" class="block w-full py-4 md:py-5 bg-white border-2 border-gray-100 text-gray-700 font-black rounded-xl md:rounded-2xl hover:border-emerald-500 hover:text-emerald-600 transition-all group flex items-center justify-center gap-2 text-xs md:text-base">
                <i class="fa-solid fa-house-chimney text-[10px] md:text-sm group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Beranda
            </a>
        </div>
        
        <div class="mt-8 md:mt-12">
            <div class="flex items-center justify-center gap-2 mb-1">
                <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                <p class="text-[8px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Transaksi Selesai</p>
            </div>
            <p class="text-[7px] md:text-[8px] font-bold text-gray-300 uppercase tracking-widest">Terima kasih telah mempercayai LitleMart</p>
        </div>
    </div>
</main>

<style>
    /* Ensure no scrolling on mobile status page */
    @media (max-width: 767px) {
        body { overflow: hidden; height: 100vh; position: fixed; width: 100%; }
        main { height: 100vh !important; py: 0 !important; }
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

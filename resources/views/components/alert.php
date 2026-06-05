<!-- Premium Global Alert System -->
<?php if (\App\Core\Session::hasFlash('success') || \App\Core\Session::hasFlash('error')): ?>
<div x-data="{ show: true }" 
     x-init="setTimeout(() => show = false, 5000)" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
     x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
     x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
     class="fixed bottom-10 right-4 md:right-10 z-[9999] max-w-sm w-full font-sans">
    
    <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
    <div class="bg-gray-900 border border-white/10 rounded-[2rem] p-5 shadow-2xl backdrop-blur-xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-500/10 rounded-full blur-2xl group-hover:bg-green-500/20 transition-all"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-green-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-green-500/20">✓</div>
            <div>
                <h4 class="text-white font-black text-sm uppercase tracking-widest">Success</h4>
                <p class="text-gray-400 text-xs font-bold mt-0.5"><?= $msg ?></p>
            </div>
            <button @click="show = false" class="ml-auto text-gray-500 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
    <div class="bg-red-950 border border-red-500/20 rounded-[2rem] p-5 shadow-2xl backdrop-blur-xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-red-500/10 rounded-full blur-2xl group-hover:bg-red-500/20 transition-all"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-red-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-red-500/20">!</div>
            <div>
                <h4 class="text-white font-black text-sm uppercase tracking-widest leading-none mb-1">Attention</h4>
                <p class="text-red-200 text-xs font-bold opacity-80"><?= $msg ?></p>
            </div>
            <button @click="show = false" class="ml-auto text-red-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

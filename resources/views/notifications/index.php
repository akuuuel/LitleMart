<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="container mx-auto px-4 py-6 md:py-12 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 md:mb-10">
            <div>
                <h1 class="text-2xl md:text-4xl font-black text-gray-900 tracking-tight mb-1 md:mb-2">Notifikasi</h1>
                <p class="text-[10px] md:text-base text-gray-500 font-medium italic">Pantau pembaruan pesanan dan aktivitas Anda</p>
            </div>
            <?php if (!empty($notifications)): ?>
            <a href="<?= url('/notifications/mark-all-read') ?>" class="text-[10px] md:text-sm font-bold text-emerald-600 hover:text-emerald-700 px-3 py-2 md:px-4 md:py-2 bg-emerald-50 rounded-xl transition-all">
                Tandai semua dibaca
            </a>
            <?php endif; ?>
        </div>

        <!-- List Container -->
        <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] border border-gray-100 shadow-xl shadow-green-900/5 overflow-hidden">
            <?php if (empty($notifications)): ?>
                <div class="p-12 md:p-24 text-center">
                    <div class="w-16 h-16 md:w-24 md:h-24 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6 transition-transform hover:scale-110 duration-500">
                        <i class="fa-solid fa-bell-slash text-2xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-lg md:text-2xl font-bold text-gray-900 mb-2">Semua Sudah Dibaca!</h3>
                    <p class="text-[11px] md:text-gray-400 font-medium max-w-xs mx-auto italic">Belum ada pemberitahuan baru untuk Anda saat ini.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-50">
                    <?php foreach ($notifications as $n): ?>
                        <div onclick="this.querySelector('form').submit()" 
                             class="p-3 md:p-8 flex items-center gap-3 md:gap-6 lg:hover:bg-emerald-50 transition-all group relative cursor-pointer <?= !$n['is_read'] ? 'bg-emerald-50/20' : 'opacity-70 lg:hover:opacity-100' ?>">
                            
                            <?php if (!$n['is_read']): ?>
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 rounded-r-full shadow-[2px_0_10px_rgba(16,185,129,0.3)]"></div>
                            <?php endif; ?>

                            <!-- Icon (Smaller on Mobile) -->
                            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center justify-center flex-shrink-0 md:group-hover:scale-110 transition-transform duration-500 <?= !$n['is_read'] ? 'ring-2 ring-emerald-500/10' : '' ?>">
                                <?php
                                $typeMap = [
                                    'success' => ['color' => 'text-emerald-500', 'icon' => 'fa-circle-check'],
                                    'error'   => ['color' => 'text-red-500',     'icon' => 'fa-circle-exclamation'],
                                    'warning' => ['color' => 'text-amber-500',   'icon' => 'fa-triangle-exclamation'],
                                    'info'    => ['color' => 'text-blue-500',    'icon' => 'fa-circle-info']
                                ];
                                $t = $typeMap[$n['type']] ?? $typeMap['info'];
                                ?>
                                <i class="fa-solid <?= $t['icon'] ?> text-base md:text-xl <?= $t['color'] ?>"></i>
                            </div>

                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-0.5 md:mb-1">
                                    <h4 class="text-xs md:text-lg font-black text-gray-900 tracking-tight truncate"><?= htmlspecialchars($n['title']) ?></h4>
                                    <span class="text-[7px] md:text-[10px] font-black text-gray-400 whitespace-nowrap uppercase tracking-widest bg-gray-50 px-1.5 py-0.5 rounded">
                                        <?= date('H:i', strtotime($n['created_at'])) ?>
                                    </span>
                                </div>
                                <p class="text-[10px] md:text-base text-gray-500 md:text-gray-600 font-medium leading-normal truncate-2-lines"><?= htmlspecialchars($n['message']) ?></p>
                                
                                <form action="<?= url('/notifications/mark-read') ?>" method="POST" class="hidden">
                                    <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                    <input type="hidden" name="target_url" value="<?= $n['target_url'] ?>">
                                </form>
                            </div>
                            
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <?php if (!$n['is_read']): ?>
                                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                                <?php endif; ?>
                                <i class="fa-solid fa-chevron-right text-gray-200 text-[10px] md:text-sm"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
    .truncate-2-lines {
        display: -webkit-box;
        -webkit-line-clamp: 1; /* Default to 1 on small screens for horizontal feel */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    @media (min-width: 768px) {
        .truncate-2-lines { -webkit-line-clamp: 2; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userId = '<?= \App\Core\Session::get('user_id') ?>';
        if (typeof database !== 'undefined' && userId) {
            const listRef = database.ref('notifications/' + userId);
            const container = document.querySelector('.divide-y');

            listRef.on('child_added', (snapshot) => {
                const n = snapshot.val();
                if (n.senderId !== 'system') return; 
                if (!container || document.querySelector(`[data-fb-id="${snapshot.key}"]`)) return;

                const cardHtml = `
                    <div onclick="this.querySelector('form').submit()" data-fb-id="${snapshot.key}"
                         class="p-3 md:p-8 flex items-center gap-3 md:gap-6 lg:hover:bg-emerald-50 transition-all group relative cursor-pointer bg-emerald-50/20">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 rounded-r-full shadow-[2px_0_10px_rgba(16,185,129,0.3)]"></div>
                        <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl bg-white border border-gray-100 shadow-sm flex items-center justify-center flex-shrink-0 ring-2 ring-emerald-500/10">
                            <i class="fa-solid fa-circle-info text-base text-emerald-500"></i>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-0.5">
                                <h4 class="text-xs md:text-lg font-black text-gray-900 tracking-tight truncate">${n.title}</h4>
                                <span class="text-[7px] font-black text-gray-400 whitespace-nowrap uppercase bg-gray-50 px-1.5 rounded">Baru</span>
                            </div>
                            <p class="text-[10px] text-gray-500 font-medium leading-normal truncate-2-lines">${n.message}</p>
                            <form action="<?= url('/notifications/mark-read') ?>" method="POST" class="hidden">
                                <input type="hidden" name="id" value="${n.id}">
                                <input type="hidden" name="target_url" value="${n.targetUrl || ''}">
                            </form>
                        </div>
                        <div class="flex-shrink-0 flex items-center gap-2">
                             <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                             <i class="fa-solid fa-chevron-right text-gray-200 text-[10px]"></i>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('afterbegin', cardHtml);
                const empty = document.querySelector('.p-12');
                if (empty) empty.remove();
            });
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

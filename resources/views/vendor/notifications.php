<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">Notifikasi</h2>
                        <p class="text-sm text-gray-500 mt-1">Tetap terinformasi dengan aktivitas toko Anda.</p>
                    </div>
                    <?php if (!empty($notifications)): ?>
                    <a href="<?= url('/vendor/notifications/mark-all-read') ?>" class="text-sm font-bold text-[#056526] hover:bg-green-50 px-4 py-2 rounded-xl transition-all bg-white border border-gray-100 shadow-sm">
                        Tandai semua dibaca
                    </a>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-green-900/5 overflow-hidden">
                    <?php if (empty($notifications)): ?>
                        <div class="p-24 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Belum ada notifikasi</h3>
                            <p class="text-sm text-gray-400 font-medium">Kami akan memberi tahu Anda saat ada sesuatu yang penting terjadi.</p>
                        </div>
                    <?php else: ?>
                        <div class="divide-y divide-gray-50">
                            <?php foreach ($notifications as $n): ?>
                            <div onclick="this.querySelector('form').submit()" 
                                 class="flex items-start gap-6 p-4 md:p-8 lg:hover:bg-green-50 transition-all group relative cursor-pointer <?= !$n['is_read'] ? 'bg-green-50/20' : 'opacity-80 lg:hover:opacity-100' ?>">
                                
                                <?php if (!$n['is_read']): ?>
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full shadow-[2px_0_10px_rgba(34,197,94,0.3)]"></div>
                                <?php endif; ?>

                                <div class="w-12 h-12 bg-white border border-gray-100 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm md:group-hover:scale-110 transition-transform duration-500 <?= !$n['is_read'] ? 'ring-2 ring-green-500/10' : '' ?>">
                                    <?php 
                                    $icon = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                    $color = 'text-blue-500';
                                    if(strpos($n['type'], 'order') !== false || $n['type'] === 'success') { $icon = 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'; $color = 'text-green-500'; }
                                    if(strpos($n['type'], 'security') !== false || $n['type'] === 'error') { $icon = 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'; $color = 'text-red-500'; }
                                    ?>
                                    <svg class="w-6 h-6 <?= $color ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <h4 class="text-base font-black text-gray-900 tracking-tight truncate md:group-hover:text-[#056526] transition-colors"><?= htmlspecialchars($n['title']) ?></h4>
                                        <span class="text-[10px] font-bold text-gray-400 whitespace-nowrap bg-gray-50 px-2 py-1 rounded-lg border border-gray-100"><?= date('H:i', strtotime($n['created_at'])) ?></span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500 leading-relaxed"><?= htmlspecialchars($n['message']) ?></p>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-300"><?= date('d M Y', strtotime($n['created_at'])) ?></span>
                                            <?php if(!$n['is_read']): ?>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-green-500 shadow-sm shadow-green-200 animate-pulse"></span>
                                                    <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Baru</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <form action="<?= url('/vendor/notifications/mark-read') ?>" method="POST" class="hidden">
                                            <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                        </form>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 self-center hidden md:block opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-chevron-right text-green-200 text-sm"></i>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userId = '<?= \App\Core\Session::get('user_id') ?>';
        if (typeof database !== 'undefined' && userId) {
            const listRef = database.ref('notifications/' + userId);
            const container = document.querySelector('.divide-y.divide-gray-50');
            const emptyState = document.querySelector('.p-24.text-center');

            listRef.on('child_added', (snapshot) => {
                const n = snapshot.val();
                if (n.senderId !== 'system') return;

                if (emptyState) emptyState.remove();
                if (!container) return;

                if (document.querySelector(`[data-fb-id="${snapshot.key}"]`)) return;

                const cardHtml = `
                    <div onclick="this.querySelector('form').submit()" data-fb-id="${snapshot.key}"
                         class="flex items-start gap-6 p-4 md:p-8 lg:hover:bg-green-50 transition-all group relative cursor-pointer bg-green-50/20">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full shadow-[2px_0_10px_rgba(34,197,94,0.3)]"></div>
                        <div class="w-12 h-12 bg-white border border-gray-100 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm md:group-hover:scale-110 transition-transform duration-500 ring-2 ring-green-500/10">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <h4 class="text-base font-black text-gray-900 tracking-tight truncate md:group-hover:text-[#056526] transition-colors">${n.title}</h4>
                                <span class="text-[10px] font-bold text-gray-400 whitespace-nowrap bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">Baru</span>
                            </div>
                            <p class="text-sm font-medium text-gray-500 leading-relaxed">${n.message}</p>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">Baru Saja</span>
                                </div>
                                <form action="<?= url('/vendor/notifications/mark-read') ?>" method="POST" class="hidden">
                                    <input type="hidden" name="id" value="${n.id}">
                                </form>
                            </div>
                        </div>
                        <div class="flex-shrink-0 self-center hidden md:block opacity-0 lg:group-hover:opacity-100 transition-opacity">
                            <i class="fa-solid fa-chevron-right text-green-200 text-sm"></i>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('afterbegin', cardHtml);
            });
        }
    });
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

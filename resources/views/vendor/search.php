<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-gray-900">Search Results</h2>
                    <p class="text-sm text-gray-500">Showing results for "<span class="font-bold text-primary"><?= htmlspecialchars($query) ?></span>"</p>
                </div>

                <?php if (empty($results)): ?>
                    <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">No results found</h3>
                        <p class="text-sm text-gray-500">Try searching for different keywords or check for typos.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($results as $item): ?>
                            <?php
                                $link = url('/vendor/products');
                                $colorClass = 'bg-green-50 text-green-600';
                                if ($item['type'] === 'order') {
                                    $link = url('/vendor/orders');
                                    $colorClass = 'bg-blue-50 text-blue-600';
                                } elseif ($item['type'] === 'customer') {
                                    $link = url('/vendor/customers');
                                    $colorClass = 'bg-purple-50 text-purple-600';
                                }
                            ?>
                            <a href="<?= $link ?>" class="block bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 <?= $colorClass ?> rounded-xl flex items-center justify-center font-bold uppercase text-[10px]">
                                            <?= $item['type'] ?>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 group-hover:text-primary transition-colors"><?= htmlspecialchars($item['title']) ?></h4>
                                            <p class="text-[10px] text-gray-400 mt-0.5">Found in <?= $item['type'] ?>s &bull; <?= date('d M Y', strtotime($item['created_at'])) ?></p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-primary transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

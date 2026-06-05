<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex min-h-screen bg-[#F4F9F4] font-sans">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <?php $pageTitle = $title ?? 'Coming Soon'; include __DIR__ . '/partials/topbar.php'; ?>

        <main class="flex-1 p-8 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-green-50 rounded-3xl flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-[#056526]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2"><?= $title ?? 'Coming Soon' ?></h2>
            <p class="text-gray-500 max-w-sm">We are currently building this feature to help you manage your store better. Stay tuned!</p>
            <a href="/vendor/dashboard" class="mt-8 px-6 py-2.5 bg-[#056526] text-white font-bold rounded-xl hover:bg-green-800 transition-colors">
                Back to Dashboard
            </a>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

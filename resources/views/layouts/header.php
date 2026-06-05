<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LitleMart' ?> - Multi Vendor Marketplace</title>
    <!-- PWA Settings -->
    <link rel="manifest" href="<?= url('/manifest.json') ?>">
    <meta name="theme-color" content="#056526">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="LitleMart">
    <link rel="apple-touch-icon" href="<?= url('/img/apple-touch-icon.png') ?>">
    <!-- Splash screen for iOS (Apple placeholder) -->
    <link rel="apple-touch-startup-image" href="<?= url('/img/apple-touch-icon.png') ?>">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>')
                    .then(reg => console.log('LitleMart PWA Ready!'))
                    .catch(err => console.log('PWA failed: ', err));
            });
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: {
                            DEFAULT: '#056526', // The dark green from the design
                            light: '#EBF3ED', // The light background
                            50: '#F4F9F4'
                        },
                        background: '#F4F9F4'
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    borderRadius: { 'xl': '12px', '2xl': '16px', '3xl': '24px' }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $_ENV['MIDTRANS_CLIENT_KEY'] ?? '' ?>"></script>
    <style>
        [x-cloak] { display: none !important; }
        html, body { overflow-x: hidden; width: 100%; position: relative; }
        body { font-family: 'Inter', sans-serif; background-color: #F4F9F4; color: #1E293B; margin: 0; padding: 0; }
        
        /* Fix for browser autofill colors */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        textarea:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #1E293B !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Global input clarity fix */
        input, textarea, select {
            color: #1E293B !important;
        }

        /* Loading Skeleton Styles */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        #page-skeleton {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #F4F9F4;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            padding: 20px;
            gap: 15px;
            transition: opacity 0.15s ease, visibility 0.15s ease;
        }
        .skeleton-fade-out { opacity: 0; visibility: hidden; }
    </style>
    <script>
        // Hide skeleton on load
        window.addEventListener('load', () => {
            const skel = document.getElementById('page-skeleton');
            if (skel) {
                skel.classList.add('skeleton-fade-out');
                setTimeout(() => skel.remove(), 150);
            }
        });

        // Show skeleton on navigation
        window.addEventListener('beforeunload', () => {
            const skel = document.createElement('div');
            skel.id = 'page-skeleton-nav';
            skel.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:#F4F9F4;z-index:9999;opacity:0;transition:opacity 0.2s;';
            document.body.appendChild(skel);
            setTimeout(() => skel.style.opacity = '1', 10);
        });

        // Global Pull to Refresh Logic
        document.addEventListener('alpine:init', () => {
            Alpine.store('global', {
                pulling: false,
                refreshing: false,
                pullDistance: 0,
                startY: 0,
                handleStart(e) {
                    // Disable pull-to-refresh if touching inside chat message area
                    if (e.target.closest('#messages-container')) return;
                    
                    if (window.scrollY === 0) this.startY = e.touches[0].pageY;
                },
                handleMove(e) {
                    if (this.startY === 0) return; // Prevent logic if not started
                    if (window.scrollY === 0 && e.touches[0].pageY > this.startY) {
                        const dist = e.touches[0].pageY - this.startY;
                        if (dist > 0) {
                            this.pullDistance = Math.min(dist / 2.5, 80);
                            this.pulling = true;
                        }
                    }
                },
                handleEnd() {
                    if (this.pullDistance > 60) {
                        this.refreshing = true;
                        window.location.reload();
                    } else {
                        this.pulling = false;
                        this.pullDistance = 0;
                        this.startY = 0;
                    }
                }
            });
        });
    </script>
    <?php include __DIR__ . '/realtime_script.php'; ?>
</head>
<body class="antialiased pt-14 md:pt-16" 
      x-data 
      @touchstart="$store.global.handleStart($event)"
      @touchmove="$store.global.handleMove($event)"
      @touchend="$store.global.handleEnd()">

    <!-- Global Pull to Refresh Indicator -->
    <div x-show="$store.global.pulling || $store.global.refreshing" 
         class="fixed top-0 left-0 right-0 z-[10000] flex justify-center pointer-events-none transition-all duration-200"
         :style="`transform: translateY(${$store.global.pullDistance}px); opacity: ${$store.global.pullDistance / 60}`">
        <div class="bg-white rounded-full p-2 shadow-xl border border-gray-100">
            <svg class="w-6 h-6 text-[#056526] animate-spin" :class="$store.global.refreshing ? '' : 'animate-none'" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </div>
    </div>
    <!-- Initial Page Skeleton Loader -->
    <div id="page-skeleton">
        <div class="h-10 w-48 skeleton rounded-lg mb-8"></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="h-40 skeleton rounded-2xl"></div>
            <div class="h-40 skeleton rounded-2xl"></div>
            <div class="h-40 skeleton rounded-2xl"></div>
        </div>
        <div class="h-8 w-full skeleton rounded-lg mt-10"></div>
        <div class="h-8 w-3/4 skeleton rounded-lg"></div>
        <div class="h-8 w-1/2 skeleton rounded-lg"></div>
        <div class="flex-1"></div>
        <div class="h-16 w-full skeleton rounded-t-3xl"></div>
    </div>
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <?php require __DIR__ . '/../components/alert.php'; ?>

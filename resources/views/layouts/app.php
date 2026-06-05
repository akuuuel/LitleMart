<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LitleMart Admin - Enterprise Control' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Force reload on backward/forward navigation to prevent caching sensitive pages
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-gradient { background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.6); }
        .nav-link-active { background: rgba(16, 185, 129, 0.1); color: #10B981; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Fix for browser autofill colors */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #0F172A !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Global input clarity fix */
        input, textarea, select {
            color: #0F172A !important;
        }
    </style>
</head>
<body class="antialiased overflow-hidden">
    <div class="flex h-screen bg-[#F8FAFC]" x-data="{ sidebarOpen: window.innerWidth > 1024 }">
        
        <!-- Sidebar Backdrop (mobile) -->
        <div x-show="sidebarOpen && window.innerWidth < 1024" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[45]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar -->
        <aside 
            :class="{
                'w-[280px] translate-x-0': sidebarOpen,
                '-translate-x-full lg:w-20 lg:translate-x-0 lg:w-20': !sidebarOpen
            }" 
            class="sidebar-gradient h-full flex flex-col transition-all duration-300 ease-in-out fixed top-0 left-0 lg:relative z-50 shadow-2xl overflow-hidden"
        >
            <!-- Logo Section -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-white/5 flex-shrink-0">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="ml-3 font-bold text-xl tracking-tight text-white whitespace-nowrap overflow-hidden">
                        Litle<span class="text-emerald-400">Mart</span>
                    </div>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button @click="sidebarOpen = false" class="lg:hidden w-8 h-8 flex items-center justify-center bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation -->
            <div class="flex-1 px-4 py-8 space-y-2 overflow-y-auto overflow-x-hidden">
                <div x-show="sidebarOpen" class="px-4 mb-3 text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 opacity-60">System Core</div>
                
                <a href="<?= url('/admin/dashboard') ?>" class="flex items-center group px-4 py-3 rounded-xl transition-all <?= $_SERVER['REQUEST_URI'] === url('/admin/dashboard') ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="delay-100 transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-1" x-transition:enter-end="opacity-100 translate-x-0" class="ml-3 font-semibold text-sm">Overview</span>
                </a>

                <a href="<?= url('/admin/vendors') ?>" class="flex items-center group px-4 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'admin/vendors') !== false ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?> rounded-xl transition-all">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="delay-100 transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-1" x-transition:enter-end="opacity-100 translate-x-0" class="ml-3 font-semibold text-sm">Vendors</span>
                </a>

                <a href="<?= url('/admin/users') ?>" class="flex items-center group px-4 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'admin/users') !== false ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?> rounded-xl transition-all">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="delay-100 transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-1" x-transition:enter-end="opacity-100 translate-x-0" class="ml-3 font-semibold text-sm">Users</span>
                </a>

                <div x-show="sidebarOpen" class="px-4 mt-8 mb-3 text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 opacity-60">Operations</div>

                <a href="<?= url('/admin/analytics') ?>" class="flex items-center group px-4 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'admin/analytics') !== false ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?> rounded-xl transition-all">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="delay-100 transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-1" x-transition:enter-end="opacity-100 translate-x-0" class="ml-3 font-semibold text-sm">Analytics</span>
                </a>

                <a href="<?= url('/admin/orders') ?>" class="flex items-center group px-4 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'admin/orders') !== false ? 'nav-link-active' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?> rounded-xl transition-all">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="delay-100 transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-1" x-transition:enter-end="opacity-100 translate-x-0" class="ml-3 font-semibold text-sm">Orders</span>
                </a>
            </div>

            <!-- Profile Info -->
            <div class="p-4 bg-slate-900/50 border-t border-slate-800/50">
                <div class="flex items-center gap-3 p-2 rounded-xl bg-white/5 shadow-inner">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-lg shadow-emerald-500/20">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <div class="text-[10px] font-bold text-white truncate"><?= $_SESSION['user_name'] ?? 'Admin' ?></div>
                        <div class="text-[8px] font-bold text-emerald-400 tracking-wider">ONLINE</div>
                    </div>
                </div>
                <form action="<?= url('/logout') ?>" method="POST" class="mt-2">
                    <button class="w-full flex items-center group px-4 py-2 text-slate-400 hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarOpen" class="ml-3 text-[10px] font-black uppercase tracking-widest">Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 md:px-8 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-sm md:text-lg font-black text-slate-900 tracking-tight uppercase"><?= $title ?></h2>
                </div>
                
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="hidden sm:flex items-center bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-2"></div>
                        <span class="text-[9px] font-black text-emerald-600 tracking-[0.1em] uppercase">Status: Optimal</span>
                    </div>
                </div>
            </header>

            <!-- Scrollable Body -->
            <main class="flex-1 overflow-y-auto w-full">
                <div class="p-4 md:p-8 max-w-[1600px] mx-auto">
                    <?= $content ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

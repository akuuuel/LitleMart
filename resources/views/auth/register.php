<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun - LitleMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#056526', background: '#F4F9F4' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F4F9F4; }
        input { color: #000000 !important; background-color: #ffffff !important; }
        /* Fix for Autofill visibility */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #000000 !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col md:flex-row">
    
    <!-- Left Panel (compact on mobile, fixed on desktop) -->
    <div class="w-full md:w-[400px] lg:w-[480px] bg-primary text-white p-6 md:p-10 lg:p-14 flex flex-col justify-between flex-shrink-0 min-h-[140px] md:min-h-screen">
        <div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl mb-4 md:mb-12 flex justify-center items-center shadow-lg">
                <svg class="w-7 h-7 text-primary" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
            </div>
            
            <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold tracking-tight mb-2 md:mb-6 leading-tight">
                Mulai perjalanan Anda bersama LitleMart.
            </h1>
            
            <p class="hidden md:block text-green-50 text-lg md:text-xl leading-relaxed opacity-90 max-w-sm">
                Bergabunglah dengan marketplace terpercaya dan profesional. Kembangkan bisnis Anda dengan presisi.
            </p>
        </div>
        
        <div class="hidden md:block mt-20">
            <div class="border border-green-700/50 rounded-2xl p-6 bg-green-900/20 backdrop-blur-sm">
                <div class="text-xs font-bold text-green-300 tracking-widest uppercase mb-4">Sorotan Penjual</div>
                <p class="text-sm italic font-medium leading-relaxed mb-6">
                    "LitleMart mengubah cara kami mengelola inventaris. Dashboard profesionalnya tiada duanya di industri ini."
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex-shrink-0"></div>
                    <div>
                        <div class="text-sm font-bold">Sarah Jenkins</div>
                        <div class="text-xs text-green-300">CEO, TechStore Pro</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel (Form) -->
    <div class="flex-1 flex flex-col justify-center px-5 py-8 md:p-12 lg:p-20 relative">
        
        <!-- Back to Home Button -->
        <a href="<?= url('/') ?>" class="absolute top-4 right-4 md:top-10 md:right-10 flex items-center gap-2 text-xs md:text-sm font-bold text-gray-500 hover:text-[#056526] transition-colors bg-white px-3 py-2 md:px-4 md:py-2 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-[#056526]/30 z-10">
            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="hidden xs:inline">Beranda</span>
            <span class="xs:hidden">Homer</span>
        </a>

        <!-- Progress Bar -->
        <div class="max-w-2xl w-full mx-auto mb-8 mt-12 md:mt-0">
            <div class="flex justify-between items-end mb-2">
                <div class="text-xs md:text-sm font-bold text-primary text-balance">Detail Akun Pribadi</div>
                <div class="text-[10px] md:text-xs font-bold text-gray-500 whitespace-nowrap">Langkah 1/2</div>
            </div>
            <div class="h-1 bg-gray-200 rounded-full w-full overflow-hidden">
                <div class="h-full bg-primary w-1/2"></div>
            </div>
        </div>

        <div class="max-w-2xl w-full mx-auto">
            <?php if ($error = flash('error')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center p-4 text-red-800 border-t-4 border-red-300 bg-red-50 rounded-lg shadow-sm" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                    <div class="ml-3 text-sm font-bold"><?= $error ?></div>
                    <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($success = flash('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center p-4 text-green-800 border-t-4 border-green-300 bg-green-50 rounded-lg shadow-sm" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                    <div class="ml-3 text-sm font-bold"><?= $success ?></div>
                    <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-10 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Buat akun Anda</h2>
                <p class="text-sm text-gray-500 mb-8">Masukkan nama lengkap dan alamat email utama Anda.</p>

                <form action="<?= url('/register') ?>" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input name="name" type="text" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-black placeholder-gray-400 bg-white" placeholder="Budi Santoso">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                        <input name="email" type="email" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-black placeholder-gray-400 bg-white" placeholder="john@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                        <input name="password" type="password" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-black placeholder-gray-400 bg-white" placeholder="••••••••">
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-green-800 transition-colors">
                            Langkah Berikutnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="<?= url('/login') ?>" class="font-bold text-primary hover:underline">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

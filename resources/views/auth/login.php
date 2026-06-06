<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - LitleMart</title>
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
    
    <!-- Left Panel (full height on md+, compact banner on mobile) -->
    <div class="relative w-full md:w-1/2 lg:w-7/12 bg-[#125832] flex items-center justify-center p-6 md:p-10 overflow-hidden min-h-[140px] md:min-h-screen">
        <!-- Abstract Plant Background Mockup -->
        <img src="https://picsum.photos/seed/plantbg/800/1000" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0d4526]/80 to-[#125832]/60"></div>
        
        <div class="relative z-10 w-full max-w-lg bg-white/10 backdrop-blur-md border border-white/20 p-6 md:p-12 rounded-3xl shadow-2xl">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-[#16A34A] rounded-2xl mb-4 md:mb-8 flex justify-center items-center shadow-lg">
                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
            </div>
            
            <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white tracking-tight mb-3 md:mb-6 leading-tight">
                Selamat Datang di Ekosistem LitleMart.
            </h1>
            
            <p class="hidden md:block text-white/80 text-lg leading-relaxed mb-10 max-w-sm">
                Kelola marketplace multi-vendor Anda dengan alat profesional yang dirancang untuk kejelasan, efisiensi, dan kontrol yang andal.
            </p>

            <div class="hidden md:grid grid-cols-2 gap-y-4 gap-x-6 text-white text-sm font-medium">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Analitik Perusahaan
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Portal Penjual
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Sinkronisasi Inventaris
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pembayaran Aman
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel (Form) -->
    <div class="flex-1 flex flex-col justify-center px-5 py-8 md:p-14 lg:p-20 relative bg-[#F4F9F4]">
        
        <!-- Back to Home Button -->
        <a href="<?= url('/') ?>" class="absolute top-6 right-6 md:top-10 md:right-10 flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#056526] transition-colors bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-[#056526]/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>

        <div class="max-w-md w-full mx-auto">
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

            <h2 class="text-4xl font-bold text-gray-900 mb-2">Masuk ke akun Anda</h2>
            <p class="text-gray-500 mb-10 text-lg">Selamat datang kembali! Masukkan detail Anda.</p>

            <form action="<?= url('/login') ?>" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline-block mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Alamat Email
                    </label>
                    <input name="email" type="email" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-black placeholder-gray-400 bg-white" placeholder="nama@perusahaan.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 inline-block mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Kata Sandi
                        </label>
                        <a href="<?= url('/forgot-password') ?>" class="text-sm font-bold text-[#056526] hover:underline">Lupa kata sandi?</a>
                    </div>
                    <div class="relative" x-data="{ showPass: false }">
                        <input name="password" :type="showPass ? 'text' : 'password'" required class="block w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-black bg-white" placeholder="••••••••">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-3 p-0.5 text-gray-400 hover:text-gray-700 transition-colors focus:outline-none">
                            <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded bg-white">
                    <label class="ml-2 block text-sm text-gray-600">Ingat selama 30 hari</label>
                </div>

                <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-lg text-sm font-bold text-white bg-[#056526] hover:bg-green-800 transition-colors shadow-sm">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-8 relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-[#F4F9F4] text-gray-500 font-bold text-xs uppercase tracking-wider">Atau lanjutkan dengan</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="<?= url('/auth/google') ?>" class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 hover:shadow-md transition-all text-sm font-bold text-gray-700 shadow-sm group">
                    <!-- Official Google G logo SVG -->
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        <path fill="none" d="M0 0h48v48H0z"/>
                    </svg>
                    Lanjutkan dengan Google
                    <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="mt-12 text-center text-sm">
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="<?= url('/register') ?>" class="font-bold text-[#056526] hover:underline">Daftar di LitleMart</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

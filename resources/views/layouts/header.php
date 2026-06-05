<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LitleMart' ?> - Multi Vendor Marketplace</title>
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
    </style>
    <?php include __DIR__ . '/realtime_script.php'; ?>
</head>
<body class="antialiased pt-14 md:pt-16">
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <?php require __DIR__ . '/../components/alert.php'; ?>

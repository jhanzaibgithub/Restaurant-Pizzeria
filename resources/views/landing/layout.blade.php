<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FE6524">
    <meta name="description" content="@yield('meta_description', 'Restaurant Pizzeria is a premium restaurant ecosystem for admin operations, branch management, delivery tracking, customer ordering, and driver workflows.')">
    <meta name="keywords" content="restaurant management software, food delivery platform, multi branch POS, Restaurant Pizzeria, delivery tracking, restaurant admin panel">
    <meta property="og:title" content="@yield('og_title', 'Restaurant Pizzeria - Manage Your Restaurant Empire')">
    <meta property="og:description" content="@yield('og_description', 'Run admin, branch, customer, driver, delivery, and analytics workflows from one modern restaurant platform.')">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('assets/admin/img/favicon.png') }}">

    <title>@yield('title', 'Restaurant Pizzeria - Manage Your Restaurant Empire')</title>

    <link rel="icon" href="{{ asset('assets/admin/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        feast: {
                            orange: '#FE6524',
                            orangeDark: '#E5520F',
                            blue: '#006AE5',
                            ink: '#334257',
                            dark: '#0B1220',
                            line: '#E8EDF5'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Sora', 'Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('assets/landing/css/landing.css') }}">
    @stack('head')
</head>
<body class="landing-body">
    <div id="cursorGlow" aria-hidden="true"></div>
    <div class="landing-noise" aria-hidden="true"></div>
    <div class="food-particles" aria-hidden="true">
        <span class="particle particle-a"></span>
        <span class="particle particle-b"></span>
        <span class="particle particle-c"></span>
        <span class="particle particle-d"></span>
        <span class="particle particle-e"></span>
    </div>

    @include('landing.partials.navbar')

    <main id="top">
        @yield('content')
    </main>

    @include('landing.partials.footer')

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@2.0.8/dist/lottie-player.js"></script>
    <script src="{{ asset('assets/landing/js/landing.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

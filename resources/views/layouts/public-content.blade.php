<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant Pizzeria')</title>

    <link rel="shortcut icon" href="{{ asset('assets/admin/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/installation/assets/css/bootstrap.min.css') }}">

    <style>
        :root {
            --restaurant-pizzeria-orange: #ff6b2c;
            --restaurant-pizzeria-dark: #111827;
            --restaurant-pizzeria-muted: #64748b;
            --restaurant-pizzeria-border: rgba(15, 23, 42, .1);
        }

        body {
            font-family: "Inter", sans-serif;
            color: var(--restaurant-pizzeria-dark);
            background: #171b20;
        }

        .public-page {
            min-height: 100vh;
            padding: 32px 16px;
            background:
                radial-gradient(circle at top left, rgba(255, 107, 44, .16), transparent 34rem),
                linear-gradient(135deg, rgba(17, 24, 39, .94), rgba(8, 13, 21, .96)),
                url("{{ asset('assets/installation/assets/img/page-bg.png') }}") center / cover no-repeat;
        }

        .public-topbar,
        .public-container {
            width: min(100%, 1328px);
            margin: 0 auto;
        }

        .public-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            color: #fff;
            text-decoration: none;
        }

        .brand-link:hover {
            color: #fff;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 76px;
            height: 48px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .95);
            box-shadow: 0 18px 45px rgba(255, 107, 44, .16);
        }

        .brand-mark img {
            max-width: 58px;
            max-height: 34px;
            object-fit: contain;
        }

        .brand-text strong,
        .brand-text small {
            display: block;
        }

        .brand-text strong {
            font-size: 24px;
            line-height: 1;
            font-weight: 800;
        }

        .brand-text small {
            margin-top: 5px;
            color: rgba(255, 255, 255, .68);
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .home-link {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .08);
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        .home-link:hover {
            color: #fff;
            background: rgba(255, 107, 44, .92);
        }

        .content-card {
            min-height: 560px;
            padding: clamp(28px, 4vw, 54px);
            border-radius: 10px;
            background: #fff;
            border: 1px solid var(--restaurant-pizzeria-border);
            box-shadow: 0 28px 80px rgba(0, 0, 0, .24);
        }

        .content-card h1,
        .content-card h2,
        .content-card h3 {
            color: var(--restaurant-pizzeria-dark);
            font-weight: 800;
        }

        .content-card p,
        .content-card li {
            color: var(--restaurant-pizzeria-muted);
            line-height: 1.8;
            font-size: 15px;
        }

        .content-card .ql-editor {
            min-height: auto;
        }

        .policy-content {
            max-width: 980px;
        }

        .content-kicker {
            display: inline-flex;
            margin-bottom: 16px;
            color: var(--restaurant-pizzeria-orange);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .policy-content h1 {
            max-width: 760px;
            margin-bottom: 20px;
            font-size: clamp(30px, 4vw, 48px);
            line-height: 1.1;
        }

        .policy-content h2 {
            margin-top: 34px;
            margin-bottom: 12px;
            font-size: 22px;
        }

        .policy-content ul {
            padding-left: 20px;
            margin: 0;
        }

        .policy-content li + li {
            margin-top: 10px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            margin-top: 28px;
        }

        .content-grid > div {
            padding: 24px;
            border: 1px solid var(--restaurant-pizzeria-border);
            border-radius: 8px;
            background: #fff8f5;
        }

        .content-grid h2 {
            margin-top: 0;
        }

        .public-footer {
            width: min(100%, 1328px);
            margin: 36px auto 0;
            padding-top: 28px;
            border-top: 1px solid rgba(255, 255, 255, .14);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            color: rgba(255, 255, 255, .62);
            font-size: 14px;
            font-weight: 600;
        }

        .public-footer img {
            width: 120px;
            max-height: 44px;
            object-fit: contain;
        }

        @media (max-width: 640px) {
            .public-page {
                padding-top: 20px;
            }

            .public-topbar,
            .public-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .brand-text strong {
                font-size: 20px;
            }

            .brand-mark {
                width: 64px;
                height: 44px;
            }

            .content-card {
                min-height: 420px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @stack('css_or_js')
</head>

<body>
    <main class="public-page">
        <header class="public-topbar">
            <a href="{{ route('landing') }}" class="brand-link" aria-label="Restaurant Pizzeria home">
                <span class="brand-mark">
                    <img src="{{ asset('assets/admin/img/favicon.png') }}" alt="Restaurant Pizzeria">
                </span>
                <span class="brand-text">
                    <strong>Restaurant Pizzeria</strong>
                    <small>Restaurant Ecosystem</small>
                </span>
            </a>
            <a href="{{ route('landing') }}" class="home-link">Back to Home</a>
        </header>

        <section class="public-container">
            @yield('content')
        </section>

        <footer class="public-footer">
            <img src="{{ asset('assets/admin/img/restaurant-pizzeria_logo.svg') }}" alt="Restaurant Pizzeria">
            <span>&copy; {{ date('Y') }} Restaurant Pizzeria. All Rights Reserved</span>
        </footer>
    </main>

    <script src="{{ asset('assets/installation/assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('script')
</body>

</html>

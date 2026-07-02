@php
    $adminLoginUrl = Route::has('admin.auth.login') ? route('admin.auth.login') : url('/admin/auth/login');
@endphp

<header class="landing-nav" id="landingNav">
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <a href="#top" class="brand-lockup" aria-label="Restaurant Pizzeria home">
            <span class="brand-mark">
                <img src="{{ asset('assets/admin/img/logo1.png') }}" alt="">
            </span>
            <span>
                <strong>Restaurant Pizzeria</strong>
                <small>Restaurant Ecosystem</small>
            </span>
        </a>

        <button class="nav-toggle lg:hidden" type="button" aria-label="Toggle menu" aria-expanded="false" data-nav-toggle>
            <i class="mdi mdi-menu"></i>
        </button>

        <div class="nav-menu" data-nav-menu>
            <a href="#features">Features</a>
            <a href="#workflow">Workflow</a>
            <a href="#apps">Apps</a>
            <a href="#faq">FAQ</a>
        </div>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ $adminLoginUrl }}" class="btn-ghost">Live Demo</a>
            <a href="#cta" class="btn-primary">Get Started</a>
        </div>
    </nav>
</header>

@php
    $year = date('Y');
@endphp

<footer class="footer-shell">
    <div class="footer-main mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="footer-brand">
            <a href="#top" class="brand-lockup brand-lockup-light">
                <span class="brand-mark">
                    <img src="{{ asset('assets/admin/img/restaurant-pizzeria_logo.svg') }}" alt="">
                </span>
                <span>
                    <strong>Restaurant Pizzeria</strong>
                    <small>Restaurant Ecosystem</small>
                </span>
            </a>
            <p>A modern operating system for restaurants with admin control, branch workflows, customer ordering, driver delivery, and growth analytics.</p>
        </div>
        <div class="footer-links">
            <h3>Platform</h3>
            <a href="#features">Admin Dashboard</a>
            <a href="#workflow">Order Management</a>
            <a href="#apps">Customer App</a>
            <a href="#apps">Driver App</a>
        </div>
        <div class="footer-links">
            <h3>Company</h3>
            <a href="{{ route('about-us') }}">About</a>
            <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
            <a href="{{ route('terms-and-conditions') }}">Terms</a>
        </div>
    </div>
    <div class="footer-bottom">
        Copyright {{ $year }} Restaurant Pizzeria. All rights reserved.
    </div>
</footer>

@extends('landing.layout')

@section('content')
@php
    $adminLoginUrl = Route::has('admin.auth.login') ? route('admin.auth.login') : url('/admin/auth/login');
    $stats = [
        ['value' => 128000, 'suffix' => '+', 'label' => 'Orders Delivered'],
        ['value' => 850, 'suffix' => '+', 'label' => 'Restaurants'],
        ['value' => 3200, 'suffix' => '+', 'label' => 'Active Drivers'],
        ['value' => 480000, 'suffix' => '+', 'label' => 'Customers'],
    ];
    $features = [
        ['icon' => 'mdi-view-dashboard-outline', 'title' => 'Admin Dashboard', 'text' => 'Control branches, menus, staff, customers, reports, settings, and live order flows from one command center.'],
        ['icon' => 'mdi-source-branch', 'title' => 'Branch Management', 'text' => 'Give every location its own operational workspace while keeping brand-wide visibility and governance.'],
        ['icon' => 'mdi-moped-outline', 'title' => 'Delivery Driver App', 'text' => 'Assign riders, track progress, manage availability, and keep delivery teams moving with fewer delays.'],
        ['icon' => 'mdi-map-marker-path', 'title' => 'Order Tracking', 'text' => 'Follow every order from checkout to kitchen to rider handoff with real-time status clarity.'],
        ['icon' => 'mdi-chart-timeline-variant', 'title' => 'Analytics & Reports', 'text' => 'See sales, customer growth, branch performance, delivery efficiency, and menu demand at a glance.'],
        ['icon' => 'mdi-bell-ring-outline', 'title' => 'Real-Time Notifications', 'text' => 'Keep admins, branches, customers, and drivers synced with instant updates across the order lifecycle.'],
        ['icon' => 'mdi-storefront-outline', 'title' => 'Multi Branch Support', 'text' => 'Scale from one kitchen to a city-wide network with location-aware operations and permissions.'],
        ['icon' => 'mdi-credit-card-check-outline', 'title' => 'Payment Integration', 'text' => 'Support flexible checkout flows, gateway options, wallet transactions, and payment reconciliation.'],
        ['icon' => 'mdi-cellphone-message', 'title' => 'Push Notifications', 'text' => 'Trigger timely alerts for new orders, dispatch updates, offers, delivery status, and account actions.'],
        ['icon' => 'mdi-wallet-outline', 'title' => 'Wallet System', 'text' => 'Handle wallet balances, loyalty, refunds, and promotions with a polished customer account experience.'],
    ];
    $advanced = [
        'Real-time order tracking', 'Branch analytics', 'Role management', 'Multi-language support',
        'Delivery zones', 'Live notifications', 'AI analytics', 'Kitchen-ready workflows'
    ];
    $faqs = [
        ['q' => 'Can Restaurant Pizzeria handle multiple restaurant branches?', 'a' => 'Yes. The platform is built around multi-branch operations, with central admin visibility and branch-level workflows for orders, products, staff, reports, and delivery.'],
        ['q' => 'Does it include customer and driver apps?', 'a' => 'Yes. Restaurant Pizzeria supports a full restaurant ecosystem: admin panel, branch panel, customer ordering app, driver app, order management, and delivery tracking.'],
        ['q' => 'Can I customize branding and colors?', 'a' => 'The landing page follows the existing Restaurant Pizzeria brand system, and the Laravel Blade/CSS structure is intentionally easy to adjust for logos, palette, links, and content.'],
        ['q' => 'Is the page responsive?', 'a' => 'Yes. The sections, mockups, sliders, counters, timeline, FAQ, and CTA are designed for desktop, tablet, and mobile screens.'],
    ];
@endphp

<section class="hero-section">
    <div class="hero-grid mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="hero-copy" data-aos="fade-up">
            <span class="eyebrow"><i class="mdi mdi-sparkles"></i> Premium restaurant operating system</span>
            <h1 class="headline">
                <span>Manage Your Restaurant Empire with</span>
                <strong class="gradient-text">Restaurant Pizzeria</strong>
            </h1>
            <p class="hero-subtitle">Unify admin control, branch management, customer ordering, delivery driver operations, real-time tracking, payments, notifications, and analytics in one high-performance Laravel ecosystem.</p>
            <div class="hero-actions">
                <a href="#cta" class="btn-primary btn-xl">Get Started <i class="mdi mdi-arrow-right"></i></a>
                <a href="{{ $adminLoginUrl }}" class="btn-glass btn-xl">Live Demo <i class="mdi mdi-play-circle-outline"></i></a>
            </div>
            <div class="hero-proof">
                <span><i class="mdi mdi-shield-check-outline"></i> Multi-branch ready</span>
                <span><i class="mdi mdi-flash-outline"></i> Real-time operations</span>
                <span><i class="mdi mdi-cellphone-check"></i> App ecosystem</span>
            </div>
        </div>

        <div class="hero-visual" data-aos="zoom-out" data-aos-delay="160">
            <div class="orb orb-one"></div>
            <div class="orb orb-two"></div>
            <lottie-player class="hero-lottie" src="https://assets10.lottiefiles.com/packages/lf20_xlmz9xwm.json" background="transparent" speed="1" loop autoplay aria-hidden="true"></lottie-player>
            <div class="dashboard-mockup floating-card">
                <div class="mockup-topbar">
                    <span></span><span></span><span></span>
                    <strong>Live Restaurant HQ</strong>
                </div>
                <div class="mockup-body">
                    <div class="mock-sidebar">
                        <span class="active"></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div class="mock-content">
                        <div class="metric-row">
                            <div><b data-counter="842">0</b><small>Orders</small></div>
                            <div><b data-counter="96">0</b><small>Drivers</small></div>
                            <div><b data-counter="42">0</b><small>Branches</small></div>
                        </div>
                        <div class="chart-card">
                            <span style="height: 42%"></span><span style="height: 64%"></span><span style="height: 54%"></span><span style="height: 82%"></span><span style="height: 70%"></span><span style="height: 92%"></span>
                        </div>
                        <div class="live-order">
                            <i class="mdi mdi-food-takeout-box-outline"></i>
                            <div><strong>Order #FF-2849</strong><small>Out for delivery - 12 min</small></div>
                            <em>Live</em>
                        </div>
                    </div>
                </div>
            </div>
            <div class="phone-mockup phone-left floating-card">
                <div class="phone-screen">
                    <span class="pill">Restaurant Pizzeria</span>
                    <h3>Track delivery</h3>
                    <div class="route-line"></div>
                    <div class="rider-dot"></div>
                    <p>Driver arriving soon</p>
                </div>
            </div>
            <div class="food-card food-card-one floating-card"><i class="mdi mdi-noodles"></i><span>Branch accepted</span></div>
            <div class="food-card food-card-two floating-card"><i class="mdi mdi-bike-fast"></i><span>Driver assigned</span></div>
        </div>
    </div>
</section>

<section class="stats-section" id="trusted">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="stats-panel" data-aos="fade-up">
            @foreach($stats as $stat)
                <div class="stat-card">
                    <strong><span data-counter="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] }}</strong>
                    <span>{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-shell" id="features">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading" data-aos="fade-up">
            <span>Everything connected</span>
            <h2>A complete food delivery and restaurant management stack</h2>
            <p>Designed for busy operators who need speed, visibility, and control without making the dashboard feel heavy.</p>
        </div>
        <div class="feature-grid">
            @foreach($features as $feature)
                <article class="feature-card tilt-card" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 5) * 60 }}">
                    <i class="mdi {{ $feature['icon'] }}"></i>
                    <h3>{{ $feature['title'] }}</h3>
                    <p>{{ $feature['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- <section class="preview-section" id="preview">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading light" data-aos="fade-up">
            <span>System preview</span>
            <h2>One elegant interface across every operational role</h2>
            <p>Admin, branch, customer, and driver views are presented as one cohesive Restaurant Pizzeria product experience.</p>
        </div>

        <div class="swiper preview-swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                @foreach([
                    ['Admin Panel', 'Revenue, branches, menu, users, permissions, promotions, and live orders.'],
                    ['Branch Dashboard', 'Focused workspace for kitchen queues, POS, tables, stock, and branch sales.'],
                    ['Customer App', 'Fast ordering, wallet, offers, tracking, history, and push notifications.'],
                    ['Driver App', 'Delivery assignments, route visibility, status actions, and earnings clarity.'],
                ] as $slide)
                    <div class="swiper-slide">
                        <div class="preview-card">
                            <div class="macbook-frame">
                                <div class="macbook-screen">
                                    <div class="mini-dashboard">
                                        <aside></aside>
                                        <main>
                                            <div class="mini-header"></div>
                                            <div class="mini-kpis"><span></span><span></span><span></span></div>
                                            <div class="mini-chart"></div>
                                            <div class="mini-table"><span></span><span></span><span></span></div>
                                        </main>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-copy">
                                <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3>{{ $slide[0] }}</h3>
                                <p>{{ $slide[1] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section> -->

<section class="section-shell" id="workflow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading" data-aos="fade-up">
            <span>How it works</span>
            <h2>From checkout to doorstep with fewer blind spots</h2>
        </div>
        <div class="timeline">
            @foreach([
                ['mdi-cart-check', 'Customer places order', 'Customer browses, checks out, applies wallet or payment, and gets instant confirmation.'],
                ['mdi-store-check-outline', 'Branch receives order', 'Branch team accepts, prepares, updates status, and keeps kitchen flow visible.'],
                ['mdi-moped', 'Driver picks up', 'Dispatch assigns a rider and tracks pickup, route, delivery state, and handoff.'],
                ['mdi-map-check-outline', 'Customer tracks delivery', 'Customer follows real-time updates until the order is delivered.'],
            ] as $step)
                <article class="timeline-step" data-aos="fade-right" data-aos-delay="{{ $loop->index * 90 }}">
                    <span>{{ $loop->iteration }}</span>
                    <i class="mdi {{ $step[0] }}"></i>
                    <div>
                        <h3>{{ $step[1] }}</h3>
                        <p>{{ $step[2] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="app-section" id="apps">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div data-aos="fade-up">
            <span class="eyebrow dark"><i class="mdi mdi-cellphone-star"></i> Mobile app ecosystem</span>
            <h2>Customer and driver apps that feel alive, fast, and on-brand</h2>
            <p>Bring the full order journey into mobile: quick menus, live order status, wallet, delivery assignment, notifications, and location-aware workflows.</p>
            <!-- <div class="store-buttons">
                <a href="#"><i class="mdi mdi-apple"></i><span>Download on<br><strong>App Store</strong></span></a>
                <a href="#"><i class="mdi mdi-google-play"></i><span>Get it on<br><strong>Google Play</strong></span></a>
            </div> -->
        </div>
        <div class="app-mockups" data-aos="zoom-in">
            <lottie-player class="app-lottie" src="https://assets2.lottiefiles.com/packages/lf20_q5pk6p1k.json" background="transparent" speed=".8" loop autoplay aria-hidden="true"></lottie-player>
            <div class="phone-mockup app-phone-primary floating-card">
                <div class="phone-screen app-screen">
                    <div class="app-top"></div>
                    <h3>Chicken Bowl</h3>
                    <div class="app-food"></div>
                    <button>Track order</button>
                </div>
            </div>
            <div class="phone-mockup app-phone-secondary floating-card">
                <div class="phone-screen driver-screen">
                    <span>New delivery</span>
                    <h3>3.8 km route</h3>
                    <div class="driver-map"></div>
                    <button>Accept pickup</button>
                </div>
            </div>
            <div class="notification-chip floating-card"><i class="mdi mdi-bell-ring"></i> Order is out for delivery</div>
        </div>
    </div>
</section>

<section class="section-shell advanced-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading" data-aos="fade-up">
            <span>Advanced features</span>
            <h2>Built for ambitious restaurant brands</h2>
        </div>
        <div class="advanced-grid">
            @foreach($advanced as $item)
                <div class="advanced-pill" data-aos="fade-up" data-aos-delay="{{ $loop->index * 45 }}">
                    <i class="mdi mdi-check-decagram"></i>{{ $item }}
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="testimonial-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading light" data-aos="fade-up">
            <span>Operator love</span>
            <h2>Made for teams that move fast</h2>
        </div>
        <div class="swiper testimonial-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                @foreach([
                    ['A polished command center for our busiest branches. The real-time order flow is exactly what restaurant teams need.', 'Sarah Malik', 'Operations Director'],
                    ['Restaurant Pizzeria gives our managers cleaner visibility across delivery, POS, and branch performance without switching tools.', 'Daniel Reyes', 'Restaurant Founder'],
                    ['The driver workflow and customer tracking experience helped us reduce support calls and keep customers confident.', 'Amina Khan', 'Growth Lead'],
                ] as $quote)
                    <div class="swiper-slide">
                        <blockquote>
                            <i class="mdi mdi-format-quote-open"></i>
                            <p>{{ $quote[0] }}</p>
                            <footer><strong>{{ $quote[1] }}</strong><span>{{ $quote[2] }}</span></footer>
                        </blockquote>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="section-shell faq-section" id="faq">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[.8fr_1.2fr] lg:px-8">
        <div class="section-heading text-left" data-aos="fade-up">
            <span>FAQ</span>
            <h2>Clear answers for launch decisions</h2>
            <p>Everything here is structured in Blade and plain assets, so the page remains easy to maintain inside Laravel.</p>
        </div>
        <div class="faq-list" data-aos="fade-up">
            @foreach($faqs as $faq)
                <article class="faq-item">
                    <button type="button" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $faq['q'] }}
                        <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <div class="faq-answer" @if($loop->first) style="max-height: 180px" @endif>
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-section" id="cta">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="cta-banner" data-aos="zoom-in">
            <span>Ready for the next service rush?</span>
            <h2>Start Growing Your Restaurant Business Today</h2>
            <p>Launch a premium, connected restaurant ecosystem that supports admins, branches, customers, drivers, and delivery operations from day one.</p>
            <div>
                <a href="{{ $adminLoginUrl }}" class="btn-primary btn-xl">Open Demo <i class="mdi mdi-arrow-right"></i></a>
                <a href="#features" class="btn-glass btn-xl">Explore Features</a>
            </div>
        </div>
    </div>
</section>
@endsection

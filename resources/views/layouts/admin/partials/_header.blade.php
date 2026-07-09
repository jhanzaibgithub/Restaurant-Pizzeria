<div id="headerMain" class="d-none">
    <header id="header" class="navbar bg-white navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap" style="margin-top:9px;">
            <div class="navbar-brand-wrapper">
                @php($restaurant_logo = $adminLayoutSettings['logo'] ?? '')
                <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="">
                    <img class="navbar-brand-logo" style="object-fit: contain;"
                         onerror="this.src='{{asset('assets/admin/img/160x160/img1.jpg')}}'"
                         src="{{asset('/storage/restaurant/'.$restaurant_logo)}}" alt="Logo">
                    <img class="navbar-brand-logo-mini" style="object-fit: contain;"
                         onerror="this.src='{{asset('assets/admin/img/160x160/img1.jpg')}}'"
                         src="{{asset('/storage/restaurant/'.$restaurant_logo)}}"
                         alt="Logo">
                </a>
            </div>

            <div class="navbar-nav-wrap-content-left d-xl-none">
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                       data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
            </div>
            <div class="navbar-nav-wrap-content-left">
                <div class="d-flex flex-column">
                    <ul class="navbar-nav align-items-center flex-row">
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                                <a class="rounded-circle"
                                href="{{route('admin.dashboard')}}">

                                    <img style="width: 50px;" src="{{asset('assets/admin/img/logo1.png')}}" alt="">
                                </a>
                        </li>

                        @if (Request::is('admin/dashboard'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class=" rounded-circle"
                            href="{{route('admin.dashboard')}}">

                                <img src="{{asset('assets/admin/img/main_header_dashboard.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/pos') || Request::is('admin/pos/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.pos.index')}}">

                                <img src="{{asset('assets/admin/img/main_header_pos.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/orders/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.orders.list',['all'])}}">

                                <img src="{{asset('assets/admin/img/main_header_orders.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/category/add') || Request::is('admin/category/add-sub-category') || Request::is('admin/addon/add-new') || Request::is('admin/product/list') || Request::is('admin/attribute/add-new') || Request::is('admin/reviews/list') || Request::is('admin/category/*') || Request::is('admin/product/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.category.add')}}">

                                <img src="{{asset('assets/admin/img/main_header_menus.png')}}" alt="">
                            </a>
                        </li>
                        @elseif (Request::is('admin/branch/list') || Request::is('admin/branch/add-new'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.branch.list')}}">

                                <img src="{{asset('assets/admin/img/main_header_branches.png')}}" alt="">
                            </a>
                        </li>
                        @elseif (Request::is('admin/table/list') || Request::is('admin/promotion/create') || Request::is('admin/table/index'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.table.list')}}">

                                <img src="{{asset('assets/admin/img/main_header_tables.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/customer/list') || Request::is('admin/customer/settings') || Request::is('admin/customer/wallet/report') || Request::is('admin/customer/wallet/add-fund') || Request::is('admin/customer/loyalty-point/report') || Request::is('admin/customer/subscribed-emails'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.customer.list')}}">

                                <img src="{{asset('assets/admin/img/main_header_customers.png')}}" alt="">
                            </a>
                        </li>

                        @elseif(Request::is('admin/employee/list') || Request::is('admin/employee/add-new') || Request::is('admin/custom-role/create'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.employee.list')}}">

                                <img src="{{asset('assets/admin/img/main_header_employees.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/kitchen/list') || Request::is('admin/kitchen/add-new'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.kitchen.list')}}">

                                <img src="{{asset('assets/admin/img/main_header_chef.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/delivery-man/list') || Request::is('admin/delivery-man/edit/*') || Request::is('admin/delivery-man/reviews/list') || Request::is('admin/delivery-man/add') || Request::is('admin/delivery-man/pending/list') || Request::is('admin/delivery-man/denied/list'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.delivery-man.list')}}">

                                <img src="{{asset('assets/admin/img/main_header_deliveryman.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/banner/list') || Request::is('admin/coupon/add-new') || Request::is('admin/coupon/update/*') || Request::is('admin/notification/add-new') || Request::is('admin/notification/edit/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.banner.list')}}">

                                <img src="{{asset('assets/admin/img/main_header_promotion.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/report/earning') || Request::is('admin/report/sale-report') || Request::is('admin/report/order')|| Request::is('admin/report/product-report') || Request::is('admin/report/branch') || Request::is('admin/report/customer'))

                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.report.earning')}}">

                                <img src="{{asset('assets/admin/img/main_header_report.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/business-settings/restaurant/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.business-settings.restaurant.restaurant-setup')}}">

                                <img src="{{asset('assets/admin/img/main_header_settings.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/business-settings/page-setup/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.business-settings.page-setup.about-us')}}">

                                <img src="{{asset('assets/admin/img/main_header_settings.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/business-settings/web-app/social-media'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="#">

                                <img src="{{asset('assets/admin/img/main_header_settings.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/business-settings/web-app/third-party/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.business-settings.web-app.payment-method')}}">

                                <img src="{{asset('assets/admin/img/main_header_settings.png')}}" alt="">
                            </a>
                        </li>
                        @elseif(Request::is('admin/business-settings/web-app/system-setup/*'))
                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle"
                            href="{{route('admin.business-settings.web-app.system-setup.language.index')}}">

                                <img src="{{asset('assets/admin/img/main_header_settings.png')}}" alt="">
                            </a>
                        </li>

                    @endif

                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                                <a class="rounded-circle" href="javascript:void(0)">
                                    @if(Request::is('admin/*'))
                                        <img src="{{asset('assets/admin/img/main_header_rightArrow.png')}}" alt="">
                                    @endif
                                    <span style="color:#7E8299;">
                                    <strong>
                                    @if(Request::is('admin/dashboard'))
                                        Dashboard
                                    @elseif(Request::is('admin/pos') || Request::is('admin/pos/*'))
                                        POS
                                    @elseif(Request::is('admin/orders/*'))
                                        Orders
                                    @elseif(Request::is('admin/category/add') || Request::is('admin/category/add-sub-category') || Request::is('admin/addon/add-new') || Request::is('admin/product/list') || Request::is('admin/attribute/add-new') || Request::is('admin/reviews/list') || Request::is('admin/category/*') || Request::is('admin/product/*'))
                                        Menu
                                    @elseif (Request::is('admin/branch/list') || Request::is('admin/branch/add-new'))
                                        Branch
                                    @elseif (Request::is('admin/table/list') || Request::is('admin/promotion/create') || Request::is('admin/table/index'))
                                        Table
                                    @elseif(Request::is('admin/customer/list') || Request::is('admin/customer/settings') || Request::is('admin/customer/wallet/report') || Request::is('admin/customer/wallet/add-fund') || Request::is('admin/customer/loyalty-point/report') || Request::is('admin/customer/subscribed-emails'))
                                        Customer
                                    @elseif(Request::is('admin/employee/list') || Request::is('admin/employee/add-new') || Request::is('admin/custom-role/create'))
                                        Employee
                                        @elseif(Request::is('admin/kitchen/list') || Request::is('admin/kitchen/add-new'))
                                        Chef
                                        @elseif(Request::is('admin/delivery-man/list') || Request::is('admin/delivery-man/edit/*') || Request::is('admin/delivery-man/reviews/list') || Request::is('admin/delivery-man/add') || Request::is('admin/delivery-man/pending/list') || Request::is('admin/delivery-man/denied/list'))
                                        Deliveryman
                                        @elseif(Request::is('admin/banner/list') || Request::is('admin/coupon/add-new') || Request::is('admin/coupon/update/*') || Request::is('admin/notification/add-new') || Request::is('admin/notification/edit/*'))
                                        Promotion
                                        @elseif(Request::is('admin/report/earning') || Request::is('admin/report/sale-report') || Request::is('admin/report/order')|| Request::is('admin/report/product-report') || Request::is('admin/report/branch') || Request::is('admin/report/customer'))
                                            Report
                                        @elseif(Request::is('admin/settings'))
                                            Settings
                                        @elseif(Request::is('admin/business-settings/restaurant/*'))
                                            Settings
                                        @elseif(Request::is('admin/business-settings/page-setup/*'))
                                            Settings
                                            @elseif(Request::is('admin/business-settings/web-app/social-media'))
                                            Settings
                                            @elseif(Request::is('admin/business-settings/web-app/third-party/*'))
                                                Settings
                                            @elseif(Request::is('admin/business-settings/web-app/system-setup/*'))
                                                Settings


                                    @endif
                                    </strong></span>
                                </a>
                        </li>

                        <li style="margin-inline-end: 0.4rem;" class="nav-item d-none d-sm-inline-block">
                            <a class="rounded-circle" href="javascript:void(0)">
                                @if(!Request::is('admin/dashboard') && !Request::is('admin/pos') && !Request::is('admin/pos/orders') && !Request::is('admin/orders/list/*') && !Request::is('admin/category/add') && !Request::is('admin/category/add-sub-category') && !Request::is('admin/addon/add-new') && !Request::is('admin/branch/*') && !Request::is('admin/table/*') && !Request::is('admin/promotion/create') && !Request::is('admin/customer/*') && !Request::is('admin/employee/*') && !Request::is('admin/custom-role/create') && !Request::is('admin/kitchen/*') && !Request::is('admin/delivery-man/*') && !Request::is('admin/banner/*') && !Request::is('admin/coupon/*') && !Request::is('admin/notification/*') && !Request::is('admin/report/*') && !Request::is('admin/business-settings/web-app/social-media'))
                                    <img src="{{asset('assets/admin/img/main_header_rightArrow.png')}}" alt="">
                                @endif
                                <span style="color:#7E8299;">
                                <strong>

                                    @if( Request::is('admin/product/list') || Request::is('admin/attribute/add-new') || Request::is('admin/reviews/list') )
                                    Product
                                    @elseif( Request::is('admin/category/bulk-import') ||Request::is('admin/category/bulk-export')  || Request::is('admin/product/*'))
                                    BulkImport & Export
                                    @elseif(Request::is('admin/settings'))
                                    Profile Settings
                                    @elseif(Request::is('admin/business-settings/restaurant/*'))
                                    Business Setup
                                    @elseif(Request::is('admin/business-settings/page-setup/*'))
                                    page Setup
                                    @elseif(Request::is('admin/business-settings/web-app/third-party/*'))
                                    3rd Party
                                    @elseif(Request::is('admin/business-settings/web-app/system-setup/*'))
                                    System Setup
                                 @endif
                                </strong></span>
                            </a>
                        </li>
                        <li class="nav-item d-none d-sm-inline-block">
                                <a class=" rounded-circle" href="javascript:void(0)">
                                    @if(Request::is('admin/*') && !Request::is('admin/settings'))
                                        <img src="{{asset('assets/admin/img/main_header_rightArrow.png')}}" alt="">
                                    @endif
                                    <span style="color:#3F4254;">
                                        <strong>
                                        @if(Request::is('admin/dashboard'))
                                            OverView
                                        @elseif(Request::is('admin/pos'))
                                            New Sale
                                        @elseif(Request::is('admin/pos/*'))
                                            Order History
                                            @elseif(Request::is('admin/orders/list/all'))
                                            All
                                        @elseif(Request::is('admin/orders/list/dine'))
                                        Dinein
                                        @elseif(Request::is('admin/orders/list/takeaway'))
                                        TakeAway
                                        @elseif(Request::is('admin/orders/list/delivery'))
                                        Home Delivery
                                        @elseif(Request::is('admin/category/add'))
                                            category
                                        @elseif(Request::is('admin/category/add-sub-category'))
                                        Sub Category
                                        @elseif(Request::is('admin/addon/add-new'))
                                            Addon
                                        @elseif(Request::is('admin/product/list'))
                                            product List
                                        @elseif(Request::is('admin/attribute/add-new'))
                                            product Attribute
                                        @elseif(Request::is('admin/reviews/list'))
                                            product Reviews
                                        @elseif(Request::is('admin/category/bulk-import'))
                                            Category Bulk-import
                                        @elseif(Request::is('admin/category/bulk-export'))
                                            Category Bulk-export

                                        @elseif(Request::is('admin/product/bulk-import'))
                                            Product Bulk-import
                                        @elseif(Request::is('admin/product/bulk-export'))
                                            Product Bulk-export
                                        @elseif(Request::is('admin/branch/list'))
                                            List
                                        @elseif( Request::is('admin/branch/add-new'))
                                        Add New
                                        @elseif(Request::is('admin/table/list'))
                                            List
                                        @elseif(Request::is('admin/table/index'))
                                            Avalibility
                                        @elseif( Request::is('admin/promotion/create'))
                                        Add New
                                        @elseif(Request::is('admin/customer/list'))
                                        All Customers
                                        @elseif(Request::is('admin/customer/settings'))
                                            Settings
                                        @elseif(Request::is('admin/customer/wallet/report'))
                                            wallet
                                        @elseif(Request::is('admin/customer/wallet/add-fund'))
                                            Fund
                                        @elseif(Request::is('admin/customer/loyalty-point/report'))
                                        Loyality Points
                                        @elseif(Request::is('admin/customer/subscribed-emails'))
                                        Subscribed Emails
                                        @elseif(Request::is('admin/employee/list'))
                                        All Employees
                                        @elseif(Request::is('admin/employee/add-new'))
                                        Add New
                                        @elseif(Request::is('admin/custom-role/create'))
                                            Role
                                        @elseif(Request::is('admin/kitchen/list'))
                                        All Chefs
                                        @elseif(Request::is('admin/kitchen/add-new'))
                                        Add New
                                        @elseif(Request::is('admin/delivery-man/list'))
                                        All Deliverymen
                                        @elseif(Request::is('admin/delivery-man/reviews/list'))
                                        Rating
                                        @elseif(Request::is('admin/delivery-man/add'))
                                        Add New
                                        @elseif(Request::is('admin/delivery-man/pending/list'))
                                        Pending List
                                        @elseif(Request::is('admin/delivery-man/denied/list'))
                                        Denied List
                                        @elseif(Request::is('admin/banner/list'))
                                        Banner
                                        @elseif(Request::is('admin/coupon/add-new') || Request::is('admin/coupon/*'))
                                        Coupon
                                        @elseif(Request::is('admin/notification/add-new') || Request::is('admin/notification/edit/*'))
                                        Notification
                                        @elseif(Request::is('admin/report/earning'))
                                            Earning
                                        @elseif(Request::is('admin/report/sale-report'))
                                            sale
                                        @elseif(Request::is('admin/report/order'))
                                        Orders
                                        @elseif(Request::is('admin/report/product-report'))
                                        Product Report
                                        @elseif(Request::is('admin/report/branch'))
                                        Branch Report
                                        @elseif(Request::is('admin/report/customer'))
                                        Customer Report
                                        @elseif(Request::is('admin/business-settings/restaurant/restaurant-setup'))
                                        Business Setting
                                        @elseif(Request::is('admin/business-settings/restaurant/main-branch-setup'))
                                        Main Branch Setup
                                        @elseif(Request::is('admin/business-settings/restaurant/time-schedule'))
                                        Rest_Ava.Time Slot
                                        @elseif(Request::is('admin/business-settings/restaurant/delivery-fee-setup'))
                                        Delivery Fee Setup
                                        @elseif(Request::is('admin/business-settings/restaurant/cookies-setup'))
                                        Cookies Setup
                                        @elseif(Request::is('admin/business-settings/restaurant/otp-setup'))
                                        OTP Setup
                                        @elseif(Request::is('admin/business-settings/page-setup/about-us'))
                                        About Us
                                        @elseif(Request::is('admin/business-settings/page-setup/terms-and-conditions'))
                                        Terms & Conditions
                                        @elseif(Request::is('admin/business-settings/page-setup/privacy-policy'))
                                        Privacy Policy
                                        @elseif(Request::is('admin/business-settings/page-setup/return-page'))
                                        Return Policy
                                        @elseif(Request::is('admin/business-settings/page-setup/refund-page'))
                                        Refund Policy
                                        @elseif(Request::is('admin/business-settings/page-setup/cancellation-page'))
                                        Cancellation Policy
                                        @elseif(Request::is('admin/business-settings/web-app/social-media'))
                                        Social Media
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/payment-method'))
                                        Payment Methods
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/mail-config'))
                                        Mail Config
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/sms-module'))
                                        SMS Config
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/map-api-settings'))
                                        Google Map Api's
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/recaptcha'))
                                        Recaptcha
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/fcm-index'))
                                        Push Notification
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/social-login'))
                                        Social Login
                                        @elseif(Request::is('admin/business-settings/web-app/third-party/chat'))
                                        Chat
                                        @elseif(Request::is('admin/business-settings/web-app/system-setup/language'))
                                        Language Setup
                                        @elseif(Request::is('admin/business-settings/web-app/system-setup/app-setting'))
                                       App Settings
                                        @elseif(Request::is('admin/business-settings/web-app/system-setup/firebase-message-config'))
                                        Fire Base Configuration
                                        @elseif(Request::is('admin/business-settings/web-app/system-setup/db-index'))
                                        Clean Database
                                        @endif
                                        </strong>
                                    </span>
                                </a>
                        </li>
                    </ul>
                    {{-- <h2>restaurant-pizzeria</h2> --}}
                </div>
            </div>
            <div class="navbar-nav-wrap-content-right">
                <ul class="navbar-nav align-items-center flex-row">

                    <li class="nav-item d-none d-sm-inline-block">
                        <div class="hs-unfold">
                            <div class="bg-white p-1 rounded">
                                @php( $local = session()->has('local')?session('local'):'en')
                                @php($lang = $adminLanguageSettings ?? [])
                                @php($lang = is_array($lang) ? $lang : [])
                                @php($lang = collect($lang)->map(function ($item) {
                                    return is_array($item) ? $item : [
                                        'id' => 1,
                                        'name' => $item,
                                        'code' => $item,
                                        'status' => 1,
                                        'default' => $item === 'en',
                                    ];
                                })->values()->all())
                                <div class="topbar-text dropdown disable-autohide text-capitalize">
                                    @if(isset($lang[0]) && is_array($lang[0]) && array_key_exists('code', $lang[0]))
                                        <a class="topbar-link  d-flex gap-2 align-items-center font-weight-bold dropdown-toggle-empty lang-country-flag" href="#" data-toggle="dropdown">
                                            @foreach($lang as $data)
                                                @if($data['code']==$local)
                                                    <img src="{{asset('assets/admin/img/main_header_language.png')}}" alt="">
                                                @endif
                                            @endforeach
                                        </a>
                                        <ul class="dropdown-menu">
                                            @foreach($lang as $key =>$data)

                                                @if($data['status']==1)
                                                    <li>
                                                        <a class="dropdown-item pr-8 d-flex gap-2 align-items-center"
                                                           href="{{route('admin.lang',[$data['code']])}}">
                                                            <span class="text-capitalize">{{\App\CentralLogics\Helpers::get_language_name($data['code'])}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item d-none d-sm-inline-block">
                        <div class="hs-unfold">
                            <a class="rounded-circle"
                               href="{{route('admin.orders.list',['status'=>'all'])}}">
                                <img src="{{asset('assets/admin/img/main_header_bag.png')}}" alt="">

                                <span class="btn-status btn-status-c1">0</span>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item d-none d-sm-inline-block">
                        <div class="hs-unfold">
                            <a class=" rounded-circle"
                               href="{{route('admin.message.list')}}">
                                <img src="{{asset('assets/admin/img/main_header_message.png')}}" alt="">
                                    <span class="btn-status btn-status-c1">{{ $adminUnreadConversationCount ?? 0 }}</span>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item ml-4">
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper media gap-2" href="javascript:;"
                               data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="avatar avatar-sm">
                                    <img class="avatar-img"
                                         onerror="this.src='{{asset('assets/admin/img/160x160/img1.jpg')}}'"
                                         src="{{asset('/storage/admin')}}/{{auth('admin')->user()->image}}"
                                         alt="Image Description">
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account navbar-dropdown-lg">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img"
                                                 onerror="this.src='{{asset('assets/admin/img/160x160/img1.jpg')}}'"
                                                 src="{{asset('/storage/admin')}}/{{auth('admin')->user()->image}}"
                                                 alt="Image Description">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{auth('admin')->user()->f_name}}</span>
                                            <span class="card-text">{{auth('admin')->user()->email}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{route('admin.settings')}}">
                                    <span class="text-truncate pr-2" title="Settings">{{translate('settings')}}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title: '{{translate("Do you want to logout?")}}',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#FC6A57',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: '{{translate("Yes")}}',
                                    cancelButtonText: `{{translate('No')}}`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('admin.auth.logout')}}';
                                    } else{
                                        Swal.fire({
                                        title: '{{translate("Canceled")}}',
                                        confirmButtonText: '{{translate("Okay")}}',
                                        })
                                    }
                                    })">
                                    <span class="text-truncate pr-2" title="Sign out">{{translate('sign_out')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
<hr>

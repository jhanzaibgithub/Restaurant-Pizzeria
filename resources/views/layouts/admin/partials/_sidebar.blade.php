<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
        <div class="navbar-vertical-container text-capitalize">
            <div class="navbar-vertical-footer-offset">
                <div class="navbar-brand-wrapper justify-content-between">
                    @php($restaurant_logo = $adminLayoutSettings['logo'] ?? '')
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="Front">
                        <img class="navbar-brand-logo" style="object-fit: contain;"
                            onerror="this.src='{{ asset('assets/admin/img/160x160/img2.jpg') }}'"
                            src="{{ asset('/storage/restaurant/' . $restaurant_logo) }}" alt="Logo">
                        <img class="navbar-brand-logo-mini" style="object-fit: contain;"
                            onerror="this.src='{{ asset('assets/admin/img/160x160/img2.jpg') }}'"
                            src="{{ asset('/storage/restaurant/' . $restaurant_logo) }}" alt="Logo">
                    </a>

                    <button type="button"
                        class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                            data-placement="right" title="" data-original-title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                            data-template="<div class=&quot;tooltip d-none d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>"
                            data-toggle="tooltip" data-placement="right" title=""
                            data-original-title="Expand"></i>
                    </button>

                    <div class="navbar-nav-wrap-content-left d-none d-xl-block">
                        <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                            <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                                data-placement="right" title="" data-original-title="Collapse"></i>
                            <i class="tio-last-page navbar-vertical-aside-toggle-full-align"></i>
                        </button>
                    </div>
                </div>


                <div class="navbar-vertical-content">

                    <ul class="navbar-nav navbar-nav-lg nav-tabs">

                        <!-- Home -->
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.home') }}"
                                title="{{ translate('Home') }}">
                                <img class="navImg mr-3" src="{{ asset('assets/admin/img/icons/side_home.png') }}"
                                alt="">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('home') }}
                                </span>
                            </a>
                        </li>
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/dashboard') ? 'show' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.dashboard') }}" title="{{ translate('Dashboards') }}">
                                <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_dashboard.png') }}"
                                    alt="">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('dashboard') }}
                                </span>
                            </a>
                        </li>
                        <!--POS -->
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['pos_management']))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/pos') || Request::is('admin/pos*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.pos.index') }}">
                                <img  class="mr-3" src="{{ asset('assets/admin/img/icons/side_pos.png') }}"
                                 alt="">
                                    <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('pos') }}
                                </span>
                            </a>
                        </li>
                        @endif
                        {{--  -------------ORDERS START HERE-----  --}}
                                     <li class="nav-item my-2">
                                          <small
                                              class="nav-subtitle"></small>
                                          <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                      </li>
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['order_management']))
                        <li  class="navbar-vertical-aside-has-menu {{ Request::is('admin/orders/list/all')
                            || Request::is('admin/orders/list/dine')
                            || Request::is('admin/orders/list/takeaway')
                            || Request::is('admin/orders/list/delivery')
                            || Request::is('admin/orders/details/*') ? 'active' : '' }}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.orders.list', ['all']) }}" title="{{ translate('orders') }} ">
                                            <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_orders.png') }}"
                                                alt="">
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{ translate('orders') }}
                                            </span>
                                        </a>
                        </li>
                        @endif
                        {{--  MENU START  --}}
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['product_management']))
                        <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/category*') || Request::is('admin/addon*') || Request::is('admin/product*')|| Request::is('admin/product/add-new') || Request::is('admin/attribute*') || Request::is('admin/reviews/list') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.category.add') }}" title="{{ translate('menu') }}">
                                    <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_menus.png') }}"
                                        alt="">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('menu') }}
                                    </span>
                                </a>
                        @endif

                        <!--BRANCH SETUP -->
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['system_management']))
                        <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/branch*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.branch.list') }}" title="{{ translate('branch') }}">
                                    <img class="mr-3"
                                        src="{{ asset('assets/admin/img/icons/side_branches.png') }}"
                                        alt="">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ translate('branch') }}
                                    </span>
                                </a>
                        </li>
                        @endif


                                    <li class="nav-item my-2">
                                          <small
                                              class="nav-subtitle"></small>
                                          <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                      </li>
                <!-- Table Management -->
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['table_management']))
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/table/list') || Request::is('admin/table/update*') || Request::is('admin/promotion/create') || Request::is('admin/promotion/edit*') || Request::is('admin/table/index') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.table.list') }}" title="{{ translate('table') }}">
                                    <img class="mr-3"
                                        src="{{ asset('assets/admin/img/icons/side_tables.png') }}"
                                        alt="">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('tables') }}
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['user_management']))
                            <li
                                class="navbar-vertical-aside-has-menu {{
                                    Request::is('admin/customer/transaction')
                                    || Request::is('admin/customer/list')
                                    || Request::is('admin/customer/wallet/report')
                                    || Request::is('admin/customer/wallet/add-fund')
                                    || Request::is('admin/customer/loyalty-point/report')
                                    || Request::is('admin/customer/subscribed-emails')
                                    || Request::is('admin/customer/view*')
                                    || Request::is('admin/customer/settings') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.customer.list') }}" title="{{ translate('customers') }} ">
                                    <img class="mr-3"
                                        src="{{ asset('assets/admin/img/icons/side_customers.png') }}"
                                        alt="">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ translate('customers') }}
                                    </span>
                                </a>
                            </li>
                        @endif


                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['user_management']))
                            @if (auth('admin')->user()->admin_role_id == 1)
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/custom-role*') || Request::is('admin/employee*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.employee.list') }}" title="{{ translate('Employees') }}">
                                        <img class="mr-3"
                                            src="{{ asset('assets/admin/img/icons/side_employees.png') }}"
                                            alt="">
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ translate('Employees') }}
                                        </span>
                                    </a>
                                </li>
                            @endif
                        @endif

                        {{--  CHEF START --}}
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['user_management']))
                        <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/kitchen*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link "
                                    href="{{ route('admin.kitchen.list') }}" title="{{ translate('chef') }}">
                                    <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_cook.png') }}"
                                        alt="">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('chef') }}
                                    </span>
                                </a>
                        </li>
                        @endif
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['user_management']))

                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link "
                                    href="{{ route('admin.delivery-man.list') }}" title="{{ translate('Delivery') }}">
                                    <img class="mr-3"
                                        src="{{ asset('assets/admin/img/icons/side_deliveryman.png') }}"
                                        alt="">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ translate('deliveryman') }}
                                    </span>
                                </a>
                        </li>
                        @endif



                        {{--  TABLE ORDER START HERE  --}}

                        {{--  TABLE ORDER END HERE  --}}

                                     <li class="nav-item my-2">
                                          <small
                                              class="nav-subtitle"></small>
                                          <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                      </li>



                                      {{--  -------------PROMOTION MANAGEMENT STARTS HERE-----  --}}

                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['promotion_management']))
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/promotion_management*') ||Request::is('admin/banner/*') ||Request::is('admin/coupon/*') ||Request::is('admin/notification/*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link "
                                    href="{{ route('admin.banner.list') }}" title="{{ translate('promotions') }} ">
                                    <img class="mr-3"
                                        src="{{ asset('assets/admin/img/icons/side_promotion.png') }}"
                                        alt="">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ translate('promotions') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        {{--  REPORT & ANALYTICS MANAGEMENT --}}

                    @if (Helpers::module_permission_check(MANAGEMENT_SECTION['report_and_analytics_management']))
                        <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/report*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.report.earning') }}" title="{{ translate('Reports') }}">
                                    <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_report.png') }}"
                                        alt="">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ translate('Reports') }}
                                    </span>
                                </a>
                            </li>
                    @endif

                        <!-- BRANCH -->
                        @if (Helpers::module_permission_check(MANAGEMENT_SECTION['system_management']))
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/restaurant/*') || Request::is('admin/business-settings/restaurant/restaurant-setup') || Request::is('admin/business-settings/web-app/social-media') || Request::is('admin/business-settings/page-setup/*') || Request::is('admin/business-settings/web-app/third-party*') || Request::is('admin/business-settings/web-app/system-setup*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.business-settings.restaurant.restaurant-setup') }}" title="{{ translate('settings') }} ">
                                    <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_settings.png') }}"
                                        alt="">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ translate('settings') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


@push('script_2')

<script>
    $(document).ready(function () {
       $(".navbar-vertical-content .navbar-vertical-aside-has-menu").on("click", function () {
            $(".navbar-vertical-content li").removeClass("active");
        });
    });
</script>
    <script>
        $(window).on('load', function() {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content  .navbar-nav > li');
        $('#search-bar-input').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush

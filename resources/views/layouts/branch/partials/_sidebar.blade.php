<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset">
                <div class="navbar-brand-wrapper justify-content-between">
                    @php($restaurant_logo=$branchLayoutSettings['logo'] ?? '')
                    <a class="navbar-brand" href="{{route('branch.dashboard')}}" aria-label="Front">
                        <img class="navbar-brand-logo" style="object-fit: contain;"
                             onerror="this.src='{{asset('assets/admin/img/160x160/img2.jpg')}}'"
                             src="{{asset('/storage/restaurant/'.$restaurant_logo)}}"
                             alt="Logo">
                        <img class="navbar-brand-logo-mini" style="object-fit: contain;"
                             onerror="this.src='{{asset('assets/admin/img/160x160/img2.jpg')}}'"
                             src="{{asset('/storage/restaurant/'.$restaurant_logo)}}" alt="Logo">
                    </a>
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip" data-placement="right" title="" data-original-title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align" data-template="<div class=&quot;tooltip d-none d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>" data-toggle="tooltip" data-placement="right" title="" data-original-title="Expand"></i>
                    </button>
                    <div class="navbar-nav-wrap-content-left d-none d-xl-block">
                        <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                            <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip" data-placement="right" title="" data-original-title="Collapse"></i>
                            <i class="tio-last-page navbar-vertical-aside-toggle-full-align"></i>
                        </button>
                    </div>
                </div>

                <div class="navbar-vertical-content text-capitalize">
                    <div class="sidebar--search-form py-3">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control" id="search-bar-input" placeholder="Search Menu...">
                        </div>
                    </div>

                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <li class="navbar-vertical-aside-has-menu {{Request::is('branch')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('branch.dashboard')}}" title="{{translate('Dashboards')}}">
                               <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_dashboard.png') }}"
                               alt="">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('dashboard')}}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <small
                                class="nav-subtitle">{{translate('pos')}} {{translate('system')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('branch/pos') || Request::is('branch/pos*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('branch.pos.index') }}">
                            <img  class="mr-3" src="{{ asset('assets/admin/img/icons/side_pos.png') }}"
                             alt="">
                                <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('pos') }}
                            </span>
                        </a>
                    </li>

                        <li class="nav-item">
                            <small class="nav-subtitle" title="{{translate('order')}}">{{translate('order')}} {{translate('section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('branch/orders/list/all') || Request::is('branch/orders/list/pending') || Request::is('branch/orders/list/confirmed')|| Request::is('branch/orders/list/processing')|| Request::is('branch/orders/list/out_of_delivery')|| Request::is('branch/orders/list/delivered')|| Request::is('branch/orders/list/returned')|| Request::is('branch/orders/list/failed')|| Request::is('branch/orders/list/canceled')|| Request::is('branch/orders/list/schedule')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('branch.orders.list',['all'])}}">
                            <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_orders.png') }}"
                            alt="">
                                <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('orders') }}
                            </span>
                        </a>
                    </li>

                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('branch/table/order/list/all') || Request::is('branch/table/order/list/confirmed')|| Request::is('branch/table/order/list/cooking')|| Request::is('branch/table/order/list/done')|| Request::is('branch/table/order/list/canceled')|| Request::is('branch/table/order/list/completed')|| Request::is('branch/table/order/running') ?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('branch.table.order.list',['all'])}}">
                            <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_tables.png') }}"alt="">
                   
                                <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('table orders') }}
                            </span>
                        </a>
                    </li>

                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('product')}} {{translate('section')}}</small>
                        </li>


                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('branch/product/list')  ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('branch.product.list')}}" title="{{ translate('Products') }}">
                                    <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_menus.png') }}"
                                        alt="">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Products') }}
                                    </span>

                                </a>
                            </li>

                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('table')}} {{translate('section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('branch/table/list') || Request::is('branch/table/index') || Request::is('branch/promotion/*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('branch.table.list')}}" title="{{ translate('table') }}">
                            <img class="mr-3"
                                src="{{ asset('assets/admin/img/icons/side_tables.png') }}"
                                alt="">
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('tables') }}
                            </span>
                        </a>
                    </li>

                        <li class="nav-item">
                            <small
                                class="nav-subtitle">{{translate('kitchen')}} {{translate('section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('branch/kitchen/*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link "
                                    href="{{ route('branch.kitchen.list') }}" title="{{ translate('chef') }}">
                                    <img class="mr-3" src="{{ asset('assets/admin/img/icons/side_cook.png') }}"
                                        alt="">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('chef') }}
                                    </span>
                                </a>
                        </li>

                        <li class="nav-item pt-10"></li>
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
        $(window).on('load' , function() {
            if($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content .navbar-nav > li');
        $('#search-bar-input').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush

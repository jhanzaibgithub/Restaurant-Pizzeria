@extends('layouts.branch.app')

@section('title', translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" src="{{asset('assets/admin')}}/vendor/apex/apexcharts.css"></link>
    <style>
        .card-bodyd {
            color: white;
            /* padding: 20px; */
            border-radius: 15px;
            position: absolute;
            box-sizing: border-box;
            width: 100%;
            height: 45%;
            margin: auto;
            overflow: visible;
            background-image: url('{{ asset('assets/admin/img/icons/dashboard_card1.png') }}');
            background-repeat: no-repeat;
            background-size: cover;
        }

        .data-card {
            background-color: #fff;
            border-radius: 10px;
            position: absolute;
            padding: 10px;
            width: 160px;
            height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: red;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .data-card span {
            color: #A1A5B7;
        }

        .bottom-card1 {
            position: absolute;
            top: -15px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .bottom-card2 {
            position: absolute;
            top: -15px;
            left: 195px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .bottom-card3 {
            position: relative;
            bottom: -130px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .bottom-card4 {
            position: relative;
            bottom: -112px;
            left: 181px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .data-card:not(:last-child) {
            margin-right: 10px;
        }

        ul.list-unstyled li.active a {
            color: #FE6524;
            position: relative;
        }

        ul.list-unstyled li.active a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -15px;
            width: 100%;
            height: 2px;
            background-color: currentColor;
        }

        .form-li {
            margin-right: 3%;
            /* margin-left: auto; */
        }

        .date-input {
            display: none;
        }

        .dropdown-item label {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* ______________________________________________ */
        .custom-card {
            position: relative;
            overflow: hidden;
        }

        .upper-section {
            background: linear-gradient(to bottom right, #FE6524, #FF9D28);
            height: 40%;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .upper-section img {
            max-width: 100%;
            height: 100%;
            display: block;
            margin-top: 0px;
        }

        .upper-section h2,
        .upper-section h5 {
            position: relative;
            top: 40%;
            left: 58%;
            transform: translate(-50%, -50%);
            color: #fff;
        }

        .lower-section {
            padding-top: 40%;
        }

        /* START  MEDIA QURIES OF DASHBOARD CARD 1 */
        @media (min-width: 375px) and (max-width: 425px) {

            .upper-section h2,
            .upper-section h5 {
                position: relative;
                top: 25%;
                left: 58%;
                transform: translate(-50%, -50%);
                color: #fff;
            }

            .custom-card .lower-section {
                padding-top: 40%;
            }
        }

        @media (min-width: 1024px) and (max-width: 1300px) {
            .custom-card .lower-section {
                padding-top: 40%;
            }
        }

        @media (min-width: 1301px) and (max-width: 1600px) {
            .custom-card .lower-section {
                padding-top: 33%;
            }
        }

        /* END  MEDIA QURIES OF DASHBOARD CARD 1 */
    </style>
@endpush

@section('content')

<div class="ml-5">
    <div class="inline-page-menu d-flex flex-row justify-content-between align-items-center">
        <ul class="list-unstyled">
            <div>
                <li class="{{ Request::is('branch') ? 'active' : '' }}"><a
                        href="{{ route('branch.dashboard') }}">{{ translate('Overview') }}</a></li>
            </div>
        </ul>
        <div class="form-group mb-0 mr-2">
            <form action="{{ route('branch.dashboard') }}" method="get" id="dateRangeForm">
                <input type="hidden" name="from" id="from" value="">
                <input type="hidden" name="to" id="to" value="">
                <input type="text" name="date_range" class="form-control" id="config-demo" value="">
            </form>
        </div>
    </div>
</div>
<hr class="li_hr-top">
    <div class="content container-fluid">

        <div class="row">
            <!-- Start Card -->
            <div class="card h-100 col-lg-4 col-md-6 col-sm-6 mb-3 custom-card">
                <div class="upper-section">

                    <h2 class="mb-0">Business Analytics</h2>
                    <h5 style="color: #E1E3EA">You have following achieved your goals!</h5>
                    <img src="{{ asset("assets/admin/img/icons/dashboard_card01.png") }}" alt="">
                </div>
                <div class="lower-section card-body">
                    <div class="row">
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                    <div class="mb-3 font-size-lg text-success">
                                        <strong><i class="tio-trending-up  display-4"></i></strong>
                                    </div>
                                    {{-- <div class="mb-3 font-size-lg text-danger">
                                        <strong><i class="tio-trending-down display-4"></i></strong>
                                    </div> --}}
                                    <h3 class="mb-0">
                                        {{-- {{ \App\CentralLogics\Helpers::currency_symbol() }} --}}
                                        $45,54
                                    </h3>
                                    <p style="color:#A1A5B7; font-size:0.836rem;">Earnings</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card mb-3">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                    {{-- <div class="mb-3 font-size-lg text-success">
                                        <strong><i class="tio-trending-up  display-4"></i></strong>
                                    </div> --}}
                                    <div class="mb-3 font-size-lg text-danger">
                                        <strong><i class="tio-trending-down display-4"></i></strong>
                                    </div>
                                    <h3 class="mb-0">
                                        $545
                                    </h3>
                                    <p style="color:#A1A5B7; font-size:0.836rem;">Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                    <div class="mb-3 font-size-lg text-success">
                                        <strong><i class="tio-trending-up  display-4"></i></strong>
                                    </div>
                                    {{-- <div class="mb-3 font-size-lg text-danger">
                                        <strong><i class="tio-trending-down display-4"></i></strong>
                                    </div> --}}
                                    <h3 class="mb-0">
                                        87
                                    </h3>
                                    <p style="color:#A1A5B7; font-size:0.836rem;">Branches</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                    <div class="mb-3 font-size-lg text-success">
                                        <strong><i class="tio-trending-up  display-4"></i></strong>
                                    </div>
                                    {{-- <div class="mb-3 font-size-lg text-danger">
                                        <strong><i class="tio-trending-down display-4"></i></strong>
                                    </div> --}}
                                    <h3 class="mb-0">
                                        90
                                    </h3>
                                    <p style="color:#A1A5B7; font-size:0.836rem;">Employees</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            <div class="col-lg-8 col-md-12 mb-3">
                <!-- Card -->
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                {{ translate('Business_Growth') }}
                            </h4>
                            <ul class="option-select-btn">
                                <li>
                                    <label>
                                        <input type="radio" name="statistics" hidden="">
                                        <span data-order-type="yearEarn"
                                            onclick="earningGrowthStatisticsUpdate(this)">{{ translate('Revenue') }}</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics" hidden="" checked="">
                                        <span data-order-type="MonthOrder"
                                            onclick="earningGrowthStatisticsUpdate(this)">{{ translate('sales') }}</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics" hidden="">
                                        <span data-order-type="monthRegisterCustomer"
                                            onclick="earningGrowthStatisticsUpdate(this)">{{ translate('customers') }}</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics" hidden="">
                                        <span data-order-type="monthBranches"
                                            onclick="earningGrowthStatisticsUpdate(this)">{{ translate('Branches') }}</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div id="updatingOrderData" class="custom-chart mt-2">
                            <div id="order-statistics-line-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!--Sales Growth Graph End Card -->
            <!-- Card -->
            <div class="card h100">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            {{ translate('Sale_Growth') }}
                        </h4>
                        <ul class="option-select-btn">
                            <li>
                                <label>
                                    <input type="radio" name="statistics2" hidden="" checked="">
                                    <span data-earn-type="monthOrders"
                                        onclick="saleGrowthStatisticsUpdate(this)">{{ translate('Orders') }}</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="statistics2" hidden="">
                                    <span data-earn-type="monthProducts"
                                        onclick="saleGrowthStatisticsUpdate(this)">{{ translate('Products') }}</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="statistics2" hidden="">
                                    <span data-earn-type="monthMeals"
                                        onclick="saleGrowthStatisticsUpdate(this)">{{ translate('Meals') }}</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div id="updatingData" class="custom-chart mt-2">
                        <div id="line-adwords"></div>
                    </div>
                </div>
            </div>
            <!-- End Card -->
             <!--Earning Statics Graph End Card -->

             <div class="row g-2 my-3">
                <div class="col-md-12 col-lg-8">
                    <div class="card h-100">
                        @include('admin-views.partials._top-selling-products', [
                            'top_sell' => $data['top_sell_products'],
                        ])
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        @include('admin-views.partials._reviews')
                    </div>
                </div>
            </div>


            {{-- ********** Start  Map  --}}

        <div>
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title c1">{{translate('welcome')}} {{translate('to')}} {{auth('branch')->user()->name}} {{translate('branch')}}</h1>
                    <p class="text-dark font-weight-semibold">{{translate('Monitor_your_business_analytics_and_statistics')}}</p>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card card-body mb-3">
            <div class="row justify-content-between align-items-center g-2 mb-3">
                <div class="col-auto">
                    <h4 class="d-flex align-items-center gap-10 mb-0">
                        <img width="20" class="avatar-img rounded-0" src="{{asset('assets/admin/img/icons/business_analytics.png')}}" alt="Business Analytics">
                        {{translate('Business_Analytics')}}
                    </h4>
                </div>
                <div class="col-auto">
                    <select class="custom-select  min-w200" name="statistics_type" onchange="order_stats_update(this.value)">
                        <option value="overall" {{session()->has('statistics_type') && session('statistics_type') == 'overall'?'selected':''}}>
                            {{translate('Overall Statistics')}}
                        </option>
                        <option value="today" {{session()->has('statistics_type') && session('statistics_type') == 'today'?'selected':''}}>
                            {{\App\CentralLogics\translate("Today")."'s"}} {{\App\CentralLogics\translate("Statistics")}}
                        </option>
                        <option value="this_month" {{session()->has('statistics_type') && session('statistics_type') == 'this_month'?'selected':''}}>
                            {{\App\CentralLogics\translate("This Month")."'s"}} {{\App\CentralLogics\translate("Statistics")}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row g-2" id="order_stats">
                @include('branch-views.partials._dashboard-order-stats',['data'=>$data])
            </div>
        </div>
        <!-- End Card -->

        <!-- <div class="row gx-2 gx-lg-3 d-none">
            <div class="col-lg-12 mb-3 mb-lg-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 mb-3 border-bottom">
                                <h5 class="card-header-title float-left mb-2">
                                    <i style="font-size: 30px" class="tio-chart-pie-1"></i>
                                    {{translate('Earning statistics for business analytics')}}
                                </h5>
                                <h5 class="card-header-title float-right mb-2">{{translate('Monthly Earning')}}
                                    <i style="font-size: 30px" class="tio-chart-bar-2"></i>
                                </h5>
                            </div>
                            <div class="col-md-4 graph-border-1">
                                <div class="mt-2 center-div">
                                      <span class="h6 mb-0">
                                          <i class="legend-indicator" style="background-color: #B6C867!important;"></i>
                                         {{ translate('earnings') }} : {{ \App\CentralLogics\Helpers::set_symbol(array_sum($earning)) }}
                                      </span>
                                </div>
                            </div>
                        </div>

                        <div class="chartjs-custom">
                            <canvas id="updatingData" style="height: 20rem;"
                                    data-hs-chartjs-options='{
                            "type": "bar",
                            "data": {
                              "labels": ["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                              "datasets": [
                             {
                                "data": [{{$earning[1]}},{{$earning[2]}},{{$earning[3]}},{{$earning[4]}},{{$earning[5]}},{{$earning[6]}},{{$earning[7]}},{{$earning[8]}},{{$earning[9]}},{{$earning[10]}},{{$earning[11]}},{{$earning[12]}}],
                                "backgroundColor": "#B6C867",
                                "borderColor": "#B6C867"
                              }]
                            },
                            "options": {
                              "scales": {
                                "yAxes": [{
                                  "gridLines": {
                                    "color": "#e7eaf3",
                                    "drawBorder": false,
                                    "zeroLineColor": "#e7eaf3"
                                  },
                                  "ticks": {
                                    "beginAtZero": true,
                                    "stepSize": 50000,
                                    "fontSize": 12,
                                    "fontColor": "#97a4af",
                                    "fontFamily": "Open Sans, sans-serif",
                                    "padding": 10,
                                    "postfix": " {{ \App\CentralLogics\Helpers::currency_symbol() }}"
                                  }
                                }],
                                "xAxes": [{
                                  "gridLines": {
                                    "display": false,
                                    "drawBorder": false
                                  },
                                  "ticks": {
                                    "fontSize": 12,
                                    "fontColor": "#97a4af",
                                    "fontFamily": "Open Sans, sans-serif",
                                    "padding": 5
                                  },
                                  "categoryPercentage": 0.5,
                                  "maxBarThickness": "10"
                                }]
                              },
                              "cornerRadius": 2,
                              "tooltips": {
                                "prefix": " ",
                                "hasIndicator": true,
                                "mode": "index",
                                "intersect": false
                              },
                              "hover": {
                                "mode": "nearest",
                                "intersect": true
                              }
                            }
                          }'></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Card -->
        <!-- End Card -->
        <div class="grid-chart mb-3">
            <!-- Card -->
            <div class="card h-100">
                <!-- Body -->
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" class="avatar-img rounded-0" src="{{asset('assets/admin/img/icons/earning_statistics.png')}}" alt="">
                            {{translate('order_statistics')}}
                        </h4>

                        <ul class="option-select-btn">
                            <li>
                                <label>
                                    <input type="radio" name="statistics" hidden checked>
                                    <span data-order-type="yearOrder"
                                          onclick="orderStatisticsUpdate(this)">{{translate('This_Year')}}</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="statistics" hidden="">
                                    <span data-order-type="MonthOrder"
                                          onclick="orderStatisticsUpdate(this)">{{translate('This_Month')}}</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="statistics" hidden="">
                                    <span data-order-type="WeekOrder"
                                          onclick="orderStatisticsUpdate(this)">{{translate('This Week')}}</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Bar Chart -->
                    <div id="updatingOrderData" class="custom-chart mt-2">
                        <div id="order-statistics-line-chart"></div>
                    </div>
                    <!-- End Bar Chart -->
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="card h-100 order-last order-lg-0">
                <div class="card-header">
                    <h4 class="d-flex text-capitalize mb-0">
                        {{translate('order_status_statistics')}}
                    </h4>
                </div>
                <!-- Body -->
                <div class="card-body">
                    <!-- Bar Chart -->
                    <div class="mt-2">
                        <div>
                            <div class="position-relative pie-chart">
                                <div id="dognut-pie"></div>
                                <!-- Total Orders -->
                                <div class="total--orders">
                                    <h3>{{$donut['pending'] + $donut['ongoing'] + $donut['delivered']+ $donut['canceled']+ $donut['returned']+ $donut['failed']}} </h3>
                                    <span>{{ translate('orders') }}</span>
                                </div>
                                <!-- Total Orders -->
                            </div>
                            <div class="apex-legends">
                                <div class="before-bg-pending">
                                    <span>{{ translate('pending') }} ({{$donut['pending']}})</span>
                                </div>
                                <div class="before-bg-ongoing">
                                    <span>{{ translate('ongoing') }} ({{$donut['ongoing']}})</span>
                                </div>
                                <div class="before-bg-delivered">
                                    <span>{{ translate('delivered') }} ({{$donut['delivered']}})</span>
                                </div>
                                <div class="before-bg-17202A">
                                    <span>{{ translate('canceled') }} ({{$donut['canceled']}})</span>
                                </div>
                                <div class="before-bg-21618C">
                                    <span>{{ translate('returned') }} ({{$donut['returned']}})</span>
                                </div>
                                <div class="before-bg-27AE60">
                                    <span>{{ translate('failed_to_deliver') }} ({{$donut['failed']}})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Bar Chart -->
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="card h100">
                <!-- Body -->
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" class="avatar-img rounded-0" src="{{asset('assets/admin/img/icons/earning_statistics.png')}}" alt="">
                            {{translate('earning_statistics')}}
                        </h4>
                        <ul class="option-select-btn">
                            <li>
                                <label>
                                    <input type="radio" name="statistics2" hidden="" checked="">
                                    <span data-earn-type="yearEarn"
                                          onclick="earningStatisticsUpdate(this)">{{translate('This_Year')}}</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="statistics2" hidden="">
                                    <span data-earn-type="MonthEarn"
                                          onclick="earningStatisticsUpdate(this)">{{translate('This_Month')}}</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="statistics2" hidden="">
                                    <span data-earn-type="WeekEarn"
                                          onclick="earningStatisticsUpdate(this)">{{translate('This Week')}}</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Bar Chart -->
                    <div id="updatingData" class="custom-chart mt-2">
                        <div id="line-adwords"></div>
                    </div>
                    <!-- End Bar Chart -->
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="card h100 recent-orders">
                <!-- Recent Orders -->
                <div class="card-header d-flex justify-content-between gap-10">
                    <h5 class="mb-0">{{translate('Recent_Orders')}}</h5>
                    <a href="{{ route('branch.orders.list', ['status' => 'all']) }}" class="btn-link">{{translate('View_All')}}</a>
                </div>
                <div class="card-body">
                    <ul class="common-list">
                        @foreach($data['recent_orders'] as $recent)
                            <li class="pt-0 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                <div class="order-info ">
                                    <h5><a href="{{route('branch.orders.details', ['id' => $recent->id])}}" class="text-dark" >{{translate('Order')}}# {{$recent->id}}</a></h5>
                                    <p>{{\Illuminate\Support\Carbon::parse($recent->created_at)->format('d-m-y, h:m A')}}</p>
                                </div>
                                @if($recent['order_status'] == 'pending')
                                    <span
                                        class="status text-primary">{{translate($recent['order_status'])}}</span>
                                @elseif($recent['order_status'] == 'delivered')
                                    <span
                                        class="status text-success">{{translate($recent['order_status'])}}</span>
                                @elseif($recent['order_status'] == 'confirmed' || $recent['order_status'] == 'processing' || $recent['order_status'] == 'out_for_delivery')
                                    <span
                                        class="status text-warning">{{translate($recent['order_status'])}}</span>
                                @elseif($recent['order_status'] == 'canceled' || $recent['order_status'] == 'failed')
                                    @if($recent['order_status'] == 'failed')
                                        <span
                                            class="status text-warning">{{translate('failed_to_deliver')}}</span>
                                    @else
                                        <span
                                            class="status text-warning">{{translate($recent['order_status'])}}</span>
                                    @endif

                                @elseif($recent['order_status'] == 'cooking')
                                    <span
                                        class="status text-info">{{translate($recent['order_status'])}}</span>
                                @elseif($recent['order_status'] == 'completed')
                                    <span
                                        class="status text-success">{{translate($recent['order_status'])}}</span>
                                @else
                                    <span
                                        class="status text-primary">{{translate($recent['order_status'])}}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- End Recent Orders -->
            </div>
            <!-- End Card -->
        </div>

    </div>
@endsection

@push('script')
    <script src="{{asset('assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{asset('assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="{{asset('assets/admin')}}/vendor/apex/apexcharts.min.js"></script>
@endpush


@push('script_2')
 {{--  __________START_______Header DATA_________________ --}}
 <script type="text/javascript">
    $(document).ready(function() {
        var defaultStartDate = moment().subtract(0, 'days');
        var defaultEndDate = moment();
        var options = {
            startDate: defaultStartDate,
            endDate: defaultEndDate,
            showDropdowns: true,
            showWeekNumbers: true,
            showISOWeekNumbers: true,
            timePicker: false,
            autoApply: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ]
            },
            locale: {
                direction: 'ltr',
                format: 'MM/DD/YYYY',
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                    'September', 'October', 'November', 'December'
                ],
                firstDay: 1
            },
            linkedCalendars: true,
            autoUpdateInput: true,
            showCustomRangeLabel: true,
            alwaysShowCalendars: true
        };

        $('#config-demo').daterangepicker(options, function(start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
                'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        }).on('apply.daterangepicker', function (ev, picker) {
                // Set the values of the hidden input fields
                $('#from').val(picker.startDate.format('YYYY-MM-DD'));
                $('#to').val(picker.endDate.format('YYYY-MM-DD'));

                // Submit the form
                $('#dateRangeForm').submit();
            });

    });
</script>

    {{-- Order Statistics Line Chart --}}
    <script>
        var OSDCoptions = {
            chart: {
                height: 328,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false,
                },
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['rgba(255, 111, 112, 0.5)', '#107980'],
            series: [{
                name: "Order",
                    data: [{{$order_statistics_chart[1]}}, {{$order_statistics_chart[2]}}, {{$order_statistics_chart[3]}}, {{$order_statistics_chart[4]}},
                {{$order_statistics_chart[5]}}, {{$order_statistics_chart[6]}}, {{$order_statistics_chart[7]}}, {{$order_statistics_chart[8]}},
                {{$order_statistics_chart[9]}}, {{$order_statistics_chart[10]}}, {{$order_statistics_chart[11]}}, {{$order_statistics_chart[12]}}]
                },
            ],
            markers: {
                size: 2,
                strokeWidth: 0,
                hover: {
                    size: 5
                }
            },
            grid: {
                show: true,
                padding: {
                    bottom: 0
                },
                borderColor: "rgba(180, 208, 224, 0.5)",
                strokeDashArray: 7,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            xaxis: {
                tooltip: {
                    enabled: false
                }
            },
            legend: {
                show: false,
                position: 'top',
                horizontalAlign: 'right',
                offsetY: 10
            }
        }

        var chartLine = new ApexCharts(document.querySelector('#order-statistics-line-chart'), OSDCoptions);
        chartLine.render();
    </script>

    {{-- Earning Statistics Line Chart --}}
    <script>
        var earningOptions = {
            chart: {
                height: 328,
                type: 'line',
                zoom: {
                enabled: false
                },
                toolbar: {
                    show: false,
                },
            },
            stroke: {
                curve: 'straight',
                width: 3
            },
            colors: ['rgba(255, 111, 112, 0.5)', '#107980'],
            series: [{
                name: "Earning",
                data: [{{$earning[1]}}, {{$earning[2]}}, {{$earning[3]}}, {{$earning[4]}}, {{$earning[5]}}, {{$earning[6]}},
                    {{$earning[7]}}, {{$earning[8]}}, {{$earning[9]}}, {{$earning[10]}}, {{$earning[11]}}, {{$earning[12]}}]
                },
            ],
            markers: {
                size: 2,
                strokeWidth: 0,
                hover: {
                    size: 5
                }
            },
            grid: {
                show: true,
                padding: {
                    bottom: 0
                },
                borderColor: "rgba(180, 208, 224, 0.5)",
                strokeDashArray: 7,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            xaxis: {
                tooltip: {
                    enabled: false
                }
            },
            legend: {
                show: false,
                position: 'top',
                horizontalAlign: 'right',
                offsetY: 10
            }
        }

        var chartLine = new ApexCharts(document.querySelector('#line-adwords'), earningOptions);
        chartLine.render();
    </script>
    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

    </script>

    <script>
        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('branch.order-stats')}}",
                type: "post",
                data: {
                    statistics_type: type,
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    $('#order_stats').html(data.view)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>

    <script>
        function orderStatisticsUpdate(t) {
            let value = $(t).attr('data-order-type');
            console.log(value);

            $.ajax({
                url: '{{route('branch.order-statistics')}}',
                type: 'GET',
                data: {
                    type: value
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (response_data) {
                    console.log(response_data);
                    document.getElementById("order-statistics-line-chart").remove();
                    let graph = document.createElement('div');
                    graph.setAttribute("id", "order-statistics-line-chart");
                    document.getElementById("updatingOrderData").appendChild(graph);

                    var options = {
                        series: [{
                            name: "Orders",
                            data: response_data.orders,
                        }],
                        chart: {
                            height: 316,
                            type: 'line',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false,
                            },
                            markers: {
                                size: 5,
                            }
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        colors: ['rgba(255, 111, 112, 0.5)', '#107980'],
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        xaxis: {
                            categories: response_data.orders_label,
                        },
                        grid: {
                            show: true,
                            padding: {
                                bottom: 0
                            },
                            borderColor: "rgba(180, 208, 224, 0.5)",
                            strokeDashArray: 7,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            }
                        },
                        yaxis: {
                            tickAmount: 4,
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#order-statistics-line-chart"), options);
                    chart.render();
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }

        function earningStatisticsUpdate(t) {
            let value = $(t).attr('data-earn-type');
            $.ajax({
                url: '{{route('branch.earning-statistics')}}',
                type: 'GET',
                data: {
                    type: value
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (response_data) {
                    document.getElementById("line-adwords").remove();
                    let graph = document.createElement('div');
                    graph.setAttribute("id", "line-adwords");
                    document.getElementById("updatingData").appendChild(graph);

                    var optionsLine = {
                        chart: {
                            height: 328,
                            type: 'line',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false,
                            },
                        },
                        stroke: {
                            curve: 'straight',
                            width: 2
                        },
                        colors: ['rgba(255, 111, 112, 0.5)', '#107980'],
                        series: [{
                            name: "Earning",
                            data: response_data.earning,
                        }],
                        markers: {
                            size: 6,
                            strokeWidth: 0,
                            hover: {
                                size: 9
                            }
                        },
                        grid: {
                            show: true,
                            padding: {
                                bottom: 0
                            },
                            borderColor: "rgba(180, 208, 224, 0.5)",
                            strokeDashArray: 7,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            }
                        },
                        labels: response_data.earning_label,
                        xaxis: {
                            tooltip: {
                                enabled: false
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            offsetY: -20
                        }
                    }
                    var chartLine = new ApexCharts(document.querySelector('#line-adwords'), optionsLine);
                    chartLine.render();
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>
    <!-- Dognut Pie Chart -->
    <script>
        var options = {
            series: [{{$donut['ongoing']}}, {{$donut['delivered']}}, {{$donut['pending']}}, {{$donut['canceled']}}, {{$donut['returned']}}, {{$donut['failed']}}],
            chart: {
                width: 256,
                type: 'donut',
            },
            labels: ['{{ translate('ongoing') }}', '{{ translate('delivered') }}', '{{ translate('pending') }}', '{{translate('canceled')}}', '{{translate('returned')}}', '{{translate('failed_to_deliver')}}'],
            dataLabels: {
                enabled: false,
                style: {
                    colors: ['#803838', '#27AE60', '#FF6F70', '#17202A', '#21618C', '#FF0000']
                }
            },
            responsive: [{
                breakpoint: 1650,
                options: {
                    chart: {
                        width: 250
                    },
                }
            }],
            colors: ['#803838', '#27AE60', '#FF6F70', '#17202A', '#21618C', '#FF0000'],
            fill: {
                colors: ['#803838', '#27AE60', '#FF6F70', '#17202A', '#21618C', '#FF0000']
            },
            legend: {
                show: false
            },
        };

        var chart = new ApexCharts(document.querySelector("#dognut-pie"), options);
        chart.render();

    </script>
    <!-- Dognut Pie Chart -->
@endpush

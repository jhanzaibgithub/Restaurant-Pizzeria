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
                        <div class="input-group">
                            <input type="text" name="date_range" class="form-control" id="config-demo" value="">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <img src="{{ asset('assets/admin/img/icons/filter.png') }}" alt=""
                                        srcset="" style="width: 80%;">
                                </span>
                            </div>
                        </div>
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
                    <img src="{{ asset('assets/admin/img/icons/dashboard_card01.png') }}" alt="">
                </div>
                <div class="lower-section card-body">
                    <div class="row">
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                        @if ($analytics['earnings_trend'] == 'up')
                                            <div class="mb-3 font-size-lg text-success">
                                                <strong><i class="tio-trending-up  display-4"></i></strong>
                                            </div>
                                        @else
                                            <div class="mb-3 font-size-lg text-danger">
                                                <strong><i class="tio-trending-down display-4"></i></strong>
                                            </div>
                                        @endif
                                        <h3 class="mb-0">
                                            {{ \App\CentralLogics\Helpers::currency_symbol() }}
                                            {{ round($analytics['total_earning'], 0) }}
                                        </h3>
                                    <p style="color:#A1A5B7; font-size:0.836rem;">Earnings</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card mb-3">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                    @if ($analytics['product_trend'] == 'up')
                                            <div class="mb-3 font-size-lg text-success">
                                                <strong><i class="tio-trending-up  display-4"></i></strong>
                                            </div>
                                        @else
                                            <div class="mb-3 font-size-lg text-danger">
                                                <strong><i class="tio-trending-down display-4"></i></strong>
                                            </div>
                                        @endif
                                        <h3 class="mb-0">
                                            {{ $analytics['total_products'] }}
                                        </h3>
                                    <p style="color:#A1A5B7; font-size:0.836rem;">Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                    @if ($analytics['total_order_trend'] == 'up')
                                            <div class="mb-3 font-size-lg text-success">
                                                <strong><i class="tio-trending-up  display-4"></i></strong>
                                            </div>
                                        @else
                                            <div class="mb-3 font-size-lg text-danger">
                                                <strong><i class="tio-trending-down display-4"></i></strong>
                                            </div>
                                        @endif
                                        <h3 class="mb-0">
                                            {{ $analytics['total_orders'] }}
                                        </h3>
                                    <p style="color:#A1A5B7; font-size:0.836rem;">Total Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-4 mb-3">
                            <div class="card">
                                <div style="padding: 0.3125rem;" class="card-body ml-3">
                                    <div class="mb-3 font-size-lg text-danger">
                                        <strong><i class="tio-trending-down display-4"></i></strong>
                                    </div>
                                   
                                    <h3 class="mb-0">
                                        0
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
                        @include('branch-views.partials._top-selling-products')
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        @include('branch-views.partials._reviews')
                    </div>
                </div>
            </div>
            {{-- ********** Start  Map  --}}
            <!-- Card -->

            <div class="card h-100 order-last order-lg-0">
                <div class="card-header">
                    <h4 class="d-flex text-capitalize mb-0">
                        {{ translate('Sale Heat Map') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mt-2">
                        <div>
                            <div id="map" style="width: 100%; height: 315px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->


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
{{-- **********************Start For Newly dashboard Added --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var dineInCard = document.getElementById("DineInCard");
        dineInCard.click();
    });

    function showDataDine(category) {
        resetCardStyles();
        $('#allProductsData').html(`
        <div class="card-body">
            <div class="d-flex flex-column gap-3">
                    @foreach ($data['top_sell_products'] as $key => $item)
                        @if (isset($item->product))
                        <a  class="d-flex justify-content-around align-items-center text-dark" href='{{ route('branch.product.list') }}'">
                            <div style="width:50%" class="media align-items-center gap-2  flex-grow-1">
                                <img class="rounded avatar avatar-lg" src="{{ asset('/storage/product') . '/' . $item->product->image ?? '' }}" onerror="this.src='{{ asset('assets/admin/img/400x400/img2.jpg') }}'" alt="{{ $item->product->name }} image">
                                <div class="d-flex flex-column">
                                <span class="font-weight-semibold text-capitalize media-body">{{ substr($item->product['name'], 0, 18) }} {{ strlen($item->product['name']) > 18 ? '...' : '' }}</span>
                                <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">#{{ $item->product['id'] }}</span>
                                </div>
                            </div>
                            <div class="media align-items-center gap-2  flex-grow-1">
                                <div class="d-flex flex-column">
                                <span class="font-weight-semibold text-capitalize media-body">{{ $item->count }}</span>
                                <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">sale</span>
                                </div>
                            </div>
                            <div class="media align-items-center gap-2  flex-grow-1">
                                <div class="d-flex flex-column">
                                <span class="font-weight-semibold text-capitalize media-body">{{ \App\CentralLogics\Helpers::currency_symbol() }}{{ $item->product['price'] }}</span>
                                <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">Price</span>
                                </div>
                            </div>
                            <div class="media align-items-center gap-2  flex-grow-1">
                                <div class="d-flex flex-column">
                                <img class="img-fluid" src="{{ asset('assets/admin/img/dashboard/dashboard_rating.png') }}" >
                                <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">Rating</span>
                                </div>
                            </div>
                            <div class="media align-items-center gap-2  flex-grow-1">
                                <div class="d-flex flex-column">
                                        <button class="btn btn-sm btn-secondary square-btn" href="">
                                    <i class="tio-arrow-forward"></i>
                                    </button>
                                </div>
                            </div>

                        </a>
                        @endif
                    @endforeach 
            </div>
        </div>
        `);
        $('#allMealsData').html('');
        $('#homeDeliveryData').html('');

        $('#DineInCard').find('.card-body').css({
            'border-top': '2px solid #FE6524',
            'border-right': '2px solid #FE6524',
            'border-bottom': '5px solid #FE6524',
            'border-left': '2px solid #FE6524',
            'border-radius': '8px',
        });
        $('#DineInCard').find('.card-title').css({
            'color': ' #FE6524',
        });
        $('#DineInCard').find('img').attr('src', '{{ asset('assets/admin/img/dashboard/dashboard_product.png') }}');

    }

    function showDataTake(category) {
        resetCardStyles();
        $('#allMealsData').html(`
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @foreach ($data['meal_products'] as $key => $item)
                            @if (isset($item->product))
                            <a  class="d-flex justify-content-around align-items-center text-dark" href='{{ route('admin.product.list') }}'">
                                <div style="width:50%" class="media align-items-center gap-2 flex-grow-1">
                                    <img class="rounded avatar avatar-lg" src="{{ asset('/storage/product') . '/' . $item->product->image ?? '' }}" onerror="this.src='{{ asset('assets/admin/img/400x400/img2.jpg') }}'" alt=" image">
                                    <div class="d-flex flex-column">
                                    <span class="font-weight-semibold text-capitalize media-body">{{ substr($item->product['name'], 0, 18) }} {{ strlen($item->product['name']) > 18 ? '...' : '' }}</span>
                                    <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">#{{ $item->product['id'] }}</span>
                                    </div>
                                </div>
                                <div class="media align-items-center gap-2 flex-grow-1">
                                    <div class="d-flex flex-column">
                                    <span class="font-weight-semibold text-capitalize media-body">{{ $item->count }}</span>
                                    <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">sale</span>
                                    </div>
                                </div>
                                <div class="media align-items-center gap-2 flex-grow-1">
                                    <div class="d-flex flex-column">
                                    <span class="font-weight-semibold text-capitalize media-body">{{ \App\CentralLogics\Helpers::currency_symbol() }}{{ $item->product['price'] }}</span>
                                    <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">Price</span>
                                    </div>
                                </div>
                                <div class="media align-items-center gap-2 flex-grow-1">
                                    <div class="d-flex flex-column">
                                    <img class="img-fluid" src="{{ asset('assets/admin/img/dashboard/dashboard_rating.png') }}" >
                                    <span style="color:#A1A5B7;" class="font-weight-semibold text-capitalize media-body">Rating</span>
                                    </div>
                                </div>
                                <div class="media align-items-center gap-2 flex-grow-1">
                                    <div class="d-flex flex-column">
                                            <button class="btn btn-sm btn-secondary square-btn" href="">
                                        <i class="tio-arrow-forward"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
                            @endif
                        @endforeach
                </div>
            </div>
        `);

        $('#allProductsData').html('');
        $('#homeDeliveryData').html('');
        $('#takeAwayCard').find('.card-body').css({
            'border-top': '2px solid #FE6524',
            'border-right': '2px solid #FE6524',
            'border-bottom': '5px solid #FE6524',
            'border-left': '2px solid #FE6524',
            'border-radius': '8px',
        });
        $('#takeAwayCard').find('.card-title').css({
            'color': ' #FE6524',
        });
        $('#takeAwayCard').find('img').attr('src', '{{ asset('assets/admin/img/dashboard/dashboard_meal.png') }}');
    }



    function resetCardStyles() {
        $('.card-sellingP .card-body').css({
            'border': 'dashed',
            'border-radius': '0',
        });

        $('.card-sellingP .card-title').css({
            'color': '',
        });

        $('#DineInCard img').attr('src', '{{ asset('assets/admin/img/dashboard/dashboard_product_grey.png') }}');
        $('#takeAwayCard img').attr('src', '{{ asset('assets/admin/img/dashboard/dashboard_meal_grey.png') }}');

    }

    function updateCardStyles(cardId, borderColor, imagePath) {
        // Change the styles for the clicked card
        $(`#${cardId} .card-body`).css({
            'border': `2px solid ${borderColor}`,
            'border-radius': '8px',
        });

        $(`#${cardId} .card-title`).css({
            'color': borderColor,
        });

        $(`#${cardId} img`).attr('src', imagePath);
    }
</script>
{{--  ________END_________ON CLICK NEW DATA SHOW_________________ --}}
{{--  ________START_________FEEDBACK GRAPH_________________ --}}
<script>
    Chart.defaults.doughnutLabels = Chart.helpers.clone(Chart.defaults.doughnut);
    var helpers = Chart.helpers;
    var defaults = Chart.defaults;
    Chart.controllers.doughnutLabels = Chart.controllers.doughnut.extend({
        updateElement: function(arc, index, reset) {
            var _this = this;
            var chart = _this.chart,
                chartArea = chart.chartArea,
                opts = chart.options,
                animationOpts = opts.animation,
                arcOpts = opts.elements.arc,
                centerX = (chartArea.left + chartArea.right) / 2,
                centerY = (chartArea.top + chartArea.bottom) / 2,
                startAngle = opts.rotation, // non reset case handled later
                endAngle = opts.rotation, // non reset case handled later
                dataset = _this.getDataset(),
                circumference = reset && animationOpts.animateRotate ? 0 : arc.hidden ? 0 : _this
                .calculateCircumference(dataset.data[index]) * (opts.circumference / (2.0 * Math.PI)),
                outerRadius = reset && animationOpts.animateScale ? 0 : _this.outerRadius,
                innerRadius = outerRadius * 0.8,
                custom = arc.custom || {},
                valueAtIndexOrDefault = helpers.getValueAtIndexOrDefault;
            helpers.extend(arc, {
                // Utility
                _datasetIndex: _this.index,
                _index: index,
                // Desired view properties
                _model: {
                    x: centerX + chart.offsetX,
                    y: centerY + chart.offsetY,
                    startAngle: startAngle,
                    endAngle: endAngle,
                    circumference: circumference,
                    outerRadius: outerRadius,
                    innerRadius: innerRadius,
                    label: valueAtIndexOrDefault(dataset.label, index, chart.data.labels[index])
                },
                draw: function() {
                    var ctx = this._chart.ctx,
                        vm = this._view,
                        sA = vm.startAngle,
                        eA = vm.endAngle,
                        opts = this._chart.config.options;
                    var labelPos = this.tooltipPosition();
                    var segmentLabel = vm.circumference / opts.circumference * 100;
                    ctx.beginPath();
                    ctx.arc(vm.x, vm.y, vm.outerRadius, sA, eA);
                    ctx.arc(vm.x, vm.y, vm.innerRadius, eA, sA, true);
                    ctx.closePath();
                    ctx.strokeStyle = vm.borderColor;
                    ctx.lineWidth = vm.borderWidth;
                    ctx.fillStyle = vm.backgroundColor;
                    ctx.fill();
                    ctx.lineJoin = 'bevel';
                    if (vm.borderWidth) {
                        ctx.stroke();
                    }
                    if (vm.circumference > 0.0015) {
                        ctx.beginPath();
                        ctx.font = helpers.fontString(opts.defaultFontSize, opts
                            .defaultFontStyle, opts.defaultFontFamily);
                        ctx.fillStyle = "#190707";
                        ctx.textBaseline = "top";
                        ctx.textAlign = "center";
                        ctx.font = "20px Arial";
                        //    ctx.fillText(segmentLabel.toFixed(2) + "%", labelPos.x, labelPos.y);
                    }
                    var total = dataset.data.reduce((sum, val) => sum + val, 0);
                    ctx.fillText('', vm.x, vm.y - 40, 400);
                    ctx.fillText('Total Feedback', vm.x, vm.y - 20, 200);
                }

            });

            var model = arc._model;
            model.backgroundColor = custom.backgroundColor ? custom.backgroundColor : valueAtIndexOrDefault(
                dataset.backgroundColor, index, arcOpts.backgroundColor);
            model.hoverBackgroundColor = custom.hoverBackgroundColor ? custom.hoverBackgroundColor :
                valueAtIndexOrDefault(dataset.hoverBackgroundColor, index, arcOpts.hoverBackgroundColor);
            model.borderWidth = custom.borderWidth ? custom.borderWidth : valueAtIndexOrDefault(dataset
                .borderWidth, index, arcOpts.borderWidth);
            model.borderColor = custom.borderColor ? custom.borderColor : valueAtIndexOrDefault(dataset
                .borderColor, index, arcOpts.borderColor);
            if (!reset || !animationOpts.animateRotate) {
                if (index === 0) {
                    model.startAngle = opts.rotation;
                } else {
                    model.startAngle = _this.getMeta().data[index - 1]._model.endAngle;
                }
                model.endAngle = model.startAngle + model.circumference;
            }
            arc.pivot();
        }
    });
    var config = {
            type: 'doughnutLabels',
            data: {
                datasets: [{
                    data: [
                        {{ $positiveCount }}, {{ $negativeCount }},
                    ],
                    backgroundColor: [
                        "#50CD89",
                        "#FE6524"
                    ]
                }],
                labels: [
                    "PositiveReviews",
                    "NegativeReviews"
                ]
            },
            options: {
                circumference: Math.PI,
                rotation: 1.0 * Math.PI,
                responsive: true,
                legend: {
                    display: false,
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex,
                                array) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            return window.upDownChart.data.labels[tooltipItem.index] + " " + currentValue;
                        }
                    }
                }
            }
        };
    var ctx = document.getElementById("myChart").getContext("2d");
    window.upDownChart = new Chart(ctx, config);
</script>
<script>
    function initMap() {
        var orders_json = @json($heatmapOrders);
        console.log(orders_json);

        function generateHeatmapData(orders_json) {
            var heatMapData = [];

            var orderCounts = {};

            orders_json.forEach(function(order) {
                var deliveryAddress = order.delivery_address;
                if (!deliveryAddress || !deliveryAddress.latitude || !deliveryAddress.longitude) {
                    return;
                }
                var location = deliveryAddress.latitude + ',' + deliveryAddress.longitude;

                if (orderCounts[location] === undefined) {
                    orderCounts[location] = order.weight || 1;
                } else {
                    orderCounts[location] += order.weight || 1;
                }
            });

            for (var location in orderCounts) {
                var latLng = location.split(',');
                var latitude = parseFloat(latLng[0]);
                var longitude = parseFloat(latLng[1]);
                var weight = orderCounts[location];
                heatMapData.push({
                    location: new google.maps.LatLng(latitude, longitude),
                    weight: weight
                });
            }

            return heatMapData;
        }

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 33.6141,
                lng: 73.1308
            },
            zoom: 15,
            mapTypeId: 'satellite'
        });

        var heatMapData = generateHeatmapData(orders_json);

        var heatmap = new google.maps.visualization.HeatmapLayer({
            data: heatMapData,
            map: map,
            radius: 30,
            opacity: 1
        });
    }
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ $mapApiClientKey }}&libraries=visualization&callback=initMap">
</script>
{{--   **********************ENd For Newly dashboard Added --}}

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
    <script>
        function earningGrowthStatisticsUpdate(t) {
            let value = $(t).attr('data-order-type');
            console.log(value);
            $.ajax({
                url: '{{ route('branch.order-statistics') }}',
                type: 'GET',
                data: {
                    type: value
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(response_data) {
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
                        colors: ['#FE6524', '#FE6524'],
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
                                    show: false
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
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        function saleGrowthStatisticsUpdate(t) {
            let value = $(t).attr('data-earn-type');
            console.log(value);

            $.ajax({
                url: '{{ route('branch.sale-statistics') }}',
                type: 'GET',
                data: {
                    type: value
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(response_data) {
                    console.log(response_data);
                    document.getElementById("line-adwords").remove();
                    let graph = document.createElement('div');
                    graph.setAttribute("id", "line-adwords");
                    document.getElementById("updatingData").appendChild(graph);

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
                        colors: ['#FE6524', '#FE6524'],
                        stroke: {
                            curve: 'straight',
                            width: 3
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
                                    show: false
                                }
                            }
                        },
                        yaxis: {
                            tickAmount: 4,
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#line-adwords"), options);
                    chart.render();
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }
    </script>
@endpush

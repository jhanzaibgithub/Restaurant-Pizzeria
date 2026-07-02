@extends('layouts.admin.app')

@section('title', translate('Order Report'))

@push('css_or_js')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endpush

@section('content')
<div class="ml-5">
    @include('admin-views.report.partials._reportCAL-setup-inline-menu')
</div>
<hr class="li_hr-top">
<div class="content container-fluid">

    <div class="card">
        <div class="card-body">
            <div class="media flex-column flex-sm-row flex-wrap align-items-sm-center gap-4">
                <div class="media-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="">
                            <h2 class="page-header-title mb-2">{{ translate('order_Report_Overview') }}</h2>
                            <div class="">
                                <div class="mb-1">
                                    <span>{{ translate('admin') }}:</span>
                                    <a href="#">{{ auth('admin')->user()->f_name . ' ' . auth('admin')->user()->l_name }}</a>
                                </div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <div class="">{{ translate('date') }}</div>
                                    <div>
                                        ( {{ date('Y-m-d ' . config('time_format'), strtotime(session('from_date'))) }} -
                                        {{ date('Y-m-d ' . config('time_format'), strtotime(session('to_date'))) }} )
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.report.set-date') }}" method="post">
        <div class="card mt-3">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12">
                        @csrf
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="">
                                    <h4 class="form-label mb-0">{{ translate('Show Data by Date Range') }}</h4>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="predefinedRanges" class="form-control">
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="thisWeek">This Week</option>
                                        <option value="lastWeek">Last Week</option>
                                        <option value="thisMonth">This Month</option>
                                        <option value="lastMonth">Last Month</option>
                                        <option value="overall">Overall</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" id="customRange" name="customRange" class="form-control" placeholder="Select Date Range" style="display:none;" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="">
                                    <button type="submit" class="btn btn-primary btn-block">{{ translate('show') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- Hidden inputs for from and to dates -->
                        <input type="hidden" id="from_date" name="from">
                        <input type="hidden" id="to_date" name="to">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card d-flex">
        <div class="row">
            <!-- Cards for different order statuses -->
            <div class="col-sm-6 col-lg-3">
                <!-- Card for delivered orders -->
                <div class="card card-sm " style="background-color:#FE6524;">
                    <div class="card-body" style="padding: 30px 20px 30px 20px;">
                        <div class="row">
                            <div class="col">
                                <div class="media ">
                                    <i class="fs-4 text-white tio-shopping-cart nav-icon"></i>
                                    <div class="media-body">
                                        <h4 class="mb-1 text-white">{{ translate('delivered') }}</h4>
                                        <span class="font-size-sm text-white">
                                            <i class="text-white tio-trending-up"></i> {{ $delivered }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="js-circle"
                                    data-hs-circles-options='{
                                            "value": {{ round(($delivered / $total) * 100) }},
                                            "maxValue": 100,
                                            "duration": 2000,
                                            "isViewportInit": true,
                                            "colors": ["#e7eaf3", "green"],
                                            "radius": 25,
                                            "width": 3,
                                            "fgStrokeLinecap": "round",
                                            "textFontSize": 14,
                                            "additionalText": "%",
                                            "textClass": "circle-custom-text",
                                            "textColor": "white"
                                            }'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <!-- Card for returned orders -->
                <div class="card card-sm" style="background-color:#FF9D28;">
                    <div class="card-body" style="padding: 30px 20px 30px 20px;">
                        <div class="row">
                            <div class="col">
                                <div class="media">
                                    <i class="tio-shopping-cart-off nav-icon text-white"></i>
                                    <div class="media-body">
                                        <h4 class="mb-1 text-white">{{ translate('returned') }}</h4>
                                        <span class="font-size-sm text-white">
                                            <i class="tio-trending-up text-white"></i> {{ $returned }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="js-circle"
                                    data-hs-circles-options='{
                                "value": {{ round(($returned / $total) * 100) }},
                                "maxValue": 100,
                                "duration": 2000,
                                "isViewportInit": true,
                                "colors": ["#e7eaf3", "#ec9a3c"],
                                "radius": 25,
                                "width": 3,
                                "fgStrokeLinecap": "round",
                                "textFontSize": 14,
                                "additionalText": "%",
                                "textClass": "circle-custom-text",
                                "textColor": "white"
                                }'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <!-- Card for failed orders -->
                <div class="card card-sm" style="background-color:#3D3A38 ;">
                    <div class="card-body" style="padding: 30px 20px 30px 20px;">
                        <div class="row">
                            <div class="col">
                                <div class="media">
                                    <i class="tio-message-failed nav-icon text-white"></i>
                                    <div class="media-body">
                                        <h4 class="mb-1 text-white">{{ translate('failed') }}</h4>
                                        <span class="font-size-sm text-danger">
                                            <i class="tio-trending-up text-white"></i> {{ $failed }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="js-circle"
                                    data-hs-circles-options='{
                                "value": {{ round(($failed / $total) * 100) }},
                                "maxValue": 100,
                                "duration": 2000,
                                "isViewportInit": true,
                                "colors": ["#e7eaf3", "darkred"],
                                "radius": 25,
                                "width": 3,
                                "fgStrokeLinecap": "round",
                                "textFontSize": 14,
                                "additionalText": "%",
                                "textClass": "circle-custom-text",
                                "textColor": "white"
                                }'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <!-- Card for canceled orders -->
                <div class="card card-sm" style="background-color:#FF9D28;">
                    <div class="card-body" style="padding: 30px 20px 30px 20px;">
                        <div class="row">
                            <div class="col">
                                <div class="media">
                                    <i class="tio-flight-cancelled nav-icon text-white"></i>
                                    <div class="media-body">
                                        <h4 class="mb-1 text-white">{{ translate('canceled') }}</h4>
                                        <span class="font-size-sm text-white">
                                            <i class="tio-trending-up text-white"></i> {{ $canceled }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="js-circle"
                                    data-hs-circles-options='{
                                "value": {{ round(($canceled / $total) * 100) }},
                                "maxValue": 100,
                                "duration": 2000,
                                "isViewportInit": true,
                                "colors": ["#e7eaf3", "gray"],
                                "radius": 25,
                                "width": 3,
                                "fgStrokeLinecap": "round",
                                "textFontSize": 14,
                                "additionalText": "%",
                                "textClass": "circle-custom-text",
                                "textColor": "white"
                                }'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <canvas id="orderReportChart" style="height: 18rem;"></canvas>
        </div>
    </div>

</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('script_2')
<script>
    $(document).ready(function() {
        // Initialize Date Range Picker
        $('#customRange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            opens: 'left'
        }, function(start, end, label) {
            // Set hidden input values on custom range selection
            $('#from_date').val(start.format('YYYY-MM-DD'));
            $('#to_date').val(end.format('YYYY-MM-DD'));
        });

        // Handle predefined ranges
        $('#predefinedRanges').change(function() {
            let range = $(this).val();
            let startDate, endDate;

            if (range === 'today') {
                startDate = moment().format('YYYY-MM-DD');
                endDate = moment().format('YYYY-MM-DD');
            } else if (range === 'yesterday') {
                startDate = moment().subtract(1, 'days').format('YYYY-MM-DD');
                endDate = moment().subtract(1, 'days').format('YYYY-MM-DD');
            } else if (range === 'thisWeek') {
                startDate = moment().startOf('week').format('YYYY-MM-DD');
                endDate = moment().endOf('week').format('YYYY-MM-DD');
            } else if (range === 'lastWeek') {
                startDate = moment().subtract(1, 'weeks').startOf('week').format('YYYY-MM-DD');
                endDate = moment().subtract(1, 'weeks').endOf('week').format('YYYY-MM-DD');
            } else if (range === 'thisMonth') {
                startDate = moment().startOf('month').format('YYYY-MM-DD');
                endDate = moment().endOf('month').format('YYYY-MM-DD');
            } else if (range === 'lastMonth') {
                startDate = moment().subtract(1, 'months').startOf('month').format('YYYY-MM-DD');
                endDate = moment().subtract(1, 'months').endOf('month').format('YYYY-MM-DD');
            } else if (range === 'overall') {
                startDate = moment().subtract(10, 'years').format('YYYY-MM-DD'); // Arbitrary long-ago date
                endDate = moment().format('YYYY-MM-DD');
            } else if (range === 'custom') {
                $('#customRange').show();
                return;
            }

            // Set hidden input values for predefined ranges
            $('#from_date').val(startDate);
            $('#to_date').val(endDate);
            $('#customRange').hide();
        });

        // Ensure custom range selection updates the dropdown
        $('#customRange').on('apply.daterangepicker', function(ev, picker) {
            $('#predefinedRanges').val('custom');
        });

        var ctx = document.getElementById('orderReportChart').getContext('2d');
        var orderReportChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Delivered', 'Returned', 'Failed', 'Canceled'],
                datasets: [{
                    label: 'Orders',
                    data: [{{ $delivered }}, {{ $returned }}, {{ $failed }}, {{ $canceled }}],
                    backgroundColor: '#EFEFEF',
                    borderColor: '#FE6524',
                    borderWidth: 1,
                    fill: true,
                    tension: 0.4 
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

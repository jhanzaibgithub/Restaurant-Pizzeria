@extends('layouts.admin.app')
@section('title', translate('Earning Report'))

@push('css_or_js')

@endpush
@section('content')
<div class="ml-5">
    @include('admin-views.report.partials._reportCAL-setup-inline-menu')
</div>
<hr class="li_hr-top">
<div class="content container-fluid">

    <div class="card mb-3">
        <div class="card-header d-flex flex-column align-items-baseline">
            <div>
                <h2>{{ translate('branch_report_overview') }}</h2>
            </div>
            <div class="mb-1">
                <span>{{ translate('admin') }}:</span>
                <u class="text-order_id"><a href="#">{{ auth('admin')->user()->f_name . ' ' . auth('admin')->user()->l_name }}</a></u>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-footer">
            <div class="row g-2">
                <div class="col-12">
                    <form action="{{ url()->current() }}" method="get">
                        <div class="row g-2">
                            <div class="col-sm-4">
                                <div class="">
                                    <label class="input-label" for="start-date">Start Date:</label>
                                    <input type="date" name="from" id="from_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="">
                                    <label class="input-label" for="end-date">End Date:</label>
                                    <input type="date" name="to" id="to_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="">
                                    <label class="input-label" for="branch">Branch:</label>
                                    <select class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate custom-select min-w200" name="branch" id="select_branch">
                                        <option value="all_branches">{{ translate('all_branches') }}</option>
                                        @foreach($branches as $branch)
                                        <option value="{{ $branch->id}}" {{ session('branch_filter') == $branch->id ? 'selected' : '' }}>{{ $branch->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-4">
                                <div class="d-flex justify-content-start align-items-center gap-3">
                                    <button type="reset" class="btn btn-white border-primary text-order_id">{{ translate('reset') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ translate('submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 px-5 my-3">
        <div class="col-sm-4" style="padding: 5px 40px;">
            <div class="dashboard--card bg--2">
                <img class="resturant-icon mb-3" src="{{ asset('assets/admin/img/dashboard/report_branch_sale.png') }}" alt="dashboard">
                <h4 class="title text-white m-0">{{ translate('branch_Sale') }}</h4>
                <span class="subtitle text-white">Rs:{{ $total_sold }}</span>
            </div>
        </div>
        <div class="col-sm-4" style="padding: 5px 40px;">
            <div class="dashboard--card bg--3">
                <img class="resturant-icon mb-3" src="{{ asset('assets/admin/img/dashboard/report_branch_earnings.png') }}" alt="dashboard">
                <h4 class="title text-white m-0">{{ translate('branch_Earnings') }}</h4>
                <span class="subtitle text-white">Rs:{{ $total_earnings }}</span>
            </div>
        </div>
        <div class="col-sm-4" style="padding: 5px 40px;">
            <div class="dashboard--card bg--1">
                <img class="resturant-icon mb-3" src="{{ asset('assets/admin/img/dashboard/growth.png') }}" alt="dashboard">
                <h4 class="title text-white m-0">{{ translate('branch_Growth') }}</h4>
                <span class="subtitle text-white">{{ round($growth, 2) }}%</span>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">
            <h6 class="d-flex align-items-center gap-2 mb-0">
                <span class="h4 mb-0"> Branch Static</span>
            </h6>
            <button type="button" class="btnExport" data-toggle="dropdown" aria-expanded="false">
                {{ translate('export') }}
                <i class="tio-download-to"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.pos.export-excel') }}?from={{ $from }}&to={{ $to }}">
                        <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}" alt="">
                        {{ translate('Excel') }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="chartjs-custom" style="height: 360px">
                <canvas class="js-chart"
                    data-hs-chartjs-options='{
                    "type": "line",
                    "data": {
                       "labels": ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                       "datasets": [{
                        "data": [{{ $sold[1] }},{{ $sold[2] }},{{ $sold[3] }},{{ $sold[4] }},{{ $sold[5] }},{{ $sold[6] }},{{ $sold[7] }},{{ $sold[8] }},{{ $sold[9] }},{{ $sold[10] }},{{ $sold[11] }},{{ $sold[12] }}],
                        "backgroundColor": ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                        "borderColor": "green",
                        "borderWidth": 2,
                        "pointRadius": 0,
                        "pointBorderColor": "#fff",
                        "pointBackgroundColor": "green",
                        "pointHoverRadius": 0,
                        "hoverBorderColor": "#fff",
                        "hoverBackgroundColor": "#377dff"
                      },
                      {
                        "data": [{{ $tax[1] }},{{ $tax[2] }},{{ $tax[3] }},{{ $tax[4] }},{{ $tax[5] }},{{ $tax[6] }},{{ $tax[7] }},{{ $tax[8] }},{{ $tax[9] }},{{ $tax[10] }},{{ $tax[11] }},{{ $tax[12] }}],
                        "backgroundColor": ["rgba(0, 201, 219, 0)", "rgba(255, 255, 255, 0)"],
                        "borderColor": "#ec9a3c",
                        "borderWidth": 2,
                        "pointRadius": 0,
                        "pointBorderColor": "#fff",
                        "pointBackgroundColor": "#ec9a3c",
                        "pointHoverRadius": 0,
                        "hoverBorderColor": "#fff",
                        "hoverBackgroundColor": "#00c9db"
                      }]
                    },
                    "options": {
                      "gradientPosition": {"y1": 200},
                       "scales": {
                          "yAxes": [{
                            "gridLines": {
                              "color": "#e7eaf3",
                              "drawBorder": false,
                              "zeroLineColor": "#e7eaf3"
                            },
                            "ticks": {
                              "min": 0,
                              "max": {{ \App\CentralLogics\Helpers::max_earning() }},
                              "stepSize": {{ round(\App\CentralLogics\Helpers::max_earning() / 5) }},
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
                            }
                          }]
                      },
                      "tooltips": {
                        "prefix": "",
                        "postfix": "",
                        "hasIndicator": true,
                        "mode": "index",
                        "intersect": false,
                        "lineMode": true,
                        "lineWithLineColor": "rgba(19, 33, 68, 0.075)"
                      },
                      "hover": {
                        "mode": "nearest",
                        "intersect": true
                      }
                    }
                  }'>
                </canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush

@push('script_2')
<script src="{{ asset('assets/admin/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script>
    $('.js-chart').each(function() {
        $.HSCore.components.HSChartJS.init($(this));
    });

    $('.js-circle').each(function() {
        var circle = $.HSCore.components.HSCircles.init($(this));
    });

    $('#from_date,#to_date').change(function() {
        let fr = $('#from_date').val();
        let to = $('#to_date').val();
        if (fr != '' && to != '') {
            if (fr > to) {
                $('#from_date').val('');
                $('#to_date').val('');
                toastr.error('Invalid date range!', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    });
</script>
@endpush
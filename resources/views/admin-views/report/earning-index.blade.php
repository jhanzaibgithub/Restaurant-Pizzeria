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
                                <h2 >{{translate('earning_report_overview')}}</h2>
                        </div>
                        <div class="mb-1">
                                <span>{{translate('admin')}}:</span>
                                <u class="text-order_id"><a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a></u>
                        </div>
                    </div>
        </div>

        <div class="card mb-3">
            <div class="card-footer">
                <div class="row g-2">
                    <div class="col-12">
                        <form action="{{url()->current()}}" method="get">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="">
                                        <label class="input-label" for="start-date">Start Date:</label>
                                        <input type="date" name="from" value={{$from}} id="from_date"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="">
                                        <label class="input-label" for="end-date">End Date:</label>
                                        <input type="date" name="to" value={{$to}} id="to_date"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-4 mt-4">
                                        <div class="d-flex justify-content-start align-items-center gap-3">
                                            <button  type="reset" class="btn btn-white border-primary text-order_id">{{translate('reset')}}</button>
                                            <button  type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                                        </div>
                                </div>
                            </div>
                        </form>
                    </div>

                            <?php
                                if ($total_sold == 0) {
                                    $total_sold = 0.01;
                                }
                                if ($total_tax == 0) {
                                    $total_tax = 0.01;
                                }

                                ?>
                </div>
            </div>
        </div>
 
        <div class="row g-2 px-5 my-3">
            <div style="padding: 5px 40px 5px 40px;" class="col-sm-4">
                <!-- Card -->
                <div class="dashboard--card bg--2">
                    <img class="resturant-icon mb-3" src="{{asset('assets/admin/img/dashboard/earning.png')}}" alt="dashboard">
                    <h4 class="title text-white m-0">{{translate('total')}} {{translate('Earning')}}</h4>
                    <span class="subtitle text-white">
                    <i class="tio-trending-up"></i> {{ \App\CentralLogics\Helpers::set_symbol(round(abs($total_sold-$total_tax))) }}
                    </span>
                </div>
                <!-- End Card -->
            </div>

            <div style="padding: 5px 40px 5px 40px;" class="col-sm-4">
                <!-- Card -->
                <div class="dashboard--card bg--3">
                    <img class="resturant-icon mb-3" src="{{asset('assets/admin/img/dashboard/profit.png')}}" alt="dashboard">
                        <h4 class="title text-white m-0">{{translate('total')}} {{translate('Profit')}}</h4>
                        <span class="subtitle text-white">
                            <i class="tio-trending-up"></i> {{ \App\CentralLogics\Helpers::set_symbol(round(abs($total_tax))) }}
                        </span>
                </div>
                <!-- End Card -->
            </div>

            <div style="padding: 5px 40px 5px 40px;" class="col-sm-4">
                <!-- Card -->
                <div class="dashboard--card bg--1">
                    <img class="resturant-icon mb-3" src="{{asset('assets/admin/img/dashboard/growth.png')}}" alt="dashboard">
                    <h4 class="title text-white m-0">{{translate('Business Growth')}}</h4>
                    <span class="subtitle text-white">
                        <i class="tio-trending-up"></i> {{ round($growth, 2) }} %
                    </span>
                </div>
                <!-- End Card -->
            </div>
        </div>
        
        <div id="dashboard-earning-graph">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between flex-wrap gap-2">
    
                    <h6 class="d-flex align-items-center gap-2 mb-0">
                        {{translate('Total_Sale')}} ({{date('Y')}}) :
                        <span class="h4 mb-0"> {{ \App\CentralLogics\Helpers::set_symbol($data['total_sold']) }}</span>
                    </h6>
                    <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                        <div>
                            <button type="button"  class="btnExport" data-toggle="dropdown"
                               aria-expanded="false">
                                {{ translate('export') }}
                                <i class="tio-download-to"></i>     
                            </button>   
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('admin.pos.export-excel') }}?from={{ $data['start_date'] }}&to={{ $data['end_date'] }}">
                                    <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                    alt="">
                                    {{ translate('Excel') }}
                                    </a>
                                </li>
                            </ul>
                        </div>    
                    </div>   
                </div>
      
                <!-- Body -->
                <div class="card-body">
                    <!-- Bar Chart -->
                    <div class="chartjs-custom" style="height: 360px">
                        <canvas class="js-chart"
                                data-hs-chartjs-options='{
                            "type": "line",
                            "data": {
                               "labels": ["{{ implode('","', $data['labels']) }}"],
                               "datasets": [{
                                "data": [{{ implode(',', $data['sold']) }}],
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
                                "data": [{{ implode(',', $data['tax']) }}],
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
                                      "max": {{\App\CentralLogics\Helpers::max_earning()}},
                                      "stepSize": {{round(\App\CentralLogics\Helpers::max_earning()/7)}},
                                      "fontColor": "#97a4af",
                                      "fontFamily": "Open Sans, sans-serif",
                                      "padding": 10,
                                      "postfix": " {{\App\CentralLogics\Helpers::currency_symbol()}}"
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

    <script src="{{asset('assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script>
            $('.js-chart').each(function () {
                $.HSCore.components.HSChartJS.init($(this));
            });

            $('.js-circle').each(function () {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });
    </script>

    <script>
        $('#from_date,#to_date').change(function () {
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

        })
    </script>
@endpush

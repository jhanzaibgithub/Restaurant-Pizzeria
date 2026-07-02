@extends('layouts.admin.app')

@section('title', translate('Table Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" src="{{ asset('assets/admin') }}/vendor/apex/apexcharts.css">
    </link>
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.order.partials._orderCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        @if ($status == 'all')
            <div class="px-4 mt-1">
                <div class="row g-2">
                    <div class="col-sm-6 col-lg-3">
                        <a class="order--card h-100" href="">
                            <!-- REMOVED {{ route('admin.table.order.list', ['confirmed']) }} -->
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    <!-- {{ $order_count['confirmed'] }} -->27,5
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('order earnings') }}</span>
                                </h4>
                                <span class="badge-soft-success px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-upward"></i>{{ translate('2.1%') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <a class="order--card h-100" href="">
                            <!-- REMOVED {{ route('admin.table.order.list', ['cooking']) }} -->
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    <!-- {{ $order_count['confirmed'] }} -->327
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('dine in') }}</span>
                                </h4>
                                <span class="badge-soft-success px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-upward"></i>{{ translate('2.1%') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <a class="order--card h-100" href="">
                            <!-- REMOVED {{ route('admin.table.order.list', ['done']) }} -->
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    <!-- {{ $order_count['confirmed'] }} -->72
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('take away') }}</span>
                                </h4>
                                <span class="badge-soft-danger px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-downward"></i>{{ translate('0.647%') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <a class="order--card h-100" href="">
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    <!-- {{ $order_count['confirmed'] }} -->106
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('home delivery') }}</span>
                                </h4>
                                <span class="badge-soft-success px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-upward"></i>{{ translate('2.1%') }}</span>
                            </div>
                        </a>
                    </div>


                </div>
            </div>
        @endif
        <!-- *************************************** -->
        <div class="px-4 mt-4">
            <div class="row g-2">
                <div class="col-sm-12 col-lg-12">
                    <!-- Card -->
                    <div class="card h-100">
                        <!-- Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                                <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                    <img width="20" class="avatar-img rounded-0"
                                        src="{{ asset('assets/admin/img/icons/earning_statistics.png') }}" alt="">
                                    {{ translate('order_statics') }}
                                </h4>

                                <ul class="option-select-btn">
                                    <li>
                                        <label>
                                            <input type="radio" name="statistics" hidden checked>
                                            <span data-order-type="yearOrder"
                                                onclick="orderStatisticsUpdate(this)">{{ translate('Dine In ') }}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="statistics" hidden="">
                                            <span data-order-type="MonthOrder"
                                                onclick="orderStatisticsUpdate(this)">{{ translate('Take Away') }}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="statistics" hidden="">
                                            <span data-order-type="WeekOrder"
                                                onclick="orderStatisticsUpdate(this)">{{ translate('Home Delivery') }}</span>
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
                </div>
            </div>
        </div>


        <!-- *************************************** -->
        <div class="card-top px-card pt-4">
            <div class="row justify-content-between align-items-center gy-2 px-2">
                <div class="col-sm-6 col-md-6 col-lg-4">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group" >
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                placeholder="{{ translate('Search by ID, customer or payment status') }}"
                                aria-label="Search" value="{{ $search }}" required autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btnSearchArrow" type="submit"> <i class="fa-solid fa-arrow-right"></i> </button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-8 d-flex justify-content-end">
                    <button type="button" class="btnExport" data-toggle="dropdown"
                        aria-expanded="false">
                        {{ translate('export') }}
                        <i class="tio-download-to"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.table.order.export-excel', ['search' => $search, 'from' => $from, 'to' => $to, 'status' => $status]) }}">
                                <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                    alt="">
                                {{ translate('Excel') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>






        <!-- Header -->
        <!-- <div class="card-top px-card pt-4">
                                <div class="row justify-content-between align-items-center gy-2">
                                    <div class="col-sm-8 col-md-6 col-lg-4">
                                        <div>
                                            <form action="{{ url()->current() }}" method="GET">
                                                <div class="input-group">
                                                    <input id="datatableSearch_" type="search" name="search"
                                                        class="form-control"
                                                        placeholder="{{ translate('Search by Order ID  Order Status or Transaction Reference') }}" aria-label="Search"
                                                        value="{{ $search }}" required autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                        <div>
                                            <button type="button" class="btn btn-outline-primary" data-toggle="dropdown" aria-expanded="false">
                                                <i class="tio-download-to"></i>
                                                {{ translate('export') }}
                                                <i class="tio-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.table.order.export-excel', ['search' => $search, 'from' => $from, 'to' => $to, 'status' => $status]) }}">
                                                        <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}" alt="">
                                                        {{ translate('excel') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div> -->
        <!-- End Header -->
        <div class="px-4">
            <div class="card mt-4">
                <!--  Header -->
                <div class="card-header">
                    <div class="col-5">
                        <h3>
                            {{ translate('order_history') }}
                        </h3>
                    </div>
                    <div class="table-responsive col-7">
                            {!!$orders->links()!!}
                    </div>
            </div>
                <!-- End Header -->
                <!-- Table -->

                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-hover table-border table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('Date') }}</th>
                                    <th>{{ translate('order_ID') }}</th>
                                    <th>{{ translate('branch') }}</th>
                                    <th>{{ translate('total_Amount') }}</th>
                                    <!-- <th>{{ translate('order') }} {{ translate('status') }}</th> -->
                                    <th> {{ translate('Status') }}</th>
                                    <th class="text-center">{{ translate('invoice') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($orders as $key => $order)
                                    <tr class="status-{{ $order['order_status'] }} class-all">
                                        <td>
                                            <div>{{ date('M d, Y', strtotime($order['created_at'])) }}</div>
                                            <!-- <div>{{ date('h:m A', strtotime($order['created_at'])) }}</div> -->
                                        </td>
                                        <td>
                                            <a class="text-order_id"
                                                href="{{ route('admin.table.order.details', ['id' => $order['id']]) }}">{{ $order['id'] }}</a>
                                        </td>

                                        <td>
                                            @if ($order->branch)
                                                {{ $order->branch['name'] }}
                                            @else
                                                <span class="text-muted">
                                                    {{ translate('Branch unavailable') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ \App\CentralLogics\Helpers::set_symbol($order['order_amount']) }}</div>
                                            <!-- @if ($order->payment_status == 'paid')
    <span class="text-success">{{ translate('paid') }}</span>
@else
    <span class="text-danger">{{ translate('unpaid') }}</span>
    @endif -->
                                        </td>
                                        <td class="text-capitalize">
                                            @if ($order['order_status'] == 'pending')
                                                <span class="text-info px-2 rounded">{{ translate('pending') }}</span>
                                            @elseif($order['order_status'] == 'confirmed')
                                                <span class="text-info px-2 rounded">{{ translate('confirmed') }}</span>
                                            @elseif($order['order_status'] == 'processing')
                                                <span
                                                    class="text-warning px-2 rounded">{{ translate('processing') }}</span>
                                            @elseif($order['order_status'] == 'picked_up')
                                                <span
                                                    class="text-warning px-2 rounded">{{ translate('out_for_delivery') }}</span>
                                            @elseif($order['order_status'] == 'delivered')
                                                <span
                                                    class="text-success px-2 rounded">{{ translate('delivered') }}</span>
                                            @elseif($order['order_status'] == 'cooking')
                                                <span class="text-success px-2 rounded">{{ translate('cooking') }}</span>
                                            @elseif($order['order_status'] == 'completed')
                                                <span
                                                    class="text-success px-2 rounded">{{ translate('completed') }}</span>
                                            @else
                                                <span
                                                    class="text-danger px-2 rounded">{{ str_replace('_', ' ', $order['order_status']) }}</span>
                                            @endif
                                        </td>
                                        <!-- <td class="text-capitalize">
                                                        @if ($order['order_type'] == 'take_away')
    <span class="text-info px-2 rounded">{{ translate('take_away') }}</span>
@elseif($order['order_type'] == 'dine_in')
    <span class="text-info px-2 rounded">{{ translate('dine_in') }}</span>
@else
    <span class="text-success px-2 rounded">{{ translate('delivery') }}</span>
    @endif
                                                    </td> -->
                                        <td class="text-capitalize">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="badge-soft-secondary px-2 py-1 rounded"
                                                    href="{{ route('admin.pos.order-details', ['id' => $order['id']]) }}">
                                                    {{ translate('View') }}</a>
                                                <span class="badge-soft-secondary px-2 py-1 rounded" target="_blank"
                                                    onclick="print_invoice('{{ $order->id }}')">{{ translate('PDF') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                <!-- End Table -->


            </div>
        </div>
        <!-- End Card -->




        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('print') }} {{ translate('invoice') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row" style="font-family: emoji;">
                        <div class="col-md-12">
                            <center>
                                <input type="button" class="btn btn-primary non-printable"
                                    onclick="printDiv('printableArea')"
                                    value="{{ translate('Proceed, If thermal printer is ready..') }}" />
                                <a href="{{ url()->previous() }}"
                                    class="btn btn-danger non-printable">{{ translate('Back') }}</a>
                            </center>
                            <hr class="non-printable">
                        </div>
                        <div class="row" id="printableArea" style="margin: auto;">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('assets/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{ asset('assets/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <script src="{{ asset('assets/admin') }}/vendor/apex/apexcharts.min.js"></script>
@endpush


@push('script_2')
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        function print_invoice(order_id) {
            $.get({
                url: '{{ url('/') }}/admin/pos/invoice/' + order_id,
                dataType: 'json',
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log("success...")
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function printDiv(divName) {

            if ($('html').attr('dir') === 'rtl') {
                $('html').attr('dir', 'ltr')
                var printContents = document.getElementById(divName).innerHTML;
                document.body.innerHTML = printContents;
                $('#printableAreaContent').attr('dir', 'rtl')
                window.print();
                $('html').attr('dir', 'rtl')
                location.reload();
            } else {
                var printContents = document.getElementById(divName).innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                location.reload();
            }

        }
    </script>
    <script>
        function filter_branch_orders(id) {
            location.href = '{{ url('/') }}/admin/orders/branch-filter/' + id;
        }
    </script>

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

            colors: ['#FE6524', '#FE6524'],
            series: [{
                name: "Order",
                data: [
                    {{ $order_statistics_chart[1] }}, {{ $order_statistics_chart[2] }},
                    {{ $order_statistics_chart[3] }}, {{ $order_statistics_chart[4] }},
                    {{ $order_statistics_chart[5] }}, {{ $order_statistics_chart[6] }},
                    {{ $order_statistics_chart[7] }}, {{ $order_statistics_chart[8] }},
                    {{ $order_statistics_chart[9] }}, {{ $order_statistics_chart[10] }},
                    {{ $order_statistics_chart[11] }}, {{ $order_statistics_chart[12] }}
                ],
                fill: {
                    type: 'solid',
                    colors: ['#1A73E8', '#B32824']
                }
            }],
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
                        show: false
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
    <script>
        function orderStatisticsUpdate(t) {
            let value = $(t).attr('data-order-type');
            console.log(value);

            $.ajax({
                url: '{{ route('admin.order-statistics') }}',
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
    </script>
@endpush

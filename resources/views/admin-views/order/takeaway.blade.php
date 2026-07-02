@extends('layouts.admin.app')

@section('title', translate('Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" src="{{ asset('assets/admin') }}/vendor/apex/apexcharts.css">
    </link>
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.order.partials._takeawayCal-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">

    @if ($status == 'all')

                <div class="row g-2 mb-3">
                    <div class="col-sm-6 col-lg-2">
                        <a class="order--card h-100" href="">
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    {{ $order_count['delivered'] }}
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('completed') }}</span>
                                </h4>
                                <span class="badge-soft-success px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-upward"></i>{{ translate('2.1%') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-2">
                        <a class="order--card h-100" href="">
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    {{ $order_count['confirmed'] }}
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('confirmed') }}</span>
                                </h4>
                                <span class="badge-soft-success px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-upward"></i>{{ translate('2.1%') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-2">
                        <a class="order--card h-100" href="">
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    {{ $order_count['pending'] }}
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('pending') }}</span>
                                </h4>
                                <span class="badge-soft-danger px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-downward"></i>{{ translate('0.647%') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-lg-2">
                        <a class="order--card h-100" href="">
                            <!-- REMOVED {{ route('admin.table.order.list', ['completed']) }} -->
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    {{ $order_count['processing'] }}
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('processing') }}</span>
                                </h4>
                                <span class="badge-soft-success px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-upward"></i>{{ translate('2.1%') }}</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <a class="order--card h-100" href="">
                            <!-- REMOVED {{ route('admin.table.order.list', ['completed']) }} -->
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    {{ $order_count['canceled'] }}
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('canceled') }}</span>
                                </h4>
                                <span class="badge-soft-danger px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-downward"></i>{{ translate('0.47%') }}</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <a class="order--card h-100" href="">
                            <!-- REMOVED {{ route('admin.table.order.list', ['completed']) }} -->
                            <div class="d-flex flex-column justify-content-start align-items-baseline pt-5">
                                <span style="font-size: 25px;" class="card-title pb-3 text-107980">
                                    {{ $order_count['failed'] }}
                                </span>
                                <h4 style="font-size: 16px; color:#6D6D6D;"
                                    class="card-subtitle d-flex justify-content-between m-0 pb-1 align-items-center">
                                    <span>{{ translate('failed') }}</span>
                                </h4>
                                <span class="badge-soft-success px-2 py-1 rounded fs-2"><i
                                        class="tio-arrow-upward"></i>{{ translate('2.1%') }}</span>
                            </div>
                        </a>
                    </div>
                </div>
            <!-- <div class="row g-2 mb-3">
                <div class="col-sm-6 col-md-12 col-lg-12">
                    <div class="card h-100">
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
                            <div id="updatingOrderData" class="custom-chart mt-2">
                                <div id="order-statistics-line-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

    @endif
        <div class="row justify-content-between align-items-center gy-2 mb-3">

            <div class="col-sm-6 col-md-4 col-lg-4">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                            placeholder="{{ translate('Search by ID, customer or payment status') }}" aria-label="Search"
                            value="{{ $search }}" required autocomplete="off" />
                        <button class="btnSearchArrow" type="submit">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                <div>
                    <button type="button" class="btnExport" data-toggle="dropdown" aria-expanded="false">
                        {{ translate('export') }}
                        <i class="tio-download-to"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.orders.export-excel', ['search' => $search, 'from' => $from, 'to' => $to, 'status' => $status]) }}">
                                <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                    alt="">
                                {{ translate('Excel') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


    <!-- Card Top -->
    <!-- <div class="card-top px-card pt-4">
                    <div class="row justify-content-between align-items-center gy-2">
                        <div class="col-sm-8 col-md-6 col-lg-4">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                            class="form-control"
                                            placeholder="{{ translate('Search by Order ID, Order Status or Transaction Reference') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                        {{ translate('Search') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                                        <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.orders.export-excel', ['search' => $search, 'from' => $from, 'to' => $to, 'status' => $status]) }}">
                                            <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}" alt="">
                                            {{ translate('excel') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="ml-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white" href="javascript:;"
                                    onclick="$('#datatableFilterSidebar,.hs-unfold-overlay').show(500)">
                                    <i class="tio-filter-list mr-1"></i>Filter
                                    <span class="badge badge-success badge-pill ml-1" id="filter_count"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div> -->
    <!-- End Card Top -->

    <div class="card">
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
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Order_ID') }}</th>
                            <th>{{ translate('branch') }}</th>
                            <th>{{ translate('Total_Amount') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th class="text-center">{{ translate('invoice') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach ($orders as $key => $order)
                            <tr class="status-{{ $order['order_status'] }} class-all">
                                <td>
                                    <div>{{ date('M d, Y', strtotime($order['delivery_date'])) }}</div>
                                    <!-- <div>{{ date('h:i A', strtotime($order['delivery_time'])) }}</div> -->
                                </td>
                                <td>
                                    <a class="text-order_id"
                                        href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">{{ $order['id'] }}</a>
                                </td>
                                <td>
                                    <span>{{ $order->branch ? $order->branch->name : 'Branch deleted!' }}</span>
                                </td>
                                <td>
                                    <div>
                                        {{ \App\CentralLogics\Helpers::set_symbol($order['order_amount'] + $order['delivery_charge']) }}
                                    </div>
                                    <!-- @if ($order->payment_status == 'paid')
    <span class="text-success">{{ translate('paid') }}</span>
@else
    <span class="text-danger">{{ translate('unpaid') }}</span>
    @endif -->
                                </td>
                                <td class="text-capitalize">
                                    @if ($order['order_status'] == 'pending')
                                        <span class="text-info px-2 py-1 rounded">{{ translate('pending') }}</span>
                                    @elseif($order['order_status'] == 'confirmed')
                                        <span class="text-info px-2 py-1 rounded">{{ translate('confirmed') }}</span>
                                    @elseif($order['order_status'] == 'processing')
                                        <span class="text-warning px-2 py-1 rounded">{{ translate('processing') }}</span>
                                    @elseif($order['order_status'] == 'out_for_delivery')
                                        <span
                                            class="text-warning px-2 py-1 rounded">{{ translate('out_for_delivery') }}</span>
                                    @elseif($order['order_status'] == 'delivered')
                                        <span class="text-success px-2 py-1 rounded">{{ translate('delivered') }}</span>
                                    @elseif($order['order_status'] == 'failed')
                                        <span
                                            class="text-danger px-2 py-1 rounded">{{ translate('failed_to_deliver') }}</span>
                                    @else
                                        <span
                                            class="text-danger px-2 py-1 rounded">{{ str_replace('_', ' ', $order['order_status']) }}</span>
                                    @endif
                                </td>

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


        <!-- End Card -->
    </div>


    <!-- Order Filter Modal -->
    <div id="datatableFilterSidebar"
        class="hs-unfold-content_ sidebar sidebar-bordered sidebar-box-shadow initial-hidden">
        <div class="card card-lg sidebar-card sidebar-footer-fixed">
            <div class="card-header">
                <h4 class="card-header-title">Customer Filter</h4>
                <!-- Toggle Button -->
                <a class="js-hs-unfold-invoker_ btn btn-icon btn-xs btn-ghost-dark ml-2" href="javascript:;"
                    onclick="$('#datatableFilterSidebar,.hs-unfold-overlay').hide(500)">
                    <i class="tio-clear tio-lg"></i>
                </a>
                <!-- End Toggle Button -->
            </div>
            <?php
            $filter_count = 0;
            if (isset($customer_id)) {
                $filter_count += 1;
            }
            if (isset($payment_type)) {
                $filter_count += 1;
            }

            if ($status == 'all') {
                if (isset($orderstatus) && count($orderstatus) > 0) {
                    $filter_count += 1;
                }
            }
            ?>
            <!-- Body -->
            <form class="card-body sidebar-body sidebar-scrollbar" action="{{ route('admin.orders.filter') }}"
                method="POST" id="order_filter_form">
                @csrf

                <small class="text-cap mb-3">{{ translate('By Customer Name') }}</small>
                <div class="form-group">
                    <select name="customer_name" id="customer_name" class="form-control js-select2-custom">
                        <option selected disabled>---{{ translate('select') }}---</option>
                        <option value="0" {{ $customer_id == 0 ? 'selected' : '' }}>{{ translate('All') }}</option>
                        @foreach (($customers ?? []) as $user)
                            <option value="{{ $user->id }}" {{ $customer_id == $user->id ? 'selected' : '' }}>
                                {{ $user->f_name }} {{ $user->l_name }}</option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4">
                <small class="text-cap mb-3">{{ translate('Payment Type') }}</small>
                <div class="form-group">
                    <select name="payment_type" id="payment_type" class="form-control js-select2-custom">
                        <option selected disabled>---{{ translate('select') }}---</option>

                        <option value="cash_on_delivery" {{ $payment_type == 'cash_on_delivery' ? 'selected' : '' }}>
                            {{ translate('cash_on_delivery') }}</option>
                        <option value="wallet_payment" {{ $payment_type == 'wallet_payment' ? 'selected' : '' }}>
                            {{ translate('Wallet') }}</option>

                    </select>
                </div>

                <hr class="my-4">
                @if ($status == 'all')
                    <small class="text-cap mb-3">{{ translate('Order status') }}</small>
                    <!-- Custom Checkbox -->
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus2" name="orderStatus[]" class="custom-control-input"
                            value="pending" {{ isset($orderstatus) ? (in_array('pending', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label" for="orderStatus2">{{ translate('pending') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus1" name="orderStatus[]" class="custom-control-input"
                            value="confirmed"
                            {{ isset($orderstatus) ? (in_array('confirmed', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label" for="orderStatus1">{{ translate('confirmed') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus3" name="orderStatus[]" class="custom-control-input"
                            value="processing"
                            {{ isset($orderstatus) ? (in_array('processing', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label" for="orderStatus3">{{ translate('processing') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus4" name="orderStatus[]" class="custom-control-input"
                            value="picked_up"
                            {{ isset($orderstatus) ? (in_array('picked_up', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label"
                            for="orderStatus4">{{ translate('out_for_delivery') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus5" name="orderStatus[]" class="custom-control-input"
                            value="delivered"
                            {{ isset($orderstatus) ? (in_array('delivered', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label" for="orderStatus5">{{ translate('delivered') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus6" name="orderStatus[]" class="custom-control-input"
                            value="returned"
                            {{ isset($orderstatus) ? (in_array('returned', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label" for="orderStatus6">{{ translate('returned') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus7" name="orderStatus[]" class="custom-control-input"
                            value="failed" {{ isset($orderstatus) ? (in_array('failed', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label" for="orderStatus7">{{ translate('failed') }}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus8" name="orderStatus[]" class="custom-control-input"
                            value="canceled"
                            {{ isset($orderstatus) ? (in_array('canceled', $orderstatus) ? 'checked' : '') : '' }}>
                        <label class="custom-control-label" for="orderStatus8">{{ translate('canceled') }}</label>
                    </div>
                @endif
                <!-- Footer -->
                <div class="card-footer sidebar-footer">
                    <div class="row gx-2">
                        <div class="col">
                            <button type="reset" class="btn btn-block btn-white" id="reset"> Clear filter </button>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-block btn-primary">Save</button>
                        </div>
                    </div>
                </div>
                <!-- End Footer -->
            </form>
        </div>
    </div>
    <!-- End Order Filter Modal -->
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
        function resetForm() {
            location.href = '{{ url('/') }}/admin/orders/list/all';
        }

        function filter_branch_orders(id) {
            location.href = '{{ url('/') }}/admin/orders/branch-filter/' + id;
        }
    </script>

    <script>
        $(document).on('ready', function() {
            @if ($filter_count > 0)
                $('#filter_count').html({{ $filter_count }});
            @endif

            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $('#datatableSearch').on('mouseup', function(e) {
                var $input = $(this),
                    oldValue = $input.val();

                if (oldValue == "") return;

                setTimeout(function() {
                    var newValue = $input.val();

                    if (newValue == "") {
                        // Gotcha
                        datatable.search('').draw();
                    }
                }, 1);
            });


            $('.js-tagify').each(function() {
                var tagify = $.HSCore.components.HSTagify.init($(this));
            });
        });

        $('#reset').on('click', function() {
            // e.preventDefault();
            location.href = '{{ url('/') }}/admin/orders/filter/reset';
        });
    </script>
    <script>
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.orders.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('.card-footer').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>


    <!-- <script>
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
    </script> -->
@endpush

@extends('layouts.branch.app')

@section('title', translate('Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="ml-5">
    @include('branch-views.pos.order.partials._posCAL-setup-inline-menu')
</div>
<hr class="li_hr-top">
{{-- ************ --}}

<div class="content container-fluid">

    <div class="pb-4">
        <div class="row justify-content-between align-items-center gy-2">
            {{--  <div class="col-sm-8 col-md-6 col-lg-4">
                <form action="{{url()->current()}}" method="GET">
    <div class="input-group">
        <input id="datatableSearch_" type="search" name="search" class="form-control"
            placeholder="{{translate('Search by ID, customer or payment status')}}" aria-label="Search"
            value="{{$search}}" required autocomplete="off">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
                {{ translate('Search') }}
            </button>
        </div>
    </div>
    </form>
</div> --}}
            {{--  -------------------------------  --}}
            <div class="col-sm-6 col-md-4 col-lg-4">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                            placeholder="{{ translate('Search by ID, customer or payment status') }}"
                            aria-label="Search" value="{{ $search }}" required autocomplete="off" />
                        <button
                            class="btnSearchArrow" type="submit">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                <div>
                    <button type="button"  class="btnExport" data-toggle="dropdown"
                        aria-expanded="false">

                        {{ translate('export') }}
                        <i class="tio-download-to"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('branch.pos.export-excel', ['search' => $search, 'from' => $from, 'to' => $to]) }}"
                                download>
                                <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                    alt="">
                                {{ translate('Excel') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{--  **********  --}}
    <!-- Card -->
    <div class="card">

        <div class="card-header">
                <h3>
                    <span>
                        {{ translate('order_history') }}
                    </span>
                </h3>
        </div>
        <!-- End Header -->

        <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            {{--  <th >
                                {{translate('SL')}}
                </th> --}}
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Order_ID') }}</th>
                            {{--  <th>{{translate('Customer_Info')}}</th> --}}
                            <th>{{ translate('Branch') }}</th>
                            <th>{{ translate('Total_Amount') }}</th>
                            <th>{{ translate('Status') }}</th>
                            {{--  <th>{{translate('Order_Type')}}</th> --}}
                            <th class="text-center">{{ translate('invoice') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach ($orders as $key => $order)
                            <tr class="status-{{ $order['order_status'] }} class-all">
                                {{--  <td >{{$key+$orders->firstItem()}}</td> --}}
                                <td>
                                    <div>{{ date('M d, Y', strtotime($order['created_at'])) }}</div>
                                    <!-- <div>{{ date('h:i A', strtotime($order['created_at'])) }}</div> -->
                                </td>
                                <td>
                                    <a class="text-order_id text-dark-hold"
                                        href="{{ route('branch.pos.order-details', ['id' => $order['id']]) }}">{{ $order['id'] }}</a>
                                </td>
                                {{--  <td>
                                @if ($order->customer)
                                    <h6 class="text-capitalize mb-1">{{$order->customer['f_name'].' '.$order->customer['l_name']}}
                </h6>
                <a class="text-dark fz-12"
                    href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                @elseif($order['user_id'] == null)
                <h6 class="text-capitalize text-muted">{{translate('walk_in_customer')}}</h6>
                @else
                <h6 class="text-capitalize text-muted">{{translate('Customer_Unavailable')}}</h6>
                @endif
                </td> --}}
                                <td>{{ $order->branch->name }}</td>
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
                                        <span class="text-danger">{{ translate('pending') }}</span>
                                    @elseif($order['order_status'] == 'confirmed')
                                        <span class="text-info">{{ translate('confirmed') }}</span>
                                    @elseif($order['order_status'] == 'processing')
                                        <span class="text-warning">{{ translate('processing') }}</span>
                                    @elseif($order['order_status'] == 'picked_up')
                                        <span class="text-warning">{{ translate('out_for_delivery') }}</span>
                                    @elseif($order['order_status'] == 'delivered')
                                        <span class="text-success">{{ translate('delivered') }}</span>
                                    @else
                                        <span
                                            class="text-danger">{{ str_replace('_', ' ', $order['order_status']) }}</span>
                                    @endif
                                </td>
                                {{--  <td class="text-capitalize">
                                <span class="badge-soft-success px-2 py-1 rounded">{{translate($order['order_type'])}}</span>
                </td> --}}
                                <!-- <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="btn btn-sm btn-outline-primary square-btn" href="{{ route('branch.pos.order-details', ['id' => $order['id']]) }}">
                    <i class="tio-invisible"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-success square-btn" target="_blank" type="button"
                        onclick="print_invoice('{{ $order->id }}')"><i class="tio-print"></i>
                    </button>
    </div>
    </td> -->
                                <td class="text-capitalize">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="badge-soft-secondary px-2 py-1 rounded"
                                            href="{{ route('branch.pos.order-details', ['id' => $order['id']]) }}">
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

        <div class="table-responsive mt-4 px-3">
            <div class="d-flex justify-content-lg-end">
                <!-- Pagination -->
                {!! $orders->links() !!}
            </div>
        </div>

        <!-- {{--
        <div class="card-footer">
            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                {{-- <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                        <span class="mr-2">Showing:</span>

                        <select id="datatableEntries" class="js-select2-custom"
                                data-hs-select2-options='{
                                "minimumResultsForSearch": "Infinity",
                                "customClass": "custom-select custom-select-sm custom-select-borderless",
                                "dropdownAutoWidth": true,
                                "width": true
                              }'>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>

                        <span class="text-secondary mr-2">of</span>

                        <span id="datatableWithPaginationInfoTotalQty"></span>
                    </div>
                </div> --}}

                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            {!! $orders->links() !!}
                            {{-- <nav id="datatablePagination" aria-label="Activity pagination"></nav> --}}
                        </div>
                    </div>
                </div>
            </div>
            --}} -->
    </div>
    <!-- End Card -->
</div>

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
{{-- ************ --}}

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        function print_invoice(order_id) {
            $.get({
                url: '{{url('/')}}/branch/pos/invoice/'+order_id,
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log("success...")
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function printDiv(divName) {

            if($('html').attr('dir') === 'rtl') {
                $('html').attr('dir', 'ltr')
                var printContents = document.getElementById(divName).innerHTML;
                document.body.innerHTML = printContents;
                $('#printableAreaContent').attr('dir', 'rtl')
                window.print();
                $('html').attr('dir', 'rtl')
                location.reload();
            }else{
                var printContents = document.getElementById(divName).innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                location.reload();
            }

        }
    </script>

<script>
    $('#from_date, #to_date').change(function() {
        let from = $('#from_date').val();
        let to = $('#to_date').val();
        if (from != '') {
            $('#to_date').attr('required', 'required');
        }
        if (to != '') {
            $('#from_date').attr('required', 'required');
        }
        if (from != '' && to != '') {
            if (from > to) {
                $('#from_date').val('');
                $('#to_date').val('');
                toastr.error('{{ \App\CentralLogics\translate('Invalid date range ') }}!');
            }
        }
    })
</script>

<script>
    document.getElementById('filter_by').addEventListener('change', function() {
        var dateFields = document.querySelectorAll('.date-fields');
        var enddateFields = document.querySelectorAll('.enddate-fields');

        if (this.value === 'date') {
            dateFields.forEach(function(field) {
                field.style.display = 'block';
            });
            enddateFields.forEach(function(field) {
                field.style.display = 'none';
            });
        } else if (this.value === 'enddate') {
            dateFields.forEach(function(field) {
                field.style.display = 'none';
            });
            enddateFields.forEach(function(field) {
                field.style.display = 'block';
            });
        } else {
            dateFields.forEach(function(field) {
                field.style.display = 'none';
            });
            enddateFields.forEach(function(field) {
                field.style.display = 'none';
            });
        }
    });
</script>
@endpush

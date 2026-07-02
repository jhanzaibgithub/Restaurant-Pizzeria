@extends('layouts.admin.app')
@section('title', translate('Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.pos.order.partials._posCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        <div class="pb-4">
            <div class="row justify-content-between align-items-center gy-2">
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
                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('admin.pos.export-excel') }}?branch_id={{ $branch_id }}&from={{ $from }}&to={{ $to }}&search={{ $search }}">
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
        <div class="card">

            <div class="card-header">
                    <h3>
                        <span>
                            {{ translate('order_history') }}
                        </span>
                    </h3>
            </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-hover table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ translate('Date') }}</th>
                                <th>{{ translate('Order_ID') }}</th>
                                <th>{{ translate('Branch') }}</th>
                                <th>{{ translate('Total_Amount') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-center">{{ translate('invoice') }}</th>
                            </tr>
                        </thead>
                        <tbody id="set-rows">
                            @foreach ($orders as $key => $order)
                                <tr class="status-{{ $order['order_status'] }} class-all">
                                    <td>
                                        <div>{{ date('M d, Y', strtotime($order['created_at'])) }}</div>
                                    </td>
                                    <td>
                                        <a class="text-order_id text-dark-hold"
                                            href="{{ route('admin.pos.order-details', ['id' => $order['id']]) }}">{{ $order['id'] }}</a>
                                    </td>
                                    <td>{{ $order->branch->name ?? '' }}</td>
                                    <td>
                                        <div>
                                            {{ \App\CentralLogics\Helpers::set_symbol($order['order_amount'] + $order['delivery_charge']) }}
                                        </div>
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
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-lg-end">
                    {!! $orders->links() !!}
                </div>
            </div>
        </div>
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
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
        <script>
            function print_invoice(order_id) {
                $.get({
                    url: '{{url('/')}}/admin/pos/invoice/'+order_id,
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

@endpush

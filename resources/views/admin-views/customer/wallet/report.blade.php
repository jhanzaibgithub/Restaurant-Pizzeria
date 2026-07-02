@extends('layouts.admin.app')
@section('title',translate('customer_Wallet').' '.translate('report'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
@include('admin-views.customer.partials._customers-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
                    <div class="card mb-3">
                        <div class="card-header">
                                <div class="d-flex flex-column gap-2 align-items-start mb-2">
                                    <h3 class="h1 mb-0 d-flex align-items-center gap-2">
                                        <span class="page-header-title">
                                        {{translate('customer')}} {{translate('wallet')}} {{translate('report')}}
                                        </span>
                                    </h3>
                                </div>
                        </div>
                    </div>
        <div class="card mb-3">
            <div class="card-header text-capitalize">
                <h5 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-filter-outlined"></i>
                    </span>
                    <span>{{translate('filter')}} {{translate('options')}}</span>
                </h5>
            </div>
            <div class="card-body">
                <form action="{{route('admin.customer.wallet.report')}}" method="get">
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <input type="date" name="from" id="from_date" value="{{request()->get('from')}}" class="form-control h--45px" title="{{translate('from')}} {{translate('date')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <input type="date" name="to" id="to_date" value="{{request()->get('to')}}" class="form-control h--45px" title="{{ucfirst(translate('to'))}} {{translate('date')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                @php
                                    $transaction_status=request()->get('transaction_type');
                                @endphp
                                <select name="transaction_type" id="" class="form-control h--45px" title="{{translate('select')}} {{translate('transaction_type')}}">
                                    <option value="">{{translate('all')}}</option>
                                    <option value="add_fund_by_admin" {{isset($transaction_status) && $transaction_status=='add_fund_by_admin'?'selected':''}} >{{translate('add_fund_by_admin')}}</option>
                                    <option value="referral_order_place" {{isset($transaction_status) && $transaction_status=='referral_order_place	'?'selected':''}}>{{translate('referral_order_place')}}</option>
                                    <option value="loyalty_point_to_wallet" {{isset($transaction_status) && $transaction_status=='loyalty_point_to_wallet'?'selected':''}}>{{translate('loyalty_point_to_wallet')}}</option>
                                    <option value="order_place" {{isset($transaction_status) && $transaction_status=='order_place'?'selected':''}}>{{translate('order_place')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <select id='customer' name="customer_id" data-placeholder="{{translate('Select_Customer')}}" class="js-data-example-ajax form-control h--45px" title="{{translate('select_customer')}}">
                                            @if (request()->get('customer_id') && $customerInfo)
                                                <option value="{{$customerInfo->id}}" selected>{{$customerInfo->f_name.' '.$customerInfo->l_name}}({{$customerInfo->phone}})</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn-white text-order_id border-primary">
                            {{translate('reset')}}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="tio-filter-list mr-1"></i>{{translate('filter')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row g-2 px-5">
            @php
                $credit = $data[0]->total_credit;
                $debit = $data[0]->total_debit;
                $balance = $credit - $debit;
            @endphp
            <div class="col-sm-4">
                <div class="dashboard--card bg--2">
                <img class="resturant-icon mb-3" src="{{asset('assets/admin/img/dashboard/3.png')}}" alt="dashboard">
                    <h4 class="title text-white m-0">{{translate('debit')}}</h4>
                    <span class="subtitle text-white">
                        {{\App\CentralLogics\Helpers::set_symbol($debit)}}
                    </span>

                </div>
            </div>
            <div class="col-sm-4">
                <div class="dashboard--card bg--3">
                    <img class="resturant-icon mb-3" src="{{asset('assets/admin/img/dashboard/4.png')}}" alt="dashboard">
                    <h4 class="title text-white m-0">{{translate('credit')}}</h4>
                    <span class="subtitle text-white">
                        {{\App\CentralLogics\Helpers::set_symbol($credit)}}
                    </span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="dashboard--card bg--1">
                    <img class="resturant-icon mb-3" src="{{asset('assets/admin/img/dashboard/1.png')}}" alt="dashboard">
                    <h4 class="title text-white m-0">{{translate('balance')}}</h4>
                    <span class="subtitle text-white">
                        {{\App\CentralLogics\Helpers::set_symbol($balance)}}
                    </span>
                </div>
            </div>
        </div>
        <div class="card mt-3">

           <div class="card-top px-card pt-4">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-md-4 col-lg-4">
                        <h3 class="d-flex align-items-center gap-2 mb-0">
                            {{translate('Customer_Transactions')}}
                        </h3>
                        <span class="text-muted"> {{ $transactions->total() }} Transactions</span>
                    </div>
                    <div class="col-md-8 col-lg-8 d-flex flex-row justify-content-end gap-2">

                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Search by customer ID') }}"
                                    aria-label="Search" value="" required autocomplete="off" />
                                <button
                                    class="btnSearchArrow" type="submit">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                        <button type="button"  class="btnExport" data-toggle="dropdown"
                            aria-expanded="false">

                            {{ translate('export') }}
                            <i class="tio-download-to"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                    href="">
                                    <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                        alt="">
                                    {{ translate('Excel') }}
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                           class="table table-thead-bordered table-align-middle card-table table-nowrap">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{translate('transaction')}} {{translate('id')}}</th>
                            <th>{{translate('customer')}}</th>
                            <th>{{translate('credit')}}</th>
                            <th>{{translate('debit')}}</th>
                            <th>{{translate('balance')}}</th>
                            <th>{{translate('transaction_type')}}</th>
                            <th>{{translate('created_at')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $k=>$wt)
                            <tr scope="row">
                                <td>{{$k+$transactions->firstItem()}}</td>
                                <td><small>{{$wt->transaction_id}}</small></td>
                                <td><a class="text-muted" href="{{route('admin.customer.view',['user_id'=>$wt->user_id])}}">{{Str::limit($wt->user?$wt->user->f_name.' '.$wt->user->l_name:translate('not_found'),20,'...')}}</a></td>
                                <td>{{$wt->credit}}</td>
                                <td>{{$wt->debit}}</td>
                                <td>{{$wt->balance}}</td>
                                <td>
                                    <strong><span style="text-decoration:underline" class=" text-{{$wt->transaction_type=='order_refund'
                                        ?'danger'
                                        :($wt->transaction_type=='loyalty_point'?'warning'
                                            :($wt->transaction_type=='order_place'
                                                ?'info'
                                                :'success'))
                                        }}">
                                        {{ translate($wt->transaction_type)}}
                                    </span></strong>
                                </td>
                                <td>{{date('M d, Y '.config('timeformat'), strtotime($wt->created_at))}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(!$transactions)
                        <div class="empty--data">
                            <img src="{{asset('assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                    @endif
                </div>
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <div>
                            {!! $transactions->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @push('script')

        @endpush

        @push('script_2')

            <script>
                $(document).on('ready', function () {
                    $('.js-data-example-ajax').select2({
                        ajax: {
                            url: '{{route('admin.customer.select-list')}}',
                            data: function (params) {
                                return {
                                    q: params.term, 
                                    all:true,
                                    page: params.page
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            __port: function (params, success, failure) {
                                var $request = $.ajax(params);

                                $request.then(success);
                                $request.fail(failure);

                                return $request;
                            }
                        }
                    });
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

                $('#reset_btn').click(function(){
                    $('#customer').val(null).trigger('change');
                })
            </script>
    @endpush

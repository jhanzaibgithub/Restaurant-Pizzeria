@extends('layouts.admin.app')
@section('title', translate('Customer List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="ml-5">
@include('admin-views.customer.partials._customersCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        <div class="card">
                        <div class="card-top px-card pt-4">
                                <div class="row justify-content-between align-items-center gy-2">
                                    <div class="col-sm-2 col-md-4 col-lg-2">
                                        <h3 class="d-flex align-items-center gap-2 mb-0">
                                            {{translate('All_Customers')}}
                                        </h3>
                                        <span class="text-muted"> {{ $customers->total() }} customers</span>
                                    </div>
                                    <div class="col-sm-10 col-md-8 col-lg-10 d-flex flex-row justify-content-end gap-2">

                                        <form action="{{ url()->current() }}" method="GET">
                                            <div class="input-group">
                                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                    placeholder="{{ translate('Search by customer ID') }}"
                                                    aria-label="Search" value="{{ $search }}" required autocomplete="off" />
                                                <button
                                                    class="btnSearchArrow" type="submit">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <div>
                                            <button type="button"  class="btnExport" data-toggle="dropdown"
                                                aria-expanded="false">{{ translate('export') }}
                                                <i class="tio-download-to"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="{{route('admin.customer.excel_import')}}" type="submit" class="dropdown-item d-flex align-items-center gap-2">
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
                        <div class="py-3">
                            <div class="table-responsive datatable-custom">
                                <table class="table table-hover table-border table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="">
                                                {{translate('SL')}}
                                            </th>
                                            <th>{{translate('Name')}}</th>
                                            <th>{{translate('Contact_Info')}}</th>
                                            <th>{{translate('Orders')}}</th>
                                            <th>{{translate('Order_Amount')}}</th>
                                            <th>{{translate('Points')}}</th>
                                            <th>{{translate('status')}}</th>
                                            <th class="text-center">{{translate('actions')}}</th>
                                        </tr>
                                    </thead>

                                    <tbody id="set-rows">
                                        @include('admin-views.customer.partials._table',['customers'=>$customers])
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-4 px-3">
                                <div class="d-flex justify-content-lg-center">
                                    {!! $customers->links() !!}
                                </div>
                            </div>
                        </div>
        </div>
        <div class="modal fade" id="add-point-modal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="modal-content"></div>
            </div>
        </div>
    </div>
        <div id="datatableFilterSidebar" class="hs-unfold-content_ sidebar sidebar-bordered sidebar-box-shadow initial-hidden">
            <div class="card card-lg sidebar-card sidebar-footer-fixed">
                <div class="card-header">
                    <h4 class="card-header-title">Customer Filter</h4>
                    <a class="js-hs-unfold-invoker_ btn btn-icon btn-xs btn-ghost-dark ml-2" href="javascript:;"
                    onclick="$('#datatableFilterSidebar,.hs-unfold-overlay').hide(500)">
                        <i class="tio-clear tio-lg"></i>
                    </a>
                </div>

                <?php
                $filter_count=0;
                if(isset($last_order_date)) $filter_count += 1;
                if(isset($order_number)) $filter_count += 1;
                if(isset($amount_spend)) $filter_count += 1;
                if(isset($branch_id)) $filter_count += 1;
                if(isset($time_slot)) $filter_count += 1;
                ?>
                <form class="card-body sidebar-body sidebar-scrollbar" action="{{route('admin.customer.filter')}}" method="POST" id="order_filter_form">
                    @csrf
                    <small class="text-cap mb-3">{{translate('Last Order Date')}}</small>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group m-0">
                                <input type="date" name="last_order_date" class="form-control" id="last_order_date" value="{{isset($last_order_date)?$last_order_date:''}}">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <small class="text-cap mb-3">{{translate('Number of Orders')}}</small>

                    <div class="row">
                        <div class="col-12">
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="more_than_number" name="order_number" class="custom-control-input" {{ isset($order_number_radio) && $order_number_radio == 'more_than_number' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="more_than_number">{{translate('More than this number')}}</label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="less_than_number" name="order_number" class="custom-control-input" {{ isset($order_number_radio) && $order_number_radio == 'less_than_number' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="less_than_number">{{translate('Less than this number')}}</label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="exact_number" name="order_number" class="custom-control-input" {{ isset($order_number_radio) && $order_number_radio == 'exact_number' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="exact_number">{{translate('This exact number')}}</label>
                            </div>

                            <div id="inputField" style="display: {{ isset($number_input) ? '' : 'none' }};">
                                <label for="number_input">{{translate('Enter the number:')}}</label>
                                <input type="text" id="number_input" name="number_input" class="form-control"
                                value="{{ isset($number_input) ? $number_input : '' }}">
                            </div>

                            <input type="hidden" name="order_number_radio" id="order_number_radio" value="">

                        </div>
                    </div>

                    <hr class="my-4">
                    <small class="text-cap mb-3">{{translate('Amount Spend')}}</small>

                    <div class="row">
                        <div class="col-12">
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="amount_more" name="amount_spend" class="custom-control-input" {{ isset($amount_spend_radio) && $amount_spend_radio == 'amount_more' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="amount_more">{{translate('More than this amount')}}</label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="amount_less" name="amount_spend" class="custom-control-input" {{ isset($amount_spend_radio) && $amount_spend_radio == 'amount_less' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="amount_less">{{translate('Less than this amount')}}</label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="exact_amount" name="amount_spend" class="custom-control-input" {{ isset($amount_spend_radio) && $amount_spend_radio == 'exact_amount' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="exact_amount">{{translate('This exact amount')}}</label>
                            </div>

                            <div id="amountInputField" style="display: {{ isset($amount_input) ? '' : 'none' }};;">
                                <label for="amount_input">{{translate('Enter the amount:')}}</label>
                                <input type="text" id="amount_input" name="amount_input" class="form-control"
                                value="{{ isset($amount_input) ? $amount_input : '' }}">
                            </div>

                            <input type="hidden" name="amount_spend_radio" id="amount_spend_radio" value="">
                        </div>
                    </div>

                    <hr class="my-4">

                    <small class="text-cap mb-3">{{translate('Select Branches')}}</small>
                    <div class="form-group">
                        <select name="branch_id" id="branch_id" class="form-control js-select2-custom">
                            <option selected disabled>---{{translate('select')}}---</option>
                            @foreach($branches as $branch)
                                <option value="{{$branch->id}}" {{ $branch_id == $branch->id ? 'selected' : '' }}>{{$branch->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4">

                    <small class="text-cap mb-3">{{translate('Order Time Slot')}}</small>
                    <div class="form-group">
                        <input type="datetime-local" name="time_slot" class="form-control" id="time_slot" value="{{isset($time_slot)?$time_slot:''}}">
                    </div>
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
                </form>
            </div>
        </div>
@endsection

@push('script_2')

    <script>
        $(document).on('ready', function () {
            @if($filter_count>0)
            $('#filter_count').html({{$filter_count}});
            @endif

            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            function handleRadioButtonChange(radioButtons, inputField, hiddenInput) {
                radioButtons.forEach(radioButton => {
                    radioButton.addEventListener('change', () => {
                        if (radioButton.checked) {
                            inputField.style.display = 'block';
                            hiddenInput.value = radioButton.id;
                        } else {
                            inputField.style.display = 'none';
                            hiddenInput.value = '';
                        }
                    });
                });
            }

            const orderNumberRadioButtons = document.querySelectorAll('input[name="order_number"]');
            const orderNumberRadioInput = document.getElementById('order_number_radio');
            handleRadioButtonChange(orderNumberRadioButtons, inputField, orderNumberRadioInput);

            const amountSpendRadioButtons = document.querySelectorAll('input[name="amount_spend"]');
            const amountSpendRadioInput = document.getElementById('amount_spend_radio');
            handleRadioButtonChange(amountSpendRadioButtons, amountInputField, amountSpendRadioInput);



            $('#datatableSearch').on('mouseup', function (e) {
                var $input = $(this),
                    oldValue = $input.val();

                if (oldValue == "") return;

                setTimeout(function () {
                    var newValue = $input.val();

                    if (newValue == "") {
                        datatable.search('').draw();
                    }
                }, 1);
            });
        });

        $('#reset').on('click', function(){
            location.href = '{{url('/')}}/admin/customer/filter/reset';
        });
    </script>

    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.customer.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.card-footer').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

        function add_point(form_id, route, customer_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: route,
                data: $('#' + form_id).serialize(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.show-point-' + customer_id).text('( {{translate('Available Point : ')}} ' + data.updated_point + ' )');
                    $('.show-point-' + customer_id + '-table').text(data.updated_point);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function set_point_modal_data(route) {
            $.get({
                url: route,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#add-point-modal').modal('show');
                    $('#modal-content').html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>
@endpush

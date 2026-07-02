@extends('layouts.admin.app')

@section('title', translate('Product Report'))

@push('css_or_js')

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
                            <h2 class="page-header-title">{{translate('product_Report_Overview')}}</h2>

                            <div class="">
                                <span>{{translate('admin')}}:</span>
                                <a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form action="javascript:" id="search-form" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-sm-6">
                        <select class="custom-select" name="branch_id" id="branch_id"
                            required>
                            <option selected value="all">{{translate('all')}}</option>
                            @foreach($branches as $branch)
                            <option
                                value="{{$branch->id}}" {{session('branch_filter')==$branch->id?'selected':''}}>{{$branch->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <select class="form-control js-select2-custom" name="product_id"
                            id="product_id" required>
                            <option disabled>---{{translate('select')}} {{translate('product')}}---</option>
                            <option selected value="all">{{translate('all')}}</option>

                            @foreach($products as $product)
                            <option
                                value="{{$product->id}}">
                                {{$product->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-5">
                        <input type="date" name="from" id="from_date"
                            class="form-control" required>
                    </div>
                    <div class="col-sm-6 col-md-5">
                        <input type="date" name="to" id="to_date"
                            class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit"
                            class="btn btn-primary btn-block">{{translate('show')}}</button>
                    </div>
                </div>
                <div class="row g-2 mt-3">
                    <div class="col-md-6 d-flex flex-column gap-2">
                        <strong>
                            {{translate('total')}} {{translate('orders')}} : <span id="order_count"> </span>
                        </strong>
                        <strong>
                            {{translate('total')}} {{translate('item')}} {{translate('qty')}}:<span id="item_count"></span>
                        </strong>
                        <strong>
                            {{translate('total')}} {{translate('amount')}} : <span id="order_amount"></span>
                        </strong>
                    </div>
                    <div class="col-md-6 d-flex justify-content-md-end">
                    </div>
                </div>
            </form>
            <div class="table-responsive mt-3" id="set-rows">
                @include('admin-views.report.partials._table',['data'=>[]])
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
<script>
    $(document).on('ready', function() {
        $('.js-nav-scroller').each(function() {
            new HsNavScroller($(this)).init()
        });
        $('.js-select2-custom').each(function() {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });

        var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    className: 'd-none'
                },
                {
                    extend: 'excel',
                    className: 'd-none'
                },
                {
                    extend: 'csv',
                    className: 'd-none'
                },
                {
                    extend: 'pdf',
                    className: 'd-none'
                },
                {
                    extend: 'print',
                    className: 'd-none'
                },
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                classMap: {
                    checkAll: '#datatableCheckAll',
                    counter: '#datatableCounter',
                    counterInfo: '#datatableCounterInfo'
                }
            },
            language: {
                zeroRecords: '<div class="text-center p-4">' +
                    '<img class="mb-3" src="{{asset('
                assets / admin ')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">' +
                '<p class="mb-0">{{translate('
                No data to show ')}}</p>' +
                '</div>'
            }
        });

        $('.js-tagify').each(function() {
            var tagify = $.HSCore.components.HSTagify.init($(this));
        });
    });

    function filter_branch_orders(id) {
        location.href = '{{url(' / ')}}/admin/orders/branch-filter/' + id;
    }
</script>

<script>
    $('#search-form').on('submit', function() {
        $.post({
            url: "{{route('admin.report.product-report-filter')}}",
            data: $('#search-form').serialize(),

            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                $('#order_count').html(data.order_count);
                $('#order_amount').html(data.order_sum);
                $('#item_count').html(data.item_qty);
                $('#set-rows').html(data.view);
                $('.card-footer').hide();
            },
            complete: function() {
                $('#loading').hide();
            },
        });
    });
</script>
<script>
    $('#from_date,#to_date').change(function() {
        let fr = $('#from_date').val();
        let to = $('#to_date').val();
        if (fr != '' && to != '') {
            if (fr > to) {
                $('#from_date').val('');
                $('#to_date').val('');
                toastr.error('{{translate('
                    Invalid date range!')}}', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
            }
        }
    });
</script>
@endpush
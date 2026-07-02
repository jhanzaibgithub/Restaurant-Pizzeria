@extends('layouts.admin.app')

@section('title', translate('Sale Report'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
    @include('admin-views.report.partials._reportCAL-setup-inline-menu')
</div>
<hr class="li_hr-top">
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="card mb-3">
        <div class="card-header d-flex flex-column align-items-baseline">
            <div>
                <h2>{{translate('sale')}} {{translate('report')}} {{translate('overview')}}</h2>
            </div>
            <div class="mb-1">
                <span>{{translate('admin')}}:</span>
                <u class="text-order_id"> <a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a></u>
            </div>

        </div>
    </div>

    <!-- End Page Header -->


    <div class="card mt-3">
        <div class="card-body">
            <form action="javascript:" id="search-form" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-sm-6 col-md-4">
                        <select class="custom-select custom-select" name="branch_id" id="branch_id"
                            required>
                            <option selected disabled>{{translate('Select Branch')}}</option>
                            <option value="all">All</option>
                            @foreach($branches as $branch)
                            <option value="{{$branch->id}}" {{session('branch_filter')==$branch->id?'selected':''}}>{{$branch->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <input type="date" name="from" id="from_date"
                            class="form-control" required>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <input type="date" name="to" id="to_date"
                            class="form-control" required>
                    </div>
                    <div class="col-md-6 d-flex flex-column gap-2">
                        <div>
                            <strong>
                                {{translate('total_Orders')}} :
                                <span id="order_count"> </span>
                            </strong>
                        </div>
                        <div>
                            <strong>
                                {{translate('total_Item_Qty')}}
                                : <span
                                    id="item_count"> </span>
                            </strong>
                        </div>
                        <div>
                            <strong>{{translate('total')}} {{translate('amount')}} : <span
                                    id="order_amount"></span>
                            </strong>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-4">
                        <div class="d-flex justify-content-end align-items-end gap-3">
                            <button type="reset" class="btn btn-white border-primary text-order_id">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <div class="table-responsive datatable_wrapper_row mt-5" id="set-rows">
                @include('admin-views.report.partials._table',['data'=>[]])
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
<script>
    $('#search-form').on('submit', function() {
        $.post({
            url: "{{route('admin.report.sale-report-filter')}}",
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
                toastr.error('Invalid date range!', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('input').addClass('form-control');
    });

    // INITIALIZATION OF DATATABLES
    // =======================================================
    var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
        dom: 'Bfrtip',
        language: {
            zeroRecords: '<div class="text-center p-4">' +
                '<img class="mb-3" src="{{asset('
            assets / admin ')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">' +
            '<p class="mb-0">{{translate('
            No data to show ')}}</p>' +
            '</div>'
        }
    });
</script>
@endpush
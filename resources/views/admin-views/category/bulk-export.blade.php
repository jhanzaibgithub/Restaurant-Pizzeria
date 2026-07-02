@extends('layouts.admin.app')
@section('title', translate('Category Bulk Import'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
    @include('admin-views.category.partials._bulk-setup-inline-menu')
</div>
<hr  class="li_hr-top">
    <div class="content container-fluid">
        <div  class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
            <div>
             <h3>
                {{translate('Bulk_Import&Export')}}
             </h3>
            </div>
            <div>
                @include('admin-views.category.partials._bulkImp&Exp-setup-inline-menu')
                                    <hr class="li_hr">
            </div>
         </div>
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="p-2 pt-3">
                        <div class="ml-2">
                            <h3>{{translate('Category_Bulk_Export :')}} </h3>
                          </div>
                        <div class="export-steps">
                            <div class="export-steps-item">
                                <div class="inner border-dashed">

                                    <h5>{{translate('STEP 1')}}</h5>
                                    <p>
                                        {{translate('Select_Data_Type')}}
                                    </p>
                                </div>
                            </div>
                            <div class="export-steps-item">
                                <div class="inner">
                                    <h5>{{translate('STEP 2')}}</h5>
                                    <p>
                                        {{translate('Select Data Range and Export')}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form class="product-form px-3 pb-3" action="{{url()->current()}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('Type')}}</label>
                                        <select onchange="showHide(this)" name="type" id="type" data-placeholder="Select Type" class="form-control" required="" title="Select Type">
                                            <option value="all">{{translate('All Data')}}</option>
                                            <option value="date_wise">{{translate('Date Wise')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 date-wise-div" style="display: none">
                                    <div class="form-group date_wise">
                                        <label class="input-label">{{translate('From Date')}}</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 date-wise-div" style="display: none">
                                    <div class="form-group date_wise">
                                        <label class="input-label">{{translate('To Date')}}</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <button class="btn btn-white text-order_id" type="reset">{{translate('Clear')}}</button>
                                        <button class="btn btn-primary" type="submit" title="Bulk export">{{translate('Export')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function showHide(t) {
            let selectValue = $(t).find(":selected").val()
            if(selectValue === 'all') {
                $('.date-wise-div').hide()
            } else if (selectValue === 'date_wise') {
                $('.date-wise-div').css('display', 'block')
            }
        }
    </script>

@endpush

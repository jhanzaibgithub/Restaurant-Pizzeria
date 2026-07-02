@extends('layouts.admin.app')
@section('title', translate('Product Bulk Import'))

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
                <div class="card card-body">
                    <h3>{{translate('Product_Bulk_Import :')}} </h3>
                    <h3>{{translate('Instructions :')}} </h3>

                    <ol class="order-list">
                        <li>{{translate('Download the format file and fill it with proper data.')}}</li>
                        <li>{{translate('You can download the example file to understand how the data must be filled.')}}</li>
                        <li>{{translate('Once you have downloaded and filled the format file, upload it in the form below and submit.')}}</li>
                        <li>{{\App\CentralLogics\translate("After uploading products you need to edit them and set product's images and choices.")}}</li>
                        <li>{{translate('You can get category and sub-category id from their list, please input the right ids.')}}</li>
                    </ol>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-body">
                    <form class="product-form" action="{{route('admin.product.bulk-import')}}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="rest-part">
                            <div class="d-flex flex-wrap justify-content-center gap-3">
                                <h4 class="mb-0">{{translate('Do_not_have_the_template')}}?</h4>
                                <a href="{{asset('assets/product_bulk_format.xlsx')}}" download=""
                                class="fz-16 btn-link text-order_id">{{translate('Download_Here')}}</a>
                            </div>
                            <div class="mt-5">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-auto">
                                            <div class="upload-file">
                                                <input type="file" id="import-file" name="products_file" accept=".xlsx, .xls" class="upload-file__input">
                                                <div class="upload-file__img_drag upload-file__img">
                                                    <img src="{{asset('assets/admin/img/icons/drug_file.png')}}" alt="">
                                                </div>
                                                <div class="file--img"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-3">
                                    <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                    <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $('#import-file').on('change', function(){
            if($(this)[0].files.length !== 0){
                $('.file--img').empty().append(`<div class="my-2"> <img width="200" src="{{asset('assets/admin/img/icons/excel.png')}}" alt=""></div>`)
            }
        })
        $('.product-form').on('reset', function(){
            $('.file--img').empty()
        })

    </script>

@endpush

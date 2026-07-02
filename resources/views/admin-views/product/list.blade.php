@extends('layouts.admin.app')

@section('title', translate('Product List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
        <div class="ml-5">
             @include('admin-views.category.partials._menuBtn-setup-inline-menu')
        </div>
        <hr  class="li_hr-top">
    <div class="content container-fluid">
        <div  class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
            <div>
             <h3>
                {{translate('Product_List')}}
             </h3>
            </div>
            <div>
                @include('admin-views.product.partials._product-setup-inline-menu')
                                    <hr class="li_hr">
            </div>
         </div>
        <div class="row g-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-top px-card pt-4">
                        <div class="row justify-content-between align-items-center gy-2">
                            <div class="col-sm-4 col-md-4">
                                <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                                    <span class="page-header-title">
                                        {{translate('Product_List')}}
                                    </span>
                                </h2>
                                <span class="text-muted">Over {{ $products->total() }} new products</span>
                            </div>
                            <div class="col-lg-8 col-md-8 d-flex flex-row justify-content-end gap-2">
                                <div>
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ translate('Search by Product ID') }}" aria-label="Search"
                                                value="" required autocomplete="off" />
                                            <button class="btnSearchArrow" type="submit">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btnExport d-flex align-items-center" data-toggle="dropdown" aria-expanded="false">
                                        {{ translate('export') }}
                                        <i class="tio-download-to"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{route('admin.product.excel-import', ['search' => $search])}}">
                                                <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}" alt="">
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
                            <table class="table table-border-dashed table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{translate('ID')}}</th>
                                    <th>{{translate('image')}}</th>
                                    <th>{{translate('name')}}</th>
                                    <th>{{translate('price')}}</th>
                                    <th>{{translate('status')}}</th>
                                </tr>
                                </thead>
                                <tbody id="set-rows">
                                @foreach($products as $key=>$product)
                                    <tr>
                                        <td class="text-order_id">{{$products->firstitem()+$key}}</td>
                                        <td>
                                                <div>
                                                    <img width="70" class="avatar-img rounded" src="{{asset('storage/product')}}/{{$product['image']}}" class="rounded img-fit"
                                                        onerror="this.src='{{asset('assets/admin/img/160x160/img2.jpg')}}'">
                                                </div>
                                        </td>
                                        <td>
                                        <div class="media-body">
                                                    <a class="input-label" href="{{route('admin.product.view',[$product['id']])}}">
                                                        {{ Str::limit($product['name'], 30) }}
                                                    </a>
                                                </div>
                                        </td>
                                        <td>{{ \App\CentralLogics\Helpers::set_symbol($product['price']) }}</td>
                                        <td>
                                            <div class="d-flex flex-row align-items-center gap-2 ">
                                                    <label class="switcher">
                                                        <input id="{{$product['id']}}" class="switcher_input" type="checkbox" {{$product['status']==1? 'checked' : ''}} data-url="{{route('admin.product.status',[$product['id'],0])}}" onchange="status_change(this)">
                                                        <span class="switcher_control"></span>
                                                    </label>

                                                    <a class="btn btn-secondary btn-sm edit square-btn"
                                                    href="{{route('admin.product.edit',[$product['id']])}}"><i style="color:#A1A5B7;" class="tio-edit"></i></a>
                                                    <button type="button" class="btn btn-secondary btn-sm delete square-btn"
                                                    onclick="form_alert('product-{{$product['id']}}','{{translate('Want to delete this item ?')}}')"><i style=" color: #A1A5B7;" class="tio-delete"></i></button>

                                                    <form action="{{route('admin.product.delete',[$product['id']])}}"
                                                        method="post" id="product-{{$product['id']}}">
                                                        @csrf @method('delete')
                                                    </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4 px-3">
                            <div class="d-flex justify-content-lg-center">
                                {!! $products->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
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
         $filter_count=0;?>
        <?php
         $filter_count=0;
         if(isset($product_type)) $filter_count += 1;
         if(isset($branch_id)) $filter_count += 1;
         if(isset($product_section)) $filter_count += 1;
         if(isset($status)) $filter_count += 1;
         if(isset($priority)) $filter_count += 1;
         ?>
      <form class="card-body sidebar-body sidebar-scrollbar" action="{{route('admin.product.filter')}}" method="POST">
         @csrf

        <small class="text-cap mb-3">{{translate('product')}} {{translate('type')}}</small>
        <div class="form-group">
            <select name="product_type" id="product_type" class="form-control js-select2-custom">
                <option selected disabled>---{{translate('select')}}---</option>
                <option value="all" {{ $product_type == 'all' ? 'selected' : '' }}>{{translate('All')}}</option>
                <option value="veg" {{ $product_type == 'veg' ? 'selected' : '' }}>{{translate('veg')}}</option>
                <option value="nonveg" {{ $product_type == 'nonveg' ? 'selected' : '' }}>{{translate('nonveg')}}</option>
            </select>
        </div>

        <small class="text-cap mb-3">{{translate('product_by_branches')}}</small>
        <div class="form-group">
            <select name="branch_id" id="branch_id" class="form-control js-select2-custom">
                <option selected disabled>---{{translate('select')}}---</option>
                @foreach($branches as $branch)
                    <option value="{{$branch->id}}" {{ $branch_id == $branch->id ? 'selected' : '' }}>{{$branch->name}}</option>
                @endforeach
            </select>
        </div>

        <small class="text-cap mb-3">{{translate('Product_Section')}}</small>
        <div class="form-group">
            <select name="product_section" id="product_section" class="form-control js-select2-custom">
                <option selected disabled>---{{translate('select')}}---</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" {{ $product_section == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                @endforeach
            </select>
        </div>

        <small class="text-cap mb-3">{{translate('status')}}</small>
        <div class="form-group">
            <select name="status" id="status" class="form-control js-select2-custom">
                <option selected disabled>---{{translate('select')}}---</option>
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>{{translate('All')}}</option>
                <option value="1" {{ $status === '1' ? 'selected' : '' }}>{{translate('active')}}</option>
                <option value="0" {{ $status === '0' ? 'selected' : '' }}>{{translate('inactive')}}</option>
            </select>
        </div>

        <small class="text-cap mb-3">{{translate('priority')}}</small>
        <div class="form-group">
            <select name="priority" id="priority" class="form-control js-select2-custom">
                <option selected disabled>---{{translate('select')}}---</option>
                <option value="all" {{ $priority === 'all' ? 'selected' : '' }}>{{translate('All')}}</option>
                <option value="0" {{ $priority === '0' ? 'selected' : '' }}>{{translate('normal')}}</option>
                <option value="1" {{ $priority === '1' ? 'selected' : '' }}>{{translate('medium')}}</option>
                <option value="2" {{ $priority === '2' ? 'selected' : '' }}>{{translate('high')}}</option>
            </select>
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


            $('.js-tagify').each(function () {
                var tagify = $.HSCore.components.HSTagify.init($(this));
            });
        });

        $('#reset').on('click', function(){
            location.href = '{{url('/')}}/admin/product/filter/reset';
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
                url: '{{route('admin.product.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush

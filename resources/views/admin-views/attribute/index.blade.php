@extends('layouts.admin.app')
@section('title', translate('Add new attribute'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
    @include('admin-views.category.partials._menu-setup-inline-menu')
</div>
<hr class="li_hr-top">
<div class="content container-fluid">
      <div class="row g-3">
            <div class="col-12">
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
                <div class="card mb-3">
                    <div class="card-body">
                    <form action="{{route('admin.attribute.store')}}" method="post">
                        @csrf
                        @php($data = $languageSettings)
                        @php($default_lang = Helpers::get_default_language())

                        @if($data && array_key_exists('code', $data[0]))
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($data as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{$lang['default'] == true ? 'active':''}}" href="#" id="{{$lang['code']}}-link">
                                            {{ Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-12">
                                    @foreach($data as $lang)
                                        <div class="form-group lang_form {{$lang['default'] == false ? 'd-none':''}}" id="{{$lang['code']}}-form">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('Attribute_name')}} </label>
                                            <input type="text" name="name[]" class="form-control"
                                                placeholder="{{translate('New attribute')}}"
                                                {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group lang_form" id="{{$default_lang}}-form">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Attribute_name')}} </label>
                                        <input type="text" name="name[]" class="form-control" placeholder="{{translate('New attribute')}}" maxlength="255">
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$default_lang}}">
                                </div>

                            </div>
                        @endif
                            <div class="col-md-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="reset" class="btn btn-white text-order_id border-primary">{{translate('reset')}}</button>
                                        <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                                    </div>
                            </div>
                    </form>

                    </div>
                </div>
                <div class="card">
                    <div class="card-top px-card pt-4">
                         <div class="row justify-content-between align-items-center gy-2">
                            <div class="col-sm-4 col-md-4">
                                <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                                    <span class="page-header-title">
                                    {{translate('Attribute_List')}}
                                    </span>
                                </h2>
                                <span class="text-muted"> {{ $attributes->total() }} attributes</span>
                            </div>
                            <div class="col-sm-4 col-md-8 d-flex justify-content-end gap-1">
                                    <form action="{{url()->current()}}" method="GET">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ translate('Search by ID, Product') }}" aria-label="Search"
                                                value="" required autocomplete="off" />
                                            <button class="btnSearchArrow" type="submit">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                        </form>
                            </div>
                        </div>
                    </div>
                    <div class="py-4">
                        <div class="table-responsive datatable-custom">
                            <table class="table table-border table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th >{{translate('name')}}</th>
                                        <th>{{translate('status')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($attributes as $key=>$attribute)
                                    <tr>
                                        <td class="text-order_id">{{$attributes->firstitem()+$key}}</td>
                                        <td class="text-dark">
                                            <div>
                                               <strong> {{$attribute['name']}}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                            <label class="switcher">
                                                        <input class="switcher_input" type="checkbox"
                                                            {{ $attribute['status'] == 1 ? 'checked' : '' }}
                                                            id="{{ $attribute['id'] }}" onchange="status_change(this)"
                                                            data-url="{{ route('admin.attribute.status', [$attribute['id'], 1]) }}">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-secondary btn-sm edit square-btn"
                                                    href="{{route('admin.attribute.edit',[$attribute['id']])}}"><i style="color:#A1A5B7;"  class="tio-edit"></i></a>
                                                    <button type="button" class="btn btn-secondary btn-sm delete square-btn"
                                                    onclick="form_alert('attribute-{{$attribute['id']}}','{{translate('Want to delete this attribute ?')}}')"><i style="color:#A1A5B7;"  class="tio-delete"></i></a>
                                            </div>
                                            <form action="{{route('admin.attribute.delete',[$attribute['id']])}}"
                                                method="post" id="attribute-{{$attribute['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4 px-3">
                            <div class="d-flex justify-content-lg-center">
                                {!! $attributes->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();

            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>

    <script>
        $(document).on('ready', function () {
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush


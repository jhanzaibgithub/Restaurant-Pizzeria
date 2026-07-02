@extends('layouts.admin.app')
@section('title', translate('Add new sub category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="ml-5">
@include('admin-views.category.partials._menuCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">

        <div class="row g-3">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{route('admin.category.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @php($data = $languageSettings)
                        @php($default_lang = Helpers::get_default_language())

                        @if($data && array_key_exists('code', $data[0]))
                        <ul class="nav nav-tabs w-fit-content mb-4">
                            @foreach($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="row">
                            <div class="col-sm-6">
                                @foreach($data as $lang)
                                    <div class="form-group {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                        <label class="input-label">{{translate('sub_category')}} {{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="name[]" class="form-control" maxlength="255" placeholder="{{ translate('New Sub Category') }}" {{$lang['status'] == true ? 'required':''}}
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach
                                @else
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group lang_form" id="{{$default_lang}}-form">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('sub_category')}} {{translate('name')}}({{strtoupper($default_lang)}})</label>
                                            <input type="text" name="name[]" class="form-control" placeholder="{{ translate('New Sub Category') }}" required>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$default_lang}}">
                                        @endif
                                        <input name="position" value="1" style="display: none">
                                        <input type="hidden" name="type" value="sub_catddsddeegory">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="input-label">
                                                {{translate('main_category')}}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select id="exampleFormControlSelect1" name="parent_id" class="form-control js-select2-custom" required>
                                                <option disabled selected>{{\App\CentralLogics\translate('Select a category')}}</option>
                                            @foreach($parentCategories as $category)
                                                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="from_part_2 mt-2">
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <img width="105" class="rounded-10 border" id="viewer"
                                                        src="{{ asset('assets/admin/img/400x400/img2.jpg') }}" alt="image" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="from_part_2">
                                            <label>{{ translate('category_Image') }}</label>
                                            <small class="text-danger">* ( {{ translate('ratio') }} 1:1 )</small>
                                            <div class="custom-file">
                                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required
                                                    oninvalid="document.getElementById('en-link').click()">
                                                <label class="custom-file-label" for="customFileEg1">{{ translate('choose file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-3">
                                            <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                            <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            <div class="mt-3">
                <div class="card">
                    <div class="card-top px-card pt-4">
                        <div class="row justify-content-between align-items-center gy-2">
                            <div class="col-sm-4 col-md-6 col-lg-8">
                                <h3 class="d-flex mb-0 gap-2 align-items-center">
                                    {{translate('Sub_Category_List')}}
                                </h3>
                                <span class="text-muted">Over {{ $categories->total() }} new products</span>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ translate('Search by Category\SubCategory') }}"
                                                aria-label="Search" value="{{ $search }}" required autocomplete="off" />
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
                                    <th>{{translate('ID')}}</th>
                                    <th>{{translate('Image')}}</th>
                                    <th>{{translate('Main_Category')}}</th>
                                    <th>{{translate('sub_Category')}}</th>
                                    <th>{{translate('priority')}}</th>
                                    <th>{{translate('status')}}</th>

                                </tr>
                                </thead>
                                <tbody id="set-rows">
                                @foreach($categories as $key=>$category)
                                    <tr>
                                        <td class="text-order_id" >{{$categories->firstitem()+$key}}</td>
                                        <td>
                                            <div>
                                                <img width="50" class="avatar-img rounded" src="{{asset('/storage/category')}}/{{$category['image']}}" onerror="this.src='{{asset('assets/admin/img/icons/category_img.png')}}'" alt="">
                                            </div>
                                        </td>
                                        <td >
                                            <div class="input-label">
                                                {{isset($category->parent) ? $category->parent['name'] : ''}}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-label">
                                                {{$category['name']}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-capitalize">
                                                <form action="{{route('admin.category.priority',$category->id)}}">
                                                    <select name="priority" id="priority" class="form--control-select {{$category->priority == 0 ? 'text--title':''}} {{$category->priority == 1 ? 'text--info':''}} {{$category->priority == 2 ? 'text--success':''}} " onchange="this.form.submit()">
                                                        <option class="text--title" value="0" {{$category->priority == 0?'selected':''}}>{{translate('normal')}}</option>
                                                        <option class="text--info" value="1" {{$category->priority == 1?'selected':''}}>{{translate('medium')}}</option>
                                                        <option class="text--success" value="2" {{$category->priority == 2?'selected':''}}>{{translate('high')}}</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                        <td>

                                                <div class="d-flex flex-row align-items-center gap-2">
                                                    <label class="switcher">
                                                        <input class="switcher_input" type="checkbox" {{$category['status']==1? 'checked' : ''}} id="{{$category['id']}}"
                                                        onchange="status_change(this)" data-url="{{route('admin.category.status',[$category['id'],1])}}"
                                                        >
                                                        <span class="switcher_control"></span>
                                                    </label>


                                                        <a class="btn btn-secondary btn-sm edit square-btn"
                                                            href="{{route('admin.category.edit',[$category['id']])}}">
                                                            <i style=" color: #A1A5B7;" class="tio-edit"></i>
                                                        </a>

                                                        <button class="btn btn-secondary btn-sm delete square-btn"
                                                            onclick="form_alert('category-{{$category['id']}}','{{translate("Want to delete this sub category ?")}}')">
                                                            <i style=" color: #A1A5B7;" class="tio-delete"></i></a>
                                                    </div>
                                                    <form action="{{route('admin.category.delete',[$category['id']])}}"
                                                        method="post" id="category-{{$category['id']}}">
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
                                {!! $categories->links() !!}
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
        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
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
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.category.search')}}',
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
    <script>
        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function () {
            readURL(this, 'viewer2');
        });
    </script>
@endpush

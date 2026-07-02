@extends('layouts.admin.app')
@section('title', translate('Add new addon'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
@include('admin-views.category.partials._menuCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container">

        <div class="row g-3">
            <div class="col-12">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('admin.addon.store')}}" method="post">
                                @csrf
                                @php($data = $languageSettings)
                                @php($default_lang = Helpers::get_default_language())

                                @if ($data && array_key_exists('code', $data[0]))
                                <ul class="nav nav-tabs w-fit-content mb-4">
                                    @foreach ($data as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}" href="#"
                                            id="{{ $lang['code'] }}-link">{{ Helpers::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="row">
                                    <div class="col-sm-12">
                                        @foreach ($data as $lang)
                                            <div class="form-group {{ $lang['default'] == false ? 'd-none' : '' }} lang_form" id="{{ $lang['code'] }}-form">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('name') }} ({{ strtoupper($lang['code']) }})</label>
                                                <input type="text" name="name[]" class="form-control" placeholder="{{translate('New addon')}}"
                                                    {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                        @endforeach
                                        @else
                                        <div class="row">
                                            <div class="col-sm-12 mb-4">
                                                <div class="form-group lang_form" id="{{ $default_lang }}-form">
                                                    <label class="input-label" for="exampleFormControlInput1">{{ translate('name') }} ({{ strtoupper($default_lang) }})</label>
                                                    <input type="text" name="name[]" class="form-control" maxlength="255" placeholder="{{ translate('New addon') }}" required>
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{ $default_lang }}">
                                                @endif
                                                <input name="position" value="0" style="display: none">
                                            </div>
                                            <div class="col-sm-6 from_part_2 mb-4">
                                                <label class="input-label" for="exampleFormControlInput1">{{translate('price')}}</label>
                                                <input type="number" min="0" name="price" step="any" class="form-control"
                                                    placeholder="100" required
                                                    oninvalid="document.getElementById('en-link').click()">
                                            </div>
                                            <div class="col-sm-6 from_part_2 mb-4">
                                                <label class="input-label" for="exampleFormControlInput1">{{translate('tax')}} (%)</label>
                                                <input type="number" min="0" name="tax" step="any" class="form-control"
                                                       placeholder="5" required
                                                       oninvalid="document.getElementById('en-link').click()">
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
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-top px-card pt-4">
                            <div class="d-flex flex-column flex-md-row flex-wrap gap-3 justify-content-md-between align-items-md-center">
                            <div class="col-sm-4 col-md-6 col-lg-8">
                                <h3 class="d-flex align-items-center gap-2">
                                    {{translate('Addon_List')}}
                                </h3>
                                <span class="text-muted">Over {{ $addons->total() }} new products</span>
                            </div>
                                <div class="d-flex flex-wrap justify-content-md-end gap-3">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                    placeholder="{{ translate('Search by Addon') }}"
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
                                            <th>{{translate('name')}}</th>
                                            <th class="text-center">{{translate('tax')}} (%)</th>
                                            <th >{{translate('status')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($addons as $key=>$addon)
                                        <tr>
                                            <td class="text-order_id">{{$addons->firstitem()+$key}}</td>
                                            <td>
                                                <div class="text-capitalize input-label">
                                                    {{$addon['name']}}
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $addon['tax'] }}</td>
                                            <td>
                                            <div class="d-flex flex-row align-items-center gap-2 ">
                                                    <label class="switcher">
                                                        <input class="switcher_input" type="checkbox"
                                                            {{ $addon['status'] == 1 ? 'checked' : '' }}
                                                            id="{{ $addon['id'] }}" onchange="status_change(this)"
                                                            data-url="{{ route('admin.addon.status', [$addon['id'], 1]) }}">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                    <a class="btn btn-secondary btn-sm edit square-btn"
                                                        href="{{route('admin.addon.edit',[$addon['id']])}}">
                                                        <i style=" color: #A1A5B7;" class="tio-edit"></i></a>
                                                    <button class="btn btn-secondary btn-sm delete square-btn" type="button"
                                                        onclick="form_alert('addon-{{$addon['id']}}','{{translate('Want to delete this addon')}} ?')">
                                                        <i style=" color: #A1A5B7;" class="tio-delete"></i></button>
                                                        <form action="{{route('admin.addon.delete',[$addon['id']])}}"
                                                        method="post" id="addon-{{$addon['id']}}">
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
                                    {!! $addons->links() !!}
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

@endpush

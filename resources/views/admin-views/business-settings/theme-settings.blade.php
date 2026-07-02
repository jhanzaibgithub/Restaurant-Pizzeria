@extends('layouts.admin.app')

@section('title', translate('Theme setup'))

@push('css_or_js')
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.business-settings._setting-setup-inline-menu')
    </div>
    <hr>
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{ asset('assets/admin/img/icons/side_setting.png') }}" alt="public">
            </div>
            <span>
                {{ translate('change_theme_for_user_app') }}
            </span>
        </h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.restaurant.theme-settings-update') }}" method="post"
                enctype="multipart/form-data" class="pt-md-5">
                @csrf
                <div class="form-group" id="user_app_theme">

                    <div class="row">
                        <div class='col-md-3 col-sm-6 col-12 text-center'>
                            <input type="radio" name="theme" require id="img1" class="d-none imgbgchk" value="1"
                            {{ $themeValue == 1 ? 'checked' : '' }}>

                            <label for="img1">
                                <img class="img-thumbnail rounded"
                                    src="{{ asset('assets/admin/img/Theme-1.png') }}" alt="Image 1">
                            </label>
                        </div>
                        <div class='col-md-3 col-sm-6 col-12 text-center'>
                            <input type="radio" name="theme" require id="img2" class="d-none imgbgchk" value="2"
                            {{ $themeValue == 2 ? 'checked' : '' }}>
                            <label for="img2">
                                <img class="img-thumbnail rounded"
                                    src="{{ asset('assets/admin/img/Theme-2.png') }}" alt="Image 2">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group pt-2">
                    <div class="btn--container justify-content-end">
                        <button type="submit" id="add" class="btn btn-primary">{{ translate('apply')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script_2')
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.ckeditor').ckeditor();
});
</script>
@endpush

@extends('layouts.admin.app')

@section('title', translate('Terms and Conditions'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
    @include('admin-views.business-settings._setting-setup-inline-menu')
</div>
<hr class="li_hr">
    <div class="content container-fluid">
        <div  class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
            <div >
             <h3>
                {{-- {{ translate('Page_setup') }} --}}
             </h3>
            </div>
            <div>
                @include('admin-views.business-settings.partials._page-setup-inline-menu')
             <hr class="li_hr">
            </div>

         </div>

        <div class="row g-2">
            <div class="col-12">
            <div class="card mb-1">
                        <div class="card-header">
                            <h3>{{translate('Terms_and_Conditions')}}</h3>
                        </div>
                    </div>
            <div class="card">
                <div class="card-footer">
                    <form action="{{route('admin.business-settings.page-setup.terms-and-conditions')}}" method="post" id="tnc-form">
                        @csrf
                        <div class="form-group">
                            <textarea class="ckeditor form-control" name="tnc">{!! $tnc?->value ?? '' !!}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-3 align-items-center">
                            <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush

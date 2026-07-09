@extends('layouts.admin.app')

@section('title', translate('Map API Settings'))

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
                  {{-- {{ translate('3rd_party') }} --}}
             </h3>
            </div>
            <div>
                @include('admin-views.business-settings.partials._3rdparty-inline-menu')
             <hr class="li_hr">
            </div>
         </div>

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card mb-3">
                    <div class="card-header">
                       <h4> {{translate('Google_Map_APIs')}}</h4>
                    </div>
                </div>
            <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.third-party.map_api_settings'):'javascript:'}}" method="post"
            enctype="multipart/form-data">
                            @csrf
                <div class="card">
                    <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-12">
                                    <label class="input-label">{{translate('map_api_server')}} {{translate('key:')}}</label>
                                    <textarea name="map_api_server_key" class="form-control">{{env('APP_MODE')!='demo'?($mapApiSettings['map_api_server_key'] ?? ''):''}}</textarea>
                                </div>
                                <div class="form-group col-lg-6 col-md-12">
                                    <label class="input-label">{{translate('map_api_client')}} {{translate('key:')}}</label>
                                    <textarea name="map_api_client_key" class="form-control">{{env('APP_MODE')!='demo'?($mapApiSettings['map_api_client_key'] ?? ''):''}}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="btn--container justify-content-start mt-5">
                                <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush

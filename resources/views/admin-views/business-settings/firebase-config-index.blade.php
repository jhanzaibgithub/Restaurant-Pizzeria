@extends('layouts.admin.app')
@section('title', translate('Settings'))

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
                {{-- {{ translate('system_setup') }} --}}
             </h3>
            </div>
            <div>
                @include('admin-views.business-settings.partials._system-settings-inline-menu')
                <hr class="li_hr">
            </div>
         </div>
        <div class="row gx-2 gx-lg-3">
            @php($data=$firebaseConfig)
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.system-setup.firebase_message_config'):'javascript:'}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>{{translate('Firebase_Configuration')}}</h4>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-footer px-5 mx-3">
                            @if(isset($data))
                             <div class="row">
                             <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('API Key:')}}</label>
                                        <input type="text" placeholder="" class="form-control" name="apiKey"
                                            value="{{env('APP_MODE')!='demo'?$data['apiKey']:''}}" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="input-label">{{translate('Auth Domain:')}}</label>
                                    <input type="text" class="form-control" name="authDomain" value="{{env('APP_MODE')!='demo'?$data['authDomain']:''}}" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="input-label">{{translate('Project ID:')}}</label>
                                    <input type="text" class="form-control" name="projectId" value="{{env('APP_MODE')!='demo'?$data['projectId']:''}}" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="input-label">{{translate('Storage Bucket:')}}</label>
                                    <input type="text" class="form-control" name="storageBucket" value="{{env('APP_MODE')!='demo'?$data['storageBucket']:''}}" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="input-label">{{translate('Messaging Sender ID:')}}</label>
                                    <input type="text" placeholder="" class="form-control" name="messagingSenderId"
                                        value="{{env('APP_MODE')!='demo'?$data['messagingSenderId']:''}}" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="input-label">{{translate('App ID:')}}</label>
                                    <input type="text" placeholder="" class="form-control" name="appId"
                                        value="{{env('APP_MODE')!='demo'?$data['appId']:''}}" required autocomplete="off">
                                    </div>
                                </div>
                            </div>

                                <div class="btn--container">
                                    <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('Clean')}}</button>
                                </div>
                            @else
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                                </div>
                            @endif

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush

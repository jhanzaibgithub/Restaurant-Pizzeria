@extends('layouts.admin.app')

@section('title', translate('SMS Module Setup'))

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
                {{ translate('3rd_party') }}
             </h3>
            </div>
            <div>
                @include('admin-views.business-settings.partials._3rdparty-inline-menu')
             <hr class="li_hr">
            </div>
         </div>
        <div class="card mb-3">
            <div class="card-header">
                <h4>{{translate('Sms_Config')}}</h4>
            </div>
        </div>
<div class="card mb-4">
    <div class="card-footer">
        <div class="row mt-4">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center text-uppercase mb-1">
                            <h5 class="mb-0">{{translate('twilio_SMS')}}</h5>
                            <div class="pl-2">
                                <img src="{{asset('assets/admin/img/twilio.png')}}" alt="public" style="height: 50px">
                            </div>
                        </div>
                        <div>
                            <div class="text-order_id">{{translate('NB : #OTP# will be replace with otp')}}</div>
                        </div>
                        @php($config=$smsConfigs['twilio_sms'] ?? null)
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.sms-module-update',['twilio_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{translate('twilio_sms')}}</label>
                            </div>
                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-4 mr-md-4">
                                    <input type="radio" name="status" id="twilio_sms_active" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span for="twilio_sms_active" class="mb-0">{{translate('active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input type="radio" name="status" id="twilio_sms_inactive" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span for="twilio_sms_inactive" class="mb-0">{{translate('inactive')}} </span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{translate('sid:')}}</label>
                                <input type="text" class="form-control" name="sid"
                                       value="{{env('APP_MODE')!='demo'?$config['sid']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{translate('messaging_service_sid:')}}</label>
                                <input type="text" class="form-control" name="messaging_service_sid"
                                       value="{{env('APP_MODE')!='demo'?$config['messaging_service_sid']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{translate('token:')}}</label>
                                <input type="text" class="form-control" name="token"
                                       value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{translate('from:')}}</label>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{translate('otp_template:')}}</label>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <div class="btn--container">
                            <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center text-uppercase mb-1">
                            <h5 class="mb-0">{{translate('nexmo_SMS')}}</h5>
                            <div class="pl-2">
                                <img src="{{asset('assets/admin/img/nexmo.png')}}" alt="public" style="height: 50px">
                            </div>
                        </div>
                        <div>
                            <div class="text-order_id">{{translate('NB : #OTP# will be replace with otp')}}</div>
                        </div>
                        @php($config=$smsConfigs['nexmo_sms'] ?? null)
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.sms-module-update',['nexmo_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{translate('nexmo_sms')}}</label>
                            </div>
                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-4 mr-md-4">
                                    <input type="radio" name="status" id="nexmo_sms_active" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="mb-0">{{translate('active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input type="radio" name="status" id="nexmo_sms_inactive" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="mb-0">{{translate('inactive')}} </span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{translate('api_key:')}}</label>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{translate('api_secret:')}}</label>
                                <input type="text" class="form-control" name="api_secret"
                                       value="{{env('APP_MODE')!='demo'?$config['api_secret']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{translate('from:')}}</label>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{translate('otp_template:')}}</label>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <div class="btn--container">
                            <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn-primary">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
    </div>
@endsection

@push('script_2')

@endpush

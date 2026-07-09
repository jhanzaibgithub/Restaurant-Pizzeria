@extends('layouts.admin.app')

@section('title', translate('reCaptcha Setup'))

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
        @php($config=$recaptchaConfig)
        <form
        action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.third-party.recaptcha_update',['recaptcha']):'javascript:'}}"
        method="post">
        @csrf
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 >{{translate('Google Recapcha Information')}}</h3>
                                            <label class="switcher">
                                                <input id="" class="switcher_input" type="checkbox"  data-url="" onchange="status_change(this)">
                                                <span class="switcher_control"></span>
                                            </label>

                    </div>
                </div>
            <div class="card mb-3">
                <div class="card-footer">
                    <div class="mt-4 px-5">

                        <div class="row ">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label text-capitalize">{{translate('Site Key:')}}</label><br>
                                    <input type="text" class="form-control" name="site_key" value="{{env('APP_MODE')!='demo'?$config['site_key']??"":''}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label text-capitalize">{{translate('Secret Key:')}}</label><br>
                                    <input type="text" class="form-control" name="secret_key" value="{{env('APP_MODE')!='demo'?$config['secret_key']??"":''}}">
                                </div>
                            </div>
                        </div>



                        <div class="btn--container ">
                        <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
                    <div class="form-group mt-5 ml-3">
                            <h4 class="mb-3" id="staticBackdropLabel">{{translate('Instructions:')}}</h4>
                            <ol class="d-flex flex-column __gap-5px __instructions">
                                <li>{{translate('Go to the Credentials page')}}
                                    <a
                                        href="https://www.google.com/recaptcha/admin/create"
                                        target="_blank"> ({{translate('Click')}}{{translate('here')}})</a>
                                </li>
                                <li>{{translate('Add a ')}}
                                    <b>{{translate('label')}}</b> {{translate('(Ex: Test Label)')}}
                                </li>
                                <li>
                                    {{translate('Select reCAPTCHA v2 as ')}}
                                    <b>{{translate('reCAPTCHA Type')}}</b>
                                    ({{\App\CentralLogics\translate("Sub type: I'm not a robot Checkbox")}}
                                    )
                                </li>
                                <li>
                                    {{translate('Add')}}
                                    <b>{{translate('domain')}}</b>
                                    {{translate('(For ex: demo.dcodax.com)')}}
                                </li>
                                <li>
                                    {{translate('Check in ')}}
                                    <b>{{translate('Accept the reCAPTCHA Terms of Service')}}</b>
                                </li>
                                <li>
                                    {{translate('Press')}}
                                    <b>{{translate('Submit')}}</b>
                                </li>
                                <li>{{translate('Copy')}} <b>Site
                                        Key</b> {{translate('and')}} <b>Secret
                                        Key</b>, {{translate('paste in the input filed below and')}}
                                    <b>Save</b>.
                                </li>
                            </ol>
                        </div>

    </div>
@endsection

@push('script_2')

@endpush

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
                {{ translate('system_setup') }}
             </h3>
            </div>
            <div>
                @include('admin-views.business-settings.partials._system-settings-inline-menu')
                <hr class="li_hr">
            </div>
         </div>

        <div class="row g-2 px-5 mx-3">
        <div class="col-md-6">
                <form
                action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.payment-method-update',['razor_pay']):'javascript:'}}"
                method="post">
                @csrf
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">{{translate('Android')}}</h4>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-footer">

                        @php($config=$appSettings['play_store_config'] ?? null)
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.system-setup.app_setting',['platform' => 'android']):'javascript:'}}"
                            method="post">
                            @csrf
                            <div class="form-group">
                                <div class="form-group d-flex gap-3 align-items-center justify-content-between">
                                    <div
                                        class="text-dark font-weight-bold">{{ translate('Enable_Download_Link_for_Web_Footer') }}</div>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" name="play_store_status"
                                               value="1" {{(isset($config) && $config['status']==1)?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="play_store_link" name="play_store_link"
                                           value="{{$config['link']??''}}" class="form-control" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label class="text-dark"
                                           for="android_min_version">{{ translate('Minimum_Version_for_Force_Update') }}
                                        <i class="tio-info text-danger" data-toggle="tooltip" data-placement="right"
                                           title="{{ \App\CentralLogics\translate("If there is any update available in the admin panel and for that, the previous user app will not work, you can force the customer from here by providing the minimum version for force update. That means if a customer has an app below this version the customers must need to update the app first. If you don't need a force update just insert here zero (0) and ignore it.") }}"></i>
                                    </label>
                                    <input type="number" min="0" step=".1" id="android_min_version"
                                           name="android_min_version"
                                           value="{{$config['min_version']??''}}" class="form-control"
                                           placeholder="{{ translate('EX: 4.0') }}">
                                </div>
                            </div>

                            <div class="btn--container justify-content-center">
                                <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('submit')}}</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form
                action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.payment-method-update',['paypal']):'javascript:'}}"
                method="post">
                @csrf
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">{{translate('IOS')}}</h4>

                    </div>
                </div>
                     <div class="card">
                        <div class="card-footer">

                        @php($config=$appSettings['app_store_config'] ?? null)
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.system-setup.app_setting',['platform' => 'ios']):'javascript:'}}"
                            method="post">
                            @csrf
                            <div class="form-group">
                                <div class="form-group d-flex align-items-center gap-3 justify-content-between">
                                    <div
                                        class="text-dark font-weight-bold">{{ translate('Enable download link for web footer') }}</div>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" name="app_store_status"
                                               value="1" {{(isset($config) && $config['status']==1)?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="app_store_link" name="app_store_link"
                                           value="{{$config['link']??''}}" class="form-control" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label class="text-dark"
                                           for="ios_min_version">{{ translate('Minimum version for force update') }}
                                        <i class="tio-info text-danger" data-toggle="tooltip" data-placement="right"
                                           title="{{ \App\CentralLogics\translate("If there is any update available in the admin panel and for that, the previous user app will not work, you can force the customer from here by providing the minimum version for force update. That means if a customer has an app below this version the customers must need to update the app first. If you don't need a force update just insert here zero (0) and ignore it.") }}"></i>
                                    </label>
                                    <input type="number" min="0" step=".1" id="ios_min_version" name="ios_min_version"
                                           value="{{$config['min_version']??''}}" class="form-control"
                                           placeholder="{{ translate('EX: 4.0') }}">
                                </div>
                            </div>

                            <div class="btn--container justify-content-center">
                                <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('submit')}}</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection
@push('script_2')

@endpush

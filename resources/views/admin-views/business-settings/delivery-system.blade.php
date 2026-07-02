@extends('layouts.admin.app')

@section('title', translate('Payment Setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('public/assets/admin/img/icons/third-party.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('third_party')}}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->

        <!-- Inine Page Menu -->
        @include('admin-views.business-settings.partials._3rdparty-inline-menu')

        <div class="row g-2">
            <div class="col-md-6">
                <div class="card">
                    @php($config=$woltConfig ?? ['status' => 0, 'environment' => 'local', 'venue_id' => '', 'merchant_id' => '', 'token' => ''])
                    <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.delivery-system-update',['wolt_service']):'javascript:'}}" method="post">
                        @csrf
                        <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-uppercase mb-4">{{translate('wolt_service')}}</h5>
                            <label class="switcher">
                                <input class="switcher_input" name="status" type="checkbox"  {{($config['status'] ?? 0) == 1? 'checked' : ''}}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <center class="mb-4">
                            <img width="185" class="avatar-img" src="{{asset('/assets/admin/img/icons/wolt.jpg')}}" alt="">
                        </center>

                            @if(isset($config))
                                <div class="form-group">
                                    <label>{{translate('environment')}} </label><br>
                                    <select name="environment" class="form-control js-select2-custom">
                                        <option value="local" {{ ($config['environment'] ?? 'local')=='local' ? 'selected' : ''}} >{{translate('local')}}</option>
                                        <option value="live" {{ ($config['environment'] ?? 'local')=='live' ? 'selected' : ''}} >{{translate('live')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('venue_id')}}</label><br>
                                    <input type="text" class="form-control" name="venue_id"
                                           value="{{env('APP_MODE')!='demo'?($config['venue_id'] ?? ''):''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('merchant_id')}}</label><br>
                                    <input type="text" class="form-control" name="merchant_id"
                                           value="{{env('APP_MODE')!='demo'?($config['merchant_id'] ?? ''):''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('token')}}</label><br>
                                    <input type="text" class="form-control" name="token"
                                           value="{{env('APP_MODE')!='demo'?($config['token'] ?? ''):''}}">
                                </div>
                                <div class="btn--container">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{translate('save')}}</button>
                                </div>
                            @else
                                <div class="btn--container">
                                    <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                                </div>
                            @endif
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script_2')

@endpush

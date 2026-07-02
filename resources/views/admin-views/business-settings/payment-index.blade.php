@extends('layouts.admin.app')

@section('title', translate('Payment Setup'))

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
            <div>
            <h3>{{translate('Payment_Method')}}</h3>
           </div>
           <div class="d-flex justify-content-end align-items-center gap-3">
                        <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                    </div>
           </div>

        </div>



        <div class="row g-2">
            <div class="col-md-12">
                <div class="card mb-5">
                    <div class="card-footer d-flex">

                        @php($config=$paymentConfigs['cash_on_delivery'] ?? null)
                        <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{route('admin.business-settings.web-app.payment-method-update',['cash_on_delivery'])}}"
                              method="post">
                            @csrf
                                <div style="border-radius:8px;" class="form-group d-flex flex-row border m-3 py-1 px-3 align-items-center justify-content-between">
                                    <label style="color:#7E8299;" class="input-label" >{{translate('cash_on_delivery')}}</label>
                                    <label class="switcher">
                                        <input class="switcher_input" name="status" type="checkbox" {{($config['status'] ?? 0) == 1 ? 'checked' : ''}} onchange="this.form.submit()">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </form>
                        </div>
                            @php($config=$paymentConfigs['digital_payment'] ?? null)
                            <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{route('admin.business-settings.web-app.payment-method-update',['digital_payment'])}}"
                              method="post">
                            @csrf
                                <div style="border-radius:8px;" class="form-group d-flex flex-row border m-3 py-1 px-3 align-items-center justify-content-between">
                                    <label style="color:#7E8299;" class="input-label">{{translate('digital_payment')}}</label>
                                    <label class="switcher">
                                        <input class="switcher_input" name="status" type="checkbox" {{($config['status'] ?? 0) == 1 ? 'checked' : ''}} onchange="this.form.submit()">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
    <div class="col-md-6">
                <div class="card">
                    @php($config=$paymentConfigs['payconiq_payment'] ?? null)

                    <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.payment-method-update',['payconiq_payment']):'javascript:'}}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-uppercase mb-4">{{translate('payconiq')}}</h5>
                                <label class="switcher">
                                    <input class="switcher_input" name="status" type="checkbox" {{($config['status'] ?? 0) == 1? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        <center class="mb-4">
                            <img width="185" class="avatar-img" src="{{asset('assets/admin/img/icons/payconiq_magenta.svg')}}" alt="">
                        </center>

                            @if(isset($config))


                                <div class="form-group">
                                    <label>{{translate('Api Token')}} </label><br>
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
            <div class="col-lg-6 col-md-12">
                    @php($config=$paymentConfigs['ssl_commerz_payment'] ?? null)
                    <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.payment-method-update',['ssl_commerz_payment']):'javascript:'}}" method="post">
                        @csrf
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="text-uppercase">{{translate('sslcommerz')}}</h4>
                                    <label class="switcher ">
                                        <input class="switcher_input" name="status" type="checkbox" {{($config['status'] ?? 0) == 1? 'checked' : ''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-footer">
                                <center class="mb-4">
                                    <img width="180"  class="avatar-img" src="{{asset('assets/admin/img/icons/ssl.png')}}" alt="">
                                </center>
                                    @if(isset($config))
                                        <div class="form-group">
                                            <label>{{translate('store ID')}} </label><br>
                                            <input type="text" class="form-control" name="store_id"
                                                value="{{env('APP_MODE')!='demo'?($config['store_id'] ?? ''):''}}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{translate('store Password')}}</label><br>
                                            <input type="text" class="form-control" name="store_password"
                                                value="{{env('APP_MODE')!='demo'?($config['store_password'] ?? ''):''}}">
                                        </div>

                                        <div class="d-flex justify-content-center align-items-center gap-3">
                                                <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('submit')}}</button>
                                        </div>

                                    @else
                                        <div class="btn--container">
                                            <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </form>
                </div>
            <div class="col-lg-6 col-md-12">
                @php($config=$paymentConfigs['razor_pay'] ?? null)
                <form
                action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.payment-method-update',['razor_pay']):'javascript:'}}"
                method="post">
                @csrf
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="text-uppercase">{{translate('razorpay')}}</h4>
                                <label class="switcher">
                                    <input class="switcher_input" name="status" type="checkbox" {{($config['status'] ?? 0) == 1? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-footer">
                            <center class="mb-4">
                                <img width="140" class="avatar-img" src="{{asset('assets/admin/img/razorpay.png')}}" alt="">
                            </center>

                            @if(isset($config))
                                <div class="form-group">
                                    <label>{{translate('razorkey')}}</label>
                                    <input type="text" class="form-control" name="razor_key"
                                           value="{{env('APP_MODE')!='demo'?($config['razor_key'] ?? ''):''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('razorsecret')}}</label>
                                    <input type="text" class="form-control" name="razor_secret"
                                           value="{{env('APP_MODE')!='demo'?($config['razor_secret'] ?? ''):''}}">
                                </div>

                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('submit')}}</button>
                                </div>
                            @else
                                <div class="btn--container">
                                    <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 col-md-12">
                @php($config=$paymentConfigs['paypal'] ?? null)
                <form
                action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.payment-method-update',['paypal']):'javascript:'}}"
                method="post">
                @csrf
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-uppercase">{{translate('paypal')}}</h4>
                            <label class="switcher">
                                <input class="switcher_input" name="status" type="checkbox" {{($config['status'] ?? 0) == 1? 'checked' : ''}}>
                                <span class="switcher_control"></span>
                            </label>
                    </div>
                </div>
                     <div class="card">
                        <div class="card-footer">
                            <center class="mb-4">
                                <img width="140" class="avatar-img" src="{{asset('assets/admin/img/icons/paypal.png')}}" alt="">
                            </center>

                            @if(isset($config))
                                <div class="form-group">
                                    <label>{{translate('paypal')}} {{translate('client')}} {{translate('id')}}</label>
                                    <input type="text" class="form-control" name="paypal_client_id"
                                           value="{{env('APP_MODE')!='demo'?($config['paypal_client_id'] ?? ''):''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('paypal')}} {{translate('secret')}}</label>
                                    <input type="text" class="form-control" name="paypal_secret"
                                           value="{{env('APP_MODE')!='demo'?($config['paypal_secret'] ?? ''):''}}">
                                </div>

                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('Submit')}}</button>
                                </div>
                            @else
                                <div class="btn--container">
                                    <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 col-md-12">
                @php($config=$paymentConfigs['stripe'] ?? null)
                <form
                action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.payment-method-update',['stripe']):'javascript:'}}"
                method="post">
                @csrf
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-uppercase">{{translate('stripe')}}</h4>
                            <label class="switcher">
                                <input class="switcher_input" name="status" type="checkbox" {{($config['status'] ?? 0) == 1? 'checked' : ''}}>
                                <span class="switcher_control"></span>
                            </label>
                    </div>
                </div>
                    <div class="card">
                        <div class="card-footer">
                            <center class="mb-4">
                                <img width="140" class="avatar-img" src="{{asset('assets/admin/img/stripe.png')}}" alt="">
                            </center>
                            @if(isset($config))
                                <div class="form-group">
                                    <label>{{translate('published')}} {{translate('key')}}</label>
                                    <input type="text" class="form-control" name="published_key"
                                           value="{{env('APP_MODE')!='demo'?($config['published_key'] ?? ''):''}}">
                                </div>

                                <div class="form-group">
                                    <label>{{translate('api')}} {{translate('key')}}</label>
                                    <input type="text" class="form-control" name="api_key"
                                           value="{{env('APP_MODE')!='demo'?($config['api_key'] ?? ''):''}}">
                                </div>


                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('Submit')}}</button>
                                </div>
                            @else
                                <div class="btn--container">
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

@extends('layouts.admin.app')

@section('title', translate('OTP setup'))

@section('content')
    <div class="ml-5">
        @include('admin-views.business-settings._setting-setup-inline-menu')
    </div>
    <hr class="li_hr">
    <div class="content container-fluid">
        <div  class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
            <div >
             <h3>
                {{-- {{translate('business_setup')}} --}}
             </h3>
            </div>
            <div>
                @include('admin-views.business-settings.partials._business-setup-inline-menu')
             <hr class="li_hr">
            </div>
         </div>
            <form action="{{route('admin.business-settings.restaurant.otp-setup-update')}}" method="post">
                 @csrf
                 <div class="card mb-3">
                    <div class="card-header">
                        <h3>{{translate('Login and OTP Setup')}}</h3>
                    </div>
                 </div>
                <div class="card">
                    <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize">{{translate('maximum_OTP_submit_attempt')}}
                                            <i class="tio-info-outined"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="{{ translate('The maximum OTP hit is a measure of how many times a specific one-time password has been generated and used within a time.') }}">
                                            </i>
                                        </label>
                                        <input type="number" value="{{$otpSettings['maximum_otp_hit'] ?? ''}}" min="1"
                                               name="maximum_otp_hit" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize">{{translate('otp_resend_time')}}
                                            <span class="text-primary">( {{ translate('in second') }} )</span>
                                            <i class="tio-info-outined"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="{{ translate('If the user fails to get the OTP within a certain time, user can request a resend.') }}">
                                            </i>
                                        </label>
                                        <input type="number" value="{{$otpSettings['otp_resend_time'] ?? ''}}" min="1"
                                               name="otp_resend_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize">{{translate('temporary_block_time')}}
                                            <span class="text-primary">( {{ translate('in second') }} )</span>
                                            <i class="tio-info-outined"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="{{ translate('Temporary OTP block time refers to a security measure implemented by systems to restrict access to OTP service for a specified period of time for wrong OTP submission.') }}">
                                            </i>
                                        </label>
                                        <input type="number" value="{{$otpSettings['temporary_block_time'] ?? ''}}" min="1"
                                               name="temporary_block_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize">{{translate('maximum_login_attempt')}}
                                            <i class="tio-info-outined"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="{{ translate('The maximum login hit is a measure of how many times a user can submit password within a time.') }}">
                                            </i>
                                        </label>
                                        <input type="number" value="{{$otpSettings['maximum_login_hit'] ?? ''}}" min="1"
                                               name="maximum_login_hit" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize">{{translate('temporary_login_block_time')}}
                                            <span class="text-primary">( {{ translate('in second') }} )</span>
                                            <i class="tio-info-outined"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="{{ translate('Temporary login block time refers to a security measure implemented by systems to restrict access for a specified period of time for wrong Password submission.') }}">
                                            </i>
                                        </label>
                                        <input type="number" value="{{$otpSettings['temporary_login_block_time'] ?? ''}}" min="1"
                                               name="temporary_login_block_time" class="form-control" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                            <div class="btn--container justify-content-start mt-4">
                                <button type="reset" class="btn btn-white text-order_id border-primary">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('submit')}}</button>
                            </div>
                </form>


        </div>
    </div>
@endsection

@push('script_2')

@endpush

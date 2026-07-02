@extends('layouts.admin.app')

@section('title', translate('Settings'))

@section('content')
    <div class="ml-5">
        @include('admin-views.business-settings._setting-setup-inline-menu')
    </div>
    <hr class="li_hr">
<div class="content container-fluid">
    <div  class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
        <div >
         <h3>
            {{translate('business_setup')}}
         </h3>
        </div>
        <div>
            @include('admin-views.business-settings.partials._business-setup-inline-menu')
         <hr class="li_hr">
        </div>
     </div>
        <div class="row g-2">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12 col-sm-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3>{{translate('Cookies_Setup')}}</h3>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-footer">
                                <form action="{{route('admin.business-settings.restaurant.cookies-setup-update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <span class="text-dark input-label">{{translate('Cookie Text:')}}</span>
                                        <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                            <input type="checkbox" name="status" value="1" class="toggle-switch-input" {{$cookies?($cookies['status']==1?'checked':''):''}}>
                                            <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        </label>
                                    </div>
                                    <div class="form-group pt-3">
                                        <textarea name="text" class="form-control" rows="6" placeholder="{{ translate('Cookies text') }}" required>{{$cookies['text']}}</textarea>
                                    </div>
                                    <div class="btn--container justify-content-start gap-3">
                                    <button type="reset" class="btn btn-white text-order_id border-primary">{{translate('reset')}}</button>
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                                class="btn btn-primary">{{translate('submit')}}</button>
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

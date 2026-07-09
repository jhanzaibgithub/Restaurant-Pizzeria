@extends('layouts.admin.app')

@section('title', translate('Chat'))

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
        <div class="row g-2">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>{{translate('chat')}}</h4>
                    </div>
                </div>
                <div class="card">
                    @php($config=$whatsappConfig)
                    @if($config)
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.third-party.chat-update',['whatsapp']):'javascript:'}}"
                            method="post">
                            <div class="card-footer">
                               <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-4">{{translate('Whatsapp')}}</h5>
                                        <label class="switcher">
                                            <input class="switcher_input" name="status" type="checkbox" {{$config['status'] == 1? 'checked' : ''}}>

                                            <span class="switcher_control"></span>
                                        </label>
                                    </div>
                                @csrf
                                            <div class="form-group">
                                                <label>{{translate('number')}} <span class="text-danger">({{ translate('without country code') }})</span></label><br>
                                                <input type="text" class="form-control" name="number"
                                                    value="{{$config['number'] ?? ''}}" placeholder="{{ translate('WhatsApp Number') }}">
                                            </div>
                                        </div>
                                <div class="btn--container">
                                <button type="reset" class="btn btn-white text-order_id  mb-2">{{translate('reset')}}</button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn-primary mb-2">{{translate('save')}}</button>
                                </div>


                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection

@push('script_2')

@endpush

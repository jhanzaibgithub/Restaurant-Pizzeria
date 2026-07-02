@extends('layouts.admin.app')
@section('title', translate('FCM Settings'))

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
        <div class="row g-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.third-party.update-fcm'):'javascript:'}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mb-2">
                                    <label class="input-label">{{translate('server key')}}</label>
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('submit')}}</button>
                                    </div>
                                <textarea name="push_notification_key" class="form-control"
                                          required>{{env('APP_MODE')!='demo'?($fcmSettings['push_notification_key'] ?? ''):''}}</textarea>
                            </div>

                            <div class="row" style="display: none">
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('FCM Project ID')}}</label>
                                        <input type="text" value="{{$fcmSettings['fcm_project_id'] ?? ''}}"
                                               name="fcm_project_id" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="mb-0">{{translate('Push Messages')}}</h4>
                    </div>
                </div>
                <div class="card">
                    <div class="card-footer">
                        <form action="{{route('admin.business-settings.update-fcm-messages')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                @php($data = $fcmMessages['order_pending_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                            <span class="input-label">{{translate('order_Pending_Message:')}}</span>
                                            <label class="switcher" for="pending_status">
                                                <input type="checkbox" name="pending_status" class="switcher_input"
                                                    value="1" id="pending_status" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>
                                        <textarea name="pending_message" class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['order_confirmation_msg'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('order confirmation message:')}}</span>
                                            <label class="switcher" for="confirm_status">
                                                <input type="checkbox" name="confirm_status" class="switcher_input"
                                                    value="1" id="confirm_status" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="confirm_message"
                                                  class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['order_processing_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('order processing message:')}}</span>
                                            <label class="switcher" for="processing_status">
                                                <input type="checkbox" name="processing_status" class="switcher_input"
                                                    value="1" id="processing_status" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="processing_message"
                                                  class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['out_for_delivery_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('order out for delivery message:')}}</span>
                                            <label class="switcher" for="out_for_delivery">
                                                <input type="checkbox" name="out_for_delivery_status" class="switcher_input"
                                                    value="1" id="out_for_delivery" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="out_for_delivery_message"
                                                  class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['order_delivered_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('order delivered message:')}}</span>
                                            <label class="switcher" for="delivered_status">
                                                <input type="checkbox" name="delivered_status" class="switcher_input"
                                                    value="1" id="delivered_status" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="delivered_message"
                                                  class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['delivery_boy_assign_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('deliveryman assign message:')}}</span>
                                            <label class="switcher" for="delivery_boy_assign">
                                                <input type="checkbox" name="delivery_boy_assign_status" class="switcher_input"
                                                    value="1" id="delivery_boy_assign" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="delivery_boy_assign_message"
                                                  class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['customer_notify_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('Customer notify message for deliveryman:')}}</span>
                                            <label class="switcher" for="customer_notify">
                                                <input type="checkbox" name="customer_notify_status" class="switcher_input"
                                                    value="1" id="customer_notify" {{isset($data) && $data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="customer_notify_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['customer_notify_message_for_time_change'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('Customer notify message for food preparation time change:')}}</span>
                                            <label class="switcher" for="customer_notify_for_time_change">
                                                <input type="checkbox" name="customer_notify_status_for_time_change" class="switcher_input"
                                                    value="1" id="customer_notify_for_time_change" {{isset($data) && $data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="customer_notify_message_for_time_change"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['delivery_boy_start_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('deliveryman start message:')}}</span>
                                            <label class="switcher" for="delivery_boy_start_status">
                                                <input type="checkbox" name="delivery_boy_start_status" class="switcher_input"
                                                    value="1" id="delivery_boy_start_status" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="delivery_boy_start_message"
                                                  class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['delivery_boy_delivered_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('deliveryman delivered message:')}}</span>
                                            <label class="switcher" for="delivery_boy_delivered">
                                                <input type="checkbox" name="delivery_boy_delivered_status" class="switcher_input"
                                                    value="1" id="delivery_boy_delivered" {{$data['status']==1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="delivery_boy_delivered_message"
                                                  class="form-control">{{$data['message']}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['returned_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('Order_returned_message:')}}</span>
                                            <label class="switcher" for="returned_status">
                                                <input type="checkbox" name="returned_status" class="switcher_input"
                                                    value="1" id="returned_status" {{(isset($data['status']) && $data['status']==1)?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="returned_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['failed_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('Order_failed_message:')}}</span>
                                            <label class="switcher" for="failed_status">
                                                <input type="checkbox" name="failed_status" class="switcher_input"
                                                    value="1" id="failed_status" {{(isset($data['status']) && $data['status']==1)?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="failed_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>

                                @php($data = $fcmMessages['canceled_message'] ?? ['status' => 0, 'message' => ''])
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center gap-3 mb-3 justify-content-between">
                                            <span class="input-label">{{translate('Order_canceled_message:')}}</span>
                                            <label class="switcher" for="canceled_status">
                                                <input type="checkbox" name="canceled_status" class="switcher_input"
                                                    value="1" id="canceled_status" {{(isset($data['status']) && $data['status']==1)?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <textarea name="canceled_message"
                                                  class="form-control">{{$data['message']??''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3 mb-4">
                            <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush

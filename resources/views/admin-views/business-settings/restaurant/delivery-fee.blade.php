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
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            {{translate('Delivery_Fee_Steup')}}
                        </h4>
                    </div>
                </div>
            <form action="{{route('admin.business-settings.restaurant.update-delivery-fee')}}" method="post"
            enctype="multipart/form-data">
                            @csrf
                <div class="card my-3">
                    <div class="card-footer">

                            <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        @php($config = $deliveryManagement)
                                        <div class="form-group d-flex align-items-center gap-2">
                                            <input type="radio" name="shipping_status" value="1"
                                                {{$config['status']==1?'checked':''}} id="shipping_by_distance_status">
                                            <label for="shipping_by_distance_status" class="text-dark font-weight-bold mb-0">{{translate('delivery_charge_by_distance')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group d-flex align-items-center gap-2">
                                            <input type="radio" name="shipping_status" value="0"
                                                   {{$config['status']==0?'checked':''}} id="default_delivery_status">
                                            <label for="default_delivery_status" class="text-dark font-weight-bold mb-0">{{translate('default_delivery_charge')}}</label>
                                        </div>
                                    </div>
                            </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('Minimum delivery Charge:')}} </label>
                                                <input type="number" step=".01" class="form-control"
                                                    name="min_shipping_charge"
                                                    value="{{$config['min_shipping_charge']}}"
                                                    id="min_shipping_charge" {{ $config['status']==0?'disabled':'' }} >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('delivery Charge / Kilometer:')}}</label>
                                                <input type="number" step=".01" class="form-control" name="shipping_per_km"
                                                    value="{{$config['shipping_per_km']}}"
                                                    id="shipping_per_km" {{ $config['status']==0?'disabled':'' }}>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('delivery_Charge:')}} </label>
                                                <input type="number" min="0" step=".01" name="delivery_charge" value="{{$deliveryCharge}}"
                                                    class="form-control" placeholder="{{translate('EX: 100')}}" required
                                                    {{ $config['status']==1?'disabled':'' }} id="delivery_charge">
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start gap-3">
                        <button type="reset" class="btn btn-white text-order_id border-primary">{{translate('reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $('[name=time_zone]').val("{{$timeZone}}");

        let language = {!! $language ?? 'null' !!};
        $('[id=language]').val(language);

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
        $("#language").on("change", function () {
            $("#alert_box").css("display", "block");
        });
    </script>

    <script>

        function currency_symbol_position(route) {
            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success(data.message);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        $(document).on('ready', function () {
            $("#country option[value='{{$country}}']").attr('selected', 'selected').change();
        })
    </script>

    <script>
        $('#shipping_by_distance_status').on('click',function(){
            $("#delivery_charge").prop('disabled', true);
            $("#min_shipping_charge").prop('disabled', false);
            $("#shipping_per_km").prop('disabled', false);
        });

        $('#default_delivery_status').on('click',function(){
            $("#delivery_charge").prop('disabled', false);
            $("#min_shipping_charge").prop('disabled', true);
            $("#shipping_per_km").prop('disabled', true);
        });
    </script>

@endpush

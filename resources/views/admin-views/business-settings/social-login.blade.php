@extends('layouts.admin.app')

@section('title', translate('Social Login'))

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
        <div class="card mb-3">
                <div class="card-header">
                    <h4>{{translate('Social_Login')}}</h4>
                    <div class="d-flex justify-content-center align-items-center gap-3">
                        <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('save')}}</button>
                    </div>
                </div>
            </div>
        <div class="row g-3">
            <div class="col-lg-6 col-md-12">
                <div class="card-footer __social-media-login __shadow">
                    <div class="card-body my-3">
                        <div style="border-radius:7px" class="__social-media-login-top border px-4 py-1">
                            <div class="__social-media-login-icon">
                                <img src="{{asset('assets/admin/img/icons/google.png')}}" alt="">
                            </div>
                            <div class="text-center sub-txt text-capitalize">{{translate('google_login')}}</div>
                            <div class="custom--switch switch--right">
                                @php($google = $socialLoginSettings['google_social_login'] ?? 0)
                                <input onclick="loginStatusChange(this)" type="checkbox" id="google_social_login" name="google" switch="primary" class="toggle-switch-input"
                                       {{$google == 1 ? 'checked' : ''}}>
                                <label for="google_social_login" data-on-label="on" data-off-label="off"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card-footer __social-media-login __shadow">
                    <div class="card-body my-3">
                        <div style="border-radius:7px" class="__social-media-login-top border px-4 py-1">
                            <div class="__social-media-login-icon">
                                <img src="{{asset('assets/admin/img/icons/facebook.png')}}" alt="">
                            </div>
                            <div class="text-center sub-txt text-capitalize">{{translate('facebook_login')}}</div>
                            <div class="custom--switch switch--right">
                                @php($facebook = $socialLoginSettings['facebook_social_login'] ?? 0)
                                <input onclick="loginStatusChange(this)" type="checkbox" id="facebook" name="facebook_social_login" switch="primary" class="toggle-switch-input"
                                {{$facebook == 1 ? 'checked' : ''}}>
                                <label for="facebook" data-on-label="on" data-off-label="off"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function loginStatusChange(t) {
            console.log(t.id)
            let url = "{{route('admin.business-settings.web-app.third-party.social-login-status')}}";
            let checked = $(t).prop("checked");
            let status = checked === true ? 1 : 0;
            let btn_name = t.id;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Want to change status',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FC6A57',
                cancelButtonColor: 'default',
                cancelButtonText: '{{translate("No")}}',
                confirmButtonText: '{{translate("Yes")}}',
                reverseButtons: true
            }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: url,
                            data: {
                                status: status,
                                btn_name: btn_name,
                            },
                            success: function (data, status) {
                                toastr.success("{{translate('Status changed successfully')}}");
                            },
                            error: function (data) {
                                toastr.error("{{translate('Status changed failed')}}");
                            }
                        });
                    }
                    else if (result.dismiss) {
                        if (status == 1) {
                            $('#' + t.id).prop('checked', false)

                        } else if (status == 0) {
                            $('#'+ t.id).prop('checked', true)
                        }
                        toastr.info("{{translate("Status hasn't changed")}}");
                    }
                }
            )
        }
    </script>
@endpush

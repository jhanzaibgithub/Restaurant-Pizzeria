@extends('layouts.admin.app')
@section('title', translate('Add New Chef'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
@include('admin-views.kitchen.partials._chef-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
<div class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
        <form action="{{route('admin.kitchen.add-new')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card mb-3">
                             <div class="card-body">
                                <div class="d-flex flex-row justify-content-between align-items-center mb-2">
                                  <div >
                                    <h3 class="h1 mb-0 ">
                                        <span class="page-header-title">
                                            {{translate('Add_New_Chef')}}
                                        </span>
                                    </h3>


                                    <h5 class="text-muted">
                                        {{translate('Chef_Information')}}
                                    </h5>
                                  </div>

                                    <div >
                                        <select name="branch_id" class="custom-select" required>
                                            <option value="" selected disabled>{{ translate('--Select_Branch--') }}</option>
                                            @foreach($branches as $branch)
                                                <option value="{{$branch['id']}}">{{$branch['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
            <div class="card">
                <div class="card-body">
                        <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="name">{{translate('First Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="f_name" class="form-control" id="f_name"
                                           placeholder="{{translate('Ex')}} : {{translate('John')}}" value="{{old('f_name')}}" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="name">{{translate('Last Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="l_name" class="form-control" id="l_name"
                                           placeholder="{{translate('Ex')}} : {{translate('Doe')}}" value="{{old('l_name')}}" required>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="name">{{translate('Phone')}} <span class="text-danger">*</span> {{translate('(with country code)')}}</label>
                                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control" id="phone"
                                           placeholder="{{translate('Ex')}} : +88017********" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="name">{{translate('Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email"
                                           placeholder="{{translate('Ex')}} : ex@gmail.com" required>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                <label for="name">{{translate('password')}} <span class="text-danger">*</span> {{translate('(minimum length will be 6 character)')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" class="js-toggle-password form-control form-control input-field" id="password"
                                           placeholder="{{translate('Password')}}" required
                                           data-hs-toggle-password-options='{
                                        "target": "#changePassTarget",
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": "#changePassIcon"
                                        }'>
                                    <div id="changePassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label for="confirm_password">{{translate('confirm_Password')}}<span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="confirm_password" class="js-toggle-password form-control form-control input-field" id="confirm_password"
                                           placeholder="{{translate('confirm password')}}" required
                                           data-hs-toggle-password-options='{
                                        "target": "#changeConPassTarget",
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": "#changeConPassIcon"
                                        }'>
                                    <div id="changeConPassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changeConPassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label for="name">{{translate('image')}} <span class="text-danger">*</span></label>
                                <span class="badge badge-soft-danger">( {{translate('ratio')}} 1:1 )</span>
                                <div class="form-group">
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                        <label class="custom-file-label" for="customFileUpload">{{translate('choose')}} {{translate('file')}}</label>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-4 col-md-6">
                                    <div class="">
                                        <img width="100" id="viewer"
                                            src="{{asset('public\assets\admin\img\400x400\img2.jpg')}}" alt="image"/>
                                    </div>
                            </div>
                            <div class="col-lg-4 col-md-6 d-flex align-items-center mt-2">
                                    <div style="gap:2.4rem;" class="d-flex justify-content-start">
                                        <button type="reset" id="reset" class="btn btn-lg btn-secondary">{{translate('reset')}}</button>
                                        <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
                                    </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="{{asset('assets/admin')}}/js/select2.min.js"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>
@endpush

@extends('layouts.admin.app')

@section('title', translate('Employee Add'))

@push('css_or_js')
@endpush

@section('content')
<div class="ml-5">
        @include('admin-views.employee.partials._employee-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
<div class="content container-fluid">
    <div class="d-flex flex-wrap gap-2 align-items-center mb-4">

    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('admin.employee.add-new')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card mb-3">
                    <div class="card-header d-flex flex-column align-items-baseline">
                        <div>
                            <h3 >
                            {{translate('add_New_Employee')}}
                            </h3>
                        </div>
                        <div>
                        <h5 class="text-muted">  {{translate('general_Information')}}</h5>
                        </div>

                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label" for="name">{{translate('full_Name')}}</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="{{translate('Ex')}} : {{translate('Jhon_Doe')}}" value="{{old('name')}}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label" for="phone">{{translate('Phone')}}</label>
                                    <input type="tel" name="phone" value="{{old('phone')}}" class="form-control" id="phone"
                                        placeholder="{{translate('Ex')}} : +88017********" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label" for="name">{{translate('employee_image')}}
                                    <span class="text-danger">*( {{translate('ratio')}} 1:1 )</span></label>
                                    <div class="form-group">
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                            <label class="custom-file-label" for="customFileUpload">{{translate('choose')}} {{translate('file')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label" for="identity_type">{{translate('Identity Type')}}</label>
                                    <select class="custom-select" name="identity_type" id="identity_type" required>
                                        <option selected disabled>---{{translate('select_Identity_Type')}}---</option>
                                        <option value="passport">{{translate('passport')}}</option>
                                        <option value="driving_license">{{translate('driving_License')}}</option>
                                        <option value="nid">{{translate('NID')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label" for="identity_number">{{translate('identity_Number')}}</label>
                                    <input type="text" name="identity_number" class="form-control" id="identity_number" required value="{{old('identity_number')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                     <div class="text-center mb-3">
                                        <img width="100" class="border" id="viewer"
                                            src="{{asset('assets\admin\img\400x400\img2.jpg')}}" alt="image"/>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label" for="role_id">{{translate('Role')}}</label>
                                    <select class="custom-select" name="role_id">
                                        <option value="0" selected disabled>---{{translate('select_Role')}}---</option>
                                        @foreach($rls as $r)
                                            <option value="{{$r->id}}" {{old('role_id')==$r->id?'selected':''}}>{{$r->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{translate('identity_Image')}}</label>
                                    <div>
                                        <div class="row" id="coba"></div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0 d-flex align-items-center gap-2"> {{translate('account_Information')}}</h5>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">{{translate('Email')}}</label>
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email"
                                        placeholder="{{translate('Ex')}} : ex@gmail.com" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">{{translate('password')}}</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" class="js-toggle-password form-control form-control input-field" id="password"
                                               placeholder="{{translate('Ex: 8+ Characters')}}" required
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="confirm_password">{{translate('confirm_Password')}}</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="confirm_password" class="js-toggle-password form-control form-control input-field"
                                               id="confirm_password" placeholder="{{translate('confirm password')}}" required
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <button type="reset" id="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script src="{{asset('assets/admin/js/vendor.min.js')}}"></script>
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

    <script src="{{asset('assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '230px',
                groupClassName: 'col-6 col-lg-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CentralLogics\translate("Please only input png or jpg type file")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CentralLogics\translate("File size too big")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush

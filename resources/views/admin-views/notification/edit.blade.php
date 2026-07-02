@extends('layouts.admin.app')

@section('title', translate('Update Notification'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <i class="tio-notifications"></i>
                <span class="page-header-title">
                    {{translate('notification_Update')}}
                </span>
            </h2>
        </div>

        <div class="row g-2">
            <div class="col-12">
                <form action="{{route('admin.notification.update',[$notification['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                        <input type="text" value="{{$notification['title']}}" name="title" class="form-control" placeholder="{{translate('New notification')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('description')}}</label>
                                        <textarea name="description" class="form-control" required>{{$notification['description']}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label">{{translate('Notification Time Schedule')}}</label>
                                        <input id="schedule_at" type="datetime-local" name="schedule_at" value="{{$notification['schedule_at']}}" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <label class="mb-0">{{translate('image')}}</label>
                                            <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                        </div>
                                        <div class="d-flex justify-content-center mt-4">
                                            <div class="upload-file">
                                                <input type="file" name="image" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" class="upload-file__input">
                                                <div class="upload-file__img_drag upload-file__img">
                                                    <img width="465" onerror="this.src='{{asset('assets/admin/img/icons/upload_img2.png')}}'"
                                                    src="{{asset('/storage/notification')}}/{{$notification['image']}}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
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

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush

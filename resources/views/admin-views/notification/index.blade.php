@extends('layouts.admin.app')
@section('title', translate('Add new notification'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
        @include('admin-views.banner.partials._banner-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
      <div class="row g-2">
            <div class="col-12">
                <form action="{{route('admin.notification.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>{{translate('Send_Notification')}}</h4>
                        </div>

                    </div>
                    <div class="card">
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('title:')}}
                                            <i class="tio-info text-danger" data-toggle="tooltip" data-placement="right"
                                               title="{{ translate('not_more_than_100_characters') }}">
                                            </i>
                                        </label>
                                        <input type="text" name="title" maxlength="100" class="form-control" placeholder="{{translate('New notification')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label">{{translate('description:')}}
                                            <i class="tio-info text-danger" data-toggle="tooltip" data-placement="right"
                                               title="{{ translate('not_more_than_255_characters') }}">
                                            </i>
                                        </label>
                                        <textarea name="description" maxlength="256" class="form-control" placeholder="{{translate('Description...')}}" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label">{{translate('Notification Time Schedule:')}}</label>
                                        <input id="schedule_at" type="datetime-local" name="schedule_at" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-2">

                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">

                                    <div class="from_part_2 mb-4 ">
                                                <label class="input-label">{{ translate(' image') }}
                                                <small class="text-danger">* ( {{ translate('ratio') }} 8:1 )</small></label>
                                                <div class="custom-file">
                                                    <input type="file" name="image" id="customFileEg2" class="custom-file-input"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required
                                                        oninvalid="document.getElementById('en-link').click()">
                                                    <label class="custom-file-label" for="customFileEg2">{{ translate('choose file') }}</label>
                                                </div>
                                            </div>
                                            <div class="from_part_2 px-4">
                                                <div class="form-group">
                                                    <div class="upload-file__imgPromotion">
                                                        <img  width="465" id="viewer"
                                                            src="{{ asset('assets/admin/img/900x400/img1.jpg') }}" alt="image" />
                                                    </div>
                                                </div>
                                            </div>

                                    </div>

                                    <div class="form-group my-3">
                                        <div class="d-flex justify-content-center gap-4">
                                            <button  type="reset" id="reset" class="btn btn-white text-order_id border-primary">{{translate('reset')}}</button>
                                            <button  type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-top px-card pt-4">
                        <div class="row justify-content-between align-items-center gy-2">
                            <div class="col-sm-4 col-md-6 col-lg-8">
                                <h5 class="d-flex align-items-center gap-2 mb-0">
                                    {{translate('Notification_Table')}}
                                </h5>
                                <span class="text-muted">{{ $notifications->total() }} Notifications</span>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by title or Description') }}"
                                            aria-label="Search" value="{{ $search }}" required autocomplete="off" />
                                        <button
                                            class="btnSearchArrow" type="submit">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>


                    <div class="py-3">
                        <div class="table-responsive datatable-custom">
                            <table class="table table-border table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('image')}}</th>
                                    <th>{{translate('title')}}</th>
                                    <th>{{translate('description')}}</th>
                                    <th>{{translate('status')}}</th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($notifications as $key=>$notification)
                                    <tr>
                                        <td>{{$notifications->firstitem()+$key}}</td>
                                        <td>
                                            @if($notification['image']!=null)
                                                <img class="img-vertical-150"
                                                     onerror="this.src='{{asset('assets/admin/img/160x160/img2.jpg')}}'"
                                                     src="{{asset('/storage/notification')}}/{{$notification['image']}}">
                                            @else
                                                <label class="badge badge-soft-warning">{{translate('No')}} {{translate('image')}}</label>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="max-w300 text-wrap">
                                                {{substr($notification['title'],0,25)}} {{strlen($notification['title'])>25?'...':''}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="max-w300 text-wrap">
                                                {{substr($notification['description'],0,25)}} {{strlen($notification['description'])>25?'...':''}}
                                            </div>
                                        </td>
                                        <td>
                                            <label class="switcher">
                                                <input class="switcher_input" type="checkbox" onclick="status_change(this)" id="{{$notification['id']}}"
                                                    data-url="{{route('admin.notification.status',[$notification['id'],0])}}" {{$notification['status'] == 1? 'checked' : ''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-secondary btn-sm edit square-btn"
                                                href="{{route('admin.notification.edit',[$notification['id']])}}"><i style="color:#A1A5B7;" class="tio-edit"></i></a>
                                                <button type="button" class="btn btn-secondary btn-sm delete square-btn"
                                                onclick="$('#notification-{{$notification['id']}}').submit()"><i style="color:#A1A5B7;" class="tio-delete"></i></button>
                                            </div>
                                            <form
                                                action="{{route('admin.notification.delete',[$notification['id']])}}"
                                                method="post" id="notification-{{$notification['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4 px-3">
                            <div class="d-flex justify-content-lg-center">
                                {!! $notifications->links() !!}
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg2").change(function () {
            readURL(this);
        });
    </script>
@endpush

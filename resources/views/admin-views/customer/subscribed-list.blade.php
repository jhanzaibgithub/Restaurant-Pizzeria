@extends('layouts.admin.app')

@section('title', translate('Subscribed List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .input-container {
            border: none;
            border-bottom: 1px solid #e7eaf3;
        }

        .selectUser{
            padding:15px 10px;
            color: #fff;
            background-color: #FE6524;
            cursor: pointer;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endpush

@section('content')
<div class="ml-5">
@include('admin-views.customer.partials._customers-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <span class="page-header-title">
                    {{translate('Subscribed_Customers')}}
                </span>
            </h2>
        </div>
        <div class="card">
            <div class="card-top px-card pt-4">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-sm-4 col-md-6 col-lg-8">
                        <button class="btn btn-warning rounded text-nowrap text-white" id="BulkEmails" type="button"
                        data-toggle="modal" data-target="#add-BulkEmails" title="BulkEmails">

                        {{ translate('Send Bulk Emails') }}
                        </button>
                    </div>
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Search by Email') }}"
                                    aria-label="Search" value="" required autocomplete="off" />
                                <button
                                    class="btnSearchArrow" type="submit">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="py-4">
                <div class="table-responsive datatable-custom">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="">
                                    {{translate('SL')}}
                                </th>
                                <th>{{translate('email')}}</th>
                                <th>{{translate('Subscribed At')}}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($newsletters as $key=>$newsletter)
                            <tr class="">
                                <td class="">
                                    {{$newsletters->firstitem()+$key}}
                                </td>
                                <td>
                                    <a class="text-dark" href="mailto:{{$newsletter['email']}}?subject={{translate('Mail from '). $restaurantName}}">{{$newsletter['email']}}</a>
                                </td>
                                <td>{{date('Y/m/d '.config('timeformat'), strtotime($newsletter->created_at))}}</td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="table-responsive px-3">
                    <div class="d-flex justify-content-lg-end">
                        {!! $newsletters->links() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="add-BulkEmails" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #FE6524;">
                        <h5 class="modal-title text-white">{{ translate('Bulk Emails') }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.customer.sendBulkEmail')}}" method="post" id="" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 d-flex align-items-center">
                                    <div class="col-10">
                                        <select name="recipient[]" id="recipient" class="form-control js-select2-custom" multiple="multiple">
                                            @foreach($newsletters as $key=>$newsletter)
                                            <option value="{{$newsletter['email']}}">{{$newsletter['email']}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <span class="badge selectUser" data-toggle="modal" title="Open Model" data-target="#add-recepient">Select Users</span>
                                </div>

                                <div class="col-12">
                                    <div class="input-container">
                                        <input type="text" class="form-control border-0 border-bottom" name="emailSubject" placeholder="Subject">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mt-2">
                                        <div id="summernote"></div>
                                        <input type="hidden" id="summernoteContent" name="message">
                                        <script>
                                            $('#summernote').summernote({
                                                placeholder: '',
                                                tabsize: 2,
                                                height: 240,
                                                toolbar: [
                                                    ['style', ['style']],
                                                    ['font', ['bold', 'underline', 'clear']],
                                                    ['color', ['color']],
                                                    ['para', ['ul', 'ol', 'paragraph']],
                                                    ['table', ['table']],
                                                    ['insert', ['link', 'picture', 'video']],
                                                    ['view', ['fullscreen', 'codeview', 'help']]
                                                ],
                                                callbacks: {
                                                    onChange: function(contents) {
                                                        $('#summernoteContent').val(contents);
                                                        }
                                                    }
                                                });
                                        </script>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-start mt-3">
                                <button type="submit" id="" class="btn btn-primary">{{ translate('Send') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-recepient" tabindex="-1" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ff6767;">
                    <h5 class="modal-title text-white">{{ translate('select_recipient') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" >
                        <table class="table table-striped">
                            <thead>

                                <div class="form-group d-flex justify-content-between align-items-center gap-2">
                                    <div class="ml-2">
                                        <input type="checkbox" name="all_checkbox_ids" id="all_checkbox_ids"><span class="ml-2">Select All</span>
                                    </div>
                                    <div >
                                        <form action="{{url()->current()}}" method="GET" id="recipient-search-form" >
                                            <div class="input-group">
                                                <input style="width: 255px; height: 29px;" id="datatableSearch_" type="search" name="search" class="form-control  rounded-pill"
                                                    placeholder="{{ translate('Search by Email') }}" aria-label="Search" value="" required
                                                    autocomplete="off">
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </thead>
                            <tbody>
                                <tr>
                                <div class="row">
                                @foreach($newsletters as $key=>$newsletter)
                                    <div class="col-md-5">
                                        <label><input type="checkbox" name="checkbox_ids" class="checkbox_ids" value="{{$newsletter['email']}}">{{$newsletter['email']}}</label>
                                    </div>
                                @endforeach
                                </div>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" data-dismiss="modal" aria-label="Close" id="add-recipients-done" class="btn btn-primary">{{ translate('Done') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
@endpush
@push('script_2')
    <script>
        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $("#all_checkbox_ids").click(function () {
                $('.checkbox_ids').prop('checked', $(this).prop('checked'));
            });

            $('#add-recipients-done').on('click', function (e) {
                    e.preventDefault();
                    var emails = [];
                    var selectAllChecked = $('#all_checkbox_ids').prop('checked');

                    $('#add-recepient input.checkbox_ids').each(function () {
                        if (selectAllChecked || $(this).prop('checked')) {
                            emails.push($(this).val());
                        }
                    });

                    $('#recipient').val(emails).trigger('change');
                    $('#add-recepient').modal('hide');
                });
        });

    </script>

    <script>
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });
    </script>
@endpush

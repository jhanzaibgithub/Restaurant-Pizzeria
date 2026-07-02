@extends('layouts.admin.app')
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <style>
        .input-container {
            border: none;
            /* Remove the default border */
            border-bottom: 1px solid #ccc;
            /* Add an underline border */
        }

    </style>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endpush

@section('content')
    <div class="col-lg-12 mt-4">
        <div class="card suscribed-customers">
            <!-- suscribed-customers -->
            <div class="suscribed-customers">
                <h4 class="mb-0">{{ translate('Suscribed Customers') }}</h4>
            </div>


            <div class="p-2 p-sm-4">
                <div class="form-group d-flex justify-content-between gap-2">

                    <button class="btn btn-primary rounded text-nowrap" id="BulkEmails" type="button" data-toggle="modal"
                        data-target="#add-BulkEmails" title="BulkEmails">

                        {{ translate('Send Bulk Emails') }}
                    </button>

                    <form action="" method="">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                placeholder="{{ translate('Search by Email') }}" aria-label="Search" value="" required
                                autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    {{ translate('Search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal fade" id="quick-view" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" id="quick-view-modal">

                    </div>
                </div>
            </div>

            <div class="modal fade" id="add-BulkEmails" tabindex="-1" ">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #ff6767;">
                            <h5 class="modal-title text-white">{{ translate('Bulk Emails') }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="javascript:" method="post" id="" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                <div class="">
                                    <input type="text" class="form-control" name="recipient" placeholder="Recipient"
                                        data-role="tagsinput"  >
                                </div>
                            </div>
                                    <div class="col-12">
                                        <div class="input-container">
                                            <input type="text" class="form-control border-0 border-bottom"
                                                name="email-subject" placeholder="Subject">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-container mt-2">
                                            <textarea class="form-control border-0 border-bottom" id="customTextarea" rows="8" placeholder="Body Text"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mt-2">
                                            <div id="summernote"></div>
                                            <script>
                                                $('#summernote').summernote({
                                                    placeholder: '',
                                                    tabsize: 2,
                                                    height: 40,
                                                    toolbar: [
                                                        ['style', ['style']],
                                                        ['font', ['bold', 'underline', 'clear']],
                                                        ['color', ['color']],
                                                        ['para', ['ul', 'ol', 'paragraph']],
                                                        {{--  ['table', ['table']],  --}}
                                                        {{--  ['insert', ['link', 'picture', 'video']],  --}}
                                                        {{--  ['view', ['fullscreen', 'codeview', 'help']]  --}}
                                                    ]
                                                });
                                            </script>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-start mt-3">
                                    <button type="submit" id=""
                                        class="btn btn-primary">{{ translate('Send') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{--  ******************  SELECT THE RECEPIENTS************************  --}}
            {{--  <div class="p-2 p-sm-4">
    <div class="form-group d-flex justify-content-between gap-2">

        <input  id="select_recipient"
         data-toggle="modal" data-target="#add-recepient" title="select_recipient">

            {{ translate('Select The Recipient') }}

    </div>
</div>  --}}
            {{--  -------------------  --}}

        </div>
    </div>


    <div class="modal fade" id="quick-view" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>

    <div class="modal fade" id="add-recepient" tabindex="-1" ">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header" style="background-color: #ff6767;">
                    <h5 class="modal-title text-white">{{ translate('select_recipient') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>

                                <div class="form-group d-flex justify-content-between align-items-center gap-2">
                                    <div class="ml-2">
                                        <input type="checkbox" name="all_checkbox_ids" id="all_checkbox_ids"><span
                                            class="ml-2">Select All</span></th>
                                    </div>
                                    <div>
                                        <form action="" method="">
                                            <div class="input-group">
                                                <input style="width: 255px; height: 29px;" id="datatableSearch_"
                                                    type="search" name="search" class="form-control  rounded-pill"
                                                    placeholder="{{ translate('Search by Email') }}" aria-label="Search"
                                                    value="" required autocomplete="off">

                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </thead>
                            <tbody>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                                <tr>
                                    <div class="row">
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>
                                        <div class="col-3">
                                            <td><input type="checkbox" name="checkbox_ids" id="checkbox_ids"
                                                    class="checkbox_ids"><span class="ml-2">abcd@gmail.com</span></td>
                                        </div>

                                    </div>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" id=""
                                class="btn btn-primary">{{ translate('Done') }}</button>
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
    <script src="{{ asset('assets/admin') }}/js/tags-input.min.js"></script>

    <!-- JS Implementing Plugins -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets/admin') }}/js/vendor.min.js"></script>
    <script src="{{ asset('assets/admin') }}/js/theme.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#select_recipient').tagsinput({
                confirmKeys: [13, 44], // Enter and comma keys trigger tag addition
            });
        });
    </script>
    <script>
        document.getElementById('select_recipient').addEventListener('click', function () {
            // Add tags dynamically (e.g., from an array of tags)
            var tagsToAdd = ['tag1', 'tag2', 'tag3'];
            $('#select_recipient').tagsinput('add', tagsToAdd);

            // Show the modal
            $('#add-recepient').modal('show');
        });
    </script>
    <script>
    $(document).ready(function () {
        $('#select_recipient').on('click', function () {
            // Add tags dynamically (e.g., from an array of tags)
            var tagsToAdd = ['tag1', 'tag2', 'tag3'];
            $('#select_recipient').tagsinput('add', tagsToAdd);

            // Show the modal
            $('#add-recepient').modal('show');
        });
    });
</script>
    <script>
        $(function(e) {

            $("#all_checkbox_ids").click(function() {
                $('.checkbox_ids').prop('checked', $(this).prop('checked'));
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

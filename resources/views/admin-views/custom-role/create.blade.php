@extends('layouts.admin.app')

@section('title', translate('Create Role'))

@push('css_or_js')
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="ml-5">
        @include('admin-views.employee.partials._employee-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        <div class="card mb-3">
            <div class="card-body">
                    <h4 class="mb-0 d-flex align-items-center gap-2">
                        <span >
                            {{translate('employee_role_setup')}}
                        </span>
                    </h4>
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <form id="submit-create-role" method="post" action="{{route('admin.custom-role.store')}}">
                    @csrf
                <div class="row">
                    <div class="col-md-10">
                            <div class="form-group">
                                    <label class="input-label" for="name">{{translate('role_name:')}}</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                            aria-describedby="emailHelp"
                                            placeholder="{{translate('Ex')}} : {{translate('Store')}}" required>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="d-flex justify-content-end gap-3">
                            <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
                            </div>
                        </div>
                </div>

                    <div class="mb-5 d-flex flex-wrap align-items-center gap-3">
                        <h5 class="mb-0">{{translate('Module_Permission')}} : </h5>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="select-all-btn">
                            <label class="form-check-label" for="select-all-btn">{{translate('Select_All')}}</label>
                        </div>
                    </div>
                    <div class="row">
                        @foreach(MANAGEMENT_SECTION as $section)
                            <div class="col-xl-3 col-lg-3 col-sm-6">
                                <div class="form-group form-check m-3">
                                    <input type="checkbox" name="modules[]" value="{{$section}}" class="form-check-input select-all-associate"
                                            id="{{$section}}">
                                    <label class="form-check-label ml-2" for="{{$section}}">{{translate($section)}}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
               <div class="card-top px-card pt-4">
                            <div class="row justify-content-between align-items-center gy-2">
                                    <div class="col-md-4 col-lg-4">
                                        <h3 class="d-flex align-items-center gap-2 mb-0">
                                        {{translate('Employee_Role_Table')}}
                                        </h3>
                                        <span class="text-muted"> {{ count($rl) }} Roles</span>
                                    </div>
                                    <div class="col-md-8 col-lg-8 d-flex flex-row justify-content-end gap-2">

                                        <form action="{{ url()->current() }}" method="GET">
                                            <div class="input-group">
                                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                    placeholder="{{ translate('Search by EmployeeID/Role') }}"
                                                    aria-label="Search" value="" required autocomplete="off" />
                                                <button
                                                    class="btnSearchArrow" type="submit">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <button type="button"  class="btnExport" data-toggle="dropdown"
                                            aria-expanded="false">

                                            {{ translate('export') }}
                                            <i class="tio-download-to"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                                href="{{route('admin.custom-role.excel-export')}}">
                                                    <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                                        alt="">
                                                    {{ translate('Excel') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                             </div>
                    </div>

            <div class="py-4">
                <div class="table-responsive">
                    <table class="table table-border table-thead-bordered table-nowrap table-align-middle card-table" id="dataTable" cellspacing="0">
                        <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('role_name')}}</th>
                            <th>{{translate('modules')}}</th>
                            <th>{{translate('created_at')}}</th>
                            <th>{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rl as $k=>$r)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$r['name']}}</td>
                                <td class="text-capitalize">
                                    <div class="max-w300 text-wrap">
                                        @if($r['module_access']!=null)
                                            @php($comma = '')
                                            @foreach((array)json_decode($r['module_access']) as $m)
                                                {{$comma}}{{str_replace('_',' ',$m)}}
                                                @php($comma = ', ')
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td>{{date('d-m-Y',strtotime($r['created_at']))}}</td>
                                <td>
                                    <label class="switcher">
                                        <input type="checkbox" name="status" class="switcher_input" {{$r['status'] == true? 'checked' : ''}}
                                        onclick="role_status_change(this)" data-url="{{route('admin.custom-role.change-status', ['id' => $r['id']])}}" id="{{$r['id']}}"
                                        >
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{route('admin.custom-role.update',[$r['id']])}}"
                                        class="btn btn-secondary btn-sm square-btn"
                                        title="{{translate('Edit') }}">
                                        <i style=" color: #A1A5B7;" class="tio-edit"></i>
                                        </a>
                                        <a onclick="form_alert('role-{{$r->id}}', '{{translate('want_to_delete_this_employee?')}}')"
                                           class="btn btn-secondary btn-sm delete square-btn"
                                           title="{{translate('delete')}}">
                                            <i style=" color: #A1A5B7;" class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                                <form action="{{route('admin.custom-role.delete')}}" method="post" id="role-{{$r->id}}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{$r->id}}">
                                </form>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

    <script>

        $('#submit-create-role').on('submit',function(e){

            var fields = $("input[name='modules[]']").serializeArray();
            if (fields.length === 0)
            {
                toastr.warning('{{ translate('select_minimum_one_selection_box') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                return false;
            }else{
                $('#submit-create-role').submit();
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".select-all-associate").on('change', function (){
                if ($(".select-all-associate:checked").length == $(".select-all-associate").length) {
                    $("#select-all-btn").prop("checked", true);
                } else {
                    $("#select-all-btn").prop("checked", false);
                }
            });
            $("#select-all-btn").on('change', function (){
                if ($("#select-all-btn").is(":checked")) {
                    $(".select-all-associate").prop("checked", true);
                } else {
                    $(".select-all-associate").prop("checked", false);
                }
            });d
            if ($(".select-all-associate:checked").length == $(".select-all-associate").length) {
                $("#select-all-btn").prop("checked", true);
            }
        });
    </script>

    <script>
        function role_status_change(t) {
            let url = $(t).data('url');
            let checked = $(t).prop("checked");
            let status = checked === true ? 1 : 0;

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
                                status: status
                            },
                            success: function (data) {
                                toastr.success(data);
                            },
                            error: function (data) {
                                toastr.error(data.responseJSON);
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

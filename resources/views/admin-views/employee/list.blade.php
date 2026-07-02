@extends('layouts.admin.app')

@section('title', translate('Employee List'))

@push('css_or_js')
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.employee.partials._employeeCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
<div class="content container-fluid">
 <div class="row">
        <div class="col-12">
            <div class="card">
                  <div class="card-top px-card pt-4">
                            <div class="row justify-content-between align-items-center gy-2">
                                    <div class="col-lg-4 col-md-4">
                                        <h3 class="d-flex align-items-center gap-2 mb-0">
                                            {{translate('All_Employees')}}
                                        </h3>
                                        <span class="text-muted"> {{ $em->total() }} Employees</span>
                                    </div>
                                    <div class="col-lg-8 col-md-8 d-flex justify-content-end gap-2">

                                        <form action="{{ url()->current() }}" method="GET">
                                            <div class="input-group">
                                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                    placeholder="{{ translate('Search by Employee ID,') }}"
                                                    aria-label="Search" value="{{ $search }}" required autocomplete="off" />
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
                                                href="{{route('admin.employee.excel-export')}}">
                                                    <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                                        alt="">
                                                    {{ translate('Excel') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                             </div>
                    </div>
                <div class="py-3">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-border table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('Name')}}</th>
                                    <th>{{translate('Contact_Info')}}</th>
                                    <th>{{translate('Role')}}</th>
                                    <th>{{translate('Status')}}</th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($em as $k=>$e)
                            @if($e->role)
                                <tr>
                                    <td>{{$em->firstitem()+$k}}</td>
                                    <td class="text-capitalize">
                                        <div class="media align-items-center gap-3">
                                            <div class="media-body">{{$e['f_name'] . ' ' . $e['l_name']}}</div>
                                        </div>
                                    </td>
                                    <td >
                                      <div><a class="text-muted" href="mailto:{{$e['email']}}"><strong>{{$e['email']}}</strong></a></div>
                                      <div><a href="tel:{{$e['phone']}}" class="text-muted">{{$e['phone']}}</a></div>
                                    </td>
                                    <td><span class="text-muted">{{$e->role['name']}}</span></td>
                                    <td>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input"
                                                   onclick="location.href='{{route('admin.employee.status',[$e['id'],$e->status?0:1])}}'"
                                                   class="toggle-switch-input" {{$e->status?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{route('admin.employee.update',[$e['id']])}}"
                                            class="btn btn-secondary btn-sm square-btn"
                                            title="{{translate('Edit')}}">
                                                <i style=" color: #A1A5B7;" class="tio-edit"></i>
                                            </a>
                                            <a onclick="form_alert('employee-{{$e->id}}', '{{translate('want_to_delete_this_employee?')}}')"
                                               class="btn btn-secondary btn-sm delete square-btn"
                                               title="{{translate('delete')}}">
                                                <i style=" color: #A1A5B7;" class="tio-delete"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.employee.delete')}}" method="post" id="employee-{{$e->id}}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{$e->id}}">
                                        </form>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-lg-center">
                            {{$em->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{asset('assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush

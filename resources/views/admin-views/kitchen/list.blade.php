@extends('layouts.admin.app')

@section('title', translate('Chef List'))

@push('css_or_js')
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="ml-5">
@include('admin-views.kitchen.partials._chefCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
<div class="content container-fluid">

<div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-top px-card pt-4">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-sm-4 col-md-6 col-lg-8">
                        <h3 class="d-flex align-items-center gap-2 mb-0">
                            {{translate('All_Chef_List')}}
                        </h3>
                        <span class="text-muted"> {{ $chefs->total() }} chef</span>
                    </div>
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Search by Chef ID, or Name') }}"
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
                <div class="py-4">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-border table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('Name')}}</th>
                                    <th>{{translate('Contact_Info')}}</th>
                                    <th>{{translate('Branch')}}</th>
                                    <th>{{translate('Status')}}</th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($chefs as $k=>$chef)
                                <tr>
                                    <td><span>{{$k+1}}</span></td>
                                    <td class="text-capitalize">{{$chef['f_name'] . ' ' . $chef['l_name']}}</td>
                                    <td>
                                        <div><a class="text-muted" href="mailto:{{$chef['email']}}"><strong>{{$chef['email']}}</strong></a></div>
                                        <div><a class="text-muted" href="tel:{{$chef['phone']}}">{{$chef['phone']}}</a></div>
                                    </td>
                                    <td>{{ $chefBranchNames[$chef->id] ?? '' }}</td>
                                    <td>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input"
                                                   onclick="location.href='{{route('admin.kitchen.status',[$chef['id'],$chef->is_active?0:1])}}'"
                                                   class="toggle-switch-input" {{$chef->is_active?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{route('admin.kitchen.update',[$chef['id']])}}"
                                            class="btn btn-secondary btn-sm square-btn"
                                            title="{{translate('Edit')}}">
                                                <i style=" color: #A1A5B7;" class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-secondary btn-sm square-btn" title="{{translate('Delete')}}" href="javascript:"
                                            onclick="form_alert('chef-{{$chef['id']}}','{{translate('Want to delete this chef ?')}}')">
                                                <i style=" color: #A1A5B7;" class="tio-delete"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.kitchen.delete',[$chef['id']])}}"
                                              method="post" id="chef-{{$chef['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-lg-end">
                            {{$chefs->links()}}
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

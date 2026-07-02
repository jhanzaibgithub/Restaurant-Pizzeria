@extends('layouts.branch.app')

@section('title', translate('Add new table'))

@push('css_or_js')

@endpush
@section('content')
<div class="ml-5">
    @include('branch-views.table.partials._tables-setup-inline-menu')
</div>
<hr class="li_hr-top">
    <div class="content container-fluid">


        <div class="row g-2">
            {{-- <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('branch.table.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="number">{{translate('Table Number')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number" class="form-control" id="number"
                                            placeholder="{{translate('Ex')}} : {{translate('1')}}" value="{{old('number')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{translate('Table Capacity')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="capacity" class="form-control" id="capacity"
                                            placeholder="{{translate('Ex')}} : {{translate('4')}}" min="1" max="99" value="{{old('capacity')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> --}}

            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-top px-card pt-4">
                        <div class="row justify-content-between align-items-center gy-2">
                            <div class="col-sm-4 col-md-6 col-lg-8">
                                <h5 class="d-flex align-items-center gap-2 mb-0">
                                    {{translate('table')}}
                                    <span class="badge badge-soft-dark rounded-50 fz-12">{{$tables->total()}}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{url()->current()}}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search"
                                                class="form-control"
                                                placeholder="{{translate('Search')}}" aria-label="Search"
                                                value="{{$search}}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                {{translate('Search')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}
                      <!-- Header -->
                      <div class="card-header d-flex justify-content-between align-items-center ">
                        <div>
                        <h3>
                            {{translate('Table_List')}}
                        </h3>
                        <span class="text-muted">{{$tables->total()}} {{ translate('Tables') }}</span>
                        </div>
                        <a href="{{ route('branch.promotion.create') }}" class="btn btn-primary">
                                            <i class="tio-add"></i> {{translate('add_New')}}
                        </a>
                    </div>
            <!-- End Header -->

                    <div class="py-4">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover table-bordered table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('Table Number')}}</th>
                                        <th>{{translate('Table Capacity')}}</th>
                                        <th>{{translate('Branch')}}</th>
                                        <th>{{translate('Status')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($tables as $k=>$table)
                                    <tr>
                                        <th>{{$k+1}}</th>
                                        <td class="text-order_id">{{$table['number']}}</td>
                                        <td>{{$table['capacity']}}</td>
                                        <td>{{$table->branch->name ?? null}}</td>
                                        <td>
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input"
                                                        onclick="location.href='{{route('branch.table.status',[$table['id'],$table->is_active?0:1])}}'"
                                                        class="toggle-switch-input" {{$table->is_active?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{route('branch.table.edit',[$table['id']])}}"
                                                    class="btn btn-secondary btn-sm edit square-btn"
                                                    title="{{translate('Edit')}}">
                                                    <i style="color:#A1A5B7;" class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-secondary btn-sm edit square-btn" title="{{translate('Delete')}}" href="javascript:"
                                                    onclick="form_alert('table-{{$table['id']}}','{{translate('Want to delete this table ?')}}')">
                                                    <i style="color:#A1A5B7;" class="tio-delete"></i>
                                                </a>
                                            </div>
                                            <form action="{{route('branch.table.delete',[$table['id']])}}"
                                                    method="post" id="table-{{$table['id']}}">
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
                                <!-- Pagination -->
                                {{$tables->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush


@extends('layouts.admin.app')
@section('title', translate('Tables'))

@push('css_or_js')
<style>
    .bg-gray{
        background: #e4e4e4;
    }
    .bg-c1 {
        background-color: #FF6767 !important;
    }
    .c1 {
        color: #FF6767 !important;
    }
</style>
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.table.partials._tables-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
            <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center ">
                                    <div>
                                    <h3>
                                        {{translate('Table_List')}}
                                    </h3>
                                    <span class="text-muted"> 2 Tables</span>
                                    </div>
                                    <a href="{{ route('admin.promotion.create') }}" class="btn btn-primary">
                                                        <i class="tio-add"></i> {{translate('add_New')}}
                                    </a>
                        </div>
            <div class="card-body">
                    <div class="py-4">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
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
                                    @foreach($tables as $key=>$table)
                                    <tr>
                                        <th scope="row">{{$key+1}}</th>
                                        <td class="text-order_id">{{$table->number}}</td>
                                        <td>{{$table->capacity ?? ''}}</td>
                                        <td>{{$table->branch->name ?? ''}}</td>
                                        <td>
                                            <label class="switcher">
                                                <input class="switcher_input" type="checkbox" onclick="location.href='{{route('admin.table.status',[$table->id,$table->is_active?0:1])}}'" {{$table->is_active?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-3">
                                                <a href="{{route('admin.table.update',[$table->id])}}"
                                                    class="btn btn-secondary btn-sm square-btn"
                                                    title="{{translate('Edit')}}">
                                                    <i style="color:#A1A5B7;" class="tio-edit"></i>
                                                </a>

                                                <button type="button" class="btn btn-secondary btn-sm delete square-btn"
                                                        onclick="form_alert('table-{{$table->id}}','{{translate('Want to delete this branch ?')}}')">
                                                        <i style=" color: #A1A5B7;" class="tio-delete"></i></button>
                                            </div>
                                            <form action="{{route('admin.table.delete', [$table->id])}}"
                                                method="post" id="table-{{$table->id}}">
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
                                {{-- {{$tables->links()}} --}}
                            </div>
                        </div>
                    </div>
              </div>
        </div>
    </div>
@endsection

@push('script')

@endpush



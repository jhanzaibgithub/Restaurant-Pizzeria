@extends('layouts.admin.app')
@section('title', translate('Add new branch'))

@push('css_or_js')

@endpush

@section('content')
<div class="ml-5">
@include('admin-views.branch._branchCAL-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        <div class="card">
            <div class="card-top px-card pt-4">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-sm-4 col-md-6 col-lg-8">
                        <h3 class="d-flex align-items-center gap-2 mb-0">
                            {{translate('All_Branch_List')}}
                        </h3>
                        <span class="text-muted"> {{ $branches->total() }} branches</span>
                    </div>
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Search by Branch') }}"
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

            <div class="card-body px-0 pb-0">
                <div class="table-responsive datatable-custom">
                    <table
                        class="table table-border table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('Growth')}}</th>
                            <th>{{translate('branch')}}</th>
                            <th>{{translate('Contact_Info')}}</th>
                            <th>{{translate('promotion')}}</th>
                            <th>{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($branches as $key=>$branch)
                            <tr>
                                <td>{{$branches->firstItem()+$key}}</td>
                                @if($branch->trend == 'up')
                                    <td class="text-success">{{round($branch->growth,2)}} %</td>
                                @else
                                    <td class="text-danger">{{round($branch->growth,2)}} %</td>
                                @endif
                                <td>
                                    <div class="media align-items-center gap-3 px-3">
                                        <div class="media-body d-flex align-items-center flex-wrap">
                                            <span> {{$branch['name']}}</span>
                                            @if($branch['id']==1)
                                                <span class="badge badge-soft-danger">{{translate('main')}}</span>
                                            @else
                                                <span class="badge badge-soft-info">{{translate('sub')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>

                                    <a href="mailto:{{$branch['email']}}" class="text-muted">{{$branch['email']}}</a><br>
                                        <a href="tel:{{$branch['phone']}}" class="text-muted">{{$branch['phone']}}</a>

                                </td>
                                <td>
                                    <label class="switcher">
                                        <input class="switcher_input" type="checkbox" onclick="location.href='{{route('admin.promotion.status',[$branch['id'],$branch->branch_promotion_status?0:1])}}'" {{$branch->branch_promotion_status?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switcher">
                                        <input class="switcher_input" type="checkbox" onclick="location.href='{{route('admin.branch.status',[$branch['id'],$branch->status?0:1])}}'" {{$branch->status?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    @if(env('APP_MODE')!='demo' || $branch['id']!=1)
                                        <div class="d-flex justify-content-center gap-3">
                                            <a class="btn btn-secondary btn-sm edit square-btn"
                                                href="{{route('admin.branch.edit',[$branch['id']])}}">
                                                <i style=" color: #A1A5B7;" class="tio-edit"></i></a>
                                            @if($branch['id']!=1)
                                                <button type="button" class="btn btn-secondary btn-sm delete square-btn"
                                                        onclick="form_alert('branch-{{$branch['id']}}','{{translate('Want to delete this branch ?')}}')">
                                                        <i style=" color: #A1A5B7;" class="tio-delete"></i></button>
                                            @endif
                                        </div>
                                        <form action="{{route('admin.branch.delete',[$branch['id']])}}"
                                                method="post" id="branch-{{$branch['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    @else
                                        <label class="badge badge-soft-danger">{{translate('Not Permitted')}}</label>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4 px-3">
                    <div class="d-flex justify-content-lg-center">
                        {!! $branches->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')


@endpush

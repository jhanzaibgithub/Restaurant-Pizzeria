@extends('layouts.admin.app')

@section('title', translate('Add new table'))

@push('css_or_js')

@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.table.partials._tables-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">

    <div class="row g-2">
            <div class="col-12">
                <form action="{{route('admin.table.store')}}" method="post">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header d-flex flex-column align-items-baseline">
                                <h4 >
                                {{translate('add_New_Table')}}
                                </h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="number">{{translate('Table_Number:')}}</label>
                                        <input type="number" name="number" class="form-control" id="number"
                                            placeholder="{{translate('Ex')}} : {{translate('1')}}" value="{{old('number')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="name">{{translate('Table Capacity:')}} </label>
                                        <input type="number" name="capacity" class="form-control" id="capacity"
                                            placeholder="{{translate('Ex')}} : {{translate('4')}}" min="1" max="99" value="{{old('capacity')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlSelect1">{{translate('Select_Branch:')}} </label>
                                        <select name="branch_id" class="custom-select" required>
                                            <option value="" selected>{{ translate('--select--') }}</option>
                                            @foreach($branches as $branch)
                                                <option value="{{$branch['id']}}">{{$branch['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="">{{translate('Select_Group:')}} </label>
                                        <select name="group_id" class="custom-select" required>
                                            <option value="" selected>{{ translate('--select--') }}</option>
                                            @foreach($groups as $group)
                                                <option value="{{$group['id']}}">{{$group['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-white text-order_id border-primary">{{translate('Reset')}}</button>
                                <button type="submit" class="btn btn-primary">{{translate('Add')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>

@endsection

@push('script')

@endpush


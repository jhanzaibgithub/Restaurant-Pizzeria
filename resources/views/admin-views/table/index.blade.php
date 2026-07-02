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
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <span class="page-header-title">
                    {{translate('Table_Availability')}}
                </span>
            </h2>
        </div>
        <div class="card card-body">
            <div class="d-flex gap-3 flex-wrap align-items-center justify-content-between mb-4">
                <select name="branch_id" class="custom-select max-w220" id="select_branch_option" required>
                    <option value="" selected disabled>{{ translate('--Select_Branch--') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="table_box_list justify-content-center gap-2 gap-md-3" id="table_list">

            </div>
        </div>
    </div>
@push('script')
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function (){
            $('#select_branch_option').on('change', function (){
                var branch = this.value;
                console.log(branch);
                $('#table_list').html('');
                $('#table_title').html('');
                $.ajax({
                    url: "{{ route('admin.table.branch-table') }}",
                    type: "POST",
                    data: {
                        branch_id : branch,
                        _token : '{{ csrf_token() }}',
                    },
                    dataType : 'json',
                    success: function (result){
                        $('#table_list').html(result.view);
                    },
                });
            });
        });
    </script>
@endpush
@endsection





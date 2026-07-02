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
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex flex-column align-items-baseline">

                            <h4 >
                            {{translate('add_New_Table')}}
                            </h4>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('branch.table.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="number">{{translate('Table Number')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number" class="form-control" id="number"
                                            placeholder="{{translate('Ex')}} : {{translate('1')}}" value="{{old('number')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="name">{{translate('Table Capacity')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="capacity" class="form-control" id="capacity"
                                            placeholder="{{translate('Ex')}} : {{translate('4')}}" min="1" max="99" value="{{old('capacity')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="group_id">{{translate('Select_Group:')}} <span class="text-danger">*</span></label>
                                        <select name="group_id" id="group_id" class="custom-select" required>
                                            <option value="" selected>{{ translate('--select--') }}</option>
                                            @foreach($groups as $group)
                                                <option value="{{$group['id']}}" {{ old('group_id') == $group['id'] ? 'selected' : '' }}>{{$group['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-white text-order_id border-primary">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(function() {
            $('#banner_type').change(function(){
                if ($(this).val() === 'video'){
                    $('#video_section').show();
                    $('#image_section').hide();
                }else{
                    $('#video_section').hide();
                    $('#image_section').show();
                }
            });
        });

        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg").change(function () {
            readURL(this, 'viewer');
        });

    </script>
@endpush

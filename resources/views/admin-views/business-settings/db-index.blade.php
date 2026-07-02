@extends('layouts.admin.app')
@section('title', translate('Settings'))

@push('css_or_js')
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.business-settings._setting-setup-inline-menu')
    </div>
    <hr class="li_hr">
    <div class="content container-fluid">
        <div  class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
            <div >
             <h3>
                {{ translate('system_setup') }}
             </h3>
            </div>
            <div>
                @include('admin-views.business-settings.partials._system-settings-inline-menu')
                <hr class="li_hr">
            </div>
         </div>

        <div class="row g-2">
            <div class="col-12">
                <form action="{{route('admin.business-settings.web-app.system-setup.clean-db')}}" method="post"
                        enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>{{translate('Clean_Database')}}</h4>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-footer">
                            <div class="check--item-wrapper clean--database-checkgroup mt-0">
                                @foreach($tables as $key=>$table)
                                   <div class="col-lg-3 col-md-4 col-sm-12">
                                   <div style="border-radius:7px;" class="check-item border d-flex justify-content-between m-2">
                                        <div class="d-flex ">
                                            <label class="form-check-label text-muted" for="business_section_{{$key}}">{{ Str::limit(translate($table), 20) }}</label>
                                            <strong><span class="text-order_id fz-12 ml-1">{{$rows[$key]}}</span></strong>
                                        </div>
                                        <div class=" form-check">
                                            <input type="checkbox" name="tables[]" value="{{$table}}"
                                                class="form-check-input"
                                                id="business_section_{{$key}}">
                                        </div>
                                    </div>
                                   </div>
                                @endforeach
                            </div>

                            <div class="btn--container mt-5">
                                <button type="reset" class="btn btn-white text-order_id">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('Clean')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).ready(function () {
            $("#purchase_code_div").click(function () {
                var type = $('#purchase_code').get(0).type;
                if (type === 'password') {
                    $('#purchase_code').get(0).type = 'text';
                } else if (type === 'text') {
                    $('#purchase_code').get(0).type = 'password';
                }
            });
        })
    </script>

    <script>
        $("form").on('submit',function(e) {
            e.preventDefault();
            Swal.fire({
                title: '{{translate('Are you sure?')}}',
                text: "{{translate('Sensitive_data! Make_sure_before_changing.')}}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FC6A57',
                cancelButtonColor: 'default',
                cancelButtonText: '{{translate('No?')}}',
                confirmButtonText:'{{translate('Yes?')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    this.submit();
                }else{
                    e.preventDefault();
                    toastr.success("{{translate('Cancelled')}}");
                    location.reload();
                }
            })
        });
    </script>
@endpush

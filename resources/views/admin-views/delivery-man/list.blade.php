@extends('layouts.admin.app')
@section('title', translate('Deliveryman List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="ml-5">
        @include('admin-views.delivery-man.partials._deliveryman-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                  <div class="card-top px-card pt-4">
                            <div class="row justify-content-between align-items-center gy-2">
                                    <div class="col-lg-4 col-md-4">
                                        <h3 class="d-flex align-items-center gap-2 mb-0">
                                            {{translate('All_Delivery_Men')}}
                                        </h3>
                                        <span class="text-muted"> {{ $delivery_men->total() }}Delivery Men</span>
                                    </div>
                                    <div class="col-lg-8 col-md-8 d-flex flex-row justify-content-end gap-2">

                                                            <form action="{{ url()->current() }}" method="GET">
                                                                <div class="input-group">
                                                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                                        placeholder="{{ translate('Search by Deliveymen ID') }}"
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
                                                                    href="{{route('admin.delivery-man.excel-export')}}">
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
                        <div class="table-responsive datatable-custom">
                            <table class="table table-border table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('name')}}</th>
                                        <th>{{translate('Contact_Info ')}}</th>
                                        <th>{{translate('Rating')}}</th>
                                        <th>{{translate('Total_Orders')}}</th>
                                        <th>{{translate('Status')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                @foreach($delivery_men as $key=>$dm)
                                    <tr>
                                        <td>{{$delivery_men->firstitem()+$key}}</td>
                                        <td>
                                                <div class="media-body">
                                                    {{$dm['f_name'].' '.$dm['l_name']}}
                                                </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div>
                                                    <a class="text-muted" href="mailto:{{$dm['email']}}">
                                                        <strong>{{$dm['email']}}</strong>
                                                    </a>
                                                </div>
                                                <a class="text-muted" href="tel:{{$dm['phone']}}">{{$dm['phone']}}</a>
                                            </div>
                                        </td>
                                        <td>5star</td>
                                        <td><span class="text-muted">{{ $dm['orders_count'] }}</span></td>
                                        <td>
                                            <label class="switcher">
                                                <input id="{{$dm['id']}}" type="checkbox" class="switcher_input" {{$dm['is_active'] == 1? 'checked' : ''}}
                                                       data-url="{{route('admin.delivery-man.ajax-is-active', ['id'=>$dm['id']])}}" onchange="status_change(this)"
                                                >
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-3">
                                                <a class="btn btn-secondary btn-sm edit square-btn"
                                                href="{{route('admin.delivery-man.edit',[$dm['id']])}}"><i style=" color: #A1A5B7;" class="tio-edit"></i></a>
                                                <button type="button" class="btn btn-secondary btn-sm delete square-btn"
                                                onclick="form_alert('delivery-man-{{$dm['id']}}','{{translate('Want to remove this information ?')}}')"><i style=" color: #A1A5B7;" class="tio-delete"></i></button>
                                            </div>
                                            <form action="{{route('admin.delivery-man.delete',[$dm['id']])}}"
                                                method="post" id="delivery-man-{{$dm['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div class="table-responsive px-3 mt-3">
                            <div class="d-flex justify-content-end">
                                {!! $delivery_men->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.delivery-man.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush

@extends('layouts.admin.app')

@section('title', translate('New Joining Request'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="ml-5">
        @include('admin-views.delivery-man.partials._deliveryman-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        <div class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
            <div>
                <h3>
                    {{ translate('New_Joining_Request') }}
                </h3>
            </div>
            <div>
                @include('admin-views.delivery-man.partials._deliverymanRequest-setup-inline-menu')
                <hr class="li_hr">
            </div>
        </div>

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-top px-card pt-4">
                        <div class="row justify-content-between align-items-center gy-2">
                            <div class="col-lg-4 col-md-4">
                                <h3 class="d-flex align-items-center gap-2 mb-0">
                                    {{ translate('Pending_Requests') }}
                                </h3>
                            </div>
                            <div class="col-lg-8 col-md-8 d-flex flex-row justify-content-end gap-2">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by Deliveymen ID') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off" />
                                        <button class="btnSearchArrow" type="submit">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </form>
                                <button type="button" class="btnExport" data-toggle="dropdown" aria-expanded="false">

                                    {{ translate('export') }}
                                    <i class="tio-download-to"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('admin.delivery-man.excel-export') }}">
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
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('name') }}</th>
                                        <th>{{ translate('Contact_Info ') }}</th>
                                        <th class="text-center">{{ translate('Branch') }}</th>
                                        <th class="text-center">{{ translate('Identity Type') }}</th>
                                        <th class="text-center">{{ translate('Identity Number') }}</th>
                                        <th class="text-center">{{ translate('Identity Image') }}</th>
                                        <th class="text-center">{{ translate('Status') }}</th>
                                        <th class="text-center">{{ translate('action') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($delivery_men as $key => $dm)
                                        <tr>
                                            <td>{{ $delivery_men->firstitem() + $key }}</td>
                                            <td>
                                                <div class="media gap-3 align-items-center">
                                                    <div class="avatar">
                                                        <img width="60" class="img-fit rounded-circle"
                                                            onerror="this.src='{{ asset('assets/admin/img/160x160/img1.jpg') }}'"
                                                            src="{{ asset('/storage/delivery-man') }}/{{ $dm['image'] }}">
                                                    </div>
                                                    <div class="media-body">
                                                        {{ $dm['f_name'] . ' ' . $dm['l_name'] }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <div>
                                                        <a class="text-dark" href="mailto:{{ $dm['email'] }}">
                                                            <strong>{{ $dm['email'] }}</strong>
                                                        </a>
                                                    </div>
                                                    <a class="text-dark"
                                                        href="tel:{{ $dm['phone'] }}">{{ $dm['phone'] }}</a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($dm->branch_id == 0)
                                                    <label
                                                        class="badge badge-soft-primary">{{ translate('All Branch') }}</label>
                                                @else
                                                    <label
                                                        class="badge badge-soft-primary">{{ $dm->branch ? $dm->branch->name : 'Branch deleted!' }}</label>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ translate($dm->identity_type) }}</td>
                                            <td class="text-center">{{ $dm->identity_number }}</td>
                                            <td class="text-center">
                                                <div class="d-flex gap-2" data-toggle="" data-placement="top"
                                                    title="{{ translate('click for bigger view') }}">
                                                    @foreach (json_decode($dm['identity_image'], true) as $identification_image)
                                                        @php($image_full_path = asset('/storage/delivery-man') . '/' . $identification_image)
                                                        <div class="overflow-hidden">
                                                            <img class="cursor-pointer rounded img-fit"
                                                                style="max-height: 60px; width: 100px; min-width: 100px;"
                                                                onerror="this.src='{{ asset('assets/admin/img/160x160/img1.jpg') }}'"
                                                                src="{{ $image_full_path }}"
                                                                onclick="show_modal('{{ $image_full_path }}')">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <strong
                                                    class="text-info text-capitalize">{{ translate($dm->application_status) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <div class="justify-content-center">
                                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{ translate('Approve') }}"
                                                        onclick="request_alert('{{ route('admin.delivery-man.application', [$dm['id'], 'approved']) }}','{{ translate('you_want_to_approve_this_application') }}')"
                                                        href="javascript:"><i class="tio-done font-weight-bold"></i></a>
                                                    @if ($dm->application_status != 'denied')
                                                        <a class="btn btn-sm btn--danger btn-outline-danger action-btn"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="{{ translate('Deny') }}"
                                                            onclick="request_alert('{{ route('admin.delivery-man.application', [$dm['id'], 'denied']) }}','{{ translate('you_want_to_deny_this_application') }}')"
                                                            href="javascript:"><i class="tio-clear"></i></a>
                                                    @endif

                                                </div>
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
        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="identification_image_view_modal" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div data-dismiss="modal">
                            <img onerror="this.src='{{ asset('assets/admin/img/160x160/img1.jpg') }}'" alt=""
                                class="" id="identification_image_element" style="width: 100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function request_alert(url, message) {
            Swal.fire({
                title: '{{ translate('are_you_sure') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>

    <script>
        function show_modal(image_location) {
            $('#identification_image_view_modal').modal('show');
            if (image_location != null || image_location !== '') {
                $('#identification_image_element').attr("src", image_location);
            }
        }
    </script>
@endpush

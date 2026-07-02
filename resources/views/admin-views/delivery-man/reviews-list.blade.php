@extends('layouts.admin.app')

@section('title', translate('Review List'))

@push('css_or_js')
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
                                    {{ translate('Review_List') }}
                                </h3>
                                <span class="text-muted"> {{ $reviews->total() }} Reviews</span>
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
                                            href="#">
                                            <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}"
                                                alt="">
                                            {{ translate('Excel') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="py-3">
                        <div class="table-responsive datatable-custom">
                            <table id="columnSearchDatatable"
                                class="table table-border table-thead-border table-nowrap table-align-middle card-table"
                                data-hs-datatables-options='{
                                    "order": [],
                                    "orderCellsTop": true
                                }'>
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('deliveryman') }}</th>
                                        <th>{{ translate('customer') }}</th>
                                        <th class="text-center">{{ translate('rating') }}</th>
                                        <th>{{ translate('review') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($reviews as $key => $review)
                                        <tr>
                                            <td>{{ $reviews->firstitem() + $key }}</td>
                                            <td>
                                                @if (isset($review->delivery_man))
                                                    <div>
                                                        <a class="text-muted"
                                                            href="{{ route('admin.delivery-man.preview', [$review['delivery_man_id']]) }}">
                                                            {{ $review->delivery_man->f_name . ' ' . $review->delivery_man->l_name }}
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">
                                                        {{ translate('Deliveryman_Unavailable') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($review->customer))
                                                    <div>
                                                        <a class="text-muted"
                                                            href="{{ route('admin.customer.view', [$review->user_id]) }}">
                                                            {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">
                                                        {{ translate('Customer unavailable') }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="d-flex justify-content-center">
                                                <div class="text-warning d-inline-flex align-items-center gap-1">
                                                    @for ($i = 0; $i < $review->rating; $i++)
                                                        <i class="tio-star"></i>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>
                                                <div class="max-w300 line-limit-3">
                                                    {{ $review->comment ?? '' }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4 px-3">
                            <div class="d-flex justify-content-lg-end">
                                {!! $reviews->links() !!}
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
        $(document).on('ready', function() {
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush

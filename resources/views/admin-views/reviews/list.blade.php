@extends('layouts.admin.app')
@section('title', translate('Review List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
  <div class="ml-5">
        @include('admin-views.category.partials._menu-setup-inline-menu')
    </div>
    <hr class="li_hr-top">
    <div class="content container-fluid">
        <div class="row g-2">
            <div class="col-12">
                <div class="row li_hr-sub border mb-5 px-2 py-3 mx-1">
                    <div>
                        <h3>
                            {{ translate('Product_List') }}
                        </h3>
                    </div>
                    <div>
                        @include('admin-views.product.partials._product-setup-inline-menu')
                        <hr class="li_hr">
                    </div>
                </div>
                <div class="card">
                    <div class="card-top px-card pt-4">
                        <div class="card-top px-card pt-4">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-lg-4 col-md-4">
                                    <h3 class="d-flex align-items-center gap-2 mb-0">
                                        {{ translate('Product_Reviews') }}
                                    </h3>
                                    <span class="text-muted"> {{ $reviews->total() }} Reviews</span>
                                </div>
                                <div class="col-lg-8 col-md-8 d-flex flex-row justify-content-end gap-2">
                                    <div>
                                        <form action="{{ url()->current() }}" method="GET">
                                            <div class="input-group">
                                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                    placeholder="{{ translate('Search by ID, customer or payment status') }}" aria-label="Search"
                                                    value="" required autocomplete="off" />
                                                <button class="btnSearchArrow" type="submit">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btnExport d-flex align-items-center" data-toggle="dropdown" aria-expanded="false">
                                            {{ translate('export') }}
                                            <i class="tio-download-to"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                                    href="{{ route('admin.delivery-man.excel-export') }}">
                                                    <img width="14" src="{{ asset('assets/admin/img/icons/excel.png') }}" alt="">
                                                    {{ translate('Excel') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-4">
                        <div class="table-responsive datatable-custom">
                            <table
                                class="table table-border table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('product_name') }}</th>
                                        <th>{{ translate('customer_info') }}</th>
                                        <th>{{ translate('review') }}</th>
                                        <th>{{ translate('rating') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="set-rows">
                                    @foreach ($reviews as $key => $review)
                                        <tr>
                                            <td class="text-order_id">{{ $reviews->firstitem() + $key }}</td>
                                            <td>
                                                <div>
                                                    @if ($review->product)
                                                        <a class="text-dark media align-items-center gap-2"
                                                            href="{{ route('admin.product.view', [$review['product_id']]) }}">
                                                            <div class="avatar">
                                                                <img class="rounded-circle img-fit"
                                                                    src="{{ asset('/storage/product') }}/{{ $review->product['image'] }}"
                                                                    alt=""
                                                                    onerror="this.src='{{ asset('assets/admin/img/160x160/img2.jpg') }}'">
                                                            </div>
                                                            <strong><span
                                                                    class="media-body max-w220 text-dark">{{ $review->product['name'] }}</span></strong>
                                                        </a>
                                                    @else
                                                        <span class="badge-pill badge-soft-dark text-muted small">
                                                            {{ translate('Product unavailable') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($review->customer)
                                                    <div class="d-flex flex-column gap-1">
                                                        <a class="text-muted"
                                                            href="{{ route('admin.customer.view', [$review->user_id]) }}">
                                                            {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                                        </a>
                                                        <a class="text-muted fz-12"
                                                            href="tel:'{{ $review->customer->phone }}'">{{ $review->customer->phone }}</a>
                                                    </div>
                                                @else
                                                    <span class="badge-pill badge-soft-dark text-muted small">
                                                        {{ translate('Customer unavailable') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="max-w300 line-limit-3">{{ $review->comment }}</div>
                                            </td>
                                            <td>
                                                <label class="text-warning">
                                                    @for ($i = 0; $i < $review->rating; $i++)
                                                        <i class="tio-star"></i>
                                                    @endfor
                                                </label>
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
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.reviews.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#total_count').text(data.count);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush

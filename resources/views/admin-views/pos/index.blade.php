@extends('layouts.admin.app')

@section('title', translate('New_Sale'))
<link rel="stylesheet" href="{{asset('assets/admin')}}/css/pos.owl.carousel.css">

@push('css_or_js')
    <style>
            #location_map_div #pac-input{
            height: 40px;
            border: 1px solid #fbc1c1;
            outline: none;
            box-shadow: none;
            top: 7px !important;
            transform: translateX(7px);
            padding-left: 10px;
        }
a.card{
display: block;
}
div.pos--card-body{
 display: flex;
 flex-direction: row;
 justify-content: space-around;
 align-items: center;
 width: max-content;
 padding: 7px 0px 7px 0px;
}
div.pos--card-text{
 display: flex;
 flex-direction: column;
 align-items: center;
 padding: 3px 0px 3px 0px;
 margin: 5px 0px 5px 0px;
}
h5.pos--card-title
{
 color: #3F4254;
 padding: 15px 0px 0px 0px;
}
p.pos--card-subtitle{
    font-size: 12px;
 padding: 0px 0px 0px 0px;
 margin: -9px 0px 0px 0px;
}

.quantity-button {
    width: 18%;
    height: 5%;
    border-radius: 50%;
    border: 1px solid #FE6524;
    background-color: #FE6524;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    margin: 0 5px;
}

.quantity-button:hover {
    background-color: #FE6524;
}
    </style>
@endpush
@section('content')
    <div class="ml-5">
    @include('admin-views.pos.order.partials._pos-setup-inline-menu')
</div>
<hr class="li_hr-top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" class="d--none">
                    <div class="loading-inner">
                        <div class="pos-loader" role="status" aria-live="polite">
                            <div class="pos-loader__mark">
                                <span class="pos-loader__ring pos-loader__ring--outer"></span>
                                <span class="pos-loader__ring pos-loader__ring--inner"></span>
                                <span class="pos-loader__plate-core"></span>
                                <span class="pos-loader__fork"></span>
                                <span class="pos-loader__spoon"></span>
                                <span class="pos-loader__dot"></span>
                            </div>
                            <span class="sr-only">{{ translate('Loading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <header id="header"
            class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered d-none">
            <div class="navbar-nav-wrap">
                <div class="navbar-brand-wrapper">
                    <a class="navbar-brand py-0" href="{{ route('admin.dashboard') }}" aria-label="Front">
                        <img class="navbar-brand-logo rounded-circle avatar avatar-lg" style="border: 5px solid #80808012"
                            onerror="this.src='{{ asset('assets/admin/img/160x160/img1.jpg') }}'"
                            src="{{ asset('/storage/restaurant/' . $restaurantLogo) }}" alt="Logo">
                    </a>
                    {{ \Illuminate\Support\Str::limit($current_branch->name, 15) }}
                </div>
                <div class="navbar-nav-wrap-content-right">
                    <ul class="navbar-nav align-items-center flex-row">
                        <li class="nav-item d-none d-sm-inline-block">
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                    href="{{ route('admin.orders.list', ['status' => 'pending']) }}">
                                    <i class="tio-shopping-cart-outlined"></i>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper" href="javascript:;"
                                    data-hs-unfold-options='{
                                    "target": "#accountNavbarDropdown",
                                    "type": "css-animation"
                                }'>
                                    <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img"
                                            onerror="this.src='{{ asset('assets/admin/img/160x160/img1.jpg') }}'"
                                            src="{{ asset('/storage/admin') }}/{{ auth('admin')->user()->image }}"
                                            alt="Image">
                                        <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                    </div>
                                </a>
                                <div id="accountNavbarDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account navbar-dropdown-lg">
                                    <div class="dropdown-item-text">
                                        <div class="media align-items-center">
                                            <div class="avatar avatar-sm avatar-circle mr-2">
                                                <img class="avatar-img"
                                                    onerror="this.src='{{ asset('assets/admin/img/160x160/img1.jpg') }}'"
                                                    src="{{ asset('/storage/admin') }}/{{ auth('admin')->user()->image }}"
                                                    alt="Owner image">
                                            </div>
                                            <div class="media-body">
                                                <span class="card-title h5">{{ $current_branch->name }}</span>
                                                <span class="card-text">{{ $current_branch->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="javascript:"
                                        onclick="Swal.fire({
                                title: '{{ translate('Do you want to logout') }}?',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonColor: '#FC6A57',
                                cancelButtonColor: '#363636',
                                confirmButtonText: '{{ translate('Yes') }}',
                                denyButtonText: `{{ translate('Do not Logout') }}`,
                                }).then((result) => {
                                if (result.value) {
                                location.href='{{ route('admin.auth.logout') }}';
                                } else{
                                Swal.fire('Canceled', '', 'info')
                                }
                                })">
                                        <span class="text-truncate pr-2"
                                            title="Sign out">{{ translate('sign_out') }}</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <h3 class="ml-5 mb-3">{{ translate('Choose_Categories') }}</h3>
        <div class="row mb-4 mx-5 ">
            <div class="owl-carousel owl-theme">
                @php($i=0)
                @foreach ($categories as $item)
                @php($item_count = \App\CentralLogics\Helpers::getProductCount($item['id']) )
                    @if($item_count >0)
                        <div class="item">
                            <div class="pos-- card category_card" data-category-id="{{ $item['id'] }}" @if($item['id'] == $selectedCategory || ($selectedCategory == $i && $i==0)) style="border: 1px solid #FE6524;" @endif>
                                <div class="col-md-2 col-sm-4">
                                    <div class="pos--card-body" name="category" id="category_{{ $item['id'] }}" onclick="set_category_filter({{ $item['id'] }})">
                                        <div class="pos--card-text">
                                            <img class="pos--card-img" style="width: 50px; height: 50px;"  src="{{ asset('/storage/category') }}/{{ $item['image'] }}" onerror="this.src='{{asset('assets/admin/img/icons/categories/cheesecake.png')}}'" />
                                            <h5 class="pos--card-title">{{ Str::limit($item->name) }}</h5>
                                            <p class="pos--card-subtitle">{{ $item_count . ' Products'}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @php($i++)
                    @endif
                @endforeach
            </div>
        </div>

        <section class="section-content padding-y-sm bg-default mt-5">
            <div class="container-fluid">
                <div class="row gy-3 gx-2">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="pos-title">
                                <div
                                    class="d-flex flex-wrap flex-md-nowrap justify-content-between align-items-center gap-3 gap-xl-4 px-1 py-0">
                                    <div class="w-100 mr-xl-2">
                                        <h4 class="mb-0">{{ translate('Product_Section') }}</h4>
                                    </div>
                                    <div class="w-100 ml-xl-2">
                                        <form id="search-form">
                                            <div class="input-group input-group-merge input-group-flush border rounded">
                                                <div class="input-group-prepend pl-2">
                                                    <div class="input-group-text">
                                                        <img width="13"
                                                            src="{{ asset('assets/admin/img/icons/search.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                                <input id="datatableSearch" type="search"
                                                    value="{{ $keyword ? $keyword : '' }}" name="search"
                                                    class="form-control border-0"
                                                    placeholder="{{ translate('Search here') }}" aria-label="Search here">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @include('admin-views.pos.order.partials._product_partial', ['products' => $products])
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card billing-section-wrap">
                            <div class="pos-title ">
                                <h4 class="mb-0">{{ translate('Billing_Section') }}</h4>
                            </div>
                            <div class="p-2 p-sm-4">
                                <div class="form-group row row-rows-3 gap-2">
                                    <div class="col-md-3">
                                        <div class="card card-pos" style="width: 96px;  height: 102px;"
                                            onclick="showDataDine('Dine In')" id="DineInCard">
                                            <div class="card-body rounded" style="border:dashed; color:#E1E3EA;">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    <img class="img-fluid" style="max-height:70px;"
                                                        src="{{ asset('assets/admin/img/icons/categories/dine_in_grey.png') }}"
                                                        alt="">
                                                    <div class="card-title">Dine in</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-pos" style="width: 96px;  height: 102px;"
                                            onclick="showDataTake('Take Away')" id="takeAwayCard">
                                            <div class="card-body rounded" style="border:dashed; color:#E1E3EA;">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    <div>
                                                        <img class="img-fluid" style="max-height:70px;"
                                                            src="{{ asset('assets/admin/img/icons/categories/take_away_grey.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="card-title">Take Way</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-pos" style="width: 96px;  height: 102px;"
                                            onclick="showDataDelivery('Home Delivery')" id="homeDeliveryCard">
                                            <div class="card-body rounded" style="border:dashed; color:#E1E3EA;">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    <img class="img-fluid" style="max-height:70px;"
                                                        src="{{ asset('assets/admin/img/icons/categories/home_delivery_grey.png') }}"
                                                        alt="">
                                                    <div class="card-title">Home Delivery</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div id="dineInData" class="col-12 d-flex">
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="takeAwayData" class="form-group d-flex gap-2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="homeDeliveryData" class="form-group d-flex gap-2">
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div>
        <div class="modal fade" id="add-customer" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Add_New_Customer') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.pos.customer-store')}}" method="post" id="customer-form">
                            @csrf
                            <div class="row pl-2">
                                <div class="col-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="f_name" class="form-control" value=""
                                            placeholder="First name" required="">
                                    </div>
                                </div>
                                <div class="col-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="l_name" class="form-control" value=""
                                            placeholder="Last name" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="row pl-2">
                                <div class="col-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" value=""
                                            placeholder="Ex : ex@example.com" required="">
                                    </div>
                                </div>
                                <div class="col-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control" value=""
                                            placeholder="{{translate('Ex : +88017*****')}}" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <input type="number" step="any" id="latitude" name="latitude" class="form-control"
                                                       placeholder="{{ translate('Ex:') }} 23.8118428"
                                                       value="{{ old('latitude') }}" required >
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <input type="number" step="any" name="longitude" class="form-control"
                                                       placeholder="{{ translate('Ex:') }} 90.356331" id="longitude"
                                                       value="{{ old('longitude') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row pl-2">
                                <div class="col-md-12" id="location_map_div">
                                    <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                           data-placement="right"
                                           data-original-title="{{ translate('search_your_location_here') }}"
                                           type="text" placeholder="{{ translate('search_here') }}" />
                                    <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 76%"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-2">
                                <button type="reset" class="btn btn-secondary mr-1">{{ translate('reset') }}</button>
                                <button type="submit" id=""
                                    class="btn btn-primary">{{ translate('Add New') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($order)
            @php(session(['last_order' => false]))
            <div class="modal fade" id="print-invoice" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ translate('Print Invoice') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row ff-emoji">
                            <div class="row m-auto" id="printableArea">
                                @include('admin-views.pos.order.invoice')
                            </div>
                            <div class="col-md-12">
                                <center>
                                    <a href="{{ url()->previous() }}"
                                        class="btn btn-danger non-printable">{{ translate('Cancel') }}</a>
                                    <input type="button" class="btn btn-primary non-printable"
                                        onclick="printDiv('printableArea')"
                                        value="{{ translate('Print') }}" />
                                </center>
                                <hr class="non-printable">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script_2')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets/admin') }}/js/vendor.min.js"></script>
    <script src="{{ asset('assets/admin') }}/js/theme.min.js"></script>
    <script src="{{ asset('assets/admin') }}/js/sweet_alert.js"></script>
    <script src="{{ asset('assets/admin') }}/js/toastr.js"></script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $mapApiClientKey }}&libraries=places&v=3.51"></script>

<script>
        $( document ).ready(function() {
            function initAutocomplete() {
                var myLatLng = {

                    lat: 23.811842872190343,
                    lng: 90.356331
                };
                const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                    center: {
                        lat: 23.811842872190343,
                        lng: 90.356331
                    },
                    zoom: 13,
                    mapTypeId: "roadmap",
                });

                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                });

                marker.setMap(map);
                var geocoder = geocoder = new google.maps.Geocoder();
                google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                    var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                    var coordinates = JSON.parse(coordinates);
                    var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                    marker.setPosition(latlng);
                    map.panTo(latlng);

                    document.getElementById('latitude').value = coordinates['lat'];
                    document.getElementById('longitude').value = coordinates['lng'];


                    geocoder.geocode({
                        'latLng': latlng
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[1]) {
                                document.getElementById('address').innerHtml = results[1].formatted_address;
                            }
                        }
                    });
                });
                const input = document.getElementById("pac-input");
                const searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });
                let markers = [];
                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();
                    if (places.length == 0) {
                        return;
                    }
                    markers.forEach((marker) => {
                        marker.setMap(null);
                    });
                    markers = [];
                    const bounds = new google.maps.LatLngBounds();
                    places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        var mrkr = new google.maps.Marker({
                            map,
                            title: place.name,
                            position: place.geometry.location,
                        });
                        google.maps.event.addListener(mrkr, "click", function(event) {
                            document.getElementById('latitude').value = this.position.lat();
                            document.getElementById('longitude').value = this.position.lng();
                        });

                        markers.push(mrkr);

                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            };
            initAutocomplete();
        });

</script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script>
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        responsive:{
            0:{
                items:3
            },
            600:{
                items:6
            },
            1000:{
                items:6
            },
            1200:{
            items:6
        }
        }
    })
 </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var dineInCard = document.getElementById("DineInCard");
            dineInCard.click();
        });
        function showDataDine(category) {
            resetCardStyles();
            $('#dineInData').html(`

            <div class="p-2 p-sm-4">
                <div class="form-group row">
                    <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <label for="branch"
                        class="font-weight-semibold fz-12 text-dark">{{ translate('select_branch') }}</label>
                        <select onchange="store_key('branch_id',this.value)" id='branch' name="branch_id"
                            class="js-select2-custom-x form-ellipsis form-control ">
                            <option disabled selected>{{ translate('select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch['id'] }}"
                                    {{ session()->get('branch_id') == $branch['id'] ? 'selected' : '' }}>
                                    {{ $branch['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <label for="table"
                        class="font-weight-semibold fz-12 text-dark">{{ translate('select_table') }}</label>
                        <select id='table' onchange="store_key('table_id',this.value)" name="table_id"
                            class="js-select2-custom-x form-ellipsis form-control">
                            <option disabled selected>{{ translate('select_table') }}</option>
                            @foreach ($tables as $table)
                                <option value="{{ $table['id'] }}"
                                    {{ session()->get('table_id') == $table['id'] ? 'selected' : '' }}>
                                    {{ translate('table ') }} - {{ $table['number'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                </div>

                <div class="form-group row d-flex flex-wrap flex-sm-nowrap">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                    <label for="persons"
                        class="font-weight-semibold fz-12 text-dark">{{ translate('persons') }}</label>
                        <input type="number"
                            value="{{ session('people_number') }}"
                            name="number_of_people"
                            step="1"
                            oninput="store_key('people_number', this.value)"
                            class="form-control"
                            min="1"
                            max="99"
                            placeholder="{{ translate('Number Of People') }}">
                </div>
                </div>
                </div>

                <div class='w-100' id="cart">

                    @include('admin-views.pos._cart')
                </div>
            </div>


            `);
            $('#takeAwayData').html('');
            $('#homeDeliveryData').html('');

            $('#DineInCard').find('.card-body').css({
                'border-top': '2px solid #FE6524',
                'border-right': '2px solid #FE6524',
                'border-bottom': '5px solid #FE6524',
                'border-left': '2px solid #FE6524',
                'border-radius': '8px',
            });
            $('#DineInCard').find('.card-title').css({
                'color': ' #FE6524',
            });
            $('#DineInCard').find('img').attr('src', '{{ asset('assets/admin/img/icons/categories/dine_in.png') }}');
        }

        function showDataTake(category) {
            resetCardStyles();
            $('#takeAwayData').html(`

            <div class="p-2 p-sm-4">
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="d-flex flex-column">
                            <label for="branch"
                            class="font-weight-semibold fz-12 text-dark">{{ translate('select_branch') }}</label>
                            <select onchange="store_key('branch_id',this.value)" id='branch' name="branch_id"
                                class="js-select2-custom-x form-ellipsis form-control ">
                                <option disabled selected>{{ translate('select_branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"
                                        {{ session()->get('branch_id') == $branch['id'] ? 'selected' : '' }}>
                                        {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class='w-100' id="cart">

                    @include('admin-views.pos._cart')
                </div>
            </div>


            `);
             {{--  updateCardStyles('takeAwayCard', '#', '{{ asset('assets/admin/img/icons/categories/take_away.png') }}');  --}}
            $('#dineInData').html('');
            $('#homeDeliveryData').html('');
            $('#takeAwayCard').find('.card-body').css({
                'border-top': '2px solid #FE6524',
                'border-right': '2px solid #FE6524',
                'border-bottom': '5px solid #FE6524',
                'border-left': '2px solid #FE6524',
                'border-radius': '8px',
            });
            $('#takeAwayCard').find('.card-title').css({
                'color': ' #FE6524',
            });
            $('#takeAwayCard').find('img').attr('src', '{{ asset('assets/admin/img/icons/categories/take_away.png') }}');
        }

        function showDataDelivery(category) {
            resetCardStyles();
            $('#homeDeliveryData').html(`

            <div class="p-2 p-sm-4">
                <div class="form-group row">
                     <div class="col-md-12">
                        <div class="form-group d-flex gap-2">
                                            <select onchange="store_key('customer_id',this.value)" id='customer'
                                                name="customer_id" data-placeholder="{{ translate('Walk_In_Customer') }}"
                                                class="js-data-example-ajax form-control form-ellipsis">
                                                <option disabled selected>{{ translate('select Customer') }}</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer['id'] }}"
                                                        {{ session()->get('customer_id') == $customer['id'] ? 'selected' : '' }}>
                                                        {{ $customer['f_name'] . ' ' . $customer['l_name'] }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-warning rounded text-nowrap" id="add_new_customer"
                                                type="button" data-toggle="modal" data-target="#add-customer"
                                                title="Add Customer">
                                                <i class="tio-add"></i>

                                            </button>
                                        </div>



                        <div class='w-100' id="cart">

                            @include('admin-views.pos._cart')
                        </div>
                    </div>
                </div>
            </div>
            `);
            {{--  updateCardStyles('homeDeliveryCard', '#', '{{ asset('assets/admin/img/icons/categories/home_delivery.png') }}');  --}}
            $('#dineInData').html('');
            $('#takeAwayData').html('');
            $('#homeDeliveryCard').find('.card-body').css({
                'border-top': '2px solid #FE6524',
                'border-right': '2px solid #FE6524',
                'border-bottom': '5px solid #FE6524',
                'border-left': '2px solid #FE6524',
                'border-radius': '8px',
            });
            $('#homeDeliveryCard').find('.card-title').css({
                'color': ' #FE6524',
            });
            $('#homeDeliveryCard').find('img').attr('src', '{{ asset('assets/admin/img/icons/categories/home_delivery.png') }}');

        }
        function resetCardStyles() {
            $('.card-pos .card-body').css({
                'border': 'dashed',
                'border-radius': '0',
            });

            $('.card-pos .card-title').css({
                'color': '',
            });

            $('#DineInCard img').attr('src', '{{ asset('assets/admin/img/icons/categories/dine_in_grey.png') }}');
            $('#takeAwayCard img').attr('src', '{{ asset('assets/admin/img/icons/categories/take_away_grey.png') }}');
            $('#homeDeliveryCard img').attr('src', '{{ asset('assets/admin/img/icons/categories/home_delivery_grey.png') }}');
        }

        function updateCardStyles(cardId, borderColor, imagePath) {
            $(`#${cardId} .card-body`).css({
                'border': `2px solid ${borderColor}`,
                'border-radius': '8px',
            });

            $(`#${cardId} .card-title`).css({
                'color': borderColor,
            });

            $(`#${cardId} img`).attr('src', imagePath);
        }
    </script>
    <script>
        $(document).on('ready', function() {
            @if ($order)
                $('#print-invoice').modal('show');
            @endif
        });

        function printDiv(divName) {

            if ($('html').attr('dir') === 'rtl') {
                $('html').attr('dir', 'ltr')
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                $('#printableAreaContent').attr('dir', 'rtl')
                window.print();
                document.body.innerHTML = originalContents;
                $('html').attr('dir', 'rtl')
                location.reload();
            } else {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                location.reload();
            }

        }
        function set_category_filter(id) {
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('category_id', id);
            nurl.searchParams.set('keyword', keyword);

            $.ajax({
                type: 'GET',
                url: '{{ route("admin.pos.get_ajax_products") }}',
                data: { category_id: id, keyword: keyword },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#items').html(data);

                    $('.category_card').removeAttr('style');


                    $('.category_card[data-category-id="' + id + '"]').css('border', '1px solid #FE6524');

                },
                error: function (error) {
                    console.error('Error:', error);
                },
                complete: function () {
                    $('#loading').hide();
                }
            });

            history.pushState(null, '', nurl);
        }

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('keyword', keyword);

            $.ajax({
                type: 'GET',
                url: '{{ route("admin.pos.get_ajax_products") }}',
                data: { keyword: keyword },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#items').html(data);
                },
                error: function (error) {
                    console.error('Error:', error);
                },
                complete: function () {
                    $('#loading').hide();
                }
            });

            history.pushState(null, '', nurl);
        });


        function addon_quantity_input_toggle(e) {
            var cb = $(e.target);
            if (cb.is(":checked")) {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'visible'
                });
            } else {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'hidden'
                });
            }
        }

        function quickView(product_id) {
            $.ajax({
                url: '{{ route('admin.pos.quick-view') }}',
                type: 'GET',
                data: {
                    product_id: product_id
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log("success...");
                    console.log(data);
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }
        function checkAddToCartValidity() {
            return true;
        }
        function cartQuantityInitialize() {
            $('.btn-number').click(function(e) {
                e.preventDefault();

                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }
                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });
            $('.input-number').change(function() {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ translate('Cart') }}',
                        text: '{{ translate('Sorry, the minimum value was reached') }}'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ translate('Cart') }}',
                        confirmButtonText: '{{ translate('Ok') }}',
                        text: '{{ translate('Sorry, stock limit exceeded') }}.'
                    });
                    $(this).val($(this).data('oldValue'));
                }
            });
            $(".input-number").keydown(function(e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }
        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.pos.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function(data) {
                        if (data.error == 'quantity_error') {
                            toastr.error(data.message);
                        } else {
                            $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                            $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                        }
                    }
                });
            }
        }
        function addToCart(form_id = 'add-to-cart-form') {
            if (checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('admin.pos.add-to-cart') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        console.log(data)
                        if (data.data == 1) {
                            Swal.fire({
                                confirmButtonColor: '#FC6A57',
                                icon: 'info',
                                title: '{{ translate('Cart') }}',
                                confirmButtonText: '{{ translate('Ok') }}',
                                text: "{{ translate('Product already added in cart') }}"
                            });
                            return false;
                        } else if (data.data == 0) {
                            Swal.fire({
                                confirmButtonColor: '#FC6A57',
                                icon: 'error',
                                title: '{{ translate('Cart') }}',
                                confirmButtonText: '{{ translate('Ok') }}',
                                text: '{{ translate('Sorry, product out of stock') }}.'
                            });
                            return false;
                        } else if (data.data == 'variation_error') {
                            Swal.fire({
                                confirmButtonColor: '#FC6A57',
                                icon: 'error',
                                title: 'Cart',
                                text: data.message
                            });
                            return false;
                        }
                        $('.call-when-done').click();

                        toastr.success('{{ translate('Item has been added in your cart') }}!', {
                            CloseButton: true,
                            ProgressBar: true
                        });

                        updateCart();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            } else {
                Swal.fire({
                    confirmButtonColor: '#FC6A57',
                    type: 'info',
                    title: '{{ translate('Cart') }}',
                    confirmButtonText: '{{ translate('Ok') }}',
                    text: '{{ translate('Please choose all the options') }}'
                });
            }
        }

        function removeFromCart(key) {
            $.post('{{ route('admin.pos.remove-from-cart') }}', {
                _token: '{{ csrf_token() }}',
                key: key
            }, function(data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    updateCart();
                    toastr.info('{{ translate('Item has been removed from cart') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }

            });
        }

        function emptyCart() {
            $.post('{{ route('admin.pos.emptyCart') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                updateCart();
                toastr.info('{{ translate('Item has been removed from cart') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                location.reload();
            });
        }

        function updateCart() {
            $.post('<?php echo e(route('admin.pos.cart_items')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                $('#cart').empty().html(data);
            });
        }

        $(function() {
            $(document).on('click', 'input[type=number]', function() {
                this.select();
            });
        });


        function increaseQuantity(event) {
    updateQuantity(event,1);
}
function decreaseQuantity() {
    updateQuantity(event,-1);
}
        function updateQuantity(event,change) {
            event.preventDefault();
            var element = $('.qty');
            var minValue = parseInt(element.attr('min'));
            var valueCurrent = parseInt(element.val())+change;

            var key = element.data('key');
            if (valueCurrent >= minValue) {
                $.post('{{ route('admin.pos.updateQuantity') }}', {
                    _token: '{{ csrf_token() }}',
                    key: key,
                    quantity: valueCurrent
                }, function(data) {
                    updateCart();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ translate('Cart') }}',
                    confirmButtonText: '{{ translate('Ok') }}',
                    text: '{{ translate('Sorry, the minimum value was reached') }}'
                });
                element.val(element.data('oldValue'));
            }

            if (e.type == 'keydown') {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            }
        };

        $('.branch-data-selector').select2();
        $('.table-data-selector').select2();

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{ route('admin.pos.customers') }}',
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#order_place').submit(function(eventObj) {
            if ($('#customer').val()) {
                $(this).append('<input type="hidden" name="user_id" value="' + $('#customer').val() + '" /> ');
            }
            return true;
        });

        function store_key(key, value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            $.post({
                url: '{{ route('admin.pos.store-keys') }}',
                data: {
                    key: key,
                    value: value,
                },
                success: function(data) {
                    console.log(data);
                    var selected_field_text = key;
                    var selected_field = selected_field_text.replace("_", " ");
                    var selected_field = selected_field.replace("id", " ");
                    var message = selected_field + ' ' + 'selected!';
                    var new_message = message.charAt(0).toUpperCase() + message.slice(1);
                    toastr.success((new_message), {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    if (data === 'table_id') {
                        $('#pay_after_eating_li').css('display', 'block')
                    }
                },

            });
        };

        $(document).ready(function() {
            $('#branch').on('change', function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ url('admin/pos/session-destroy') }}",
                    success: function() {
                        location.reload();
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('ready', function() {
            $('.js-select2-custom-x').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset('assets/admin') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>

@endpush


@extends('layouts.admin.app')
@section('title', translate('Add new branch'))

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
    </style>
@endpush

@section('content')
<div class="ml-5">
@include('admin-views.branch._branch-setup-inline-menu')
    </div>
    <hr>
    <div class="content container-fluid">
        <div class="row g-2">
            <div class="col-12">
                <form action="{{route('admin.branch.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">
                                <div class="d-flex flex-column gap-2 align-items-center mb-2">
                                    <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                                        <span class="page-header-title">
                                            {{translate('Add_New_Branch')}}
                                        </span>
                                    </h2>

                                    <h4 class="text-muted">
                                        {{translate('branch_Information')}}
                                    </h4>
                                </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{translate('name')}}</label>
                                        <input value="{{old('name')}}" type="text" name="name" class="form-control" maxlength="255"
                                               placeholder="{{translate('New branch')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" for="">{{translate('address')}}</label>
                                        <input value="{{old('address')}}" type="text" name="address" class="form-control" placeholder="" required>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="from_part_2 mb-4">
                                            <label class="input-label">{{ translate('Image') }}
                                            <small class="text-danger">* ( {{ translate('ratio') }} 1:1 )</small></label>
                                            <div class="custom-file">
                                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required
                                                    oninvalid="document.getElementById('en-link').click()">
                                                <label class="custom-file-label" for="customFileEg1">{{ translate('choose file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('phone')}}</label>
                                        <input value="{{old('phone')}}" type="tel" name="phone" class="form-control"
                                               placeholder="{{translate('Ex: +098538534')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('email')}}</label>
                                        <input value="{{old('email')}}" type="email" name="email" class="form-control" maxlength="255"
                                               placeholder="{{translate('EX : example@example.com')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                <div class="from_part_2 px-4">
                                            <div class="form-group">
                                                <div class="text-center">
                                                <img width="115" id="viewer"
                                                        src="{{ asset('assets/admin/img/400x400/img2.jpg') }}" alt="image" />
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                                <div class="row mt-3">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="input-label">{{translate('password')}}</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" name="password" class="js-toggle-password form-control form-control input-field" id="password" required
                                                    placeholder="{{translate('Ex: 8+ Characters')}}"
                                                    data-hs-toggle-password-options='{
                                                    "target": "#changePassTarget",
                                                    "defaultClass": "tio-hidden-outlined",
                                                    "showClass": "tio-visible-outlined",
                                                    "classChangeTarget": "#changePassIcon"
                                                    }'>
                                                <div id="changePassTarget" class="input-group-append">
                                                    <a class="input-group-text" href="javascript:">
                                                        <i id="changePassIcon" class="tio-visible-outlined"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 ">
                                                <div class="from_part_2 mb-4 ">
                                                    <label class="input-label">{{ translate('banner image') }}
                                                    <small class="text-danger">* ( {{ translate('ratio') }} 8:1 )</small></label>
                                                    <div class="custom-file">
                                                        <input type="file" name="cover_image" id="customFileEg2" class="custom-file-input"
                                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required
                                                            oninvalid="document.getElementById('en-link').click()">
                                                        <label class="custom-file-label" for="customFileEg2">{{ translate('choose file') }}</label>
                                                    </div>
                                                </div>
                                    </div>
                                    <div class="col-lg-4 ">
                                                <div class="from_part_2 px-4">
                                                    <div class="form-group">
                                                        <div class="text-center">
                                                        <img width="300" id="viewer2"
                                                        src="{{ asset('assets/admin/img/900x400/img1.jpg') }}" alt="image" />
                                                        </div>
                                                    </div>
                                                </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                    <div class="card my-3">
                        <div class="card-header">
                                <div class="d-flex flex-column gap-2 align-items-center mb-2">
                                    <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                                        <span class="page-header-title">
                                        {{translate('branch_Location')}}
                                        </span>
                                    </h2>

                                    <h5 class="text-muted mb-0 d-flex align-items-center gap-2">
                                    {{translate('Add_your_branch_location')}}
                                    </h5>
                                </div>
                        </div>
                    </div>
                    <div class="card my-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label class="form-label text-capitalize"
                                                       for="latitude">{{ translate('latitude') }}
                                                        <i class="tio-info-outined"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ translate('Click on the map select your default location.') }}"></i><span
                                                        class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                        data-original-title="{{ translate('click_on_the_map_select_your_default_location') }}"></span></label>
                                                <input type="number" step="any" id="latitude" name="latitude" class="form-control"
                                                       placeholder="{{ translate('Ex:') }} 23.8118428"
                                                       value="{{ old('latitude') }}" required >
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label class="form-label text-capitalize"
                                                       for="longitude">
                                                        {{ translate('longitude') }}
                                                        <i class="tio-info-outined"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ translate('Click on the map select your default location.') }}"></i>
                                                       <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                        data-original-title="{{ translate('click_on_the_map_select_your_default_location') }}">
                                                       </span>
                                                </label>
                                                <input type="number" step="any" name="longitude" class="form-control"
                                                       placeholder="{{ translate('Ex:') }} 90.356331" id="longitude"
                                                       value="{{ old('longitude') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label class="input-label">
                                                    {{translate('coverage (km)')}}
                                                    <i class="tio-info-outined"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="{{ translate('This value is the radius from your restaurant location, and customer can order food inside  the circle calculated by this radius.') }}"></i>
                                                </label>
                                                <input type="number" name="coverage" min="1" max="1000" class="form-control" placeholder="{{ translate('Ex : 3') }}" value="{{ old('coverage') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" id="location_map_div">
                                    <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                           data-placement="right"
                                           data-original-title="{{ translate('search_your_location_here') }}"
                                           type="text" placeholder="{{ translate('search_here') }}" />
                                    <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 76%"></div>

                                    <div style="gap:2.25rem;"  class=" d-flex justify-content-center mt-4">
                                        <button type="reset" class="btn btn-white text-order_id border-primary">{{translate('reset')}}</button>
                                        <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
@push('script_2')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $mapApiClientKey }}&libraries=places&v=3.51"></script>
    <script>
    function readURL(input, viewerId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#' + viewerId).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function () {
        readURL(this, 'viewer');
    });

    $("#customFileEg2").change(function () {
        readURL(this, 'viewer2');
    });

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


        $('.__right-eye').on('click', function(){
            if($(this).hasClass('active')) {
                $(this).removeClass('active')
                $(this).find('i').removeClass('tio-invisible')
                $(this).find('i').addClass('tio-hidden-outlined')
                $(this).siblings('input').attr('type', 'password')
            }else {
                $(this).addClass('active')
                $(this).siblings('input').attr('type', 'text')


                $(this).find('i').addClass('tio-invisible')
                $(this).find('i').removeClass('tio-hidden-outlined')
            }
        })
    </script>

@endpush

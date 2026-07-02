@extends('layouts.admin.app')
@section('title', translate('Home'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/css/home.owl.carousel.css">
    <style>
        {
                {
                -- -------Div #1 --
            }
        }

        .embed-responsive-16by9 {
            padding-bottom: 50.25%;
            position: relative;
            height: 0;
        }

        .embed-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 15px;
        }
    </style>
@endpush

@section('content')
<div class="container mt-5 ">
    {{-- ---------------Div One1------------ --}}
    <div class="row mb-3 mx-4">
        <div class="col-md-6">
            <div class="mt-4">
                <img width="140" class="mb-3"
                    onerror="this.src='http://127.0.0.1:8000/public/assets/admin/img/160x160/img2.jpg'"
                    src="{{asset('assets/admin/img/logo1.png')}}" alt="Logo">
                <h1 class="mt-3"> Welcome Note </h1>
                <p class="home--p">A comprehensive Online Ordering <br> and first managment
                    system with your own <br> branded website</p>
            </div>
            <hr class="home--hr">
        </div>
        <div class="col-md-6">
            <div class="embed-responsive embed-responsive-16by9 rounded">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Sikhmw_U214"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>
    {{-- ---------------Div Two2------------ --}}
    <div class="row mb-3 mx-4">
        <div class="mt-3 col-md-6 my-3">
            <h2>Getting Started</h2>
            <p class="home--p">Click on the following Options to get yourself started.</p>
        </div>
        <div class="mt-3 col-lg-6 col-md-6 col-sm-6 my-3 home--btn">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#feedbackModal">
                <img src="{{ asset('assets/admin/img/icons/h1comment.png') }}" alt=""> Give us a Feedback
            </button>
        </div>
    </div>
    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('admin.feedback')}}">
                        @csrf
                        <div class="form-group">
                            <label for="feedback">Your Feedback</label>
                            <textarea name="feedback" class="form-control" id="feedback" rows="3"
                                placeholder="write here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- ---------------Div Three3-----Start------- --}}
    <div class="row mb-4 mx-4">
        <div class="owl-carousel owl-theme">
            <div class="item">
                <div class="home-- card" style="border-color:#ff6767;">
                    <div class="col-lg-4 col-md-6 col-sm-8 mb-3 homeD3">
                        <!-- Card 1 Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" title="{{ translate('Dashboards') }}">
                            <div class="home--card-body">

                                <div class="home--card-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="24"
                                        height="24">
                                        <rect fill="#FE6524" x="0" y="0" width="30" height="90" rx="5" ry="5" />
                                        <rect fill="#FE6524" x="40" y="0" width="50" height="40" rx="5" ry="5" />
                                        <rect fill="#FE6524" x="40" y="50" width="50" height="40" rx="5" ry="5" />
                                    </svg>
                                    <h3 class="home--card-title">Dashboard</h3>
                                    <h5 class="home--card-subtitle">View Order Matrix And Graphs</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="col-lg-4 col-md-6 col-sm-8 mb-3 homeD3">
                        <a href="{{ route('admin.orders.list', ['all']) }}" title="{{ translate('Orders') }}">
                            <div class="home--card-body">

                                <div class="home--card-text">
                                    <img class="home--card-img"
                                        src="{{ asset('assets/admin/img/icons/h3liveorders.png') }}" alt="">
                                    <h3 class="home--card-title">Live Orders</h3>
                                    <h5 class="home--card-subtitle">View Your Orders</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="col-lg-4 col-md-6 col-sm-8  mb-3 homeD3">
                        <a href="{{ route('admin.product.list') }}" title="{{ translate('product_list') }}">
                            <div class="home--card-body">

                                <div class="home--card-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="24" height="24"
                                        fill="#FE6524">
                                        <path
                                            d="M70,0H20C14.5,0,10,4.5,10,10v80c0,5.5,4.5,10,10,10h70c5.5,0,10-4.5,10-10V30L70,0z M80,25h-5.5c-2.5,0-4.5-2-4.5-4.5V10l10,10V25z M60,50H30v-5h30V50z M60,65H30v-5h30V65z M60,80H30v-5h30V80z M80,90H20V10h40L80,30V90z" />
                                        <rect x="35" y="45" width="5" height="5" />
                                        <rect x="35" y="60" width="5" height="5" />
                                        <rect x="35" y="75" width="5" height="5" />
                                        <path
                                            d="M80,30L60,50V30H80z M85,75h10v5h-10V75z M85,65h10v5h-10V65z M85,55h10v5h-10V55z M85,45h10v5h-10V45z M85,35h10v5h-10V35z" />
                                        <path d="M70,30v10h10L70,30z" />
                                    </svg>
                                    <h3 class="home--card-title">Product Managment</h3>
                                    <h5 class="home--card-subtitle">View Your Products</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="col-lg-4 col-md-6 col-sm-8  mb-3 homeD3">
                        <a href="{{ route('admin.orders.list', ['all']) }}" title="{{ translate('Orders') }}">
                            <div class="home--card-body">
                                <div class="home--card-text">
                                    <img class="home--card-img" src="{{ asset('assets/admin/img/icons/h5orderm.png') }}"
                                        alt="">
                                    <h3 class="home--card-title">Order Managment</h3>
                                    <h5 class="home--card-subtitle">View Your Orders</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="col-lg-4 col-md-6 col-sm-8  mb-3 homeD3">
                        <a href="{{ route('admin.table.list') }}" title="{{ translate('Table') }}">
                            <div class="home--card-body">
                                <div class="home--card-text">
                                    <img class="home--card-img" src="{{ asset('assets/admin/img/icons/h6table.png') }}"
                                        alt="">
                                    <h3 class="home--card-title">Tables</h3>
                                    <h5 class="home--card-subtitle">View Your Tables</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="col-lg-4 col-md-6 col-sm-8  mb-3 homeD3">
                        <a href="{{ route('admin.banner.list') }}" title="{{ translate('Promotion') }}">
                            <div class="home--card-body">
                                <div class="home--card-text">
                                    <img class="home--card-img"
                                        src="{{ asset('assets/admin/img/icons/h7promotion.png') }}" alt="">
                                    <h3 class="home--card-title">Promotions</h3>
                                    <h5 class="home--card-subtitle">View Your Promotions</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="col-lg-4 col-md-6 col-sm-8  mb-3 homeD3">
                        <a href="{{ route('admin.report.sale-report') }}" title="{{ translate('Sale Report') }}">
                            <div class="home--card-body">
                                <div class="home--card-text">
                                    <img class="home--card-img" src="{{ asset('assets/admin/img/icons/h8report.png') }}"
                                        alt="">
                                    <h3 class="home--card-title">Report & Analytics</h3>
                                    <h5 class="home--card-subtitle">View Your Reports</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card">
                    <div class="col-lg-4 col-md-6 col-sm-8  mb-3 homeD3">
                        <a href="{{ route('admin.customer.list') }}" title="{{ translate('Customer') }}">
                            <div class="home--card-body">
                                <div class="home--card-text">
                                    <img class="home--card-img"
                                        src="{{ asset('assets/admin/img/icons/h9customerm.png') }}" alt="">
                                    <h3 class="home--card-title">customer Management</h3>
                                    <h5 class="home--card-subtitle">View Your Customers</h5>
                                </div>
                                <div class="ml-2">
                                    <i class="fa-solid fa-arrow-right"
                                        style="margin-left: 28px; margin-top:20px;font-size:23px;color:#FE6524;"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection


    @push('script_2')
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="{{ asset('assets/admin') }}/js/vendor.min.js"></script>
        <script src="{{ asset('assets/admin') }}/js/theme.min.js"></script>
        <script src="{{ asset('assets/admin') }}/js/sweet_alert.js"></script>
        <script src="{{ asset('assets/admin') }}/js/toastr.js"></script>
        {{-- {!! Toastr::message() !!} --}}
        {{-- OWl Carousel min.js----------- --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
            integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }

                }
            })
        </script>
    @endpush

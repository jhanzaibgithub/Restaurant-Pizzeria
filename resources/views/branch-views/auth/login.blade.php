<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{ translate('Branch') }} | {{ translate('Login') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/restaurant/'.$restaurantLogo) }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/toastr.css">
    <style>
        .gold-background {
            background-color: #3D3A38;
            padding: 60px;
            text-align: center;
        }

        .full-width-img-cover {
            width: 100%;
            height: 100%;
            object-fit: 100% 100%;
        }

        .form{
            padding: 30px;
            margin: 40px;
        }
        .demo-credentials {
            border: 1px solid rgba(254, 101, 36, .18);
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 18px;
            background: linear-gradient(135deg, rgba(254, 101, 36, .08), rgba(0, 106, 229, .05));
        }
        .demo-credentials__title {
            color: #334257;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .demo-credentials__row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #66758A;
            font-size: 13px;
            line-height: 1.6;
        }
        .demo-credentials__copy {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }

        /* @media (min-width: 768px) and (max-width: 1024px) {
            form {
                padding: 11px;
                margin: 11px;
            }

        } */
        @media (max-width: 991px) {
            .auth-left {
                display: none;
            }

            .row {
                display: block;
            }

            .auth-right-logo {
                display: block;
                width: 70%;
                max-width: 200px;
                margin: 0 auto;
                margin-bottom: 20px;
            }

            .auth-right-logo img {
                width: 100%;
                max-height: 70px;
                -o-object-fit: contain;
                object-fit: contain;
                -o-object-position: center center;
                object-position: center center;
            }

            .auth-right {
                padding: 25px 40px;
                max-width: 100%;
                text-align: center;
            }

            .auth-right .form-group {
                text-align: start;
            }
            .form{
            padding: 0px;
            margin: 0px;
            }


        }


        @media (max-width: 767px) {
            .auth-right {
                padding: 15px 30px;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- ========== MAIN CONTENT ========== -->
    <div class="container-fluid">
        <div class="row mx-5 my-1 p-2">
            <div class="col-md-6 col-sm-6 p-0 m-0 auth-left">
                <div class="card h-100">
                    <div style="border-top-left-radius: 10px; border-top-right-radius: 0px;" class="gold-background">
                        <img width="220" src="{{ asset('assets/admin/img/logo1.png') }}"
                            alt="Logo">
                        <h1 class="text-white mt-5">Welcome to<span class="text-order_id ml-2">Restaurant Pizzeria</span> </h1>
                        <h1 class="text-white"> User Friendly Branch Panel.</h1>
                    </div>
                    <div class="card-body p-0">
                        <img style="border-bottom-left-radius: 10px;"
                            src="{{ asset('assets/admin/img/FeastFlow_signin_Bgsm.png') }}" alt="Image"
                            class="full-width-img-cover">
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-6 col-sm-6 p-0 m-0 auth-right">
                <div style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;" class="card h-100">
                    <div class="card-body">
                        <!-- Form -->
                        <form id="form-id" class="form" action="{{ route('branch.auth.login') }}" method="post">
                            @csrf

                            <div class="auth-header">
                                <div class="mb-5 text-center">
                                    <h1>{{ translate('sign_in') }}/{{ translate('log_in') }}</h1>

                                </div>
                            </div>

                            <!-- Form Group -->
                            <div class="js-form-message form-group">
                                <label class="input-label text-capitalize text-muted" for="signinSrEmail">
                                    {{ translate('email') }} {{ translate('Address') }}</label>

                                <input type="email" class="form-control form-control-lg" name="email"
                                    id="signinSrEmail" tabindex="1"
                                    placeholder="{{ translate('email@address.com') }}" aria-label="email@address.com"
                                    required data-msg="Please enter a valid email address.">
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="js-form-message form-group">
                                <label class="input-label text-muted" for="signupSrPassword" tabindex="0">
                                    <span class="d-flex justify-content-between align-items-center">
                                        {{ translate('password') }}
                                    </span>
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control form-control-lg"
                                        name="password" id="signupSrPassword"
                                        placeholder="{{ translate('8+ characters required') }}"
                                        aria-label="8+ characters required" required
                                        data-msg="Your password is invalid. Please try again."
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
                            <!-- End Form Group -->

                            <!-- Checkbox -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                        name="remember">
                                    <label class="custom-control-label text-muted" for="termsCheckbox">
                                        {{ translate('remember') }} {{ translate('me') }}
                                    </label>
                                </div>
                            </div>
                            <!-- End Checkbox -->
                            <div class="d-flex justify-content-center mb-3">
                                <button type="submit" class="btn btn-primary ">{{ translate('sign_in') }} <i
                                        class="tio-arrow-forward"></i></button>
                            </div>
                            <div class="d-flex justify-content-center ">
                                <p>
                                    <a href="{{ route('admin.auth.login') }}">
                                        {{ translate('admin_login') }}
                                    </a>
                                </p>
                            </div>

                            <div class="demo-credentials">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="demo-credentials__title">Demo Branch Credentials</div>
                                        <div class="demo-credentials__row">
                                            <span>Email</span>
                                            <strong>mainb@mainb.com</strong>
                                        </div>
                                        <div class="demo-credentials__row">
                                            <span>Password</span>
                                            <strong>12345678</strong>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary demo-credentials__copy" onclick="copy_cred()" title="Use credentials">
                                        <i class="tio-copy"></i>
                                    </button>
                                </div>
                            </div>

                        </form>
                        <!-- End Form -->

                        <!-- <div class="mt-2">
                            <p class="mb-0 text-capitalize">{{ translate('powered_by') }}
                                <a href="https://dcodax.com/">{{ translate('Dcodax') }}</a>
                            </p>
                        </div> -->
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <!-- End Content -->
        </div>
    </div>
    <!-- ========== END MAIN CONTENT ========== -->


    <!-- JS Implementing Plugins -->
    <script src="{{ asset('assets/admin') }}/js/vendor.min.js"></script>

    <!-- JS Front -->
    <script src="{{ asset('assets/admin') }}/js/theme.min.js"></script>
    <script src="{{ asset('assets/admin') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}

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

    <!-- JS Plugins Init. -->
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF SHOW PASSWORD
            // =======================================================
            $('.js-toggle-password').each(function() {
                new HSTogglePassword(this).init()
            });

            // INITIALIZATION OF FORM VALIDATION
            // =======================================================
            $('.js-validate').each(function() {
                $.HSCore.components.HSValidation.init($(this));
            });
        });
    </script>

    {{-- recaptcha scripts start --}}
    @if (isset($recaptcha) && $recaptcha['status'] == 1)
        <script type="text/javascript">
            var onloadCallback = function() {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ $recaptcha['site_key'] }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script>
            $("#form-id").on('submit', function(e) {
                var response = grecaptcha.getResponse();

                if (response.length === 0) {
                    e.preventDefault();
                    toastr.error("{{ translate('Please check the recaptcha') }}");
                }
            });
        </script>
    @else
        <script type="text/javascript">
            function re_captcha() {
                $url = "{{ URL('/branch/auth/code/captcha') }}";
                $url = $url + "/" + Math.random();
                document.getElementById('default_recaptcha_id').src = $url;
                console.log('url: ' + $url);
            }
        </script>
    @endif
    {{-- recaptcha scripts end --}}

    <script>
        function copy_cred() {
            $('#signinSrEmail').val('mainb@mainb.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Credentials filled successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset('assets/admin') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
</body>

</html>

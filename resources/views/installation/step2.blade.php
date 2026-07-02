@extends('layouts.blank')

@section('title', 'Environment - Restaurant Pizzeria Installer')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>Environment Setup</h2>
        <h6 class="fw-normal">These values are written safely to your .env file.</h6>
    </div>

    @include('installation.partials.progress', ['active' => 3])

    <div class="card mt-4">
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('install.environment.save') }}" enctype="multipart/form-data">
                @csrf
                <div class="bg-light p-4 rounded mb-4">
                    <div class="row gy-4">
                        <div class="col-md-6"><label class="form-label">App Name</label><input class="form-control" name="APP_NAME" value="{{ old('APP_NAME', $environment['APP_NAME']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">App URL</label><input class="form-control" name="APP_URL" value="{{ old('APP_URL', $environment['APP_URL']) }}" required></div>
                        <div class="col-md-6">
                            <label class="form-label">Debug Mode</label>
                            <select class="form-select" name="APP_DEBUG" required>
                                <option value="false" @selected(old('APP_DEBUG', $environment['APP_DEBUG']) === 'false')>Disabled</option>
                                <option value="true" @selected(old('APP_DEBUG', $environment['APP_DEBUG']) === 'true')>Enabled</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Database Host</label><input class="form-control" name="DB_HOST" value="{{ old('DB_HOST', $environment['DB_HOST']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Database Port</label><input class="form-control" name="DB_PORT" value="{{ old('DB_PORT', $environment['DB_PORT']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Database Name</label><input class="form-control" name="DB_DATABASE" value="{{ old('DB_DATABASE', $environment['DB_DATABASE']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Database Username</label><input class="form-control" name="DB_USERNAME" value="{{ old('DB_USERNAME', $environment['DB_USERNAME']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Database Password</label><input type="password" class="form-control" name="DB_PASSWORD" value="{{ old('DB_PASSWORD', $environment['DB_PASSWORD']) }}"></div>
                        <div class="col-md-6"><label class="form-label">Copyright Text</label><input class="form-control" name="footer_text" value="{{ old('footer_text', session('installer.business_settings.footer_text')) }}" placeholder="Ex: Copyright@restaurant-pizzeria.com"></div>
                        <div class="col-md-6"><label class="form-label">Minimum Order Value</label><input type="number" min="0" step="any" class="form-control" name="minimum_order_value" value="{{ old('minimum_order_value', session('installer.business_settings.minimum_order_value', 0)) }}"></div>

                        @php($currentLogo = session('installer.business_settings.logo'))
                        <div class="col-md-6">
                            <label class="form-label d-block">Restaurant Logo <small class="text-muted">(ratio 3:1)</small></label>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            @if ($currentLogo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/restaurant/' . $currentLogo) }}" alt="Logo" style="height:70px;border:1px solid #ddd;border-radius:6px;">
                                    <small class="d-block text-muted">Current logo uploaded. Choose a file to replace it.</small>
                                </div>
                            @endif
                        </div>

                        @php($currentFavIcon = session('installer.business_settings.fav_icon'))
                        <div class="col-md-6">
                            <label class="form-label d-block">Favicon <small class="text-muted">(ratio 1:1)</small></label>
                            <input type="file" class="form-control" name="fav_icon" accept="image/*">
                            @if ($currentFavIcon)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/restaurant/' . $currentFavIcon) }}" alt="Favicon" style="height:50px;width:50px;object-fit:contain;border:1px solid #ddd;border-radius:6px;">
                                    <small class="d-block text-muted">Current favicon uploaded. Choose a file to replace it.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-dark px-sm-5">Save Environment</button>
                </div>
            </form>
        </div>
    </div>
@endsection

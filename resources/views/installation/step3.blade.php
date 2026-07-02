@extends('layouts.blank')

@section('title', 'Database - Restaurant Pizzeria Installer')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>Database Connection Test</h2>
        <h6 class="fw-normal">The installer will not proceed until the database accepts these credentials.</h6>
    </div>

    @include('installation.partials.progress', ['active' => 4])

    <div class="card mt-4">
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('install.database.test') }}">
                @csrf
                <div class="bg-light p-4 rounded mb-4">
                    <div class="row gy-4">
                        <div class="col-md-6"><label class="form-label">Host</label><input class="form-control" name="DB_HOST" value="{{ old('DB_HOST', $environment['DB_HOST']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Port</label><input class="form-control" name="DB_PORT" value="{{ old('DB_PORT', $environment['DB_PORT']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Database</label><input class="form-control" name="DB_DATABASE" value="{{ old('DB_DATABASE', $environment['DB_DATABASE']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Username</label><input class="form-control" name="DB_USERNAME" value="{{ old('DB_USERNAME', $environment['DB_USERNAME']) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Password</label><input type="password" class="form-control" name="DB_PASSWORD" value="{{ old('DB_PASSWORD', $environment['DB_PASSWORD']) }}"></div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-dark px-sm-5">Test Connection</button>
                </div>
            </form>
        </div>
    </div>
@endsection

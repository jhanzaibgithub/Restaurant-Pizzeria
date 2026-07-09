@extends('layouts.blank')

@section('title', 'Admin - Restaurant Pizzeria Installer')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>Admin Account Creation</h2>
        <h6 class="fw-normal">Create the first master administrator for the admin panel.</h6>
    </div>

    @include('installation.partials.progress', ['active' => 6])

    <div class="card mt-4">
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('install.admin.save') }}">
                @csrf
                <div class="bg-light p-4 rounded mb-4">
                    <div class="row gy-4">
                        <div class="col-md-12"><label class="form-label">Business Name</label><input class="form-control" name="app_name" value="{{ old('app_name', $appName) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Admin Name</label><input class="form-control" name="name" value="{{ old('name', $seededAdmin['name'] ?? '') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ old('email', $seededAdmin['email'] ?? '') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="{{ old('phone', $seededAdmin['phone'] ?? '') }}"></div>
                        @if ($seededAdmin)
                            <div class="col-12"><small class="text-muted">A default admin was seeded from the database. Review the details above and enter a password only if you want to change it.</small></div>
                        @endif
                        <div class="col-md-6"><label class="form-label">Password</label><input type="password"  class="form-control" name="password" minlength="8" autocomplete="new-password" placeholder="Leave blank to keep current password"></div>
                        <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password"  class="form-control" name="password_confirmation" minlength="8" autocomplete="new-password"></div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-dark px-sm-5">Create Admin</button>
                </div>
            </form>
        </div>
    </div>
@endsection

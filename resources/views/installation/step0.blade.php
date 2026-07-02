@extends('layouts.blank')

@section('title', 'Welcome - Restaurant Pizzeria Installer')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>Restaurant Pizzeria Installation</h2>
        <h6 class="fw-normal">A guided setup for shared hosting, cPanel, and production servers.</h6>
    </div>

    @include('installation.partials.progress', ['active' => 1])

    <div class="card mt-4">
        <div class="p-4 my-md-3 mx-xl-4 px-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/admin/img/logo1.png') }}" alt="Restaurant Pizzeria" style="max-height: 64px">
                <h4 class="mt-4 mb-2">{{ $appName }}</h4>
                <p class="text-muted mb-0">This wizard verifies the server, writes environment settings, prepares the database, creates the administrator account, and locks the installer.</p>
            </div>

            <div class="bg-light p-4 rounded mb-4">
                <div class="row g-3">
                    <div class="col-md-4"><strong>Laravel</strong><br>{{ app()->version() }}</div>
                    <div class="col-md-4"><strong>PHP</strong><br>{{ $phpVersion }}</div>
                    <div class="col-md-4"><strong>Server Time</strong><br>{{ now()->toDateTimeString() }}</div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('install.requirements') }}" class="btn btn-dark px-sm-5">Start Installation</a>
            </div>
        </div>
    </div>
@endsection

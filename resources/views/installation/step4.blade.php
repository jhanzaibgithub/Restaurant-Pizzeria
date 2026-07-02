@extends('layouts.blank')

@section('title', 'Migration - Restaurant Pizzeria Installer')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>Migration & Seeder Runner</h2>
        <h6 class="fw-normal">This runs php artisan migrate --force and php artisan db:seed --force.</h6>
    </div>

    @include('installation.partials.progress', ['active' => 5])

    <div class="card mt-4">
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            @if($result)
                <div class="alert {{ $result['ok'] ? 'alert-success' : 'alert-danger' }}">{{ $result['message'] }}</div>
                @if(!empty($result['output']))
                    <pre class="bg-light p-3 rounded small" style="max-height: 260px; overflow:auto">{{ $result['output'] }}</pre>
                @endif
            @endif
            @if(!empty($prepared['ok']))
                <div class="alert alert-success">
                    {{ $prepared['message'] }}
                </div>
            @endif

            <div class="bg-light p-4 rounded mb-4">
                @if(!empty($prepared['ok']))
                    <p class="mb-0">The selected database is already prepared. Continue to create or update the admin account.</p>
                @else
                    <p class="mb-0">The database connection is ready. Start migration and seeding to prepare the application tables and baseline data.</p>
                @endif
            </div>

            <div class="text-center">
                @if(!empty($prepared['ok']))
                    <a href="{{ route('install.admin') }}" class="btn btn-dark px-sm-5">Continue to Admin Setup</a>
                @else
                    <form method="POST" action="{{ route('install.migrations.run') }}">
                        @csrf
                        <button type="submit" class="btn btn-dark px-sm-5" @disabled(! $ready)>Run Migration & Seeder</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

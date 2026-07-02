@extends('layouts.blank')

@section('title', 'Requirements - Restaurant Pizzeria Installer')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>Server Requirement Checker</h2>
        <h6 class="fw-normal">Resolve every failed item before continuing.</h6>
    </div>

    @include('installation.partials.progress', ['active' => 2])

    <div class="card mt-4">
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            <div class="bg-light p-4 rounded mb-4">
                <div class="row gy-3">
                    @foreach($items as $item)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-start gap-3 p-3 bg-white rounded">
                                <div>
                                    <strong>{{ $item['label'] }}</strong>
                                    <div class="small text-muted text-break">{{ $item['value'] }}</div>
                                </div>
                                <span class="badge {{ $item['ok'] ? 'bg-success' : 'bg-danger' }}">{{ $item['ok'] ? 'OK' : 'Fix' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                @if($passes)
                    <a href="{{ route('install.environment') }}" class="btn btn-dark px-sm-5">Continue</a>
                @else
                    <a href="{{ route('install.requirements') }}" class="btn btn-outline-dark px-sm-5">Recheck</a>
                @endif
            </div>
        </div>
    </div>
@endsection

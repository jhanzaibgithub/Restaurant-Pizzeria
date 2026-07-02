@extends('layouts.admin.app')

@section('title', translate('app_activation'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="container">
        <div class="row pt-5 mt-10">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                @if(session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{session('error')}}
                    </div>
                @endif
                <div class="mar-ver pad-btm text-center mb-4">
                    <h1 class="h3">Just click in "Activate" and that's is</h1>
                </div>
                <div class="text-muted font-13">
                    <form method="POST" action="{{route('admin.app-activate',[$app_id])}}">
                        @csrf

                        <div class="form-group">
                            <input type="text" class="form-control" id="purchase_key"
                                   name="purchase_key" value="GambitSteel" disabled>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info btn">
                                <i class="tio-key"></i>
                                Activate
                            </button>
                        </div>
                    </form>

                    <div class="mar-ver pad-btm text-center mt-5">
                        <p>
                            What!? You don't know me?
                            <a href="https://babia.to/members/gambitsteel.90801/" target="_blank"
                               class="text-info">Visit Profile</a>
                        </p>
                        <p>
                            Thanks to <a href="https://babia.to/members/ncode.163056/" target="_blank"
                               class="text-info">nCode</a> for nulling method
                            
                        </p>                        
                    </div>

                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush

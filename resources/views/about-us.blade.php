@extends('layouts.public-content')

@section('title', 'About Us | Restaurant Pizzeria')

@push('css_or_js')
    <style>
        input {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="content-card">
        {!! $aboutUs !!}
    </div>
@endsection

@push('script')
    <script>
        const editor = document.getElementsByClassName("ql-editor")[0];
        if (editor) {
            editor.contentEditable = "false";
        }
    </script>
@endpush

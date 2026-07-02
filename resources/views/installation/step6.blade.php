@extends('layouts.blank')

@section('title', 'Finish - Restaurant Pizzeria Installer')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>Finalize Installation</h2>
        <h6 class="fw-normal">The installer will create the lock file, enable APP_INSTALL, and send you to admin login.</h6>
    </div>

    @include('installation.partials.progress', ['active' => 7])

    <div class="card mt-4">
        <div class="p-4 my-md-3 mx-xl-4 px-md-5 text-center">
            @if(session('success'))
                <div class="alert alert-success text-start">{{ session('success') }}</div>
            @endif
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger text-start">{{ $errors->first() }}</div>
            @endif

            <h4 class="mb-3">Everything is ready.</h4>
            <p class="text-muted">Finalize now to prevent reinstall access and move to the admin login screen.</p>

            <form method="POST" action="{{ route('install.finalize') }}" data-finalize-form>
                @csrf
                <button type="submit" class="btn btn-dark px-sm-5" data-finalize-button>Finalize & Login</button>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const finalizeForm = document.querySelector('[data-finalize-form]');
        const finalizeButton = document.querySelector('[data-finalize-button]');

        finalizeForm?.addEventListener('submit', () => {
            finalizeButton.disabled = true;
            finalizeButton.innerText = 'Finalizing...';
        });
    </script>
@endpush

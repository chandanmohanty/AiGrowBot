@extends('install.layout', ['pageTitle' => 'Requirements'])

@section('install-content')
    <h2>Welcome</h2>
    <p class="install-lead">Before we continue, let's make sure the server meets all requirements.</p>

    @php $allOk = collect($checks)->every(fn ($c) => $c['ok']); @endphp

    <ul class="check-list">
        @foreach ($checks as $c)
            <li class="check {{ $c['ok'] ? 'ok' : 'bad' }}">
                <span class="check-icon">
                    <i class="fa-solid {{ $c['ok'] ? 'fa-check' : 'fa-xmark' }}"></i>
                </span>
                <div class="check-body">
                    <strong>{{ $c['label'] }}</strong>
                    <small>{{ $c['detail'] }}</small>
                </div>
            </li>
        @endforeach
    </ul>

    @if (! $allOk)
        <div class="install-note warn">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Please fix the items marked in red before continuing. Typical fixes:
            install missing PHP extensions via your package manager, and run
            <code>chmod -R ug+rw storage bootstrap/cache .env</code>.
        </div>
    @endif

    <div class="install-actions">
        <span></span>
        <a href="{{ route('install.database') }}" class="btn-next {{ $allOk ? '' : 'is-disabled' }}">
            Continue <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
@endsection

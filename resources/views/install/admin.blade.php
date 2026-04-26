@extends('install.layout', ['pageTitle' => 'Admin account'])

@section('install-content')
    <h2>Create admin account</h2>
    <p class="install-lead">This user will have full access to the admin panel. Keep these credentials safe — you can change the password later.</p>

    <form method="POST" action="{{ route('install.admin.save') }}" class="install-form">
        @csrf

        <div class="field">
            <label>Full name *</label>
            <input type="text" name="name" required maxlength="120" value="{{ old('name', $data['name']) }}">
        </div>

        <div class="field">
            <label>Email *</label>
            <input type="email" name="email" required maxlength="160" value="{{ old('email', $data['email']) }}">
        </div>

        <div class="grid2">
            <div class="field">
                <label>Password *</label>
                <input type="password" name="password" required minlength="8" autocomplete="new-password">
            </div>
            <div class="field">
                <label>Confirm password *</label>
                <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
            </div>
        </div>

        <p class="install-note info">
            <i class="fa-solid fa-shield-halved"></i>
            Use a password with at least 8 characters — a mix of letters, numbers and a symbol is recommended.
        </p>

        <div class="install-actions">
            <a href="{{ route('install.site') }}" class="btn-ghost"><i class="fa-solid fa-arrow-left"></i> Back</a>
            <button type="submit" class="btn-next">Continue <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </form>
@endsection

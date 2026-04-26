@extends('install.layout', ['pageTitle' => 'Install'])

@section('install-content')
    <h2>Ready to install</h2>
    <p class="install-lead">Review the configuration below. Clicking <strong>Run Install</strong> will write <code>.env</code>, migrate the database, seed default data and create your admin account.</p>

    <div class="review-grid">
        <div class="review-card">
            <h3><i class="fa-solid fa-database"></i> Database</h3>
            <dl>
                <dt>Driver</dt><dd>{{ strtoupper($db['connection']) }}</dd>
                <dt>Host</dt><dd>{{ $db['host'] }}:{{ $db['port'] }}</dd>
                <dt>Database</dt><dd>{{ $db['database'] }}</dd>
                <dt>Username</dt><dd>{{ $db['username'] }}</dd>
            </dl>
        </div>

        <div class="review-card">
            <h3><i class="fa-solid fa-globe"></i> Site</h3>
            <dl>
                <dt>Name</dt><dd>{{ $site['site_name'] }}</dd>
                <dt>URL</dt><dd>{{ $site['app_url'] }}</dd>
                <dt>Email</dt><dd>{{ $site['contact_email'] }}</dd>
                <dt>Timezone</dt><dd>{{ $site['timezone'] }}</dd>
            </dl>
        </div>

        <div class="review-card">
            <h3><i class="fa-solid fa-user-shield"></i> Admin</h3>
            <dl>
                <dt>Name</dt><dd>{{ $admin['name'] }}</dd>
                <dt>Email</dt><dd>{{ $admin['email'] }}</dd>
                <dt>Password</dt><dd>••••••••</dd>
            </dl>
        </div>
    </div>

    <div class="install-note warn">
        <i class="fa-solid fa-triangle-exclamation"></i>
        The installer will switch <code>APP_ENV</code> to <strong>production</strong> and <code>APP_DEBUG</code> to <strong>false</strong>. Migrations are destructive on existing data — make sure the target database is empty.
    </div>

    <form method="POST" action="{{ route('install.finalize.run') }}" id="installForm">
        @csrf
        <div class="install-actions">
            <a href="{{ route('install.admin') }}" class="btn-ghost"><i class="fa-solid fa-arrow-left"></i> Back</a>
            <button type="submit" class="btn-next" id="runBtn">
                <i class="fa-solid fa-rocket"></i> Run Install
            </button>
        </div>
    </form>

    <script>
    (function () {
        var form = document.getElementById('installForm');
        var btn  = document.getElementById('runBtn');
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Installing…';
        });
    })();
    </script>
@endsection

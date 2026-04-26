@extends('install.layout', ['pageTitle' => 'Database'])

@section('install-content')
    <h2>Database connection</h2>
    <p class="install-lead">Provide MySQL / MariaDB credentials. The database must already exist and be empty.</p>

    <form method="POST" action="{{ route('install.database.save') }}" id="dbForm" class="install-form">
        @csrf
        <div class="grid2">
            <div class="field">
                <label>Driver *</label>
                <select name="connection">
                    <option value="mysql"   {{ ($data['connection'] ?? 'mysql')  === 'mysql'   ? 'selected' : '' }}>MySQL</option>
                    <option value="mariadb" {{ ($data['connection'] ?? 'mysql')  === 'mariadb' ? 'selected' : '' }}>MariaDB</option>
                </select>
            </div>
            <div class="field">
                <label>Host *</label>
                <input type="text" name="host" value="{{ old('host', $data['host']) }}" required>
            </div>
        </div>

        <div class="grid2">
            <div class="field">
                <label>Port *</label>
                <input type="number" name="port" value="{{ old('port', $data['port']) }}" required>
            </div>
            <div class="field">
                <label>Database *</label>
                <input type="text" name="database" value="{{ old('database', $data['database']) }}" required>
            </div>
        </div>

        <div class="grid2">
            <div class="field">
                <label>Username *</label>
                <input type="text" name="username" value="{{ old('username', $data['username']) }}" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" value="{{ old('password', $data['password']) }}" autocomplete="new-password">
            </div>
        </div>

        <div class="install-note" id="dbTestResult" hidden></div>

        <div class="install-actions">
            <button type="button" id="testDbBtn" class="btn-ghost">
                <i class="fa-solid fa-plug"></i> Test connection
            </button>
            <button type="submit" class="btn-next">
                Save &amp; Continue <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </form>

    <script>
    (function () {
        var form = document.getElementById('dbForm');
        var btn  = document.getElementById('testDbBtn');
        var out  = document.getElementById('dbTestResult');
        if (!btn) return;

        btn.addEventListener('click', async function () {
            out.hidden = false;
            out.className = 'install-note info';
            out.textContent = 'Testing connection…';

            var fd = new FormData(form);
            try {
                var res = await fetch("{{ route('install.database.test') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: fd,
                });
                var j = await res.json();
                out.className = 'install-note ' + (j.ok ? 'ok' : 'warn');
                out.innerHTML = (j.ok ? '<i class="fa-solid fa-circle-check"></i> ' : '<i class="fa-solid fa-triangle-exclamation"></i> ')
                    + (j.message || (j.ok ? 'Connected.' : 'Failed.'));
            } catch (e) {
                out.className = 'install-note warn';
                out.textContent = 'Network error while testing connection.';
            }
        });
    })();
    </script>
@endsection

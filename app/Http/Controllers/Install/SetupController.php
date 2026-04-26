<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Database\Seeders\SeoSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\SubscriptionPlanSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PDO;
use PDOException;
use Throwable;

class SetupController extends Controller
{
    /* ============================================================
       Entry: welcome + system requirements
       ============================================================ */
    public function welcome()
    {
        return view('install.welcome', [
            'checks'    => $this->runRequirementChecks(),
            'stepIndex' => 1,
        ]);
    }

    /* ============================================================
       Step: database credentials
       ============================================================ */
    public function database()
    {
        return view('install.database', [
            'data'      => $this->state()['db'] ?? [
                'connection' => 'mysql',
                'host'       => '127.0.0.1',
                'port'       => '3306',
                'database'   => 'aigrowbot',
                'username'   => 'root',
                'password'   => '',
            ],
            'stepIndex' => 2,
        ]);
    }

    public function saveDatabase(Request $request)
    {
        $data = $request->validate([
            'connection' => ['required', Rule::in(['mysql', 'mariadb'])],
            'host'       => ['required', 'string', 'max:120'],
            'port'       => ['required', 'numeric'],
            'database'   => ['required', 'string', 'max:120'],
            'username'   => ['required', 'string', 'max:120'],
            'password'   => ['nullable', 'string', 'max:255'],
        ]);

        $err = $this->connectionError($data);
        if ($err) {
            return back()->withInput()->withErrors(['database' => $err]);
        }

        $this->mergeState(['db' => $data]);
        return redirect()->route('install.site');
    }

    /** AJAX endpoint — returns JSON {ok, message}. */
    public function testConnection(Request $request)
    {
        $data = $request->validate([
            'connection' => ['required', Rule::in(['mysql', 'mariadb'])],
            'host'       => ['required', 'string'],
            'port'       => ['required', 'numeric'],
            'database'   => ['required', 'string'],
            'username'   => ['required', 'string'],
            'password'   => ['nullable', 'string'],
        ]);

        $err = $this->connectionError($data);
        return $err
            ? response()->json(['ok' => false, 'message' => $err], 200)
            : response()->json(['ok' => true, 'message' => 'Connected successfully.']);
    }

    /* ============================================================
       Step: site settings
       ============================================================ */
    public function site()
    {
        return view('install.site', [
            'data' => $this->state()['site'] ?? [
                'site_name'       => 'AI Grow Bot',
                'site_tagline'    => 'Grow Faster with AI Automation',
                'app_url'         => rtrim(request()->getSchemeAndHttpHost(), '/'),
                'contact_email'   => '',
                'contact_phone'   => '',
                'whatsapp_number' => '',
                'timezone'        => 'Asia/Kolkata',
            ],
            'stepIndex' => 3,
        ]);
    }

    public function saveSite(Request $request)
    {
        $data = $request->validate([
            'site_name'       => ['required', 'string', 'max:100'],
            'site_tagline'    => ['nullable', 'string', 'max:200'],
            'app_url'         => ['required', 'url', 'max:255'],
            'contact_email'   => ['required', 'email', 'max:160'],
            'contact_phone'   => ['nullable', 'string', 'max:40'],
            'whatsapp_number' => ['nullable', 'string', 'max:25'],
            'timezone'        => ['required', 'string', 'max:60'],
        ]);
        $this->mergeState(['site' => $data]);
        return redirect()->route('install.admin');
    }

    /* ============================================================
       Step: admin account
       ============================================================ */
    public function admin()
    {
        return view('install.admin', [
            'data' => $this->state()['admin_public'] ?? [
                'name'  => '',
                'email' => '',
            ],
            'stepIndex' => 4,
        ]);
    }

    public function saveAdmin(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:120'],
            'email'                 => ['required', 'email', 'max:160'],
            'password'              => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ]);

        $this->mergeState([
            'admin_public' => ['name' => $data['name'], 'email' => $data['email']],
            'admin_secret' => [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ],
        ]);
        return redirect()->route('install.finalize');
    }

    /* ============================================================
       Step: review + install
       ============================================================ */
    public function finalize()
    {
        $s = $this->state();
        if (empty($s['db']) || empty($s['site']) || empty($s['admin_secret'])) {
            return redirect()->route('install.welcome')
                ->withErrors(['install' => 'Setup data is incomplete. Please start again.']);
        }

        return view('install.finalize', [
            'db'        => $s['db'],
            'site'      => $s['site'],
            'admin'     => $s['admin_public'] ?? ['name' => '', 'email' => ''],
            'stepIndex' => 5,
        ]);
    }

    public function install(Request $request)
    {
        $s = $this->state();
        if (empty($s['db']) || empty($s['site']) || empty($s['admin_secret'])) {
            return redirect()->route('install.welcome')
                ->withErrors(['install' => 'Setup data is incomplete. Please start again.']);
        }

        try {
            // 1. Write .env with DB + app metadata
            $this->writeEnv([
                'APP_NAME'       => $s['site']['site_name'],
                'APP_ENV'        => 'production',
                'APP_DEBUG'      => 'false',
                'APP_URL'        => rtrim($s['site']['app_url'], '/'),
                'APP_TIMEZONE'   => $s['site']['timezone'],
                'SESSION_DRIVER' => 'file',
                'CACHE_STORE'    => 'file',
                'DB_CONNECTION'  => $s['db']['connection'],
                'DB_HOST'        => $s['db']['host'],
                'DB_PORT'        => (string) $s['db']['port'],
                'DB_DATABASE'    => $s['db']['database'],
                'DB_USERNAME'    => $s['db']['username'],
                'DB_PASSWORD'    => (string) ($s['db']['password'] ?? ''),
            ]);

            // 2. Ensure APP_KEY exists
            if (! env('APP_KEY')) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            // 3. Point the active DB connection at the new creds (no process restart)
            $this->rebindDb($s['db']);

            // 4. Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // 5. Run seeders (skip AdminSeeder — we create a user-defined admin)
            Artisan::call('db:seed', ['--class' => RolesPermissionsSeeder::class, '--force' => true]);
            Artisan::call('db:seed', ['--class' => SeoSeeder::class,              '--force' => true]);
            Artisan::call('db:seed', ['--class' => SettingsSeeder::class,         '--force' => true]);
            Artisan::call('db:seed', ['--class' => SubscriptionPlanSeeder::class, '--force' => true]);

            // 6. Overwrite seeded site settings with wizard values
            $this->applySiteSettings($s['site']);

            // 7. Create the admin user and assign the Admin role
            $this->createAdmin($s['admin_secret']);

            // 8. Lock the installer
            $this->writeMarker($s['site']);

            // 9. Clear state file
            @unlink($this->statePath());

            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            // Render the completion screen inline — subsequent requests will be
            // blocked by EnsureInstalled (marker is now present).
            return response()->view('install.complete', [
                'adminUrl'  => url('/admin'),
                'siteUrl'   => url('/'),
                'stepIndex' => 6,
            ]);
        } catch (Throwable $e) {
            return back()->withErrors(['install' => 'Install failed: ' . $e->getMessage()]);
        }
    }

    /* ============================================================
       Complete
       ============================================================ */
    public function complete()
    {
        // Once the marker exists, middleware would 404 this route.
        // So we render it directly from state of env.
        return view('install.complete', [
            'adminUrl'  => url('/admin'),
            'siteUrl'   => url('/'),
            'stepIndex' => 6,
        ]);
    }

    /* ============================================================
       Helpers
       ============================================================ */

    /** Path of the state file. Survives between steps without sessions. */
    protected function statePath(): string
    {
        return storage_path('app/install-state.json');
    }

    protected function state(): array
    {
        $p = $this->statePath();
        if (! is_file($p)) return [];
        $raw = @file_get_contents($p);
        $d = $raw ? json_decode($raw, true) : [];
        return is_array($d) ? $d : [];
    }

    protected function mergeState(array $add): void
    {
        @mkdir(dirname($this->statePath()), 0755, true);
        file_put_contents(
            $this->statePath(),
            json_encode(array_merge($this->state(), $add), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
        @chmod($this->statePath(), 0600);
    }

    /** Return null on success, or a human-readable message on failure. */
    protected function connectionError(array $data): ?string
    {
        try {
            $dsn = sprintf('%s:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $data['connection'], $data['host'], (int) $data['port'], $data['database']);
            new PDO($dsn, $data['username'], (string) ($data['password'] ?? ''), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
            ]);
            return null;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /** Push user-provided DB creds into the live config + purge the connection. */
    protected function rebindDb(array $db): void
    {
        $name = $db['connection']; // 'mysql' or 'mariadb'
        config([
            "database.default"                      => $name,
            "database.connections.$name.driver"     => $name,
            "database.connections.$name.host"       => $db['host'],
            "database.connections.$name.port"       => (int) $db['port'],
            "database.connections.$name.database"   => $db['database'],
            "database.connections.$name.username"   => $db['username'],
            "database.connections.$name.password"   => (string) ($db['password'] ?? ''),
            "database.connections.$name.charset"    => 'utf8mb4',
            "database.connections.$name.collation"  => 'utf8mb4_unicode_ci',
            "database.connections.$name.strict"     => true,
        ]);
        DB::purge($name);
    }

    /** Idempotent .env writer — replace existing keys, append missing ones. */
    protected function writeEnv(array $updates): void
    {
        $path = base_path('.env');
        if (! is_file($path) && is_file(base_path('.env.example'))) {
            copy(base_path('.env.example'), $path);
        }
        $content = is_file($path) ? file_get_contents($path) : '';

        foreach ($updates as $key => $value) {
            $escaped     = $this->escapeEnvValue((string) $value);
            $line        = "$key=$escaped";
            $pattern     = '/^\s*' . preg_quote($key, '/') . '\s*=.*$/m';
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $line, $content);
            } else {
                $content = rtrim($content, "\r\n") . PHP_EOL . $line . PHP_EOL;
            }
        }

        file_put_contents($path, $content);
        @chmod($path, 0640);
    }

    protected function escapeEnvValue(string $v): string
    {
        if ($v === '') return '';
        // Quote if value contains whitespace, '#', '"', or starts with a special char.
        if (preg_match('/[\s#"\'\\\\$]/', $v)) {
            return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $v) . '"';
        }
        return $v;
    }

    protected function applySiteSettings(array $site): void
    {
        Setting::updateOrCreate(['key' => 'site_name'],        ['value' => $site['site_name'],       'group' => 'general']);
        Setting::updateOrCreate(['key' => 'site_tagline'],     ['value' => $site['site_tagline'] ?? '', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'contact_email'],    ['value' => $site['contact_email'],   'group' => 'general']);
        Setting::updateOrCreate(['key' => 'contact_phone'],    ['value' => $site['contact_phone'] ?? '', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'whatsapp_number'],  ['value' => $site['whatsapp_number'] ?? '', 'group' => 'general']);
    }

    protected function createAdmin(array $secret): void
    {
        $user = User::updateOrCreate(
            ['email' => $secret['email']],
            [
                'name'              => $secret['name'],
                'password'          => $secret['password'], // already hashed
                'email_verified_at' => now(),
            ]
        );
        if (method_exists($user, 'assignRole')) {
            try { $user->syncRoles(['Admin']); } catch (Throwable $e) {}
        }
    }

    protected function writeMarker(array $site): void
    {
        $dir = storage_path('app');
        @mkdir($dir, 0755, true);
        file_put_contents($dir . '/installed.lock', json_encode([
            'installed_at' => now()->toIso8601String(),
            'site_name'    => $site['site_name'],
            'app_url'      => $site['app_url'],
            'version'      => '1.0.0',
        ], JSON_PRETTY_PRINT));
        @chmod($dir . '/installed.lock', 0644);
    }

    protected function runRequirementChecks(): array
    {
        $checks = [];

        $checks[] = [
            'label'  => 'PHP 8.2 or higher',
            'ok'     => version_compare(PHP_VERSION, '8.2.0', '>='),
            'detail' => 'Current: ' . PHP_VERSION,
        ];

        foreach (['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'curl', 'fileinfo', 'bcmath'] as $ext) {
            $checks[] = [
                'label'  => "PHP extension: $ext",
                'ok'     => extension_loaded($ext),
                'detail' => extension_loaded($ext) ? 'loaded' : 'missing',
            ];
        }

        $writables = [
            storage_path()           => 'storage/',
            storage_path('framework')=> 'storage/framework/',
            storage_path('logs')     => 'storage/logs/',
            base_path('bootstrap/cache') => 'bootstrap/cache/',
            base_path()              => 'project root (.env write access)',
        ];
        foreach ($writables as $path => $label) {
            $checks[] = [
                'label'  => "Writable: $label",
                'ok'     => is_writable($path),
                'detail' => is_writable($path) ? 'writable' : 'NOT writable — chmod required',
            ];
        }

        return $checks;
    }
}

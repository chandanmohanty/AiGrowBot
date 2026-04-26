@extends('install.layout', ['pageTitle' => 'Installed'])

@section('install-content')
    <div class="complete-hero">
        <div class="complete-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h2>Installation complete</h2>
        <p class="install-lead">Your site is ready. The installer has been locked — this wizard is no longer accessible.</p>
    </div>

    <ul class="complete-list">
        <li><i class="fa-solid fa-check"></i> <code>.env</code> written with your database credentials</li>
        <li><i class="fa-solid fa-check"></i> Application key generated</li>
        <li><i class="fa-solid fa-check"></i> Database migrated &amp; seeded</li>
        <li><i class="fa-solid fa-check"></i> Admin account created</li>
        <li><i class="fa-solid fa-check"></i> Installer locked (<code>storage/app/installed.lock</code>)</li>
    </ul>

    <div class="install-note info">
        <i class="fa-solid fa-circle-info"></i>
        <strong>Next steps:</strong> log in to the admin panel to finish configuring your site — social links, SEO, blog posts and subscription plans are all editable from there.
    </div>

    <div class="install-actions" style="justify-content:center;gap:12px">
        <a href="{{ $siteUrl }}" class="btn-ghost"><i class="fa-solid fa-house"></i> View site</a>
        <a href="{{ $adminUrl }}" class="btn-next"><i class="fa-solid fa-right-to-bracket"></i> Go to admin</a>
    </div>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $keys = [
            'site_name', 'site_tagline', 'contact_email', 'contact_phone',
            'whatsapp_number', 'google_analytics', 'facebook_pixel', 'google_verify',
            'social_twitter', 'social_linkedin', 'social_facebook', 'social_instagram',
            'social_youtube',
        ];

        $values = [];
        foreach ($keys as $k) $values[$k] = Setting::get($k);

        return view('admin.settings.index', compact('values'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name'        => ['nullable', 'string', 'max:100'],
            'site_tagline'     => ['nullable', 'string', 'max:200'],
            'contact_email'    => ['nullable', 'email', 'max:160'],
            'contact_phone'    => ['nullable', 'string', 'max:40'],
            'whatsapp_number'  => ['nullable', 'string', 'max:20'],
            'google_analytics' => ['nullable', 'string', 'max:30'],
            'facebook_pixel'   => ['nullable', 'string', 'max:30'],
            'google_verify'    => ['nullable', 'string', 'max:255'],
            'social_twitter'   => ['nullable', 'url', 'max:255'],
            'social_linkedin'  => ['nullable', 'url', 'max:255'],
            'social_facebook'  => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_youtube'   => ['nullable', 'url', 'max:255'],
        ]);

        foreach ($data as $k => $v) {
            Setting::put($k, $v);
        }

        return back()->with('success', 'Settings saved.');
    }
}

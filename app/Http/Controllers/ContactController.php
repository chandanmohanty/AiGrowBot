<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot
        if (filled($request->input('website'))) {
            return back();
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:160'],
            'phone'   => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($data + ['ip' => $request->ip()]);

        $wa = Setting::get('whatsapp_number', '919870217934');
        $lines = [
            '*New Enquiry — AI Grow Bot*',
            '',
            "*Name:* {$data['name']}",
            "*Email:* {$data['email']}",
        ];
        if (!empty($data['phone'])) $lines[] = "*Phone:* {$data['phone']}";
        $lines[] = "*Subject:* {$data['subject']}";
        $lines[] = '';
        $lines[] = '*Message:*';
        $lines[] = $data['message'];

        $url = 'https://wa.me/' . $wa . '?text=' . rawurlencode(implode("\n", $lines));

        return redirect()->away($url);
    }
}

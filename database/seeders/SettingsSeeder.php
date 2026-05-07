<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name'          => 'AI Grow Bot',
            'site_tagline'       => 'Grow Faster with AI Automation',
            'contact_email'      => 'support@aigrowbot.com',
            'contact_phone'      => '+91 80760 96255',
            'whatsapp_number'    => '919870217934',
            'google_analytics'   => 'G-02PSSJ4R83',
            'facebook_pixel'     => '',
            'google_verify'      => '',
            'social_twitter'     => 'https://x.com/DawkiInfotech',
            'social_linkedin'    => 'https://www.linkedin.com/in/mohantychandan/',
            'social_facebook'    => 'https://www.facebook.com/dawkiinfotech',
            'social_instagram'   => 'https://www.instagram.com/dawki_infotech/',
            'social_youtube'     => '',
        ];

        foreach ($defaults as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v, 'group' => 'general']);
        }
    }
}

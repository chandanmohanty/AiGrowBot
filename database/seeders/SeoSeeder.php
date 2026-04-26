<?php

namespace Database\Seeders;

use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'route_key'   => 'home',
                'title'       => 'AI Grow Bot — Grow Faster with AI Automation',
                'description' => 'AI Grow Bot helps you automate tasks, track growth, and scale smarter with AI-powered WhatsApp automation.',
                'keywords'    => 'WhatsApp automation, AI chatbot, marketing automation, WhatsApp Business API',
            ],
            [
                'route_key'   => 'blog.index',
                'title'       => 'Blog — AI Grow Bot',
                'description' => 'Insights, tutorials and product updates from the AI Grow Bot team.',
                'keywords'    => 'whatsapp marketing blog, ai automation tips',
            ],
            [
                'route_key'   => 'contact',
                'title'       => 'Contact — AI Grow Bot',
                'description' => 'Get in touch with AI Grow Bot. We reply within minutes on WhatsApp.',
                'keywords'    => 'contact, whatsapp support',
            ],
        ];
        foreach ($rows as $r) {
            SeoMeta::updateOrCreate(['route_key' => $r['route_key']], $r);
        }
    }
}

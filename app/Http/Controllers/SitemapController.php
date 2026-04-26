<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = [
                ['loc' => url('/'),              'changefreq' => 'daily',   'priority' => '1.0'],
                ['loc' => route('blog.index'),   'changefreq' => 'daily',   'priority' => '0.9'],
                ['loc' => url('/privacy'),       'changefreq' => 'monthly', 'priority' => '0.3'],
                ['loc' => url('/terms'),         'changefreq' => 'monthly', 'priority' => '0.3'],
            ];

            foreach (Post::published()->latest('published_at')->get(['slug', 'updated_at']) as $post) {
                $urls[] = [
                    'loc'        => route('blog.show', $post->slug),
                    'lastmod'    => optional($post->updated_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.7',
                ];
            }

            $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($urls as $u) {
                $xml .= '<url>';
                $xml .= '<loc>' . htmlspecialchars($u['loc']) . '</loc>';
                if (!empty($u['lastmod'])) $xml .= '<lastmod>' . $u['lastmod'] . '</lastmod>';
                $xml .= '<changefreq>' . $u['changefreq'] . '</changefreq>';
                $xml .= '<priority>' . $u['priority'] . '</priority>';
                $xml .= '</url>';
            }
            $xml .= '</urlset>';
            return $xml;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /register',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
        ];
        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
    }
}

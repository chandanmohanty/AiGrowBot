<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Services\SeoService;

class HomeController extends Controller
{
    public function index(SeoService $seo)
    {
        return response()
            ->view('home', [
                'seo'   => $seo->forRoute('home'),
                'plans' => SubscriptionPlan::active()->ordered()->get(),
            ])
            ->header('Cache-Control', 'public, max-age=600');
    }

    public function staticPage(string $slug, SeoService $seo)
    {
        abort_unless(view()->exists("pages.$slug"), 404);

        return response()
            ->view("pages.$slug", [
                'seo' => $seo->forRoute("page.$slug", [
                    'title' => ucfirst($slug) . ' — ' . config('seo.site_name'),
                ]),
            ])
            ->header('Cache-Control', 'public, max-age=600');
    }
}

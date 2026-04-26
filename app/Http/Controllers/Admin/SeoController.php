<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function index()
    {
        return view('admin.seo.index', [
            'metas' => SeoMeta::orderBy('route_key')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.seo.form', ['meta' => new SeoMeta()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        SeoMeta::create($data);
        Cache::flush();
        return redirect()->route('admin.seo.index')->with('success', 'SEO entry created.');
    }

    public function edit(SeoMeta $meta)
    {
        return view('admin.seo.form', ['meta' => $meta]);
    }

    public function update(Request $request, SeoMeta $meta)
    {
        $data = $this->validateData($request, $meta->id);
        $meta->update($data);
        Cache::flush();
        return redirect()->route('admin.seo.index')->with('success', 'SEO entry updated.');
    }

    public function destroy(SeoMeta $meta)
    {
        $meta->delete();
        Cache::flush();
        return back()->with('success', 'SEO entry deleted.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'route_key'     => ['required', 'string', 'max:100', "unique:seo_meta,route_key,{$ignoreId}"],
            'title'         => ['required', 'string', 'max:160'],
            'description'   => ['nullable', 'string', 'max:320'],
            'keywords'      => ['nullable', 'string', 'max:255'],
            'og_image'      => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:500'],
            'noindex'       => ['nullable', 'boolean'],
        ]);
    }
}

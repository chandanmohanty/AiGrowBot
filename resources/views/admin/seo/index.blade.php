@extends('layouts.admin')

@section('admin-content')
<div class="page-head">
    <h1 class="page-title">SEO Management</h1>
    <a href="{{ route('admin.seo.create') }}" class="btn btn-primary-admin"><i class="fa-solid fa-plus"></i> Add SEO Entry</a>
</div>

<div class="card" style="background:#eff6ff;border-color:#bfdbfe">
    <strong>How it works:</strong> Each SEO entry is tied to a <code>route_key</code> (e.g. <code>home</code>, <code>blog.index</code>, <code>contact</code>). Use these to control title, meta description, OG image, canonical URL, and noindex per page. Individual blog posts have their own SEO fields on the post form.
</div>

<table class="table">
    <thead><tr><th>Route Key</th><th>Title</th><th>Description</th><th>Noindex</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach ($metas as $m)
            <tr>
                <td><code>{{ $m->route_key }}</code></td>
                <td>{{ $m->title }}</td>
                <td>{{ \Illuminate\Support\Str::limit($m->description, 80) }}</td>
                <td>@if ($m->noindex)<span class="badge badge-warning">Yes</span>@else—@endif</td>
                <td class="row-actions">
                    <a href="{{ route('admin.seo.edit', $m) }}" class="btn btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.seo.destroy', $m) }}" data-confirm="Delete?" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination">{{ $metas->links() }}</div>
@endsection

@extends('layouts.admin')

@section('admin-content')
@php $isEdit = $meta->exists; @endphp

<div class="page-head">
    <h1 class="page-title">{{ $isEdit ? 'Edit SEO Entry' : 'Add SEO Entry' }}</h1>
    <a href="{{ route('admin.seo.index') }}" class="btn">← Back</a>
</div>

@if ($errors->any())<div class="alert alert-danger">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

<form method="POST" action="{{ $isEdit ? route('admin.seo.update', $meta) : route('admin.seo.store') }}" class="card">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="form-group">
        <label>Route Key *</label>
        <input type="text" name="route_key" required value="{{ old('route_key', $meta->route_key) }}" placeholder="e.g. home, blog.index, contact, page.privacy">
        <div class="help">Unique identifier matching where this SEO applies. Common values: <code>home</code>, <code>blog.index</code>, <code>contact</code>, <code>page.privacy</code>.</div>
    </div>
    <div class="form-group"><label>Title *</label><input type="text" name="title" required maxlength="160" value="{{ old('title', $meta->title) }}"></div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="3" maxlength="320">{{ old('description', $meta->description) }}</textarea></div>
    <div class="form-group"><label>Keywords</label><input type="text" name="keywords" value="{{ old('keywords', $meta->keywords) }}"></div>
    <div class="form-group"><label>OG Image URL</label><input type="text" name="og_image" value="{{ old('og_image', $meta->og_image) }}" placeholder="/img/og-home.png"></div>
    <div class="form-group"><label>Canonical URL</label><input type="url" name="canonical_url" value="{{ old('canonical_url', $meta->canonical_url) }}"></div>
    <div class="form-group">
        <label><input type="hidden" name="noindex" value="0"><input type="checkbox" name="noindex" value="1" {{ old('noindex', $meta->noindex) ? 'checked' : '' }}> Noindex</label>
    </div>
    <button class="btn btn-primary-admin">{{ $isEdit ? 'Update' : 'Create' }}</button>
</form>
@endsection

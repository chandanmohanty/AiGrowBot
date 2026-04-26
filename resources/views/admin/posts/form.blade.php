@extends('layouts.admin')

@push('head')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('admin-content')
@php $isEdit = $post->exists; @endphp

<div class="page-head">
    <h1 class="page-title">{{ $isEdit ? 'Edit Post' : 'New Post' }}</h1>
    <a href="{{ route('admin.posts.index') }}" class="btn">← Back</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
@endif

<form method="POST" action="{{ $isEdit ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
        <div>
            <div class="card">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" required value="{{ old('title', $post->title) }}">
                </div>
                <div class="form-group">
                    <label>Slug <span class="help">(auto-generated from title if empty)</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $post->slug) }}">
                </div>
                <div class="form-group">
                    <label>Excerpt</label>
                    <textarea name="excerpt" rows="3" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea name="body" id="body-editor" rows="20">{{ old('body', $post->body) }}</textarea>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-top:0">SEO Settings</h3>
                <div class="form-group">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" maxlength="160" value="{{ old('meta_title', $post->meta_title) }}">
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" rows="2" maxlength="300">{{ old('meta_description', $post->meta_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Meta Keywords <span class="help">(comma-separated)</span></label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}">
                </div>
                <div class="form-group">
                    <label>Canonical URL</label>
                    <input type="url" name="canonical_url" value="{{ old('canonical_url', $post->canonical_url) }}">
                </div>
                <div class="form-group">
                    <label><input type="hidden" name="noindex" value="0"><input type="checkbox" name="noindex" value="1" {{ old('noindex', $post->noindex) ? 'checked' : '' }}> Noindex (hide from search engines)</label>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <h3 style="margin-top:0">Publish</h3>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="draft"     @selected(old('status', $post->status) === 'draft')>Draft</option>
                        @can('publish posts')
                        <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                        @endcan
                    </select>
                </div>
                <div class="form-group">
                    <label>Publish Date</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
                </div>
                <button type="submit" class="btn btn-primary-admin" style="width:100%">{{ $isEdit ? 'Update Post' : 'Create Post' }}</button>
            </div>

            <div class="card">
                <h3 style="margin-top:0">Cover Image</h3>
                @if ($post->cover_image)
                    <img src="{{ $post->cover_image }}" style="max-width:100%;border-radius:8px;margin-bottom:10px">
                @endif
                <input type="file" name="cover_image" accept="image/*">
            </div>

            <div class="card">
                <h3 style="margin-top:0">Category</h3>
                <select name="category_id">
                    <option value="">— None —</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $post->category_id) == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="card">
                <h3 style="margin-top:0">Tags</h3>
                <div class="checkboxes">
                    @foreach ($tags as $t)
                        <label><input type="checkbox" name="tags[]" value="{{ $t->id }}" @checked(in_array($t->id, old('tags', $post->tags->pluck('id')->toArray() ?? [])))> {{ $t->name }}</label>
                    @endforeach
                </div>
                <p class="help" style="margin-top:10px">Add new tags as a comma-separated list:</p>
                <input type="text" name="new_tags" placeholder="new-tag, another-tag" onchange="addNewTags(this)">
            </div>
        </div>
    </div>
</form>

<script>
if (window.tinymce) {
    tinymce.init({
        selector:'#body-editor',
        plugins:'link image lists table code preview',
        toolbar:'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code preview',
        height:500, menubar:false,
    });
}
function addNewTags(el){
    const form = el.closest('form');
    el.value.split(',').map(s=>s.trim()).filter(Boolean).forEach(name=>{
        const input = document.createElement('input');
        input.type='hidden'; input.name='tags[]'; input.value=name;
        form.appendChild(input);
    });
    el.value='';
}
</script>
@endsection

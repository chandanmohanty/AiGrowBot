@extends('layouts.admin')

@section('admin-content')
<div class="page-head">
    <h1 class="page-title">Blog Posts</h1>
    @can('create posts')
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary-admin"><i class="fa-solid fa-plus"></i> New Post</a>
    @endcan
</div>

<form class="search-box" method="GET">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search by title or slug...">
    <button class="btn">Search</button>
</form>

<table class="table">
    <thead><tr>
        <th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Published</th><th>Views</th><th>Actions</th>
    </tr></thead>
    <tbody>
    @forelse ($posts as $p)
        <tr>
            <td><strong>{{ $p->title }}</strong><br><small style="color:#94a3b8">/blog/{{ $p->slug }}</small></td>
            <td>{{ $p->author?->name }}</td>
            <td>{{ $p->category?->name ?? '—' }}</td>
            <td><span class="badge badge-{{ $p->status === 'published' ? 'success' : 'warning' }}">{{ $p->status }}</span></td>
            <td>{{ optional($p->published_at)->format('M d, Y') ?? '—' }}</td>
            <td>{{ $p->views }}</td>
            <td class="row-actions">
                <a href="{{ route('blog.show', $p->slug) }}" target="_blank" class="btn btn-sm" title="View"><i class="fa-solid fa-eye"></i></a>
                <a href="{{ route('admin.posts.edit', $p) }}" class="btn btn-sm">Edit</a>
                @can('delete posts')
                <form method="POST" action="{{ route('admin.posts.destroy', $p) }}" data-confirm="Delete this post?" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
                @endcan
            </td>
        </tr>
    @empty
        <tr><td colspan="7" style="text-align:center;color:#94a3b8;padding:32px">No posts yet.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="pagination">{{ $posts->links() }}</div>
@endsection

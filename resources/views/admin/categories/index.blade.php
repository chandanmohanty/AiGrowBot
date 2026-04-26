@extends('layouts.admin')

@section('admin-content')
<div class="page-head"><h1 class="page-title">Categories</h1></div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">
    <div class="card">
        <h3 style="margin-top:0">Add Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="3"></textarea></div>
            <button class="btn btn-primary-admin">Add Category</button>
        </form>
    </div>

    <div class="card">
        <h3 style="margin-top:0">All Categories</h3>
        <table class="table">
            <thead><tr><th>Name</th><th>Slug</th><th>Posts</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($categories as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td><code>{{ $c->slug }}</code></td>
                        <td>{{ $c->posts_count }}</td>
                        <td class="row-actions">
                            <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" data-confirm="Delete?" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:#94a3b8">No categories.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $categories->links() }}</div>
    </div>
</div>
@endsection

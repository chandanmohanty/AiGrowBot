@extends('layouts.admin')

@section('admin-content')
<div class="page-head"><h1 class="page-title">Tags</h1></div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">
    <div class="card">
        <h3 style="margin-top:0">Add Tag</h3>
        <form method="POST" action="{{ route('admin.tags.store') }}">
            @csrf
            <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
            <button class="btn btn-primary-admin">Add Tag</button>
        </form>
    </div>

    <div class="card">
        <h3 style="margin-top:0">All Tags</h3>
        <table class="table">
            <thead><tr><th>Name</th><th>Slug</th><th>Posts</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($tags as $t)
                    <tr>
                        <td>{{ $t->name }}</td>
                        <td><code>{{ $t->slug }}</code></td>
                        <td>{{ $t->posts_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.tags.destroy', $t) }}" data-confirm="Delete?" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:#94a3b8">No tags.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $tags->links() }}</div>
    </div>
</div>
@endsection

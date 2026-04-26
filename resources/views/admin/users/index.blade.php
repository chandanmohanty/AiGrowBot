@extends('layouts.admin')

@section('admin-content')
<div class="page-head">
    <h1 class="page-title">Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary-admin"><i class="fa-solid fa-plus"></i> New User</a>
</div>

<table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Roles</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                    @foreach ($u->roles as $r)
                        <span class="badge badge-gray">{{ $r->name }}</span>
                    @endforeach
                </td>
                <td>{{ $u->created_at->format('M d, Y') }}</td>
                <td class="row-actions">
                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm">Edit</a>
                    @if ($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Delete this user?" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination">{{ $users->links() }}</div>
@endsection

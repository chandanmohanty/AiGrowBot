@extends('layouts.admin')

@section('admin-content')
<div class="page-head"><h1 class="page-title">Contact Messages</h1></div>

<table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse ($messages as $m)
            <tr>
                <td>{{ $m->name }}</td>
                <td>{{ $m->email }}</td>
                <td>{{ $m->subject }}</td>
                <td>{{ $m->created_at->diffForHumans() }}</td>
                <td class="row-actions">
                    <a href="{{ route('admin.messages.show', $m) }}" class="btn btn-sm">View</a>
                    <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" data-confirm="Delete?" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center;color:#94a3b8">No messages.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="pagination">{{ $messages->links() }}</div>
@endsection

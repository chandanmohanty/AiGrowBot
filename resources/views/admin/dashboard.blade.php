@extends('layouts.admin')

@section('admin-content')
<div class="page-head">
    <h1 class="page-title">Dashboard</h1>
    <div>Welcome, {{ auth()->user()->name }}</div>
</div>

<div class="grid-stats">
    <div class="stat">
        <div class="stat-label">Total Posts</div>
        <div class="stat-value">{{ $stats['posts'] }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Published</div>
        <div class="stat-value">{{ $stats['published'] }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Users</div>
        <div class="stat-value">{{ $stats['users'] }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Messages</div>
        <div class="stat-value">{{ $stats['messages'] }}</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0">Latest Posts</h3>
    <table class="table">
        <thead><tr><th>Title</th><th>Author</th><th>Status</th><th>Created</th></tr></thead>
        <tbody>
        @forelse ($latest_posts as $p)
            <tr>
                <td><a href="{{ route('admin.posts.edit', $p) }}">{{ $p->title }}</a></td>
                <td>{{ $p->author?->name }}</td>
                <td><span class="badge badge-{{ $p->status === 'published' ? 'success' : 'warning' }}">{{ $p->status }}</span></td>
                <td>{{ $p->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center;color:#94a3b8">No posts yet. <a href="{{ route('admin.posts.create') }}">Create one</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3 style="margin-top:0">Latest Contact Messages</h3>
    <table class="table">
        <thead><tr><th>Name</th><th>Subject</th><th>Email</th><th>Date</th></tr></thead>
        <tbody>
        @forelse ($latest_messages as $m)
            <tr>
                <td>{{ $m->name }}</td>
                <td>{{ $m->subject }}</td>
                <td>{{ $m->email }}</td>
                <td>{{ $m->created_at->diffForHumans() }}</td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center;color:#94a3b8">No messages yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

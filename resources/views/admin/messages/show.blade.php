@extends('layouts.admin')

@section('admin-content')
<div class="page-head">
    <h1 class="page-title">Message</h1>
    <a href="{{ route('admin.messages.index') }}" class="btn">← Back</a>
</div>

<div class="card">
    <p><strong>From:</strong> {{ $message->name }} &lt;{{ $message->email }}&gt;</p>
    @if ($message->phone)<p><strong>Phone:</strong> {{ $message->phone }}</p>@endif
    <p><strong>Subject:</strong> {{ $message->subject }}</p>
    <p><strong>IP:</strong> {{ $message->ip }} &nbsp; <strong>Date:</strong> {{ $message->created_at }}</p>
    <hr>
    <p style="white-space:pre-wrap">{{ $message->message }}</p>
</div>
@endsection

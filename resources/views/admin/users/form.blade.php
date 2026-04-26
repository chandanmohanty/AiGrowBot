@extends('layouts.admin')

@section('admin-content')
@php $isEdit = $user->exists; @endphp

<div class="page-head">
    <h1 class="page-title">{{ $isEdit ? 'Edit User' : 'New User' }}</h1>
    <a href="{{ route('admin.users.index') }}" class="btn">← Back</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
@endif

<form method="POST" action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="card">
        <div class="form-row">
            <div class="form-group"><label>Name *</label><input type="text" name="name" required value="{{ old('name', $user->name) }}"></div>
            <div class="form-group"><label>Email *</label><input type="email" name="email" required value="{{ old('email', $user->email) }}"></div>
        </div>
        <div class="form-group">
            <label>Password {{ $isEdit ? '(leave blank to keep current)' : '*' }}</label>
            <input type="password" name="password" {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password">
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Roles</h3>
        <div class="checkboxes">
            @foreach ($roles as $r)
                <label><input type="checkbox" name="roles[]" value="{{ $r->name }}" @checked(in_array($r->name, old('roles', $user->roles->pluck('name')->toArray() ?? [])))> {{ $r->name }}</label>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Direct Permissions <span style="font-size:12px;color:#94a3b8">(in addition to role permissions)</span></h3>
        <div class="checkboxes">
            @foreach ($permissions as $p)
                <label><input type="checkbox" name="permissions[]" value="{{ $p->name }}" @checked(in_array($p->name, old('permissions', $user->getDirectPermissions()->pluck('name')->toArray() ?? [])))> {{ $p->name }}</label>
            @endforeach
        </div>
    </div>

    <button class="btn btn-primary-admin">{{ $isEdit ? 'Update User' : 'Create User' }}</button>
</form>
@endsection

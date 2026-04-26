<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('roles')->orderBy('name')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.users.form', [
            'user'        => new User(),
            'roles'       => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8'],
            'roles'       => ['nullable', 'array'],
            'roles.*'     => ['exists:roles,name'],
            'permissions' => ['nullable', 'array'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->syncRoles($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user'        => $user,
            'roles'       => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120'],
            'email'       => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'    => ['nullable', 'string', 'min:8'],
            'roles'       => ['nullable', 'array'],
            'roles.*'     => ['exists:roles,name'],
            'permissions' => ['nullable', 'array'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        $user->syncRoles($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot delete yourself.');
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}

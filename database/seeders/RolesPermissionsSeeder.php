<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access admin',
            'manage users',
            'manage posts',
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'manage categories',
            'manage tags',
            'manage seo',
            'manage settings',
            'view contact messages',
            'manage subscriptions',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $editor = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'access admin',
            'manage posts', 'create posts', 'edit posts', 'delete posts', 'publish posts',
            'manage categories', 'manage tags', 'manage seo',
        ]);

        $author = Role::firstOrCreate(['name' => 'Author', 'guard_name' => 'web']);
        $author->syncPermissions([
            'access admin',
            'create posts', 'edit posts',
        ]);
    }
}

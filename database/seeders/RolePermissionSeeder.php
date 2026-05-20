<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view dashboard',
            'manage users',

            'view statistik',

            'view cabor',
            'manage cabor',

            'view atlet',
            'manage atlet',

            'view pelatih',
            'manage pelatih',

            'view wasit',
            'manage wasit',

            'view juri',
            'manage juri',

            'view artikel',
            'manage artikel',

            'view pengumuman',
            'manage pengumuman',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super admin',
            'guard_name' => 'web',
        ]);

        $kepalaCabor = Role::firstOrCreate([
            'name' => 'kepala cabor',
            'guard_name' => 'web',
        ]);

        $public = Role::firstOrCreate([
            'name' => 'public',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions($permissions);

        $kepalaCabor->syncPermissions([
            'view dashboard',
            'view statistik',
            'view cabor',
            'view atlet',
            'manage atlet',
            'view pelatih',
            'manage pelatih',
            'view wasit',
            'manage wasit',
            'view juri',
            'manage juri',
        ]);

        $public->syncPermissions([
            'view cabor',
            'view atlet',
            'view artikel',
        ]);
    }
}
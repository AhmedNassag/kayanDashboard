<?php

namespace Database\Seeders;

use App\Models\Notify;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'role read', 'role' => 'role-employee'],
            ['name' => 'role create', 'role' => 'role-employee'],
            ['name' => 'role edit', 'role' => 'role-employee'],
            ['name' => 'role delete', 'role' => 'role-employee'],
            ['name' => 'employee read', 'role' => 'role-employee'],
            ['name' => 'employee create', 'role' => 'role-employee'],
            ['name' => 'employee edit', 'role' => 'role-employee'],
            ['name' => 'employee delete', 'role' => 'role-employee'],
            ['name' => 'employeeChangePassword edit', 'role' => 'role-employee'],
            ['name' => 'department read', 'role' => 'management'],
            ['name' => 'department create', 'role' => 'management'],
            ['name' => 'department edit', 'role' => 'management'],
            ['name' => 'department delete', 'role' => 'management'],
            ['name' => 'job read', 'role' => 'management'],
            ['name' => 'job create', 'role' => 'management'],
            ['name' => 'job edit', 'role' => 'management'],
            ['name' => 'job delete', 'role' => 'management'],
            ['name' => 'unit read', 'role' => ''],
            ['name' => 'unit create', 'role' => ''],
            ['name' => 'unit edit', 'role' => ''],
            ['name' => 'unit delete', 'role' => ''],
            ['name' => 'offer read', 'role' => ''],
            ['name' => 'offer create', 'role' => ''],
            ['name' => 'offer edit', 'role' => ''],
            ['name' => 'offer delete', 'role' => ''],
            ['name' => 'shipping read', 'role' => ''],
            ['name' => 'shipping create', 'role' => ''],
            ['name' => 'shipping edit', 'role' => ''],
            ['name' => 'shipping delete', 'role' => ''],
            ['name' => 'supplier read', 'role' => ''],
            ['name' => 'supplier create', 'role' => ''],
            ['name' => 'supplier edit', 'role' => ''],
            ['name' => 'supplier delete', 'role' => ''],
        ];

        $notifies = [
            "Sdsd"
        ];

        foreach ($notifies as $notify) {
            Notify::create(['name' => $notify]);
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name'], 'role' => $permission['role']]);
        }
    }
}

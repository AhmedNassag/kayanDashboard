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

            ['name' => 'role read','role' => 'role-employee'],
            ['name' => 'role create','role' => 'role-employee'],
            ['name' => 'role edit','role' => 'role-employee'],
            ['name' => 'role delete','role' => 'role-employee'],
            ['name' => 'employeeChangePassword edit','role' => 'role-employee'],
            ['name' => 'department read','role' => 'management'],
            ['name' => 'department create','role' => 'management'],
            ['name' => 'department edit','role' => 'management'],
            ['name' => 'department delete','role' => 'management'],
            ['name' => 'job read','role' => 'management'],
            ['name' => 'job create','role' => 'management'],
            ['name' => 'job edit','role' => 'management'],
            ['name' => 'job delete','role' => 'management'],
            //category
            ['name' => 'category read','role' => 'management'],
            ['name' => 'category create','role' => 'management'],
            ['name' => 'category edit','role' => 'management'],
            ['name' => 'category delete','role' => 'management'],
            //subCategory
            ['name' => 'subCategory read','role' => 'management'],
            ['name' => 'subCategory create','role' => 'management'],
            ['name' => 'subCategory edit','role' => 'management'],
            ['name' => 'subCategory delete','role' => 'management'],
            //usersCategory
            ['name' => 'usersCategory read','role' => 'management'],
            ['name' => 'usersCategory create','role' => 'management'],
            ['name' => 'usersCategory edit','role' => 'management'],
            ['name' => 'usersCategory delete','role' => 'management'],
            //tax
            ['name' => 'tax read','role' => 'management'],
            ['name' => 'tax create','role' => 'management'],
            ['name' => 'tax edit','role' => 'management'],
            ['name' => 'tax delete','role' => 'management'],
            //company
            ['name' => 'company read','role' => 'management'],
            ['name' => 'company create','role' => 'management'],
            ['name' => 'company edit','role' => 'management'],
            ['name' => 'company delete','role' => 'management'],
            //product
            ['name' => 'product read','role' => 'management'],
            ['name' => 'product create','role' => 'management'],
            ['name' => 'product edit','role' => 'management'],
            ['name' => 'product delete','role' => 'management'],
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

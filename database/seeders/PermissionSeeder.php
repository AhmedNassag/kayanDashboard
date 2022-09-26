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
            //Role
            ['name' => 'role read', 'role' => 'role-employee'],
            ['name' => 'role create', 'role' => 'role-employee'],
            ['name' => 'role edit', 'role' => 'role-employee'],
            ['name' => 'role delete', 'role' => 'role-employee'],
            //Department
            ['name' => 'department read', 'role' => 'management'],
            ['name' => 'department create', 'role' => 'management'],
            ['name' => 'department edit', 'role' => 'management'],
            ['name' => 'department delete', 'role' => 'management'],
            //Job
            ['name' => 'job read', 'role' => 'management'],
            ['name' => 'job create', 'role' => 'management'],
            ['name' => 'job edit', 'role' => 'management'],
            ['name' => 'job delete', 'role' => 'management'],
            //category
            ['name' => 'category read', 'role' => 'management'],
            ['name' => 'category create', 'role' => 'management'],
            ['name' => 'category edit', 'role' => 'management'],
            ['name' => 'category delete', 'role' => 'management'],
            //subCategory
            ['name' => 'subCategory read', 'role' => 'management'],
            ['name' => 'subCategory create', 'role' => 'management'],
            ['name' => 'subCategory edit', 'role' => 'management'],
            ['name' => 'subCategory delete', 'role' => 'management'],
            //usersCategory
            ['name' => 'usersCategory read', 'role' => 'management'],
            ['name' => 'usersCategory create', 'role' => 'management'],
            ['name' => 'usersCategory edit', 'role' => 'management'],
            ['name' => 'usersCategory delete', 'role' => 'management'],
            //tax
            ['name' => 'tax read', 'role' => 'management'],
            ['name' => 'tax create', 'role' => 'management'],
            ['name' => 'tax edit', 'role' => 'management'],
            ['name' => 'tax delete', 'role' => 'management'],
            //company
            ['name' => 'company read', 'role' => 'management'],
            ['name' => 'company create', 'role' => 'management'],
            ['name' => 'company edit', 'role' => 'management'],
            ['name' => 'company delete', 'role' => 'management'],
            //productName
            ['name' => 'productName read', 'role' => 'management'],
            ['name' => 'productName create', 'role' => 'management'],
            ['name' => 'productName edit', 'role' => 'management'],
            ['name' => 'productName delete', 'role' => 'management'],
            //product
            ['name' => 'product read', 'role' => 'management'],
            ['name' => 'product create', 'role' => 'management'],
            ['name' => 'product edit', 'role' => 'management'],
            ['name' => 'product delete', 'role' => 'management'],
            //Pharmacist Form
            ['name' => 'pharmacistForm read', 'role' => 'management'],
            ['name' => 'pharmacistForm create', 'role' => 'management'],
            ['name' => 'pharmacistForm edit', 'role' => 'management'],
            ['name' => 'pharmacistForm delete', 'role' => 'management'],
            //price
            ['name' => 'price read', 'role' => 'management'],
            ['name' => 'price create', 'role' => 'management'],
            ['name' => 'price edit', 'role' => 'management'],
            ['name' => 'price delete', 'role' => 'management'],
            //kayanPrice
            ['name' => 'kayanPrice read', 'role' => 'management'],
            ['name' => 'kayanPrice create', 'role' => 'management'],
            ['name' => 'kayanPrice edit', 'role' => 'management'],
            ['name' => 'kayanPrice delete', 'role' => 'management'],
            //sellingMethods
            ['name' => 'sellingMethod read', 'role' => 'management'],
            ['name' => 'sellingMethod create', 'role' => 'management'],
            ['name' => 'sellingMethod edit', 'role' => 'management'],
            ['name' => 'sellingMethod delete', 'role' => 'management'],
            //shift
            ['name' => 'shift read', 'role' => 'management'],
            ['name' => 'shift create', 'role' => 'management'],
            ['name' => 'shift edit', 'role' => 'management'],
            ['name' => 'shift delete', 'role' => 'management'],
            //stock
            ['name' => 'stock read', 'role' => 'management'],
            ['name' => 'stock create', 'role' => 'management'],
            ['name' => 'stock edit', 'role' => 'management'],
            ['name' => 'stock delete', 'role' => 'management'],
            //virtualStock
            ['name' => 'virtualStock read', 'role' => 'management'],
            ['name' => 'virtualStock create', 'role' => 'management'],
            ['name' => 'virtualStock edit', 'role' => 'management'],
            ['name' => 'virtualStock delete', 'role' => 'management'],
            //purchase
            ['name' => 'purchase read', 'role' => 'management'],
            ['name' => 'purchase create', 'role' => 'management'],
            ['name' => 'purchase edit', 'role' => 'management'],
            ['name' => 'purchase delete', 'role' => 'management'],
            //saleInvoice
            ['name' => 'SaleInvoice read', 'role' => 'buy'],
            ['name' => 'SaleInvoice create', 'role' => 'buy'],
            ['name' => 'SaleInvoice edit', 'role' => 'buy'],
            ['name' => 'SaleInvoice delete', 'role' => 'buy'],
            ['name' => 'SaleReturn read', 'role' => 'buy'],
            //Sale Records
            ['name' => 'saleRecords read', 'role' => 'buy'],
            ['name' => 'saleRecords create', 'role' => 'buy'],
            ['name' => 'saleRecords edit', 'role' => 'buy'],
            //storage
            ['name' => 'storage read', 'role' => 'management'],
            ['name' => 'storage create', 'role' => 'management'],
            ['name' => 'storage edit', 'role' => 'management'],
            ['name' => 'storage delete', 'role' => 'management'],
            //refused
            ['name' => 'refused read', 'role' => 'management'],
            ['name' => 'refused create', 'role' => 'management'],
            ['name' => 'refused edit', 'role' => 'management'],
            ['name' => 'refused delete', 'role' => 'management'],
            //Unit
            ['name' => 'unit read', 'role' => ''],
            ['name' => 'unit create', 'role' => ''],
            ['name' => 'unit edit', 'role' => ''],
            ['name' => 'unit delete', 'role' => ''],
            //Know us ways
            ['name' => 'know-us-way read', 'role' => ''],
            ['name' => 'know-us-way create', 'role' => ''],
            ['name' => 'know-us-way edit', 'role' => ''],
            ['name' => 'know-us-way delete', 'role' => ''],
            //Offer
            ['name' => 'offer read', 'role' => ''],
            ['name' => 'offer create', 'role' => ''],
            ['name' => 'offer edit', 'role' => ''],
            ['name' => 'offer delete', 'role' => ''],
            //Shipping
            ['name' => 'shipping read', 'role' => ''],
            ['name' => 'shipping create', 'role' => ''],
            ['name' => 'shipping edit', 'role' => ''],
            ['name' => 'shipping delete', 'role' => ''],
            //Supplier
            ['name' => 'supplier read', 'role' => ''],
            ['name' => 'supplier create', 'role' => ''],
            ['name' => 'supplier edit', 'role' => ''],
            ['name' => 'supplier delete', 'role' => ''],
            //Employee
            ['name' => 'employee read', 'role' => 'management'],
            ['name' => 'employee create', 'role' => 'management'],
            ['name' => 'employee edit', 'role' => 'management'],
            ['name' => 'employee delete', 'role' => 'management'],
            ['name' => 'employeeChangePassword edit', 'role' => 'role-employee'],
            //Client Group
            ['name' => 'client-group read', 'role' => ''],
            ['name' => 'client-group create', 'role' => ''],
            ['name' => 'client-group edit', 'role' => ''],
            ['name' => 'client-group delete', 'role' => ''],
            //Client
            ['name' => 'client read', 'role' => ''],
            ['name' => 'client create', 'role' => ''],
            ['name' => 'client edit', 'role' => ''],
            ['name' => 'client delete', 'role' => ''],
            //Sales Points
            ['name' => 'sale-point read', 'role' => ''],
            ['name' => 'sale-point create', 'role' => ''],
            ['name' => 'sale-point edit', 'role' => ''],
            ['name' => 'sale-point delete', 'role' => ''],
            //Purchase Invoice
            ['name' => 'PurchaseInvoice read', 'role' => 'buy'],
            ['name' => 'PurchaseInvoice create', 'role' => 'buy'],
            ['name' => 'PurchaseInvoice edit', 'role' => 'buy'],
            ['name' => 'PurchaseInvoice delete', 'role' => 'buy'],
            ['name' => 'PurchaseReturn read', 'role' => 'buy'],
            //Examination Records
            ['name' => 'examinationRecords read', 'role' => 'buy'],
            ['name' => 'examinationRecords create', 'role' => 'buy'],
            ['name' => 'examinationRecords edit', 'role' => 'buy'],
            //Newsletters
            ['name' => 'newsletter read', 'role' => ''],
            //Sliders
            ['name' => 'slider read', 'role' => ''],
            ['name' => 'slider create', 'role' => ''],
            ['name' => 'slider edit', 'role' => ''],
            ['name' => 'slider delete', 'role' => ''],
            //Simple advertises
            ['name' => 'simple-advertise read', 'role' => ''],
            ['name' => 'simple-advertise create', 'role' => ''],
            ['name' => 'simple-advertise edit', 'role' => ''],
            ['name' => 'simple-advertise delete', 'role' => ''],
            //Cities
            ['name' => 'city read', 'role' => 'places'],
            ['name' => 'city create', 'role' => 'places'],
            ['name' => 'city edit', 'role' => 'places'],
            //Areas
            ['name' => 'area read', 'role' => 'places'],
            ['name' => 'area create', 'role' => 'places'],
            ['name' => 'area edit', 'role' => 'places'],
            //Unavailable places users
            ['name' => 'unavailable-city-client read', 'role' => 'places'],
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

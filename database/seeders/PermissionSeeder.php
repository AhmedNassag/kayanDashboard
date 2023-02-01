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

            //tax
            ['name' => 'tax read', 'role' => 'Tax', 'category' => 'Taxes'],
            ['name' => 'tax create', 'role' => 'Tax', 'category' => 'Taxes'],
            ['name' => 'tax edit', 'role' => 'Tax', 'category' => 'Taxes'],
            ['name' => 'tax delete', 'role' => 'Tax', 'category' => 'Taxes'],
            //company
            ['name' => 'company read', 'role' => 'companies', 'category' => 'Companies'],
            ['name' => 'company create', 'role' => 'companies', 'category' => 'Companies'],
            ['name' => 'company edit', 'role' => 'companies', 'category' => 'Companies'],
            ['name' => 'company delete', 'role' => 'companies', 'category' => 'Companies'],

            /////////////////////////////Products Management
            //category
            ['name' => 'category read', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'category create', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'category edit', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'category delete', 'role' => 'products_management', 'category' => 'Products Management'],
            //subCategory
            ['name' => 'subCategory read', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'subCategory create', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'subCategory edit', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'subCategory delete', 'role' => 'products_management', 'category' => 'Products Management'],
            //product
            ['name' => 'product read', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'product create', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'product edit', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'product delete', 'role' => 'products_management', 'category' => 'Products Management'],
            //Pharmacist Form
            ['name' => 'pharmacistForm read', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'pharmacistForm create', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'pharmacistForm edit', 'role' => 'products_management', 'category' => 'Products Management'],
            ['name' => 'pharmacistForm delete', 'role' => 'products_management', 'category' => 'Products Management'],



            //discount
            ['name' => 'discount read', 'role' => 'Discount', 'category' => 'Coupons and Discounts'],
            ['name' => 'discount create', 'role' => 'Discount', 'category' => 'Coupons and Discounts'],
            ['name' => 'discount edit', 'role' => 'Discount', 'category' => 'Coupons and Discounts'],
            ['name' => 'discount delete', 'role' => 'Discount', 'category' => 'Coupons and Discounts'],


            /////////////////////////////stock management
            //price
            ['name' => 'price read', 'role' => 'stock_management', 'category' => 'Stocks Management'],
            ['name' => 'price create', 'role' => 'stock_management', 'category' => 'Stocks Management'],
            ['name' => 'price edit', 'role' => 'stock_management', 'category' => 'Stocks Management'],
            ['name' => 'price delete', 'role' => 'stock_management', 'category' => 'Stocks Management'],
            //virtualStock
            ['name' => 'virtualStock read', 'role' => 'stock_management', 'category' => 'Stocks Management'],
            ['name' => 'virtualStock create', 'role' => 'stock_management', 'category' => 'Stocks Management'],
            ['name' => 'virtualStock edit', 'role' => 'stock_management', 'category' => 'Stocks Management'],
            ['name' => 'virtualStock delete', 'role' => 'stock_management', 'category' => 'Stocks Management'],

            // orders management
            ['name' => 'orderOnline read', 'role' => 'Orders', 'category' => 'orders management'],
            ['name' => 'orderOnline edit', 'role' => 'Orders', 'category' => 'orders management'],
            ['name' => 'CollectOrdersPerDay read', 'role' => 'Orders', 'category' => 'orders management'],
            ['name' => 'CollectOrdersPerDay edit', 'role' => 'Orders', 'category' => 'orders management'],


            //representative
            ['name' => 'representative read', 'role' => 'representatives', 'category' => 'representatives'],
            ['name' => 'representative create', 'role' => 'representatives', 'category' => 'representatives'],
            ['name' => 'representative edit', 'role' => 'representatives', 'category' => 'representatives'],
            ['name' => 'representativeChangePassword edit', 'role' => 'representatives', 'category' => 'representatives'],

            // start financial Accounts
            ['name' => 'AccountsTree read', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'AccountsTree create', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'AccountsTree edit', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'DailyRestriction read', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'DailyRestriction create', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'DailyRestriction edit', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'TrialBalance read', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'FinancialCenter read', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'IncomeList read', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],
            ['name' => 'AccountStatement read', 'role' => 'financial Accounts', 'category' => 'financial Accounts'],





            // start platform Accounts
            ['name' => 'treasury read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'treasury create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'treasury edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'treasury delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'expense read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'expense create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'expense edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'expense delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income&expense read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income&expense create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income&expense edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'income&expense delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'treasuriesIncome read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'treasuriesExpense read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'transferringTreasury read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'transferringTreasury create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'transferringTreasury edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'transferringTreasury delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],

            ['name' => 'purchaseExpenses read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'supplierAccountStatement read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'clientAccountStatement read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'financialCondition read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],

            ['name' => 'supplierExpenses read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'supplierExpenses create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'supplierExpenses edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'supplierExpenses delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],

            ['name' => 'clientExpenses read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'clientExpenses create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'clientExpenses edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'clientExpenses delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],

            ['name' => 'SupplierIncomes read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'SupplierIncomes create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'SupplierIncomes edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'SupplierIncomes delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],

            ['name' => 'clientIncomes read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'clientIncomes create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'clientIncomes edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'clientIncomes delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],

            ['name' => 'orderIncomes read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'purchaseReturnIncomes read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],

            ['name' => 'CapitalOwnerAccount read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'CapitalOwnerAccount create', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'CapitalOwnerAccount edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'CapitalOwnerAccount delete', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'SupplierDues read', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],
            ['name' => 'SupplierDues edit', 'role' => 'platform Accounts', 'category' => 'platform Accounts'],


            // end platform Accounts


            //reports
            ['name' => 'platform-accounts-Report read', 'role' => 'Report', 'category' => 'Reports'],

            //  //advertise
            ['name' => 'package read', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'package create', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'package edit', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'package show', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'package delete', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'schedule read', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'schedule show', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'schedule create', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'schedule edit', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'schedule delete', 'role' => 'advertise', 'category' => 'Advertisement'],
            //Sliders
            ['name' => 'slider read', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'slider create', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'slider edit', 'role' => 'advertise', 'category' => 'Advertisement'],
            ['name' => 'slider delete', 'role' => 'advertise', 'category' => 'Advertisement'],








            //Deals
            // ['name' => 'deal read', 'role' => ''],
            // ['name' => 'deal create', 'role' => ''],
            // ['name' => 'deal edit', 'role' => ''],
            // ['name' => 'deal delete', 'role' => ''],

            //Best sellers
            ['name' => 'best-seller read', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'best-seller create', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'best-seller edit', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'best-seller delete', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],

            //Most populars
            ['name' => 'most-popular read', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'most-popular create', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'most-popular edit', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'most-popular delete', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],

            //Also bought
            ['name' => 'also-bought read', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'also-bought create', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'also-bought edit', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],
            ['name' => 'also-bought delete', 'role' => 'Kayan Special Products','category' => 'Kayan Special Products'],



            //Cities
            ['name' => 'city read', 'role' => 'places' ,'category' => 'Places'],
            ['name' => 'city create', 'role' => 'places' ,'category' => 'Places'],
            ['name' => 'city edit', 'role' => 'places' ,'category' => 'Places'],
            //Areas
            ['name' => 'area read', 'role' => 'places' ,'category' => 'Places'],
            ['name' => 'area create', 'role' => 'places' ,'category' => 'Places'],
            ['name' => 'area edit', 'role' => 'places' ,'category' => 'Places'],
            //Unavailable places users
            ['name' => 'unavailable-city-client read', 'role' => 'places' ,'category' => 'Places'],


            //Supplier
            ['name' => 'supplier read', 'role' => 'Suppliers','category' => 'Suppliers'],
            ['name' => 'supplier create', 'role' => 'Suppliers','category' => 'Suppliers'],
            ['name' => 'supplier edit', 'role' => 'Suppliers','category' => 'Suppliers'],
            ['name' => 'supplier delete', 'role' => 'Suppliers','category' => 'Suppliers'],


            // start sitting
            ['name' => 'setting read', 'role' => 'Setting', 'category' => 'Setting'],
            ['name' => 'setting edit', 'role' => 'Setting', 'category' => 'Setting'],
            // end setting
            //Terms and conditions
            ['name' => 'termAndCondition read', 'role' => 'Setting' , 'category' =>'Setting' ],







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

            //usersCategory
            // ['name' => 'usersCategory read', 'role' => 'management'],
            // ['name' => 'usersCategory create', 'role' => 'management'],
            // ['name' => 'usersCategory edit', 'role' => 'management'],
            // ['name' => 'usersCategory delete', 'role' => 'management'],


            //alternative
            // ['name' => 'alternative read', 'role' => 'management' , 'category' => 'management'],
            // ['name' => 'alternative create', 'role' => 'management' , 'category' => 'management'],
            // ['name' => 'alternative edit', 'role' => 'management' , 'category' => 'management'],
            // ['name' => 'alternative delete', 'role' => 'management' , 'category' => 'management'],






            //kayanPrice
            ['name' => 'kayanPrice read', 'role' => 'management'],
            ['name' => 'kayanPrice create', 'role' => 'management'],
            ['name' => 'kayanPrice edit', 'role' => 'management'],
            ['name' => 'kayanPrice delete', 'role' => 'management'],
            //sellingMethods
            // ['name' => 'sellingMethod read', 'role' => 'management'],
            // ['name' => 'sellingMethod create', 'role' => 'management'],
            // ['name' => 'sellingMethod edit', 'role' => 'management'],
            // ['name' => 'sellingMethod delete', 'role' => 'management'],
            //shift
            ['name' => 'shift read', 'role' => 'management'],
            ['name' => 'shift create', 'role' => 'management'],
            ['name' => 'shift edit', 'role' => 'management'],
            ['name' => 'shift delete', 'role' => 'management'],
            // start Store Management
            ['name' => 'store read', 'role' => 'StoreManagement', 'category' => 'Store Management'],
            ['name' => 'store create', 'role' => 'StoreManagement', 'category' => 'Store Management'],
            ['name' => 'store edit', 'role' => 'StoreManagement', 'category' => 'Store Management'],
            ['name' => 'store delete', 'role' => 'StoreManagement', 'category' => 'Store Management'],

            ['name' => 'examinationRecords read', 'role' => 'StoreManagement', 'category' => 'Store Management'],
            ['name' => 'examinationRecords create', 'role' => 'StoreManagement', 'category' => 'Store Management'],
            ['name' => 'examinationRecords edit', 'role' => 'StoreManagement', 'category' => 'Store Management'],

            ['name' => 'ProductsPricing read', 'role' => 'StoreManagement', 'category' => 'Store Management'],
            ['name' => 'ProductsPricing edit', 'role' => 'StoreManagement', 'category' => 'Store Management'],
            // end Store Management

            //stock
            ['name' => 'stock read', 'role' => 'management'],
            ['name' => 'stock create', 'role' => 'management'],
            ['name' => 'stock edit', 'role' => 'management'],
            ['name' => 'stock delete', 'role' => 'management'],




            //purchase
            // ['name' => 'purchase read', 'role' => 'management'],
            // ['name' => 'purchase create', 'role' => 'management'],
            // ['name' => 'purchase edit', 'role' => 'management'],
            // ['name' => 'purchase delete', 'role' => 'management'],
            //saleInvoice
            // ['name' => 'SaleInvoice read', 'role' => 'buy'],
            // ['name' => 'SaleInvoice create', 'role' => 'buy'],
            // ['name' => 'SaleInvoice edit', 'role' => 'buy'],
            // ['name' => 'SaleInvoice delete', 'role' => 'buy'],
            // ['name' => 'SaleReturn read', 'role' => 'buy'],
            //Sale Records
            ['name' => 'saleRecords read', 'role' => 'buy'],
            ['name' => 'saleRecords create', 'role' => 'buy'],
            ['name' => 'saleRecords edit', 'role' => 'buy'],
            //storage
            ['name' => 'storage read', 'role' => 'management'],
            ['name' => 'storage create', 'role' => 'management'],
            ['name' => 'storage edit', 'role' => 'management'],
            ['name' => 'storage delete', 'role' => 'management'],
            //complaint
            ['name' => 'complaint read', 'role' => 'management'],
            ['name' => 'complaint create', 'role' => 'management'],
            ['name' => 'complaint edit', 'role' => 'management'],
            ['name' => 'complaint delete', 'role' => 'management'],

            //financialReport
            ['name' => 'financialReport read', 'role' => 'management'],
            ['name' => 'financialReport create', 'role' => 'management'],
            ['name' => 'financialReport edit', 'role' => 'management'],
            ['name' => 'financialReport delete', 'role' => 'management'],
            //productReport
            ['name' => 'productReport read', 'role' => 'management'],
            ['name' => 'productReport create', 'role' => 'management'],
            ['name' => 'productReport edit', 'role' => 'management'],
            ['name' => 'productReport delete', 'role' => 'management'],
            //customerReport
            ['name' => 'customerReport read', 'role' => 'management'],
            ['name' => 'customerReport create', 'role' => 'management'],
            ['name' => 'customerReport edit', 'role' => 'management'],
            ['name' => 'customerReport delete', 'role' => 'management'],
            //supplierReport
            ['name' => 'supplierReport read', 'role' => 'management'],
            ['name' => 'supplierReport create', 'role' => 'management'],
            ['name' => 'supplierReport edit', 'role' => 'management'],
            ['name' => 'supplierReport delete', 'role' => 'management'],
            //stockReport
            ['name' => 'stockReport read', 'role' => 'management'],
            ['name' => 'stockReport create', 'role' => 'management'],
            ['name' => 'stockReport edit', 'role' => 'management'],
            ['name' => 'stockReport delete', 'role' => 'management'],
            //complaintReport
            ['name' => 'complaintReport read', 'role' => 'management'],
            ['name' => 'complaintReport create', 'role' => 'management'],
            ['name' => 'complaintReport edit', 'role' => 'management'],
            ['name' => 'complaintReport delete', 'role' => 'management'],
            //delegateReport
            ['name' => 'delegateReport read', 'role' => 'management'],
            ['name' => 'delegateReport create', 'role' => 'management'],
            ['name' => 'delegateReport edit', 'role' => 'management'],
            ['name' => 'delegateReport delete', 'role' => 'management'],
            //regionReport
            ['name' => 'regionReport read', 'role' => 'management'],
            ['name' => 'regionReport create', 'role' => 'management'],
            ['name' => 'regionReport edit', 'role' => 'management'],
            ['name' => 'regionReport delete', 'role' => 'management'],
            //purchaseReport
            ['name' => 'purchaseReport read', 'role' => 'management'],
            ['name' => 'purchaseReport create', 'role' => 'management'],
            ['name' => 'purchaseReport edit', 'role' => 'management'],
            ['name' => 'purchaseReport delete', 'role' => 'management'],
            //saleReport
            ['name' => 'saleReport read', 'role' => 'management'],
            ['name' => 'saleReport create', 'role' => 'management'],
            ['name' => 'saleReport edit', 'role' => 'management'],
            ['name' => 'saleReport delete', 'role' => 'management'],
            //adOwner
            ['name' => 'adOwner read', 'role' => 'management'],
            ['name' => 'adOwner create', 'role' => 'management'],
            ['name' => 'adOwner edit', 'role' => 'management'],
            ['name' => 'adOwner delete', 'role' => 'management'],

            //CRM
            // ['name' => 'targetPlan read', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'targetPlan create', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'targetPlan edit', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'targetPlan delete', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'SellerCategory read', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'SellerCategory create', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'SellerCategory edit', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'SellerCategory delete', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'LeadsManagement read', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'LeadsManagement create', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'LeadsManagement edit', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'LeadsManagement delete', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'LeadsManagement changeEmployee', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'Leads read', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'Leads create', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'Leads edit', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'Leads delete', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'TargetAchieved read', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'TargetAchieved create', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'TargetAchieved edit', 'role' => 'CRM', 'category' => 'CRM'],
            // ['name' => 'TargetAchieved delete', 'role' => 'CRM', 'category' => 'CRM'],


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

            //Employee
            ['name' => 'employee read', 'role' => 'management'],
            ['name' => 'employee create', 'role' => 'management'],
            ['name' => 'employee edit', 'role' => 'management'],
            ['name' => 'employee delete', 'role' => 'management'],
            ['name' => 'employeeChangePassword edit', 'role' => 'role-employee'],
            //Client Group
            // ['name' => 'client-group read', 'role' => ''],
            // ['name' => 'client-group create', 'role' => ''],
            // ['name' => 'client-group edit', 'role' => ''],
            // ['name' => 'client-group delete', 'role' => ''],
            //Client
            ['name' => 'client read', 'role' => ''],
            ['name' => 'client create', 'role' => ''],
            ['name' => 'client edit', 'role' => ''],
            ['name' => 'client delete', 'role' => ''],
            //Sales Points
            // ['name' => 'sale-point read', 'role' => ''],
            // ['name' => 'sale-point create', 'role' => ''],
            // ['name' => 'sale-point edit', 'role' => ''],
            // ['name' => 'sale-point delete', 'role' => ''],
            // //Purchase Invoice
            // ['name' => 'PurchaseInvoice read', 'role' => 'buy', 'category' => 'Purchase Invoice'],
            // ['name' => 'PurchaseInvoice create', 'role' => 'buy', 'category' => 'Purchase Invoice'],
            // ['name' => 'PurchaseInvoice edit', 'role' => 'buy', 'category' => 'Purchase Invoice'],
            // ['name' => 'PurchaseInvoice delete', 'role' => 'buy', 'category' => 'Purchase Invoice'],
            // //PurchaseReturn
            // ['name' => 'PurchaseReturn read', 'role' => 'buy', 'category' => 'Purchase Return'],
            // ['name' => 'PurchaseReturn create', 'role' => 'buy', 'category' => 'Purchase Return'],
            // ['name' => 'PurchaseReturn delete', 'role' => 'buy', 'category' => 'Purchase Return'],
            // //EarnedDiscount
            // ['name' => 'EarnedDiscount read', 'role' => 'buy', 'category' => 'Earned Discount'],
            // ['name' => 'EarnedDiscount create', 'role' => 'buy', 'category' => 'Earned Discount'],
            // ['name' => 'EarnedDiscount edit', 'role' => 'buy', 'category' => 'Earned Discount'],
            // ['name' => 'EarnedDiscount delete', 'role' => 'buy', 'category' => 'Earned Discount'],
            //Examination Records
            // ['name' => 'examinationRecords read', 'role' => 'buy'],
            // ['name' => 'examinationRecords create', 'role' => 'buy'],
            // ['name' => 'examinationRecords edit', 'role' => 'buy'],
            //Newsletters
            ['name' => 'newsletter read', 'role' => ''],

            //Simple advertises
            ['name' => 'simple-advertise read', 'role' => ''],
            ['name' => 'simple-advertise create', 'role' => ''],
            ['name' => 'simple-advertise edit', 'role' => ''],
            ['name' => 'simple-advertise delete', 'role' => ''],

            //Footer
            ['name' => 'footer read', 'role' => 'footer'],
            ['name' => 'footer edit', 'role' => 'footer'],

            //About
            ['name' => 'about read', 'role' => 'about'],
            ['name' => 'about edit', 'role' => 'about'],


            // end financial Accounts

            // // start order
            // ['name' => 'order read', 'role' => 'order', 'category' => 'order'],
            // ['name' => 'order create', 'role' => 'order', 'category' => 'order'],
            // ['name' => 'order edit', 'role' => 'order', 'category' => 'order'],
            // ['name' => 'order delete', 'role' => 'order', 'category' => 'order'],
            // ['name' => 'avg price product', 'role' => 'order', 'category' => 'order'],

            // ['name' => 'orderReturned read', 'role' => 'order', 'category' => 'order'],
            // ['name' => 'orderDelivered read', 'role' => 'order', 'category' => 'order'],
            // end order





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

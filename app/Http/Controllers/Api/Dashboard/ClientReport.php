<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\OrderStoreProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientReport extends Controller
{
    use Message;

    public function clientOldNew(Request $request)
    {
        $clients = Client::with('user')
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })
            ->where(function ($q) use ($request) {
                $q->when($request->password, function ($q) use ($request) {
                    $q->where('platform_type','=', $request->password);
                });
            })
            ->paginate(10);

        return $this->sendResponse(['clients' => $clients], 'Data exited successfully');
    }



    public function clientQty(Request $request)
    {
        $order = $request->order == 'asc' ? 'asc' : 'desc';
        $clients = SaleProduct::
            join('sales','sales.id','sale_products.sale_id')
            ->join('clients','clients.id','sales.client_id')
            ->join('users','users.id','clients.user_id')
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('sale_products.created_at', ">=", $request->from_date)
                    ->whereDate('sale_products.created_at', "<=", $request->to_date);
                });
            })->select(DB::raw('SUM(quantity) as amount'), 'users.id', 'users.name', 'users.phone')
            ->groupBy(['users.id', 'users.name', 'users.phone'])
            ->orderBy('amount', $order)
            ->take(10)
            ->get();

        return $this->sendResponse(['clients' => $clients], 'Data exited successfully');
    }



    public function clientPrice(Request $request)
    {
        $order = $request->order == 'asc' ? 'asc' : 'desc';
        $clients = Sale::
            join('clients', 'clients.id', 'sales.client_id')
            ->join('users', 'users.id', 'clients.user_id')
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('sales.created_at', ">=", $request->from_date)
                    ->whereDate('sales.created_at', "<=", $request->to_date);
                });
            })
            ->select(DB::raw('SUM(price) as amount'), 'users.id', 'users.name', 'users.phone')
            ->groupBy(['users.id', 'users.name', 'users.phone'])
            ->orderBy('amount', $order)
            ->take(10)
            ->get();

        return $this->sendResponse(['clients' => $clients], 'Data exited successfully');
    }

}

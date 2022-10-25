<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SaleProduct;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryReportController extends Controller
{
    use Message;

    public function product(Request $request)
    {
        $order = $request->order == 'asc' ? 'asc' : 'desc';
        $products = SaleProduct::
        select(DB::raw('SUM(quantity) as amount'),DB::raw('product_id'))
        ->groupBy('product_id')->orderBy('amount',$order)
        ->with('product')
        ->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', ">=", $request->from_date)
                    ->whereDate('created_at', "<=", $request->to_date);
            });
        })
        ->take(10)
        ->get();

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }



    public function category(Request $request)
    {
        $order = $request->order == 'asc' ? 'asc' : 'desc';
        $categories = SaleProduct::
            select(DB::raw('SUM(quantity) as amount'),DB::raw('product_id'))
            ->groupBy('product_id')->orderBy('amount',$order)
            ->with(['product' => function ($q) {
                $q->with('subCategory:id,name')
                    ->with('category:id,name');
            }])
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })
            ->take(10)
            ->get();

        return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
    }
}


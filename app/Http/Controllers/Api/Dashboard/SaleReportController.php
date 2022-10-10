<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Traits\Message;
use Illuminate\Http\Request;

class SaleReportController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->from_date != null && $request->to_date != null)
        {
            $sales = Sale::with(['saleProducts', 'batches', 'store', 'client', 'saleRecord', 'saleReturns'])
            // ->where(function ($q) use ($request) {
            //     $q->when($request->search, function ($q) use ($request) {
            //         $q->where('kind', 'like', '%' . $request->search . '%')
            //         ->orWhere('type', 'like', '%' . $request->search . '%')
            //         ->orWhere('payment_method', 'like', '%' . $request->search . '%')
            //         ->orWhere('purchase_type', 'like', '%' . $request->search . '%')
            //         ->orWhere('price', 'like', '%' . $request->search . '%')
            //         ->orWhere('visa_percentage', 'like', '%' . $request->search . '%')
            //         ->orWhere('visa_value', 'like', '%' . $request->search . '%')
            //         ->orWhere('added_tax_percentage', 'like', '%' . $request->search . '%')
            //         ->orWhere('added_tax_value', 'like', '%' . $request->search . '%')
            //         ->orWhere('discount_value', 'like', '%' . $request->search . '%')
            //         ->orWhere('other_discounts', 'like', '%' . $request->search . '%')
            //         ->orWhere('transfer_price', 'like', '%' . $request->search . '%')
            //         ->orWhere('note', 'like', '%' . $request->search . '%')
            //         ->orWhereRelation('store', 'name', 'like', '%' . $request->search . '%')
            //         ->orWhereRelation('client', 'name', 'like', '%' . $request->search . '%');
            //     });
            // })
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })
            ->latest()->paginate(5);

            $total = Sale::where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })->sum('price');
            return $this->sendResponse(['sales' => $sales, 'total' => $total], 'Data exited successfully');
        }
    }


    public function saleReportByProduct(Request $request)
    {
        if ($request->product_id != null) {
            $saleProducts = SaleProduct::with(['product', 'sale', 'returnProducts', 'storeProducts'])
            ->where(function ($q) use ($request) {
                $q->when($request->product_id, function ($q) use ($request) {
                    $q->where('product_id', $request->product_id);
                });
            })
            ->latest()->paginate(5);
            $totalQuantity = SaleProduct::where(function ($q) use ($request) {
                $q->when($request->product_id, function ($q) use ($request) {
                    $q->where('product_id', $request->product_id);
                });
            })->sum('quantity');

            return $this->sendResponse(['saleProducts' => $saleProducts,/* 'totalPrice', $totalPrice,*/ 'totalQuantity'=> $totalQuantity], 'Data exited successfully');
        }
    }


    public function saleReportByCategory(Request $request)
    {
        if ($request->category_id != null) {
            $saleCategories = SaleProduct::with(['product', 'sale', 'returnProducts', 'storeProducts'])
            ->where(function ($q) use ($request) {
                $q->when($request->category_id, function ($q) use ($request) {
                    $q->whereRelation('product','category_id', $request->category_id);
                });
            })
            ->latest()->paginate(5);

            return $this->sendResponse(['saleCategories' => $saleCategories], 'Data exited successfully');
        }
    }


    public function saleReportByReturn(Request $request)
    {
        if($request->date_from != null && $request->date_to != null)
        {
            $saleReturns = ProductReturn::where(function ($q) use ($request) {
                $q->when($request->date_from && $request->date_to, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->date_from)
                        ->whereDate('created_at', "<=", $request->date_to);
                });
            })
            ->latest()->paginate(5);
            return $saleReturns;

            $totalReturnQuantity = ProductReturn::with(['saleProduct', 'product', 'saleRecords'])
            ->where(function ($q) use ($request) {
                $q->when($request->date_from && $request->date_to, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->date_from)
                        ->whereDate('created_at', "<=", $request->date_to);
                });
            })->sum('quantity');
            return $this->sendResponse(['saleReturns' => $saleReturns, 'totalReturnQuantity' => $totalReturnQuantity], 'Data exited successfully');
        }
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('status',1)->get();
        $categories = Category::where('status',1)->with('products')->get();
        return $this->sendResponse(['products' => $products, 'categories'=> $categories], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

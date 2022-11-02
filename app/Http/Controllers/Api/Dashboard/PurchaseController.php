<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\VirtualStock;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = Purchase::with(['purchaseProducts.product.mainMeasurementUnit','store','supplier'])
        ->where(function ($q) use ($request)
        {
            $q->when($request->search, function ($q) use ($request)
            {
                return $q->where('discount_percentage', 'like', '%' . $request->search . '%')
                ->orWhere('discount_value', 'like', '%' . $request->search . '%')
                ->orWhere('other_discounts', 'like', '%' . $request->search . '%')
                ->orWhere('	transfer_price', 'like', '%' . $request->search . '%')
                ->orWhere('	note', 'like', '%' . $request->search . '%')
                ->orWhere('	price', 'like', '%' . $request->search . '%')
                ->orWhereRelation('store','name','like','%'.$request->search.'%')
                ->orWhereRelation('supplier','name','like','%'.$request->search.'%');
            });
        })
        ->where(function ($q) use ($request)
        {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request)
            {
                $q->whereDate('created_at', ">=", $request->from_date)
                ->whereDate('created_at', "<=", $request->to_date);
            });
        })
        ->where(function ($q) use ($request)
        {
            $q->when($request->purchase_id, function ($q) use ($request)
            {
                $q->where('id', $request->purchase_id);
            });
        })->latest()->paginate(5);

        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
    }



    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $stores = Stock::get();
        $suppliers = Supplier::where('active',1)->get();
        $clients = Client::with('user')->get();

        return $this->sendResponse(['categories'=> $categories, 'stores'=>$stores, 'suppliers'=>$suppliers, 'clients'=>$clients], 'Data exited successfully');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            // Validator request
            $v = Validator::make($request->all(),
            [
                'stock_id' => 'required|integer|exists:stocks,id',
                'supplier_id' => 'required|integer|exists:suppliers,id',
                'discount_percentage' => 'nullable|numeric',
                'discount_value' => 'nullable|numeric',
                'other_discounts' => 'nullable|numeric',
                'transfer_price' => 'nullable|numeric',
                'note' => 'required|string|min:5',
                'price' => 'required|numeric',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.quantity' => 'required|integer',
                'product.*.count_unit' => 'required|integer',
                'product.*.price_before_discount' => 'required|numeric',
                'product.*.price_after_discount' => 'required|numeric|lte:product.*.price_before_discount',
                'product.*.production_date' => 'required|date',
                'product.*.expiry_date' => 'required|date|after:product.*.production_date',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            // $purchase = Purchase::create($request->all());
            $purchase = Purchase::create
            ([
                'stock_id'            => $request->stock_id,
                'supplier_id'         => $request->supplier_id,
                'discount_percentage' => $request->discount_percentage,
                'discount_value'      => $request->discount_value,
                'other_discounts'     => $request->other_discounts,
                'transfer_price'      => $request->transfer_price,
                'note'                => $request->note,
                'price'               => $request->price,
            ]);

            foreach ($request->product as $product)
            {
                PurchaseProduct::create
                ([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price_before_discount' => $product['price_before_discount'],
                    'price_after_discount' => $product['price_after_discount'],
                    'production_date' => $product['production_date'],
                    'expiry_date' => $product['expiry_date'],
                    'purchase_id' => $purchase['id'],
                    'count_unit' => $product['count_unit'],
                ]);

                // $virtualStockQuantitiy = VirtualStock::where('product_id', $product['product_id'])->find($purchase['stock_id']);
                // $virtualStockQuantitiy->update
                // ([
                //     'productQuantity' =>  intval($virtualStockQuantitiy->productQuantity) + intval($product['quantity'])
                // ]);
            }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }



    public function edit($id)
    {
        try
        {
            $purchase = Purchase::with(['purchaseProducts'=>function($q)
            {
                $q->with(['product' =>function($qu)
                {
                    $qu->with('mainMeasurementUnit','subMeasurementUnit');
                }]);
            }])->find($id);

            $categories = Category::where('status', 1)->get();
            $stores = Stock::get();
            $suppliers = Supplier::where('active',1)->get();

            return $this->sendResponse(['purchase' => $purchase,'categories' => $categories,'stores'=>$stores,'suppliers'=>$suppliers], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }



    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            DB::beginTransaction();
            // Validator request
            $v = Validator::make($request->all(),
            [
                'stock_id' => 'required|integer|exists:stocks,id',
                'supplier_id' => 'required|integer|exists:suppliers,id',
                'discount_percentage' => 'nullable|numeric',
                'discount_value' => 'nullable|numeric',
                'other_discounts' => 'nullable|numeric',
                'transfer_price' => 'nullable|numeric',
                'note' => 'required|string|min:5',
                'price' => 'required|numeric',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.quantity' => 'required|integer',
                'product.*.count_unit' => 'required|integer',
                'product.*.price_before_discount' => 'required|numeric',
                'product.*.price_after_discount' => 'required|numeric|lte:product.*.price_before_discount',
                'product.*.production_date' => 'required|date',
                'product.*.expiry_date' => 'required|date|after:product.*.production_date',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $purchase = Purchase::find($id);
            // $purchase->update($request->all());
            $purchase->update
            ([
                'stock_id'            => $request->stock_id,
                'supplier_id'         => $request->supplier_id,
                'discount_percentage' => $request->discount_percentage,
                'discount_value'      => $request->discount_value,
                'other_discounts'     => $request->other_discounts,
                'transfer_price'      => $request->transfer_price,
                'note'                => $request->note,
                'price'               => $request->price,
            ]);
            foreach ($purchase->purchaseProducts as $data)
            {
                $data->delete();
            }

            foreach ($request->product as $product)
            {
                PurchaseProduct::create
                ([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price_before_discount' => $product['price_before_discount'],
                    'price_after_discount' => $product['price_after_discount'],
                    'production_date' => $product['production_date'],
                    'expiry_date' => $product['expiry_date'],
                    'purchase_id' => $purchase['id'],
                    'count_unit' => $product['count_unit'],
                ]);

                // $virtualStockQuantitiy = VirtualStock::where('product_id', $product['product_id'])->find($purchase['stock_id']);
                // $virtualStockQuantitiy->update
                // ([
                //     'productQuantity' =>  intval($virtualStockQuantitiy->productQuantity) + intval($product['quantity'])
                // ]);
            }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase=Purchase::find($id);
        if ($purchase->purchaseReturns == null && $purchase->examinationRecord == null)
        {
            $purchase->delete();
            return $this->sendResponse([],'Deleted successfully');
        }
        else
        {
            return $this->sendError('ID is not exist');
        }
    }



    //start relations functions
    public function getSuppliers()
    {
        $suppliers = Supplier::where('active',1)->get();
        return $this->sendResponse(['suppliers' => $suppliers], 'Data exited successfully');
    }



    public function productPrice($id)
    {
        $productPrice = PurchaseProduct::where('product_id',$id)->select('price_after_discount')->latest()->get();
        return $this->sendResponse(['productPrice' => $productPrice], 'Data exited successfully');
    }



    public function getProducts()
    {
        $products = Product::where('status',1)->get();
        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }
}

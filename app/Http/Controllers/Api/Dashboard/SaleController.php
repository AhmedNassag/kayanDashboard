<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Client;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\Stock;
use App\Models\VirtualStock;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales = Sale::with(['saleProducts.product.mainMeasurementUnit','store','client.user'])
            ->where(function ($q) use ($request)
            {
                $q->when($request->search, function ($q) use ($request)
                {
                    return $q->Where('note', 'like', '%' . $request->search . '%')
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhereRelation('client.user','name','like','%'.$request->search.'%')
                    ->orWhereRelation('store','name','like','%'.$request->search.'%');
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
                $q->when($request->sale_id, function ($q) use ($request)
                {
                    $q->where('id', $request->sale_id);
                });
            })->latest()->paginate(5);

        $saleInvoices       = Sale::all();
        $taxSaleInvoices    = Sale::where('purchase_type','Tax')->get();
        $notTaxSaleInvoices = Sale::where('purchase_type','Not Tax')->get();

        return $this->sendResponse(['sales' => $sales,'saleInvoices'=> $saleInvoices, 'taxSaleInvoices' => $taxSaleInvoices, 'notTaxSaleInvoices' => $notTaxSaleInvoices], 'Data exited successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients    = Client::with('user')->get();
        $stores     = Stock::get();
        $categories = Category::where('status', 1)->get();
        return $this->sendResponse([ 'clients'=>$clients, 'stores'=>$stores,'categories'=> $categories], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
                'type'                            => 'required',
                'client_id'                       => 'required|integer|exists:clients,id',
                'stock_id'                        => 'required|integer|exists:stocks,id',
                'payment_method'                  => 'required',
                'purchase_type'                   => 'required',
                'price'                           => 'required|numeric',
                'visa_percentage'                 => 'required|numeric',
                'visa_value'                      => 'required|numeric',
                'added_tax_percentage'            => 'required|numeric',
                'added_tax_value'                 => 'required|numeric',
                'discount_percentage'             => 'nullable|numeric',
                'discount_value'                  => 'nullable|numeric',
                'other_discounts'                 => 'nullable|numeric',
                'transfer_price'                  => 'nullable|numeric',
                'note'                            => 'required|string|min:5',
                'product.*.product_id'            => 'required|integer|exists:products,id',
                'product.*.quantity'              => 'required|integer',
                'product.*.price_before_discount' => 'required|numeric',
                'product.*.price_after_discount'  => 'required|numeric|lte:product.*.price_before_discount',
                // 'batch.*.money'                   => 'required|integer',
                // 'batch.*.due_date'                => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $sale = Sale::create
            ([
                'type'                 => $request->type,
                'client_id'            => $request->client_id,
                'stock_id'             => $request->stock_id,
                'payment_method'       => $request->payment_method,
                'purchase_type'        => $request->purchase_type,
                'price'                => $request->price,
                'visa_percentage'      => $request->visa_percentage,
                'visa_value'           => $request->visa_value,
                'added_tax_percentage' => $request->added_tax_percentage,
                'added_tax_value'      => $request->added_tax_value,
                'discount_percentage'  => $request->discount_percentage,
                'discount_value'       => $request->discount_value,
                'other_discounts'      => $request->other_discounts,
                'transfer_price'       => $request->transfer_price,
                'note'                 => $request->note,
            ]);

            foreach ($request->product as $product)
            {
                // $virtualStockQuantitiy = VirtualStock::where('product_id', $product['product_id'])->find($sale['stock_id']);
                // if(intval($virtualStockQuantitiy->productQuantity) >= intval($product['quantity']))
                // {
                    SaleProduct::create
                    ([
                        'sale_id'               => $sale['id'],
                        'product_id'            => $product['product_id'],
                        'quantity'              => $product['quantity'],
                        'price_before_discount' => $product['price_before_discount'],
                        'price_after_discount'  => $product['price_after_discount'],
                    ]);

                    // $virtualStockQuantitiy->update
                    // ([
                    //     'productQuantity' =>  intval($virtualStockQuantitiy->productQuantity) - intval($product['quantity'])
                    // ]);
                // }
                // else
                // {
                //     return $this->sendError('الكمية المطلوبة غير متوفرة حالياً', []);
                // }
            }

            if($request->payment_method == 'Delay' && $request->batch)
            {
                foreach ($request->batch as $batch)
                {
                    if($batch['money'] != null && $batch['due_date'] != null)
                    {
                        Batch::create([
                            'sale_id'  => $sale['id'],
                            'money'    => $batch['money'],
                            'due_date' => $batch['due_date'],
                        ]);
                    }
                }
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
        try
        {
            $sale = Sale::with(['batches'])->with(['saleProducts'=>function($q)
            {
                $q->with(['product' =>function($qu)
                {
                    $qu->with('mainMeasurementUnit','subMeasurementUnit');
                }]);
            }])->find($id);
            $clients    = Client::with('user')->get();
            $stores     = Stock::get();
            $categories = Category::where('status', 1)->get();
            return $this->sendResponse(['sale' => $sale, 'clients' => $clients, 'stores'=>$stores, 'categories' => $categories], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
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
        try
        {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(),
            [
                'type'                            => 'required',
                'client_id'                       => 'required|integer|exists:clients,id',
                'stock_id'                        => 'required|integer|exists:stocks,id',
                'payment_method'                  => 'required',
                'purchase_type'                   => 'required',
                'price'                           => 'required|numeric',
                'visa_percentage'                 => 'required|numeric',
                'visa_value'                      => 'required|numeric',
                'added_tax_percentage'            => 'required|numeric',
                'added_tax_value'                 => 'required|numeric',
                'discount_percentage'             => 'nullable|numeric',
                'discount_value'                  => 'nullable|numeric',
                'other_discounts'                 => 'nullable|numeric',
                'transfer_price'                  => 'nullable|numeric',
                'note'                            => 'required|string|min:5',
                'product.*.product_id'            => 'required|integer|exists:products,id',
                'product.*.quantity'              => 'required|integer',
                'product.*.price_before_discount' => 'required|numeric',
                'product.*.price_after_discount'  => 'required|numeric|lte:product.*.price_before_discount',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $sale = Sale::find($id);

            $sale->update
            ([
                'type'                 => $request->type,
                'client_id'            => $request->client_id,
                'stock_id'             => $request->stock_id,
                'payment_method'       => $request->payment_method,
                'purchase_type'        => $request->purchase_type,
                'price'                => $request->price,
                'visa_percentage'      => $request->visa_percentage,
                'visa_value'           => $request->visa_value,
                'added_tax_percentage' => $request->added_tax_percentage,
                'added_tax_value'      => $request->added_tax_value,
                'discount_percentage'  => $request->discount_percentage,
                'discount_value'       => $request->discount_value,
                'other_discounts'      => $request->other_discounts,
                'transfer_price'       => $request->transfer_price,
                'note'                 => $request->note,
            ]);

            foreach ($sale->saleProducts as $data)
            {
                $data->delete();
            }

            foreach ($request->product as $product)
            {
                // $virtualStockQuantitiy = VirtualStock::where('product_id', $product['product_id'])->find($sale['stock_id']);
                // if (intval($virtualStockQuantitiy->productQuantity) >= intval($product['quantity']))
                // {
                    SaleProduct::create
                    ([
                        'sale_id'               => $sale['id'],
                        'product_id'            => $product['product_id'],
                        'quantity'              => $product['quantity'],
                        'price_before_discount' => $product['price_before_discount'],
                        'price_after_discount'  => $product['price_after_discount'],
                    ]);

                    // $virtualStockQuantitiy->update
                    // ([
                    //     'productQuantity' =>  intval($virtualStockQuantitiy->productQuantity) - intval($product['quantity'])
                    // ]);
                // }
                // else
                // {
                //     return $this->sendError('الكمية المطلوبة غير متوفرة حالياً', []);
                // }
            }

            //
            foreach ($sale->batches as $data)
            {
                $data->delete();
            }
            foreach ($request->batch as $batch)
            {
                if($batch['money'] != null && $batch['due_date'] != null)
                Batch::create
                ([
                    'sale_id'  => $sale['id'],
                    'money'    => $batch['money'],
                    'due_date' => $batch['due_date'],
                ]);
            }
            //

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale=Sale::find($id);
        if ($sale->saleReturns == null && $sale->saleRecord == null)
        {
            $sale->delete();
            return $this->sendResponse([],'Deleted successfully');
        }
        else
        {
            return $this->sendError('ID is not exist');
        }
    }



    public function getClientBalance($id)
    {
        $clientBalance = Sale::Where('client_id',$id)->sum('price');
        // $clientBalance = sum($Balance->price);
        return $this->sendResponse(['clientBalance' => $clientBalance], 'Data exited successfully');
    }
}

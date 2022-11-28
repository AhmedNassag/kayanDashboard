<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\VirtualStock;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Imports\VirtualStocksImport;
use App\Imports\VirtualStocksAlternativeImport;
use App\Models\AlternativePrice;
use Maatwebsite\Excel\Facades\Excel;

class VirtualStockController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $virtualStocks = Supplier::where(['active'=>1, 'is_our_supplier'=>0])
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['virtualStocks' => $virtualStocks], 'Data exited successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(),
            [
                'product.*.supplier_id'     => 'required',
                'product.*.product_id'      => 'required',
                'product.*.quantity'        => 'required',
                'product.*.publicPrice'     => 'required',
                'product.*.clientDiscount'  => 'required',
                'product.*.kayanDiscount'   => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            foreach ($request->product as $product)
            {
                if(!Price::where('supplier_id', $product['supplier_id'])->where('product_id', $product['product_id'])->first()){
                    Price::create([
                            'supplier_id'     => $product['supplier_id'],
                            'product_id'      => $product['product_id'],
                            'quantity'        => $product['quantity'],
                            'publicPrice'     => $product['publicPrice'],
                            'clientDiscount'  => $product['clientDiscount'],
                            'kayanDiscount'   => $product['kayanDiscount'],
                            'pharmacyPrice'   => $product['publicPrice'] - ($product['publicPrice'] * ($product['clientDiscount'] / 100)),
                            'kayanProfit'     => $product['kayanDiscount'] - $product['clientDiscount'],
                        ]);
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
    public function show(Request $request, $id)
    {
        $virtualStocks = Price::where('supplier_id',$id)->with('product:id,nameAr', 'supplier:id,name')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('publicPrice', 'like', '%' . $request->search . '%')
            ->orWhereRelation('product', 'nameAr', 'like', '%' . $request->search . '%')
            ->orWhereRelation('supplier', 'name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['virtualStocks' => $virtualStocks], 'Data exited successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // try
        // {
        //     $virtualStock   = VirtualStock::with('product')->with('category')->with('subCategory')->with('supplier')->find($id);
        //     $products       = Product::where('status',1)->get();
        //     $suppliers      = Supplier::where('active',1)->select('id','name')->get();
        //     $categories     = Category::where('status',1)->select('id','name')->get();
        //     return $this->sendResponse
        //     ([
        //         'virtualStock' => $virtualStock,
        //         'products'     => $products,
        //         'suppliers'    => $suppliers,
        //         'categories'   => $categories,
        //     ], 'Data exited successfully');
        // }
        // catch (\Exception $e)
        // {
        //     return $this->sendError('An error occurred in the system');
        // }
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
        DB::beginTransaction();
        try {

            $virtualStock = VirtualStock::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'productQuantity' => 'required',
                // 'pharmacyPrice' => 'required',
                'publicPrice' => 'required',
                'pharmacyDiscount' => 'required',
                'kayanDiscount' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'product_id' => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['productQuantity','pharmacyPrice','publicPrice','pharmacyDiscount','kayanDiscount','category_id','sub_category_id','product_id']);
            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->pharmacyDiscount/100));
            $virtualStock->update($data);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
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
        try
        {
            $virtualStock = VirtualStock::find($id);
            if ($virtualStock)
            {
                $virtualStock->delete();
                return $this->sendResponse([],'Deleted successfully');
            }
            else
            {
                return $this->sendError('ID is not exist');
            }
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }



    public function virtualStockAlternative(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make(
                $request->all(),
                [
                    'alternative.*.supplier_id'     => 'required',
                    'alternative.*.alternative_id'  => 'required',
                    'alternative.*.quantity'        => 'required',
                    'alternative.*.publicPrice'     => 'required',
                    'alternative.*.clientDiscount'  => 'required',
                    'alternative.*.kayanDiscount'   => 'required',
                ]
            );

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            foreach ($request->alternative as $alternative) {
                AlternativePrice::create([
                        'supplier_id'     => $alternative['supplier_id'],
                        'alternative_id'  => $alternative['alternative_id'],
                        'quantity'        => $alternative['quantity'],
                        'publicPrice'     => $alternative['publicPrice'],
                        'clientDiscount'  => $alternative['clientDiscount'],
                        'kayanDiscount'   => $alternative['kayanDiscount'],
                        'pharmacyPrice'   => $alternative['publicPrice'] - ($alternative['publicPrice'] * ($alternative['clientDiscount'] / 100)),
                        'kayanProfit'     => $alternative['kayanDiscount'] - $alternative['clientDiscount'],
                    ]);
            }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    public function saveExcelVirtualStock(Request $request)
    {
        $path = $request->file('select_virtualStocks_file')->getRealPath();
        Excel::import(new VirtualStocksImport, $path);
    }


    public function saveExcelVirtualStockAlternative(Request $request)
    {
        $path = $request->file('select_virtualStocks_file')->getRealPath();
        Excel::import(new VirtualStocksAlternativeImport, $path);
    }

    public function purchaseInvoiceProduct(Request $request)
    {
        $products = Product::
        whereDoesntHave('prices',function($q) use($request){
            $q->where('supplier_id',$request->supplier_id);
        })->
        where([
            ['status', 1],
            ['category_id', $request->category_id],
            ['sub_category_id', $request->sub_category_id]
        ])
        ->with('mainMeasurementUnit', 'subMeasurementUnit')
        ->get();

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }
}

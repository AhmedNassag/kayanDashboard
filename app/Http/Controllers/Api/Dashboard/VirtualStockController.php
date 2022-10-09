<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\VirtualStock;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $virtualStocks = VirtualStock::with('product.productName')->with('category')->with('subCategory')->with('supplier')
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
            $v = Validator::make($request->all(), [
                'productQuantity' => 'required',
                // 'pharmacyPrice' => 'required',
                'publicPrice' => 'required',
                'pharmacyDiscount' => 'required',
                'kayanDiscount' => 'required',
                'supplier_id' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'product_id' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['productQuantity','pharmacyPrice','publicPrice','pharmacyDiscount','kayanDiscount','category_id','sub_category_id','product_id','supplier_id']);
            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->pharmacyDiscount/100));
            $virtualStock = VirtualStock::create($data);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
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
            $virtualStock = VirtualStock::with('product.productName')->with('category')->with('subCategory')->with('supplier')->find($id);
            $products       = Product::with('productName')->get();
            $suppliers      = Supplier::select('id','name')->get();
            $categories     = Category::select('id','name')->get();
            return $this->sendResponse
            ([
                'virtualStock' => $virtualStock,
                'products'     => $products,
                'suppliers'    => $suppliers,
                'categories'   => $categories,
            ], 'Data exited successfully');
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

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['productQuantity','pharmacyPrice','publicPrice','pharmacyDiscount','kayanDiscount','category_id','sub_category_id','product_id']);
            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->pharmacyDiscount/100));
            $virtualStock->update($data);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

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
}

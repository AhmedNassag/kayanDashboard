<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\KayanPrice;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KayanPriceController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kayanPrices = KayanPrice::with('product', 'supplier:id,name', 'category:id,name', 'subCategory:id,name')
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', "%" . $request->search . "%");
            })->latest()->paginate(15);

        return $this->sendResponse(['kayanPrices' => $kayanPrices], 'Data exited successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try
        {
            $products   = Product::where('status',1)->get();
            $suppliers  = Supplier::where('active',1)->select('id', 'name')->get();
            $categories = Category::where('status',1)->select('id', 'name')->get();

            return $this->sendResponse([
                'products'   => $products,
                'suppliers'  => $suppliers,
                'categories' => $categories,
            ], 'Data exited successfully');

        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
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
            $v = Validator::make($request->all(), [
                'product_id'        => 'required|integer|exists:products,id',
                'category_id'       => 'required|integer|exists:categories,id',
                'sub_category_id'   => 'required|integer|exists:sub_categories,id',
                'pharmacyPrice'     => 'required',
                'publicPrice'       => 'required',
                'productDiscount'   => 'required',
                'collectionPrice'   => 'required',
                'partialPrice'      => 'required',
                'destributionPrice' => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id', 'category_id', 'sub_category_id', 'maximumLimit', 'reOrderLimit', 'pharmacyPrice', 'category_id', 'sub_category_id', 'company_id', 'supplier_id', 'pharmacyPrice', 'publicPrice', 'productDiscount', 'collectionPrice','partialPrice','destributionPrice','supplier_id'/*, 'company_id'*/]);

            $productId    = Product::where('id',$request->product_id)->where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->get()->last();
            $productPrice = PurchaseProduct::where('product_id',$productId->id)->get('price_after_discount')->last();
            // if(!$productPrice->price_after_discount){
            //     return response()->json(["error"=>"ay kalam"],400);
            //     // return $this->sendError('No Price');
            // }
            $data['productPrice'] = $productPrice->price_after_discount;

            $data['collectionKayanProfit']             = ($data['collectionPrice'] - $data['productPrice']);
            $data['partialKayanProfit']                = ($data['partialPrice'] - $data['productPrice']);
            $data['destributionKayanProfit']           = ($data['destributionPrice'] - $data['productPrice']);

            $data['averagePrice']                      = ($data['collectionPrice'] + $data['partialPrice'] + $data['destributionPrice']) / 3 ;

            $data['collectionPercentageKayanProfit']   = ($data['collectionKayanProfit'] / $data['productPrice']) * 100 ;
            $data['partialPercentageKayanProfit']      = ($data['partialKayanProfit'] / $data['productPrice']) * 100 ;
            $data['destributionPercentageKayanProfit'] = ($data['destributionKayanProfit'] / $data['productPrice']) * 100 ;

            $kayaPrice = KayanPrice::create($data);

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
        try {
            $kayanPrice = KayanPrice::find($id);
            $products   = Product::where('status',1)->get();
            $suppliers  = Supplier::where('active',1)->select('id', 'name')->get();
            $categories = Category::where('status',1)->select('id', 'name')->get();

            return $this->sendResponse([
                'kayanPrice' => $kayanPrice,
                'products'   => $products,
                'suppliers'  => $suppliers,
                'categories' => $categories,
            ], 'Data exited successfully');

        } catch (\Exception $e) {
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

            $kayanPrice = KayanPrice::find($id);

            // Validator request
            $v = Validator::make($request->all(),
            [
                'product_id'        => 'required|integer|exists:products,id',
                'category_id'       => 'required|integer|exists:categories,id',
                'sub_category_id'   => 'required|integer|exists:sub_categories,id',
                'pharmacyPrice'     => 'required',
                'publicPrice'       => 'required',
                'productDiscount'   => 'required',
                'collectionPrice'   => 'required',
                'partialPrice'      => 'required',
                'destributionPrice' =>'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id', 'category_id', 'sub_category_id', 'supplier_id', 'maximumLimit', 'reOrderLimit', 'pharmacyPrice', 'category_id', 'sub_category_id', 'company_id', 'supplier_id', 'pharmacyPrice', 'publicPrice', 'productDiscount', 'collectionPrice','partialPrice','destributionPrice']);

            $productId    = Product::where('id',$request->product_id)->where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->get()->last();
            $productPrice = PurchaseProduct::where('product_id',$productId->id)->get('price_after_discount')->last();

            $data['productPrice'] = $productPrice->price_after_discount;

            $data['collectionKayanProfit']             = ($data['collectionPrice'] - $data['productPrice']);
            $data['partialKayanProfit']                = ($data['partialPrice'] - $data['productPrice']);
            $data['destributionKayanProfit']           = ($data['destributionPrice'] - $data['productPrice']);

            $data['averagePrice']                      = ($data['collectionPrice'] + $data['partialPrice'] + $data['destributionPrice']) / 3 ;

            $data['collectionPercentageKayanProfit']   = ($data['collectionKayanProfit'] / $data['productPrice']) * 100 ;
            $data['partialPercentageKayanProfit']      = ($data['partialKayanProfit'] / $data['productPrice']) * 100 ;
            $data['destributionPercentageKayanProfit'] = ($data['destributionKayanProfit'] / $data['productPrice']) * 100 ;

            $kayanPrice->update($data);

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
            $kayanPrice = KayanPrice::find($id);
            if ($kayanPrice)
            {
                $kayanPrice->delete();
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

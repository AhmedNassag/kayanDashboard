<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealRequest;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductLog;
use App\Models\Supplier;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PriceController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $prices = Price::with('product:id,nameAr', 'supplier:id,name')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('publicPrice', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('product', 'nameAr', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('supplier', 'name', 'like', '%' . $request->search . '%');
                });
            })->orWhere(function ($q) use ($request) {
                $q->when($request->filter_best_offers == 'best_offers', function ($q) use ($request) {
                    return $q->where('best_offer', 1);
                });
            })->latest()->paginate(20);
        return $this->sendResponse(['prices' => $prices], 'Data exited successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $products     = Product::where('status', 1)->get();
            $suppliers    = Supplier::where('active', 1)->select('id', 'name')->get();
            $categories   = Category::where('status', 1)->select('id', 'name')->get();

            return $this->sendResponse([
                'products'   => $products,
                'suppliers'  => $suppliers,
                'categories' => $categories,
            ], 'Data exited successfully');
        } catch (\Exception $e) {
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
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'product_id'     => 'required|integer|exists:products,id',
                'category_id'    => 'required|integer|exists:categories,id',
                'sub_category_id' => 'required|integer|exists:sub_categories,id',
                'supplier_id'    => 'required|integer|exists:suppliers,id',
                // 'pharmacyPrice'  => 'required',
                'publicPrice'    => 'required',
                'clientDiscount' => 'required',
                'kayanDiscount'  => 'required',
                // 'kayanProfit'    => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id', 'category_id', 'sub_category_id', 'pharmacyPrice', 'category_id', 'sub_category_id', 'company_id', 'supplier_id', 'pharmacyPrice', 'publicPrice', 'clientDiscount', 'kayanDiscount', 'supplier_id', /*'company_id', 'kayanProfit'*/]);

            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->clientDiscount / 100));

            $data['kayanProfit'] = $data['kayanDiscount'] - $data['clientDiscount'];

            $price = Price::create($data);


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
        try {
            $price          = Price::find($id);
            $products       = Product::where('status', 1)->get();
            $suppliers      = Supplier::where('active', 1)->select('id', 'name')->get();
            $categories     = Category::where('status', 1)->select('id', 'name')->get();

            return $this->sendResponse([
                'price'               => $price,
                'products'            => $products,
                'suppliers'           => $suppliers,
                'categories'          => $categories,
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

            $price = Price::find($id);

            // Validator request
            $v = Validator::make(
                $request->all(),
                [
                    'product_id'     => 'required|integer|exists:products,id',
                    // 'category_id'    => 'required|integer|exists:categories,id',
                    // 'sub_category_id' => 'required|integer|exists:sub_categories,id',
                    'supplier_id'    => 'required|integer|exists:suppliers,id',
                    // 'pharmacyPrice'  => 'required',
                    'quantity' => 'required',
                    'publicPrice'    => 'required',
                    'clientDiscount' => 'required',
                    'kayanDiscount'  => 'required',
                    // 'kayanProfit'    => 'required',
                ]
            );

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id', 'supplier_id', 'quantity', 'pharmacyPrice', 'publicPrice', 'clientDiscount', 'kayanDiscount', 'kayanProfit']);

            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->clientDiscount / 100));

            $data['kayanProfit'] = $data['kayanDiscount'] - $data['clientDiscount'];

            $old_qty = $price->quantity; //befor update quantity
            $price->update($data);

            $this->store_in_product_logs($price->quantity - $old_qty, $price->quantity,
                $price->pharmacyPrice,
                $price->publicPrice,
                $price->clientDiscount,
                $price->kayanDiscount,
                $price->kayanProfit,
                $price->id,
                $old_qty,
            );

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
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
        try {
            $price = Price::find($id);
            if ($price) {
                $price->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }


    //General settings of deal
    public function insertDealSettings(DealRequest $request)
    {
        $deal = Deal::first();
        $dealInput = $request->validated();
        if ($deal) {
            $deal->end_at = $dealInput["end_at"];
            $deal->limit = $dealInput["limit"];
            $deal->save();
        } else {
            Deal::create($dealInput);
        }
    }

    public function getDealSettings()
    {
        return Deal::first();
    }

    public function markProductAsBestOffer(Request $request)
    {
        $product_price = Price::findOrFail($request->product_id);
        $best_offers_count = Price::where('best_offer', 1)->count();
        $deat_limit = Deal::first()->limit;
        if ($product_price->best_offer == 1 || $deat_limit > $best_offers_count) {
            $product_price->update(['best_offer' => $product_price->best_offer == 0 ? 1 : 0]);
        } else {
            return response()->json(['limit' => $deat_limit], 404);
        }
    }


    public function store_in_product_logs($diff_qty, $total_qty, $pharmacyPrice, $publicPrice, $clientDiscount, $kayanDiscount, $kayanProfit,$price_id,$old_qty)
    {
        $latest_log = ProductLog::where('price_id',$price_id)->latest()->first();
        $latest_log->update(['sold_quantity' => $latest_log->total_qty- $old_qty]);
        ProductLog::create([
            'diff_qty' => $diff_qty,
            'total_qty' => $total_qty,
            'pharmacyPrice' => $pharmacyPrice,
            'publicPrice' => $publicPrice,
            'clientDiscount' => $clientDiscount,
            'kayanDiscount' => $kayanDiscount,
            'kayanProfit' => $kayanProfit,
            'price_id' => $price_id,
        ]);
    }
}

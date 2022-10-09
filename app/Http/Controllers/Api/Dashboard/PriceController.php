<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Price;
use App\Models\Product;
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
        $prices = Price::with('product:id,nameAr', 'supplier:id,name', 'category:id,name','subCategory:id,name'/*, 'company:id,name'*/)
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', "%" . $request->search . "%");
            })->latest()->paginate(15);

        return $this->sendResponse(['prices' => $prices], 'Data exited successfully');
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
            $products     = Product::get();
            $suppliers    = Supplier::select('id', 'name')->get();
            // $companies    = Company::select('id', 'name')->get();
            $categories   = Category::select('id', 'name')->get();

            return $this->sendResponse([
                'products'   => $products,
                'suppliers'  => $suppliers,
                // 'companies'  => $companies,
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
                'product_id'     => 'required|integer|exists:products,id',
                'category_id'    => 'required|integer|exists:categories,id',
                'sub_category_id' => 'required|integer|exists:sub_categories,id',
                'supplier_id'    => 'required|integer|exists:suppliers,id',
                // 'company_id'     => 'required|integer|exists:companies,id',
                // 'pharmacyPrice'  => 'required',
                'publicPrice'    => 'required',
                'clientDiscount' => 'required',
                'kayanDiscount'  => 'required',
                // 'kayanProfit'    => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id', 'category_id', 'sub_category_id', 'pharmacyPrice', 'category_id', 'sub_category_id', 'company_id', 'supplier_id', 'pharmacyPrice', 'publicPrice', 'clientDiscount','kayanDiscount', 'supplier_id', /*'company_id', 'kayanProfit'*/]);

            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->clientDiscount / 100));

            $data['kayanProfit'] = $data['kayanDiscount'] - $data['clientDiscount'];

            // if ($data['company_id'] != "null")
            // {
            //     $data['supplier_id'] = null;
            // }
            // else
            // {
            //     $data['company_id'] = null;
            // }

            $price = Price::create($data);

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
            $price          = Price::find($id);
            $products       = Product::get();
            $suppliers      = Supplier::select('id','name')->get();
            // $companies      = Company::select('id','name')->get();
            $categories     = Category::select('id','name')->get();

            return $this->sendResponse([
                'price'               => $price,
                'products'            => $products,
                'suppliers'           => $suppliers,
                // 'companies'           => $companies,
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
            $v = Validator::make($request->all(),
            [
                'product_id'     => 'required|integer|exists:products,id',
                'category_id'    => 'required|integer|exists:categories,id',
                'sub_category_id' => 'required|integer|exists:sub_categories,id',
                'supplier_id'    => 'required|integer|exists:suppliers,id',
                // 'company_id'     => 'required|integer|exists:companies,id',
                // 'pharmacyPrice'  => 'required',
                'publicPrice'    => 'required',
                'clientDiscount' => 'required',
                'kayanDiscount'  => 'required',
                // 'kayanProfit'    => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id', 'category_id', 'sub_category_id', 'pharmacyPrice', 'category_id', 'sub_category_id', 'company_id', 'supplier_id', 'pharmacyPrice', 'publicPrice', 'clientDiscount', 'kayanDiscount','kayanProfit', 'supplier_id'/*, 'company_id'*/]);

            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->clientDiscount / 100));

            $data['kayanProfit'] = $data['kayanDiscount'] - $data['clientDiscount'];

            // if ($data['company_id'] != "null")
            // {
            //     $data['supplier_id'] = null;
            // }
            // else
            // {
            //     $data['company_id'] = null;
            // }

            $price->update($data);

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
            $price = Price::find($id);
            if ($price)
            {
                $price->delete();
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

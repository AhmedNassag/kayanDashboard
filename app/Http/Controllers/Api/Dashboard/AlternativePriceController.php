<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AlternativePrice;
use App\Models\Category;
use App\Models\Price;
use App\Models\Supplier;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AlternativePriceController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $alternativePrices = AlternativePrice::with('alternative:id,nameAr', 'supplier:id,name')
            ->when($request->search, function ($q) use ($request) {
                return $q->where('publicPrice', 'like', '%' . $request->search . '%')
                ->orWhereRelation('alternative', 'nameAr', 'like', '%' . $request->search . '%')
                ->orWhereRelation('supplier', 'name', 'like', '%' . $request->search . '%');
            })->latest()->paginate(10);

        return $this->sendResponse(['alternativePrices' => $alternativePrices], 'Data exited successfully');
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
            $alternatives = Alternative::where('status',1)->get();
            $suppliers    = Supplier::where('active',1)->select('id', 'name')->get();
            $categories   = Category::where('status',1)->select('id', 'name')->get();

            return $this->sendResponse([
                'alternatives' => $alternatives,
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
                'alternative_id'  => 'required|integer|exists:alternatives,id',
                // 'category_id'     => 'required|integer|exists:categories,id',
                // 'sub_category_id' => 'required|integer|exists:sub_categories,id',
                'supplier_id'     => 'required|integer|exists:suppliers,id',
                // 'pharmacyPrice'  => 'required',
                'publicPrice'     => 'required',
                'clientDiscount'  => 'required',
                'kayanDiscount'   => 'required',
                // 'kayanProfit'    => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['alternative_id',/* 'category_id', 'sub_category_id',*/ 'pharmacyPrice', 'supplier_id', 'pharmacyPrice', 'publicPrice', 'clientDiscount','kayanDiscount', 'supplier_id', /*'kayanProfit'*/]);

            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->clientDiscount / 100));

            $data['kayanProfit'] = $data['kayanDiscount'] - $data['clientDiscount'];

            $alternativePrice = AlternativePrice::create($data);

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
            $alternativePrice = AlternativePrice::find($id);
            $alternatives     = Alternative::where('status',1)->get();
            $suppliers        = Supplier::where('active',1)->select('id','name')->get();
            $categories       = Category::where('status',1)->select('id','name')->get();

            return $this->sendResponse([
                'alternativePrice'    => $alternativePrice,
                'alternatives'        => $alternatives,
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

            $alternativePrice = AlternativePrice::find($id);

            // Validator request
            $v = Validator::make($request->all(),
            [
                'alternative_id'  => 'required|integer|exists:alternatives,id',
                // 'category_id'     => 'required|integer|exists:categories,id',
                // 'sub_category_id' => 'required|integer|exists:sub_categories,id',
                'supplier_id'     => 'required|integer|exists:suppliers,id',
                // 'pharmacyPrice'  => 'required',
                'publicPrice'     => 'required',
                'clientDiscount'  => 'required',
                'kayanDiscount'   => 'required',
                // 'kayanProfit'    => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['alternative_id',/* 'category_id', 'sub_category_id',*/ 'pharmacyPrice', 'supplier_id', 'pharmacyPrice', 'publicPrice', 'clientDiscount', 'kayanDiscount','kayanProfit']);

            $data['pharmacyPrice'] = $request->publicPrice - ($request->publicPrice * ($request->clientDiscount / 100));

            $data['kayanProfit'] = $data['kayanDiscount'] - $data['clientDiscount'];

            $alternativePrice->update($data);

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
            $alternativePrice = AlternativePrice::find($id);
            if ($alternativePrice)
            {
                $alternativePrice->delete();
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

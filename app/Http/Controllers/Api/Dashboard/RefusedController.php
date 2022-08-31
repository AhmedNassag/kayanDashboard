<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Refuse;
use Illuminate\Http\Request;
use App\Models\Refused;
use App\Models\Stock;
use App\Traits\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RefusedController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $refuseds = Refused::with('category')->with('supplier')->with('product')->with('stock')
            ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        foreach($refuseds as $refused)
        {
            $refused->setAttribute('added_at',$refused->created_at->format('Y-m-d'));
        }

        return $this->sendResponse(['refuseds' => $refuseds], 'Data exited successfully');
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
                'refusedQuantity' => 'required',
                'refusedReason' => 'required',
                'note' => 'required',
                'discountPercentage' => 'required',
                'discountValue' => 'required',
                'anotherDiscount' => 'required',
                'total' => 'required',
                'category_id' => 'required',
                'supplier_id' => 'required',
                'product_id' => 'required',
                'stock_id' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['refusedQuantity','refusedReason','note','discountPercentage','discountValue','anotherDiscount','total','category_id','supplier_id','product_id','stock_id']);
            $data['code'] = rand();
            $refused = Refused::create($data);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        }
        catch (\Exception $e) {
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

            $refused = Refused::with('category')->with('supplier')->with('product')->with('stock')->find($id);

            return $this->sendResponse(['refused' => $refused], 'Data exited successfully');

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

            $refused = Refused::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'refusedQuantity' => 'required',
                'refusedReason' => 'required',
                'note' => 'required',
                'discountPercentage' => 'required',
                'discountValue' => 'required',
                'anotherDiscount' => 'required',
                'total' => 'required',
                'category_id' => 'required',
                'supplier_id' => 'required',
                'product_id' => 'required',
                'stock_id' => 'required',
            ]);


            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['refusedQuantity','refusedReason','note','discountPercentage','discountValue','anotherDiscount','total','category_id','supplier_id','product_id','stock_id']);

            $refused->update($data);

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
        try {
            $refused = Refused::find($id);
            if ($refused){
                $refused->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }


    //start relations functions
    public function getStocks()
    {
        $stocks = Stock::all();
        return $this->sendResponse(['stocks' => $stocks], 'Data exited successfully');
    }
}

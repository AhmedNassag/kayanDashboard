<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ExaminationRecord;
use App\Models\ProductStatus;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\ReturnProduct;
use App\Models\StoreProduct;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExaminationRecordController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = Purchase::with(['purchaseProducts.product'=>function($qu){
            $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
        },'store','supplier','examinationRecord'=>function($qu){
            $qu->with(['storeProducts','user']);
        },'purchaseReturns.returnProducts'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('	note', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('store','name','like','%'.$request->search.'%')
                        ->orWhereRelation('supplier','name_supplier','like','%'.$request->search.'%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->purchase_id, function ($q) use ($request) {
                    $q->where('id', $request->purchase_id);
                });
            })->latest()->paginate(5);

        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
    }

    public function create(){
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'purchase_id' => 'required|integer|exists:purchases,id',
                'stock_id' => 'required|integer|exists:stocks,id',
                'supplier_id' => 'required|integer|exists:suppliers,id',
                'notes_received' => 'nullable|string|min:5',
                'notes_return' => 'nullable|string|min:5',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.product_status_id' => 'required|integer|exists:product_statuses,id',
                'product.*.quantity_received' => 'required|integer',
                'product.*.return_quantity' => 'required|integer',
                'product.*.count_unit' => 'required|integer',
                'product.*.production_date' => 'required|date',
                'product.*.expiry_date' => 'required|date|after:product.*.production_date',
                'product.*.note' => 'nullable|string|min:3',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            if ($request->received > 0){
               $this->storeProduct($request);
            }
            if ($request->return > 0){
               $this->purchaseReturn($request);
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    public function storeProduct($request){

        $employee_id = auth()->user()->id;

        $examination = ExaminationRecord::create([
            'user_id'=>$employee_id,
            'purchase_id'=>$request->purchase_id,
            'note'=>$request->notes_received
        ]);

        foreach ($request->product as $value){
            if ($value['quantity_received'] > 0 ){
                StoreProduct::create([
                    'examination_record_id'=>$examination->id,
                    'product_status_id'=>$value['product_status_id'],
                    'product_id'=>$value['product_id'],
                    'stock_id'=>$request['stock_id'],
                    'quantity'=>$value['quantity_received'],
                    'expiry_date'=>$value['expiry_date'],
                    'production_date'=>$value['production_date'],
                    'count_unit'=>$value['count_unit'],
                    'purchase_product_id'=>$value['purchase_product_id'],
                ]);
            }
        }
    }

    public function purchaseReturn($request){

        $employee_id = auth()->user()->id;

        $PurchaseReturn = PurchaseReturn::create([
            'user_id'=>$employee_id,
            'purchase_id'=>$request->purchase_id,
            'supplier_id'=>$request->supplier_id,
            'stock_id'=>$request->stock_id,
            'note'=>$request->notes_return
        ]);

        foreach ($request->product as $value){

            if ($value['return_quantity'] > 0 ){
                ReturnProduct::create([
                    'purchase_return_id'=>$PurchaseReturn->id,
                    'product_id'=>$value['product_id'],
                    'quantity'=>$value['return_quantity'],
                    'note'=>$value['note'],
                    'purchase_product_id'=>$value['purchase_product_id'],
                ]);
            }
        }
    }

    public function edit($id)
    {
        try {

            $purchase = Purchase::with(['purchaseProducts.product'=>function($qu){
                $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
            },'store','supplier','examinationRecord.storeProducts','purchaseReturns.returnProducts'])->find($id);

            $productStatuses = ProductStatus::all();

            return $this->sendResponse(['purchase' => $purchase,'productStatuses' => $productStatuses], 'Data exited successfully');

        } catch (\Exception $e) {

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
        $purchase = Purchase::with(['purchaseProducts.product'=>function($qu){
            $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
        },'store','supplier'])->find($id);

        $productStatuses = ProductStatus::all();

        return $this->sendResponse(['purchase' => $purchase,'productStatuses' => $productStatuses], 'Data exited successfully');

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
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'purchase_id' => 'required|integer|exists:purchases,id',
                'stock_id' => 'required|integer|exists:stocks,id',
                'supplier_id' => 'required|integer|exists:suppliers,id',
                'notes_received' => 'nullable|string|min:5',
                'notes_return' => 'nullable|string|min:5',
                'product.*.product_id' => 'required|integer|exists:products,id',
                'product.*.product_status_id' => 'required|integer|exists:product_statuses,id',
                'product.*.quantity_received' => 'required|integer',
                'product.*.return_quantity' => 'required|integer',
                'product.*.count_unit' => 'required|integer',
                'product.*.production_date' => 'required|date',
                'product.*.expiry_date' => 'required|date|after:product.*.production_date',
                'product.*.note' => 'nullable|string|min:3',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $purchase = Purchase::find($id);
            $purchase->examinationRecord->delete();
            $purchase->purchaseReturns->delete();

            if ($request->received > 0){
                $this->storeProduct($request);
            }
            if ($request->return > 0){
                $this->purchaseReturn($request);
            }

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {

    }
}

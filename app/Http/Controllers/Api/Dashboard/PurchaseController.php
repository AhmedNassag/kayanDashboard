<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Employee;
use App\Traits\Message;
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
        $purchases = Purchase::with('category')->with('supplier')->with('product')->with('employee')
            ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        foreach($purchases as $purchase)
        {
            $purchase->setAttribute('added_at',$purchase->created_at->format('Y-m-d'));
        }

        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
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
                'enteredQuanitity' => 'required',
                'refusedQuantity' => 'required',
                'productCase' => 'required',
                'productionDate' => 'required',
                'expirationDate' => 'required',
                'price' => 'required',
                'total' => 'required',
                'category_id' => 'required',
                'supplier_id' => 'required',
                'product_id' => 'required',
                'employee_id' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['enteredQuanitity','refusedQuantity','productCase','productionDate','expirationDate','price','total','category_id','supplier_id','product_id','employee_id']);

            $purchase = Purchase::create($data);

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

            $purchase = Purchase::with('category')->with('supplier')->with('product')->with('employee')->find($id);

            return $this->sendResponse(['purchase' => $purchase], 'Data exited successfully');

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

            $purchase = Purchase::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'enteredQuanitity' => 'required',
                'refusedQuantity' => 'required',
                'productCase' => 'required',
                'productionDate' => 'required',
                'expirationDate' => 'required',
                'price' => 'required',
                'total' => 'required',
                'category_id' => 'required',
                'supplier_id' => 'required',
                'product_id' => 'required',
                'employee_id' => 'required',
            ]);


            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['enteredQuanitity','refusedQuantity','productCase','productionDate','expirationDate','price','total','category_id','supplier_id','product_id','employee_id']);

            $purchase->update($data);

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
            $purchase = Purchase::find($id);
            if ($purchase){
                $purchase->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }


    //start relations functions
    public function getSuppliers()
    {
        $suppliers = Supplier::all();
        return $this->sendResponse(['suppliers' => $suppliers], 'Data exited successfully');
    }

    public function getProducts()
    {
        $products = Product::all();
        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    public function getEmployees()
    {
        $employees = Employee::with('user')->get();
        return $this->sendResponse(['employees' => $employees], 'Data exited successfully');
    }
}

<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PurchaseReturn;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = PurchaseReturn::with(['user','supplier','returnProducts'=>function($qu){
            $qu->with(['product','purchaseProduct']);
            },'store','purchase'=>function($qu){
            $qu->with(['purchaseProducts.product.mainMeasurementUnit','store','supplier']);
            }])->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->orWhereRelation('user','name','like','%'.$request->search.'%')
                        ->orWhere('	note', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('store','name','like','%'.$request->search.'%')
                        ->orWhereRelation('supplier','name','like','%'.$request->search.'%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->supplier_id, function ($q) use ($request) {
                    $q->where('supplier_id', $request->supplier_id);
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
        //
    }

    public function edit($id)
    {
       //
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       //
    }
}

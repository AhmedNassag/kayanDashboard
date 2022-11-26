<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DirectOrders;
use App\Traits\Message;
use Illuminate\Http\Request;

class OrderDeliveredController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders= DirectOrders::where('order_status_id',5)
        ->with(['orderStatus','representative:id,name','orderOtherOffer','user' => function ($q){
            $q->with('client');
        },'orderOffer','orderTax','orderDetails' => function ($q) {
            $q->with(['sellingMethod:id,name',
                'mainMeasurementUnit:id,name',
                'subMeasurementUnit:id,name',
                'product:id,name'
            ]);
        },'store:id,name'])->where(function ($q) use ($request) {
            $q->when($request->search, function ($q) use ($request) {
                return $q->where('id','like','%'.$request->search.'%')
                    ->orWhereRelation('store','name','like','%'.$request->search.'%')
                    ->orWhereRelation('orderStatus','name','like','%'.$request->search.'%')
                    ->orWhereRelation('user','name','like','%'.$request->search.'%');
            });
        })->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', ">=", $request->from_date)
                    ->whereDate('created_at', "<=", $request->to_date);
            });
        })->where(function ($q) use ($request) {
            $q->when($request->order_id, function ($q) use ($request) {
                $q->where('id', $request->order_id);
            });
        })->where(function ($q) use ($request) {
            $q->when( $request->order_type , function ($q) use ($request) {
                $q->where('is_online', $request->order_type == 2 ? 0 : 1 );
            });
        })->latest()->paginate(15);

        return $this->sendResponse(['orders' => $orders], 'Data exited successfully');
    }

    public function show($id){
        $order = DirectOrders::with(['orderStatus','orderOtherOffer','user' => function ($q){
            $q->with('client');
        },'orderOffer','orderTax','orderDetails' => function ($q) {
            $q->with(['sellingMethod:id,name',
                'mainMeasurementUnit:id,name',
                'subMeasurementUnit:id,name',
                'product:id,name'
            ]);
        },'store:id,name'])->find($id);

        return $this->sendResponse(['order' => $order], 'Data exited successfully');
    }
}

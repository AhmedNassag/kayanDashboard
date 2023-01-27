<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Supplier;
use App\Repositories\ShippingRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private $supplierRepository;

    public function __construct(
        SupplierRepository $supplierRepository,
        ShippingRepository $shippingRepository
    ) {
        $this->supplierRepository = $supplierRepository;
        $this->shippingRepository = $shippingRepository;
        $this->middleware('permission:supplier read', ['only' => ['index']]);
        $this->middleware('permission:supplier create', ['only' => ['store']]);
        $this->middleware('permission:supplier edit', ['only' => ['update', 'toggleActivation']]);
        $this->middleware('permission:supplier delete', ['only' => ['delete']]);
    }

    public function store(StoreSupplierRequest $request)
    {
        $request->merge(["active" => 1]);
        return $this->supplierRepository->store($request->input());
    }

    public function update(UpdateSupplierRequest $request)
    {
        return $this->supplierRepository->update($request->input());
    }

    public function delete($id)
    {
        $this->supplierRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->supplierRepository->getPage(request()->page_size, $text);
    }

    public function toggleActivation($id)
    {
        $this->supplierRepository->toggleActivation($id);
    }

    public function activeSupplier()
    {
        $suppliers = Supplier::where('active', 1)->get();
        return $this->sendResponse(['suppliers' => $suppliers], 'Data exited successfully');
    }



       //suppliers dues *************************************************************************************
       public function dues_suppliers(Request $request)
       {
           $refund_allowed_days =Setting::first()->refund_allowed_days;
           $suppliers = Supplier::with(['orders' =>function($q) use($refund_allowed_days){
               $q->where('orders.order_status','Completed')->where('order_suppliers.dues',0)->where('orders.updated_at','<',now()->subDays($refund_allowed_days));
           }])
           ->where(function($q) use($request){
               $q->when($request->search, function ($q) use ($request) {
                   return $q->where('responsible_name', 'like', '%' . $request->search . '%')
                           ->orWhere('name', 'like', '%' . $request->search . '%')
                           ->orWhere('responsible_phone', 'like', '%' . $request->search . '%')
                           ->orWhere('tax_card', 'like', '%' . $request->search . '%')
                           ->orWhere('phone', 'like', '%' . $request->search . '%')
                           ->orWhere('commerical_register', 'like', '%' . $request->search . '%')
                           ->orWhere('address', 'like', '%' . $request->search . '%');
               });
           })
           ->whereHas('orders' , function($q) use($refund_allowed_days){
               $q->where('orders.order_status','Completed')
               ->where('order_suppliers.dues',0)
               ->where('orders.updated_at','<',now()->subDays($refund_allowed_days));
           })
           ->paginate(10);
           return response()->json(['suppliers' => $suppliers],200);
       }


       public function doSupplierDues(Request $request)
       {
           $order = Order::find($request->order_id);
           $refund_allowed_days=Setting::find(1)->refund_allowed_days;
           $supplier=$order->suppliers()->find($request->supplier_id);
           if($order&& $supplier && $order->order_status == 'Completed' && $order->updated_at->addDays($refund_allowed_days) < now() ){
               $order->suppliers()->updateExistingPivot($request->supplier_id,['dues' => 1]);
               return response()->json([],200);
           }
           return response()->json([],404);
       }
}

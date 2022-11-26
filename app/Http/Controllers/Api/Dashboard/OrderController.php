<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MyFatoorahController;
use App\Models\CartItem;
use App\Models\IncomeAndExpense;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\Admin\UpdateOrderNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    use Message;

    //get all orders
    public function index(Request $request)
    {
        $orders = $this->search($request);
        return $this->sendResponse(['orders' => $orders],'Data exited successfully');
    }

    // order details
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $products = $order->products;
        $client = $order->user;
        $client_orders=Order::where('user_id',$client->id)->count();
        // $refund_allowed_days=Setting::find(1)->refund_allowed_days;
        // $refund_allowed = $order->updated_at->addDays($refund_allowed_days) > now();
        // $refund_date = $order->updated_at->addDays($refund_allowed_days)->format('Y-m-d / (H:i)');
        return $this->sendResponse(['order' => $order,'products'=>$products,'refund_date' => "",'refund_allowed'=>"",'order_numbers' => $client_orders,'client' => $client,'client_orders' => $client_orders],'Data exited successfully');
    }




    // search
    protected function search($request)
    {
        return Order::where('order_status','!=','Cart')->
        where(function ($q) use($request) {
            return $q
            ->when($request->payment_status, function ($q) use($request) {
                return $q->where('payment_status', $request->payment_status);
            });
        })
        ->where(function ($q) use($request) {
            return $q
            ->when($request->payment_method, function ($q) use($request) {
                    return $q->where('payment_method', $request->payment_method);
            });
        })
        ->where(function ($q) use($request) {
            return $q
            ->when($request->order_status, function ($q) use($request) {
                if ($request->order_status == 'hold') {
                    return $q->where('hold', 1);
                } else {
                    return $q->where('order_status', $request->order_status);
                }
            });
        })
        ->where(function ($q) use($request) {
            $q->when(is_numeric($request->search), function ($q) use($request) {
                return $q->where('id', $request->search)
                        ->orWhere('total_amount', 'like', '%' . $request->search . '%');
            })->when(!is_numeric($request->search), function ($q) use($request) {
                return $q->where('receiver_address', 'like', '%' . $request->search . '%')
                    ->orWhere('receiver_name', 'like', '%' . $request->search . '%')
                    ->orWhere('receiver_phone', 'like', '%' . $request->search . '%');
            });
        })
        ->orderByDesc('id')->latest()->paginate(10);
    }



    public function updateOrderStatus(Request $request)
    {
        $order=Order::findOrFail($request->id);
        if($order->hold == 1 ){
            $order->update(['hold' => 0]);
        }
        if ($order && $order->payment_status != 'Failed' && ($order->order_status != 'Completed' && $order->order_status != 'Canceled' && $order->order_status != 'Modified' && $order->order_status != 'Refund')) {
            if ($order->order_status == 'Pending') {
                $order->update(['order_status' => 'Processing']);
                // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') is processing now' , " $order->id جاري تجهيز الطلب رقم " );
            } elseif ($order->order_status == 'Processing') {
                $order->update(['order_status' => 'Shipping']);
                // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') is Shipping now' , " $order->id جاري شحن الطلب رقم " );
            } elseif ($order->order_status == 'Shipping') {
                $order->update(['order_status' => 'Completed']);
                // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') Completed' , " $order->id تم تسليم الطلب رقم " );
                if ($order->payment_method == 'Cash') {
                    $order->update(['payment_status' => 'Paid']);
                }
                // $this->putCommissionInIncomes($order->commission,$order->receiver_first_name." ".$order->receiver_last_name,['income_id' =>1,'notes' => "مقابل شراء منتجات. رقم الطلب ($order->id) "]);

                // foreach ($order->vendors()->withTrashed()->get() as $vendor) {
                //     Mail::to($vendor->email)->send(new AfterOrderComplete(__('text.Your order') . $order->id . __('text.get Completed'),$vendor->store_name));
                // }
            }
            $order->save();
            return response()->json(['message' => 'Order Updated Successfully'],200);
        }
    }


    public function holdOrder(Request $request){

        $order=Order::findOrFail($request->id);

        if ($order && $order->payment_status != 'Failed' && ($order->order_status != 'Completed' && $order->order_status != 'Pending' && $order->order_status != 'canceled' && $order->order_status != 'modified'&& $order->order_status != 'refund')) {
            if($order->hold == 0){
                $order->update(['hold' => 1]);
                // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') is holding now' ,"مؤقتا $order->id تم وقف الطلب رقم " );
                return response()->json(['message' => 'Order is Pending'],200);
            }else{
                $order->update(['hold' => 0]);
                // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') is processing now' , " $order->id جاري استكمال الطلب رقم " );
                return response()->json(['message' => 'Order Updated Successfully'],200);
            }

        }
    }

    public function cancelOrder(Request $request){
        $order=Order::findOrFail($request->id);
        // $refund_allowed_days=Setting::find(1)->refund_allowed_days;

        if ($order && $order->payment_status != 'Failed') {
            if($order->order_status == 'Pending' && $order->payment_method == 'Online'&&$order->payment_status=='Unpaid'){
                $this->returnProductToStock($order);
                // $this->notifiy($order->customer,$order->id, 'Order Number ('. $order->id.') canceled' , " $order->id تم الغاء الطلب رقم " );
                $order->delete();
                return response()->json(['message' => 'Order Canceled Successfully','canceled'=>true],200);
            }
            elseif(($order->payment_method == 'Online' && $order->payment_status == 'Paid') || ( $order->order_status == 'Shipping' )){ //|| ($order->order_status == 'Completed' && $order->updated_at->addDays($refund_allowed_days) > now())
                $this->refundOrder($order);
                return response()->json(['message' => 'Order Updated Successfully'],200);
            }
        }
    }

    //cancel order
    public function returnProductToStock($order){
        foreach($order->products()->withTrashed()->get() as $product){
            $expiry_date= $product->expiry_date;
            $product->update(['stock' => $product->stock+$product->pivot->quantity,'expiry_date' => $expiry_date]);
        }
    }


    protected function refundOrder($order)
    {
        $amount =$order->order_status == 'Shipping' ? $order->total_amount - $order->Shipping_cost - $order->weight : $order->total_amount;

        if($order->order_status == 'refund' || !$this->makeRefund($amount,$order->invoice_id))
            return;
        foreach ($order->products()->withTrashed()->get() as $product) {
            $quantity = $product->pivot->quantity;
            $expiry_date= $product->expiry_date;
            $product->update(['stock' => $product->stock + $quantity,'expiry_date' => $expiry_date]);
        }

        // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') returned', " $order->id تم ارجاع الطلب رقم " );
        $order->update(['payment_status' => 'Failed', 'order_status' => 'refund','refund_with_status_Shipping'=>$order->order_status == 'Shipping' ? 1 : 0]);
        // $this->putCommissionInIncomes($order->commission,$order->receiver_first_name." ".$order->receiver_last_name,['expense_id' =>1,'notes' => "مقابل ارجاع منتجات. رقم الطلب ($order->id) "]);
        session()->flash('danger', __('text.Order Refunded Successfully'));
    }

    public function makeRefund($amount,$invoice_id)
    {
        $myfatorah = new MyFatoorahController();
        $postFields=[
            'Key' => $invoice_id,
            'KeyType' => 'InvoiceId',
            'Amount' => $amount
        ];
        $data = $myfatorah->callAPI("/v2/MakeRefund",$postFields);
        if(isset($data->IsSuccess) && $data->IsSuccess){
            return true;
        }
        return false;
    }

    // public function notifiy($user,$order_id,$message_en,$message_ar)
    // {
    //     User::whereAuthId(1)
    //     ->whereRelation('roles.notify','name','update order')
    //     ->each(function ($admin) use($order_id,$message_ar,$message_en){
    //         $admin->notify(new UpdateOrderNotification($order_id,$message_ar,$message_en));
    //     });
    //     $user->notify(new UpdateOrderNotification($order_id,$message_ar,$message_en));
    // }


    // protected function putCommissionInIncomes($amount ,$name,$arr_type)
    // {
    //     IncomeAndExpense::create(array_merge([
    //         'amount' => $amount,
    //         'payment_date' => now()->format('Y-m-d'),
    //         'payer' => $name,
    //         'treasury_id' =>1,
    //     ]
    //     ,$arr_type));
    // }


    // protected function modify_after_collected($order, $sizes)
    // {
    //     $sum_taxes = 0;
    //     $sum_total_amount = 0;
    //     $sum_subtotal = 0;
    //     foreach (collect($sizes)->toArray() as $size_id) {
    //         $size = Size::withTrashed()->find($size_id);
    //         if ($size) {
    //             $order_size=$order->sizes->where('id', $size->id);
    //             $quantity = $order_size->pluck('pivot.quantity')->first();
    //             $price = $order_size->pluck('pivot.price')->first();
    //             $taxes = $order_size->pluck('pivot.tax')->first();
    //             $total_refund_amount = ($quantity * $price) + $taxes;
    //             $vendor_id = $size->color()->withTrashed()->first()->product()->withTrashed()->first()->user_id;
    //             $subtotal_refund = $quantity * $price;
    //             Refund::create([
    //                 'order_id' => $order->id,
    //                 'vendor_id' => $vendor_id,
    //                 'total_refund_amount' => $total_refund_amount,
    //                 'size_id' => $size->id,
    //                 'quantity' => $quantity,
    //                 'price' => $price,
    //                 'taxes' => $taxes,
    //                 'size' => $order_size->pluck('pivot.size')->first(),
    //                 'color' => $order->colors->where('id', $size->color()->withTrashed()->first()->id)->pluck('pivot.color')->first(),
    //                 'subtotal_refund_amount' => $subtotal_refund,
    //             ]);
    //             $size->update(['stock' => $size->stock + $quantity]);

    //             $order->vendors()->updateExistingPivot($vendor_id, [
    //                 'total_amount' => $order->vendors->find($vendor_id)->pivot->total_amount - $total_refund_amount,
    //                 'subtotal' => $order->vendors->find($vendor_id)->pivot->subtotal - $subtotal_refund,
    //                 'taxes' => $order->vendors->find($vendor_id)->pivot->taxes - $taxes,
    //             ]);

    //             $sum_taxes += $taxes;
    //             $sum_total_amount += $total_refund_amount;
    //             $sum_subtotal += $subtotal_refund;
    //             $vendor=User::find($vendor_id);
    //             Mail::to($vendor->email)->send(new AfterOrderComplete(__('text.Your order') . $order->id . __('text.get modified'),$vendor->store_name));

    //         }
    //     }

    //     $order->update(['taxes' => $order->taxes - $sum_taxes, 'subtotal' => $order->subtotal - $sum_subtotal, 'total_amount' => $order->total_amount - $sum_total_amount, 'payment_status' => 'paid', 'order_status' => 'modified']);
    // }



}

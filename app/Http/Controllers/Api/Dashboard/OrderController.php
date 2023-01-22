<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\IncomeAndExpense;
use App\Models\Order;
use App\Models\Price;
use App\Models\Setting;
use App\Models\User;
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
        return $this->sendResponse(['orders' => $orders], 'Data exited successfully');
    }

    // order details
    public function show($id)
    {
        $order = Order::with('representative')->findOrFail($id);
        $products = $order->products;
        $client = $order->user;
        $client_orders = Order::where('user_id', $client->id)->count();
        $refund_allowed_days = Setting::find(1)->refund_allowed_days;
        $refund_allowed = $order->updated_at->addDays($refund_allowed_days) > now();
        $refund_date = $order->updated_at->addDays($refund_allowed_days)->format('Y-m-d / (H:i)');
        return $this->sendResponse(['order' => $order, 'products' => $products, 'refund_date' => $refund_date, 'refund_allowed' => $refund_allowed, 'order_numbers' => $client_orders, 'client' => $client, 'client_orders' => $client_orders], 'Data exited successfully');
    }




    // search
    protected function search($request)
    {
        return Order::with('representative')->where('order_status', '!=', 'Cart')->where(function ($q) use ($request) {
                return $q
                    ->when($request->payment_status, function ($q) use ($request) {
                        return $q->where('payment_status', $request->payment_status);
                    });
            })
            ->where(function ($q) use ($request) {
                return $q
                    ->when($request->payment_method, function ($q) use ($request) {
                        return $q->where('payment_method', $request->payment_method);
                    });
            })
            ->where(function ($q) use ($request) {
                return $q
                    ->when($request->order_status, function ($q) use ($request) {
                        if ($request->order_status == 'hold') {
                            return $q->where('hold', 1);
                        } else {
                            return $q->where('order_status', $request->order_status);
                        }
                    });
            })
            ->where(function ($q) use ($request) {
                $q->when(is_numeric($request->search), function ($q) use ($request) {
                    return $q->where('id', $request->search)
                        ->orWhere('total_amount', 'like', '%' . $request->search . '%');
                })->when(!is_numeric($request->search), function ($q) use ($request) {
                    return $q->where('receiver_address', 'like', '%' . $request->search . '%')
                        ->orWhere('receiver_name', 'like', '%' . $request->search . '%')
                        ->orWhere('receiver_phone', 'like', '%' . $request->search . '%');
                });
            })
            ->orderByDesc('id')->latest()->paginate(10);
    }



    public function updateOrderStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
        if ($order->hold == 1) {
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
                    $this->putCommissionInIncomes($order->total_amount,$order->user->name,['income_id' =>1,'notes' => "مقابل شراء منتجات. رقم الطلب ($order->id) "]);
                }

                // foreach ($order->vendors()->withTrashed()->get() as $vendor) {
                //     Mail::to($vendor->email)->send(new AfterOrderComplete(__('text.Your order') . $order->id . __('text.get Completed'),$vendor->store_name));
                // }
            }
            $order->save();
            return response()->json(['message' => 'Order Updated Successfully'], 200);
        }
    }


    public function holdOrder(Request $request)
    {

        $order = Order::findOrFail($request->id);

        if ($order && $order->payment_status != 'Failed' && ($order->order_status != 'Completed' && $order->order_status != 'Pending' && $order->order_status != 'canceled' && $order->order_status != 'modified' && $order->order_status != 'refund')) {
            if ($order->hold == 0) {
                $order->update(['hold' => 1]);
                // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') is holding now' ,"مؤقتا $order->id تم وقف الطلب رقم " );
                return response()->json(['message' => 'Order is Pending'], 200);
            } else {
                $order->update(['hold' => 0]);
                // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') is processing now' , " $order->id جاري استكمال الطلب رقم " );
                return response()->json(['message' => 'Order Updated Successfully'], 200);
            }
        }
    }

    public function cancelOrder(Request $request)
    {
        $order = Order::findOrFail($request->id);
        return $this->cancel_or_refund_order($order);
    }

    public function cancel_or_refund_order($order)
    {
        $refund_allowed_days_check = $order->updated_at->addDays(Setting::first()->refund_allowed_days) > now();
        if ($order && $order->payment_status != 'Failed') {
            if ((($order->order_status == 'Pending' || $order->order_status == 'Processing') && $order->payment_method == 'Cash') || ($order->payment_method == 'Online' && $order->payment_status == 'Unpaid')) {
                $this->returnProductToStock($order);
                // $this->notifiy($order->customer,$order->id, 'Order Number ('. $order->id.') canceled' , " $order->id تم الغاء الطلب رقم " );
                $order->update(['order_status' => 'Canceled', 'payment_status' => 'Failed']);
                return response()->json(['message' => 'Order Canceled Successfully', 'canceled' => true], 200);
            } elseif (
                ($order->payment_method == 'Online' && $order->payment_status == 'Paid')
                || (($order->order_status == 'Completed' || $order->order_status == 'Shipping') && $order->payment_method == 'Cash' && $refund_allowed_days_check)
                || ($order->order_status == 'Completed' && $refund_allowed_days_check)
            ) {
                $this->refundOrder($order);
                return response()->json(['message' => 'Order Updated Successfully'], 200);
            }
        }
    }

    //cancel order
    public function returnProductToStock($order)
    {
        foreach ($order->products()->get() as $cart_item) {
            $price = Price::where('product_id', $cart_item->product_id)->where('supplier_id', $cart_item->supplier_id)->first();
            $price->update(['quantity' => $cart_item->quantity + $price->quantity]);
        }
    }


    protected function refundOrder($order)
    {
        $amount = $order->order_status == 'Shipping' ? $order->total_amount - $order->shipping_cost : $order->total_amount;
        if ($order->order_status == 'Refund' || ($order->payment_method == 'Online' && !$this->makeRefundRequest($amount, $order->invoice_id)))
            return;
        foreach ($order->products()->get() as $cart_item) {
            $price = Price::where('product_id', $cart_item->product_id)->where('supplier_id', $cart_item->supplier_id)->first();
            $price->update(['quantity' => $cart_item->quantity + $price->quantity]);
        }
        $this->putCommissionInIncomes($amount,$order->user->name,['expense_id' =>1,'notes' => "مقابل ارجاع منتجات. رقم الطلب ($order->id) "]);
        // $this->notifiy($order->customer,$order->id,'Order Number ('. $order->id.') returned', " $order->id تم ارجاع الطلب رقم " );
        $order->update(['payment_status' => 'Failed', 'order_status' => 'Refund', 'refund_amount' => $amount]);
        // session()->flash('danger', __('text.Order Refunded Successfully'));
    }

    public function makeRefundRequest($amount, $invoice_id)
    {
        $postFields = [
            'Key' => $invoice_id,
            'KeyType' => 'InvoiceId',
            'Amount' => $amount
        ];
        $data = $this->callAPI("/v2/MakeRefund", $postFields);
        if (isset($data->IsSuccess) && $data->IsSuccess) {
            return true;
        }
        return false;
    }




    //my fatoraah
    public function callAPI($endpointURL,  $postFields = [], $requestType = 'POST')
    {
        $api_url="https://apitest.myfatoorah.com";
        $key="rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $curl = curl_init($api_url . $endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $key", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
        curl_close($curl);
        $error = $this->handleError($response);
        if ($error || $curlErr) {
            return $error;
        }
        return json_decode($response);
    }


    private function handleError($response)
    {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
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


    public function assignRepresentativeToOrder(Request $request)
    {
        if ($order=Order::find($request->order_id)) {
            if($order->order_status == 'Pending' || $order->order_status == 'Shipping' || $order->order_status == 'Processing'){
                if($request->type == 'cancel')
                $order->update(['representative_id' => null]);
                else
                    $order->update(['representative_id' => $request->rep_id]);
                return response()->json([],200);
            }
        }
        return response()->json([],404);
    }

    public function get_representatives(Request $request)
    {
        $representatives = User::whereHas('representative')->where(function($q) use($request){
            $q->when($request->search,function($q) use($request){
                $q->where('name' , 'like' , "%$request->search%")
                ->orWhere('email' , 'like' , "%$request->search%")
                ->orWhere('phone' , 'like' , "%$request->search%");
            });
        })
        ->latest()
        ->take(10)
        ->get();
        return response()->json(['representatives' => $representatives],200);
    }

    public function representative_complete_order(Request $request)
    {
        $user=$request->user();
        if($order = Order::where('representative_id',$user->id)->where('order_status','!=','Completed')->where('id',$request->order_id)->first()){
            $order->update(['order_status' => 'Completed']);
            return response()->json([],200);
        }
        return response()->json([],404);
    }
    public function representative_refund_order(Request $request)
    {
        $user=$request->user();
        if($order = Order::where('representative_id',$user->id)->where('order_status','!=','Refund')->where('id',$request->order_id)->first()){
            return $this->cancel_or_refund_order($order);
        }
        return response()->json([],404);
    }
    public function representative_orders(Request $request)
    {
        $user = $request->user();
        $orders= Order::with(['representative','products'])
        ->where('representative_id', $user->id)
        ->where(function($q){
            $q->where('order_status', 'Pending')
            ->orWhere('order_status', 'Processing')
            ->orWhere('order_status', 'Shipping');
        })
        ->latest()->get();

        return response()->json(['orders' => $orders]);
    }



    protected function putCommissionInIncomes($amount ,$name,$arr_type)
    {
        IncomeAndExpense::create(array_merge([
            'amount' => $amount,
            'payment_date' => now()->format('Y-m-d'),
            'payer' => $name,
            'treasury_id' =>1,
        ]
        ,$arr_type));
    }

}

<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Client;
use App\Models\Order;
use App\Models\Price;
use App\Models\Supplier;
use App\Models\User;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatisitcsController extends Controller
{
    use NotificationTrait;

    public function get_client_has_cart(Request $request)
    {
        $clients = User::join('orders', 'orders.user_id', 'users.id')
            ->select(['users.*', 'orders.updated_at as order_updated_at'])
            ->where('orders.order_status', 'Cart')
            ->whereRelation('client', 'platform_type', 'WEB')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    $q->where("users.name", "like", "%$request->search%")
                        ->orWhere("users.phone",  "like", "%$request->search%")
                        ->orWhere("users.email",  "like", "%$request->search%");
                });
            })
            ->latest()->paginate(10);

        return response()->json(['clients' => $clients]);
    }
    public function get_client_doesnt_have_orders(Request $request)
    {
        $clients = User::whereDoesntHave('orders')
            ->whereRelation('client', 'platform_type', 'WEB')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    $q->where("name", "like", "%$request->search%")
                        ->orWhere("phone", "like", "%$request->search%")
                        ->orWhere("email", "like", "%$request->search%");
                });
            })
            ->latest()->paginate(10);

        return response()->json(['clients' => $clients]);
    }
    public function web_clients(Request $request)
    {
        $clients_query = User::
        with('client')
        ->withSum(['cart_items as bought_quantity' => function ($query) {
            $query->whereHas('order' , function ($query) {
                $query->whereIn('order_status' , ['Pending','Shipping','Processing','Completed']);
            });
        }], 'quantity')
        ->withSum(['orders as total_amount' => function ($query) {
                $query->whereIn('order_status' , ['Pending','Shipping','Processing','Completed']);
        }], 'total_amount')
        ->whereRelation('client', function($q) use($request){
            $q->where('platform_type', 'like',"%$request->platform%")
                ->where('platform_type', '!=', 'DIRECT_SALE');
        })
        ->where(function ($q) use ($request) {
            $q->when($request->search, function ($q) use ($request) {
                $q->where("name", "like", "%$request->search%")
                    ->orWhere("phone", "like", "%$request->search%")
                    ->orWhere("email", "like", "%$request->search%");
            });
        })
        ->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate("created_at",'>=', $request->from_date)
                ->whereDate("created_at",'<=', $request->to_date);
            });
        });
        if($request->product_filter){
            $clients_query->orderBy(strpos($request->product_filter,'bought') ? 'bought_quantity' : 'total_amount',strpos($request->product_filter,'least') === 0 ? 'asc' :'desc');
        }else{
            $clients_query->latest();
        }
         $clients= $clients_query->paginate($request->pagination ?? 25);

        return response()->json(['clients' => $clients]);
    }
    public function client_details(User $user)
    {
        $user->order_numbers = Order::where('user_id', $user->id)->where('order_status', '!=', 'Cart')->count();
        return response()->json(['client' => $user]);
    }
    public function client_orders(Request $request)
    {
        $orders = Order::with('products')->where('user_id', $request->user_id)->where('order_status', '!=', 'Cart')->latest()->paginate(10);
        return response()->json(['orders' => $orders]);
    }

    public function supplier_details(Supplier $supplier)
    {
        $supplier->order_numbers = Order::where('order_status', '!=', 'Cart')->whereRelation('products', 'supplier_id', $supplier->id)->count();
        return response()->json(['supplier' => $supplier]);
    }
    public function supplier_orders(Request $request)
    {
        $items = DB::table('cart_items')->join('products', 'products.id', 'cart_items.product_id')
            ->select(
                'cart_items.unit_price_for_client',
                'cart_items.unit_price_for_pharmacist',
                'cart_items.discount_percentage',
                'products.nameAr',
                'products.nameEn',
                'cart_items.kayan_discount',
                DB::raw('SUM(total_amount) as total_amount,SUM(quantity) as quantity')
            )
            ->where('supplier_id', $request->user_id)
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    $q->where('products.nameAr', 'like', "%$request->search%")
                        ->orWhere('products.nameEn', 'like', "%$request->search%");
                });
            })
            ->where(function ($q) use ($request) {
                $q->when($request->date_from && $request->date_to, function ($q) use ($request) {
                    $q->whereBetween('cart_items.updated_at', [$request->date_from, $request->date_to]);
                });
            })
            ->whereNotNull('total_amount')
            ->groupBy('unit_price_for_client', 'unit_price_for_pharmacist', 'discount_percentage', 'kayan_discount', 'product_id', 'nameAr', 'nameEn')
            ->latest('cart_items.created_at')
            ->paginate(10);
        return response()->json(['items' => $items], 200);
    }

    public function supplier_products(Request $request)
    {
        $products = Price::with(['logs' => function ($q) {
                $q->latest();
            }, 'product'])->where('supplier_id', $request->user_id)
            ->whereHas('product', function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    $q->where('products.nameAr', 'like', "%$request->search%")
                        ->orWhere('products.nameEn', 'like', "%$request->search%")
                        ->orWhere('prices.pharmacyPrice', 'like', "%$request->search%")
                        ->orWhere('prices.publicPrice', 'like', "%$request->search%")
                        ->orWhere('prices.kayanDiscount', 'like', "%$request->search%")
                        ->orWhere('prices.kayanDiscount', 'like', "%$request->search%")
                        ->orWhere('prices.quantity', 'like', "%$request->search%")
                        ->orWhere('prices.clientDiscount', 'like', "%$request->search%");
                });
            })
            ->latest()
            ->paginate(10);
        return response()->json(['products' => $products], 200);
    }

    public function sendNotificationToAllClients(Request $request)
    {
        // Validator request
        $v = Validator::make($request->all(), [
            'title' => 'required',
            'notification' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
        ]);

        if ($v->fails()) {
            return response()->json($v->errors(), 404);
        }
        //    $product = Product::find($request->product_id);
        $tokens = Client::whereNotNull('firebase_token')->get('firebase_token')->pluck('firebase_token');
        $title = $request->title;
        $body = $request->notification;
        $type = "send notification";
        $image = "";
        if($request->hasFile('image')){
            $image = time().'.'. $request->image->getClientOriginalName();
            // picture move
            $request->image->storeAs('category', $image,'general');
            $image = asset('upload/category/'.$image);
        }
        $response =   $this->notificationAllClient($tokens, $body, $title, $type,$image);

        return response()->json($response, 200);
    }

    public function sendNotificationToClient(Request $request)
    {
        // Validator request
        $request->validate([
            'title' => 'required',
            'notification' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'user_id' => 'required|exists:users,id',
        ]);

        //    $product = Product::find($request->product_id);
        $client = Client::whereNotNull('firebase_token')->where('user_id', $request->user_id)->first();
        if ($client) {
            $image = "";
            if($request->hasFile('image')){
                $image = time().'.'. $request->image->getClientOriginalName();
                // picture move
                $request->image->storeAs('category', $image,'general');
                $image = asset('upload/category/'.$image);
            }
            $title = $request->title;
            $body = $request->notification;
            $type = "send notification";
            $response = $this->notificationAllClient($client->firebase_token, $body, $title, $type,$image);
            return response()->json($response, 200);
        }
    }
}

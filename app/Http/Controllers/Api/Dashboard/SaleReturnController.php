<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SaleReturn;
use App\Traits\Message;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales = SaleReturn::with(['user','client.user','returnProducts'=>function($qu)
        {
            $qu->with(['product','saleProduct']);
        },
        'store','sale'=>function($qu)
        {
            $qu->with(['saleProducts.product.mainMeasurementUnit','store','client.user']);
        }])
        ->where(function ($q) use ($request)
        {
            $q->when($request->search, function ($q) use ($request)
            {
                return
                $q->orWhereRelation('user','name','like','%'.$request->search.'%')
                ->orWhere('	note', 'like', '%' . $request->search . '%')
                ->orWhereRelation('store','name','like','%'.$request->search.'%')
                ->orWhereRelation('client','client.user.name','like','%'.$request->search.'%');
            });
        })
        ->where(function ($q) use ($request)
        {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request)
            {
                $q->whereDate('created_at', ">=", $request->from_date)
                ->whereDate('created_at', "<=", $request->to_date);
            });
        })
        ->where(function ($q) use ($request)
        {
            $q->when($request->client_id, function ($q) use ($request)
            {
                $q->where('client_id', $request->client_id);
            });
        })->latest()->paginate(5);
        return $this->sendResponse(['sales' => $sales], 'Data exited successfully');
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\ProductStore;
use App\Models\Sale;
use App\Models\SaleRecord;
use App\Models\SaleReturn;
use App\Models\StatusProduct;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleRecordController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales = Sale::with(['saleProducts.product'=>function($qu)
        {
            $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
        },
        'store','client.user','saleRecord'=>function($qu)
        {
            $qu->with(['storeProducts','user']);
        },
        'saleReturns.returnProducts'])->where(function ($q) use ($request)
        {
            $q->when($request->search, function ($q) use ($request)
            {
                return
                $q->where('	note', 'like', '%' . $request->search . '%')
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
            $q->when($request->sale_id, function ($q) use ($request)
            {
                $q->where('id', $request->sale_id);
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
        try
        {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(),
            [
                'sale_id'                     => 'required|integer|exists:sales,id',
                'stock_id'                    => 'required|integer|exists:stocks,id',
                'client_id'                   => 'required|integer|exists:clients,id',
                'notes_received'              => 'nullable|string|min:5',
                'notes_return'                => 'nullable|string|min:5',
                'product.*.product_id'        => 'required|integer|exists:products,id',
                'product.*.product_status_id' => 'required|integer',
                'product.*.quantity_received' => 'required|integer',
                'product.*.return_quantity'   => 'required|integer',
                'product.*.note'              => 'nullable|string|min:3',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            if ($request->received > 0)
            {
               $this->storeProduct($request);
            }
            if ($request->return > 0)
            {
               $this->saleReturn($request);
            }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    public function storeProduct($request)
    {
        $employee_id = auth()->user()->id;

        $sale = SaleRecord::create
        ([
            'user_id'=>$employee_id,
            'sale_id'=>$request->sale_id,
            'note'=>$request->notes_received
        ]);

        foreach ($request->product as $value)
        {
            if ($value['quantity_received'] > 0 )
            {
                ProductStore::create
                ([
                    'sale_record_id'    =>$sale->id,
                    'product_status_id' =>$value['product_status_id'],
                    'product_id'        =>$value['product_id'],
                    'stock_id'          =>$request['stock_id'],
                    'quantity'          =>$value['quantity_received'],
                    'sale_product_id'   =>$value['sale_product_id'],
                ]);
            }
        }
    }



    public function saleReturn($request)
    {
        $employee_id = auth()->user()->id;

        $saleReturn = SaleReturn::create
        ([
            'user_id'=>$employee_id,
            'sale_id'=>$request->sale_id,
            'client_id'=>$request->client_id,
            'stock_id'=>$request->stock_id,
            'note'=>$request->notes_return
        ]);

        foreach ($request->product as $value)
        {
            if ($value['return_quantity'] > 0 )
            {
                ProductReturn::create
                ([
                    'sale_return_id'=>$saleReturn->id,
                    'product_id'=>$value['product_id'],
                    'quantity'=>$value['return_quantity'],
                    'note'=>$value['note'],
                    'sale_product_id'=>$value['sale_product_id'],
                ]);
            }
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
        $sale = Sale::with(['saleProducts.product'=>function($qu)
        {
            $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
        },'store','client.user'])->find($id);

        $productStatuses = StatusProduct::all();

        return $this->sendResponse(['sale' => $sale,'productStatuses' => $productStatuses], 'Data exited successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try
        {
            $sale = Sale::with(['saleProducts.product'=>function($qu)
            {
                $qu->with(['mainMeasurementUnit','subMeasurementUnit']);
            },'store','client.user','saleRecord.storeProducts','saleReturns.returnProducts'])->find($id);

            $productStatuses = StatusProduct::all();

            return $this->sendResponse(['sale' => $sale,'productStatuses' => $productStatuses], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
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
        try
        {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'sale_id'                     => 'required|integer|exists:sales,id',
                'stock_id'                    => 'required|integer|exists:stocks,id',
                'client_id'                   => 'required|integer|exists:clients,id',
                'notes_received'              => 'nullable|string|min:5',
                'notes_return'                => 'nullable|string|min:5',
                'product.*.product_id'        => 'required|integer|exists:products,id',
                'product.*.product_status_id' => 'required|integer',
                'product.*.quantity_received' => 'required|integer',
                'product.*.return_quantity'   => 'required|integer',
                'product.*.note'              => 'nullable|string|min:3',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $sale = Sale::find($id);
            $sale->saleRecord->delete();
            $sale->saleReturns->delete();

            if ($request->received > 0)
            {
                $this->storeProduct($request);
            }

            if ($request->return > 0)
            {
                $this->saleReturn($request);
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        }
        catch (\Exception $e)
        {
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
        //
    }
}

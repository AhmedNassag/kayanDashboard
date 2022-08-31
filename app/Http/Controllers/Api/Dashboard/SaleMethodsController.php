<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SaleMethods;
use App\Models\SellingMethod;
use Illuminate\Http\Request;
use App\Traits\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleMethodsController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $saleMethods = SellingMethod::when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['saleMethods' => $saleMethods], 'Data exited successfully');
    }


    public function activationSaleMethod($id)
    {
        $saleMethod = SellingMethod::find($id);

        if ($saleMethod->status == 1) {
            $saleMethod->update([
                "status" => 0
            ]);
        } else {
            $saleMethod->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
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
        // try {
        //     DB::beginTransaction();

        // Validator request
        $v = Validator::make($request->all(), [
            'name' => 'required|unique:sale_methods,name',
        ]);

        if ($v->fails()) {
            return $this->sendError('There is an error in the data', $v->errors());
        }

        $data = $request->only(['name']);
        $saleMethod = SellingMethod::create($data);

        DB::commit();

        return $this->sendResponse([], 'Data exited successfully');

        // } catch (\Exception $e) {
        DB::rollBack();
        return $this->sendError('An error occurred in the system');
        // }
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

            $saleMethod = SellingMethod::find($id);

            return $this->sendResponse(['saleMethod' => $saleMethod], 'Data exited successfully');
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

            $saleMethod = SellingMethod::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name', 'status']);

            $saleMethod->update($data);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $saleMethod = SellingMethod::find($id);
            if ($saleMethod) {

                $saleMethod->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
